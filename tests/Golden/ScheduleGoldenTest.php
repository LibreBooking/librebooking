<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../Pages/Pages.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for the Schedule core templates:
 *   - tpl/Schedule/schedule-reservations-grid-static.tpl → .twig (via monitor host, Format=1)
 *   - tpl/Schedule/schedule.tpl                          → tpl/Schedule/schedule.twig (full page)
 *
 * Both engines are rendered in the same process with identical template variables
 * and superglobal state; normalized outputs are asserted byte-identical.
 *
 * Dispatch coverage strategy
 * --------------------------
 * The grids perform dynamic dispatch: a factory (DisplaySlotFactory /
 * StaticDisplaySlotFactory) returns a macro name at runtime and the grid routes
 * to it.  Both engines call the *same* PHP factory object passed in the vars, so
 * given the same returned name the only thing under test is that each engine's
 * dispatch routes that name to a byte-identical slot rendering.  The fixtures use
 * fake factories that return a specific name per slot so that EVERY dispatch
 * branch is exercised:
 *   - interactive grid (schedule.twig): displayReservable, displayRestricted,
 *     displayPastTime, displayUnreservable
 *   - static grid (monitor Format=1): the four non-reserved names above PLUS the
 *     four reserved-state names displayMyReserved, displayMyParticipating,
 *     displayAdminReserved, displayReserved.
 *
 * Notes:
 * - Clock pinned to 2025-06-15 10:00:00 UTC to make Date::Now() deterministic.
 * - CSRF token pinned via FakeServer for stable csrf_token() output.
 */
class ScheduleGoldenTest extends GoldenTemplateTestCase
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

    // ── fixture builders ───────────────────────────────────────────────────────

    /**
     * A period object for the grid header (colspan + label).
     */
    private function makePeriod(int $span, string $label): object
    {
        return new class ($span, $label) {
            public function __construct(private int $span, private string $label)
            {
            }
            public function Span(): int
            {
                return $this->span;
            }
            public function Label(mixed $date = null): string
            {
                return $this->label;
            }
        };
    }

    /**
     * A slot double covering every method the base + monitor macros touch.
     */
    private function makeSlot(
        int $id,
        string $begin,
        string $end,
        string $label,
        int $periodSpan = 1,
        bool $isReserved = false,
        bool $isPending = false,
        bool $hasColor = false,
        string $color = '',
        string $textColor = ''
    ): object {
        return new class ($id, $begin, $end, $label, $periodSpan, $isReserved, $isPending, $hasColor, $color, $textColor) {
            public function __construct(
                private int $id,
                private string $begin,
                private string $end,
                private string $label,
                private int $periodSpan,
                private bool $isReserved,
                private bool $isPending,
                private bool $hasColor,
                private string $color,
                private string $textColor
            ) {
            }
            public function BeginDate(): Date
            {
                return Date::Parse($this->begin, 'UTC');
            }
            public function EndDate(): Date
            {
                return Date::Parse($this->end, 'UTC');
            }
            public function Date(): Date
            {
                return Date::Parse(explode(' ', $this->begin)[0], 'UTC');
            }
            public function Label(mixed $factory = null): string
            {
                return $this->label;
            }
            public function PeriodSpan(): int
            {
                return $this->periodSpan;
            }
            public function Id(): int
            {
                return $this->id;
            }
            public function IsReserved(): bool
            {
                return $this->isReserved;
            }
            public function IsPending(): bool
            {
                return $this->isPending;
            }
            public function HasCustomColor(): bool
            {
                return $this->hasColor;
            }
            public function Color(): string
            {
                return $this->color;
            }
            public function TextColor(): string
            {
                return $this->textColor;
            }
        };
    }

    private function makeResource(int $id, string $name, bool $canBook = true, bool $canAccess = true, bool $hasColor = false): object
    {
        return new class ($id, $name, $canBook, $canAccess, $hasColor) {
            public int $Id;
            public string $Name;
            public bool $CanBook;
            public bool $CanAccess;
            public int $MaxConcurrentReservations = 0;

            public function __construct(int $id, string $name, bool $canBook, bool $canAccess, private bool $hasColor)
            {
                $this->Id = $id;
                $this->Name = $name;
                $this->CanBook = $canBook;
                $this->CanAccess = $canAccess;
            }
            public function GetId(): int
            {
                return $this->Id;
            }
            public function HasColor(): bool
            {
                return $this->hasColor;
            }
            public function GetColor(): string
            {
                return '#336699';
            }
            public function GetTextColor(): string
            {
                return '#ffffff';
            }
        };
    }

    // ── static grid via monitor Format=1 (all 8 dispatch branches) ─────────────

    /**
     * RESTORED (Task 2.10): monitor-display-schedule Format=1 with a FULL slot
     * render.  Task 2.8 could only cover empty-period fixtures because the grid
     * was still Smarty and relied on parent-scope {function} definitions that a
     * standalone render could not see.  Now the static grid is a .twig that
     * imports the monitor macro file, so render_partial fires the Twig branch and
     * the full grid renders in both engines.
     *
     * The fake StaticDisplaySlotFactory returns a distinct macro name per slot so
     * every one of the eight dispatch branches is exercised and proven equivalent.
     */
    public function testMonitorScheduleFormat1FullSlotsMatchesSmarty(): void
    {
        $date = Date::Parse('2025-06-15', 'UTC');

        $periods = [$this->makePeriod(1, 'Morning'), $this->makePeriod(1, 'Afternoon')];

        // One slot per dispatch branch.  The factory keys off the slot Id().
        $slotReservable   = $this->makeSlot(1, '2025-06-15 08:00:00', '2025-06-15 09:00:00', '');
        $slotRestricted   = $this->makeSlot(2, '2025-06-15 09:00:00', '2025-06-15 10:00:00', '');
        $slotPast         = $this->makeSlot(3, '2025-06-14 08:00:00', '2025-06-14 09:00:00', '');
        $slotUnreservable = $this->makeSlot(4, '2025-06-15 11:00:00', '2025-06-15 12:00:00', 'Closed');
        $slotMine         = $this->makeSlot(5, '2025-06-15 12:00:00', '2025-06-15 13:00:00', 'My Booking', 2, true);
        $slotParticipating = $this->makeSlot(6, '2025-06-15 13:00:00', '2025-06-15 14:00:00', 'Team Sync', 1, true, true);
        $slotAdmin        = $this->makeSlot(7, '2025-06-15 14:00:00', '2025-06-15 15:00:00', 'Admin Res', 1, true, false, true, '#ff0000', '#ffffff');
        $slotReserved     = $this->makeSlot(8, '2025-06-15 15:00:00', '2025-06-15 16:00:00', 'Other', 1, true);

        $allSlots = [
            $slotReservable, $slotRestricted, $slotPast, $slotUnreservable,
            $slotMine, $slotParticipating, $slotAdmin, $slotReserved,
        ];

        $dailyLayout = new class ($periods, $allSlots) {
            /** @param object[] $periods @param object[] $slots */
            public function __construct(private array $periods, private array $slots)
            {
            }
            /** @return object[] */
            public function GetPeriods(mixed $date, bool $flag): array
            {
                return $this->periods;
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return $this->slots;
            }
            public function IsDateReservable(mixed $date): bool
            {
                return true;
            }
        };

        // Maps slot Id() -> the dispatch name to force every branch.
        $factory = new class () {
            /** @var array<int,string> */
            public array $map = [
                1 => 'displayReservable',
                2 => 'displayRestricted',
                3 => 'displayPastTime',
                4 => 'displayUnreservable',
                5 => 'displayMyReserved',
                6 => 'displayMyParticipating',
                7 => 'displayAdminReserved',
                8 => 'displayReserved',
            ];
            public function GetFunction(mixed $slot, mixed $accessAllowed = false): string
            {
                return $this->map[$slot->Id()];
            }
        };

        $vars = [
            'Format' => 1,
            'BoundDates' => [$date],
            'Resources' => [$this->makeResource(42, 'Lab Room')],
            'DailyLayout' => $dailyLayout,
            'SlotLabelFactory' => null,
            'DisplaySlotFactory' => $factory,
            'ScheduleId' => 1,
            'CreateReservationPage' => 'reservation.php',
            'CanViewAdmin' => true,
        ];
        $this->assertParity(
            'MonitorDisplay/monitor-display-schedule.tpl',
            'MonitorDisplay/monitor-display-schedule.twig',
            $vars
        );
    }

    /**
     * Static grid via monitor Format=1 with a non-reservable / no-color resource
     * and a today-row (date == pinned now) to cover the today-highlight branch and
     * the else (span) resource-name branch.
     */
    public function testMonitorScheduleFormat1RestrictedResourceMatchesSmarty(): void
    {
        $date = Date::Parse('2025-06-15', 'UTC');
        $periods = [$this->makePeriod(2, 'All Day')];
        $slot = $this->makeSlot(2, '2025-06-15 09:00:00', '2025-06-15 10:00:00', '');

        $dailyLayout = new class ($periods, [$slot]) {
            /** @param object[] $periods @param object[] $slots */
            public function __construct(private array $periods, private array $slots)
            {
            }
            /** @return object[] */
            public function GetPeriods(mixed $date, bool $flag): array
            {
                return $this->periods;
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return $this->slots;
            }
            public function IsDateReservable(mixed $date): bool
            {
                return false;
            }
        };

        $factory = new class () {
            public function GetFunction(mixed $slot, mixed $accessAllowed = false): string
            {
                return 'displayRestricted';
            }
        };

        $vars = [
            'Format' => 1,
            'BoundDates' => [$date],
            'Resources' => [$this->makeResource(7, 'No Access Room', false, false)],
            'DailyLayout' => $dailyLayout,
            'SlotLabelFactory' => null,
            'DisplaySlotFactory' => $factory,
            'ScheduleId' => 3,
            'CreateReservationPage' => 'reservation.php',
            'CanViewAdmin' => false,
        ];
        $this->assertParity(
            'MonitorDisplay/monitor-display-schedule.tpl',
            'MonitorDisplay/monitor-display-schedule.twig',
            $vars
        );
    }

    // ── interactive grid via full schedule page (4 dispatch branches) ──────────

    /**
     * @param array<string,string> $factoryMap slot-Id -> dispatch name
     * @return array<string,mixed>
     */
    private function makeSchedulePageVars(array $factoryMap, object $dailyLayout, object $resource): array
    {
        $factory = new class ($factoryMap) {
            /** @param array<int,string> $map */
            public function __construct(private array $map)
            {
            }
            public function GetFunction(mixed $slot, mixed $accessAllowed = false): string
            {
                return $this->map[$slot->Id()];
            }
        };

        $displayDates = new class () {
            public function GetBegin(): Date
            {
                return Date::Parse('2025-06-15', 'UTC');
            }
            public function GetEnd(): Date
            {
                return Date::Parse('2025-06-22', 'UTC');
            }
        };

        return [
            'ShowResourceWarning' => false,
            'CanViewAdmin' => false,
            'IsAccessible' => true,
            'HideSchedule' => false,
            'LoggedIn' => true,
            'IsMobile' => false,
            'IsTablet' => false,
            'ScheduleStyle' => ScheduleStyle::Standard->value,
            'ShowSubscription' => false,
            'SubscriptionUrl' => null,
            'Schedules' => [],
            'ScheduleId' => 1,
            'DisplayDates' => $displayDates,
            'PreviousDate' => Date::Parse('2025-06-08', 'UTC'),
            'NextDate' => Date::Parse('2025-06-22', 'UTC'),
            'ShowWeekNumbers' => false,
            'ShowFullWeekLink' => false,
            'CanViewUsers' => false,
            'AllowParticipation' => false,
            'ResourceAttributes' => [],
            'ResourceTypeAttributes' => [],
            'ResourceTypes' => [],
            'ResourceTypeIdFilter' => '',
            'MaxParticipantsFilter' => '',
            'MinCapacityFilter' => '',
            'SpecificDates' => [],
            'ResourceIds' => [],
            'UserIdFilter' => '',
            'ParticipantIdFilter' => '',
            'OwnerId' => '',
            'ParticipantId' => '',
            'BoundDates' => [Date::Parse('2025-06-15', 'UTC')],
            'Resources' => [$resource],
            'DailyLayout' => $dailyLayout,
            'DisplaySlotFactory' => $factory,
            'CreateReservationPage' => 'reservation.php',
            'LoadViewOnly' => false,
            'AllowGuestBooking' => false,
            'Path' => '/',
            'ScriptUrl' => 'http://localhost',
            'CookieName' => 'schedule-cookie',
            'FastReservationLoad' => false,
            'AutoScrollToday' => true,
            'ResourceGroupsAsJson' => '[]',
            'PopupMonths' => 1,
            'FirstWeekday' => 0,
            'timezone' => 'UTC',
        ];
    }

    /**
     * Full schedule page: the interactive grid renders a row of slots, one per
     * dispatch branch (reservable, restricted, past-time, unreservable), proving
     * DisplaySlotFactory routing is byte-identical in both engines.
     */
    public function testSchedulePageInteractiveGridAllBranchesMatchesSmarty(): void
    {
        $periods = [
            $this->makePeriod(1, '08:00'),
            $this->makePeriod(1, '09:00'),
            $this->makePeriod(1, '10:00'),
            $this->makePeriod(1, '11:00'),
        ];
        $slots = [
            $this->makeSlot(1, '2025-06-15 08:00:00', '2025-06-15 09:00:00', ''),
            $this->makeSlot(2, '2025-06-15 09:00:00', '2025-06-15 10:00:00', ''),
            $this->makeSlot(3, '2025-06-15 10:00:00', '2025-06-15 11:00:00', ''),
            $this->makeSlot(4, '2025-06-15 11:00:00', '2025-06-15 12:00:00', ''),
        ];

        $dailyLayout = new class ($periods, $slots) {
            /** @param object[] $periods @param object[] $slots */
            public function __construct(private array $periods, private array $slots)
            {
            }
            /** @return object[] */
            public function GetPeriods(mixed $date, bool $withLabels): array
            {
                return $withLabels ? $this->periods : $this->slots;
            }
            public function IsDateReservable(mixed $date): bool
            {
                return true;
            }
        };

        $map = [
            1 => 'displayReservable',
            2 => 'displayRestricted',
            3 => 'displayPastTime',
            4 => 'displayUnreservable',
        ];

        $vars = $this->makeSchedulePageVars($map, $dailyLayout, $this->makeResource(10, 'Room A'));
        $this->assertParity('Schedule/schedule.tpl', 'Schedule/schedule.twig', $vars);
    }

    /**
     * Full schedule page: resource with a custom color and no-book access, so the
     * resource-name renders via the non-linked (span) branch with color styles.
     */
    public function testSchedulePageColoredNonBookableResourceMatchesSmarty(): void
    {
        $periods = [$this->makePeriod(2, 'Session')];
        $slots = [
            $this->makeSlot(1, '2025-06-15 08:00:00', '2025-06-15 09:00:00', ''),
            $this->makeSlot(2, '2025-06-15 09:00:00', '2025-06-15 10:00:00', ''),
        ];

        $dailyLayout = new class ($periods, $slots) {
            /** @param object[] $periods @param object[] $slots */
            public function __construct(private array $periods, private array $slots)
            {
            }
            /** @return object[] */
            public function GetPeriods(mixed $date, bool $withLabels): array
            {
                return $withLabels ? $this->periods : $this->slots;
            }
            public function IsDateReservable(mixed $date): bool
            {
                return true;
            }
        };

        $map = [1 => 'displayRestricted', 2 => 'displayUnreservable'];

        $vars = $this->makeSchedulePageVars($map, $dailyLayout, $this->makeResource(11, 'Colored Room', false, false, true));
        $this->assertParity('Schedule/schedule.tpl', 'Schedule/schedule.twig', $vars);
    }
}
