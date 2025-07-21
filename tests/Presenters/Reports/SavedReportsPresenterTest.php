<?php

require_once(ROOT_DIR . 'Presenters/Reports/SavedReportsPresenter.php');

class SavedReportsPresenterTest extends TestBase
{
    /**
     * @var SavedReportsPresenter
     */
    public $presenter;

    /**
     * @var FakeSavedReportsPage
     */
    public $page;

    /**
     * @var IReportingService|PHPUnit\Framework\MockObject\MockObject
     */
    public $service;

    /**
     * @var FakeUserRepository
     */
    public $userRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeSavedReportsPage();
        $this->service = $this->createMock('IReportingService');
        $this->userRepository = new FakeUserRepository();

        $this->presenter = new SavedReportsPresenter($this->page, $this->fakeUser, $this->service, $this->userRepository);
    }

    public function testGetsAllSavedReportsForTheUser()
    {
        $savedReports = [new FakeSavedReport()];

        $this->service->expects($this->once())
                      ->method('GetSavedReports')
                      ->with($this->equalTo($this->fakeUser->UserId))
                      ->willReturn($savedReports);

        $this->presenter->PageLoad();

        $this->assertEquals($savedReports, $this->page->_BoundReportList);
    }

    public function testRunsSavedReport()
    {
        $reportId = 100;
        $this->page->_ReportId = $reportId;
        $report = new FakeReport();

        $this->service->expects($this->once())
                      ->method('GenerateSavedReport')
                      ->with($this->equalTo($reportId), $this->equalTo($this->fakeUser->UserId))
                      ->willReturn($report);

        $user = new FakeUser();
        $savedReportColumns = 'savedreport';
        $user->ChangePreference(UserPreferences::REPORT_COLUMNS, $savedReportColumns);
        $this->userRepository->_User = $user;

        $this->presenter->GenerateReport();

        $this->assertEquals($report, $this->page->_BoundReport);
        $this->assertEquals(new ReportDefinition($report, $this->fakeUser->Timezone), $this->page->_BoundDefinition);
        $this->assertTrue($this->page->_ResultsDisplayed);
        $this->assertEquals($savedReportColumns, $this->page->_SelectedColumns);
    }

    public function testWhenReportIsNotFound()
    {
        $this->service->expects($this->once())
                      ->method('GenerateSavedReport')
                      ->with($this->anything(), $this->anything())
                      ->willReturn(null);

        $this->presenter->GenerateReport();

        $this->assertTrue($this->page->_ErrorDisplayed);
    }

    public function testEmailsReport()
    {
        $emailAddress = 'e@mail.com';
        $reportId = 123;

        $this->page->_EmailAddress = $emailAddress;
        $this->page->_ReportId = $reportId;

        $report = new FakeReport();
        $definition = new ReportDefinition($report, $this->fakeUser->Timezone);

        $this->service->expects($this->once())
                      ->method('GenerateSavedReport')
                      ->with($this->equalTo($reportId), $this->equalTo($this->fakeUser->UserId))
                      ->willReturn($report);

        $this->service->expects($this->once())
                      ->method('SendReport')
                      ->with($this->equalTo($report), $this->equalTo($definition), $this->equalTo($emailAddress), $this->equalTo($this->fakeUser), $this->anything());

        $this->presenter->EmailReport();
    }

    public function testDeletesSavedReport()
    {
        $reportId = 100;
        $this->page->_ReportId = $reportId;

        $this->service->expects($this->once())
                      ->method('DeleteSavedReport')
                      ->with($this->equalTo($reportId), $this->equalTo($this->fakeUser->UserId));

        $this->presenter->DeleteReport();
    }
}

class FakeSavedReportsPage extends SavedReportsPage
{
    /**
     * @var SavedReport[]
     */
    public $_BoundReportList;
    /**
     * @var int
     */
    public $_ReportId;
    /**
     * @var IReport
     */
    public $_BoundReport;
    /**
     * @var ReportDefinition
     */
    public $_BoundDefinition;
    /**
     * @var bool
     */
    public $_ErrorDisplayed = false;
    /**
     * @var bool
     */
    public $_ResultsDisplayed = false;
    /**
     * @var string
     */
    public $_EmailAddress;
    public $_SelectedColumns;

    public function BindReportList($reportList)
    {
        $this->_BoundReportList = $reportList;
    }

    public function BindReport(IReport $report, IReportDefinition $definition, $selectedColumns)
    {
        $this->_BoundReport = $report;
        $this->_BoundDefinition = $definition;
        $this->_SelectedColumns = $selectedColumns;
    }

    public function DisplayError()
    {
        $this->_ErrorDisplayed = true;
    }

    public function ShowResults()
    {
        $this->_ResultsDisplayed = true;
    }

    public function GetReportId()
    {
        return $this->_ReportId;
    }

    public function GetEmailAddress()
    {
        return $this->_EmailAddress;
    }
}
