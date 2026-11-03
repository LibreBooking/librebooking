<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reporting/namespace.php');

/**
 * Tests for the report results template shared by the generate, common and
 * saved report pages. Report cells contain profile data, which is stored
 * HTML-encoded by the web forms but raw when it comes from the API, so both
 * the cell text and the chart-value attribute must be escaped without
 * encoding existing entities a second time.
 */
class ResultsCustomTemplateTest extends TestBase
{
    public function testEscapesRawMarkupInCellText(): void
    {
        $output = $this->render(new ReportCell('<img src=x onerror=alert(1)>'));

        $this->assertStringContainsString('>&lt;img src=x onerror=alert(1)&gt;</td>', $output);
        $this->assertStringNotContainsString('<img', $output);
    }

    public function testEscapesAttributeBreakoutInChartValue(): void
    {
        $payload = '"><img src=x onerror=alert(1)>';

        $output = $this->render(new ReportCell($payload, $payload, ChartColumnType::Label, ChartGroup::Resource));

        $escaped = '&quot;&gt;&lt;img src=x onerror=alert(1)&gt;';
        $this->assertStringContainsString("chart-value=\"$escaped\"", $output);
        $this->assertStringContainsString(">$escaped</td>", $output);
        $this->assertStringNotContainsString('<img', $output);
    }

    public function testDoesNotDoubleEncodeStoredValues(): void
    {
        $stored = 'Smith &amp; O&#039;Brien';

        $output = $this->render(new ReportCell($stored, $stored));

        $this->assertStringContainsString("chart-value=\"$stored\"", $output);
        $this->assertStringContainsString(">$stored</td>", $output);
        $this->assertStringNotContainsString('&amp;amp;', $output);
    }

    private function render(ReportCell $cell): string
    {
        $definition = $this->createStub(IReportDefinition::class);
        $definition->method('GetColumnHeaders')->willReturn([new ReportStringColumn('User')]);
        $definition->method('GetRow')->willReturn([$cell]);
        $definition->method('GetChartType')->willReturn(ChartType::Total);
        $definition->method('GetTotal')->willReturn('');

        $data = $this->createStub(IReportData::class);
        $data->method('Rows')->willReturn([['owner' => 'value']]);

        $report = $this->createStub(IReport::class);
        $report->method('GetData')->willReturn($data);
        $report->method('ResultCount')->willReturn(1);

        $page = new SmartyPage();
        $page->assign('Definition', $definition);
        $page->assign('Report', $report);
        $page->assign('HideSave', true);

        return $page->fetch('Reports/results-custom.tpl');
    }
}
