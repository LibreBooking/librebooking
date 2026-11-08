<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../Domain/Announcement.php');

use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Live Smarty-vs-Twig golden comparison for the login template.
 *
 * Unlike a stored-baseline golden test, each case renders BOTH engines in the
 * same process with identical template variables and superglobal state, then
 * asserts the normalized outputs are byte-identical. This guarantees the Twig
 * conversion is faithful for every branch without committing a frozen baseline.
 *
 * The captcha branch (EnableCaptcha=true) is handled separately: CaptchaControl
 * emits nondeterministic content (a random Securimage image URL or a recaptcha
 * key from config), so exact cross-engine parity is impossible. That case is
 * asserted structurally instead — see testEnableCaptchaTrueRendersControlBranch.
 */
class LoginGoldenTest extends GoldenTemplateTestCase
{
    private ?Resources $savedResources = null;

    /** @var array<string, mixed> */
    private array $savedServer = [];
    /** @var array<string, mixed> */
    private array $savedCookie = [];

    protected function setUp(): void
    {
        parent::setUp();
        // Use real language strings, isolated from mocks installed by other suites.
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();

        $this->savedServer = $_SERVER;
        $this->savedCookie = $_COOKIE;

        // Identical state for both engines: Smarty reads $smarty.server/$smarty.cookies,
        // Twig reads the `server`/`cookies` globals — both are backed by these superglobals.
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

    /**
     * @return array<string, mixed>
     */
    private function baseVars(): array
    {
        return require __DIR__ . '/fixtures/login.php';
    }

    private function renderBoth(array $vars): void
    {
        $expected = (new SmartyRenderer())->render('login.tpl', $vars);
        $actual = (new TwigRenderer())->render('login.twig', $vars);

        $this->assertSame(
            HtmlNormalizer::normalize($expected),
            HtmlNormalizer::normalize($actual)
        );
    }

    /**
     * Every case below keeps EnableCaptcha=false so CaptchaControl (nondeterministic)
     * is never invoked, allowing exact cross-engine parity.
     *
     * @return array<string, array{0: array<string, mixed>}>
     */
    public static function branchProvider(): array
    {
        return [
            'defaults' => [[]],
            'ShowLoginError, empty message (falls back to translated LoginError)' => [[
                'ShowLoginError' => true,
                'LoginErrorMessage' => '',
            ]],
            'ShowLoginError, explicit message' => [[
                'ShowLoginError' => true,
                'LoginErrorMessage' => 'Invalid credentials please retry',
            ]],
            'no username prompt' => [['ShowUsernamePrompt' => false]],
            'no password prompt' => [['ShowPasswordPrompt' => false]],
            'neither username nor password prompt' => [[
                'ShowUsernamePrompt' => false,
                'ShowPasswordPrompt' => false,
            ]],
            'register link shown' => [[
                'ShowRegisterLink' => true,
                'RegisterUrl' => 'register.php',
            ]],
            'register link shown with external RegisterUrlNew attribute' => [[
                'ShowRegisterLink' => true,
                'RegisterUrl' => 'https://external.example.com/register',
                'RegisterUrlNew' => "target='_new'",
            ]],
            'google login' => [['AllowGoogleLogin' => true]],
            'microsoft login' => [['AllowMicrosoftLogin' => true]],
            'facebook login' => [['AllowFacebookLogin' => true]],
            'keycloak login' => [['AllowKeycloakLogin' => true]],
            'oauth2 login' => [['AllowOauth2Login' => true]],
            'all social logins' => [[
                'AllowGoogleLogin' => true,
                'AllowMicrosoftLogin' => true,
                'AllowFacebookLogin' => true,
                'AllowKeycloakLogin' => true,
                'AllowOauth2Login' => true,
            ]],
            'facebook error' => [['facebookError' => true]],
            'forgot password prompt' => [['ShowForgotPasswordPrompt' => true]],
            'forgot password prompt with external ForgotPasswordUrlNew attribute' => [[
                'ShowForgotPasswordPrompt' => true,
                'ForgotPasswordUrl' => 'https://external.example.com/reset',
                'ForgotPasswordUrlNew' => "target='_new'",
            ]],
            'single language (change-language hidden)' => [[
                'Languages' => [new AvailableLanguage('en_us', 'en_us.php', 'English US')],
                'SelectedLanguage' => 'en_us',
            ]],
        ];
    }

    /**
     * @param array<string, mixed> $overrides
     */
    #[DataProvider('branchProvider')]
    public function testLoginBranchesMatchSmarty(array $overrides): void
    {
        require_once(__DIR__ . '/../../lang/AvailableLanguage.php');
        $vars = array_merge($this->baseVars(), $overrides);
        $this->renderBoth($vars);
    }

    /**
     * Announcements-present branch: use real Announcement objects whose Text()
     * flows through sanitize_rich_text in both engines. Plain-text bodies keep
     * the output identical across engines.
     */
    public function testAnnouncementsPresentMatchSmarty(): void
    {
        $now = Date::Now();
        $vars = array_merge($this->baseVars(), [
            'Announcements' => [
                new Announcement(1, 'First announcement body', $now, $now, 1, [], [], null),
                new Announcement(2, 'Second announcement body', $now, $now, 1, [], [], null),
            ],
        ]);
        $this->renderBoth($vars);
    }

    /**
     * EnableCaptcha=true invokes CaptchaControl, which is nondeterministic.
     * We cannot assert exact parity, so assert the branch is structurally present:
     * the validation_group wrapper and the captcha container div are emitted, and
     * the hidden captcha input from the else-branch is NOT emitted.
     */
    public function testEnableCaptchaTrueRendersControlBranch(): void
    {
        $vars = array_merge($this->baseVars(), ['EnableCaptcha' => true]);

        $twig = (new TwigRenderer())->render('login.twig', $vars);
        $smarty = (new SmartyRenderer())->render('login.tpl', $vars);

        foreach ([$twig, $smarty] as $html) {
            // The captcha container div (else-branch hidden input suppressed).
            $this->assertStringContainsString('class="text-center mb-2"', $html);
            // The else-branch hidden captcha input must be absent when captcha is on.
            $this->assertStringNotContainsString('type="hidden" name=\'captcha\'', $html);
        }
    }

    /**
     * The `cookies` Twig global exposes $_COOKIE, mirroring {$smarty.cookies.*}.
     * When the language cookie is set, both engines emit the same escaped value
     * into the client-side language-detection script.
     */
    public function testLanguageCookieMatchesSmarty(): void
    {
        $_COOKIE['language'] = 'de_de';
        $vars = $this->baseVars();
        $this->renderBoth($vars);

        // Sanity: the cookie value actually reached the rendered Twig output.
        $twig = (new TwigRenderer())->render('login.twig', $vars);
        $this->assertStringContainsString("var langCode = 'de_de';", $twig);
    }
}
