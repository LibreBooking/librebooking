<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../lib/Application/Reporting/namespace.php');
require_once(__DIR__ . '/../../Pages/Pages.php');
require_once(__DIR__ . '/../../Presenters/Reports/ReportActions.php');
require_once(__DIR__ . '/../../Presenters/Reports/ReportCsvColumnView.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for Reports templates:
 *   - tpl/Reports/error.tpl                  → tpl/Reports/error.twig
 *   - tpl/Reports/chart.tpl                  → tpl/Reports/chart.twig
 *   - tpl/Reports/results-custom.tpl         → tpl/Reports/results-custom.twig
 *   - tpl/Reports/print-custom-report.tpl    → tpl/Reports/print-custom-report.twig
 *   - tpl/Reports/custom-csv.tpl             → tpl/Reports/custom-csv.twig
 *   - tpl/Reports/common-reports.tpl         → tpl/Reports/common-reports.twig
 *   - tpl/Reports/saved-reports.tpl          → tpl/Reports/saved-reports.twig
 *   - tpl/Reports/generate-report.tpl        → tpl/Reports/generate-report.twig
 *
 * Both engines are rendered in the same process with identical template variables
 * and superglobal state; normalized outputs are asserted byte-identical.
 *
 * Notes:
 * - Clock is pinned to 2025-06-15 10:00:00 UTC to make Date::Now() deterministic.
 * - Commented-out {* ... *} blocks in saved-reports.tpl are faithfully omitted in the .twig counterpart.
 */
class ReportsGoldenTest extends GoldenTemplateTestCase
{
    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Resources $savedResources = null;

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/index.php';
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();
        $this->savedServiceLocatorServer = ServiceLocator::GetServer();
        $fakeServer = new FakeServer();
        $fakeServer->UserSession->CSRFToken = 'golden-test-csrf-token';
        ServiceLocator::SetServer($fakeServer);
        Date::_SetNow(Date::Parse('2025-06-15 10:00:00', 'UTC'));
    }

    protected function tearDown(): void
    {
        $prop = new \ReflectionProperty(Date::class, '_Now');
        $prop->setAccessible(true);
        $prop->setValue(null, null);

        $_SERVER = $this->savedServer;
        Resources::SetInstance($this->savedResources);
        ServiceLocator::SetServer($this->savedServiceLocatorServer);
        parent::tearDown();
    }

    /**
     * Render both Smarty and Twig with the given vars and assert normalized parity.
     *
     * @param array<string, mixed> $vars
     */
    private function assertParity(string $tplName, string $twigName, array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $expected = $smarty->render($tplName);

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $actual = $twig->render($twigName);

        $this->assertSame(
            HtmlNormalizer::normalize($expected),
            HtmlNormalizer::normalize($actual),
            "Smarty vs Twig mismatch for $twigName"
        );
    }

    /**
     * Build a minimal IReport with one result row.
     */
    private function makeReport(): IReport
    {
        return new class () implements IReport {
            public function GetColumns(): IReportColumns
            {
                return new class () implements IReportColumns {
                    public function Exists($columnName): bool
                    {
                        return true;
                    }
                    /** @return string[] */
                    public function GetAll(): array
                    {
                        return [];
                    }
                    /** @return AttributeReportColumn[] */
                    public function GetCustomAttributes(): array
                    {
                        return [];
                    }
                };
            }
            public function GetData(): IReportData
            {
                return new class () implements IReportData {
                    /** @return array<int, array<string, mixed>> */
                    public function Rows(): array
                    {
                        return [['start' => 'Test Value']];
                    }
                };
            }
            public function ResultCount(): int
            {
                return 1;
            }
        };
    }

    /**
     * Build a minimal IReport with no results.
     */
    private function makeEmptyReport(): IReport
    {
        return new class () implements IReport {
            public function GetColumns(): IReportColumns
            {
                return new class () implements IReportColumns {
                    public function Exists($columnName): bool
                    {
                        return false;
                    }
                    /** @return string[] */
                    public function GetAll(): array
                    {
                        return [];
                    }
                    /** @return AttributeReportColumn[] */
                    public function GetCustomAttributes(): array
                    {
                        return [];
                    }
                };
            }
            public function GetData(): IReportData
            {
                return new class () implements IReportData {
                    /** @return array */
                    public function Rows(): array
                    {
                        return [];
                    }
                };
            }
            public function ResultCount(): int
            {
                return 0;
            }
        };
    }

    /**
     * Build a minimal IReportDefinition with one string column.
     */
    private function makeDefinition(): IReportDefinition
    {
        return new class () implements IReportDefinition {
            /** @return ReportColumn[] */
            public function GetColumnHeaders(): array
            {
                return [new ReportStringColumn('StartDate')];
            }
            /** @return ReportCell[] */
            public function GetRow($row): array
            {
                return [new ReportCell($row['start'] ?? 'Test Value', '1', 'string', '')];
            }
            public function GetTotal(): string
            {
                return '';
            }
            public function GetChartType(): string
            {
                return 'date';
            }
        };
    }

    // ── error ─────────────────────────────────────────────────────────────────

    public function testErrorMatchesSmarty(): void
    {
        $this->assertParity('Reports/error.tpl', 'Reports/error.twig', []);
    }

    // ── chart ─────────────────────────────────────────────────────────────────

    public function testChartMatchesSmarty(): void
    {
        $this->assertParity('Reports/chart.tpl', 'Reports/chart.twig', []);
    }

    // ── results-custom ────────────────────────────────────────────────────────

    public function testResultsCustomWithResultsMatchesSmarty(): void
    {
        $vars = [
            'Report'     => $this->makeReport(),
            'Definition' => $this->makeDefinition(),
            'HideSave'   => false,
        ];
        $this->assertParity('Reports/results-custom.tpl', 'Reports/results-custom.twig', $vars);
    }

    public function testResultsCustomHideSaveMatchesSmarty(): void
    {
        $vars = [
            'Report'     => $this->makeReport(),
            'Definition' => $this->makeDefinition(),
            'HideSave'   => true,
        ];
        $this->assertParity('Reports/results-custom.tpl', 'Reports/results-custom.twig', $vars);
    }

    public function testResultsCustomEmptyMatchesSmarty(): void
    {
        $vars = [
            'Report'     => $this->makeEmptyReport(),
            'Definition' => $this->makeDefinition(),
        ];
        $this->assertParity('Reports/results-custom.tpl', 'Reports/results-custom.twig', $vars);
    }

    // ── common-reports ────────────────────────────────────────────────────────

    public function testCommonReportsMatchesSmarty(): void
    {
        $vars = [
            'ScriptUrl'      => 'http://localhost/',
            'DateAxisFormat' => 'YYYY-MM-DD',
        ];
        $this->assertParity('Reports/common-reports.tpl', 'Reports/common-reports.twig', $vars);
    }

    // ── saved-reports ─────────────────────────────────────────────────────────

    public function testSavedReportsEmptyMatchesSmarty(): void
    {
        $vars = [
            'ReportList'     => [],
            'Path'           => '/',
            'ScriptUrl'      => 'http://localhost/',
            'DateAxisFormat' => 'YYYY-MM-DD',
            'untitled'       => 'Untitled',
            'UserEmail'      => 'test@example.com',
        ];
        $this->assertParity('Reports/saved-reports.tpl', 'Reports/saved-reports.twig', $vars);
    }

    public function testSavedReportsWithReportsMatchesSmarty(): void
    {
        $savedReport = new class () {
            public function Id(): int
            {
                return 42;
            }
            public function ReportName(): ?string
            {
                return 'My Report';
            }
            public function DateCreated(): Date
            {
                return Date::Parse('2025-01-15 09:00:00', 'UTC');
            }
        };

        $vars = [
            'ReportList'     => [$savedReport],
            'Path'           => '/',
            'ScriptUrl'      => 'http://localhost/',
            'DateAxisFormat' => 'YYYY-MM-DD',
            'untitled'       => 'Untitled',
            'UserEmail'      => 'test@example.com',
        ];
        $this->assertParity('Reports/saved-reports.tpl', 'Reports/saved-reports.twig', $vars);
    }

    // ── generate-report ───────────────────────────────────────────────────────

    public function testGenerateReportMatchesSmarty(): void
    {
        $vars = [
            'Resources'      => [],
            'ResourceTypes'  => [],
            'Accessories'    => [],
            'Schedules'      => [],
            'Groups'         => [],
            'Path'           => '/',
            'ScriptUrl'      => 'http://localhost/',
            'DateAxisFormat' => 'YYYY-MM-DD',
        ];
        $this->assertParity('Reports/generate-report.tpl', 'Reports/generate-report.twig', $vars);
    }

    // ── print-custom-report ───────────────────────────────────────────────────

    public function testPrintCustomReportMatchesSmarty(): void
    {
        $vars = [
            'HtmlLang'           => 'en',
            'HtmlTextDirection'  => 'ltr',
            'TitleKey'           => '',
            'Title'              => 'Test Report',
            'TitleArgs'          => [],
            'Charset'            => 'utf-8',
            'Report'             => $this->makeReport(),
            'Definition'         => $this->makeDefinition(),
            'ReportCsvColumnView' => new ReportCsvColumnView(''),
            'ScriptUrl'          => 'http://localhost/',
        ];
        $this->assertParity('Reports/print-custom-report.tpl', 'Reports/print-custom-report.twig', $vars);
    }

    // ── custom-csv ────────────────────────────────────────────────────────────

    public function testCustomCsvMatchesSmarty(): void
    {
        $vars = [
            'Report'              => $this->makeReport(),
            'Definition'          => $this->makeDefinition(),
            'ReportCsvColumnView' => new ReportCsvColumnView(''),
        ];
        $this->assertParity('Reports/custom-csv.tpl', 'Reports/custom-csv.twig', $vars);
    }
}
