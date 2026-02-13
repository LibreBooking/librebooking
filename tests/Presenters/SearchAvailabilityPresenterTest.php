<?php

require_once(ROOT_DIR . 'Presenters/SearchAvailabilityPresenter.php');

class SearchAvailabilityPresenterTest extends TestBase
{
    /**
     * @var SearchAvailabilityPresenter
     */
    private $presenter;

    /**
     * @var FakeSearchAvailabilityPage
     */
    private $page;

    /**
     * @var FakeResourceService
     */
    private $resourceService;

    /**
     * @var FakeReservationService
     */
    private $reservationService;
    /**
     * @var FakeScheduleService
     */
    private $scheduleService;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeSearchAvailabilityPage();
        $this->resourceService = new FakeResourceService();
        $this->reservationService = new FakeReservationService();
        $this->scheduleService = new FakeScheduleService();

        $this->presenter = new SearchAvailabilityPresenter(
            $this->page,
            $this->fakeUser,
            $this->resourceService,
            $this->reservationService,
            $this->scheduleService
        );

        $this->resourceService->_AllResources = [new TestResourceDto(1, '', true, true, 1), new TestResourceDto(3, '', true, true, 2)];
    }

    public function testWhenNoResourcesSelected()
    {
        $this->page->_Resources = [];
        $this->page->_Hours = 1;
        $this->page->_Minutes = 0;
        $this->page->_Range = 'tomorrow';

        $resourceId = 1;

        $resource = new TestResourceDto($resourceId);
        $this->resourceService->_AllResources = [$resource];

        $tz = $this->fakeUser->Timezone;
        $date = Date::Now()->AddDays(1)->ToTimezone($tz)->GetDate();

        $tooShort1 = $this->GetEmpty($date, '00:00', '00:15');
        $tooShort1a = $this->GetEmpty($date, '00:15', '00:30');
        $oneHour1 = $this->GetEmpty($date, '00:30', '01:00');
        $oneHour2 = $this->GetEmpty($date, '01:00', '02:30');
        $twoHours1 = $this->GetEmpty($date, '16:00', '18:30');
        $twoHours2 = $this->GetEmpty($date, '22:00', '00:00');

        $scheduleLayout = new ScheduleLayout($tz);
        $scheduleLayout->AppendPeriod(Time::Parse('00:00', $tz), Time::Parse('00:15', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('00:15', $tz), Time::Parse('00:30', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('00:30', $tz), Time::Parse('01:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('01:00', $tz), Time::Parse('02:30', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('2:30', $tz), Time::Parse('12:30', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('12:30', $tz), Time::Parse('16:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('16:00', $tz), Time::Parse('18:30', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('18:30', $tz), Time::Parse('19:30', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('19:30', $tz), Time::Parse('20:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('20:00', $tz), Time::Parse('21:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('21:00', $tz), Time::Parse('22:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('22:00', $tz), Time::Parse('00:00', $tz));

        $this->scheduleService->_Layout = $scheduleLayout;
        $this->reservationService->_ReservationsAndBlackouts = [
            new ReservationListItem(new TestBlackoutItemView(1, $date->SetTimeString('20:00'), $date->SetTimeString('21:00'), $resourceId, 1)),
            new ReservationListItem(new TestBlackoutItemView(2, $date->SetTimeString('21:00'), $date->SetTimeString('22:00'), $resourceId, 1)),
            new ReservationListItem(new ReservationItemView('1', $date->SetTimeString('02:30'), $date->SetTimeString('12:30'), '', $resourceId)),
            new ReservationListItem(new ReservationItemView('2', $date->SetTimeString('12:30'), $date->SetTimeString('16:00'), '', $resourceId)),
            new ReservationListItem(new ReservationItemView('3', $date->SetTimeString('18:30'), $date->SetTimeString('19:30'), '', $resourceId)),
        ];

        $this->presenter->SearchAvailability();

        $expectedOpenings = [
            new AvailableOpeningView($resource, $date->SetTimeString('00:00'), $date->SetTimeString('01:00')),
            new AvailableOpeningView($resource, $date->SetTimeString('00:15'), $date->SetTimeString('02:30')),
            new AvailableOpeningView($resource, $date->SetTimeString('00:30'), $date->SetTimeString('02:30')),
            new AvailableOpeningView($resource, $date->SetTimeString('01:00'), $date->SetTimeString('02:30')),
            new AvailableOpeningView($resource, $date->SetTimeString('16:00'), $date->SetTimeString('18:30')),
            new AvailableOpeningView($resource, $date->SetTimeString('22:00'), $date->AddDays(1)->SetTimeString('00:00')),
        ];
        $expectedSearchRange = new DateRange($date->ToTimezone($tz), $date->ToTimezone($tz)->GetDate()->AddDays(1), $tz);

        $this->assertEquals(count($expectedOpenings), count($this->page->_Openings));
        $this->assertEquals($expectedOpenings, $this->page->_Openings);
        $this->assertEquals($expectedSearchRange, $this->reservationService->_LastDateRange);
    }

    public function testWhenThisWeekSelected()
    {
        $resourceId = 1;

        $tz = $this->fakeUser->Timezone;

        $resource = new FakeBookableResource($resourceId);
        $this->resourceService->_AllResources = [$resource];

        Date::_SetNow(Date::Parse('2016-07-09 12:00'));
        $this->page->_Range = 'thisweek';

        $scheduleLayout = new ScheduleLayout($tz);
        $scheduleLayout->AppendBlockedPeriod(Time::Parse('00:00', $tz), Time::Parse('00:00', $tz));
        $this->scheduleService->_Layout = $scheduleLayout;

        $this->presenter->SearchAvailability();

        $expectedSearchRange = new DateRange(Date::Parse('2016-07-03', $this->fakeUser->Timezone), Date::Parse('2016-07-10', $this->fakeUser->Timezone));
        $this->assertEquals($expectedSearchRange, $this->reservationService->_LastDateRange);
    }

    public function testWhenLengthIsLongerThan24Hours()
    {
        $resourceId = 1;

        $tz = $this->fakeUser->Timezone;

        $resource = new TestResourceDto($resourceId);
        $this->resourceService->_AllResources = [$resource];

        Date::_SetNow(Date::Parse('2020-06-07 00:00', $tz));
        $this->page->_Range = 'thisweek';
        $this->page->_Hours = 36;

        $scheduleLayout = new ScheduleLayout($tz);

        for ($hour = 0; $hour < 24; $hour++) {
            $start = $hour == 0 ? '00:00' : "$hour:00";
            $end = $hour + 1;
            $scheduleLayout->AppendPeriod(Time::Parse($start, $tz), Time::Parse("$end:00", $tz), '', DayOfWeek::SUNDAY);
            $scheduleLayout->AppendPeriod(Time::Parse($start, $tz), Time::Parse("$end:00", $tz), '', DayOfWeek::MONDAY);
        }
        $midnight = Time::Parse('00:00', $tz);
        $scheduleLayout->AppendBlockedPeriod($midnight, $midnight, '', DayOfWeek::TUESDAY);
        $scheduleLayout->AppendBlockedPeriod($midnight, $midnight, '', DayOfWeek::WEDNESDAY);
        $scheduleLayout->AppendBlockedPeriod($midnight, $midnight, '', DayOfWeek::THURSDAY);
        $scheduleLayout->AppendBlockedPeriod($midnight, $midnight, '', DayOfWeek::FRIDAY);
        $scheduleLayout->AppendBlockedPeriod($midnight, $midnight, '', DayOfWeek::SATURDAY);

        $this->scheduleService->_Layout = $scheduleLayout;

        $this->presenter->SearchAvailability();

        $this->assertEquals(13, count($this->page->_Openings));
        $this->assertEquals(Date::Parse('2020-06-07 00:00', $tz), $this->page->_Openings[0]->Start());
        $this->assertEquals(Date::Parse('2020-06-08 12:00', $tz), $this->page->_Openings[0]->End());
        $this->assertEquals(Date::Parse('2020-06-07 12:00', $tz), $this->page->_Openings[12]->Start());
        $this->assertEquals(Date::Parse('2020-06-09 00:00', $tz), $this->page->_Openings[12]->End());
    }

    public function testWhenNotAllRepeatingDatesAreOpen()
    {
        $tz = $this->fakeUser->Timezone;
        $date = Date::Now()->AddDays(1)->ToTimezone($tz)->GetDate();
        $terminationDate = $date->AddDays(2);

        $this->page->_Resources = [];
        $this->page->_Hours = 1;
        $this->page->_Minutes = 0;
        $this->page->_Range = 'tomorrow';
        $this->page->_RepeatType = RepeatType::Daily;
        $this->page->_RepeatInterval = 1;
        $this->page->_RepeatTerminationDate = $terminationDate->Format('Y-m-d');

        $resourceId = 1;

        $resource = new TestResourceDto($resourceId);
        $this->resourceService->_AllResources = [$resource];

        $scheduleLayout = new ScheduleLayout($tz);

        for ($hour = 0; $hour < 24; $hour++) {
            $start = $hour == 0 ? '00:00' : "$hour:00";
            $end = $hour + 1;
            $scheduleLayout->AppendPeriod(Time::Parse($start, $tz), Time::Parse("$end:00", $tz));
        }

        $this->scheduleService->_Layout = $scheduleLayout;
        $this->reservationService->_ReservationsAndBlackouts = [
            new ReservationListItem(new TestReservationItemView(
                1,
                $date->AddDays(2)->SetTimeString('0:00'),
                $date->AddDays(3)->SetTimeString('00:00'),
                $resourceId,
                '1'
            )),
        ];

        $this->presenter->SearchAvailability();

        $this->assertEquals(0, count($this->page->_Openings));
    }

    public function testWhenAllRepeatingDatesAreOpen()
    {
        $tz = $this->fakeUser->Timezone;
        $date = Date::Now()->AddDays(1)->ToTimezone($tz)->GetDate();
        $terminationDate = $date->AddDays(2);

        $this->page->_Resources = [];
        $this->page->_Hours = 1;
        $this->page->_Minutes = 0;
        $this->page->_Range = 'tomorrow';
        $this->page->_RepeatType = RepeatType::Daily;
        $this->page->_RepeatInterval = 1;
        $this->page->_RepeatTerminationDate = $terminationDate->Format('Y-m-d');

        $resourceId = 1;

        $resource = new TestResourceDto($resourceId);
        $this->resourceService->_AllResources = [$resource];
        $this->resourceService->_AllResources = [$resource];
        $tz = $this->fakeUser->Timezone;
        $date = Date::Now()->AddDays(1)->ToTimezone($tz)->GetDate();

        $scheduleLayout = new ScheduleLayout($tz);
        $scheduleLayout->AppendPeriod(Time::Parse('00:00', $tz), Time::Parse('00:15', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('00:15', $tz), Time::Parse('00:30', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('00:30', $tz), Time::Parse('01:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('01:00', $tz), Time::Parse('02:30', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('2:30', $tz), Time::Parse('12:30', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('12:30', $tz), Time::Parse('16:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('16:00', $tz), Time::Parse('18:30', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('18:30', $tz), Time::Parse('19:30', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('19:30', $tz), Time::Parse('20:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('20:00', $tz), Time::Parse('21:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('21:00', $tz), Time::Parse('22:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('22:00', $tz), Time::Parse('00:00', $tz));

        $this->scheduleService->_Layout = $scheduleLayout;
        $this->reservationService->_ReservationsAndBlackouts = [
            new ReservationListItem(new TestBlackoutItemView(1, $date->SetTimeString('20:00'), $date->SetTimeString('21:00'), $resourceId, 1)),
            new ReservationListItem(new TestBlackoutItemView(2, $date->SetTimeString('21:00'), $date->SetTimeString('22:00'), $resourceId, 1)),
            new ReservationListItem(new ReservationItemView('1', $date->SetTimeString('02:30'), $date->SetTimeString('12:30'), '', $resourceId)),
            new ReservationListItem(new ReservationItemView('2', $date->SetTimeString('12:30'), $date->SetTimeString('16:00'), '', $resourceId)),
            new ReservationListItem(new ReservationItemView('3', $date->SetTimeString('18:30'), $date->SetTimeString('19:30'), '', $resourceId)),
        ];

        $this->presenter->SearchAvailability();

        $expectedOpenings = [
            new AvailableOpeningView($resource, $date->SetTimeString('00:00'), $date->SetTimeString('01:00')),
            new AvailableOpeningView($resource, $date->SetTimeString('00:15'), $date->SetTimeString('02:30')),
            new AvailableOpeningView($resource, $date->SetTimeString('00:30'), $date->SetTimeString('02:30')),
            new AvailableOpeningView($resource, $date->SetTimeString('01:00'), $date->SetTimeString('02:30')),
            new AvailableOpeningView($resource, $date->SetTimeString('16:00'), $date->SetTimeString('18:30')),
            new AvailableOpeningView($resource, $date->SetTimeString('22:00'), $date->AddDays(1)->SetTimeString('00:00')),
        ];

        $this->assertEquals(count($expectedOpenings), count($this->page->_Openings));
        $this->assertEquals($expectedOpenings, $this->page->_Openings);
    }

    public function testWhenSearchingForSpecificTime()
    {
        $resourceId = 1;

        $tz = $this->fakeUser->Timezone;

        $resource = new TestResourceDto($resourceId);
        $this->resourceService->_AllResources = [$resource];

        $mon = new Date('2019-01-21', $tz);
        $tue = new Date('2019-01-22', $tz);
        $wed = new Date('2019-01-23', $tz);
        $thu = new Date('2019-01-24', $tz);
        $fri = new Date('2019-01-25', $tz);
        $sat = new Date('2019-01-26', $tz);
        $sun = new Date('2019-01-27', $tz);

        Date::_SetNow($tue);
        $this->page->_Range = 'thisweek';
        $this->page->_Specific = true;
        $this->page->_StartTime = '07:00';
        $this->page->_EndTime = '10:00';

        $scheduleLayout = new ScheduleLayout($tz);
        $scheduleLayout->AppendBlockedPeriod(Time::Parse('00:00', $tz), Time::Parse('06:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('06:00', $tz), Time::Parse('07:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('07:00', $tz), Time::Parse('08:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('08:00', $tz), Time::Parse('10:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('10:00', $tz), Time::Parse('14:00', $tz));
        $scheduleLayout->AppendBlockedPeriod(Time::Parse('14:00', $tz), Time::Parse('00:00', $tz));

        $this->scheduleService->_Layout = $scheduleLayout;
        $this->reservationService->_ReservationsAndBlackouts = [
            new ReservationListItem(new TestBlackoutItemView(1, $tue->SetTimeString('06:00'), $tue->SetTimeString('07:30'), $resourceId, 1)),
            new ReservationListItem(new ReservationItemView('1', $tue->SetTimeString('10:00'), $tue->SetTimeString('00:00'), '', $resourceId)),
            new ReservationListItem(new ReservationItemView('2', $wed->SetTimeString('04:00'), $tue->SetTimeString('07:00'), '', $resourceId)),
            new ReservationListItem(new ReservationItemView('3', $wed->SetTimeString('10:00'), $wed->SetTimeString('00:00'), '', $resourceId)),
            new ReservationListItem(new ReservationItemView('4', $thu->SetTimeString('06:00'), $thu->SetTimeString('07:00'), '', $resourceId)),
            new ReservationListItem(new ReservationItemView('5', $thu->SetTimeString('10:00'), $thu->SetTimeString('00:00'), '', $resourceId)),
            new ReservationListItem(new ReservationItemView('6', $fri->SetTimeString('00:00'), $sat->SetTimeString('00:00'), '', $resourceId)),
            new ReservationListItem(new ReservationItemView('7', $sat->SetTimeString('00:00'), $sun->SetTimeString('00:00'), '', $resourceId)),
        ];

        $this->presenter->SearchAvailability();

        $expectedOpenings = [
            new AvailableOpeningView($resource, $wed->SetTimeString('07:00'), $wed->SetTimeString('10:00')),
            new AvailableOpeningView($resource, $thu->SetTimeString('07:00'), $thu->SetTimeString('10:00')),
        ];

        $this->assertEquals($expectedOpenings, $this->page->_Openings);
    }

    public function testWhenResourceAllowsConcurrent()
    {
        $resourceId = 1;

        $tz = $this->fakeUser->Timezone;

        $resource = new TestResourceDto($resourceId);
        $resource->MaxConcurrentReservations = 2;
        $this->resourceService->_AllResources = [$resource];

        $sun = new Date('2019-01-20', $tz);
        $mon = new Date('2019-01-21', $tz);
        $tue = new Date('2019-01-22', $tz);
        $wed = new Date('2019-01-23', $tz);
        $thu = new Date('2019-01-24', $tz);
        $fri = new Date('2019-01-25', $tz);
        $sat = new Date('2019-01-26', $tz);

        Date::_SetNow($tue);
        $this->page->_Range = 'thisweek';
        $this->page->_Specific = true;
        $this->page->_StartTime = '07:00';
        $this->page->_EndTime = '10:00';

        $scheduleLayout = new ScheduleLayout($tz);
        $scheduleLayout->AppendBlockedPeriod(Time::Parse('00:00', $tz), Time::Parse('06:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('06:00', $tz), Time::Parse('07:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('07:00', $tz), Time::Parse('08:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('08:00', $tz), Time::Parse('10:00', $tz));
        $scheduleLayout->AppendPeriod(Time::Parse('10:00', $tz), Time::Parse('14:00', $tz));
        $scheduleLayout->AppendBlockedPeriod(Time::Parse('14:00', $tz), Time::Parse('00:00', $tz));

        $this->scheduleService->_Layout = $scheduleLayout;
        $this->reservationService->_ReservationsAndBlackouts = [
            new ReservationListItem(new TestBlackoutItemView(1, $sun->SetTimeString('06:00'), $sun->SetTimeString('08:00'), $resourceId, 1)),
            new ReservationListItem(new ReservationItemView('1', $mon->SetTimeString('06:00'), $mon->SetTimeString('08:00'), '', $resourceId)),
            new ReservationListItem(new ReservationItemView('2', $tue->SetTimeString('06:00'), $tue->SetTimeString('08:00'), '', $resourceId)),
            new ReservationListItem(new ReservationItemView('3', $wed->SetTimeString('08:00'), $wed->SetTimeString('14:00'), '', $resourceId)),
            new ReservationListItem(new ReservationItemView('4', $fri->SetTimeString('06:00'), $fri->SetTimeString('08:00'), '', $resourceId)),
            new ReservationListItem(new ReservationItemView('5', $fri->SetTimeString('07:00'), $sat->SetTimeString('14:00'), '', $resourceId)),
            new ReservationListItem(new ReservationItemView('6', $sat->SetTimeString('09:00'), $sat->SetTimeString('14:00'), '', $resourceId)),
        ];

        $this->presenter->SearchAvailability();

        $expectedOpenings = [
            new AvailableOpeningView($resource, $tue->SetTimeString('07:00'), $tue->SetTimeString('10:00')),
            new AvailableOpeningView($resource, $wed->SetTimeString('07:00'), $wed->SetTimeString('10:00')),
            new AvailableOpeningView($resource, $thu->SetTimeString('07:00'), $thu->SetTimeString('10:00')),
        ];

        $this->assertEquals($expectedOpenings, $this->page->_Openings);
    }

    /**
     * @param Date $date
     * @param string $start
     * @param string $end
     * @return EmptyReservationSlot
     */
    private function GetEmpty(Date $date, $start, $end)
    {
        return new EmptyReservationSlot(
            new SchedulePeriod($date->SetTimeString($start), $date->SetTimeString($end, true)),
            new SchedulePeriod($date->SetTimeString($start), $date->SetTimeString($end, true)),
            $date,
            true
        );
    }

    /**
     * @param Date $date
     * @param string $start
     * @param string $end
     * @return ReservationSlot
     */
    private function GetReservation(Date $date, $start, $end)
    {
        return new ReservationSlot(
            new SchedulePeriod($date->SetTimeString($start), $date->SetTimeString($end, true)),
            new SchedulePeriod($date->SetTimeString($start), $date->SetTimeString($end, true)),
            $date,
            6,
            new TestReservationItemView(1, Date::Now(), Date::Now())
        );
    }

    /**
     * @param Date $date
     * @param string $start
     * @param string $end
     * @return ReservationSlot|BlackoutSlot
     */
    private function GetBlackout(Date $date, $start, $end)
    {
        return new BlackoutSlot(
            new SchedulePeriod($date->SetTimeString($start), $date->SetTimeString($end, true)),
            new SchedulePeriod($date->SetTimeString($start), $date->SetTimeString($end, true)),
            $date,
            6,
            new TestBlackoutItemView(1, Date::Now(), Date::Now(), 1)
        );
    }
}

class FakeSearchAvailabilityPage extends SearchAvailabilityPage
{
    /**
     * @var int[]
     */
    public $_Resources = [];

    /**
     * @var AvailableOpeningView[]
     */
    public $_Openings;

    /**
     * @var int
     */
    public $_Hours;

    /**
     * @var int
     */
    public $_Minutes;

    /**
     * @var string
     */
    public $_Range;

    /**
     * @var string
     */
    public $_RepeatType = RepeatType::None;

    /**
     * @var int
     */
    public $_RepeatInterval = 1;

    /**
     * @var int[]
     */
    public $_RepeatDays = [];

    /**
     * @var string
     */
    public $_RepeatMonthlyType = RepeatMonthlyType::DayOfMonth;

    /**
     * @var Date[]
     */
    public $_RepeatCustomDates = [];

    public $_RepeatTerminationDate;
    public $_Specific = false;
    public $_StartTime;
    public $_EndTime;

    public function SetResources($resources)
    {
    }

    public function SetResourceTypes($resourceTypes)
    {
    }

    public function ShowOpenings($openings)
    {
        $this->_Openings = $openings;
    }

    public function GetRequestedHours()
    {
        return $this->_Hours;
    }

    public function GetRequestedMinutes()
    {
        return $this->_Minutes;
    }

    public function GetRequestedRange()
    {
        return $this->_Range;
    }

    public function GetRepeatType()
    {
        return $this->_RepeatType;
    }

    public function GetRepeatInterval()
    {
        return $this->_RepeatInterval;
    }

    public function GetRepeatWeekdays()
    {
        return $this->_RepeatDays;
    }

    public function GetRepeatMonthlyType()
    {
        return $this->_RepeatMonthlyType;
    }

    public function GetRepeatTerminationDate()
    {
        return $this->_RepeatTerminationDate;
    }

    public function GetStartTime()
    {
        return $this->_StartTime;
    }

    public function GetEndTime()
    {
        return $this->_EndTime;
    }

    public function SearchingSpecificTime()
    {
        return $this->_Specific;
    }

    public function GetRepeatCustomDates()
    {
        return $this->_RepeatCustomDates;
    }
}
