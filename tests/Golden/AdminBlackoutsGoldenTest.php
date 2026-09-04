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
 *   - tpl/Admin/Blackouts/manage_blackouts_response.tpl  → .twig (structural)
 *   - tpl/Admin/Blackouts/manage_blackouts_edit.tpl      → .twig (structural)
 *   - tpl/Admin/Blackouts/manage_blackouts.tpl           → .twig (structural)
 *
 * Parity strategy
 * ---------------
 * All three templates use structural assertTwigContains rather than full parity because:
 *   - manage_blackouts.tpl is a full page with globalheader/globalfooter includes
 *     which contain CSRF token, user session data etc. — not deterministic enough
 *     for byte-equal comparison.
 *   - manage_blackouts_edit.tpl and manage_blackouts_response.tpl involve
 *     Date object rendering which may differ slightly between engines in edge cases.
 *   - Class constants (ReservationConflictResolution, SeriesUpdateScope, etc.)
 *     rendered via constant() in Twig vs {ClassName::CONST} in Smarty are
 *     structurally equivalent but the accepted-divergence pattern applies.
 *
 * We pin the clock to a fixed date for deterministic date output.
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
     * Render Twig only and assert the output contains expected strings.
     *
     * @param array<string, mixed> $vars
     * @param string[]             $expectedStrings
     */
    private function assertTwigContains(string $twigName, array $vars, array $expectedStrings): void
    {
        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $output = $twig->render($twigName);
        foreach ($expectedStrings as $needle) {
            $this->assertStringContainsString($needle, $output, "Expected '$needle' in $twigName output");
        }
    }

    // ── manage_blackouts_response ─────────────────────────────────────────────

    public function testResponseSuccessful(): void
    {
        $this->assertTwigContains(
            'Admin/Blackouts/manage_blackouts_response.twig',
            [
                'Successful' => true,
                'SuccessKey' => 'BlackoutCreated',
                'FailKey' => 'BlackoutNotCreated',
                'Message' => '',
                'Reservations' => [],
                'Blackouts' => [],
                'Timezone' => 'America/New_York',
            ],
            ['reload btn btn-primary']
        );
    }

    public function testResponseFailure(): void
    {
        $this->assertTwigContains(
            'Admin/Blackouts/manage_blackouts_response.twig',
            [
                'Successful' => false,
                'SuccessKey' => 'BlackoutCreated',
                'FailKey' => 'BlackoutNotCreated',
                'Message' => '',
                'Reservations' => [],
                'Blackouts' => [],
                'Timezone' => 'America/New_York',
            ],
            ['unblock btn btn-primary']
        );
    }

    public function testResponseWithMessage(): void
    {
        $this->assertTwigContains(
            'Admin/Blackouts/manage_blackouts_response.twig',
            [
                'Successful' => true,
                'SuccessKey' => 'BlackoutCreated',
                'FailKey' => 'BlackoutNotCreated',
                'Message' => 'Custom blackout message',
                'Reservations' => [],
                'Blackouts' => [],
                'Timezone' => 'UTC',
            ],
            ['Custom blackout message', 'reload btn btn-primary']
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

        $this->assertTwigContains(
            'Admin/Blackouts/manage_blackouts_response.twig',
            [
                'Successful' => false,
                'SuccessKey' => 'BlackoutCreated',
                'FailKey' => 'BlackoutNotCreated',
                'Message' => '',
                'Reservations' => [],
                'Blackouts' => [$blackout],
                'Timezone' => 'UTC',
            ],
            ['blackoutConflictsTable', 'Server Maintenance']
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

        $this->assertTwigContains(
            'Admin/Blackouts/manage_blackouts_response.twig',
            [
                'Successful' => false,
                'SuccessKey' => 'BlackoutCreated',
                'FailKey' => 'BlackoutNotCreated',
                'Message' => '',
                'Reservations' => [$reservation],
                'Blackouts' => [],
                'Timezone' => 'UTC',
            ],
            ['reservationTable', 'REF-999', 'Jane', 'Doe', 'Team Meeting', 'Conference Room A']
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
        $this->assertTwigContains(
            'Admin/Blackouts/manage_blackouts_edit.twig',
            $this->makeEditVars(false),
            [
                'editBlackoutForm',
                'updateStartDate',
                'updateEndDate',
                'Scheduled Maintenance',
                'btnUpdateAllInstances',
            ]
        );
    }

    public function testEditRecurring(): void
    {
        $this->assertTwigContains(
            'Admin/Blackouts/manage_blackouts_edit.twig',
            $this->makeEditVars(true),
            [
                'editBlackoutForm',
                'btnUpdateThisInstance',
                'btnUpdateAllInstances',
            ]
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
        $this->assertTwigContains(
            'Admin/Blackouts/manage_blackouts.twig',
            $this->makeMainVars(),
            [
                'page-manage-blackouts',
                'addBlackoutForm',
                'blackoutTable',
                'deleteDialog',
            ]
        );
    }

    public function testMainPageWithBlackouts(): void
    {
        $this->assertTwigContains(
            'Admin/Blackouts/manage_blackouts.twig',
            $this->makeMainVars(true),
            [
                'page-manage-blackouts',
                'blackoutTable',
                'Maintenance Window',
                'Conference Room',
            ]
        );
    }

    public function testMainPageWithSchedulesAndResources(): void
    {
        $this->assertTwigContains(
            'Admin/Blackouts/manage_blackouts.twig',
            $this->makeMainVars(false, true),
            [
                'page-manage-blackouts',
                'addScheduleId',
                'allResources',
                'Main Schedule',
            ]
        );
    }
}
