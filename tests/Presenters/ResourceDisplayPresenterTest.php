<?php

require_once(ROOT_DIR . 'Presenters/ResourceDisplayPresenter.php');
require_once(ROOT_DIR . 'Pages/ResourceDisplayPage.php');

class ResourceDisplayPresenterTest extends TestBase
{
    /**
     * @var FakeGuestUserService
     */
    private $guestUserService;

    /**
     * @var TestResourceDisplayPage
     */
    private $page;

    /**
     * @var FakeResourceRepository
     */
    private $resourceRepository;

    /**
     * @var FakeReservationService
     */
    private $reservationService;

    /**
     * @var ResourceDisplayPresenter
     */
    private $presenter;

    /**
     * @var FakeAuthorizationService
     */
    private $authorizationService;

    /**
     * @var FakeWebAuthentication
     */
    private $authentication;

    /**
     * @var FakeScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @var DailyLayoutFactory
     */
    private $dailyLayoutFactory;

    /**
     * @var FakeReservationHandler
     */
    private $reservationCreateHandler;

    /**
     * @var FakeReservationHandler
     */
    private $reservationCheckinHandler;

    /**
     * @var FakeAttributeService
     */
    private $attributeService;

    /**
     * @var FakeReservationRepository
     */
    private $reservationRepository;

    /**
     * @var FakeTermsOfServiceRepository
     */
    private $termsOfServiceRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new TestResourceDisplayPage();
        $this->resourceRepository = new FakeResourceRepository();
        $this->scheduleRepository = new FakeScheduleRepository();
        $this->reservationService = new FakeReservationService();
        $this->authorizationService = new FakeAuthorizationService();
        $this->authentication = new FakeWebAuthentication();
        $this->dailyLayoutFactory = new DailyLayoutFactory();
        $this->guestUserService = new FakeGuestUserService();
        $this->reservationCreateHandler = new FakeReservationHandler();
        $this->reservationCheckinHandler = new FakeReservationHandler();
        $this->attributeService = new FakeAttributeService();
        $this->reservationRepository = new FakeReservationRepository();
        $this->termsOfServiceRepository = new FakeTermsOfServiceRepository();

        $this->presenter = new ResourceDisplayPresenter(
            $this->page,
            $this->resourceRepository,
            $this->reservationService,
            $this->authorizationService,
            $this->authentication,
            $this->scheduleRepository,
            $this->dailyLayoutFactory,
            $this->guestUserService,
            $this->attributeService,
            $this->reservationRepository,
            $this->termsOfServiceRepository
        );
    }

    public function testDisplaysReservationsIfResourceAllowsDisplay()
    {
        Date::_SetNow(new Date('2016-03-07 11:28', 'UTC'));
        $now = Date::Now();
        $scheduleId = 123;
        $timezone = 'America/Chicago';

        $publicId = 'publicId';
        $resource = new FakeBookableResource(1);
        $resource->EnableSubscription();
        $resource->SetScheduleId($scheduleId);
        $this->resourceRepository->_Resource = $resource;
        $schedule = new FakeSchedule($scheduleId);
        $schedule->SetTimezone($timezone);
        $this->scheduleRepository->_Schedule = $schedule;
        $this->scheduleRepository->_Layout = new ScheduleLayout($timezone);
        $this->scheduleRepository->_Layout->AppendPeriod(Time::Parse('22:00', $timezone), Time::Parse('22:30', $timezone));
        $this->attributeService->_ReservationAttributes = [];

        $this->presenter->reservationCreateHandler = $this->reservationCreateHandler;
        $this->presenter->DisplayResource($publicId, null);
        $expectedDate = DateRange::Create($now->ToTimezone($timezone)->GetDate()->ToUtc(), $now->ToTimezone($timezone)->GetDate()->AddDays(1)->ToUtc(), 'UTC');

        $this->assertEquals(new DailyLayout(
            $this->reservationService->_ReservationListing,
            $this->scheduleRepository->_Layout
        ), $this->page->_DailyLayout);
        $this->assertEquals($expectedDate, $this->reservationService->_LastDateRange);
        $this->assertEquals(1, $this->reservationService->_LastResourceId);
        $this->assertEquals($resource, $this->page->_BoundResource);
        $this->assertFalse($this->page->_DisplayNotEnabledMessage);
        $this->assertEquals($this->attributeService->_ReservationAttributes, $this->page->_Attributes);
    }

    public function testDisplaysUpcomingReservations()
    {
        Date::_SetNow(new Date('2018-03-07 11:28', 'UTC'));
        $now = Date::Now();

        $this->setupStandardExpectations();

        $pastItem = new ReservationListItem(new TestReservationItemView(1, $now->SubtractMinutes(60), $now->SubtractMinutes(30)));
        $currentItem = new ReservationListItem(new TestReservationItemView(2, $now->SubtractMinutes(30), $now->AddMinutes(30)));
        $nextItem = new ReservationListItem(new TestReservationItemView(3, $now->AddMinutes(60), $now->AddMinutes(90)));
        $this->reservationService->_ReservationListing->_Reservations = [
            $pastItem,
            $currentItem,
            $nextItem
        ];

        $this->presenter->DisplayResource(1, null);

        $this->assertEquals([$nextItem], $this->page->_UpcomingReservations);
        $this->assertEquals($nextItem, $this->page->_NextReservation);
    }

    public function testAsksForNextCheckin()
    {
        $now = Date::Now();

        $this->setupStandardExpectations(true, 5);

        $next = new TestReservationItemView(2, $now->AddMinutes(5), $now->AddMinutes(90), 1, "refnum");
        $next->_RequiresCheckin = true;

        $r1 = new TestReservationItemView(1, $now->AddMinutes(-90), $now->AddMinutes(-30), 1, "refnum1");
        $r2 = new TestReservationItemView(3, $now->AddMinutes(90), $now->AddMinutes(120), 1, "refnum2");

        $this->reservationService->_ReservationListing->_Reservations = [
            new ReservationListItem($r1),
            new ReservationListItem($next),
            new ReservationListItem($r2),
        ];

        $this->presenter->DisplayResource(1, null);

        $this->assertEquals(true, $this->page->_RequiresCheckIn);
        $this->assertEquals("refnum", $this->page->_CheckinReferenceNumber);
    }

    public function testDisplaysAvailable()
    {
        $now = Date::Now();

        $this->setupStandardExpectations();

        $pastItem = new ReservationListItem(new TestReservationItemView(1, $now->SubtractMinutes(60), $now->SubtractMinutes(30)));
        $currentItem = new ReservationListItem(new TestReservationItemView(2, $now->SubtractMinutes(30), $now));
        $nextItem = new ReservationListItem(new TestReservationItemView(3, $now->AddMinutes(15), $now->AddMinutes(90)));
        $this->reservationService->_ReservationListing->_Reservations = [
            $pastItem,
            $currentItem,
            $nextItem
        ];

        $this->presenter->DisplayResource(1, null);

        $this->assertEquals(true, $this->page->_AvailableNow);
        $this->assertEquals(false, $this->page->_RequiresCheckIn);
    }

    public function testDisplaysUnavailable()
    {
        $now = Date::Now();

        $this->setupStandardExpectations();

        $pastItem = new ReservationListItem(new TestReservationItemView(1, $now->SubtractMinutes(60), $now->SubtractMinutes(30)));
        $currentItem = new ReservationListItem(new TestReservationItemView(2, $now->SubtractMinutes(30), $now->AddMinutes(15)));
        $nextItem = new ReservationListItem(new TestReservationItemView(3, $now->AddMinutes(60), $now->AddMinutes(90)));
        $this->reservationService->_ReservationListing->_Reservations = [
            $pastItem,
            $currentItem,
            $nextItem
        ];

        $this->presenter->DisplayResource(1, null);

        $this->assertEquals(false, $this->page->_AvailableNow);
        $this->assertEquals($currentItem, $this->page->_CurrentReservation);
    }

    public function testWhenNotEnabledToDisplay()
    {
        $resource = new FakeBookableResource(1);
        $this->resourceRepository->_Resource = $resource;

        $this->presenter->DisplayResource('whatever', null);

        $this->assertTrue($this->page->_DisplayNotEnabledMessage);
    }

    public function testWhenTheLastSlotIsAlreadyPast_GoToTomorrow()
    {
        Date::_SetNow(new Date('2016-03-07 18:28', 'UTC'));
        $now = Date::Now();
        $scheduleId = 123;
        $timezone = 'America/Chicago';

        $publicId = 'publicId';
        $resource = new FakeBookableResource(1);
        $resource->EnableSubscription();
        $resource->SetScheduleId($scheduleId);
        $this->resourceRepository->_Resource = $resource;
        $schedule = new FakeSchedule($scheduleId);
        $schedule->SetTimezone($timezone);
        $this->scheduleRepository->_Schedule = $schedule;
        $this->scheduleRepository->_Layout = new ScheduleLayout($timezone);
        $this->scheduleRepository->_Layout->AppendPeriod(Time::Parse('10:00', $timezone), Time::Parse('10:30', $timezone));
        $this->attributeService->_ReservationAttributes = [];

        $this->presenter->reservationCreateHandler = $this->reservationCreateHandler;
        $this->presenter->DisplayResource($publicId, null);
        $expectedDate = $now->ToTimezone($timezone)->GetDate()->AddDays(1);

        $this->assertEquals(new DailyLayout(
            $this->reservationService->_ReservationListing,
            $this->scheduleRepository->_Layout
        ), $this->page->_DailyLayout);
        $this->assertEquals($expectedDate, $this->page->_Today);
    }

    public function testWhenBookingSucceeds()
    {
        Date::_SetNow(Date::Parse('2016-03-11 13:46'));
        $this->page->_Email = 'some@user.com';
        $this->page->_BeginTime = '08:00';
        $this->page->_EndTime = '17:00';
        $this->page->_BeginDate = '2016-03-11';
        $this->page->_Timezone = 'America/New_York';
        $this->page->_ResourceId = 292;

        $userAccount = new FakeUserSession(123);
        $this->guestUserService->_UserSession = $userAccount;
        $this->reservationCreateHandler->_Success = true;

        $resource = new FakeBookableResource(122);
        $this->resourceRepository->_Resource = $resource;

        $begin = Date::Parse('2016-03-11 08:00', $this->page->_Timezone);
        $end = Date::Parse('2016-03-11 17:00', $this->page->_Timezone);

        $this->presenter->reservationCreateHandler = $this->reservationCreateHandler;

        $this->presenter->Reserve();

        $this->assertEquals(true, $this->page->_ReservationCreatedSuccessfully);
        $this->assertEquals($resource, $this->reservationCreateHandler->_LastSeries->Resource());
        $this->assertEquals($userAccount->UserId, $this->reservationCreateHandler->_LastSeries->UserId());
        $this->assertEquals($userAccount, $this->reservationCreateHandler->_LastSeries->BookedBy());
        $this->assertEquals($begin, $this->reservationCreateHandler->_LastSeries->CurrentInstance()->StartDate());
        $this->assertEquals($end, $this->reservationCreateHandler->_LastSeries->CurrentInstance()->EndDate());
    }

    public function testWhenReservationFails()
    {
        Date::_SetNow(Date::Parse('2016-03-11 13:46'));
        $this->page->_Email = 'some@user.com';
        $this->page->_BeginTime = '08:00';
        $this->page->_EndTime = '17:00';
        $this->page->_BeginDate = '2016-03-11';
        $this->page->_Timezone = 'America/New_York';
        $this->page->_ResourceId = 292;

        $userAccount = new FakeUserSession(123);
        $this->guestUserService->_UserSession = $userAccount;
        $this->reservationCreateHandler->_Success = false;
        $this->reservationCreateHandler->_Errors = ['one', 'two'];

        $resource = new FakeBookableResource(122);
        $this->resourceRepository->_Resource = $resource;

        $this->presenter->reservationCreateHandler = $this->reservationCreateHandler;

        $this->presenter->Reserve();

        $this->assertEquals(false, $this->page->_ReservationCreatedSuccessfully);
        $this->assertEquals($this->reservationCreateHandler->_Errors, $this->page->_ResultCollector->Errors);
    }

    private function setupStandardExpectations($requiresCheckin = false, $checkinMin = 0)
    {
        $timezone = 'America/Chicago';
        $scheduleId = 10;
        $resource = new FakeBookableResource(1);
        $resource->EnableSubscription();
        $resource->SetCheckin($requiresCheckin, $checkinMin);
        $resource->SetScheduleId($scheduleId);
        $this->resourceRepository->_Resource = $resource;
        $schedule = new FakeSchedule($scheduleId);
        $schedule->SetTimezone($timezone);
        $this->scheduleRepository->_Schedule = $schedule;
        $this->scheduleRepository->_Layout = new ScheduleLayout($timezone);
        $this->scheduleRepository->_Layout->AppendPeriod(Time::Parse('22:00', $timezone), Time::Parse('22:30', $timezone));
        $this->attributeService->_ReservationAttributes = [];
    }

    public function testChecksInAnonymously()
    {
        $this->page->_CheckinReferenceNumber = '123';
        $series = new TestHelperExistingReservationSeries();
        $this->reservationRepository->_Series = $series;
        $this->reservationCheckinHandler = new FakeReservationHandler();
        $this->reservationCheckinHandler->_Success = true;
        $this->presenter->reservationCheckinHandler = $this->reservationCheckinHandler;

        $this->presenter->Checkin();

        $this->assertEquals($series, $this->reservationCheckinHandler->_LastSeries);
        $this->assertTrue($series->_WasCheckedIn);
        $this->assertEquals(new NullUserSession(), $series->_CheckedInBy);
        $this->assertEquals($this->reservationCheckinHandler->_Success, $this->page->_ReservationCheckedInSuccessfully);
    }
}

class TestResourceDisplayPage extends FakePageBase implements IResourceDisplayPage
{
    public $_DisplayLoginCalled = false;
    public $_Username;
    public $_Password;
    public $_BoundInvalidLogin = false;
    public $_BoundResourceList = [];
    public $_ResourceId;
    public $_ActivatedResourceId;
    public $_PublicResourceId;
    public $_DailyLayout;
    public $_BoundResource;
    public $_AvailableNow = false;
    public $_DisplayNotEnabledMessage = false;
    public $_Email;
    public $_BeginTime;
    public $_BeginDate;
    public $_EndTime;
    public $_Timezone;
    public $_ReservationCreatedSuccessfully;
    public $_ReservationCheckedInSuccessfully;
    public $_Today;

    /**
     * @var ReservationResultCollector
     */
    public $_ResultCollector;

    public $_Attributes;

    public $_AttributeFormElements = [];
    public $_UpcomingReservations = [];

    /**
     * @var ReservationListItem
     */
    public $_NextReservation;

    /**
     * @var ReservationListItem
     */
    public $_CurrentReservation;
    public $_RequiresCheckIn = false;
    public $_CheckinReferenceNumber;
    public $_TermsOfService;

    public function TakingAction()
    {
        // TODO: Implement TakingAction() method.
    }

    public function GetAction()
    {
        // TODO: Implement GetAction() method.
    }

    public function RequestingData()
    {
        // TODO: Implement RequestingData() method.
    }

    public function GetDataRequest()
    {
        // TODO: Implement GetDataRequest() method.
    }

    public function GetResourceId()
    {
        // TODO: Implement GetResourceId() method.
        return null;
    }

    public function DisplayLogin()
    {
        $this->_DisplayLoginCalled = true;
    }

    public function DisplayResourceList()
    {
        // TODO: Implement DisplayResourceList() method.
    }

    public function DisplayResourceAvailability()
    {
        // TODO: Implement DisplayResourceAvailability() method.
    }

    public function GetEmail()
    {
        return $this->_Username;
    }

    public function GetPassword()
    {
        return $this->_Password;
    }

    public function BindInvalidLogin()
    {
        $this->_BoundInvalidLogin = true;
    }

    public function BindResourceList($resourceList)
    {
        $this->_BoundResourceList = $resourceList;
    }

    public function SetActivatedResourceId($publicId)
    {
        $this->_ActivatedResourceId = $publicId;
    }

    public function GetPublicResourceId()
    {
        return $this->_PublicResourceId;
    }

    public function DisplayAvailability(IDailyLayout $dailyLayout, Date $today, Date $reservationDate, $current, $next, $upcoming, $requiresCheckin, $checkinReferenceNumber)
    {
        $this->_DailyLayout = $dailyLayout;
        $this->_CurrentReservation = $current;
        $this->_NextReservation = $next;
        $this->_UpcomingReservations = $upcoming;
        $this->_RequiresCheckIn = $requiresCheckin;
        $this->_CheckinReferenceNumber = $checkinReferenceNumber;
        $this->_Today = $today;
    }

    public function BindResource(BookableResource $resource)
    {
        $this->_BoundResource = $resource;
    }

    public function SetIsAvailableNow($availableNow)
    {
        $this->_AvailableNow = $availableNow;
    }

    public function DisplayNotEnabled()
    {
        $this->_DisplayNotEnabledMessage = true;
    }

    public function DisplayResourceShell()
    {
        // TODO: Implement DisplayResourceShell() method.
    }

    public function GetTimezone()
    {
        return $this->_Timezone;
    }

    public function GetBeginTime()
    {
        return $this->_BeginTime;
    }

    public function GetBeginDate()
    {
        return $this->_BeginDate;
    }

    public function GetEndTime()
    {
        return $this->_EndTime;
    }

    public function SetReservationSaveResults($success, $resultCollector)
    {
        $this->_ReservationCreatedSuccessfully = $success;
        $this->_ResultCollector = $resultCollector;
    }

    public function SetReservationCheckinResults($success, $resultCollector)
    {
        $this->_ReservationCheckedInSuccessfully = $success;
        $this->_ResultCollector = $resultCollector;
    }

    public function BindSchedule(Schedule $schedule)
    {
        // TODO: Implement BindSchedule() method.
    }

    public function BindAttributes($attributes)
    {
        $this->_Attributes = $attributes;
    }

    public function GetAttributes()
    {
        return $this->_AttributeFormElements;
    }

    public function GetReferenceNumber()
    {
        return $this->_CheckinReferenceNumber;
    }

    public function SetTerms($termsOfService)
    {
        $this->_TermsOfService = $termsOfService;
    }

    /**
     * @return bool
     */
    public function GetTermsOfServiceAcknowledgement()
    {
        // TODO: Implement GetTermsOfServiceAcknowledgement() method.
        return null;
    }

    public function DisplayInstructions()
    {
        // TODO: Implement DisplayInstructions() method.
    }

    public function GetStartDate()
    {
        throw new LogicException('GetStartDate() method not implemented in TestResourceDisplayPage');
    }
}
