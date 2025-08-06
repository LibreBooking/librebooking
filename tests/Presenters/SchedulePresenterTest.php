<?php

require_once(ROOT_DIR . 'Presenters/Schedule/SchedulePresenter.php');
require_once(ROOT_DIR . 'Pages/SchedulePage.php');

class SchedulePresenterTest extends TestBase
{
    private $scheduleId;
    private $currentSchedule;
    private $schedules;
    private $numDaysVisible;
    private $showInaccessibleResources = 'true';

    public function setUp(): void
    {
        parent::setup();

        $this->scheduleId = 1;
        $this->numDaysVisible = 10;
        $this->currentSchedule = new Schedule($this->scheduleId, 'default', true, 1, $this->numDaysVisible, 'America/New_York', 1);
        $otherSchedule = new Schedule(2, 'not default', false, 0, $this->numDaysVisible);

        $this->schedules = [$this->currentSchedule, $otherSchedule];
        $this->fakeConfig->SetKey(
            ConfigKeys::SCHEDULE_SHOW_INACCESSIBLE_RESOURCES,
            $this->showInaccessibleResources
        );
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testPageLoadBindsAllSchedulesAndProperResourcesWhenNotPostingBack()
    {
        $user = $this->fakeServer->GetUserSession();
        $resources = [];
        $bindingDates = new DateRange(Date::Now(), Date::Now());
        $groups = new ResourceGroupTree();
        $resourceTypes = [new ResourceType(1, 'n', 'd')];
        $resourceAttributes = [new FakeCustomAttribute()];
        $resourceTypeAttributes = [new FakeCustomAttribute()];

        $page = $this->createMock('ISchedulePage');
        $scheduleService = $this->createMock('IScheduleService');
        $resourceService = $this->createMock('IResourceService');
        $pageBuilder = $this->createMock('ISchedulePageBuilder');
        $reservationService = $this->createMock('IReservationService');
        $dailyLayout = $this->createMock('IDailyLayout');

        $presenter = new SchedulePresenter($page, $scheduleService, $resourceService, $pageBuilder, $reservationService);

        $page
                ->expects($this->once())
                ->method('ShowInaccessibleResources')
                ->willReturn($this->showInaccessibleResources);

        $page
                ->expects($this->once())
                ->method('GetDisplayTimezone')
                ->willReturn($user->Timezone);

        $scheduleService
                ->expects($this->once())
                ->method('GetAll')
                ->with($this->equalTo($this->showInaccessibleResources), $this->equalTo($this->fakeUser))
                ->willReturn($this->schedules);

        $pageBuilder
                ->expects($this->once())
                ->method('GetCurrentSchedule')
                ->with($this->equalTo($page), $this->equalTo($this->schedules), $this->equalTo($user))
                ->willReturn($this->currentSchedule);

        $pageBuilder
                ->expects($this->once())
                ->method('BindSchedules')
                ->with($this->equalTo($page), $this->equalTo($this->schedules), $this->equalTo($this->currentSchedule));

        $resourceFilter = new ScheduleResourceFilter();

        $pageBuilder
                ->expects($this->once())
                ->method('GetResourceFilter')
                ->with($this->equalTo($this->scheduleId), $this->equalTo($page))
                ->willReturn($resourceFilter);

        $pageBuilder
                ->expects($this->once())
                ->method('BindResourceFilter')
                ->with(
                    $this->equalTo($page),
                    $this->equalTo($resourceFilter),
                    $this->equalTo($resourceAttributes),
                    $this->equalTo($resourceTypeAttributes)
                );

        $resourceService
                ->expects($this->once())
                ->method('GetScheduleResources')
                ->with(
                    $this->equalTo($this->scheduleId),
                    $this->equalTo($this->showInaccessibleResources),
                    $this->equalTo($user),
                    $this->equalTo($resourceFilter)
                )
                ->willReturn($resources);

        $resourceService
                ->expects($this->once())
                ->method('GetResourceGroups')
                ->with($this->equalTo($this->scheduleId))
                ->willReturn($groups);

        $pageBuilder
                ->expects($this->once())
                ->method('BindResourceGroups')
                ->with($this->equalTo($page), $this->equalTo($groups));

        $pageBuilder
                ->expects($this->once())
                ->method('GetScheduleDates')
                ->with($this->equalTo($user), $this->equalTo($this->currentSchedule), $this->equalTo($page))
                ->willReturn($bindingDates);

        $pageBuilder
                ->expects($this->once())
                ->method('BindDisplayDates')
                ->with($this->equalTo($page), $this->equalTo($bindingDates), $this->equalTo($this->schedules[0]));

        $scheduleService
                ->expects($this->once())
                ->method('GetDailyLayout')
                ->with($this->equalTo($this->scheduleId), new ScheduleLayoutFactory($user->Timezone), new EmptyReservationListing())
                ->willReturn($dailyLayout);

        $pageBuilder
                ->expects($this->once())
                ->method('BindReservations')
                ->with($this->equalTo($page), $this->equalTo($resources), $this->equalTo($dailyLayout));

        $resourceService
                ->expects($this->once())
                ->method('GetResourceTypes')
                ->willReturn($resourceTypes);

        $pageBuilder
                ->expects($this->once())
                ->method('BindResourceTypes')
                ->with($this->equalTo($page), $this->equalTo($resourceTypes));

        $resourceService
                ->expects($this->once())
                ->method('GetResourceAttributes')
                ->willReturn($resourceAttributes);

        $resourceService
                ->expects($this->once())
                ->method('GetResourceTypeAttributes')
                ->willReturn($resourceTypeAttributes);

        $presenter->PageLoad($this->fakeUser);
    }

    public function testScheduleBuilderBindsAllSchedulesAndSetsActive()
    {
        $activeId = 100;
        $activeName = 'super active';
        $weekdayStart = 4;

        $schedule = new FakeSchedule($activeId, $activeName, true, $weekdayStart);

        $page = $this->createMock('ISchedulePage');

        $page
                ->expects($this->once())
                ->method('SetSchedules')
                ->with($this->equalTo($this->schedules));

        $page
                ->expects($this->once())
                ->method('SetScheduleId')
                ->with($this->equalTo($activeId));

        $page
                ->expects($this->once())
                ->method('SetScheduleName')
                ->with($this->equalTo($activeName));

        $page
                ->expects($this->once())
                ->method('SetFirstWeekday')
                ->with($this->equalTo($weekdayStart));

        $page
                ->expects($this->once())
                ->method('GetScheduleStyle')
                ->with($this->equalTo($activeId))
                ->willReturn(ScheduleStyle::Tall);

        $page
                ->expects($this->once())
                ->method('SetScheduleStyle')
                ->with($this->equalTo(ScheduleStyle::Tall));

        $pageBuilder = new SchedulePageBuilder();
        $pageBuilder->BindSchedules($page, $this->schedules, $schedule);
    }

    public function testScheduleBuilderGetCurrentScheduleReturnsSelectedScheduleOnPostBack()
    {
        $s1 = $this->createMock('ISchedule');
        $s2 = $this->createMock('ISchedule');

        $s1
                ->expects($this->once())
                ->method('GetId')
                ->willReturn(11);

        $s2
                ->expects($this->once())
                ->method('GetId')
                ->willReturn(10);

        $schedules = [$s1, $s2];

        $page = $this->createMock('ISchedulePage');

        $page
                ->expects($this->atLeastOnce())
                ->method('GetScheduleId')
                ->willReturn(10);

        $pageBuilder = new SchedulePageBuilder();
        $actual = $pageBuilder->GetCurrentSchedule($page, $schedules, $this->fakeUser);

        $this->assertEquals($s2, $actual);
    }

    public function testScheduleBuilderGetCurrentScheduleReturnsDefaultScheduleWhenInitialLoad()
    {
        $s1 = new FakeSchedule(1, '', false);
        $s2 = new FakeSchedule(2, '', true);

        $schedules = [$s1, $s2];

        $page = $this->createMock('ISchedulePage');

        $pageBuilder = new SchedulePageBuilder();
        $actual = $pageBuilder->GetCurrentSchedule($page, $schedules, $this->fakeUser);

        $this->assertEquals($s2, $actual);
    }

    public function testSetsDefaultScheduleWhenSetForUserProfile()
    {
        $session = $this->fakeUser;
        $s1 = new FakeSchedule(1, '1', false);
        $s2 = new FakeSchedule(2, '2', true);
        $s3 = new FakeSchedule($session->ScheduleId, 'should be default', false);

        $schedules = [$s1, $s2, $s3];

        $page = $this->createMock('ISchedulePage');

        $pageBuilder = new SchedulePageBuilder();
        $actual = $pageBuilder->GetCurrentSchedule($page, $schedules, $session);

        $this->assertEquals($s3, $actual);
    }

    public function testStartsOnCurrentDateIfShowingLessThanAWeekOfData()
    {
        $timezone = 'America/Chicago';
        $this->fakeConfig->SetTimezone($timezone);

        // saturday
        $currentServerDate = Date::Create(2009, 07, 18, 11, 00, 00, $timezone);
        Date::_SetNow($currentServerDate);

        $startDay = 0;
        $daysVisible = 6;

        // previous sunday
        $expectedStart = Date::Create(2009, 07, 12, 00, 00, 00, $timezone);
        $expectedEnd = $expectedStart->AddDays($daysVisible);
        $expectedScheduleDates = new DateRange($expectedStart, $expectedEnd);

        $user = new UserSession(1);
        $user->Timezone = $timezone;

        $schedule = $this->createMock('ISchedule');
        $schedulePage = $this->createMock('ISchedulePage');

        $schedulePage
                ->expects($this->once())
                ->method('GetSelectedDate')
                ->willReturn(null);

        $schedule
                ->expects($this->once())
                ->method('GetWeekdayStart')
                ->willReturn($startDay);

        $schedule
                ->expects($this->once())
                ->method('GetDaysVisible')
                ->willReturn($daysVisible);

        $pageBuilder = new SchedulePageBuilder();
        $dates = $pageBuilder->GetScheduleDates($user, $schedule, $schedulePage);

        $this->assertEquals($expectedScheduleDates, $dates);
    }

    public function testStartsToday()
    {
        $timezone = 'America/Chicago';
        $this->fakeConfig->SetTimezone($timezone);

        $currentServerDate = Date::Create(2009, 07, 18, 11, 00, 00, $timezone);
        Date::_SetNow($currentServerDate);

        $startDay = Schedule::Today;
        $daysVisible = 6;

        $expectedStart = $currentServerDate->GetDate();
        $expectedEnd = $expectedStart->AddDays($daysVisible);
        $expectedScheduleDates = new DateRange($expectedStart, $expectedEnd);

        $user = new UserSession(1);
        $user->Timezone = $timezone;

        $schedule = $this->createMock('ISchedule');
        $schedulePage = $this->createMock('ISchedulePage');

        $schedulePage
                ->expects($this->once())
                ->method('GetSelectedDate')
                ->willReturn(null);

        $schedule
                ->expects($this->once())
                ->method('GetWeekdayStart')
                ->willReturn($startDay);

        $schedule
                ->expects($this->once())
                ->method('GetDaysVisible')
                ->willReturn($daysVisible);

        $pageBuilder = new SchedulePageBuilder();
        $dates = $pageBuilder->GetScheduleDates($user, $schedule, $schedulePage);

        $this->assertEquals($expectedScheduleDates, $dates);
    }

    public function testGetScheduleDatesStartsOnConfiguredDayOfWeekWhenStartDayIsPriorToToday()
    {
        $timezone = 'CST';

        // saturday
        $currentServerDate = Date::Create(2009, 07, 18, 11, 00, 00, 'CST');
        Date::_SetNow($currentServerDate);

        $startDay = 0;
        $daysVisible = 7;

        // previous sunday
        $expectedStart = Date::Create(2009, 07, 12, 00, 00, 00, $timezone);
        $expectedEnd = $expectedStart->AddDays($daysVisible);
        $expectedScheduleDates = new DateRange($expectedStart, $expectedEnd);

        $user = new UserSession(1);
        $user->Timezone = $timezone;
        $this->fakeConfig->SetTimezone('CST');

        $schedule = $this->createMock('ISchedule');
        $schedulePage = $this->createMock('ISchedulePage');

        $schedulePage
                ->expects($this->once())
                ->method('GetSelectedDate')
                ->willReturn(null);

        $schedule
                ->expects($this->once())
                ->method('GetWeekdayStart')
                ->willReturn($startDay);

        $schedule
                ->expects($this->once())
                ->method('GetDaysVisible')
                ->willReturn($daysVisible);

        $pageBuilder = new SchedulePageBuilder();
        $dates = $pageBuilder->GetScheduleDates($user, $schedule, $schedulePage);

        $this->assertEquals($expectedScheduleDates, $dates);
    }

    public function testGetScheduleDatesStartsOnConfiguredDayOfWeekWhenStartDayIsAfterToday()
    {
        $timezone = 'CST';
        // tuesday
        $currentServerDate = Date::Create(2009, 07, 14, 11, 00, 00, 'CST');
        Date::_SetNow($currentServerDate);

        $startDay = 3;
        $daysVisible = 7;

        // previous wednesday
        $expectedStart = Date::Create(2009, 07, 8, 00, 00, 00, $timezone);
        $expectedEnd = $expectedStart->AddDays($daysVisible);
        $expectedScheduleDates = new DateRange($expectedStart, $expectedEnd);

        $user = new UserSession(1);
        $user->Timezone = $timezone;
        $this->fakeConfig->SetTimezone('CST');

        $schedule = $this->createMock('ISchedule');
        $schedulePage = $this->createMock('ISchedulePage');

        $schedulePage
                ->expects($this->once())
                ->method('GetSelectedDate')
                ->willReturn(null);

        $schedule
                ->expects($this->once())
                ->method('GetWeekdayStart')
                ->willReturn($startDay);

        $schedule
                ->expects($this->once())
                ->method('GetDaysVisible')
                ->willReturn($daysVisible);

        $pageBuilder = new SchedulePageBuilder();
        $dates = $pageBuilder->GetScheduleDates($user, $schedule, $schedulePage);

        //echo $expectedScheduleDates->ToString();
        //echo $utcDates->ToString();

        $this->assertEquals($expectedScheduleDates, $dates);
    }

    public function testCorrectDatesAreUsedWhenTheUsersTimezoneIsAheadByAWeek()
    {
        $timezone = 'EST';
        // saturday
        $currentServerDate = Date::Create(2009, 07, 18, 23, 00, 00, 'CST');
        Date::_SetNow($currentServerDate);

        $startDay = 0;
        $daysVisible = 7;

        // sunday of next week
        $expectedStart = Date::Create(2009, 07, 19, 00, 00, 00, $timezone);
        $expectedEnd = $expectedStart->AddDays($daysVisible);
        $expectedScheduleDates = new DateRange($expectedStart, $expectedEnd);

        $user = new UserSession(1);
        $user->Timezone = $timezone;
        $this->fakeConfig->SetTimezone('CST');

        $schedule = $this->createMock('ISchedule');
        $schedulePage = $this->createMock('ISchedulePage');

        $schedulePage
                ->expects($this->once())
                ->method('GetSelectedDate')
                ->willReturn(null);

        $schedule
                ->expects($this->once())
                ->method('GetWeekdayStart')
                ->willReturn($startDay);

        $schedule
                ->expects($this->once())
                ->method('GetDaysVisible')
                ->willReturn($daysVisible);

        $pageBuilder = new SchedulePageBuilder();
        $dates = $pageBuilder->GetScheduleDates($user, $schedule, $schedulePage);

        //echo $expectedScheduleDates->ToString();
        //echo $utcDates->ToString();

        $this->assertEquals($expectedScheduleDates, $dates);
    }

    public function testCorrectDatesAreUsedWhenTheUsersTimezoneIsBehindByAWeek()
    {
        $timezone = 'PST';
        // sunday
        $currentServerDate = Date::Create(2009, 07, 19, 00, 30, 00, 'CST');
        Date::_SetNow($currentServerDate);

        $startDay = 0;
        $daysVisible = 7;

        // previous sunday
        $expectedStart = Date::Create(2009, 07, 12, 00, 00, 00, $timezone);
        $expectedEnd = $expectedStart->AddDays($daysVisible);
        $expectedScheduleDates = new DateRange($expectedStart, $expectedEnd);

        $user = new UserSession(1);
        $user->Timezone = $timezone;
        $this->fakeConfig->SetTimezone('CST');

        $schedule = $this->createMock('ISchedule');
        $schedulePage = $this->createMock('ISchedulePage');

        $schedulePage
                ->expects($this->once())
                ->method('GetSelectedDate')
                ->willReturn(null);

        $schedule
                ->expects($this->once())
                ->method('GetWeekdayStart')
                ->willReturn($startDay);

        $schedule
                ->expects($this->once())
                ->method('GetDaysVisible')
                ->willReturn($daysVisible);

        $pageBuilder = new SchedulePageBuilder();
        $dates = $pageBuilder->GetScheduleDates($user, $schedule, $schedulePage);

        $this->assertEquals($expectedScheduleDates, $dates);
    }

    public function testProvidedStartDateIsUsedIfSpecified()
    {
        $timezone = 'CST';
        // saturday
        $selectedDate = Date::Create(2009, 07, 18, 00, 00, 00, 'CST');

        $startDay = 0;
        $daysVisible = 7;

        // previous sunday
        $expectedStart = Date::Create(2009, 07, 12, 00, 00, 00, $timezone);
        $expectedEnd = $expectedStart->AddDays($daysVisible);
        $expectedScheduleDates = new DateRange($expectedStart, $expectedEnd);

        $user = new UserSession(1);
        $user->Timezone = $timezone;
        $this->fakeConfig->SetTimezone('CST');

        $schedule = $this->createMock('ISchedule');
        $schedulePage = $this->createMock('ISchedulePage');

        $schedulePage
                ->expects($this->once())
                ->method('GetSelectedDate')
                ->willReturn($selectedDate->Format("Y-m-d"));

        $schedule
                ->expects($this->once())
                ->method('GetWeekdayStart')
                ->willReturn($startDay);

        $schedule
                ->expects($this->once())
                ->method('GetDaysVisible')
                ->willReturn($daysVisible);

        $pageBuilder = new SchedulePageBuilder();
        $dates = $pageBuilder->GetScheduleDates($user, $schedule, $schedulePage);

        //echo $expectedScheduleDates->ToString();
        //echo $utcDates->ToString();

        $this->assertEquals($expectedScheduleDates, $dates);
    }

    public function testBindReservationsSetsReservationsAndResourcesOnPage()
    {
        $page = $this->createMock('ISchedulePage');
        $dailyLayout = $this->createMock('IDailyLayout');
        $resources = [];

        $page
                ->expects($this->once())
                ->method('SetResources')
                ->with($this->equalTo($resources));

        $page
                ->expects($this->once())
                ->method('SetDailyLayout')
                ->with($this->equalTo($dailyLayout));

        $pageBuilder = new SchedulePageBuilder();
        $pageBuilder->BindReservations($page, $resources, $dailyLayout);
    }

    public function testBindDisplayDatesSetsPageToArrayOfDatesInGivenTimezoneForRange()
    {
        $tz = 'America/New_York';
        $daysVisible = 7;
        $schedule = new Schedule(1, '', false, 4, $daysVisible);
        $page = $this->createMock('ISchedulePage');

        $start = Date::Now();
        $end = Date::Now()->AddDays(10);

        $expectedRange = new DateRange($start, $end);

        $expectedPrev = $expectedRange
                ->GetBegin()
                ->AddDays(-$daysVisible);
        $expectedNext = $expectedRange
                ->GetBegin()
                ->AddDays($daysVisible);

        $page
                ->expects($this->once())
                ->method('SetDisplayDates')
                ->with($this->equalTo($expectedRange));

        $page
                ->expects($this->once())
                ->method('SetPreviousNextDates')
                ->with($this->equalTo($expectedPrev), $this->equalTo($expectedNext));

        $pageBuilder = new SchedulePageBuilder();
        $pageBuilder->BindDisplayDates($page, $expectedRange, $schedule);
    }

    public function testPreviousAndNextLinksWhenStartingOnMondayShowingFiveDays()
    {
        $tz = 'America/Chicago';
        $start = Date::Parse('2011-04-04', $tz);
        $end = Date::Parse('2011-04-08', $tz);

        $session = new UserSession(1);
        $session->Timezone = $tz;

        $schedule = new Schedule(1, null, true, 1, 5);

        $expectedPrevious = Date::Parse('2011-03-28', $tz);
        $expectedNext = Date::Parse('2011-04-11', $tz);

        $page = $this->createMock('ISchedulePage');
        $page
                ->expects($this->once())
                ->method('SetPreviousNextDates')
                ->with($this->equalTo($expectedPrevious), $this->equalTo($expectedNext));

        $page
                ->expects($this->once())
                ->method('ShowFullWeekToggle')
                ->with($this->equalTo(true));

        $builder = new SchedulePageBuilder();
        $builder->BindDisplayDates($page, new DateRange($start, $end), $schedule);
    }

    public function testPreviousAndNextLinksWhenStartingOnMondayShowingTenDays()
    {
        $tz = 'America/Chicago';
        $start = Date::Parse('2011-04-04', $tz);
        $end = Date::Parse('2011-04-13', $tz);

        $session = new UserSession(1);
        $session->Timezone = $tz;

        $schedule = new Schedule(1, null, true, 1, 10);

        $expectedPrevious = Date::Parse('2011-03-28', $tz);
        $expectedNext = Date::Parse('2011-04-14', $tz);

        $page = $this->createMock('ISchedulePage');
        $page
                ->expects($this->once())
                ->method('SetPreviousNextDates')
                ->with($this->equalTo($expectedPrevious), $this->equalTo($expectedNext));

        $builder = new SchedulePageBuilder();
        $builder->BindDisplayDates($page, new DateRange($start, $end), $schedule);
    }

    public function testIfCurrentDateIsBeforeAvailability_ShowFirstAvailableMessage()
    {
        $tz = 'America/Chicago';
        $start = Date::Parse('2011-04-04', $tz);
        $end = Date::Parse('2011-04-13', $tz);

        $session = new UserSession(1);
        $session->Timezone = $tz;

        $schedule = new Schedule(1, null, true, 1, 10);
        $schedule->SetAvailability($end->AddDays(20), $end->AddDays(30));

        $page = new FakeSchedulePage();

        $builder = new SchedulePageBuilder();
        $builder->BindDisplayDates($page, new DateRange($start, $end), $schedule);

        $this->assertEquals($page->_ScheduleAvailability, $schedule->GetAvailability());
        $this->assertTrue($page->_ScheduleTooEarly);
    }

    public function testIfCurrentDateIsAfterAvailability_ShowNoLongerAvailableMessage()
    {
        $tz = 'America/Chicago';
        $start = Date::Parse('2011-04-04', $tz);
        $end = Date::Parse('2011-04-13', $tz);

        $session = new UserSession(1);
        $session->Timezone = $tz;

        $schedule = new Schedule(1, null, true, 1, 10);
        $schedule->SetAvailability($start->AddDays(-30), $start->AddDays(-20));

        $page = new FakeSchedulePage();

        $builder = new SchedulePageBuilder();
        $builder->BindDisplayDates($page, new DateRange($start, $end), $schedule);

        $this->assertEquals($page->_ScheduleAvailability, $schedule->GetAvailability());
        $this->assertTrue($page->_ScheduleTooLate);
    }

    public function testShowsSevenDaysIfWeAreShowingFullWeek()
    {
        $timezone = 'America/Chicago';
        // saturday
        $selectedDate = Date::Parse('2009-07-18', $timezone);

        $startDay = 0;
        $daysVisible = 100;

        // previous sunday
        $expectedStart = Date::Parse('2009-07-12', $timezone);
        $expectedEnd = $expectedStart->AddDays(7);
        $expectedScheduleDates = new DateRange($expectedStart, $expectedEnd);

        $user = new UserSession(1);
        $user->Timezone = $timezone;
        $this->fakeConfig->SetTimezone($timezone);

        $schedule = $this->createMock('ISchedule');
        $schedulePage = $this->createMock('ISchedulePage');

        $schedulePage
                ->expects($this->once())
                ->method('GetSelectedDate')
                ->willReturn($selectedDate->Format("Y-m-d"));

        $schedulePage
                ->expects($this->once())
                ->method('GetShowFullWeek')
                ->willReturn(true);

        $schedule
                ->expects($this->once())
                ->method('GetWeekdayStart')
                ->willReturn($startDay);

        $schedule
                ->expects($this->once())
                ->method('GetDaysVisible')
                ->willReturn($daysVisible);

        $pageBuilder = new SchedulePageBuilder();
        $dates = $pageBuilder->GetScheduleDates($user, $schedule, $schedulePage);

        $this->assertEquals($expectedScheduleDates, $dates);
    }

    public function testCanGetLayoutForScheduleOnDate()
    {
        $user = $this->fakeUser;
        $page = $this->createMock('ISchedulePage');
        $scheduleService = $this->createMock('IScheduleService');
        $resourceService = $this->createMock('IResourceService');
        $pageBuilder = $this->createMock('ISchedulePageBuilder');
        $reservationService = $this->createMock('IReservationService');

        $layout = $this->createMock('IScheduleLayout');

        $dateString = '2013-01-07';
        $date = Date::Parse($dateString, $user->Timezone);

        $periods = [];
        $scheduleId = 1928;

        $expectedLayoutResponse = new ScheduleLayoutSerializable($periods);

        $page
                ->expects($this->once())
                ->method('GetScheduleId')
                ->willReturn($scheduleId);

        $page
                ->expects($this->once())
                ->method('GetLayoutDate')
                ->willReturn($dateString);

        $scheduleService
                ->expects($this->once())
                ->method('GetLayout')
                ->with($this->equalTo($scheduleId), $this->equalTo(new ScheduleLayoutFactory($user->Timezone)))
                ->willReturn($layout);

        $layout
                ->expects($this->once())
                ->method('GetLayout')
                ->with($this->equalTo($date))
                ->willReturn($periods);

        $page
                ->expects($this->once())
                ->method('SetLayoutResponse')
                ->with($this->equalTo($expectedLayoutResponse));

        $presenter = new SchedulePresenter($page, $scheduleService, $resourceService, $pageBuilder, $reservationService);

        $presenter->GetLayout($user);
    }

    public function testGetsResourceFilterWhenSubmitted()
    {
        $scheduleId = 1;
        $page = new FakeSchedulePage();
        $page->_FilterSubmitted = true;
        $page->_ResourceTypeId = 1;
        $page->_MaxParticipants = 10;
        $page->_ResourceAttributes = [new AttributeFormElement(2, 2)];
        $page->_ResourceTypeAttributes = [new AttributeFormElement(1, 1)];
        $page->_ResourceIds = [1, 2, 3];
        $builder = new SchedulePageBuilder();

        $filter = $builder->GetResourceFilter($scheduleId, $page);

        $this->assertEquals($page->_ResourceIds, $filter->ResourceIds);
        $this->assertEquals([new AttributeValue(2, 2)], $filter->ResourceAttributes);
        $this->assertEquals([new AttributeValue(1, 1)], $filter->ResourceTypeAttributes);
    }

    public function testGetsResourceFilterFromCookie()
    {
        $scheduleId = 1;
        $page = new FakeSchedulePage();

        $filter = new ScheduleResourceFilter(1, 2, 3, [new AttributeValue(1, 1)], [new AttributeValue(2, 2)], [1, 2, 3]);
        $this->fakeServer->SetCookie(new Cookie('resource_filter' . $scheduleId, json_encode($filter)));
        $page->_FilterSubmitted = false;
        $builder = new SchedulePageBuilder();

        $builtFilter = $builder->GetResourceFilter($scheduleId, $page);

        $this->assertEquals($filter->ScheduleId, $builtFilter->ScheduleId);
        $this->assertEquals($filter->ResourceIds, $builtFilter->ResourceIds);
    }

    public function testGetsRangeForSpecificDates()
    {
        $d1 = Date::Parse('2015-10-31', $this->fakeUser->Timezone);
        $d2 = Date::Parse('2015-12-25', $this->fakeUser->Timezone);

        $page = new FakeSchedulePage();
        $page->_SelectedDates = [$d1, $d2];

        $builder = new SchedulePageBuilder();

        $dates = $builder->GetScheduleDates($this->fakeUser, new FakeSchedule(), $page);

        $this->assertEquals(new DateRange($d1, $d2->AddDays(1)), $dates);
    }
}


class FakeSchedulePage implements ISchedulePage
{
    public $_FilterSubmitted = false;
    public $_ResourceTypeId;
    public $_MaxParticipants;
    public $_ResourceAttributes = [];
    public $_ResourceTypeAttributes = [];
    public $_ResourceIds = [];
    public $_SelectedDates = [];
    public $_ScheduleAvailability;
    public $_ScheduleTooEarly;
    public $_ScheduleTooLate;
    /**
     * @var LoadReservationRequest
     */
    public $_LoadReservationRequest;

    /**
     * @var ReservationListItem[]
     */
    public $_BoundReservations = [];
    /**
     * @var int
     */
    public $_OwnerId;
    /**
     * @var int
     */
    public $_ParticipantId;

    public function BindViewableResourceReservations($resourceIds) { }

    public function TakingAction()
    {
    }

    public function GetAction()
    {
    }

    public function RequestingData()
    {
    }

    public function GetDataRequest()
    {
    }

    public function PageLoad()
    {
    }

    public function Redirect($url)
    {
    }

    public function RedirectToError($errorMessageId = ErrorMessages::UNKNOWN_ERROR, $lastPage = '')
    {
    }

    public function IsPostBack()
    {
    }

    public function IsValid()
    {
    }

    public function GetLastPage($defaultPage = '')
    {
    }

    public function RegisterValidator($validatorId, $validator)
    {
    }

    public function EnforceCSRFCheck()
    {
    }

    public function SetSchedules($schedules)
    {
    }

    public function SetResources($resources)
    {
    }

    public function SetDailyLayout($dailyLayout)
    {
    }

    public function GetScheduleId()
    {
        return null;
    }

    /**
     * @param int $scheduleId
     */
    public function SetScheduleId($scheduleId)
    {
    }

    /**
     * @param string $scheduleName
     */
    public function SetScheduleName($scheduleName)
    {
    }

    /**
     * @param int $firstWeekday
     */
    public function SetFirstWeekday($firstWeekday)
    {
    }

    /**
     * Sets the dates to be displayed for the schedule, adjusted for timezone if necessary
     *
     * @param DateRange $dates
     */
    public function SetDisplayDates($dates)
    {
    }

    /**
     * @param Date $previousDate
     * @param Date $nextDate
     */
    public function SetPreviousNextDates($previousDate, $nextDate)
    {
    }

    /**
     * @return string
     */
    public function GetSelectedDate()
    {
        return null;
    }

    /**
     */
    public function ShowInaccessibleResources()
    {
    }

    /**
     * @param bool $showShowFullWeekToggle
     */
    public function ShowFullWeekToggle($showShowFullWeekToggle)
    {
    }

    /**
     * @return bool
     */
    public function GetShowFullWeek()
    {
        return null;
    }

    /**
     * @param ScheduleLayoutSerializable $layoutResponse
     */
    public function SetLayoutResponse($layoutResponse)
    {
    }

    /**
     * @return string
     */
    public function GetLayoutDate()
    {
        return null;
    }

    /**
     * @param int $scheduleId
     * @return string|ScheduleStyle
     */
    public function GetScheduleStyle($scheduleId)
    {
        return null;
    }

    /**
     * @param string|ScheduleStyle $direction
     */
    public function SetScheduleStyle($direction)
    {
    }

    /**
     * @return int
     */
    public function GetGroupId()
    {
        return null;
    }

    /**
     * @return int[]
     */
    public function GetResourceIds()
    {
        return $this->_ResourceIds;
    }

    /**
     * @param ResourceGroupTree $resourceGroupTree
     */
    public function SetResourceGroupTree(ResourceGroupTree $resourceGroupTree)
    {
    }

    /**
     * @param ResourceType[] $resourceTypes
     */
    public function SetResourceTypes($resourceTypes)
    {
    }

    /**
     * @param Attribute[] $attributes
     */
    public function SetResourceCustomAttributes($attributes)
    {
    }

    /**
     * @param Attribute[] $attributes
     */
    public function SetResourceTypeCustomAttributes($attributes)
    {
    }

    /**
     * @return bool
     */
    public function FilterSubmitted()
    {
        return $this->_FilterSubmitted;
    }

    /**
     * @return int
     */
    public function GetResourceTypeId()
    {
        return $this->_ResourceTypeId;
    }

    /**
     * @return int
     */
    public function GetMaxParticipants()
    {
        return $this->_MaxParticipants;
    }

    /**
     * @return AttributeFormElement[]|array
     */
    public function GetResourceAttributes()
    {
        return $this->_ResourceAttributes;
    }

    /**
     * @return AttributeFormElement[]|array
     */
    public function GetResourceTypeAttributes()
    {
        return $this->_ResourceTypeAttributes;
    }

    public function SetFilter($resourceFilter)
    {
    }

    public function SetSubscriptionUrl(CalendarSubscriptionUrl $subscriptionUrl)
    {
    }

    public function ShowPermissionError($shouldShow)
    {
    }

    public function GetDisplayTimezone(UserSession $user, Schedule $schedule)
    {
        return null;
    }

    public function GetResourceId()
    {
        return null;
    }

    public function GetSelectedDates()
    {
        return $this->_SelectedDates;
    }

    public function SetSpecificDates($specificDates)
    {
    }

    public function GetSortField()
    {
    }

    public function GetSortDirection()
    {
    }

    public function FilterCleared()
    {
        return null;
    }

    public function BindScheduleAvailability($availability, $tooEarly)
    {
        $this->_ScheduleAvailability = $availability;
        $this->_ScheduleTooEarly = $tooEarly;
        $this->_ScheduleTooLate = !$tooEarly;
    }

    public function SetAllowConcurrent($allowConcurrentReservations)
    {
    }


    public function GetReservationRequest()
    {
        return $this->_LoadReservationRequest;
    }

    public function BindReservations($items)
    {
        $this->_BoundReservations = $items;
    }

    public function GetOwnerId()
    {
        return $this->_OwnerId;
    }

    public function GetParticipantId()
    {
        return $this->_ParticipantId;
    }

    public function GetOwnerText()
    {
        // TODO: Implement GetOwnerText() method.
        return null;
    }

    public function GetParticipantText()
    {
        // TODO: Implement GetParticipantText() method.
        return null;
    }
}
