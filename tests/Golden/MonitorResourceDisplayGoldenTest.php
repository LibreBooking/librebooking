<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for MonitorDisplay and ResourceDisplay templates:
 *   - tpl/MonitorDisplay/monitor-display.tpl             → tpl/MonitorDisplay/monitor-display.twig
 *   - tpl/MonitorDisplay/monitor-display-schedule.tpl    → tpl/MonitorDisplay/monitor-display-schedule.twig
 *   - tpl/ResourceDisplay/resource-display-instructions.tpl → tpl/ResourceDisplay/resource-display-instructions.twig
 *   - tpl/ResourceDisplay/resource-display-login.tpl     → tpl/ResourceDisplay/resource-display-login.twig
 *   - tpl/ResourceDisplay/resource-display-not-enabled.tpl → tpl/ResourceDisplay/resource-display-not-enabled.twig
 *   - tpl/ResourceDisplay/resource-display-resource.tpl  → tpl/ResourceDisplay/resource-display-resource.twig
 *   - tpl/ResourceDisplay/resource-display-shell.tpl     → tpl/ResourceDisplay/resource-display-shell.twig
 *
 * Both engines are rendered in the same process with identical template variables
 * and superglobal state; normalized outputs are asserted byte-identical.
 *
 * Notes:
 * - Clock is pinned to 2025-06-15 10:00:00 UTC to make Date::Now() deterministic.
 * - monitor-display-schedule Format=1 is tested via render_partial (see below).
 *   As of Task 2.10 the static grid (schedule-reservations-grid-static) is a
 *   .twig that imports the shared monitor slot macros, so render_partial fires the
 *   Twig branch and full slot rendering works.  The FULL-slot Format=1 parity case
 *   (every StaticDisplaySlotFactory dispatch branch) lives in ScheduleGoldenTest
 *   (testMonitorScheduleFormat1FullSlotsMatchesSmarty).  The empty / no-period
 *   Format=1 cases below remain here as lightweight routing regressions.
 */
class MonitorResourceDisplayGoldenTest extends GoldenTemplateTestCase
{
    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Resources $savedResources = null;

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/index.php';
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();
        $this->savedServiceLocatorServer = ServiceLocator::GetServer();
        $fakeServer = new FakeServer();
        $fakeServer->UserSession->CSRFToken = 'golden-test-csrf-token';
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

    // ── monitor-display ────────────────────────────────────────────────────────

    /**
     * Monitor display disabled: alert shown, no config panel.
     */
    public function testMonitorDisplayDisabledMatchesSmarty(): void
    {
        $vars = [
            'Enabled' => false,
            'Schedules' => [],
            'Resources' => [],
        ];
        $this->assertParity(
            'MonitorDisplay/monitor-display.tpl',
            'MonitorDisplay/monitor-display.twig',
            $vars
        );
    }

    /**
     * Monitor display enabled with one schedule and one resource.
     */
    public function testMonitorDisplayEnabledMatchesSmarty(): void
    {
        $schedule = new class () {
            public function GetId(): int
            {
                return 1;
            }
            public function GetName(): string
            {
                return 'Main Schedule';
            }
            public function GetIsDefault(): bool
            {
                return true;
            }
        };

        $resource = new class () {
            public function GetId(): int
            {
                return 10;
            }
            public function GetName(): string
            {
                return 'Conference Room A';
            }
        };

        $vars = [
            'Enabled' => true,
            'Schedules' => [$schedule],
            'Resources' => [$resource],
        ];
        $this->assertParity(
            'MonitorDisplay/monitor-display.tpl',
            'MonitorDisplay/monitor-display.twig',
            $vars
        );
    }

    // ── monitor-display-schedule ───────────────────────────────────────────────

    /**
     * Monitor schedule Format=2 empty (no dates, no resources).
     * Note: Format=1 is NOT tested — Schedule/schedule-reservations-grid-static.twig not migrated yet.
     */
    public function testMonitorDisplayScheduleEmptyMatchesSmarty(): void
    {
        $dailyLayout = new class () {
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return [];
            }
        };

        $vars = [
            'Format' => 2,
            'BoundDates' => [],
            'Resources' => [],
            'DailyLayout' => $dailyLayout,
            'SlotLabelFactory' => null,
        ];
        $this->assertParity(
            'MonitorDisplay/monitor-display-schedule.tpl',
            'MonitorDisplay/monitor-display-schedule.twig',
            $vars
        );
    }

    /**
     * Monitor schedule Format=2 with one date, one resource, and several slots.
     * Covers: IsReserved=true, HasColor=true, multi-day slot (BeginDate != EndDate).
     */
    public function testMonitorDisplayScheduleWithSlotsMatchesSmarty(): void
    {
        $date = Date::Parse('2025-06-15', 'UTC');

        $resource = new class () {
            public string $Name = 'Lab Room';
            public int $Id = 42;

            public function HasColor(): bool
            {
                return true;
            }
            public function GetTextColor(): string
            {
                return '#ffffff';
            }
            public function GetColor(): string
            {
                return '#336699';
            }
        };

        $slotReserved = new class () {
            public function IsReserved(): bool
            {
                return true;
            }
            public function IsReservable(): bool
            {
                return false;
            }
            public function HasCustomColor(): bool
            {
                return false;
            }
            public function BeginDate(): Date
            {
                return Date::Parse('2025-06-15 09:00:00', 'UTC');
            }
            public function EndDate(): Date
            {
                return Date::Parse('2025-06-15 10:00:00', 'UTC');
            }
            public function Label(mixed $factory): string
            {
                return 'Morning Meeting';
            }
            public function PeriodSpan(): int
            {
                return 2;
            }
            public function Id(): int
            {
                return 101;
            }
            public function Date(): Date
            {
                return Date::Parse('2025-06-15', 'UTC');
            }
            public function Color(): string
            {
                return '#ff0000';
            }
            public function TextColor(): string
            {
                return '#ffffff';
            }
            public function IsPending(): bool
            {
                return false;
            }
        };

        $slotMultiDay = new class () {
            public function IsReserved(): bool
            {
                return true;
            }
            public function IsReservable(): bool
            {
                return false;
            }
            public function HasCustomColor(): bool
            {
                return false;
            }
            public function BeginDate(): Date
            {
                return Date::Parse('2025-06-15 22:00:00', 'UTC');
            }
            public function EndDate(): Date
            {
                return Date::Parse('2025-06-16 06:00:00', 'UTC');
            }
            public function Label(mixed $factory): string
            {
                return 'Overnight Booking';
            }
            public function PeriodSpan(): int
            {
                return 1;
            }
            public function Id(): int
            {
                return 102;
            }
            public function Date(): Date
            {
                return Date::Parse('2025-06-15', 'UTC');
            }
            public function Color(): string
            {
                return '#0000ff';
            }
            public function TextColor(): string
            {
                return '#ffffff';
            }
            public function IsPending(): bool
            {
                return false;
            }
        };

        $slotFree = new class () {
            public function IsReserved(): bool
            {
                return false;
            }
            public function IsReservable(): bool
            {
                return true;
            }
            public function HasCustomColor(): bool
            {
                return false;
            }
            public function BeginDate(): Date
            {
                return Date::Parse('2025-06-15 11:00:00', 'UTC');
            }
            public function EndDate(): Date
            {
                return Date::Parse('2025-06-15 12:00:00', 'UTC');
            }
            public function Label(mixed $factory): string
            {
                return '';
            }
            public function PeriodSpan(): int
            {
                return 2;
            }
            public function Id(): int
            {
                return 103;
            }
            public function Date(): Date
            {
                return Date::Parse('2025-06-15', 'UTC');
            }
            public function Color(): string
            {
                return '';
            }
            public function TextColor(): string
            {
                return '';
            }
            public function IsPending(): bool
            {
                return false;
            }
        };

        $dailyLayout = new class ($slotReserved, $slotMultiDay, $slotFree) {
            private object $slotReserved;
            private object $slotMultiDay;
            private object $slotFree;

            public function __construct(object $r, object $m, object $f)
            {
                $this->slotReserved = $r;
                $this->slotMultiDay = $m;
                $this->slotFree = $f;
            }

            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return [$this->slotReserved, $this->slotMultiDay, $this->slotFree];
            }
        };

        $vars = [
            'Format' => 2,
            'BoundDates' => [$date],
            'Resources' => [$resource],
            'DailyLayout' => $dailyLayout,
            'SlotLabelFactory' => null,
        ];
        $this->assertParity(
            'MonitorDisplay/monitor-display-schedule.tpl',
            'MonitorDisplay/monitor-display-schedule.twig',
            $vars
        );
    }

    // ── monitor-display-schedule Format=1 (render_partial) ────────────────────

    /**
     * Format=1 with no dates: the grid sub-template receives an empty BoundDates
     * array, iterates nothing, and produces no table output.  Both Smarty (via
     * {include}) and Twig (via render_partial → Smarty fallback) must yield the
     * same result (just the <h1> header element).
     *
     * This validates the render_partial routing path without reaching the
     * {call} sites inside the grid that need parent-scope {function} definitions.
     */
    public function testMonitorDisplayScheduleFormat1EmptyMatchesSmarty(): void
    {
        $dailyLayout = new class () {
            /** @return object[] */
            public function GetPeriods(mixed $date, bool $flag): array
            {
                return [];
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return [];
            }
            public function IsDateReservable(mixed $date): bool
            {
                return true;
            }
        };

        $displaySlotFactory = new class () {
            public function GetFunction(mixed $slot, mixed $accessAllowed): string
            {
                return 'displayReservable';
            }
        };

        $vars = [
            'Format' => 1,
            'BoundDates' => [],
            'Resources' => [],
            'DailyLayout' => $dailyLayout,
            'SlotLabelFactory' => null,
            'DisplaySlotFactory' => $displaySlotFactory,
            'ScheduleId' => 1,
            'CreateReservationPage' => 'Web/reservation.php',
        ];
        $this->assertParity(
            'MonitorDisplay/monitor-display-schedule.tpl',
            'MonitorDisplay/monitor-display-schedule.twig',
            $vars
        );
    }

    /**
     * Format=1 with one date but no periods (GetPeriods returns []).
     * The grid template skips dates with zero periods via {continue}, so the
     * slot-display {call} sites are never reached.  Exercises the render_partial
     * Smarty-fallback path with a non-empty BoundDates array.
     */
    public function testMonitorDisplayScheduleFormat1WithDateNoPeriodMatchesSmarty(): void
    {
        $date = Date::Parse('2025-06-15', 'UTC');

        $resource = new class () {
            public string $Name = 'Conference Room';
            public int $Id = 10;

            public function HasColor(): bool
            {
                return false;
            }
            public function GetTextColor(): string
            {
                return '';
            }
            public function GetColor(): string
            {
                return '';
            }
            public function CanAccess(): bool
            {
                return true;
            }
        };

        $dailyLayout = new class () {
            /** @return object[] */
            public function GetPeriods(mixed $date, bool $flag): array
            {
                // Returning [] causes the grid template to skip this date entirely
                // (the {if count == 0}{continue} guard fires before any {call} site).
                return [];
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return [];
            }
            public function IsDateReservable(mixed $date): bool
            {
                return true;
            }
        };

        $displaySlotFactory = new class () {
            public function GetFunction(mixed $slot, mixed $accessAllowed): string
            {
                return 'displayReservable';
            }
        };

        $vars = [
            'Format' => 1,
            'BoundDates' => [$date],
            'Resources' => [$resource],
            'DailyLayout' => $dailyLayout,
            'SlotLabelFactory' => null,
            'DisplaySlotFactory' => $displaySlotFactory,
            'ScheduleId' => 1,
            'CreateReservationPage' => 'Web/reservation.php',
        ];
        $this->assertParity(
            'MonitorDisplay/monitor-display-schedule.tpl',
            'MonitorDisplay/monitor-display-schedule.twig',
            $vars
        );
    }

    // ── resource-display-instructions ─────────────────────────────────────────

    /**
     * Instructions page: no vars needed, just static alert with link.
     */
    public function testResourceDisplayInstructionsMatchesSmarty(): void
    {
        $this->assertParity(
            'ResourceDisplay/resource-display-instructions.tpl',
            'ResourceDisplay/resource-display-instructions.twig',
            []
        );
    }

    // ── resource-display-login ────────────────────────────────────────────────

    /**
     * Login page: uses SCRIPT_NAME from $_SERVER (set in setUp).
     */
    public function testResourceDisplayLoginMatchesSmarty(): void
    {
        $this->assertParity(
            'ResourceDisplay/resource-display-login.tpl',
            'ResourceDisplay/resource-display-login.twig',
            []
        );
    }

    // ── resource-display-not-enabled ──────────────────────────────────────────

    /**
     * Not-enabled page: purely static HTML, no vars needed.
     */
    public function testResourceDisplayNotEnabledMatchesSmarty(): void
    {
        $this->assertParity(
            'ResourceDisplay/resource-display-not-enabled.tpl',
            'ResourceDisplay/resource-display-not-enabled.twig',
            []
        );
    }

    // ── resource-display-resource ─────────────────────────────────────────────

    /**
     * Resource display: no current/next reservation, no extras.
     */
    public function testResourceDisplayResourceAllNullMatchesSmarty(): void
    {
        $dailyLayout = new class () {
            /** @return object[] */
            public function GetPeriods(mixed $date, bool $flag): array
            {
                return [];
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return [];
            }
        };

        $vars = [
            'AvailableNow' => true,
            'ResourceName' => 'Lab A',
            'ReservationDate' => Date::Parse('2025-06-15', 'UTC'),
            'Timezone' => 'UTC',
            'CurrentReservation' => null,
            'NextReservation' => null,
            'UpcomingReservations' => [],
            'RequiresCheckin' => false,
            'AllowReservations' => false,
            'NoTitle' => '(No Title)',
            'DailyLayout' => $dailyLayout,
            'ResourceId' => 5,
            'ScheduleId' => 1,
            'SlotLabelFactory' => null,
            'TimeFormat' => 'H:i',
            'CheckinReferenceNumber' => '',
        ];
        $this->assertParity(
            'ResourceDisplay/resource-display-resource.tpl',
            'ResourceDisplay/resource-display-resource.twig',
            $vars
        );
    }

    /**
     * Resource display: with current, next reservation, checkin button, upcoming list.
     */
    public function testResourceDisplayResourceWithReservationsMatchesSmarty(): void
    {
        $startDate = Date::Parse('2025-06-15 09:00:00', 'UTC');
        $endDate = Date::Parse('2025-06-15 10:00:00', 'UTC');

        $makeReservation = static function (string $title, string $user) use ($startDate, $endDate): object {
            return new class ($title, $user, $startDate, $endDate) {
                private string $title;
                private string $user;
                private Date $start;
                private Date $end;

                public function __construct(string $t, string $u, Date $s, Date $e)
                {
                    $this->title = $t;
                    $this->user = $u;
                    $this->start = $s;
                    $this->end = $e;
                }
                public function StartDate(): Date
                {
                    return $this->start;
                }
                public function EndDate(): Date
                {
                    return $this->end;
                }
                public function GetUserName(): string
                {
                    return $this->user;
                }
                public function GetTitle(): string
                {
                    return $this->title;
                }
            };
        };

        $current = $makeReservation('Team Standup', 'alice');
        $next = $makeReservation('Design Review', 'bob');
        $upcoming1 = $makeReservation('Sprint Planning', 'carol');
        $upcoming2 = $makeReservation('Retrospective', 'dave');

        $dailyLayout = new class () {
            /** @return object[] */
            public function GetPeriods(mixed $date, bool $flag): array
            {
                return [];
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return [];
            }
        };

        $vars = [
            'AvailableNow' => false,
            'ResourceName' => 'Board Room',
            'ReservationDate' => Date::Parse('2025-06-15', 'UTC'),
            'Timezone' => 'UTC',
            'CurrentReservation' => $current,
            'NextReservation' => $next,
            'UpcomingReservations' => [$upcoming1, $upcoming2],
            'RequiresCheckin' => true,
            'CheckinReferenceNumber' => 'REF-001',
            'AllowReservations' => true,
            'NoTitle' => '(No Title)',
            'DailyLayout' => $dailyLayout,
            'ResourceId' => 7,
            'ScheduleId' => 2,
            'SlotLabelFactory' => null,
            'TimeFormat' => 'H:i',
        ];
        $this->assertParity(
            'ResourceDisplay/resource-display-resource.tpl',
            'ResourceDisplay/resource-display-resource.twig',
            $vars
        );
    }

    /**
     * Resource display: with Terms of Service and custom attributes.
     */
    public function testResourceDisplayResourceWithTermsAndAttributesMatchesSmarty(): void
    {
        $terms = new class () {
            public function DisplayUrl(): string
            {
                return 'https://example.com/terms';
            }
        };

        // AttributeControl calls Id() and Type() on the attribute, then renders the sub-template.
        // Type must be a valid CustomAttributeTypes constant (1 = SINGLE_LINE_TEXTBOX).
        $attribute = new class () {
            public function Id(): int
            {
                return 99;
            }
            public function Type(): int
            {
                return 1;
            }
            public function Label(): string
            {
                return 'Notes';
            }
            public function Required(): bool
            {
                return false;
            }
            public function Value(): string
            {
                return '';
            }
        };

        $dailyLayout = new class () {
            /** @return object[] */
            public function GetPeriods(mixed $date, bool $flag): array
            {
                return [];
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return [];
            }
        };

        $vars = [
            'AvailableNow' => true,
            'ResourceName' => 'Quiet Room',
            'ReservationDate' => Date::Parse('2025-06-15', 'UTC'),
            'Timezone' => 'UTC',
            'CurrentReservation' => null,
            'NextReservation' => null,
            'UpcomingReservations' => [],
            'RequiresCheckin' => false,
            'AllowReservations' => true,
            'NoTitle' => '(No Title)',
            'Terms' => $terms,
            'TermsAccepted' => true,
            'Attributes' => [$attribute],
            'DailyLayout' => $dailyLayout,
            'ResourceId' => 9,
            'ScheduleId' => 3,
            'SlotLabelFactory' => null,
            'TimeFormat' => 'H:i',
            'CheckinReferenceNumber' => '',
        ];
        $this->assertParity(
            'ResourceDisplay/resource-display-resource.tpl',
            'ResourceDisplay/resource-display-resource.twig',
            $vars
        );
    }

    // ── resource-display-shell ────────────────────────────────────────────────

    /**
     * Shell page with autocomplete disabled.
     */
    public function testResourceDisplayShellNoAutocompleteMatchesSmarty(): void
    {
        $minDate = Date::Parse('2025-06-15', 'UTC');
        $maxDate = Date::Parse('2025-12-31', 'UTC');

        $vars = [
            'AllowAutocomplete' => false,
            'PublicResourceId' => 'test-uuid-123',
            'MinDate' => $minDate,
            'MaxFutureDate' => $maxDate,
        ];
        $this->assertParity(
            'ResourceDisplay/resource-display-shell.tpl',
            'ResourceDisplay/resource-display-shell.twig',
            $vars
        );
    }

    /**
     * Shell page with autocomplete enabled.
     */
    public function testResourceDisplayShellWithAutocompleteMatchesSmarty(): void
    {
        $minDate = Date::Parse('2025-06-01', 'UTC');
        $maxDate = Date::Parse('2026-06-01', 'UTC');

        $vars = [
            'AllowAutocomplete' => true,
            'PublicResourceId' => 'test-uuid-456',
            'MinDate' => $minDate,
            'MaxFutureDate' => $maxDate,
        ];
        $this->assertParity(
            'ResourceDisplay/resource-display-shell.tpl',
            'ResourceDisplay/resource-display-shell.twig',
            $vars
        );
    }
}
