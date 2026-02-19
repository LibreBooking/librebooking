<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Reporting/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class ReportingServiceTest extends TestBase
{
    /**
     * @var ReportingService
     */
    private $rs;

    /**
     * @var FakeReportingRepository
     */
    private $reportingRepository;
    /**
     * @var FakeScheduleRepository
     */
    private $scheduleRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->reportingRepository = new FakeReportingRepository();

        $this->scheduleRepository = new FakeScheduleRepository();
        $this->rs = new ReportingService($this->reportingRepository, new FakeAttributeRepository(), $this->scheduleRepository);
    }

    public function testBuildsCustomResourceReport()
    {
        $start = '2010-01-01';
        $end = '2010-01-02';

        $resourceId = [1];
        $scheduleId = [2];
        $userId = 3;
        $groupId = [4];
        $accessoryId = [5];
        $participantId = 6;
        $resourceTypeId = [7];

        $usage = new Report_Usage(Report_Usage::RESOURCES);
        $selection = new Report_ResultSelection(Report_ResultSelection::FULL_LIST);
        $groupBy = new Report_GroupBy(Report_GroupBy::GROUP);
        $range = new Report_Range(Report_Range::DATE_RANGE, $start, $end, 'UTC');
        $filter = new Report_Filter($resourceId, $scheduleId, $userId, $groupId, $accessoryId, $participantId, true, $resourceTypeId);

        $commandBuilder = new ReportCommandBuilder();
        $commandBuilder->SelectFullList()
            ->OfResources()
            ->Within(Date::Parse($start, 'UTC'), Date::Parse($end, 'UTC'))
            ->WithResourceIds($resourceId)
            ->WithUserId($userId)
            ->WithParticipantId($participantId)
            ->WithScheduleIds($scheduleId)
            ->WithGroupIds($groupId)
            ->WithAccessoryIds($accessoryId)
            ->WithResourceTypeIds($resourceTypeId)
            ->WithDeleted()
            ->GroupByGroup();

        $rows = [[
            ColumnNames::OWNER_FIRST_NAME => 'value',
            ColumnNames::OWNER_LAST_NAME => 'value',
            ColumnNames::OWNER_USER_ID => 'value',
        ]];

        $this->reportingRepository->_CustomReportData = $rows;

        $report = $this->rs->GenerateCustomReport($usage, $selection, $groupBy, $range, $filter, $this->fakeUser->Timezone);

        $cols = new ReportColumns();
        $cols->Add(ColumnNames::OWNER_FIRST_NAME);
        $cols->Add(ColumnNames::OWNER_LAST_NAME);
        $cols->Add(ColumnNames::OWNER_USER_ID);

        $this->assertEquals($cols, $report->GetColumns());
        $this->assertEquals(new CustomReportData($rows), $report->GetData());
    }

    public function testSavesReportForUser()
    {
        $reportName = 'reportName';
        $userId = 12;

        $usage = new Report_Usage(Report_Usage::ACCESSORIES);
        $selection = new Report_ResultSelection(Report_ResultSelection::COUNT);
        $groupBy = new Report_GroupBy(Report_GroupBy::RESOURCE);
        $range = new Report_Range(Report_Range::ALL_TIME, Date::Now(), Date::Now());
        $filter = new Report_Filter(null, null, null, null, null, null, null, null);

        $savedReport = new SavedReport($reportName, $userId, $usage, $selection, $groupBy, $range, $filter);

        $this->rs->Save($reportName, $userId, $usage, $selection, $groupBy, $range, $filter);

        $this->assertEquals($savedReport, $this->reportingRepository->_LastSavedReport);
    }

    public function testGetsSavedReports()
    {
        $reports = [new FakeSavedReport()];
        $userId = 100;

        $this->reportingRepository->_SavedReports = $reports;

        $actualReports = $this->rs->GetSavedReports($userId);

        $this->assertEquals($reports, $actualReports);
    }

    public function testGeneratesSavedReport()
    {
        $reportId = 1;
        $userId = 2;

        $savedReport = new FakeSavedReport();
        $data = [];
        $report = new CustomReport($data, new FakeAttributeRepository());

        $this->reportingRepository->_SavedReport = $savedReport;
        $this->reportingRepository->_CustomReportData = $data;

        $expectedReport = new GeneratedSavedReport($savedReport, $report);
        $actualReport = $this->rs->GenerateSavedReport($reportId, $userId, $this->fakeUser->Timezone);

        $this->assertEquals($expectedReport, $actualReport);
    }

    public function testEmailsReport()
    {
        $report = new GeneratedSavedReport(new FakeSavedReport(), new FakeReport());
        $def = new ReportDefinition($report, null);
        $to = 'email';
        $user = $this->fakeUser;

        $cols = 'cols';

        $expectedMessage = new ReportEmailMessage($report, $def, $to, $user, $cols);

        $this->rs->SendReport($report, $def, $to, $user, $cols);
        $this->assertInstanceOf('ReportEmailMessage', $this->fakeEmailService->_LastMessage);
    }

    public function testDeletesReport()
    {
        $reportId = 1;
        $userId = 2;

        $this->rs->DeleteSavedReport($reportId, $userId);

        $this->assertTrue($this->reportingRepository->_Deleted);
        $this->assertEquals($reportId, $this->reportingRepository->_DeletedReport);
    }

    public function testGetsUtilizationReport_DifferentLayoutPerDay()
    {
        $r1 = 1;
        $s1 = 100;

        $tz = 'America/New_York';

        $thursday = Date::Parse('2018-12-20', $tz);
        $friday = Date::Parse('2018-12-21', $tz);
        $saturday = Date::Parse('2018-12-22', $tz);
        $sunday = Date::Parse('2018-12-23', $tz);
        $monday = Date::Parse('2018-12-24', $tz);
        $tuesday = Date::Parse('2018-12-25', $tz);
        $wednesday = Date::Parse('2018-12-26', $tz);

        $thursdaySlots = $this->HourlyBetween(6, 18, $thursday);
        $fridaySlots = $this->HourlyBetween(3, 22, $friday);
        $saturdaySlots = $this->HourlyBetween(8, 22, $saturday);
        $sundaySlots = $this->HourlyBetween(8, 22, $sunday);
        $mondaySlots = $this->HourlyBetween(8, 20, $monday);
        $tuesdaySlots = $this->HourlyBetween(8, 20, $tuesday);
        $wednesdaySlots = $this->HourlyBetween(8, 20, $wednesday);

        $layout = new FakeScheduleLayout();
        $layout->_Timezone = $tz;
        $layout->_UsesDailyLayouts = true;
        $layout->_AddDailyLayout($thursday, $thursdaySlots);
        $layout->_AddDailyLayout($friday, $fridaySlots);
        $layout->_AddDailyLayout($saturday, $saturdaySlots);
        $layout->_AddDailyLayout($sunday, $sundaySlots);
        $layout->_AddDailyLayout($monday, $mondaySlots);
        $layout->_AddDailyLayout($tuesday, $tuesdaySlots);
        $layout->_AddDailyLayout($wednesday, $wednesdaySlots);

        $this->scheduleRepository->_Layout = $layout;

        $data = [
            $this->GetUtilizationRow($r1, $s1, $thursday->SetTimeString('12:00'), $saturday->SetTimeString('12:00'), 1, 'r1'),
            $this->GetUtilizationRow($r1, $s1, $thursday->SetTimeString('06:00'), $thursday->SetTimeString('08:00'), 2, 'r1'),
            $this->GetUtilizationRow($r1, $s1, $saturday->SetTimeString('13:00'), $saturday->SetTimeString('15:00'), 1, 'r1'),
            $this->GetUtilizationRow($r1, $s1, $sunday->SetTimeString('13:00'), $sunday->SetTimeString('15:00'), 2, 'r1'),
        ];

        $this->reportingRepository->_CustomReportData = $data;

        $report = $this->rs->GenerateCustomReport(new Report_Usage(Report_Usage::RESOURCES), new Report_ResultSelection(Report_ResultSelection::UTILIZATION), new Report_GroupBy(Report_GroupBy::NONE), Report_Range::AllTime(), new Report_Filter(null, null, null, null, null, null, false, null), $this->fakeUser->Timezone);

        $data = $report->GetData()->Rows();

        $format = Resources::GetInstance()->GetDateFormat('general_date');

        /**
         * $availableHoursThursday = 12;
         * $blackoutHoursThursday = 2;
         * $reservedHoursThursday = 6;
         *
         * $availableHoursFriday = 19;
         * $blackoutHoursFriday = 0;
         * $reservedHoursFriday = 19;
         *
         * $availableHoursSaturday = 14;
         * $blackoutHoursSaturday = 0;
         * $reservedHoursSaturday = 6;
         **/

        $this->assertEquals($thursday, $data[0][ColumnNames::DATE]);
        $this->assertEquals(60, $data[0][ColumnNames::UTILIZATION], '6/10');
        $this->assertEquals('r1', $data[0][ColumnNames::RESOURCE_NAME_ALIAS]);
        $this->assertEquals($s1, $data[0][ColumnNames::SCHEDULE_ID]);
        $this->assertEquals($r1, $data[0][ColumnNames::RESOURCE_ID]);

        $this->assertEquals($friday, $data[1][ColumnNames::DATE]);
        $this->assertEquals(100, $data[1][ColumnNames::UTILIZATION], '19/19');

        $this->assertEquals($saturday, $data[2][ColumnNames::DATE]);
        $this->assertEquals(43, $data[2][ColumnNames::UTILIZATION], '6/14');

        $this->assertEquals($sunday, $data[3][ColumnNames::DATE]);
        $this->assertEquals(0, $data[3][ColumnNames::UTILIZATION]);
    }

    public function testGetsUtilizationReport_SameLayoutPerDay()
    {
        $r1 = 1;
        $s1 = 100;

        $tz = 'America/New_York';

        $thursday = Date::Parse('2018-12-20', $tz);
        $friday = Date::Parse('2018-12-21', $tz);
        $saturday = Date::Parse('2018-12-22', $tz);
        $sunday = Date::Parse('2018-12-23', $tz);

        $slots = $this->HourlyBetween(6, 18, $thursday);

        $layout = new FakeScheduleLayout();
        $layout->_Timezone = $tz;
        $layout->_UsesDailyLayouts = false;
        $layout->_Layout = $slots;

        $this->scheduleRepository->_Layout = $layout;

        $data = [
            $this->GetUtilizationRow($r1, $s1, $thursday->SetTimeString('12:00'), $saturday->SetTimeString('12:00'), 1, 'r1'),
            $this->GetUtilizationRow($r1, $s1, $thursday->SetTimeString('06:00'), $thursday->SetTimeString('08:00'), 2, 'r1'),
            $this->GetUtilizationRow($r1, $s1, $saturday->SetTimeString('13:00'), $saturday->SetTimeString('15:00'), 1, 'r1'),
            $this->GetUtilizationRow($r1, $s1, $sunday->SetTimeString('13:00'), $sunday->SetTimeString('15:00'), 2, 'r1'),
        ];

        $this->reportingRepository->_CustomReportData = $data;

        $report = $this->rs->GenerateCustomReport(new Report_Usage(Report_Usage::RESOURCES), new Report_ResultSelection(Report_ResultSelection::UTILIZATION), new Report_GroupBy(Report_GroupBy::NONE), Report_Range::AllTime(), new Report_Filter(null, null, null, null, null, null, false, null), $this->fakeUser->Timezone);

        $data = $report->GetData()->Rows();

        $format = Resources::GetInstance()->GetDateFormat('general_date');

        /**
         * availableHours = 12;
         * $blackoutHoursThursday = 2;
         * $reservedHoursThursday = 6;
         * $reservedHoursSaturday = 8;
         **/

        $this->assertEquals($thursday, $data[0][ColumnNames::DATE]);
        $this->assertEquals(60, $data[0][ColumnNames::UTILIZATION], '6/10');
        $this->assertEquals('r1', $data[0][ColumnNames::RESOURCE_NAME_ALIAS]);
        $this->assertEquals($s1, $data[0][ColumnNames::SCHEDULE_ID]);
        $this->assertEquals($r1, $data[0][ColumnNames::RESOURCE_ID]);

        $this->assertEquals($friday, $data[1][ColumnNames::DATE]);
        $this->assertEquals(100, $data[1][ColumnNames::UTILIZATION]);

        $this->assertEquals($saturday, $data[2][ColumnNames::DATE]);
        $this->assertEquals(67, $data[2][ColumnNames::UTILIZATION], '8/12');

        $this->assertEquals($sunday, $data[3][ColumnNames::DATE]);
        $this->assertEquals(0, $data[3][ColumnNames::UTILIZATION]);
    }

    public function testGetsUtilizationReport_CustomLayout()
    {
        $r1 = 1;
        $s1 = 100;

        $tz = 'America/New_York';

        $thursday = Date::Parse('2018-12-20', $tz);
        $friday = Date::Parse('2018-12-21', $tz);
        $saturday = Date::Parse('2018-12-22', $tz);
        $sunday = Date::Parse('2018-12-23', $tz);

        $layout = new CustomScheduleLayout($tz, $s1, $this->scheduleRepository);

        $this->scheduleRepository->_Layout = $layout;
        $this->scheduleRepository->_AddCustomLayout(
            $thursday,
            [
                new SchedulePeriod($thursday->SetTimeString('12:00'), $thursday->SetTimeString('14:00')),
                new SchedulePeriod($thursday->SetTimeString('16:00'), $thursday->SetTimeString('18:00')),
            ]
        );
        $this->scheduleRepository->_AddCustomLayout($friday, []);

        $this->scheduleRepository->_AddCustomLayout(
            $saturday,
            [
                new SchedulePeriod($saturday->SetTimeString('12:00'), $saturday->SetTimeString('14:00')),
                new SchedulePeriod($saturday->SetTimeString('16:00'), $saturday->SetTimeString('18:00')),
            ]
        );


        $data = [
            $this->GetUtilizationRow($r1, $s1, $thursday->SetTimeString('12:00'), $thursday->SetTimeString('14:00'), 1, 'r1'),
            $this->GetUtilizationRow($r1, $s1, $saturday->SetTimeString('12:00'), $saturday->SetTimeString('14:00'), 1, 'r1'),
            $this->GetUtilizationRow($r1, $s1, $saturday->SetTimeString('16:00'), $saturday->SetTimeString('18:00'), 1, 'r1'),
        ];

        $this->reportingRepository->_CustomReportData = $data;

        $report = $this->rs->GenerateCustomReport(new Report_Usage(Report_Usage::RESOURCES), new Report_ResultSelection(Report_ResultSelection::UTILIZATION), new Report_GroupBy(Report_GroupBy::NONE), Report_Range::AllTime(), new Report_Filter(null, null, null, null, null, null, false, null), $this->fakeUser->Timezone);

        $data = $report->GetData()->Rows();

        $format = Resources::GetInstance()->GetDateFormat('general_date');

        $this->assertEquals($thursday, $data[0][ColumnNames::DATE]);
        $this->assertEquals(50, $data[0][ColumnNames::UTILIZATION], '2/4');
        $this->assertEquals('r1', $data[0][ColumnNames::RESOURCE_NAME_ALIAS]);
        $this->assertEquals($s1, $data[0][ColumnNames::SCHEDULE_ID]);
        $this->assertEquals($r1, $data[0][ColumnNames::RESOURCE_ID]);

        $this->assertEquals($friday, $data[1][ColumnNames::DATE]);
        $this->assertEquals(0, $data[1][ColumnNames::UTILIZATION]);

        $this->assertEquals($saturday, $data[2][ColumnNames::DATE]);
        $this->assertEquals(100, $data[2][ColumnNames::UTILIZATION], '4/4');
    }

    /**
     * @param int $resourceId
     * @param int $scheduleId
     * @param Date $start
     * @param Date $end
     * @parma int $type
     * @param string $resourceName
     * @return array
     */
    private function GetUtilizationRow($resourceId, $scheduleId, $start, $end, $type = 1, $resourceName = 'name')
    {
        return [
            ColumnNames::SCHEDULE_ID => $scheduleId,
            ColumnNames::RESOURCE_ID => $resourceId,
            ColumnNames::RESOURCE_NAME_ALIAS => $resourceName,
            ColumnNames::RESERVATION_START => $start->ToDatabase(),
            ColumnNames::RESERVATION_END => $end->ToDatabase(),
            ColumnNames::UTILIZATION_TYPE => $type,
        ];
    }

    /**
     * @param int $firstHour
     * @param int $lastHour
     * @param Date $date
     * @return SchedulePeriod[]
     */
    private function HourlyBetween($firstHour, $lastHour, $date)
    {
        $slots = [];
        if ($firstHour != 0) {
            $slots = [new NonSchedulePeriod($date->SetTimeString('00:00'), $date->SetTimeString("$firstHour:00", true))];
        }

        for ($i = $firstHour; $i < $lastHour; $i++) {
            $e = $i + 1;
            $slots[] = new SchedulePeriod($date->SetTimeString("$i:00"), $date->SetTimeString("$e:00", true));
        }

        if ($lastHour != 0) {
            $slots[] = new NonSchedulePeriod($date->SetTimeString("$lastHour:00"), $date->SetTimeString('24:00', true));
        }

        return $slots;
    }
}

class FakeReportingRepository implements IReportingRepository
{
    public $_CustomReportData = [];
    public $_LastSavedReport;
    public $_SavedReports;
    public $_SavedReport;
    public $_Deleted;
    public $_DeletedReport;

    /**
     * @param ReportCommandBuilder $commandBuilder
     * @return array
     */
    public function GetCustomReport(ReportCommandBuilder $commandBuilder)
    {
        return $this->_CustomReportData;
    }

    /**
     * @param SavedReport $savedReport
     */
    public function SaveCustomReport(SavedReport $savedReport)
    {
        $this->_LastSavedReport = $savedReport;
    }

    /**
     * @param int $userId
     * @return array|SavedReport[]
     */
    public function LoadSavedReportsForUser($userId)
    {
        return $this->_SavedReports;
    }

    /**
     * @param int $reportId
     * @param int $userId
     * @return SavedReport
     */
    public function LoadSavedReportForUser($reportId, $userId)
    {
        return $this->_SavedReport;
    }

    /**
     * @param int $reportId
     * @param int $userId
     */
    public function DeleteSavedReport($reportId, $userId)
    {
        $this->_Deleted = true;
        $this->_DeletedReport = $reportId;
    }
}
