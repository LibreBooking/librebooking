<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../Pages/Pages.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');
require_once(__DIR__ . '/../../Presenters/Admin/ManageBlackoutsPresenter.php');

/**
 * Live Smarty-vs-Twig golden comparison for Admin/Blackouts templates.
 *
 * Templates covered:
 *   - tpl/Admin/Blackouts/manage_blackouts_response.tpl  → .twig (full parity)
 *   - tpl/Admin/Blackouts/manage_blackouts_edit.tpl      → .twig (full parity)
 *   - tpl/Admin/Blackouts/manage_blackouts.tpl           → .twig (parity after stripping data-default)
 *
 * Parity strategy
 * ---------------
 * manage_blackouts_response and manage_blackouts_edit:
 *   Full parity — no accepted divergences. CSRF token is pinned via FakeServer.
 *   Date objects are pinned to deterministic fixture values.
 *
 * manage_blackouts (main full page):
 *   Has two nondeterministic `data-default` attributes populated by the wall clock
 *   (`now().Format('H:00')` in Twig, `{format_date format='H:00' date=now}` in Smarty).
 *   Neither engine honors Date::_SetNow() for these — both use PHP's time(). The
 *   attribute is stripped from BOTH outputs before comparison so that the rest of the
 *   page (every translated string, form field, table row, JS block, CSRF token,
 *   conditional section) is Smarty-verified. The stripping is documented per method.
 */
class AdminBlackoutsGoldenTest extends GoldenTemplateTestCase
{
    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Resources $savedResources = null;

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/admin/manage_blackouts.php';
        $_SERVER['REQUEST_URI'] = '/web/admin/manage_blackouts.php';
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();
        $this->savedServiceLocatorServer = ServiceLocator::GetServer();
        $fakeServer = new FakeServer();
        $fakeServer->UserSession->CSRFToken = 'golden-test-csrf-token';
        $fakeServer->UserSession->UserId = 42;
        ServiceLocator::SetServer($fakeServer);
        Date::_SetNow(Date::Parse('2025-06-15 10:00:00', 'UTC'));
    }

    protected function tearDown(): void
    {
        $prop = new \ReflectionProperty(Date::class, '_Now');
        $prop->setAccessible(true);
        $prop->setValue(null, null);

        $_SERVER = $this->savedServer;
        Resources::SetInstance($this->savedResources);
        ServiceLocator::SetServer($this->savedServiceLocatorServer);
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

    /**
     * Render both engines for manage_blackouts (main page) and assert parity after
     * stripping nondeterministic `data-default` attributes.
     *
     * The main page has two `<select ... data-default="H:00">` elements whose
     * `data-default` value is the current wall-clock hour. Both Smarty
     * (`{format_date format='H:00' date=now}`) and Twig (`{{ now().Format('H:00') }}`)
     * use the live clock; Date::_SetNow() does not affect them. Stripping
     * `data-default="..."` from BOTH outputs before comparison normalizes the clock
     * dependency while keeping all other markup Smarty-verified.
     *
     * @param array<string, mixed> $vars
     */
    private function assertMainPageParity(array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyHtml = $smarty->render('Admin/Blackouts/manage_blackouts.tpl');

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigHtml = $twig->render('Admin/Blackouts/manage_blackouts.twig');

        // Strip data-default="..." (clock-based H:00 values) from BOTH outputs BEFORE
        // normalization so that surrounding whitespace is correctly collapsed.
        $smartyHtml = preg_replace('/\s+data-default="[^"]*"/', '', $smartyHtml);
        $twigHtml   = preg_replace('/\s+data-default="[^"]*"/', '', $twigHtml);

        $this->assertSame(
            HtmlNormalizer::normalize($smartyHtml),
            HtmlNormalizer::normalize($twigHtml),
            'Smarty vs Twig mismatch for manage_blackouts.twig (after stripping data-default)'
        );
    }

    // ── manage_blackouts_response ─────────────────────────────────────────────

    public function testResponseSuccessful(): void
    {
        $this->assertParity(
            'Admin/Blackouts/manage_blackouts_response.tpl',
            'Admin/Blackouts/manage_blackouts_response.twig',
            [
                'Successful' => true,
                'SuccessKey' => 'BlackoutCreated',
                'FailKey' => 'BlackoutNotCreated',
                'Message' => '',
                'Reservations' => [],
                'Blackouts' => [],
                'Timezone' => 'America/New_York',
            ]
        );
    }

    public function testResponseFailure(): void
    {
        $this->assertParity(
            'Admin/Blackouts/manage_blackouts_response.tpl',
            'Admin/Blackouts/manage_blackouts_response.twig',
            [
                'Successful' => false,
                'SuccessKey' => 'BlackoutCreated',
                'FailKey' => 'BlackoutNotCreated',
                'Message' => '',
                'Reservations' => [],
                'Blackouts' => [],
                'Timezone' => 'America/New_York',
            ]
        );
    }

    public function testResponseWithMessage(): void
    {
        $this->assertParity(
            'Admin/Blackouts/manage_blackouts_response.tpl',
            'Admin/Blackouts/manage_blackouts_response.twig',
            [
                'Successful' => true,
                'SuccessKey' => 'BlackoutCreated',
                'FailKey' => 'BlackoutNotCreated',
                'Message' => 'Custom blackout message',
                'Reservations' => [],
                'Blackouts' => [],
                'Timezone' => 'UTC',
            ]
        );
    }

    public function testResponseWithBlackoutConflicts(): void
    {
        $blackout = new class () {
            public string $Title = 'Server Maintenance';
            public Date $StartDate;
            public Date $EndDate;

            public function __construct()
            {
                $this->StartDate = Date::Parse('2025-06-15 10:00:00', 'UTC');
                $this->EndDate = Date::Parse('2025-06-15 12:00:00', 'UTC');
            }
        };

        $this->assertParity(
            'Admin/Blackouts/manage_blackouts_response.tpl',
            'Admin/Blackouts/manage_blackouts_response.twig',
            [
                'Successful' => false,
                'SuccessKey' => 'BlackoutCreated',
                'FailKey' => 'BlackoutNotCreated',
                'Message' => '',
                'Reservations' => [],
                'Blackouts' => [$blackout],
                'Timezone' => 'UTC',
            ]
        );
    }

    public function testResponseWithReservationConflicts(): void
    {
        $reservation = new class () {
            public string $ReferenceNumber = 'REF-999';
            public string $FirstName = 'Jane';
            public string $LastName = 'Doe';
            public string $Title = 'Team Meeting';
            public string $ResourceName = 'Conference Room A';
            public Date $StartDate;
            public Date $EndDate;

            public function __construct()
            {
                $this->StartDate = Date::Parse('2025-06-15 10:00:00', 'UTC');
                $this->EndDate = Date::Parse('2025-06-15 11:00:00', 'UTC');
            }
        };

        $this->assertParity(
            'Admin/Blackouts/manage_blackouts_response.tpl',
            'Admin/Blackouts/manage_blackouts_response.twig',
            [
                'Successful' => false,
                'SuccessKey' => 'BlackoutCreated',
                'FailKey' => 'BlackoutNotCreated',
                'Message' => '',
                'Reservations' => [$reservation],
                'Blackouts' => [],
                'Timezone' => 'UTC',
            ]
        );
    }

    // ── manage_blackouts_edit ─────────────────────────────────────────────────

    /**
     * @return array<string, mixed>
     */
    private function makeEditVars(bool $isRecurring = false): array
    {
        $resource1 = new class () {
            public function GetId(): int
            {
                return 1;
            }

            public function GetName(): string
            {
                return 'Room A';
            }
        };
        $resource2 = new class () {
            public function GetId(): int
            {
                return 2;
            }

            public function GetName(): string
            {
                return 'Room B';
            }
        };

        return [
            'TimeFormat' => 'g:i A',
            'BlackoutStartDate' => Date::Parse('2025-06-15 10:00:00', 'UTC'),
            'BlackoutEndDate' => Date::Parse('2025-06-15 12:00:00', 'UTC'),
            'Resources' => [$resource1, $resource2],
            'BlackoutResourceIds' => [1],
            'BlackoutTitle' => 'Scheduled Maintenance',
            'BlackoutId' => 42,
            'RepeatTerminationDate' => Date::Parse('2025-12-31 00:00:00', 'UTC'),
            'IsRecurring' => $isRecurring,
            'RepeatType' => $isRecurring ? 'daily' : '',
            'RepeatInterval' => $isRecurring ? '1' : '',
            'RepeatMonthlyType' => '',
            'RepeatWeekdays' => [],
            'CustomRepeatDates' => [],
            'Timezone' => 'UTC',
        ];
    }

    public function testEditBasicNonRecurring(): void
    {
        $this->assertParity(
            'Admin/Blackouts/manage_blackouts_edit.tpl',
            'Admin/Blackouts/manage_blackouts_edit.twig',
            $this->makeEditVars(false)
        );
    }

    public function testEditRecurring(): void
    {
        $this->assertParity(
            'Admin/Blackouts/manage_blackouts_edit.tpl',
            'Admin/Blackouts/manage_blackouts_edit.twig',
            $this->makeEditVars(true)
        );
    }

    // ── manage_blackouts (main page) ──────────────────────────────────────────

    /**
     * @return array<string, mixed>
     */
    private function makeMainVars(bool $withBlackouts = false, bool $withSchedules = false): array
    {
        $resource = new class () {
            public function GetId(): int
            {
                return 1;
            }

            public function GetName(): string
            {
                return 'Conference Room';
            }
        };

        $schedules = [];
        if ($withSchedules) {
            $schedule = new class () {
                public function GetId(): int
                {
                    return 10;
                }

                public function GetName(): string
                {
                    return 'Main Schedule';
                }
            };
            $schedules = [$schedule];
        }

        $blackouts = [];
        if ($withBlackouts) {
            $blackout = new BlackoutItemView(
                1,
                Date::Parse('2025-06-15 09:00:00', 'UTC'),
                Date::Parse('2025-06-15 17:00:00', 'UTC'),
                1,
                42,
                10,
                'Maintenance Window',
                '',
                'Admin',
                'User',
                'Conference Room',
                1,
                '',
                ''
            );
            $blackouts = [$blackout];
        }

        return [
            'TimeFormat' => 'g:i A',
            'Resources' => [$resource],
            'ResourceId' => null,
            'Schedules' => $schedules,
            'ScheduleId' => null,
            'blackouts' => $blackouts,
            'Timezone' => 'UTC',
            'StartDate' => Date::Parse('2025-06-01 00:00:00', 'UTC'),
            'EndDate' => Date::Parse('2025-06-30 00:00:00', 'UTC'),
            'AddStartDate' => Date::Parse('2025-06-15 09:00:00', 'UTC'),
            'AddEndDate' => Date::Parse('2025-06-15 10:00:00', 'UTC'),
            'RepeatType' => '',
            'RepeatInterval' => '',
            'RepeatMonthlyType' => '',
            'RepeatWeekdays' => [],
            'Path' => '/web/',
        ];
    }

    public function testMainPageEmpty(): void
    {
        $this->assertMainPageParity($this->makeMainVars());
    }

    public function testMainPageWithBlackouts(): void
    {
        $this->assertMainPageParity($this->makeMainVars(true));
    }

    public function testMainPageWithSchedulesAndResources(): void
    {
        $this->assertMainPageParity($this->makeMainVars(false, true));
    }
}
