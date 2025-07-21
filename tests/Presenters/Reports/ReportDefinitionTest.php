<?php

require_once(ROOT_DIR . 'Presenters/Reports/GenerateReportPresenter.php');

class ReportDefinitionTest extends TestBase
{
    /**
     * @var FakeAttributeRepository
     */
    private $attributeRepository;

    public function setUp(): void
    {
        $this->attributeRepository = new FakeAttributeRepository();
        parent::setup();
    }

    public function testGetsColumns()
    {
        $rows = [[ColumnNames::ACCESSORY_NAME => 'an', ColumnNames::RESOURCE_NAME_ALIAS => 'rn', 'unknown' => 'unknown']];
        $report = new CustomReport($rows, $this->attributeRepository);

        $definition = new ReportDefinition($report, null);

        $headerKeys = $definition->GetColumnHeaders();

        $this->assertEquals(2, count($headerKeys));
    }

    public function testGetsColumnsWithCustomAttributes()
    {
        $rows = [[ColumnNames::ACCESSORY_NAME => 'an', 'unknown' => 'unknown', ColumnNames::ATTRIBUTE_LIST => '1=1!sep!2=!sep!3=']];
        $report = new CustomReport($rows, $this->attributeRepository);

        $definition = new ReportDefinition($report, null);

        $headerKeys = $definition->GetColumnHeaders();

        $this->assertEquals(3, count($headerKeys));
        $this->assertEquals('test attribute', $headerKeys['1attribute1']->Title());
        $this->assertEquals('test attribute2', $headerKeys['1attribute2']->Title());
    }

    public function testGetsColumnsWithUserCustomAttributes()
    {
        $rows = [[ColumnNames::ACCESSORY_NAME => 'an', 'unknown' => 'unknown', ColumnNames::USER_ATTRIBUTE_LIST => '1=1!sep!2=!sep!3=']];
        $report = new CustomReport($rows, $this->attributeRepository);

        $definition = new ReportDefinition($report, null);

        $headerKeys = $definition->GetColumnHeaders();

        $this->assertEquals(3, count($headerKeys));
        $this->assertEquals('test attribute', $headerKeys['2attribute1']->Title());
        $this->assertEquals('test attribute2', $headerKeys['2attribute2']->Title());
    }

    public function testGetsColumnsWithResourceCustomAttributes()
    {
        $rows = [[ColumnNames::ACCESSORY_NAME => 'an', 'unknown' => 'unknown', ColumnNames::RESOURCE_ATTRIBUTE_LIST => '1=1!sep!2=!sep!3=']];
        $report = new CustomReport($rows, $this->attributeRepository);

        $definition = new ReportDefinition($report, null);

        $headerKeys = $definition->GetColumnHeaders();

        $this->assertEquals(3, count($headerKeys));
        $this->assertEquals('test attribute', $headerKeys['4attribute1']->Title());
        $this->assertEquals('test attribute2', $headerKeys['4attribute2']->Title());
    }

    public function testGetsColumnsWithResourceTypeCustomAttributes()
    {
        $rows = [[ColumnNames::ACCESSORY_NAME => 'an', 'unknown' => 'unknown', ColumnNames::RESOURCE_TYPE_ATTRIBUTE_LIST => '1=1!sep!2=!sep!3=']];
        $report = new CustomReport($rows, $this->attributeRepository);

        $definition = new ReportDefinition($report, null);

        $headerKeys = $definition->GetColumnHeaders();

        $this->assertEquals(3, count($headerKeys));
        $this->assertEquals('test attribute', $headerKeys['5attribute1']->Title());
        $this->assertEquals('test attribute2', $headerKeys['5attribute2']->Title());
    }

    public function testOrdersAndFormatsData()
    {
        $timezone = 'America/Chicago';
        $date = '2012-02-14 08:12:31';
        $oneHourThirtyMinutes = TimeInterval::Parse("1h30m");
        $userId = 100;

        $rows = [[
                ColumnNames::RESERVATION_START => $date,
                ColumnNames::OWNER_FULL_NAME_ALIAS => 'un',
                ColumnNames::OWNER_USER_ID => $userId,
                ColumnNames::ACCESSORY_NAME => 'an',
                'unknown' => 'unknown',
                ColumnNames::TOTAL_TIME => $oneHourThirtyMinutes->TotalSeconds(),
                ColumnNames::ACCESSORY_ID => 1,
        ]];
        $report = new CustomReport($rows, $this->attributeRepository);

        $definition = new ReportDefinition($report, $timezone);

        /** @var ReportCell[] $row */
        $row = $definition->GetRow($rows[0]);

        $this->assertEquals(4, count($row));
        $this->assertEquals('an', $row[0]->Value());

        $format = Resources::GetInstance()->ShortDateTimeFormat();
        $systemFormat = Resources::GetInstance()->GeneralDateFormat();

        $this->assertEquals(Date::FromDatabase($date)->ToTimezone($timezone)->Format($format), $row[1]->Value());
        $this->assertEquals(Date::FromDatabase($date)->ToTimezone($timezone)->GetDate()->ToIso(), $row[1]->ChartValue());
        $this->assertEquals(ChartColumnType::Date, $row[1]->GetChartColumnType());
        $this->assertNull($row[1]->GetChartGroup());

        $this->assertEquals('un', $row[2]->Value());
        $this->assertEquals($userId, $row[2]->ChartValue());
        $this->assertEquals(ChartColumnType::Label, $row[2]->GetChartColumnType());
        $this->assertEmpty($row[2]->GetChartGroup());

        $this->assertEquals($oneHourThirtyMinutes->ToString(true), $row[3]->Value());
        $this->assertEquals($oneHourThirtyMinutes->TotalSeconds(), $row[3]->ChartValue());
        $this->assertEquals(ChartColumnType::Total, $row[3]->GetChartColumnType());
    }

    public function testGetChartTypeBasedOnReportData()
    {
        $timezone = 'UTC';
        $totalReport = new CustomReport([[ColumnNames::TOTAL => 1]], $this->attributeRepository);
        $timeReport = new CustomReport([[ColumnNames::TOTAL_TIME => 1]], $this->attributeRepository);
        $reservationReport = new CustomReport([[ColumnNames::RESERVATION_START => 1]], $this->attributeRepository);

        $totalDefinition = new ReportDefinition($totalReport, $timezone);
        $timeDefinition = new ReportDefinition($timeReport, $timezone);
        $reservationDefinition = new ReportDefinition($reservationReport, $timezone);

        $this->assertEquals(ChartType::Total, $totalDefinition->GetChartType());
        $this->assertEquals(ChartType::TotalTime, $timeDefinition->GetChartType());
        $this->assertEquals(ChartType::Date, $reservationDefinition->GetChartType());
    }

    public function testGetsRowDataForCustomAttributes()
    {
        $rows = [[
                    ColumnNames::ACCESSORY_NAME => 'an',
                    ColumnNames::ACCESSORY_ID => 1,
                    ColumnNames::ATTRIBUTE_LIST => '1=1!sep!2=!sep!3=3']];

        $report = new CustomReport($rows, $this->attributeRepository);

        $definition = new ReportDefinition($report, null);

        /** @var ReportCell[] $row */
        $row = $definition->GetRow($rows[0]);

        $this->assertEquals(3, count($row));
        $this->assertEquals('an', $row[0]->Value());
        $this->assertEquals('1', $row[1]->Value());
        $this->assertEquals(null, $row[2]->Value(), 'there is no 3rd attribute in the fake');
    }

    public function testGetsRowDataForUserCustomAttributes()
    {
        $rows = [[
                    ColumnNames::ACCESSORY_NAME => 'an',
                    ColumnNames::ACCESSORY_ID => 1,
                    ColumnNames::USER_ATTRIBUTE_LIST => '1=1!sep!2=!sep!3=3']];

        $report = new CustomReport($rows, $this->attributeRepository);

        $definition = new ReportDefinition($report, null);

        /** @var ReportCell[] $row */
        $row = $definition->GetRow($rows[0]);

        $this->assertEquals(3, count($row));
        $this->assertEquals('an', $row[0]->Value());
        $this->assertEquals('1', $row[1]->Value());
        $this->assertEquals(null, $row[2]->Value(), 'there is no 3rd attribute in the fake');
    }

    public function testGetsRowDataForResourceCustomAttributes()
    {
        $rows = [[
                    ColumnNames::ACCESSORY_NAME => 'an',
                    ColumnNames::ACCESSORY_ID => 1,
                    ColumnNames::RESOURCE_ATTRIBUTE_LIST => '1=1!sep!2=!sep!3=3']];

        $report = new CustomReport($rows, $this->attributeRepository);

        $definition = new ReportDefinition($report, null);

        /** @var ReportCell[] $row */
        $row = $definition->GetRow($rows[0]);

        $this->assertEquals(3, count($row));
        $this->assertEquals('an', $row[0]->Value());
        $this->assertEquals('1', $row[1]->Value());
        $this->assertEquals(null, $row[2]->Value(), 'there is no 3rd attribute in the fake');
    }

    public function testGetsRowDataForResourceTypeCustomAttributes()
    {
        $rows = [[
                    ColumnNames::ACCESSORY_NAME => 'an',
                    ColumnNames::ACCESSORY_ID => 1,
                    ColumnNames::RESOURCE_TYPE_ATTRIBUTE_LIST => '1=1!sep!2=!sep!3=3']];

        $report = new CustomReport($rows, $this->attributeRepository);

        $definition = new ReportDefinition($report, null);

        /** @var ReportCell[] $row */
        $row = $definition->GetRow($rows[0]);

        $this->assertEquals(3, count($row));
        $this->assertEquals('an', $row[0]->Value());
        $this->assertEquals('1', $row[1]->Value());
        $this->assertEquals(null, $row[2]->Value(), 'there is no 3rd attribute in the fake');
    }
}
