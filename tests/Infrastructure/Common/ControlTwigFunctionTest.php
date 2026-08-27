<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../../lib/Common/namespace.php');

/**
 * Tests for the `control(type, params={})` Twig function added to LibreBookingExtension,
 * and the .twig/.tpl fallback in TwigRenderer::renderControlTemplate.
 */
class ControlTwigFunctionTest extends TestBase
{
    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /** Capture echoed output of a callable. */
    private function capture(callable $fn): string
    {
        ob_start();
        $fn();
        return (string) ob_get_clean();
    }

    /** Build a minimal Twig environment with LibreBookingExtension and the given renderer. */
    private function makeTwigEnv(
        string $template,
        ?\LibreBooking\Common\Templating\TemplateRenderer $renderer = null
    ): \Twig\Environment {
        $env = new \Twig\Environment(
            new \Twig\Loader\ArrayLoader(['t' => $template]),
            ['autoescape' => false]
        );
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), '', $renderer));
        return $env;
    }

    // -------------------------------------------------------------------------
    // Test 1: CaptchaControl — control() Twig function parity with DisplayControl
    // -------------------------------------------------------------------------

    /**
     * The Twig `control('CaptchaControl')` function should produce the same output
     * as SmartyPage::DisplayControl when captcha is disabled (default FakeConfig).
     *
     * CaptchaControl::PageLoad() echoes HTML directly (no template rendering),
     * so this test validates the ob_start/PageLoad/ob_get_clean pattern.
     */
    public function testControlFunctionCaptchaControlMatchesSmartyDisplayControl(): void
    {
        // Smarty side: capture DisplayControl output
        $smartyPage = new SmartyPage();
        $smartyExpected = $this->capture(
            fn () => $smartyPage->DisplayControl(['type' => 'CaptchaControl'], null)
        );

        // Twig side: the control() function needs a renderer.
        // Build a TwigRenderer to wire up; the extension receives it.
        $renderer = new TwigRenderer();
        $env = $this->makeTwigEnv("{{ control('CaptchaControl') }}", $renderer);
        $twigActual = $env->render('t');

        $this->assertSame($smartyExpected, $twigActual);
    }

    /**
     * Verify the control() function returns non-empty output for CaptchaControl.
     * When captcha is disabled, the captchaDiv HTML must be present.
     */
    public function testControlFunctionCaptchaControlOutputIsNonEmpty(): void
    {
        $renderer = new TwigRenderer();
        $env = $this->makeTwigEnv("{{ control('CaptchaControl') }}", $renderer);
        $output = $env->render('t');

        $this->assertNotEmpty($output);
        // When RECAPTCHA is disabled (FakeConfig default), showCaptcha() produces a #captchaDiv
        $this->assertStringContainsString('captchaDiv', $output);
    }

    // -------------------------------------------------------------------------
    // Test 2: CheckboxControl — .tpl fallback through TwigRenderer
    // -------------------------------------------------------------------------

    /**
     * CheckboxControl::PageLoad() calls $this->Display('Controls/Checkbox.tpl').
     * Since no Controls/Checkbox.twig exists, TwigRenderer::renderControlTemplate
     * must fall back to SmartyRenderer.
     *
     * This test proves:
     *  (a) the .twig/.tpl fallback path is exercised, and
     *  (b) the output matches what Smarty produces for the same control.
     */
    public function testControlFunctionCheckboxControlFallsBackToTpl(): void
    {
        // Params required by CheckboxControl::PageLoad()
        $params = ['name-key' => 'ALLOW_PARTICIPATION', 'label-key' => 'Yes'];

        // Smarty side
        $smartyPage = new SmartyPage();
        $smartyExpected = $this->capture(
            fn () => $smartyPage->DisplayControl(
                array_merge(['type' => 'CheckboxControl'], $params),
                null
            )
        );

        // Twig side via TwigRenderer (real filesystem loader — Checkbox.twig absent)
        $renderer = new TwigRenderer();
        $env = $this->makeTwigEnv("{{ control('CheckboxControl', params) }}", $renderer);
        $twigActual = $env->render('t', ['params' => $params]);

        // The output must be non-empty — the .tpl fallback rendered successfully.
        $this->assertNotEmpty($twigActual);

        // Parity with Smarty output.
        $this->assertSame($smartyExpected, $twigActual);
    }

    /**
     * Verify the CheckboxControl output contains expected structural HTML elements
     * when rendered through the Twig control() function with .tpl fallback.
     */
    public function testControlFunctionCheckboxControlOutputContainsExpectedHtml(): void
    {
        $params = ['name-key' => 'ALLOW_PARTICIPATION', 'label-key' => 'Yes'];

        $renderer = new TwigRenderer();
        $env = $this->makeTwigEnv("{{ control('CheckboxControl', params) }}", $renderer);
        $output = $env->render('t', ['params' => $params]);

        // Checkbox.tpl renders a <label> with a <button> inside it
        $this->assertStringContainsString('<label', $output);
        $this->assertStringContainsString('<button', $output);
        $this->assertStringContainsString('booked-checkbox', $output);
    }

    // -------------------------------------------------------------------------
    // Test 3: Twig-rendered template takes precedence over .tpl when .twig exists
    // -------------------------------------------------------------------------

    /**
     * When a .twig template exists for a control template name, TwigRenderer should
     * render the .twig version rather than falling back to Smarty.
     *
     * We inject a synthetic .twig template via ArrayLoader to verify the
     * "exists → render Twig" branch of renderControlTemplate.
     */
    public function testRenderControlTemplateUsesTwigWhenTwigTemplateExists(): void
    {
        $renderer = new TwigRenderer();

        // Inject a .twig template into the Twig loader that the renderer uses
        /** @var \Twig\Loader\FilesystemLoader $fsLoader */
        $fsLoader = $renderer->environment()->getLoader();

        // Wrap with a ChainLoader so we can add an array source
        $arrayLoader = new \Twig\Loader\ArrayLoader([
            'Controls/TestWidget.twig' => '<div class="twig-rendered">{{ greeting }}</div>',
        ]);
        $chain = new \Twig\Loader\ChainLoader([$arrayLoader, $fsLoader]);
        $renderer->environment()->setLoader($chain);

        $output = $renderer->renderControlTemplate('Controls/TestWidget.tpl', ['greeting' => 'hello']);

        $this->assertSame('<div class="twig-rendered">hello</div>', $output);
    }
}
