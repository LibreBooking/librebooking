<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/MonitorDisplayPresenter.php');

class MonitorDisplayPresenterTest extends TestBase
{
    /**
     * @var TestMonitorDisplayPage
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
     * @var MonitorDisplayPresenter
     */
    private $presenter;

    /**
     * @var FakeScheduleService
     */
    private $scheduleService;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new TestMonitorDisplayPage();
        $this->resourceService = new FakeResourceService();
        $this->scheduleService = new FakeScheduleService();
        $this->reservationService = new FakeReservationService();

        $this->presenter = new MonitorDisplayPresenter(
            $this->page,
            $this->resourceService,
            $this->reservationService,
            $this->scheduleService,
            new ScheduleLayoutFactory()
        );
    }

    public function testBindsConfigurationOptions()
    {
        $this->scheduleService->_AllSchedules = [new FakeSchedule(1, '1', false), new FakeSchedule(2, '2', true)];
        $this->resourceService->_ScheduleResources = [new TestResourceDto(1), new TestResourceDto(2)];

        $this->presenter->PageLoad();

        $this->assertEquals($this->scheduleService->_AllSchedules, $this->page->_Schedules);
        $this->assertEquals($this->resourceService->_ScheduleResources, $this->page->_Resources);
    }

    public function testRebindsResourcesForSchedule()
    {
        $this->page->_ScheduleId = 1;
        $this->resourceService->_ScheduleResources = [new TestResourceDto(1, '1'), new TestResourceDto(2, '2')];

        $this->presenter->ProcessDataRequest('resources');

        $this->assertEquals([['id' => 1, 'name' => '1'], ['id' => 2, 'name' => '2']], $this->page->_ReBoundResources);
    }

    public function testShowsSchedule()
    {
        $timezone = 'America/Chicago';
        $this->page->_ScheduleId = 1;
        $this->page->_ResourceId = 2;
        $this->page->_DaysToView = 5;
        $this->page->_Format = 1;

        $this->scheduleService->_Layout = new FakeScheduleLayout();

        $this->scheduleService->_DailyLayout = new FakeDailyLayout();
        $this->scheduleService->_DailyLayout->_Timezone = $timezone;

        $now = Date::Now()->ToTimezone($timezone);
        $pageRange = new DateRange($now->GetDate(), $now->GetDate()->AddDays(5));
        $searchRange = new DateRange($now->GetDate(), $now->GetDate()->AddDays(6));

        $this->presenter->ProcessDataRequest('schedule');

        $this->assertEquals($searchRange, $this->reservationService->_LastDateRange);
        $this->assertEquals($pageRange, $this->page->_DateRange);
        $this->assertEquals($this->scheduleService->_DailyLayout, $this->page->_DailyLayout);
        $this->assertEquals($this->resourceService->_ScheduleResources, $this->page->_Resources);
    }
}

class TestMonitorDisplayPage extends MonitorDisplayPage
{
    /**
     * @var Schedule[]
     */
    public $_Schedules;

    /**
     * @var ResourceDto[]
     */
    public $_Resources;

    /**
     * @var int
     */
    public $_ScheduleId;

    /**
     * @var ResourceDto[]
     */
    public $_ReBoundResources;

    /**
     * @var int
     */
    public $_ResourceId;

    /**
     * @var int
     */
    public $_DaysToView;

    /**
     * @var int
     */
    public $_Format;

    /**
     * @var IDailyLayout
     */
    public $_DailyLayout;

    /**
     * @var DateRange
     */
    public $_DateRange;

    public function BindSchedules($schedules)
    {
        $this->_Schedules = $schedules;
    }

    public function BindResources($resources)
    {
        $this->_Resources = $resources;
    }

    public function RebindResources($resources)
    {
        $this->_ReBoundResources = $resources;
    }

    public function RebindSchedule(DateRange $range, IDailyLayout $layout, $resources, $format)
    {
        $this->_DateRange = $range;
        $this->_DailyLayout = $layout;
        $this->_Resources = $resources;
    }

    public function GetDaysToView()
    {
        return $this->_DaysToView;
    }
}
