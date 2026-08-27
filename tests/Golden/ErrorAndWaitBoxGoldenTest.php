<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');

class ErrorAndWaitBoxGoldenTest extends GoldenTemplateTestCase
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

    public function testWaitBoxMatchesBaseline(): void
    {
        $vars = require __DIR__ . '/fixtures/wait-box.php';

        if (getenv('UPDATE_GOLDEN') === '1') {
            $this->captureSmartyBaseline('wait-box.tpl', $vars, 'wait-box');
            $this->markTestSkipped('Baseline captured from Smarty');
            return;
        }

        $twigRenderer = new TwigRenderer();
        $rendered = $twigRenderer->render('wait-box.twig', $vars);
        $this->assertMatchesBaseline('wait-box', $rendered);
    }

    public function testErrorMatchesBaseline(): void
    {
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['REQUEST_URI'] = '/web/error.php';

        $vars = require __DIR__ . '/fixtures/error.php';

        if (getenv('UPDATE_GOLDEN') === '1') {
            $this->captureSmartyBaseline('error.tpl', $vars, 'error');
            $this->markTestSkipped('Baseline captured from Smarty');
            return;
        }

        $twigRenderer = new TwigRenderer();
        $rendered = $twigRenderer->render('error.twig', $vars);
        $this->assertMatchesBaseline('error', $rendered);
    }
}
