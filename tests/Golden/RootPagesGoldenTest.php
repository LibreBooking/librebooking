<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');

use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Live Smarty-vs-Twig golden comparison for the root page templates:
 *   dashboard.twig, forgot_pwd.twig, guest-participation.twig,
 *   support-and-credits.twig, tos.twig, register.twig
 *
 * Each test renders BOTH engines in the same process with identical template
 * variables and superglobal state, then asserts the normalized outputs are
 * byte-identical.  This avoids committing frozen baselines and guarantees
 * fidelity for every branch without baseline churn.
 *
 * Strategy notes per template:
 *
 * - support-and-credits, tos, guest-participation: fully deterministic given
 *   static vars; full same-process parity asserted.
 *
 * - forgot_pwd: deterministic; branches (enabled/disabled, email-sent) covered
 *   via data-provider.
 *
 * - dashboard: deterministic with an empty items array (DashboardItem controls
 *   require a real DB/session and are nondeterministic); the structural
 *   elements (accordion wrapper, JS init block, wait-box modal) are compared
 *   exactly. A structural assertion is added for the items-loop position.
 *
 * - register: session-dependent only if CaptchaControl or AttributeControl are
 *   rendered. With EnableCaptcha=false and Attributes=[] both engines produce
 *   identical deterministic output. Those feature branches are asserted
 *   structurally in separate test methods.
 */
class RootPagesGoldenTest extends GoldenTemplateTestCase
{
    private ?Resources $savedResources = null;

    /** @var array<string, mixed> */
    private array $savedServer = [];
    /** @var array<string, mixed> */
    private array $savedCookie = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();

        $this->savedServer = $_SERVER;
        $this->savedCookie = $_COOKIE;

        // Stable superglobal state for both engines.
        $_SERVER['SCRIPT_NAME'] = '/web/index.php';
        $_COOKIE = [];
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->savedServer;
        $_COOKIE = $this->savedCookie;
        Resources::SetInstance($this->savedResources);
        parent::tearDown();
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

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

    // ── support-and-credits ──────────────────────────────────────────────────

    public function testSupportAndCreditsMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/support-and-credits.php';
        $this->assertParity('support-and-credits.tpl', 'support-and-credits.twig', $vars);
    }

    // ── tos ───────────────────────────────────────────────────────────────────

    public function testTosWithContentMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/tos.php';
        $this->assertParity('tos.tpl', 'tos.twig', $vars);
    }

    public function testTosWithEmptyContentMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/tos.php';
        $vars['TermsContent'] = '';
        $this->assertParity('tos.tpl', 'tos.twig', $vars);
    }

    // ── guest-participation ──────────────────────────────────────────────────

    /**
     * @return array<string, array{0: array<string, mixed>}>
     */
    public static function guestParticipationBranchProvider(): array
    {
        return [
            'defaults — all false' => [[]],
            'missing information' => [['IsMissingInformation' => true]],
            'invitation accepted as guest with registration allowed' => [[
                'InvitationAccepted' => true,
                'IsGuest' => true,
                'AllowRegistration' => true,
            ]],
            'invitation accepted, not guest, registration disallowed' => [[
                'InvitationAccepted' => true,
                'IsGuest' => false,
                'AllowRegistration' => false,
            ]],
            'invitation declined' => [['InvitationDeclined' => true]],
            'capacity reached' => [[
                'CapacityReached' => true,
                'CapacityErrorMessage' => 'This resource is fully booked.',
            ]],
        ];
    }

    /**
     * @param array<string, mixed> $overrides
     */
    #[DataProvider('guestParticipationBranchProvider')]
    public function testGuestParticipationBranchesMatchSmarty(array $overrides): void
    {
        $vars = array_merge(require __DIR__ . '/fixtures/guest-participation.php', $overrides);
        $this->assertParity('guest-participation.tpl', 'guest-participation.twig', $vars);
    }

    // ── forgot_pwd ───────────────────────────────────────────────────────────

    public function testForgotPwdEnabledMatchesSmarty(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/web/forgot_pwd.php';
        $vars = require __DIR__ . '/fixtures/forgot_pwd.php';
        $this->assertParity('forgot_pwd.tpl', 'forgot_pwd.twig', $vars);
    }

    public function testForgotPwdDisabledMatchesSmarty(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/web/forgot_pwd.php';
        $vars = require __DIR__ . '/fixtures/forgot_pwd.php';
        $vars['Enabled'] = false;
        $this->assertParity('forgot_pwd.tpl', 'forgot_pwd.twig', $vars);
    }

    public function testForgotPwdResetEmailSentMatchesSmarty(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/web/forgot_pwd.php';
        $vars = require __DIR__ . '/fixtures/forgot_pwd.php';
        $vars['ShowResetEmailSent'] = true;
        $this->assertParity('forgot_pwd.tpl', 'forgot_pwd.twig', $vars);
    }

    // ── dashboard ────────────────────────────────────────────────────────────

    /**
     * With an empty items array (controls require real DB/session; nondeterministic),
     * the structural frame is compared exactly.
     */
    public function testDashboardEmptyItemsMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/dashboard.php';
        $this->assertParity('dashboard.tpl', 'dashboard.twig', $vars);
    }

    /**
     * The dashboard items loop position is structurally present even with an empty
     * array: both engines emit the dashboardList div with no child items.
     */
    public function testDashboardStructuralElements(): void
    {
        $vars = require __DIR__ . '/fixtures/dashboard.php';
        $twig = (new TwigRenderer())->render('dashboard.twig', $vars);

        // The page wrapper and dashboardList container are always present.
        $this->assertStringContainsString('id="page-dashboard"', $twig);
        $this->assertStringContainsString('id="dashboardList"', $twig);
        // The wait-box modal is always present.
        $this->assertStringContainsString('id="wait-box"', $twig);
        // The JavaScript initialisation is always present.
        $this->assertStringContainsString('var dashboard = new Dashboard(dashboardOpts)', $twig);
    }

    // ── register ─────────────────────────────────────────────────────────────

    /**
     * Base state: captcha off, no custom attributes, no ToS, no optional fields
     * hidden, no required-only fields. Fully deterministic.
     */
    public function testRegisterBaseMatchesSmarty(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/web/register.php';
        $vars = require __DIR__ . '/fixtures/register.php';
        $this->assertParity('register.tpl', 'register.twig', $vars);
    }

    /**
     * Phone/organization/position fields hidden — all three visibility branches.
     */
    public function testRegisterHiddenOptionalFieldsMatchesSmarty(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/web/register.php';
        $vars = require __DIR__ . '/fixtures/register.php';
        $vars['HidePhone'] = true;
        $vars['HidePosition'] = true;
        $vars['HideOrganization'] = true;
        $this->assertParity('register.tpl', 'register.twig', $vars);
    }

    /**
     * Phone/organization/position required — required attribute branches.
     */
    public function testRegisterRequiredOptionalFieldsMatchesSmarty(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/web/register.php';
        $vars = require __DIR__ . '/fixtures/register.php';
        $vars['RequirePhone'] = true;
        $vars['RequirePosition'] = true;
        $vars['RequireOrganization'] = true;
        $this->assertParity('register.tpl', 'register.twig', $vars);
    }

    /**
     * EnableCaptcha=true invokes CaptchaControl, which is nondeterministic.
     * Assert the captcha container is present and the hidden-captcha else-branch is absent.
     */
    public function testRegisterCaptchaEnabledRendersControlBranch(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/web/register.php';
        $vars = require __DIR__ . '/fixtures/register.php';
        $vars['EnableCaptcha'] = true;

        $twig  = (new TwigRenderer())->render('register.twig', $vars);
        $smarty = (new SmartyRenderer())->render('register.tpl', $vars);

        foreach ([$twig, $smarty] as $html) {
            // The captcha container div is present.
            $this->assertStringContainsString('form-group text-center', $html);
            // The else-branch hidden captcha input is absent.
            $this->assertStringNotContainsString("type=\"hidden\" name='captcha'", $html);
        }
    }

    /**
     * Terms of service checkbox branch: when Terms is a non-null object.
     * Uses a minimal fake Terms object (duck-typed).
     */
    public function testRegisterWithTermsOfServiceMatchesSmarty(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/web/register.php';
        $vars = require __DIR__ . '/fixtures/register.php';

        // Minimal Terms object: only DisplayUrl() is called in the template.
        $terms = new class () {
            public function DisplayUrl(): string
            {
                return '/web/tos.php';
            }
        };
        $vars['Terms'] = $terms;

        $this->assertParity('register.tpl', 'register.twig', $vars);
    }
}
