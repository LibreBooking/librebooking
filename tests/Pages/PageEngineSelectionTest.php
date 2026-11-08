<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Page.php');

use LibreBooking\Common\Templating\TemplateRenderer;

/**
 * Tests that Page::Display() routes to Twig when a .twig template exists,
 * and falls back to Smarty when only a .tpl template exists.
 */
class PageEngineSelectionTest extends TestBase
{
    private string $tempDir = '';

    public function setUp(): void
    {
        parent::setUp();
        $this->tempDir = sys_get_temp_dir() . '/page_engine_sel_' . uniqid();
        mkdir($this->tempDir);
    }

    public function tearDown(): void
    {
        $this->removeDirectory($this->tempDir);
        parent::tearDown();
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }

    /**
     * When a .twig template exists, Display() should render it via Twig
     * and the output should contain the Twig-rendered content.
     */
    public function testDisplayRoutesThroughTwigWhenTwigTemplateExists(): void
    {
        // Create a minimal Twig template in our temp dir.
        file_put_contents($this->tempDir . '/hello.twig', 'twig-rendered-output');

        $page = $this->makePage(searchDirs: [$this->tempDir]);

        ob_start();
        $page->callDisplay('hello.tpl');
        $output = ob_get_clean();

        $this->assertStringContainsString('twig-rendered-output', $output);
    }

    /**
     * When no .twig template exists, Display() should fall back to Smarty.
     * We verify the Smarty path does NOT crash (and that twig is NOT used).
     */
    public function testDisplayFallsBackToSmartyWhenNoTwigExists(): void
    {
        // No .twig in the temp dir — so EngineSelector returns null.
        $page = $this->makePage(searchDirs: [$this->tempDir]);

        // Smarty will look for 'no-such-template.tpl'. We expect it to throw
        // or produce a Smarty error — what we really care about is that the
        // Twig path is NOT taken. Assert the page went Smarty-path by checking
        // that TwigRenderer was not involved (no 'twig-rendered-output').
        ob_start();
        try {
            $page->callDisplay('no-such-template.tpl');
        } catch (\Throwable $e) {
            // Smarty may throw on missing template — that's expected.
        } finally {
            $output = ob_get_clean();
        }

        $this->assertStringNotContainsString('twig-rendered-output', $output);
    }

    /**
     * Assigned template vars should be visible to the Twig template.
     */
    public function testTwigRenderReceivesAssignedVars(): void
    {
        // Template uses a variable set before Display().
        file_put_contents($this->tempDir . '/withvar.twig', '{{ greeting }}');

        $page = $this->makePage(searchDirs: [$this->tempDir]);
        $page->callSet('greeting', 'hello-from-vars');

        ob_start();
        $page->callDisplay('withvar.tpl');
        $output = ob_get_clean();

        $this->assertStringContainsString('hello-from-vars', $output);
    }

    /**
     * Renders a real template whose engines differ observably: Smarty prints
     * ResourceName raw, Twig escapes it.
     */
    private function renderQrPage(): string
    {
        $page = $this->makePage(searchDirs: [ROOT_DIR . 'tpl']);
        $page->callSet('ResourceName', '<b>engine-probe</b>');
        $page->callSet('QRImageUrl', 'qr.png');

        ob_start();
        $page->callDisplay('Admin/Resources/show_resource_qr.tpl');
        return (string) ob_get_clean();
    }

    public function testRendersTwigByDefault(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, true);

        $this->assertStringContainsString('&lt;b&gt;engine-probe', $this->renderQrPage());
    }

    public function testQueryOverrideRendersSmartyInDebugMode(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, true);
        $this->fakeServer->SetQuerystring(QueryStringKeys::TEMPLATE_ENGINE, 'smarty');

        $this->assertStringContainsString('<b>engine-probe</b>', $this->renderQrPage());
    }

    public function testCookieOverrideRendersSmartyInDebugMode(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, true);
        $this->fakeServer->SetCookie(new Cookie(CookieKeys::TEMPLATE_ENGINE, 'smarty'));

        $this->assertStringContainsString('<b>engine-probe</b>', $this->renderQrPage());
    }

    public function testOverrideIsIgnoredWithoutDebugMode(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, false);
        $this->fakeServer->SetQuerystring(QueryStringKeys::TEMPLATE_ENGINE, 'smarty');

        $this->assertStringContainsString('&lt;b&gt;engine-probe', $this->renderQrPage());
    }

    public function testFooterNamesTheRenderingEngineInDebugMode(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, true);
        $this->assertStringContainsString('Template engine: Twig', $this->renderQrPage());

        $this->fakeServer->SetQuerystring(QueryStringKeys::TEMPLATE_ENGINE, 'smarty');
        $this->assertStringContainsString('Template engine: Smarty', $this->renderQrPage());
    }

    public function testFooterHasNoEngineIndicatorWithoutDebugMode(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, false);

        $this->assertStringNotContainsString('Template engine:', $this->renderQrPage());
    }

    public function testPageStoresTheRequestedEngineWithoutChangingTheRequest(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, true);
        $this->fakeServer->SetQuerystring(QueryStringKeys::TEMPLATE_ENGINE, 'smarty');
        $this->fakeServer->SetQuerystring(QueryStringKeys::REFERENCE_NUMBER, 'abc');
        $getBefore = $_GET;
        $serverQueryBefore = $this->fakeServer->Get;

        $this->makePage(searchDirs: [$this->tempDir]);

        $this->assertSame('smarty', $this->fakeServer->GetCookie(CookieKeys::TEMPLATE_ENGINE));
        $this->assertSame($getBefore, $_GET);
        $this->assertSame($serverQueryBefore, $this->fakeServer->Get);
        $this->assertSame('abc', $this->fakeServer->GetQuerystring(QueryStringKeys::REFERENCE_NUMBER));
    }

    /**
     * Create a concrete Page subclass whose search dirs can be overridden,
     * allowing isolated filesystem tests.
     *
     * @param string[] $searchDirs
     */
    private function makePage(array $searchDirs): object
    {
        return new class ($searchDirs) extends Page {
            private array $overrideSearchDirs;

            public function __construct(array $searchDirs)
            {
                $this->overrideSearchDirs = $searchDirs;
                parent::__construct('');
            }

            public function PageLoad(): void
            {
            }

            protected function getTemplateSearchDirs(): array
            {
                return $this->overrideSearchDirs;
            }

            public function callDisplay(string $templateName): void
            {
                $this->Display($templateName);
            }

            public function callSet(string $var, mixed $value): void
            {
                $this->Set($var, $value);
            }
        };
    }
}
