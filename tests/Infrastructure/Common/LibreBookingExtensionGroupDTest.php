<?php

require_once(__DIR__ . '/../../../lib/Common/namespace.php');

/**
 * Group D tests: asset includes & datatables.
 * Extends TestBase so setUp() wires ServiceLocator, FakeServer, FakeConfig, FakeResources.
 */
class LibreBookingExtensionGroupDTest extends TestBase
{
    private function makeEnv(string $template, string $rootPath = 'http://example.com/'): \Twig\Environment
    {
        $env = new \Twig\Environment(
            new \Twig\Loader\ArrayLoader(['t' => $template]),
            ['autoescape' => false]
        );
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), $rootPath));
        return $env;
    }

    /** Capture output echoed by a callable (for SmartyPage methods that echo). */
    private function captureEcho(callable $cb): string
    {
        ob_start();
        $cb();
        return (string) ob_get_clean();
    }

    // ── jsfile ────────────────────────────────────────────────────────────────

    public function testJsfileRendersScriptTag(): void
    {
        $env = $this->makeEnv("{{ jsfile(src='app.js') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('<script type="text/javascript"', $actual);
        $this->assertStringContainsString('scripts/app.js', $actual);
        $this->assertStringContainsString('?v=' . Configuration::VERSION, $actual);
        $this->assertStringContainsString('</script>', $actual);
    }

    public function testJsfileWithoutAsyncHasNoAsyncAttr(): void
    {
        $env = $this->makeEnv("{{ jsfile(src='app.js') }}");
        $actual = $env->render('t');

        $this->assertStringNotContainsString(' async', $actual);
    }

    public function testJsfileWithAsyncIncludesAsyncAttr(): void
    {
        $env = $this->makeEnv("{{ jsfile(src='app.js', async=true) }}");
        $actual = $env->render('t');

        $this->assertStringContainsString(' async', $actual);
    }

    public function testJsfileMatchesSmartyPageWithoutAsync(): void
    {
        $page = new SmartyPage(Resources::GetInstance(), 'http://example.com/');
        $expected = $this->captureEcho(fn () => $page->IncludeJavascriptFile(['src' => 'my.js'], null));

        $env = $this->makeEnv("{{ jsfile(src='my.js') }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testJsfileMatchesSmartyPageWithAsync(): void
    {
        $page = new SmartyPage(Resources::GetInstance(), 'http://example.com/');
        $expected = $this->captureEcho(fn () => $page->IncludeJavascriptFile(['src' => 'my.js', 'async' => true], null));

        $env = $this->makeEnv("{{ jsfile(src='my.js', async=true) }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // ── cssfile ───────────────────────────────────────────────────────────────

    public function testCssfileBarenameAddsCssPrefix(): void
    {
        $env = $this->makeEnv("{{ cssfile(src='style.css') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString("href='http://example.com/css/style.css", $actual);
        $this->assertStringContainsString('?v=' . Configuration::VERSION, $actual);
    }

    public function testCssfileWithPathDoesNotAddCssPrefix(): void
    {
        $env = $this->makeEnv("{{ cssfile(src='custom/theme.css') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString("href='http://example.com/custom/theme.css", $actual);
        $this->assertStringNotContainsString('css/custom/', $actual);
    }

    public function testCssfileRendersLinkTag(): void
    {
        $env = $this->makeEnv("{{ cssfile(src='style.css') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString("<link rel='stylesheet' type='text/css'", $actual);
        $this->assertStringContainsString('/>', $actual);
    }

    public function testCssfileMatchesSmartyPageBareName(): void
    {
        $page = new SmartyPage(Resources::GetInstance(), 'http://example.com/');
        $expected = $this->captureEcho(fn () => $page->IncludeCssFile(['src' => 'style.css'], null));

        $env = $this->makeEnv("{{ cssfile(src='style.css') }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testCssfileMatchesSmartyPageWithPath(): void
    {
        $page = new SmartyPage(Resources::GetInstance(), 'http://example.com/');
        $expected = $this->captureEcho(fn () => $page->IncludeCssFile(['src' => 'custom/theme.css'], null));

        $env = $this->makeEnv("{{ cssfile(src='custom/theme.css') }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // ── vendor_js ─────────────────────────────────────────────────────────────

    public function testVendorJsRendersScriptTagWithVendorPath(): void
    {
        $env = $this->makeEnv("{{ vendor_js(src='datatables/dataTables.min.js') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('assets/vendor/datatables/dataTables.min.js', $actual);
        $this->assertStringContainsString('?v=' . Configuration::VERSION, $actual);
    }

    public function testVendorJsMatchesSmartyPage(): void
    {
        $page = new SmartyPage(Resources::GetInstance(), 'http://example.com/');
        $expected = $this->captureEcho(fn () => $page->IncludeVendorJavascriptFile(['src' => 'lib.js'], null));

        $env = $this->makeEnv("{{ vendor_js(src='lib.js') }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testVendorJsWithAsyncMatchesSmartyPage(): void
    {
        $page = new SmartyPage(Resources::GetInstance(), 'http://example.com/');
        $expected = $this->captureEcho(fn () => $page->IncludeVendorJavascriptFile(['src' => 'lib.js', 'async' => true], null));

        $env = $this->makeEnv("{{ vendor_js(src='lib.js', async=true) }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // ── vendor_css ────────────────────────────────────────────────────────────

    public function testVendorCssRendersLinkTagWithVendorPath(): void
    {
        $env = $this->makeEnv("{{ vendor_css(src='bootstrap/bootstrap.min.css') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('assets/vendor/bootstrap/bootstrap.min.css', $actual);
        $this->assertStringContainsString('?v=' . Configuration::VERSION, $actual);
    }

    public function testVendorCssMatchesSmartyPage(): void
    {
        $page = new SmartyPage(Resources::GetInstance(), 'http://example.com/');
        $expected = $this->captureEcho(fn () => $page->IncludeVendorCssFile(['src' => 'vendor.css'], null));

        $env = $this->makeEnv("{{ vendor_css(src='vendor.css') }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // ── datatable ─────────────────────────────────────────────────────────────

    public function testDatatableRendersScriptTag(): void
    {
        $env = $this->makeEnv("{{ datatable(tableId='my-table') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('<script>', $actual);
        $this->assertStringContainsString('</script>', $actual);
        $this->assertStringContainsString('new DataTable("#my-table", {', $actual);
    }

    public function testDatatableDisablesClientSidePaging(): void
    {
        $env = $this->makeEnv("{{ datatable(tableId='users') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('"paging": false, "info": false,', $actual);
        $this->assertStringNotContainsString('"pageLength"', $actual);
    }

    public function testDatatableReportResultsUsesNoPagination(): void
    {
        $env = $this->makeEnv("{{ datatable(tableId='report-results') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('"paging": false', $actual);
        $this->assertStringContainsString('"ordering": false', $actual);
        $this->assertStringNotContainsString('"pageLength"', $actual);
    }

    public function testDatatableMatchesSmartyPageForNormalTable(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_PAGE_SIZE, 50);

        $page = new SmartyPage(Resources::GetInstance(), 'http://example.com/');
        $expected = $page->CreateDataTable(['tableId' => 'users-table']);

        $env = $this->makeEnv("{{ datatable(tableId='users-table') }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testDatatableMatchesSmartyPageForReportResults(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_PAGE_SIZE, 50);

        $page = new SmartyPage(Resources::GetInstance(), 'http://example.com/');
        $expected = $page->CreateDataTable(['tableId' => 'report-results']);

        $env = $this->makeEnv("{{ datatable(tableId='report-results') }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // ── pagination ────────────────────────────────────────────────────────────

    public function testPaginationRendersNothingWithoutResults(): void
    {
        $env = $this->makeEnv('{{ pagination(PageInfo) }}');

        $this->assertSame('', $env->render('t', ['PageInfo' => new PageInfo(0, 1, 10)]));
        $this->assertSame('', $env->render('t'));
    }

    public function testPaginationMatchesSmartyPage(): void
    {
        $this->fakeServer->Url = '/admin/users.php?page=2&pageSize=10&q=a b';
        $pageInfo = new PageInfo(35, 2, 10);

        $page = new SmartyPage(Resources::GetInstance(), 'http://example.com/');
        $expected = $page->CreatePagination(['pageInfo' => $pageInfo], null);

        $env = $this->makeEnv('{{ pagination(PageInfo) }}');
        $actual = $env->render('t', ['PageInfo' => $pageInfo]);

        $this->assertSame($expected, $actual);
        $this->assertStringContainsString('<li class="active page-item">', $actual);
        $this->assertStringContainsString('page=3&amp;pageSize=10', $actual);
    }

    // ── datatablefilter ───────────────────────────────────────────────────────

    public function testDatatablefilterRendersScriptTag(): void
    {
        $env = $this->makeEnv("{{ datatablefilter(tableId='filter-table') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('<script>', $actual);
        $this->assertStringContainsString('</script>', $actual);
        $this->assertStringContainsString('new DataTable("#filter-table", {', $actual);
    }

    public function testDatatablefilterIncludesPageLength(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_PAGE_SIZE, 25);

        $env = $this->makeEnv("{{ datatablefilter(tableId='filter-table') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('"pageLength": 25', $actual);
    }

    public function testDatatablefilterMatchesSmartyPage(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_PAGE_SIZE, 50);

        $page = new SmartyPage(Resources::GetInstance(), 'http://example.com/');
        $expected = $page->CreateDataTableFilter(['tableId' => 'filter-table']);

        $env = $this->makeEnv("{{ datatablefilter(tableId='filter-table') }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }
}
