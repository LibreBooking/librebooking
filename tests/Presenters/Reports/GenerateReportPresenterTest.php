<?php

require_once(ROOT_DIR . 'Pages/Reports/GenerateReportPage.php');

class GenerateReportPresenterTest extends TestBase
{
    /**
     * @var GenerateReportPresenter
     */
    public $presenter;

    /**
     * @var FakeGenerateReportPage
     */
    public $page;

    /**
     * @var IReportingService|PHPUnit\Framework\MockObject\MockObject
     */
    public $reportingService;

    /**
     * @var FakeUserRepository
     */
    public $userRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeGenerateReportPage();
        $this->reportingService = $this->createMock('IReportingService');
        $resourceRepository = $this->createMock('IResourceRepository');
        $scheduleRepository = $this->createMock('IScheduleRepository');
        $groupRepository = $this->createMock('IGroupViewRepository');
        $this->userRepository = new FakeUserRepository();

        $this->presenter = new GenerateReportPresenter(
            $this->page,
            $this->fakeUser,
            $this->reportingService,
            $resourceRepository,
            $scheduleRepository,
            $groupRepository,
            $this->userRepository
        );
    }

    public function testRunsCustomReport()
    {
        $this->SetupPage();

        $expectedStart = $this->page->_RangeStart;
        $expectedEnd = $this->page->_RangeEnd;
        $expectedReport = new FakeReport();

        $usage = new Report_Usage($this->page->_Usage);
        $selection = new Report_ResultSelection($this->page->_ResultSelection);
        $groupBy = new Report_GroupBy($this->page->_GroupBy);
        $range = new Report_Range($this->page->_Range, $expectedStart, $expectedEnd, $this->fakeUser->Timezone);
        $filter = new Report_Filter(
            $this->page->_ResourceId,
            $this->page->_ScheduleId,
            $this->page->_UserId,
            $this->page->_GroupId,
            $this->page->_AccessoryId,
            $this->page->_ParticipantId,
            $this->page->_IncludeDeleted,
            $this->page->_ResourceTypeId
        );

        $this->reportingService->expects($this->once())
                               ->method('GenerateCustomReport')
                               ->with(
                                   $this->equalTo($usage),
                                   $this->equalTo($selection),
                                   $this->equalTo($groupBy),
                                   $this->equalTo($range),
                                   $this->equalTo($filter)
                               )
                               ->willReturn($expectedReport);

        $user = new FakeUser();
        $savedReportColumns = 'savedreport';
        $user->ChangePreference(UserPreferences::REPORT_COLUMNS, $savedReportColumns);
        $this->userRepository->_User = $user;

        $this->presenter->GenerateCustomReport();

        $this->assertEquals($expectedReport, $this->page->_BoundReport);
        $this->assertEquals($savedReportColumns, $this->page->_ReportColumns);
    }

    public function testSavesReport()
    {
        $this->SetupPage();
        $reportName = 'report name';

        $this->page->_ReportName = $reportName;

        $expectedStart = $this->page->_RangeStart;
        $expectedEnd = $this->page->_RangeEnd;

        $usage = new Report_Usage($this->page->_Usage);
        $selection = new Report_ResultSelection($this->page->_ResultSelection);
        $groupBy = new Report_GroupBy($this->page->_GroupBy);
        $range = new Report_Range($this->page->_Range, $expectedStart, $expectedEnd, $this->fakeUser->Timezone);
        $filter = new Report_Filter(
            $this->page->_ResourceId,
            $this->page->_ScheduleId,
            $this->page->_UserId,
            $this->page->_GroupId,
            $this->page->_AccessoryId,
            $this->page->_ParticipantId,
            $this->page->_IncludeDeleted,
            $this->page->_ResourceTypeId
        );

        $this->reportingService->expects($this->once())
                               ->method('Save')
                               ->with(
                                   $this->equalTo($reportName),
                                   $this->equalTo($this->fakeUser->UserId),
                                   $this->equalTo($usage),
                                   $this->equalTo($selection),
                                   $this->equalTo($groupBy),
                                   $this->equalTo($range),
                                   $this->equalTo($filter)
                               );

        $this->presenter->SaveReport();
    }

    private function SetupPage()
    {
        $this->page->_Usage = Report_Usage::ACCESSORIES;
        $this->page->_ResultSelection = Report_ResultSelection::COUNT;
        $this->page->_GroupBy = Report_GroupBy::USER;
        $this->page->_Range = Report_Range::DATE_RANGE;
        $this->page->_RangeStart = '01/01/2001';
        $this->page->_RangeEnd = '02/02/2002';
        $this->page->_ResourceId = 20;
        $this->page->_ScheduleId = 30;
        $this->page->_UserId = 40;
        $this->page->_GroupId = 50;
        $this->page->_AccessoryId = 60;
        $this->page->_ParticipantId = 70;
        $this->page->_ResourceTypeId = 80;
    }
}

class FakeGenerateReportPage extends GenerateReportPage
{
    /**
     * @var Report_ResultSelection|string
     */
    public $_ResultSelection = Report_ResultSelection::FULL_LIST;
    /**
     * @var Report_GroupBy|string
     */
    public $_GroupBy = Report_GroupBy::NONE;
    /**
     * @var Report_Range|string
     */
    public $_Range = Report_Range::ALL_TIME;
    /**
     * @var string
     */
    public $_RangeStart;
    /**
     * @var string
     */
    public $_RangeEnd;

    /**
     * @var int
     */
    public $_ResourceId;
    /**
     * @var int
     */
    public $_ScheduleId;
    /**
     * @var int
     */
    public $_UserId;
    /**
     * @var int
     */
    public $_ParticipantId;
    /**
     * @var int
     */
    public $_GroupId;
    /**
     * @var int
     */
    public $_AccessoryId;
    /**
     * @var IReport
     */
    public $_BoundReport;
    /**
     * @var Report_Usage
     */
    public $_Usage;

    /**
     * @var string
     */
    public $_ReportName;
    /**
     * @var BookableResource[]
     */
    public $_BoundResources;
    /**
     * @var AccessoryDto[]
     */
    public $_BoundAccessories;
    /**
     * @var Schedule[]
     */
    public $_BoundSchedules;
    /**
     * @var bool
     */
    public $_ErrorDisplayed;
    /**
     * @var bool
     */
    public $_ResultsShown;
    /**
     * @var bool
     */
    public $_ReportPrinted;
    /**
     * @var bool
     */
    public $_CsvShown;

    public $_IncludeDeleted;

    public $_ResourceTypeId;
    /**
     * @var string
     */
    public $_ReportColumns;

    public function GetResultSelection()
    {
        return $this->_ResultSelection;
    }

    public function GetGroupBy()
    {
        return $this->_GroupBy;
    }

    public function GetRange()
    {
        return $this->_Range;
    }

    public function GetStart()
    {
        return $this->_RangeStart;
    }

    public function GetEnd()
    {
        return $this->_RangeEnd;
    }

    public function GetResourceIds()
    {
        return $this->_ResourceId;
    }

    public function GetResourceTypeIds()
    {
        return $this->_ResourceTypeId;
    }

    public function GetScheduleIds()
    {
        return $this->_ScheduleId;
    }

    public function GetUserId()
    {
        return $this->_UserId;
    }

    public function GetParticipantId()
    {
        return $this->_ParticipantId;
    }

    public function GetGroupIds()
    {
        return $this->_GroupId;
    }

    public function BindReport(IReport $report, IReportDefinition $definition, $selectedColumns)
    {
        $this->_BoundReport = $report;
        $this->_ReportColumns = $selectedColumns;
    }

    public function GetUsage()
    {
        return $this->_Usage;
    }

    public function GetAccessoryIds()
    {
        return $this->_AccessoryId;
    }

    public function GetReportName()
    {
        return $this->_ReportName;
    }

    public function BindResources($resources)
    {
        $this->_BoundResources = $resources;
    }

    public function BindAccessories($accessories)
    {
        $this->_BoundAccessories = $accessories;
    }

    public function BindSchedules($schedules)
    {
        $this->_BoundSchedules = $schedules;
    }

    public function DisplayError()
    {
        $this->_ErrorDisplayed = true;
    }

    public function ShowResults()
    {
        $this->_ResultsShown = true;
    }

    public function PrintReport()
    {
        $this->_ReportPrinted = true;
    }

    public function ShowCsv()
    {
        $this->_CsvShown = true;
    }
}
