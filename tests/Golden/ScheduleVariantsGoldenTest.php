<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../Pages/Pages.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for the Schedule variant templates:
 *   - tpl/Schedule/schedule-week-condensed.tpl  → .twig
 *   - tpl/Schedule/schedule-mobile.tpl          → .twig
 *   - tpl/Schedule/schedule-flipped.tpl         → .twig  (tall/flipped grid)
 *   - tpl/Schedule/schedule-days-horizontal.tpl → .twig  (wide/horizontal grid)
 *
 * Dispatch coverage strategy
 * --------------------------
 * Condensed and Mobile: these variants load reservations asynchronously via JS.
 * The PHP templates define {function name=displaySlot*} but the actual call site
 * is commented out in the Smarty source (the PHP render only emits an add-button
 * div and an empty `<div class="reservations">` container).  The macro files are
 * created for completeness per the design, but no dispatch call is exercised in
 * the PHP render.  The golden tests cover full structural parity of the page.
 *
 * Flipped (Tall) and Wide (Days-Horizontal): these variants DO dispatch in PHP.
 * Period objects are passed as slot args; DisplaySlotFactory::GetFunction() is
 * called per period per resource.  Fixtures drive all four dispatch branches
 * (displayReservable, displayRestricted, displayPastTime, displayUnreservable)
 * by using a fake factory that returns a specific name per period Id.
 *
 * Notes:
 * - Clock pinned to 2025-06-15 10:00:00 UTC.
 * - CSRF token pinned via FakeServer.
 * - All variant templates extend schedule.twig/schedule.tpl so the full page
 *   chrome is included; makeSchedulePageVars() provides the shared chrome vars.
 */
class ScheduleVariantsGoldenTest extends GoldenTemplateTestCase
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
     * A period/slot double that works for both header periods and slot dispatch.
     * Covers all methods called by the base macros and the flipped/wide grids.
     */
    private function makePeriodSlot(
        int $id,
        string $begin,
        string $end,
        string $label,
        int $span = 1,
        bool $isReservable = true
    ): object {
        return new class ($id, $begin, $end, $label, $span, $isReservable) {
            public function __construct(
                private int $id,
                private string $begin,
                private string $end,
                private string $label,
                private int $span,
                private bool $isReservable
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
            public function Label(mixed $date = null): string
            {
                return $this->label;
            }
            public function Id(): int
            {
                return $this->id;
            }
            public function Span(): int
            {
                return $this->span;
            }
            public function PeriodSpan(): int
            {
                return $this->span;
            }
            public function IsReservable(): bool
            {
                return $this->isReservable;
            }
            public function IsPastDate(): bool
            {
                // Past if begin is before pinned Now (2025-06-15 10:00:00 UTC)
                return Date::Parse($this->begin, 'UTC')->LessThan(Date::Now());
            }
            public function IsReserved(): bool
            {
                return false;
            }
            public function IsPending(): bool
            {
                return false;
            }
            public function HasCustomColor(): bool
            {
                return false;
            }
            public function Color(): string
            {
                return '';
            }
            public function TextColor(): string
            {
                return '';
            }
            public function IsNew(): bool
            {
                return false;
            }
            public function IsUpdated(): bool
            {
                return false;
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

    /**
     * Shared page chrome vars for any schedule variant (extends schedule.tpl/twig).
     *
     * @param array<string,string> $factoryMap period/slot-Id -> dispatch name
     * @return array<string,mixed>
     */
    private function makeSchedulePageVars(array $factoryMap, object $dailyLayout, object $resource): array
    {
        $factory = new class ($factoryMap) {
            /** @param array<int,string> $map */
            public function __construct(private array $map)
            {
            }
            public function GetFunction(mixed $slot, mixed $accessAllowed = false, mixed $suffix = ''): string
            {
                return $this->map[$slot->Id()] ?? 'displayUnreservable';
            }
            /** @param object[] $periods */
            public function GetCondensedPeriodLabel(array $periods, Date $start, Date $end): string
            {
                return $start->Format('H:i') . ' - ' . $end->Format('H:i');
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
            'SlotLabelFactory' => null,
            'CreateReservationPage' => 'reservation.php',
            'LoadViewOnly' => false,
            'AllowGuestBooking' => false,
            'AllowCreatePastReservationsButton' => false,
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

    // ── condensed week ─────────────────────────────────────────────────────────

    /**
     * Condensed week: slots are loaded via JS, not PHP dispatch.
     * Covers structural page parity including the add-button and AJAX container.
     */
    public function testCondensedWeekPageStructureMatchesSmarty(): void
    {
        $date = Date::Parse('2025-06-15', 'UTC');
        $periods = [
            $this->makePeriodSlot(1, '2025-06-15 08:00:00', '2025-06-15 09:00:00', '08:00'),
            $this->makePeriodSlot(2, '2025-06-15 09:00:00', '2025-06-15 10:00:00', '09:00'),
        ];

        $dailyLayout = new class ($periods) {
            /** @param object[] $periods */
            public function __construct(private array $periods)
            {
            }
            /** @return object[] */
            public function GetPeriods(mixed $date, mixed $flag = true): array
            {
                return $this->periods;
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return $this->periods;
            }
            public function IsDateReservable(mixed $date): bool
            {
                return true;
            }
        };

        $map = [
            1 => 'displayReservable',
            2 => 'displayUnreservable',
        ];

        $vars = $this->makeSchedulePageVars($map, $dailyLayout, $this->makeResource(10, 'Room A'));
        $this->assertParity(
            'Schedule/schedule-week-condensed.tpl',
            'Schedule/schedule-week-condensed.twig',
            $vars
        );
    }

    /**
     * Condensed week: today date highlighted; non-bookable resource uses span not anchor.
     */
    public function testCondensedWeekTodayAndNonAccessibleResourceMatchesSmarty(): void
    {
        // Date matches pinned now → triggers today CSS class
        $date = Date::Parse('2025-06-15', 'UTC');
        $periods = [
            $this->makePeriodSlot(1, '2025-06-15 08:00:00', '2025-06-15 09:00:00', '08:00'),
        ];

        $dailyLayout = new class ($periods) {
            /** @param object[] $periods */
            public function __construct(private array $periods)
            {
            }
            /** @return object[] */
            public function GetPeriods(mixed $date, mixed $flag = true): array
            {
                return $this->periods;
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return $this->periods;
            }
            public function IsDateReservable(mixed $date): bool
            {
                return false;
            }
        };

        $map = [1 => 'displayRestricted'];
        $vars = $this->makeSchedulePageVars($map, $dailyLayout, $this->makeResource(11, 'Restricted Room', false, false));
        $vars['AllowCreatePastReservationsButton'] = false;
        $this->assertParity(
            'Schedule/schedule-week-condensed.tpl',
            'Schedule/schedule-week-condensed.twig',
            $vars
        );
    }

    // ── mobile ─────────────────────────────────────────────────────────────────

    /**
     * Mobile: slots are loaded via JS, not PHP dispatch.
     * Covers structural parity: date header row, resource rows, add button, AJAX container.
     */
    public function testMobilePageStructureMatchesSmarty(): void
    {
        $date = Date::Parse('2025-06-15', 'UTC');
        $periods = [
            $this->makePeriodSlot(1, '2025-06-15 08:00:00', '2025-06-15 09:00:00', '08:00'),
            $this->makePeriodSlot(2, '2025-06-15 09:00:00', '2025-06-15 10:00:00', '09:00'),
        ];

        $dailyLayout = new class ($periods) {
            /** @param object[] $periods */
            public function __construct(private array $periods)
            {
            }
            /** @return object[] */
            public function GetPeriods(mixed $date, mixed $flag = true): array
            {
                return $this->periods;
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return $this->periods;
            }
            public function IsDateReservable(mixed $date): bool
            {
                return true;
            }
        };

        $map = [
            1 => 'displayReservable',
            2 => 'displayUnreservable',
        ];

        $vars = $this->makeSchedulePageVars($map, $dailyLayout, $this->makeResource(20, 'Mobile Room'));
        $this->assertParity(
            'Schedule/schedule-mobile.tpl',
            'Schedule/schedule-mobile.twig',
            $vars
        );
    }

    /**
     * Mobile: today highlighted, non-accessible resource renders span not anchor+icon link.
     */
    public function testMobileTodayAndNonAccessibleResourceMatchesSmarty(): void
    {
        $date = Date::Parse('2025-06-15', 'UTC');
        $periods = [
            $this->makePeriodSlot(1, '2025-06-15 08:00:00', '2025-06-15 09:00:00', '08:00'),
        ];

        $dailyLayout = new class ($periods) {
            /** @param object[] $periods */
            public function __construct(private array $periods)
            {
            }
            /** @return object[] */
            public function GetPeriods(mixed $date, mixed $flag = true): array
            {
                return $this->periods;
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return $this->periods;
            }
            public function IsDateReservable(mixed $date): bool
            {
                return false;
            }
        };

        $map = [1 => 'displayRestricted'];
        $vars = $this->makeSchedulePageVars($map, $dailyLayout, $this->makeResource(21, 'No Access Mobile', false, false, true));
        $this->assertParity(
            'Schedule/schedule-mobile.tpl',
            'Schedule/schedule-mobile.twig',
            $vars
        );
    }

    // ── flipped (tall) ─────────────────────────────────────────────────────────

    /**
     * Flipped (Tall): ALL FOUR dispatch branches exercised.
     * Period objects serve as both period labels and slot args to GetFunction().
     * Periods are: reservable(future), restricted(access=false), past-time, unreservable.
     * Resource CanAccess is set per slot via separate resources-per-period trick:
     * we use a single resource with CanAccess=true and let the factory map drive branches.
     */
    public function testFlippedGridAllDispatchBranchesMatchesSmarty(): void
    {
        // Period IDs map to dispatch branches via fake factory
        $periods = [
            $this->makePeriodSlot(1, '2025-06-15 11:00:00', '2025-06-15 12:00:00', '11:00 - 12:00', 1, true),
            $this->makePeriodSlot(2, '2025-06-15 12:00:00', '2025-06-15 13:00:00', '12:00 - 13:00', 1, true),
            $this->makePeriodSlot(3, '2025-06-14 08:00:00', '2025-06-14 09:00:00', '08:00 - 09:00', 1, true),
            $this->makePeriodSlot(4, '2025-06-15 14:00:00', '2025-06-15 15:00:00', '14:00 - 15:00', 1, false),
        ];

        $dailyLayout = new class ($periods) {
            /** @param object[] $periods */
            public function __construct(private array $periods)
            {
            }
            /** @return object[] */
            public function GetPeriods(mixed $date, mixed $flag = true): array
            {
                return $this->periods;
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return $this->periods;
            }
            public function IsDateReservable(mixed $date): bool
            {
                return true;
            }
        };

        // Force all four displayX branches via fake factory
        $map = [
            1 => 'displayReservable',
            2 => 'displayRestricted',
            3 => 'displayPastTime',
            4 => 'displayUnreservable',
        ];

        $vars = $this->makeSchedulePageVars($map, $dailyLayout, $this->makeResource(30, 'Tall Room'));
        $this->assertParity(
            'Schedule/schedule-flipped.tpl',
            'Schedule/schedule-flipped.twig',
            $vars
        );
    }

    /**
     * Flipped (Tall): non-accessible resource (span instead of link) + today date.
     */
    public function testFlippedGridColoredNonAccessibleResourceMatchesSmarty(): void
    {
        $periods = [
            $this->makePeriodSlot(1, '2025-06-15 11:00:00', '2025-06-15 12:00:00', '11:00', 1, true),
            $this->makePeriodSlot(2, '2025-06-15 12:00:00', '2025-06-15 13:00:00', '12:00', 1, true),
        ];

        $dailyLayout = new class ($periods) {
            /** @param object[] $periods */
            public function __construct(private array $periods)
            {
            }
            /** @return object[] */
            public function GetPeriods(mixed $date, mixed $flag = true): array
            {
                return $this->periods;
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return $this->periods;
            }
            public function IsDateReservable(mixed $date): bool
            {
                return false;
            }
        };

        $map = [1 => 'displayRestricted', 2 => 'displayUnreservable'];
        $vars = $this->makeSchedulePageVars($map, $dailyLayout, $this->makeResource(31, 'Colored Tall', false, false, true));
        $this->assertParity(
            'Schedule/schedule-flipped.tpl',
            'Schedule/schedule-flipped.twig',
            $vars
        );
    }

    // ── days-horizontal (wide) ─────────────────────────────────────────────────

    /**
     * Days-Horizontal (Wide): ALL FOUR dispatch branches exercised.
     * Period objects serve as both column headers and slot args to GetFunction().
     */
    public function testWideGridAllDispatchBranchesMatchesSmarty(): void
    {
        // Four periods covering all four dispatch branches
        $periods = [
            $this->makePeriodSlot(1, '2025-06-15 11:00:00', '2025-06-15 12:00:00', '11:00', 1, true),
            $this->makePeriodSlot(2, '2025-06-15 12:00:00', '2025-06-15 13:00:00', '12:00', 1, true),
            $this->makePeriodSlot(3, '2025-06-14 08:00:00', '2025-06-14 09:00:00', '08:00', 1, true),
            $this->makePeriodSlot(4, '2025-06-15 14:00:00', '2025-06-15 15:00:00', '14:00', 1, false),
        ];

        $dailyLayout = new class ($periods) {
            /** @param object[] $periods */
            public function __construct(private array $periods)
            {
            }
            /** @return object[] */
            public function GetPeriods(mixed $date, mixed $flag = true): array
            {
                return $this->periods;
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return $this->periods;
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

        $vars = $this->makeSchedulePageVars($map, $dailyLayout, $this->makeResource(40, 'Wide Room'));
        $this->assertParity(
            'Schedule/schedule-days-horizontal.tpl',
            'Schedule/schedule-days-horizontal.twig',
            $vars
        );
    }

    /**
     * Days-Horizontal (Wide): non-accessible resource (span), today highlight,
     * multi-span periods to test colspan attribute.
     */
    public function testWideGridColoredResourceAndMultiSpanMatchesSmarty(): void
    {
        $periods = [
            $this->makePeriodSlot(1, '2025-06-15 08:00:00', '2025-06-15 10:00:00', 'Morning', 2, true),
            $this->makePeriodSlot(2, '2025-06-15 10:00:00', '2025-06-15 12:00:00', 'Afternoon', 2, false),
        ];

        $dailyLayout = new class ($periods) {
            /** @param object[] $periods */
            public function __construct(private array $periods)
            {
            }
            /** @return object[] */
            public function GetPeriods(mixed $date, mixed $flag = true): array
            {
                return $this->periods;
            }
            /** @return object[] */
            public function GetLayout(mixed $date, mixed $resourceId): array
            {
                return $this->periods;
            }
            public function IsDateReservable(mixed $date): bool
            {
                return false;
            }
        };

        $map = [1 => 'displayReservable', 2 => 'displayUnreservable'];
        $vars = $this->makeSchedulePageVars($map, $dailyLayout, $this->makeResource(41, 'Colored Wide', false, false, true));
        $this->assertParity(
            'Schedule/schedule-days-horizontal.tpl',
            'Schedule/schedule-days-horizontal.twig',
            $vars
        );
    }
}
