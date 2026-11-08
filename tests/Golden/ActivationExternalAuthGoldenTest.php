<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');

/**
 * Live Smarty-vs-Twig golden comparison for:
 *   - tpl/Activation/activation-sent.tpl  → tpl/Activation/activation-sent.twig
 *   - tpl/Activation/activation-error.tpl → tpl/Activation/activation-error.twig
 *   - tpl/ExternalAuth/external-login-error.tpl → tpl/ExternalAuth/external-login-error.twig
 *
 * Both engines are rendered in the same process with identical template
 * variables and superglobal state; normalized outputs are asserted byte-identical.
 *
 * Strategy notes:
 *
 * - activation-sent and activation-error: fully static (only globalheader,
 *   a translated heading, js-includes, globalfooter). A single branch covers
 *   each template completely.
 *
 * - external-login-error: conditionally branches only on the Errors loop
 *   (empty vs populated). Both branches are covered. Errors are plain-text
 *   strings (from Resources::GetString() or hardcoded literals in the
 *   presenter) — standard autoescaping via {{ error }} is correct; no |raw.
 *
 * All three templates are fully deterministic: no controls, no captcha, no
 * nondeterministic output. Full cross-engine parity is asserted for every case.
 */
class ActivationExternalAuthGoldenTest extends GoldenTemplateTestCase
{
    private ?Resources $savedResources = null;

    /** @var array<string, mixed> */
    private array $savedServer = [];

    protected function setUp(): void
    {
        parent::setUp();
        // Use real language strings, isolated from mocks installed by other suites.
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();

        $this->savedServer = $_SERVER;

        // Stable superglobal state for both engines.
        $_SERVER['SCRIPT_NAME'] = '/web/index.php';
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->savedServer;
        Resources::SetInstance($this->savedResources);
        parent::tearDown();
    }

    /**
     * Render both Smarty and Twig with the given vars and assert normalized parity.
     *
     * @param array<string, mixed> $vars
     */
    private function assertParity(string $tplName, string $twigName, array $vars): void
    {
        $expected = (new SmartyRenderer())->render($tplName, $vars);
        $actual   = (new TwigRenderer())->render($twigName, $vars);

        $this->assertSame(
            HtmlNormalizer::normalize($expected),
            HtmlNormalizer::normalize($actual),
            "Smarty vs Twig mismatch for $twigName"
        );
    }

    // ── activation-sent ───────────────────────────────────────────────────────

    /**
     * Fully static: only includes + one translated heading. Single case covers
     * the complete template.
     */
    public function testActivationSentMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/activation-sent.php';
        $this->assertParity(
            'Activation/activation-sent.tpl',
            'Activation/activation-sent.twig',
            $vars
        );
    }

    // ── activation-error ──────────────────────────────────────────────────────

    /**
     * Fully static: only includes + one translated heading. Single case covers
     * the complete template.
     */
    public function testActivationErrorMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/activation-error.php';
        $this->assertParity(
            'Activation/activation-error.tpl',
            'Activation/activation-error.twig',
            $vars
        );
    }

    // ── external-login-error ─────────────────────────────────────────────────

    /**
     * Errors populated: the foreach loop emits one div per error string.
     */
    public function testExternalLoginErrorWithErrorsMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/external-login-error.php';
        $this->assertParity(
            'ExternalAuth/external-login-error.tpl',
            'ExternalAuth/external-login-error.twig',
            $vars
        );
    }

    /**
     * Multiple errors: ensures the loop iterates correctly for more than one item.
     */
    public function testExternalLoginErrorMultipleErrorsMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/external-login-error.php';
        $vars['Errors'] = [
            'Invalid email domain.',
            'Self-registration is disabled.',
        ];
        $this->assertParity(
            'ExternalAuth/external-login-error.tpl',
            'ExternalAuth/external-login-error.twig',
            $vars
        );
    }

    /**
     * Empty errors array: the loop body is not emitted.
     */
    public function testExternalLoginErrorEmptyErrorsMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/external-login-error.php';
        $vars['Errors'] = [];
        $this->assertParity(
            'ExternalAuth/external-login-error.tpl',
            'ExternalAuth/external-login-error.twig',
            $vars
        );
    }
}
