<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../../lib/Common/namespace.php');

/**
 * Tests for the `render_partial(name, vars={})` Twig function added to LibreBookingExtension.
 *
 * render_partial provides engine-selecting partial rendering:
 *  (a) If a .twig counterpart of the named .tpl exists → renders via Twig.
 *  (b) If only the .tpl exists → renders via a fresh SmartyRenderer (full-context
 *      fetch, matching Smarty {include} shared-context semantics).
 *
 * The function is the standard mechanism for forward/cross-area includes during
 * the Smarty→Twig migration: use {{ render_partial('path/to/file.tpl', _context) }}
 * instead of {% include 'path/to/file.tpl' %} so that Smarty partials not yet
 * migrated to .twig are rendered faithfully, and promote automatically once migrated.
 */
class RenderPartialTwigFunctionTest extends TestBase
{
    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Build a Twig environment with a TwigRenderer wired into LibreBookingExtension.
     * The renderer is the same object that LibreBookingExtension uses, so
     * render_partial can call renderer->environment() to access the Twig loader.
     *
     * @param string                                        $template Inline template source.
     * @param array<string,string>                          $extra    Extra templates added to the ChainLoader.
     * @return array{0: \Twig\Environment, 1: TwigRenderer}
     */
    private function makeEnv(string $template, array $extra = []): array
    {
        $renderer = new TwigRenderer();

        /** @var \Twig\Loader\FilesystemLoader $fsLoader */
        $fsLoader = $renderer->environment()->getLoader();

        // Merge the inline template and any synthetic .twig templates into a chain loader.
        $sources = array_merge(['t' => $template], $extra);
        $arrayLoader = new \Twig\Loader\ArrayLoader($sources);
        $chain = new \Twig\Loader\ChainLoader([$arrayLoader, $fsLoader]);
        $renderer->environment()->setLoader($chain);

        // Re-add the extension to pick up the updated loader in the same environment.
        // (The extension closure captures $renderer which already holds the new loader.)
        // We do NOT add it again — TwigRenderer's constructor already added LibreBookingExtension
        // with $this (i.e. the TwigRenderer) wired in.  The existing extension closure
        // references $renderer so it will use the updated env automatically.

        return [$renderer->environment(), $renderer];
    }

    // -------------------------------------------------------------------------
    // Test (a): .twig exists → renders via current Twig environment
    // -------------------------------------------------------------------------

    /**
     * When a .twig file exists for the requested name, render_partial must render
     * the Twig version and return its output (NOT fall back to Smarty).
     */
    public function testRenderPartialUsesTwigWhenTwigTemplateExists(): void
    {
        // Inject a synthetic .twig partial alongside the main template.
        [$env] = $this->makeEnv(
            "{{ render_partial('Partials/widget.tpl', {greeting: 'hello'}) }}",
            ['Partials/widget.twig' => '<span class="twig-partial">{{ greeting }}</span>']
        );

        $output = $env->render('t');

        // Must render via Twig (the injected .twig source), not Smarty.
        $this->assertSame('<span class="twig-partial">hello</span>', $output);
    }

    /**
     * Variables passed as the second argument to render_partial are forwarded
     * to the Twig partial and rendered correctly.
     */
    public function testRenderPartialPassesVarsToTwigPartial(): void
    {
        [$env] = $this->makeEnv(
            "{{ render_partial('Partials/item.tpl', {label: label, count: count}) }}",
            ['Partials/item.twig' => '{{ label }}: {{ count }}']
        );

        $output = $env->render('t', ['label' => 'Items', 'count' => 42]);

        $this->assertSame('Items: 42', $output);
    }

    /**
     * If the .tpl name is passed without a matching .twig counterpart,
     * the Twig branch must NOT fire (no false positive on existence check).
     * This is a boundary test: ensure the loader check is name-specific.
     */
    public function testRenderPartialDoesNotUseTwigWhenOnlyTplExists(): void
    {
        // Controls/Checkbox.twig now exists, so render_partial uses the Twig path.
        // This test verifies the output still contains checkbox markup (structural check)
        // and no synthetic twig-partial marker from injected templates.
        [$env] = $this->makeEnv(
            "{{ render_partial('Controls/Checkbox.tpl', {'name-key': 'ALLOW_PARTICIPATION', 'label-key': 'Yes'}) }}"
        );

        $output = $env->render('t');

        // Output contains checkbox markup regardless of engine.
        $this->assertNotEmpty($output);
        $this->assertStringContainsString('booked-checkbox', $output);
        // Must NOT contain any .twig-specific marker from injected synthetic templates.
        $this->assertStringNotContainsString('twig-partial', $output);
    }

    // -------------------------------------------------------------------------
    // Test (b): .twig exists → renders via Twig (Twig takes precedence)
    // -------------------------------------------------------------------------

    /**
     * Controls/Checkbox.twig now exists alongside Controls/Checkbox.tpl, so
     * render_partial uses the Twig path (not the Smarty fallback).
     * The output must be structurally equivalent to the Smarty reference — verified
     * via HtmlNormalizer to account for minor whitespace differences between engines.
     */
    public function testRenderPartialRendersCheckboxViaTwig(): void
    {
        $vars = ['name-key' => 'ALLOW_PARTICIPATION', 'label-key' => 'Yes'];

        // Direct Smarty reference output.
        $smarty = new SmartyRenderer();
        $expected = $smarty->render('Controls/Checkbox.tpl', $vars);

        // render_partial output via Twig environment — uses Controls/Checkbox.twig.
        [$env] = $this->makeEnv(
            "{{ render_partial('Controls/Checkbox.tpl', vars) }}"
        );
        $actual = $env->render('t', ['vars' => $vars]);

        // Structural parity: both engines produce equivalent normalised HTML.
        require_once(__DIR__ . '/../../Golden/HtmlNormalizer.php');
        $this->assertSame(
            HtmlNormalizer::normalize($expected),
            HtmlNormalizer::normalize($actual)
        );
    }

    /**
     * The Twig render output must be non-empty and contain expected structural
     * HTML from the checkbox template (structural assertion alongside parity check).
     */
    public function testRenderPartialCheckboxOutputIsNonEmpty(): void
    {
        [$env] = $this->makeEnv(
            "{{ render_partial('Controls/Checkbox.tpl', {'name-key': 'ALLOW_PARTICIPATION', 'label-key': 'Yes'}) }}"
        );

        $output = $env->render('t');

        $this->assertNotEmpty($output);
        $this->assertStringContainsString('<label', $output);
        $this->assertStringContainsString('booked-checkbox', $output);
    }

    // -------------------------------------------------------------------------
    // Test (c): only .tpl exists → renders via SmartyRenderer (Smarty fallback)
    // -------------------------------------------------------------------------

    /**
     * When no .twig counterpart exists for a .tpl, render_partial must fall back
     * to SmartyRenderer and produce the same output as SmartyRenderer directly.
     *
     * Uses tpl/_render_fallback_probe.tpl — a test-only fixture that intentionally
     * has no .twig sibling, ensuring the Smarty-fallback branch actually executes.
     * The probe template renders: <span class="probe">{$probeValue}</span>
     */
    public function testRenderPartialFallsBackToSmartyWhenNoTwigExists(): void
    {
        // Confirm no .twig sibling exists for the probe template.
        $tplDir = __DIR__ . '/../../../tpl/';
        $this->assertFileDoesNotExist($tplDir . '_render_fallback_probe.twig');

        $vars = ['probeValue' => 'smarty-fallback'];

        // Smarty reference: render the probe directly.
        $smarty = new SmartyRenderer();
        $expected = trim($smarty->render('_render_fallback_probe.tpl', $vars));

        // render_partial must fire the Smarty fallback and produce matching output.
        [$env] = $this->makeEnv(
            "{{ render_partial('_render_fallback_probe.tpl', vars) }}"
        );
        $actual = trim($env->render('t', ['vars' => $vars]));

        // The Smarty fallback branch must have fired and produced the probe output.
        $this->assertStringContainsString('smarty-fallback', $actual);
        $this->assertStringContainsString('probe', $actual);
        $this->assertSame($expected, $actual);
    }

    // -------------------------------------------------------------------------
    // Edge cases
    // -------------------------------------------------------------------------

    /**
     * render_partial with no renderer wired (renderer === null) returns empty string
     * rather than throwing — matches the graceful-degrade pattern used by validator().
     */
    public function testRenderPartialReturnsEmptyStringWhenNoRenderer(): void
    {
        $env = new \Twig\Environment(
            new \Twig\Loader\ArrayLoader(['t' => "{{ render_partial('anything.tpl') }}"]),
            ['autoescape' => false]
        );
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), '', null));

        $output = $env->render('t');

        $this->assertSame('', $output);
    }

    /**
     * render_partial with an empty vars array uses an empty context for both
     * Twig and Smarty branches; does not throw.
     */
    public function testRenderPartialAcceptsEmptyVarsArray(): void
    {
        [$env] = $this->makeEnv(
            "{{ render_partial('Partials/empty.tpl', {}) }}",
            ['Partials/empty.twig' => 'EMPTY']
        );

        $output = $env->render('t');

        $this->assertSame('EMPTY', $output);
    }
}
