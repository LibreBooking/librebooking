<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../lib/Email/namespace.php');

/**
 * Golden tests for email header/footer templates.
 *
 * emailheader.tpl / emailfooter.tpl have been migrated to .twig;
 * TwigRenderer::fetch() should select the .twig engine for these.
 * We compare Smarty-rendered .tpl output against Twig-rendered .twig output
 * to verify parity.
 *
 * Email body templates (lang/en_us/*.tpl) are NOT yet migrated; those
 * continue to fall back to Smarty via TwigRenderer::fetchLocalized().
 */
class EmailGoldenTest extends GoldenTemplateTestCase
{
    /** @var array<string, string> */
    private array $emailVars = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->emailVars = [
            'Charset' => 'UTF-8',
            'ScriptUrl' => 'http://localhost/',
            'AppTitle' => 'LibreBooking',
        ];
    }

    /**
     * Render both Smarty (.tpl) and Twig (.twig) with the given vars and
     * assert normalized-HTML parity.
     *
     * @param array<string, mixed> $vars
     */
    private function assertEmailParity(string $tplName, string $twigName, array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $expected = HtmlNormalizer::normalize($smarty->fetch($tplName));

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $actual = HtmlNormalizer::normalize($twig->fetch($tplName));

        $this->assertSame($expected, $actual, "Smarty vs Twig mismatch for $twigName");
    }

    /**
     * emailheader.twig must produce identical output to emailheader.tpl.
     */
    public function testEmailHeaderParity(): void
    {
        $this->assertEmailParity(
            'Email/emailheader.tpl',
            'Email/emailheader.twig',
            $this->emailVars
        );
    }

    /**
     * emailfooter.twig must produce identical output to emailfooter.tpl.
     */
    public function testEmailFooterParity(): void
    {
        $this->assertEmailParity(
            'Email/emailfooter.tpl',
            'Email/emailfooter.twig',
            $this->emailVars
        );
    }

    /**
     * TwigRenderer::fetch() must route Email/emailheader.tpl → Twig engine
     * when the .twig counterpart exists in the loader search path.
     */
    public function testFetchRoutesHeaderToTwig(): void
    {
        $renderer = new TwigRenderer();
        $renderer->assign('Charset', 'UTF-8');

        $result = $renderer->fetch('Email/emailheader.tpl');

        // The Twig version must contain the charset we assigned.
        $this->assertStringContainsString('UTF-8', $result);
        // Sanity-check it looks like an HTML email header.
        $this->assertStringContainsString('<html', $result);
        $this->assertStringContainsString('<body', $result);
    }

    /**
     * TwigRenderer::fetch() must route Email/emailfooter.tpl → Twig engine.
     */
    public function testFetchRoutesFooterToTwig(): void
    {
        $renderer = new TwigRenderer();
        $renderer->assign('Charset', 'UTF-8');

        $result = $renderer->fetch('Email/emailfooter.tpl');

        $this->assertStringContainsString('</html>', $result);
    }

    /**
     * TwigRenderer::fetchLocalized() selects the Twig engine for en_us email bodies
     * now that Phase 4b has migrated all 20 bodies to .twig.
     * This test verifies that ReportEmail.tpl routes to the .twig engine and
     * produces output matching the Smarty .tpl (parity).
     */
    public function testFetchLocalizedRoutesToTwigForMigratedBody(): void
    {
        $vars = [
            'CurrentLanguage' => 'en_us',
            'ScriptUrl' => 'http://localhost/',
            'AppTitle' => 'LibreBooking',
        ];

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigResult = $twig->fetchLocalized('ReportEmail.tpl', false, 'en_us');

        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyResult = $smarty->fetchLocalized('ReportEmail.tpl', false, 'en_us');

        $this->assertIsString($twigResult);
        $this->assertNotEmpty($twigResult);
        $this->assertSame(
            HtmlNormalizer::normalize($smartyResult),
            HtmlNormalizer::normalize($twigResult),
            'fetchLocalized must route en_us ReportEmail.tpl to Twig and match Smarty output'
        );
    }
}
