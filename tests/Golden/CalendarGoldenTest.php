<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../lib/Application/Schedule/namespace.php');
require_once(__DIR__ . '/../../Pages/Pages.php');
require_once(__DIR__ . '/../../Pages/Ajax/AutoCompletePage.php');
require_once(__DIR__ . '/../../Presenters/Calendar/CalendarFilters.php');
require_once(__DIR__ . '/../../Presenters/Calendar/CalendarCommon.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for Calendar templates:
 *   - tpl/Calendar/calendar.filter.tpl             → tpl/Calendar/calendar.filter.twig
 *   - tpl/Calendar/calendar.subscription.tpl       → tpl/Calendar/calendar.subscription.twig
 *   - tpl/Calendar/mycalendar.subscription.tpl     → tpl/Calendar/mycalendar.subscription.twig
 *   - tpl/Calendar/calendar-page-base.tpl          → tpl/Calendar/calendar-page-base.twig
 *   - tpl/Calendar/calendar.tpl                    → tpl/Calendar/calendar.twig
 *   - tpl/Calendar/mycalendar.tpl                  → tpl/Calendar/mycalendar.twig
 *
 * Both engines are rendered in the same process with identical template variables
 * and superglobal state; normalized outputs are asserted byte-identical.
 *
 * Notes:
 * - Clock is pinned to 2025-06-15 10:00:00 UTC to make Date::Now() deterministic.
 * - calendar.filter.tpl has a {if false} dead-code block that is faithfully omitted
 *   in the .twig counterpart; both engines produce identical output.
 * - The calendar-page-base includes calendar.filter and the appropriate subscription
 *   sub-template. Both are now .twig, so no render_partial Smarty fallback is needed.
 */
class CalendarGoldenTest extends GoldenTemplateTestCase
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

    /**
     * Build a minimal but valid CalendarFilters object with one schedule + one resource.
     */
    private function makeFilters(bool $withSubfilter = true): CalendarFilters
    {
        $filter = new CalendarFilter(CalendarFilters::FilterSchedule, 1, 'Main Schedule', true);
        if ($withSubfilter) {
            $filter->AddSubFilter(new CalendarFilter(CalendarFilters::FilterResource, 10, 'Conference Room A', false));
        }
        // CalendarFilters constructor requires ResourceGroupTree which we don't easily build.
        // Use anonymous object with GetFilters() directly instead.
        return new class ($filter) extends CalendarFilters {
            private CalendarFilter $f;
            public function __construct(CalendarFilter $f)
            {
                $this->f = $f;
            }
            /** @return CalendarFilter[] */
            public function GetFilters(): array
            {
                return [$this->f];
            }
        };
    }

    /**
     * Build a minimal but valid CalendarFilters object with no schedule.
     */
    private function makeEmptyFilters(): CalendarFilters
    {
        return new class () extends CalendarFilters {
            public function __construct()
            {
            }
            /** @return CalendarFilter[] */
            public function GetFilters(): array
            {
                return [];
            }
        };
    }

    /**
     * Build base calendar vars shared between tests.
     *
     * @return array<string, mixed>
     */
    private function makeBaseVars(): array
    {
        return [
            'filters'               => $this->makeFilters(),
            'CalendarType'          => CalendarTypes::Month,
            'DisplayDate'           => Date::Parse('2025-06-15', 'UTC'),
            'DayNames'              => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            'DayNamesShort'         => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            'MonthNames'            => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'MonthNamesShort'       => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'TimeFormat'            => 'H:i',
            'DateFormat'            => 'd/m/Y',
            'FirstDay'              => 0,
            'ScheduleId'            => 1,
            'ResourceId'            => 0,
            'CreateReservationPage' => 'reservation.php',
            'Path'                  => '/',
            'ShowWeekNumbers'       => false,
            'ResourceGroupsAsJson'  => '[]',
            'IsSubscriptionAllowed' => false,
            'IsSubscriptionEnabled' => false,
            'SubscriptionUrl'       => '',
        ];
    }

    // ── calendar.filter ────────────────────────────────────────────────────────

    /**
     * Filter with GroupName set: only the groupName span renders (no select).
     */
    public function testCalendarFilterGroupNameMatchesSmarty(): void
    {
        $vars = [
            'filters'   => $this->makeEmptyFilters(),
            'GroupName' => 'Room A',
        ];
        $this->assertParity(
            'Calendar/calendar.filter.tpl',
            'Calendar/calendar.filter.twig',
            $vars
        );
    }

    /**
     * Filter without GroupName: select with one schedule + one sub-resource option.
     */
    public function testCalendarFilterSelectMatchesSmarty(): void
    {
        $vars = [
            'filters'   => $this->makeFilters(),
        ];
        $this->assertParity(
            'Calendar/calendar.filter.tpl',
            'Calendar/calendar.filter.twig',
            $vars
        );
    }

    /**
     * Filter with selected subfilter: resource option has selected="selected".
     */
    public function testCalendarFilterSubfilterSelectedMatchesSmarty(): void
    {
        $schedule = new CalendarFilter(CalendarFilters::FilterSchedule, 2, 'Schedule B', false);
        $schedule->AddSubFilter(new CalendarFilter(CalendarFilters::FilterResource, 20, 'Lab Room', true));
        $filters = new class ($schedule) extends CalendarFilters {
            private CalendarFilter $f;
            public function __construct(CalendarFilter $f)
            {
                $this->f = $f;
            }
            /** @return CalendarFilter[] */
            public function GetFilters(): array
            {
                return [$this->f];
            }
        };
        $vars = ['filters' => $filters];
        $this->assertParity(
            'Calendar/calendar.filter.tpl',
            'Calendar/calendar.filter.twig',
            $vars
        );
    }

    // ── calendar.subscription ─────────────────────────────────────────────────

    /**
     * Subscription not allowed and not enabled: empty div.
     */
    public function testCalendarSubscriptionNotAllowedMatchesSmarty(): void
    {
        $vars = [
            'IsSubscriptionAllowed' => false,
            'IsSubscriptionEnabled' => false,
            'SubscriptionUrl'       => '',
        ];
        $this->assertParity(
            'Calendar/calendar.subscription.tpl',
            'Calendar/calendar.subscription.twig',
            $vars
        );
    }

    /**
     * Subscription allowed and enabled: subscribe link shown.
     */
    public function testCalendarSubscriptionEnabledMatchesSmarty(): void
    {
        $vars = [
            'IsSubscriptionAllowed' => true,
            'IsSubscriptionEnabled' => true,
            'SubscriptionUrl'       => 'https://example.com/subscribe?token=abc123',
        ];
        $this->assertParity(
            'Calendar/calendar.subscription.tpl',
            'Calendar/calendar.subscription.twig',
            $vars
        );
    }

    // ── mycalendar.subscription ───────────────────────────────────────────────

    /**
     * My-calendar subscription: neither allowed nor enabled — empty div.
     */
    public function testMyCalendarSubscriptionNotAllowedMatchesSmarty(): void
    {
        $vars = [
            'IsSubscriptionAllowed' => false,
            'IsSubscriptionEnabled' => false,
            'SubscriptionUrl'       => '',
        ];
        $this->assertParity(
            'Calendar/mycalendar.subscription.tpl',
            'Calendar/mycalendar.subscription.twig',
            $vars
        );
    }

    /**
     * My-calendar subscription: allowed and enabled — turnOff link + subscribe link.
     */
    public function testMyCalendarSubscriptionAllowedAndEnabledMatchesSmarty(): void
    {
        $vars = [
            'IsSubscriptionAllowed' => true,
            'IsSubscriptionEnabled' => true,
            'SubscriptionUrl'       => 'https://example.com/ical/token123',
        ];
        $this->assertParity(
            'Calendar/mycalendar.subscription.tpl',
            'Calendar/mycalendar.subscription.twig',
            $vars
        );
    }

    /**
     * My-calendar subscription: not allowed but enabled — turnOn link shown.
     * This is the elseif branch: IsSubscriptionAllowed=false, IsSubscriptionEnabled=true.
     */
    public function testMyCalendarSubscriptionNotAllowedButEnabledMatchesSmarty(): void
    {
        $vars = [
            'IsSubscriptionAllowed' => false,
            'IsSubscriptionEnabled' => true,
            'SubscriptionUrl'       => '',
        ];
        $this->assertParity(
            'Calendar/mycalendar.subscription.tpl',
            'Calendar/mycalendar.subscription.twig',
            $vars
        );
    }

    // ── calendar.tpl (full page via calendar-page-base) ───────────────────────

    /**
     * Full calendar page: no HideCreate, no GroupId, subscription not allowed.
     */
    public function testCalendarPageBasicMatchesSmarty(): void
    {
        $vars = $this->makeBaseVars();
        $this->assertParity(
            'Calendar/calendar.tpl',
            'Calendar/calendar.twig',
            $vars
        );
    }

    /**
     * Full calendar page: HideCreate=true hides the create reservation link.
     */
    public function testCalendarPageHideCreateMatchesSmarty(): void
    {
        $vars = array_merge($this->makeBaseVars(), [
            'HideCreate' => true,
        ]);
        $this->assertParity(
            'Calendar/calendar.tpl',
            'Calendar/calendar.twig',
            $vars
        );
    }

    /**
     * Full calendar page: with GroupId in context (affects JS dayClickUrl and eventsData).
     */
    public function testCalendarPageWithGroupIdMatchesSmarty(): void
    {
        $vars = array_merge($this->makeBaseVars(), [
            'GroupId'           => 5,
            'SelectedGroupNode' => 5,
        ]);
        $this->assertParity(
            'Calendar/calendar.tpl',
            'Calendar/calendar.twig',
            $vars
        );
    }

    /**
     * Full calendar page: subscription enabled, shows subscribe link.
     */
    public function testCalendarPageWithSubscriptionMatchesSmarty(): void
    {
        $vars = array_merge($this->makeBaseVars(), [
            'IsSubscriptionAllowed' => true,
            'IsSubscriptionEnabled' => true,
            'SubscriptionUrl'       => 'https://example.com/subscribe?token=xyz',
        ]);
        $this->assertParity(
            'Calendar/calendar.tpl',
            'Calendar/calendar.twig',
            $vars
        );
    }

    /**
     * Full calendar page: GroupName set, hides the filter select.
     */
    public function testCalendarPageWithGroupNameMatchesSmarty(): void
    {
        $vars = array_merge($this->makeBaseVars(), [
            'GroupName' => 'Conference Rooms',
        ]);
        $this->assertParity(
            'Calendar/calendar.tpl',
            'Calendar/calendar.twig',
            $vars
        );
    }

    /**
     * Full calendar page: ShowWeekNumbers=true changes JS option.
     */
    public function testCalendarPageShowWeekNumbersMatchesSmarty(): void
    {
        $vars = array_merge($this->makeBaseVars(), [
            'ShowWeekNumbers' => true,
        ]);
        $this->assertParity(
            'Calendar/calendar.tpl',
            'Calendar/calendar.twig',
            $vars
        );
    }

    // ── mycalendar.tpl (full page via calendar-page-base) ─────────────────────

    /**
     * My-calendar page: subscription not allowed.
     */
    public function testMyCalendarPageBasicMatchesSmarty(): void
    {
        $vars = $this->makeBaseVars();
        $this->assertParity(
            'Calendar/mycalendar.tpl',
            'Calendar/mycalendar.twig',
            $vars
        );
    }

    /**
     * My-calendar page: subscription allowed and enabled — turnOff + subscribe shown.
     */
    public function testMyCalendarPageWithSubscriptionMatchesSmarty(): void
    {
        $vars = array_merge($this->makeBaseVars(), [
            'IsSubscriptionAllowed' => true,
            'IsSubscriptionEnabled' => true,
            'SubscriptionUrl'       => 'https://example.com/ical/mytoken',
        ]);
        $this->assertParity(
            'Calendar/mycalendar.tpl',
            'Calendar/mycalendar.twig',
            $vars
        );
    }

    /**
     * My-calendar page: subscription enabled but not allowed — turnOn link shown.
     */
    public function testMyCalendarPageSubscriptionEnabledNotAllowedMatchesSmarty(): void
    {
        $vars = array_merge($this->makeBaseVars(), [
            'IsSubscriptionAllowed' => false,
            'IsSubscriptionEnabled' => true,
            'SubscriptionUrl'       => '',
        ]);
        $this->assertParity(
            'Calendar/mycalendar.tpl',
            'Calendar/mycalendar.twig',
            $vars
        );
    }
}
