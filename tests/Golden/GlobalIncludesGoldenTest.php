<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');

class GlobalIncludesGoldenTest extends GoldenTemplateTestCase
{
    private ?Resources $savedResources = null;

    protected function setUp(): void
    {
        parent::setUp();
        // Save the current Resources singleton and install a fresh one so that
        // translation lookups are not affected by mocked instances from other suites.
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance(); // triggers Create() → loads real language strings
    }

    protected function tearDown(): void
    {
        // Restore the original singleton to avoid polluting downstream tests.
        Resources::SetInstance($this->savedResources);
        parent::tearDown();
    }

    public function testGlobalheaderMatchesBaseline(): void
    {
        $_SERVER['REQUEST_URI'] = '/web/index.php';

        $vars = require __DIR__ . '/fixtures/globalheader.php';

        if (getenv('UPDATE_GOLDEN') === '1') {
            $this->captureSmartyBaseline('globalheader.tpl', $vars, 'globalheader');
            $this->markTestSkipped('Baseline captured from Smarty');
            return;
        }

        $twigRenderer = new TwigRenderer();
        $rendered = $twigRenderer->render('globalheader.twig', $vars);
        $this->assertMatchesBaseline('globalheader', $rendered);
    }

    public function testGlobalfooterMatchesBaseline(): void
    {
        $vars = require __DIR__ . '/fixtures/globalfooter.php';

        if (getenv('UPDATE_GOLDEN') === '1') {
            $this->captureSmartyBaseline('globalfooter.tpl', $vars, 'globalfooter');
            $this->markTestSkipped('Baseline captured from Smarty');
            return;
        }

        $twigRenderer = new TwigRenderer();
        $rendered = $twigRenderer->render('globalfooter.twig', $vars);
        $this->assertMatchesBaseline('globalfooter', $rendered);
    }

    public function testJavascriptIncludesMatchesBaseline(): void
    {
        $vars = require __DIR__ . '/fixtures/javascript-includes.php';

        if (getenv('UPDATE_GOLDEN') === '1') {
            $this->captureSmartyBaseline('javascript-includes.tpl', $vars, 'javascript-includes');
            $this->markTestSkipped('Baseline captured from Smarty');
            return;
        }

        $twigRenderer = new TwigRenderer();
        $rendered = $twigRenderer->render('javascript-includes.twig', $vars);
        $this->assertMatchesBaseline('javascript-includes', $rendered);
    }
}
