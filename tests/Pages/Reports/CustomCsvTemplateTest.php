<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reporting/namespace.php');
require_once(ROOT_DIR . 'Presenters/Reports/ReportCsvColumnView.php');

/**
 * Tests for the report CSV export template shared by the generate, common and
 * saved report pages. The expected strings pin the exact bytes rendered so
 * template refactoring cannot silently change the exported data.
 */
class CustomCsvTemplateTest extends TestBase
{
    private const COLUMN_SEPARATOR = '!s!';

    public function testRendersAllColumnsWhenNoneSelected(): void
    {
        $output = $this->render(selectedColumns: '');

        $this->assertSame(self::EXPECTED_ALL_COLUMNS_CSV, $output);
        $this->assertEveryRowMatchesHeaderColumnCount($output);
    }

    public function testRendersOnlySelectedColumns(): void
    {
        $output = $this->render(selectedColumns: implode(self::COLUMN_SEPARATOR, ['ResourceName', "Owner's \"Note\""]));

        $this->assertSame(self::EXPECTED_SELECTED_COLUMNS_CSV, $output);
        $this->assertEveryRowMatchesHeaderColumnCount($output);
    }

    public function testOmitsSeparatorBeforeFirstShownColumnWhenFirstColumnIsHidden(): void
    {
        $output = $this->render(selectedColumns: implode(self::COLUMN_SEPARATOR, ['Title', "Owner's \"Note\""]));

        $this->assertSame(self::EXPECTED_FIRST_COLUMN_HIDDEN_CSV, $output);
        $this->assertEveryRowMatchesHeaderColumnCount($output);
    }

    private function render(string $selectedColumns): string
    {
        $columns = [
            new ReportStringColumn('ResourceName'),
            new ReportStringColumn('Title'),
            new ReportAttributeColumn("Owner's \"Note\""),
        ];
        $rows = [
            ['resource' => "Room 'A', North", 'title' => 'Kickoff "Q2"', 'note' => 'A & B <b>'],
            ['resource' => 'Projector', 'title' => '', 'note' => ''],
        ];

        $definition = $this->createStub(IReportDefinition::class);
        $definition->method('GetColumnHeaders')->willReturn($columns);
        $definition->method('GetRow')->willReturnCallback(
            fn (array $row): array => array_map(fn (string $value): ReportCell => new ReportCell($value), array_values($row))
        );

        $data = $this->createStub(IReportData::class);
        $data->method('Rows')->willReturn($rows);

        $report = $this->createStub(IReport::class);
        $report->method('GetData')->willReturn($data);

        $page = new SmartyPage();
        $page->assign('Definition', $definition);
        $page->assign('Report', $report);
        $page->assign('ReportCsvColumnView', new ReportCsvColumnView($selectedColumns));

        return $page->fetch('Reports/custom-csv.tpl');
    }

    private function assertEveryRowMatchesHeaderColumnCount(string $csv): void
    {
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $csv);
        rewind($handle);

        $rows = [];
        while (($row = fgetcsv($handle, null, ',', '"', '')) !== false) {
            $rows[] = $row;
        }
        fclose($handle);

        $headerCount = count($rows[0]);
        foreach ($rows as $index => $row) {
            $this->assertCount($headerCount, $row, "CSV row $index does not match the header column count");
        }
    }

    private const EXPECTED_ALL_COLUMNS_CSV = <<<'CSV'
"ResourceName","Title","Owner's ""Note"""
"Room 'A', North","Kickoff ""Q2""","A & B <b>"
"Projector","",""
CSV . "\n";

    private const EXPECTED_SELECTED_COLUMNS_CSV = <<<'CSV'
"ResourceName","Owner's ""Note"""
"Room 'A', North","A & B <b>"
"Projector",""
CSV . "\n";

    private const EXPECTED_FIRST_COLUMN_HIDDEN_CSV = <<<'CSV'
"Title","Owner's ""Note"""
"Kickoff ""Q2""","A & B <b>"
"",""
CSV . "\n";
}
