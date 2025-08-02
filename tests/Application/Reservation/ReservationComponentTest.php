<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/ReservationInitializerBase.php');

class ReservationComponentTest extends TestBase
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var FakeScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @var IAttributeRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $attributeRepository;

    /**
     * @var IUserRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $userRepository;

    /**
     * @var IResourceService|PHPUnit\Framework\MockObject\MockObject
     */
    private $resourceService;

    /**
     * @var IReservationAuthorization|PHPUnit\Framework\MockObject\MockObject
     */
    private $reservationAuthorization;

    /**
     * @var IReservationComponentInitializer|PHPUnit\Framework\MockObject\MockObject
     */
    private $initializer;

    /**
     * @var ReservationDetailsBinder
     */
    private $reservationDetailsBinder;

    /**
     * @var IExistingReservationPage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var ReservationView
     */
    private $reservationView;

    /**
     * @var IPrivacyFilter|PHPUnit\Framework\MockObject\MockObject
     */
    private $privacyFilter;

    public function setUp(): void
    {
        parent::setup();

        $this->userId = 9999;

        $this->scheduleRepository = new FakeScheduleRepository();
        $this->attributeRepository = $this->createMock('IAttributeRepository');
        $this->userRepository = $this->createMock('IUserRepository');

        $this->resourceService = $this->createMock('IResourceService');
        $this->reservationAuthorization = $this->createMock('IReservationAuthorization');

        $this->initializer = $this->createMock('IReservationComponentInitializer');
        $this->page = $this->createMock('IExistingReservationPage');
        $this->reservationView = new ReservationView();
        $this->privacyFilter = $this->createMock('IPrivacyFilter');

        $this->reservationDetailsBinder = new ReservationDetailsBinder(
            $this->reservationAuthorization,
            $this->page,
            $this->reservationView,
            $this->privacyFilter
        );
    }

    public function testBindsUserData()
    {
        $userDto = new UserDto($this->userId, 'f', 'l', 'email');

        $this->initializer->expects($this->once())
                          ->method('GetOwnerId')
                          ->willReturn($this->userId);

        $this->initializer->expects($this->atLeastOnce())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $this->userRepository->expects($this->once())
                             ->method('GetById')
                             ->with($this->equalTo($this->userId))
                             ->willReturn($userDto);

        $this->reservationAuthorization->expects($this->once())
                                       ->method('CanChangeUsers')
                                       ->with($this->fakeUser)
                                       ->willReturn(true);

        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, 'true');
        $this->initializer->expects($this->once())
                          ->method('SetShowParticipation')
                          ->with($this->equalTo(false));

        $this->initializer->expects($this->once())
                          ->method('SetCanChangeUser')
                          ->with($this->equalTo(true));

        $this->initializer->expects($this->once())
                          ->method('SetReservationUser')
                          ->with($this->equalTo($userDto));

        $binder = new ReservationUserBinder($this->userRepository, $this->reservationAuthorization);
        $binder->Bind($this->initializer);
    }

    public function testBindsResourceData()
    {
        $requestedScheduleId = 10;
        $requestedResourceId = 90;
        $maxResources = 1000;

        $this->initializer->expects($this->atLeastOnce())
                          ->method('GetScheduleId')
                          ->willReturn($requestedScheduleId);

        $this->initializer->expects($this->atLeastOnce())
                          ->method('GetResourceId')
                          ->willReturn($requestedResourceId);

        $this->initializer->expects($this->atLeastOnce())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);


        $bookedResource = new TestResourceDto(
            $requestedResourceId,
            'resource 1',
            true,
            true,
            1,
            TimeInterval::None(),
            null,
            null,
            null,
            1,
            false,
            false,
            false,
            null
        );
        $otherResource = new TestResourceDto(
            2,
            'resource 2',
            true,
            true,
            1,
            TimeInterval::None(),
            null,
            null,
            null,
            1,
            false,
            false,
            false,
            null
        );
        $otherResource2 = new TestResourceDto(
            100,
            'something',
            false,
            true,
            1,
            TimeInterval::None(),
            null,
            null,
            null,
            1,
            false,
            false,
            false,
            null
        );
        $resourceList = [$otherResource, $bookedResource, $otherResource2];

        $groups = new FakeResourceGroupTree();
        $groups->WithAllResources($resourceList);

        $this->scheduleRepository->_Schedule = new FakeSchedule();
        $this->scheduleRepository->_Schedule->SetMaxResourcesPerReservation($maxResources);

        $this->resourceService->expects($this->once())
                              ->method('GetResourceGroups')
                              ->with($this->equalTo($requestedScheduleId), $this->equalTo($this->fakeUser))
                              ->willReturn($groups);

        // accessories
        $accessoryList = [new Accessory(1, 'a1', 30), new Accessory(2, 'a2', 20)];
        $this->resourceService->expects($this->once())
                              ->method('GetAccessories')
                              ->willReturn($accessoryList);

        $this->initializer->expects($this->once())
                          ->method('BindResourceGroups')
                          ->with($this->equalTo($groups));

        $this->initializer->expects($this->once())
                          ->method('ShowAdditionalResources')
                          ->with($this->equalTo(true));

        $this->initializer->expects($this->once())
                          ->method('BindAvailableAccessories')
                          ->with($this->equalTo($accessoryList));

        $this->initializer->expects($this->once())
                          ->method('SetReservationResource')
                          ->with($this->equalTo($bookedResource));

        $this->initializer->expects($this->once())
                          ->method('SetMaximumResources')
                          ->with($this->equalTo($maxResources));

        $binder = new ReservationResourceBinder($this->resourceService, $this->scheduleRepository);
        $binder->Bind($this->initializer);
    }

    public function testRedirectsIfUserHasPermissionToZeroResources()
    {
        $requestedScheduleId = 10;
        $requestedResourceId = null;

        $this->initializer->expects($this->once())
                          ->method('GetScheduleId')
                          ->willReturn($requestedScheduleId);

        $this->initializer->expects($this->once())
                          ->method('GetResourceId')
                          ->willReturn($requestedResourceId);

        $this->initializer->expects($this->once())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $resourceList = [];
        $groups = new FakeResourceGroupTree();
        $groups->WithAllResources($resourceList);

        $this->resourceService->expects($this->once())
                              ->method('GetResourceGroups')
                              ->with($this->equalTo($requestedScheduleId), $this->equalTo($this->fakeUser))
                              ->willReturn($groups);

        $this->initializer->expects($this->once())
                          ->method('RedirectToError')
                          ->with($this->equalTo(ErrorMessages::INSUFFICIENT_PERMISSIONS));

        $binder = new ReservationResourceBinder($this->resourceService, $this->scheduleRepository);
        $binder->Bind($this->initializer);
    }

    public function testBindsDates()
    {
        $timezone = 'UTC';
        $scheduleId = 1;
        $dateString = Date::Now()->AddDays(1)->SetTimeString('02:55:22')->Format('Y-m-d H:i:s');
        $endDateString = Date::Now()->AddDays(1)->SetTimeString('4:55:22')->Format('Y-m-d H:i:s');
        $dateInUserTimezone = Date::Parse($dateString, $timezone);

        $startDate = Date::Parse($dateString, $timezone);
        $endDate = Date::Parse($endDateString, $timezone);

        $availabilityStart = Date::Now()->AddDays(-23);
        $availabilityEnd = Date::Now()->AddDays(23);

        $schedule = new FakeSchedule();
        $schedule->SetAvailability($availabilityStart, $availabilityEnd);

        $resourceDto = new TestResourceDto(1, 'resource', true, true, $scheduleId, null);

        $this->initializer->expects($this->any())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $this->initializer->expects($this->any())
                          ->method('GetTimezone')
                          ->willReturn($timezone);

        $this->initializer->expects($this->any())
                          ->method('GetReservationDate')
                          ->willReturn($dateInUserTimezone);

        $this->initializer->expects($this->any())
                          ->method('GetStartDate')
                          ->willReturn($startDate);

        $this->initializer->expects($this->any())
                          ->method('GetEndDate')
                          ->willReturn($endDate);

        $this->initializer->expects($this->any())
                          ->method('GetScheduleId')
                          ->willReturn($scheduleId);

        $this->initializer->expects($this->any())
                          ->method('PrimaryResource')
                          ->willReturn($resourceDto);

        $startPeriods = [new SchedulePeriod(Date::Now(), Date::Now())];
        $endPeriods = [new SchedulePeriod(Date::Now()->AddDays(1), Date::Now()->AddDays(1))];
        $layout = $this->createMock('IScheduleLayout');

        $this->scheduleRepository->_Layout = $layout;
        $this->scheduleRepository->_Schedule = $schedule;

        $layout->expects($this->exactly(2))
               ->method('GetLayout')
               ->willReturnCallback(function(Date $date, $hideBlockedPeriods) use ($startDate, $endDate, $startPeriods, $endPeriods)
               {
                    if ($date->Equals($startDate) && $hideBlockedPeriods)
                        return $startPeriods;
                    if ($date->Equals($endDate) && $hideBlockedPeriods)
                        return $endPeriods;
                    throw new Exception("Unexpected arguments");
               });

        $this->initializer->expects($this->once())
                          ->method('SetDates')
                          ->with(
                              $this->equalTo($startDate),
                              $this->equalTo($endDate),
                              $this->equalTo($startPeriods),
                              $this->equalTo($endPeriods)
                          );

        $this->initializer->expects($this->once())
            ->method('SetAvailability')
            ->with($this->equalTo($schedule->GetAvailability()));

        $this->initializer->expects($this->once())
                          ->method('HideRecurrence')
                          ->with($this->equalTo(false));

        $binder = new ReservationDateBinder($this->scheduleRepository);
        $binder->Bind($this->initializer);
    }

    public function testBindsDatesWhenResourceHasMinimumTime()
    {
        $timezone = 'UTC';
        $scheduleId = 1;
        $dateString = Date::Now()->AddDays(1)->SetTimeString('02:55:22')->Format('Y-m-d H:i:s');
        $endDateString = Date::Now()->AddDays(1)->SetTimeString('4:55:22')->Format('Y-m-d H:i:s');
        $dateInUserTimezone = Date::Parse($dateString, $timezone);

        $startDate = Date::Parse($dateString, $timezone);
        $endDate = Date::Parse($endDateString, $timezone);

        $expectedEndDate = $startDate->AddHours(2);

        $resourceDto = new TestResourceDto(1, 'resource', true, true, $scheduleId, TimeInterval::FromHours(2));

        $this->initializer->expects($this->any())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $this->initializer->expects($this->any())
                          ->method('GetTimezone')
                          ->willReturn($timezone);

        $this->initializer->expects($this->any())
                          ->method('GetReservationDate')
                          ->willReturn($dateInUserTimezone);

        $this->initializer->expects($this->any())
                          ->method('GetStartDate')
                          ->willReturn($startDate);

        $this->initializer->expects($this->any())
                          ->method('GetEndDate')
                          ->willReturn($endDate);

        $this->initializer->expects($this->any())
                          ->method('GetScheduleId')
                          ->willReturn($scheduleId);

        $this->initializer->expects($this->any())
                          ->method('PrimaryResource')
                          ->willReturn($resourceDto);

        $startPeriods = [new SchedulePeriod(Date::Now(), Date::Now())];
        $endPeriods = [new SchedulePeriod(Date::Now()->AddDays(1), Date::Now()->AddDays(1))];
        $layout = $this->createMock('IScheduleLayout');

        $this->scheduleRepository->_Layout = $layout;
        $this->scheduleRepository->_Schedule = new FakeSchedule();

        $layout->expects($this->exactly(2))
               ->method('GetLayout')
               ->willReturnCallback(function(Date $date) use ($startDate, $endDate, $startPeriods, $endPeriods)
               {
                    if($date->Equals($startDate))
                        return $startPeriods;
                    if($date->Equals($endDate))
                        return $endPeriods;
                    throw new Exception("Unexpeced argument");
               });

        $this->initializer->expects($this->once())
                          ->method('SetDates')
                          ->with(
                              $this->equalTo($startDate),
                              $this->equalTo($expectedEndDate),
                              $this->equalTo($startPeriods),
                              $this->equalTo($endPeriods)
                          );

        $this->initializer->expects($this->once())
                          ->method('HideRecurrence')
                          ->with($this->equalTo(false));

        $this->initializer->expects($this->once())
                          ->method('IsNew')
                          ->willReturn(true);

        $binder = new ReservationDateBinder($this->scheduleRepository);
        $binder->Bind($this->initializer);
    }

    public function testMovesFirstPeriodToEndIfTimeIsLaterInTheDay()
    {
        $timezone = 'UTC';
        $scheduleId = 1;
        $dateString = Date::Now()->AddDays(1)->SetTimeString('02:55:22')->Format('Y-m-d H:i:s');
        $dateInUserTimezone = Date::Parse($dateString, $timezone);

        $requestedDate = Date::Parse($dateString, $timezone);

        $this->initializer->expects($this->any())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $this->initializer->expects($this->any())
                          ->method('GetTimezone')
                          ->willReturn($timezone);

        $this->initializer->expects($this->any())
                          ->method('GetReservationDate')
                          ->willReturn($dateInUserTimezone);

        $this->initializer->expects($this->any())
                          ->method('GetStartDate')
                          ->willReturn($requestedDate);

        $this->initializer->expects($this->any())
                          ->method('GetEndDate')
                          ->willReturn($requestedDate);

        $this->initializer->expects($this->any())
                          ->method('GetScheduleId')
                          ->willReturn($scheduleId);

        $periods = [
                new SchedulePeriod(
                    Date::Parse('2012-01-22 22:00', $timezone),
                    Date::Parse('2012-01-22 10:00', $timezone)
                ),
                new SchedulePeriod(
                    Date::Parse('2012-01-22 10:00', $timezone),
                    Date::Parse('2012-01-23 22:00', $timezone)
                ),
        ];
        $startPeriods = [$periods[1], $periods[0]];
        $endPeriods = [$periods[1], $periods[0]];
        $layout = $this->createMock('IScheduleLayout');

        $this->scheduleRepository->_Layout = $layout;
        $this->scheduleRepository->_Schedule =new FakeSchedule();

        $layout->expects($this->any())
               ->method('GetLayout')
               ->with($this->equalTo($requestedDate))
               ->willReturn($periods);

        $this->initializer->expects($this->once())
                          ->method('SetDates')
                          ->with(
                              $this->equalTo($requestedDate),
                              $this->equalTo($requestedDate),
                              $this->equalTo($startPeriods),
                              $this->equalTo($endPeriods)
                          );

        $binder = new ReservationDateBinder($this->scheduleRepository);
        $binder->Bind($this->initializer);
    }

    public function testBindsReservationDetails()
    {
        $timezone = 'UTC';
        $repeatType = RepeatType::Monthly;
        $repeatInterval = 2;
        $repeatWeekdays = [1, 2, 3];
        $repeatMonthlyType = 'dayOfMonth';
        $repeatTerminationDate = Date::Parse('2010-01-04', 'UTC');

        $title = 'title';
        $description = 'description';

        $firstName = 'fname';
        $lastName = 'lastName';

        $reservationId = 928;
        $resourceId = 10;
        $scheduleId = 100;
        $referenceNumber = '1234';

        $startDateUtc = '2010-01-01 10:11:12';
        $endDateUtc = '2010-01-02 10:11:12';
        $ownerId = 987;
        $additionalResourceIds = [10, 20, 30];
        $participants = [
                new ReservationUserView(10, 'p1', 'l', null, ReservationUserLevel::PARTICIPANT),
                new ReservationUserView(11, 'p2', 'l', null, ReservationUserLevel::PARTICIPANT)
        ];
        $invitees = [
                new ReservationUserView($this->fakeUser->UserId, 'i1', 'l', null, ReservationUserLevel::INVITEE),
                new ReservationUserView(110, 'i2', 'l', null, ReservationUserLevel::INVITEE)
        ];
        $participatingGuests = ['p1@email.com', 'p2@email.com'];
        $invitedGuests = ['i1@email.com', 'i2@email.com'];
        $accessories = [
                new ReservationAccessory(1, 2)
        ];

        $attachments = [
                new ReservationAttachmentView(1, 2, 'filename')
        ];

        $expectedStartDate = Date::Parse($startDateUtc, 'UTC');
        $expectedEndDate = Date::Parse($endDateUtc, 'UTC');

        $startReminderValue = 15;
        $startReminderInterval = ReservationReminderInterval::Minutes;

        $this->reservationView->ReservationId = $reservationId;
        $this->reservationView->ReferenceNumber = $referenceNumber;
        $this->reservationView->ResourceId = $resourceId;
        $this->reservationView->ScheduleId = $scheduleId;
        $this->reservationView->StartDate = $expectedStartDate;
        $this->reservationView->EndDate = $expectedEndDate;
        $this->reservationView->OwnerId = $ownerId;
        $this->reservationView->OwnerFirstName = $firstName;
        $this->reservationView->OwnerLastName = $lastName;
        $this->reservationView->AdditionalResourceIds = $additionalResourceIds;
        $this->reservationView->Participants = $participants;
        $this->reservationView->Invitees = $invitees;
        $this->reservationView->Title = $title;
        $this->reservationView->Description = $description;
        $this->reservationView->RepeatType = $repeatType;
        $this->reservationView->RepeatInterval = $repeatInterval;
        $this->reservationView->RepeatWeekdays = $repeatWeekdays;
        $this->reservationView->RepeatMonthlyType = $repeatMonthlyType;
        $this->reservationView->RepeatTerminationDate = $repeatTerminationDate;
        $this->reservationView->StatusId = ReservationStatus::Pending;
        $this->reservationView->Accessories = $accessories;
        $this->reservationView->Attachments = $attachments;
        $this->reservationView->StartReminder = new ReservationReminderView($startReminderValue);
        $this->reservationView->EndReminder = null;
        $this->reservationView->ParticipatingGuests = $participatingGuests;
        $this->reservationView->InvitedGuests = $invitedGuests;
        $this->reservationView->CustomRepeatDates = [Date::Now()];

        $this->page->expects($this->once())
                   ->method('SetAdditionalResources')
                   ->with($this->equalTo($additionalResourceIds));

        $this->page->expects($this->once())
                   ->method('SetParticipants')
                   ->with($this->equalTo($participants));

        $this->page->expects($this->once())
                   ->method('SetInvitees')
                   ->with($this->equalTo($invitees));

        $this->page->expects($this->once())
             ->method('SetParticipatingGuests')
             ->with($this->equalTo($participatingGuests));

        $this->page->expects($this->once())
             ->method('SetInvitedGuests')
             ->with($this->equalTo($invitedGuests));

        $this->page->expects($this->once())
                   ->method('SetTitle')
                   ->with($this->equalTo($title));

        $this->page->expects($this->once())
                   ->method('SetDescription')
                   ->with($this->equalTo($description));

        $this->page->expects($this->once())
                   ->method('SetRepeatType')
                   ->with($this->equalTo($repeatType));

        $this->page->expects($this->once())
                   ->method('SetRepeatInterval')
                   ->with($this->equalTo($repeatInterval));

        $this->page->expects($this->once())
                   ->method('SetRepeatMonthlyType')
                   ->with($this->equalTo($repeatMonthlyType));

        $this->page->expects($this->any())
                   ->method('SetRepeatTerminationDate')
                   ->with($repeatTerminationDate->ToTimezone($timezone));

        $this->page->expects($this->once())
                   ->method('SetRepeatWeekdays')
                   ->with($this->equalTo($repeatWeekdays));

        $this->page->expects($this->once())
            ->method('SetCustomRepeatDates')
            ->with($this->equalTo($this->reservationView->CustomRepeatDates));

        $this->page->expects($this->once())
                   ->method('SetAccessories')
                   ->with($this->equalTo($accessories));

        $this->page->expects($this->once())
                   ->method('SetAttachments')
                   ->with($this->equalTo($attachments));

        $isEditable = false;

        $this->reservationAuthorization->expects($this->once())
                                       ->method('CanEdit')
                                       ->with($this->equalTo($this->reservationView), $this->equalTo($this->fakeUser))
                                       ->willReturn($isEditable);

        $this->page->expects($this->once())
                   ->method('SetIsEditable')
                   ->with($this->equalTo($isEditable));

        $isApprovable = true;
        $this->reservationAuthorization->expects($this->once())
                                       ->method('CanApprove')
                                       ->with($this->equalTo($this->reservationView), $this->equalTo($this->fakeUser))
                                       ->willReturn($isApprovable);

        $this->page->expects($this->once())
                   ->method('SetIsApprovable')
                   ->with($this->equalTo($isApprovable));

        $isParticipating = false;
        $this->page->expects($this->once())
                   ->method('SetCurrentUserParticipating')
                   ->with($this->equalTo($isParticipating));

        $this->page->expects($this->once())
                   ->method('SetStartReminder')
                   ->with($this->equalTo($startReminderValue), $this->equalTo($startReminderInterval));

        $this->page->expects($this->never())
                   ->method('SetEndReminder');

        $isInvited = true;
        $this->page->expects($this->once())
                   ->method('SetCurrentUserInvited')
                   ->with($this->equalTo($isInvited));

        $this->initializer->expects($this->atLeastOnce())
                          ->method('GetTimezone')
                          ->willReturn($timezone);

        $this->initializer->expects($this->atLeastOnce())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $canViewDetails = true;
        $canViewUser = true;
        $this->privacyFilter->expects($this->once())
                            ->method('CanViewDetails')
                            ->with($this->equalTo($this->fakeUser), $this->equalTo($this->reservationView))
                            ->willReturn($canViewDetails);

        $this->privacyFilter->expects($this->once())
                            ->method('CanViewUser')
                            ->with($this->equalTo($this->fakeUser), $this->equalTo($this->reservationView))
                            ->willReturn($canViewUser);

        $this->initializer->expects($this->once())
                          ->method('ShowUserDetails')
                          ->with($this->equalTo($canViewDetails));

        $this->initializer->expects($this->once())
                          ->method('ShowReservationDetails')
                          ->with($this->equalTo($canViewDetails));

        $this->reservationDetailsBinder->Bind($this->initializer);
    }

    public function testCheckInIsRequiredIfAtLeastOneResourceRequiresIt_And_ReservationIsNotCheckedIn_And_WithinCheckinWindow()
    {
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_CHECKIN_MINUTES_PRIOR, 5);

        $page = new FakeExistingReservationPage();
        $this->reservationView->StartDate = Date::Now()->AddMinutes(4);
        $this->reservationView->EndDate = Date::Now()->AddMinutes(45);

        $this->reservationView->Resources = [
                new ReservationResourceView(1, 'r1', null, null, null, false, null, ResourceStatus::AVAILABLE),
                new ReservationResourceView(2, 'r2', null, null, null, true, 20, ResourceStatus::AVAILABLE),
        ];
        $this->reservationView->CheckinDate = new NullDate();

        $this->initializer->expects($this->any())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $this->reservationAuthorization->expects($this->once())
            ->method('CanEdit')
            ->willReturn(true);

        $this->reservationDetailsBinder = new ReservationDetailsBinder(
            $this->reservationAuthorization,
            $page,
            $this->reservationView,
            $this->privacyFilter
        );
        $this->reservationDetailsBinder->Bind($this->initializer);

        $this->assertTrue($page->_CheckInRequired);
    }

    public function testCheckInNotRequiredIfCheckedIn()
    {
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_CHECKIN_MINUTES_PRIOR, 5);

        $page = new FakeExistingReservationPage();
        $this->reservationView->StartDate = Date::Now()->AddMinutes(4);
        $this->reservationView->EndDate = Date::Now()->AddMinutes(45);

        $this->reservationView->Resources = [
                new ReservationResourceView(1, 'r1', null, null, null, false, null, ResourceStatus::AVAILABLE),
                new ReservationResourceView(2, 'r2', null, null, null, true, 20, ResourceStatus::AVAILABLE),
        ];
        $this->reservationView->CheckinDate = Date::Now();

        $this->initializer->expects($this->any())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $this->reservationDetailsBinder = new ReservationDetailsBinder(
            $this->reservationAuthorization,
            $page,
            $this->reservationView,
            $this->privacyFilter
        );
        $this->reservationDetailsBinder->Bind($this->initializer);

        $this->assertFalse($page->_CheckInRequired);
    }

    public function testCheckInIsNotRequiredIfNoResourceRequiresIt()
    {
        $page = new FakeExistingReservationPage();
        $this->reservationView->StartDate = Date::Now()->AddMinutes(4);
        $this->reservationView->EndDate = Date::Now()->AddMinutes(45);

        $this->reservationView->Resources = [
                new ReservationResourceView(1, 'r1', null, null, null, false, null, ResourceStatus::AVAILABLE),
                new ReservationResourceView(2, 'r2', null, null, null, false, null, ResourceStatus::AVAILABLE),
        ];
        $this->reservationView->CheckinDate = new NullDate();

        $this->initializer->expects($this->any())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $this->reservationDetailsBinder = new ReservationDetailsBinder(
            $this->reservationAuthorization,
            $page,
            $this->reservationView,
            $this->privacyFilter
        );
        $this->reservationDetailsBinder->Bind($this->initializer);

        $this->assertFalse($page->_CheckInRequired);
    }

    public function testCheckInIsNotRequiredIfTooEarly()
    {
        $page = new FakeExistingReservationPage();
        $this->reservationView->StartDate = Date::Now()->AddMinutes(6);
        $this->reservationView->EndDate = Date::Now()->AddMinutes(45);

        $this->reservationView->Resources = [
                new ReservationResourceView(1, 'r1', null, null, null, true, null, ResourceStatus::AVAILABLE),
                new ReservationResourceView(2, 'r2', null, null, null, true, null, ResourceStatus::AVAILABLE),
        ];
        $this->reservationView->CheckinDate = new NullDate();

        $this->initializer->expects($this->any())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $this->reservationDetailsBinder = new ReservationDetailsBinder(
            $this->reservationAuthorization,
            $page,
            $this->reservationView,
            $this->privacyFilter
        );
        $this->reservationDetailsBinder->Bind($this->initializer);

        $this->assertFalse($page->_CheckInRequired);
    }

    public function testCheckOutRequiredIfAtLeastOneResourceRequiresIt_AndTheReservationHasStarted_AndNotCheckedOut()
    {
        $page = new FakeExistingReservationPage();
        $this->reservationView->StartDate = Date::Now()->AddMinutes(-6);
        $this->reservationView->EndDate = Date::Now()->AddMinutes(45);

        $this->reservationView->Resources = [
                new ReservationResourceView(1, 'r1', null, null, null, true, null, ResourceStatus::AVAILABLE),
                new ReservationResourceView(2, 'r2', null, null, null, false, null, ResourceStatus::AVAILABLE),
        ];

        $this->reservationView->CheckinDate = Date::Now();
        $this->reservationView->CheckoutDate = new NullDate();

        $this->initializer->expects($this->any())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $this->reservationAuthorization->expects($this->once())
            ->method('CanEdit')
            ->willReturn(true);

        $this->reservationDetailsBinder = new ReservationDetailsBinder(
            $this->reservationAuthorization,
            $page,
            $this->reservationView,
            $this->privacyFilter
        );
        $this->reservationDetailsBinder->Bind($this->initializer);

        $this->assertTrue($page->_CheckOutRequired);
    }

    public function testCheckOutIsNotRequiredIfNoResourceRequiresIt()
    {
        $page = new FakeExistingReservationPage();
        $this->reservationView->StartDate = Date::Now()->AddMinutes(-6);
        $this->reservationView->EndDate = Date::Now()->AddMinutes(45);

        $this->reservationView->Resources = [
                new ReservationResourceView(1, 'r1', null, null, null, false, null, ResourceStatus::AVAILABLE),
                new ReservationResourceView(2, 'r2', null, null, null, false, null, ResourceStatus::AVAILABLE),
        ];
        $this->reservationView->CheckinDate = Date::Now();

        $this->initializer->expects($this->any())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $this->reservationDetailsBinder = new ReservationDetailsBinder(
            $this->reservationAuthorization,
            $page,
            $this->reservationView,
            $this->privacyFilter
        );
        $this->reservationDetailsBinder->Bind($this->initializer);

        $this->assertFalse($page->_CheckOutRequired);
    }

    public function testCheckOutIsNotRequiredIfAlreadyCheckedOut()
    {
        $page = new FakeExistingReservationPage();
        $this->reservationView->StartDate = Date::Now()->AddMinutes(-6);
        $this->reservationView->EndDate = Date::Now()->AddMinutes(45);

        $this->reservationView->Resources = [
                new ReservationResourceView(1, 'r1', null, null, null, true, null, ResourceStatus::AVAILABLE),
                new ReservationResourceView(2, 'r2', null, null, null, true, null, ResourceStatus::AVAILABLE),
        ];
        $this->reservationView->CheckoutDate = Date::Now();

        $this->initializer->expects($this->any())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $this->reservationDetailsBinder = new ReservationDetailsBinder(
            $this->reservationAuthorization,
            $page,
            $this->reservationView,
            $this->privacyFilter
        );
        $this->reservationDetailsBinder->Bind($this->initializer);

        $this->assertFalse($page->_CheckOutRequired);
    }

    public function testCheckOutIsNotRequiredIfNotCheckedIn()
    {
        $page = new FakeExistingReservationPage();
        $this->reservationView->StartDate = Date::Now()->AddMinutes(-6);
        $this->reservationView->EndDate = Date::Now()->AddMinutes(45);

        $this->reservationView->Resources = [
                new ReservationResourceView(1, 'r1', null, null, null, true, null, ResourceStatus::AVAILABLE),
                new ReservationResourceView(2, 'r2', null, null, null, true, null, ResourceStatus::AVAILABLE),
        ];
        $this->reservationView->CheckinDate = new NullDate();
        $this->reservationView->CheckoutDate = new NullDate();

        $this->initializer->expects($this->any())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $this->reservationDetailsBinder = new ReservationDetailsBinder(
            $this->reservationAuthorization,
            $page,
            $this->reservationView,
            $this->privacyFilter
        );
        $this->reservationDetailsBinder->Bind($this->initializer);

        $this->assertFalse($page->_CheckOutRequired);
    }

    public function testCheckOutIsNotRequiredIfNotStarted()
    {
        $page = new FakeExistingReservationPage();
        $this->reservationView->StartDate = Date::Now()->AddMinutes(6);
        $this->reservationView->EndDate = Date::Now()->AddMinutes(45);

        $this->reservationView->Resources = [
                new ReservationResourceView(1, 'r1', null, null, null, true, null, ResourceStatus::AVAILABLE),
                new ReservationResourceView(2, 'r2', null, null, null, true, null, ResourceStatus::AVAILABLE),
        ];
        $this->reservationView->CheckoutDate = new NullDate();

        $this->initializer->expects($this->any())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $this->reservationDetailsBinder = new ReservationDetailsBinder(
            $this->reservationAuthorization,
            $page,
            $this->reservationView,
            $this->privacyFilter
        );
        $this->reservationDetailsBinder->Bind($this->initializer);

        $this->assertFalse($page->_CheckOutRequired);
    }

    public function testAutoReleaseMinutesIsSetToMinimumAutoReleaseMinutes_IfNotCheckedIn()
    {
        $page = new FakeExistingReservationPage();
        $this->reservationView->StartDate = Date::Now()->AddMinutes(6);
        $this->reservationView->EndDate = Date::Now()->AddMinutes(45);

        $this->reservationView->Resources = [
                new ReservationResourceView(1, 'r1', null, null, null, true, 20, ResourceStatus::AVAILABLE),
                new ReservationResourceView(2, 'r2', null, null, null, true, 10, ResourceStatus::AVAILABLE),
        ];
        $this->reservationView->CheckinDate = new NullDate();

        $this->initializer->expects($this->any())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $this->reservationAuthorization->expects($this->once())
            ->method('CanEdit')
            ->willReturn(true);

        $this->reservationDetailsBinder = new ReservationDetailsBinder(
            $this->reservationAuthorization,
            $page,
            $this->reservationView,
            $this->privacyFilter
        );
        $this->reservationDetailsBinder->Bind($this->initializer);

        $this->assertEquals(10, $page->_AutoReleaseMinutes);
    }

    public function testAutoReleaseMinutesNotSetIfCheckedIn()
    {
        $page = new FakeExistingReservationPage();
        $this->reservationView->StartDate = Date::Now()->AddMinutes(6);
        $this->reservationView->EndDate = Date::Now()->AddMinutes(45);

        $this->reservationView->Resources = [
                new ReservationResourceView(1, 'r1', null, null, null, true, 20, ResourceStatus::AVAILABLE),
                new ReservationResourceView(2, 'r2', null, null, null, true, 10, ResourceStatus::AVAILABLE),
        ];
        $this->reservationView->CheckinDate = Date::Now();

        $this->initializer->expects($this->any())
                          ->method('CurrentUser')
                          ->willReturn($this->fakeUser);

        $this->reservationDetailsBinder = new ReservationDetailsBinder(
            $this->reservationAuthorization,
            $page,
            $this->reservationView,
            $this->privacyFilter
        );
        $this->reservationDetailsBinder->Bind($this->initializer);

        $this->assertEquals(null, $page->_AutoReleaseMinutes);
    }
}
