<?php

declare(strict_types=1);

class CannedReportTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function testGetsHighestCountResourcesAllTime()
    {
        $report = new CannedReport(CannedReport::RESOURCE_COUNT_ALLTIME, $this->fakeUser);
        $builder = $report->GetBuilder();

        $expected = new ReportCommandBuilder();
        $expected->SelectCount()
                ->OfResources()
                ->GroupByResource();

        $this->assertEquals($expected, $builder);
    }

    public function testGetsHighestCountResourcesThisWeek()
    {
        $report = new CannedReport(CannedReport::RESOURCE_COUNT_THISWEEK, $this->fakeUser);
        $builder = $report->GetBuilder();

        $range = new Report_Range(Report_Range::CURRENT_WEEK, null, null, $this->fakeUser->Timezone);
        $expected = new ReportCommandBuilder();
        $expected->SelectCount()
                ->OfResources()
                ->Within($range->Start(), $range->End())
                ->GroupByResource();

        $this->assertEquals($expected, $builder);
    }

    public function testGetsHighestCountResourcesThisMonth()
    {
        $report = new CannedReport(CannedReport::RESOURCE_COUNT_THISMONTH, $this->fakeUser);
        $builder = $report->GetBuilder();

        $range = new Report_Range(Report_Range::CURRENT_MONTH, null, null, $this->fakeUser->Timezone);
        $expected = new ReportCommandBuilder();
        $expected->SelectCount()
                ->OfResources()
                ->Within($range->Start(), $range->End())
                ->GroupByResource();

        $this->assertEquals($expected, $builder);
    }

    public function testGetsHighestTimeResourcesAllTime()
    {
        $report = new CannedReport(CannedReport::RESOURCE_TIME_ALLTIME, $this->fakeUser);
        $builder = $report->GetBuilder();

        $expected = new ReportCommandBuilder();
        $expected->SelectTime()
                ->OfResources()
                ->GroupByResource();

        $this->assertEquals($expected, $builder);
    }

    public function testGetsHighestTimeResourcesThisWeek()
    {
        $report = new CannedReport(CannedReport::RESOURCE_TIME_THISWEEK, $this->fakeUser);
        $builder = $report->GetBuilder();

        $range = new Report_Range(Report_Range::CURRENT_WEEK, null, null, $this->fakeUser->Timezone);
        $expected = new ReportCommandBuilder();
        $expected->SelectTime()
                ->OfResources()
                ->Within($range->Start(), $range->End())
                ->GroupByResource();

        $this->assertEquals($expected, $builder);
    }

    public function testGetsHighestTimeResourcesThisMonth()
    {
        $report = new CannedReport(CannedReport::RESOURCE_TIME_THISMONTH, $this->fakeUser);
        $builder = $report->GetBuilder();

        $range = new Report_Range(Report_Range::CURRENT_MONTH, null, null, $this->fakeUser->Timezone);
        $expected = new ReportCommandBuilder();
        $expected->SelectTime()
                ->OfResources()
                ->Within($range->Start(), $range->End())
                ->GroupByResource();

        $this->assertEquals($expected, $builder);
    }

    public function testGetsTopUsersByTimeReservedAllTime()
    {
        $report = new CannedReport(CannedReport::USER_TIME_ALLTIME, $this->fakeUser);

        $builder = $report->GetBuilder();

        $expected = new ReportCommandBuilder();
        $expected->SelectTime()
                ->OfResources()
                ->GroupByUser()
                ->LimitedTo(20);

        $this->assertEquals($expected, $builder);
    }

    public function testGetsTopUsersByTimeReservedThisWeek()
    {
        $report = new CannedReport(CannedReport::USER_TIME_THISWEEK, $this->fakeUser);

        $range = new Report_Range(Report_Range::CURRENT_WEEK, null, null, $this->fakeUser->Timezone);
        $builder = $report->GetBuilder();

        $expected = new ReportCommandBuilder();
        $expected->SelectTime()
                ->OfResources()
                ->GroupByUser()
                ->Within($range->Start(), $range->End())
                ->LimitedTo(20);

        $this->assertEquals($expected, $builder);
    }

    public function testGetsTopUsersByTimeReservedThisMonth()
    {
        $report = new CannedReport(CannedReport::USER_TIME_THISMONTH, $this->fakeUser);

        $range = new Report_Range(Report_Range::CURRENT_MONTH, null, null, $this->fakeUser->Timezone);
        $builder = $report->GetBuilder();

        $expected = new ReportCommandBuilder();
        $expected->SelectTime()
                ->OfResources()
                ->GroupByUser()
                ->Within($range->Start(), $range->End())
                ->LimitedTo(20);

        $this->assertEquals($expected, $builder);
    }

    public function testGetsTopUsersByCountAllTime()
    {
        $report = new CannedReport(CannedReport::USER_COUNT_ALLTIME, $this->fakeUser);

        $builder = $report->GetBuilder();

        $expected = new ReportCommandBuilder();
        $expected->SelectCount()
                ->OfResources()
                ->GroupByUser()
                ->LimitedTo(20);

        $this->assertEquals($expected, $builder);
    }

    public function testGetsTopUsersByCountThisWeek()
    {
        $report = new CannedReport(CannedReport::USER_COUNT_THISWEEK, $this->fakeUser);

        $range = new Report_Range(Report_Range::CURRENT_WEEK, null, null, $this->fakeUser->Timezone);
        $builder = $report->GetBuilder();

        $expected = new ReportCommandBuilder();
        $expected->SelectCount()
                ->OfResources()
                ->GroupByUser()
                ->Within($range->Start(), $range->End())
                ->LimitedTo(20);

        $this->assertEquals($expected, $builder);
    }

    public function testGetsTopUsersByCountThisMonth()
    {
        $report = new CannedReport(CannedReport::USER_COUNT_THISMONTH, $this->fakeUser);

        $range = new Report_Range(Report_Range::CURRENT_MONTH, null, null, $this->fakeUser->Timezone);
        $builder = $report->GetBuilder();

        $expected = new ReportCommandBuilder();
        $expected->SelectCount()
                ->OfResources()
                ->GroupByUser()
                ->Within($range->Start(), $range->End())
                ->LimitedTo(20);

        $this->assertEquals($expected, $builder);
    }

    public function testTodaysReservations()
    {
        $report = new CannedReport(CannedReport::RESERVATIONS_TODAY, $this->fakeUser);

        $range = new Report_Range(Report_Range::TODAY, null, null, $this->fakeUser->Timezone);
        $builder = $report->GetBuilder();

        $expected = new ReportCommandBuilder();
        $expected->SelectFullList()
                ->OfResources()
                ->Within($range->Start(), $range->End());

        $this->assertEquals($expected, $builder);
    }

    public function testThisWeeksReservations()
    {
        $report = new CannedReport(CannedReport::RESERVATIONS_THISWEEK, $this->fakeUser);

        $range = new Report_Range(Report_Range::CURRENT_WEEK, null, null, $this->fakeUser->Timezone);
        $builder = $report->GetBuilder();

        $expected = new ReportCommandBuilder();
        $expected->SelectFullList()
                ->OfResources()
                ->Within($range->Start(), $range->End());

        $this->assertEquals($expected, $builder);
    }

    public function testThisMonthsReservations()
    {
        $report = new CannedReport(CannedReport::RESERVATIONS_THISMONTH, $this->fakeUser);

        $range = new Report_Range(Report_Range::CURRENT_MONTH, null, null, $this->fakeUser->Timezone);
        $builder = $report->GetBuilder();

        $expected = new ReportCommandBuilder();
        $expected->SelectFullList()
                ->OfResources()
                ->Within($range->Start(), $range->End());

        $this->assertEquals($expected, $builder);
    }

    public function testTodaysAccessories()
    {
        $report = new CannedReport(CannedReport::ACCESSORIES_TODAY, $this->fakeUser);

        $range = new Report_Range(Report_Range::TODAY, null, null, $this->fakeUser->Timezone);
        $builder = $report->GetBuilder();

        $expected = new ReportCommandBuilder();
        $expected->SelectFullList()
                ->OfAccessories()
                ->Within($range->Start(), $range->End());

        $this->assertEquals($expected, $builder);
    }

    public function testThisWeeksAccessories()
    {
        $report = new CannedReport(CannedReport::ACCESSORIES_THISWEEK, $this->fakeUser);

        $range = new Report_Range(Report_Range::CURRENT_WEEK, null, null, $this->fakeUser->Timezone);
        $builder = $report->GetBuilder();

        $expected = new ReportCommandBuilder();
        $expected->SelectFullList()
                ->OfAccessories()
                ->Within($range->Start(), $range->End());

        $this->assertEquals($expected, $builder);
    }

    public function testThisMonthsAccessories()
    {
        $report = new CannedReport(CannedReport::ACCESSORIES_THISMONTH, $this->fakeUser);

        $range = new Report_Range(Report_Range::CURRENT_MONTH, null, null, $this->fakeUser->Timezone);
        $builder = $report->GetBuilder();

        $expected = new ReportCommandBuilder();
        $expected->SelectFullList()
                ->OfAccessories()
                ->Within($range->Start(), $range->End());

        $this->assertEquals($expected, $builder);
    }
}
