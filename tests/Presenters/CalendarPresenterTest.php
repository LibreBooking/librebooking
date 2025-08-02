<?php

require_once(ROOT_DIR . 'Pages/CalendarPage.php');
require_once(ROOT_DIR . 'Presenters/Calendar/CalendarPresenter.php');

class CalendarPresenterTest extends TestBase
{
    /**
     * @var ICommonCalendarPage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var CalendarPresenter
     */
    private $presenter;

    /**
     * @var IReservationViewRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $repository;

    /**
     * @var ICalendarFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $calendarFactory;

    /**
     * @var IScheduleRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $scheduleRepository;

    /**
     * @var FakeUserRepository
     */
    private $userRepository;

    /**
     * @var IResourceService|PHPUnit\Framework\MockObject\MockObject
     */
    private $resourceService;

    /**
     * @var ICalendarSubscriptionService|PHPUnit\Framework\MockObject\MockObject
     */
    private $subscriptionService;

    /**
     * @var FakePrivacyFilter
     */
    private $privacyFilter;

    public function setUp(): void
    {
        parent::setup();

        $this->page = $this->createMock('ICommonCalendarPage');
        $this->repository = $this->createMock('IReservationViewRepository');
        $this->scheduleRepository = $this->createMock('IScheduleRepository');
        $this->userRepository = new FakeUserRepository();
        $this->calendarFactory = $this->createMock('ICalendarFactory');
        $this->resourceService = $this->createMock('IResourceService');
        $this->subscriptionService = $this->createMock('ICalendarSubscriptionService');
        $this->privacyFilter = new FakePrivacyFilter();

        $this->presenter = new CalendarPresenter(
            $this->page,
            $this->repository,
            $this->scheduleRepository,
            $this->userRepository,
            $this->resourceService,
            $this->subscriptionService,
            $this->privacyFilter,
            new SlotLabelFactory()
        );
    }

    public function testBindsDefaultScheduleByMonthWhenNothingSelected()
    {
        $showInaccessible = true;
        $this->fakeConfig->SetKey(ConfigKeys::SCHEDULE_SHOW_INACCESSIBLE_RESOURCES, 'true');

        $userId = $this->fakeUser->UserId;
        $defaultScheduleId = 10;
        $userTimezone = "America/New_York";

        $calendarType = CalendarTypes::Month;

        $startDate = Date::Parse('2011-01-01', 'UTC');
        $endDate = Date::Parse('2011-01-02', 'UTC');
        $summary = 'foo summary';
        $resourceId = 3;
        $fname = 'fname';
        $lname = 'lname';
        $referenceNumber = 'refnum';
        $resourceName = 'resource name';

        //$res = new ScheduleReservation(1, $startDate, $endDate, null, $summary, $resourceId, $userId, $fname, $lname, $referenceNumber, ReservationStatus::Created);
        $res = new ReservationItemView($referenceNumber, $startDate, $endDate, 'resource name', $resourceId, 1, null, null, $summary, null, $fname, $lname, $userId);

        $r1 = new FakeBookableResource(1, 'dude1');
        $r2 = new FakeBookableResource($resourceId, $resourceName);

        $resources = [$r1, $r2];
        /** @var Schedule[] $schedules */
        $schedules = [new Schedule(1, null, false, 2, null), new Schedule($defaultScheduleId, null, true, 3, null),];

        $this->scheduleRepository
            ->expects($this->atLeastOnce())
            ->method('GetAll')
            ->willReturn($schedules);

        $this->resourceService
            ->expects($this->atLeastOnce())
            ->method('GetAllResources')
            ->with($this->equalTo($showInaccessible), $this->equalTo($this->fakeUser))
            ->willReturn($resources);

        $this->resourceService
            ->expects($this->atLeastOnce())
            ->method('GetResourceGroups')
            ->with($this->isNull(), $this->equalTo($this->fakeUser))
            ->willReturn(new ResourceGroupTree());

        $this->page
            ->expects($this->atLeastOnce())
            ->method('GetScheduleId')
            ->willReturn($defaultScheduleId);

        $this->page
            ->expects($this->atLeastOnce())
            ->method('GetResourceId')
            ->willReturn(null);


        $this->page
            ->expects($this->atLeastOnce())
            ->method('GetCalendarType')
            ->willReturn($calendarType);

        $this->page
            ->expects($this->atLeastOnce())
            ->method('SetFirstDay')
            ->with($this->equalTo($schedules[1]->GetWeekdayStart()));

        $details = new CalendarSubscriptionDetails(true);
        $this->subscriptionService->expects($this->once())->method('ForSchedule')->with($this->equalTo($defaultScheduleId))->willReturn($details);

        $this->page->expects($this->atLeastOnce())->method('BindSubscription')->with($this->equalTo($details));

        $calendarFilters = new CalendarFilters($schedules, $resources, $defaultScheduleId, null, new ResourceGroupTree());
        $this->page->expects($this->atLeastOnce())->method('BindFilters')->with($this->equalTo($calendarFilters));

        $this->presenter->PageLoad($this->fakeUser, $userTimezone);
    }

    public function testSkipsReservationsForUnknownResources()
    {
        $res1 = new TestReservationItemView(1, Date::Now(), Date::Now(), 1);
        $res2 = new TestReservationItemView(2, Date::Now(), Date::Now(), 2);
        $b1 = new TestBlackoutItemView(1, Date::Now(), Date::Now(), 1);
        $b2 = new TestBlackoutItemView(2, Date::Now(), Date::Now(), 2);

        $r1 = new FakeBookableResource(1, 'dude1');

        $reservations = [$res1, $res2];
        $blackouts = [$b1, $b2];
        $resources = [$r1];
        $availableSlots = [];

        $actualReservations = CalendarReservation::FromScheduleReservationList($reservations, $blackouts, $availableSlots, $resources, $this->fakeUser);

        $this->assertEquals(2, count($actualReservations));
    }

    public function testGroupsReservationsByResource()
    {
        $start = Date::Now();
        $end = Date::Now()->AddDays(1);

        $r1 = new TestReservationItemView(1, $start, $end, 1);
        $r1->SeriesId = 1;
        $r1->ReferenceNumber = 1;

        $r2 = new TestReservationItemView(2, $start, $end, 2);
        $r2->SeriesId = 1;
        $r2->ReferenceNumber = 2;

        $r3 = new TestReservationItemView(3, $start, $end, 1);
        $r3->SeriesId = 2;
        $r3->ReferenceNumber = 2;

        $reservations = [$r1, $r2, $r3];
        $blackouts = [];
        $resources = [new FakeBookableResource(1, '1'), new FakeBookableResource(2, '2')];

        $calendarReservations = CalendarReservation::FromScheduleReservationList($reservations, $blackouts, [], $resources, $this->fakeUser, true);

        $this->assertEquals(2, count($calendarReservations));
    }

    public function testBindsReservationsAndBlackoutsAndAvailableSlots()
    {
        $resourceId = 1;
        $scheduleId = 2;
        $resources = [];
        $reservations = [];
        $blackouts = [];
        $layout = new FakeScheduleLayout();
        $layout->_Layout[] = [new SchedulePeriod(Date::Now(), Date::Now()->AddHours(1))];

        $this->page
            ->expects($this->atLeastOnce())
            ->method('GetResourceId')
            ->willReturn($resourceId);
        $this->page
            ->expects($this->atLeastOnce())
            ->method('GetScheduleId')
            ->willReturn($scheduleId);
        $this->resourceService
            ->expects($this->atLeastOnce())
            ->method('GetAllResources')
            ->willReturn($resources);
        $this->resourceService
            ->expects($this->atLeastOnce())
            ->method('GetResource')
            ->willReturn(new FakeBookableResource(1));
        $this->repository
            ->expects($this->atLeastOnce())
            ->method('GetReservations')
            ->willReturn($reservations);
        $this->repository
            ->expects($this->atLeastOnce())
            ->method('GetBlackoutsWithin')
            ->willReturn($blackouts);
        $this->scheduleRepository
            ->expects($this->atLeastOnce())
            ->method('GetLayout')
            ->willReturn($layout);

        $this->presenter->ProcessDataRequest('events');
    }
}
