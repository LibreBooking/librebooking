<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');
require_once(__DIR__ . '/../../Presenters/ProfilePresenter.php');

/**
 * Live Smarty-vs-Twig golden comparison for MyAccount templates:
 *   - tpl/MyAccount/notification-preferences.tpl → tpl/MyAccount/notification-preferences.twig
 *   - tpl/MyAccount/participation.tpl            → tpl/MyAccount/participation.twig
 *   - tpl/MyAccount/password.tpl                 → tpl/MyAccount/password.twig
 *   - tpl/MyAccount/profile.tpl                  → tpl/MyAccount/profile.twig
 *
 * Both engines are rendered in the same process with identical template
 * variables and superglobal state; normalized outputs are asserted byte-identical.
 *
 * CSRF token nondeterminism: password.twig and profile.twig use csrf_token().
 * Both engines call ServiceLocator::GetServer()->GetUserSession()->CSRFToken,
 * so wiring a FakeServer with a stable token produces identical output in both.
 *
 * AttributeControl nondeterminism: profile tests use Attributes=[] to avoid
 * any AttributeControl rendering (which may have random ids).
 */
class MyAccountGoldenTest extends GoldenTemplateTestCase
{
    private ?Resources $savedResources = null;

    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Server $savedServiceLocatorServer = null;

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

        // Wire a FakeServer with a stable CSRF token so csrf_token() produces
        // identical output from both Smarty and Twig.
        $this->savedServiceLocatorServer = ServiceLocator::GetServer();
        $fakeServer = new FakeServer();
        $fakeServer->UserSession->CSRFToken = 'golden-test-csrf-token';
        ServiceLocator::SetServer($fakeServer);
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->savedServer;
        Resources::SetInstance($this->savedResources);
        ServiceLocator::SetServer($this->savedServiceLocatorServer);
        parent::tearDown();
    }

    /**
     * Render both Smarty and Twig with the given vars and assert normalized parity.
     *
     * Variables are assigned to both renderers so that textbox value resolution
     * (which calls getTemplateVars()) works identically in both engines.
     *
     * @param array<string, mixed> $vars
     */
    private function assertParity(string $tplName, string $twigName, array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $expected = $smarty->render($tplName);

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $actual = $twig->render($twigName);

        $this->assertSame(
            HtmlNormalizer::normalize($expected),
            HtmlNormalizer::normalize($actual),
            "Smarty vs Twig mismatch for $twigName"
        );
    }

    // ── notification-preferences ─────────────────────────────────────────────

    /**
     * EmailEnabled=false shows the alert banner, no form.
     */
    public function testNotificationPreferencesEmailDisabledMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/notification-preferences.php';
        $vars['EmailEnabled'] = false;
        $this->assertParity(
            'MyAccount/notification-preferences.tpl',
            'MyAccount/notification-preferences.twig',
            $vars
        );
    }

    /**
     * EmailEnabled=true, all checkboxes checked, PreferencesUpdated=true.
     */
    public function testNotificationPreferencesAllCheckedMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/notification-preferences.php';
        $vars['EmailEnabled'] = true;
        $vars['PreferencesUpdated'] = true;
        $vars['Created'] = true;
        $vars['Updated'] = true;
        $vars['Deleted'] = true;
        $vars['Approved'] = true;
        $vars['ParticipantChanged'] = true;
        $vars['SeriesEnding'] = true;
        $this->assertParity(
            'MyAccount/notification-preferences.tpl',
            'MyAccount/notification-preferences.twig',
            $vars
        );
    }

    /**
     * EmailEnabled=true, no checkboxes checked, PreferencesUpdated=false.
     */
    public function testNotificationPreferencesNoneCheckedMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/notification-preferences.php';
        $this->assertParity(
            'MyAccount/notification-preferences.tpl',
            'MyAccount/notification-preferences.twig',
            $vars
        );
    }

    // ── participation ─────────────────────────────────────────────────────────

    /**
     * Reservations populated: the foreach loop emits rows.
     */
    public function testParticipationWithReservationsMatchesSmarty(): void
    {
        require_once(__DIR__ . '/../../Domain/ReservationItemView.php');

        $now = Date::Now();
        $end = $now->AddDays(1);

        $res = new ReservationItemView(
            'REF-001',
            $now,
            $end,
            'Room A',
            1,
            100,
            1,
            'Team Meeting',
            '',
            1,
            'Alice',
            'Smith',
            42
        );

        $vars = require __DIR__ . '/fixtures/participation.php';
        $vars['Reservations'] = [$res];
        $this->assertParity(
            'MyAccount/participation.tpl',
            'MyAccount/participation.twig',
            $vars
        );
    }

    /**
     * Reservations empty: the foreachelse branch emits "None" row.
     */
    public function testParticipationEmptyMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/participation.php';
        $vars['Reservations'] = [];
        $this->assertParity(
            'MyAccount/participation.tpl',
            'MyAccount/participation.twig',
            $vars
        );
    }

    /**
     * Result set: the result div is shown.
     */
    public function testParticipationWithResultMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/participation.php';
        $vars['result'] = 'Invitation accepted';
        $this->assertParity(
            'MyAccount/participation.tpl',
            'MyAccount/participation.twig',
            $vars
        );
    }

    // ── password ─────────────────────────────────────────────────────────────

    /**
     * AllowPasswordChange=true, ResetPasswordSuccess=false.
     */
    public function testPasswordAllowedMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/password.php';
        $this->assertParity(
            'MyAccount/password.tpl',
            'MyAccount/password.twig',
            $vars
        );
    }

    /**
     * AllowPasswordChange=false: shows the external-control alert, no form.
     */
    public function testPasswordNotAllowedMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/password.php';
        $vars['AllowPasswordChange'] = false;
        $this->assertParity(
            'MyAccount/password.tpl',
            'MyAccount/password.twig',
            $vars
        );
    }

    /**
     * AllowPasswordChange=true, ResetPasswordSuccess=true: shows success alert.
     */
    public function testPasswordSuccessMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/password.php';
        $vars['ResetPasswordSuccess'] = true;
        $this->assertParity(
            'MyAccount/password.tpl',
            'MyAccount/password.twig',
            $vars
        );
    }

    // ── profile ───────────────────────────────────────────────────────────────

    /**
     * All AllowXxxChange=true, Attributes=[].
     */
    public function testProfileAllAllowedMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/profile.php';
        $this->assertParity(
            'MyAccount/profile.tpl',
            'MyAccount/profile.twig',
            $vars
        );
    }

    /**
     * All AllowXxxChange=false: shows static spans and hidden inputs.
     */
    public function testProfileNoneAllowedMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/profile.php';
        $vars['AllowUsernameChange'] = false;
        $vars['AllowEmailAddressChange'] = false;
        $vars['AllowNameChange'] = false;
        $vars['AllowPhoneChange'] = false;
        $vars['AllowOrganizationChange'] = false;
        $vars['AllowPositionChange'] = false;
        $this->assertParity(
            'MyAccount/profile.tpl',
            'MyAccount/profile.twig',
            $vars
        );
    }
}
