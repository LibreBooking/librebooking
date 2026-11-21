<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../lib/Application/Schedule/namespace.php');
require_once(__DIR__ . '/../../Pages/Export/EmbeddedCalendarPage.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');
require_once(__DIR__ . '/../../Domain/ReservationItemView.php');

/**
 * Live Smarty-vs-Twig golden comparison for Export embedded-calendar templates:
 *   - tpl/Export/embedded-calendar-agenda.tpl  → tpl/Export/embedded-calendar-agenda.twig
 *   - tpl/Export/embedded-calendar-week.tpl    → tpl/Export/embedded-calendar-week.twig
 *   - tpl/Export/embedded-calendar-month.tpl   → tpl/Export/embedded-calendar-month.twig
 *
 * Both engines are rendered in the same process with identical template variables
 * and superglobal state; normalized outputs are asserted byte-identical.
 *
 * Clock pinning: Date::_SetNow is set in setUp() to prevent same-process
 * Smarty-vs-Twig divergence across midnight boundaries for DateEquals(Date::Now())
 * checks (today-highlight logic).
 */
class ExportGoldenTest extends GoldenTemplateTestCase
{
    private ?Resources $savedResources = null;

    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();

        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/index.php';

        $this->savedServiceLocatorServer = ServiceLocator::GetServer();
        $fakeServer = new FakeServer();
        $fakeServer->UserSession->CSRFToken = 'golden-test-csrf-token';
        ServiceLocator::SetServer($fakeServer);

        Date::_SetNow(Date::Parse('2025-06-15 12:00:00', 'UTC'));
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->savedServer;
        Resources::SetInstance($this->savedResources);
        ServiceLocator::SetServer($this->savedServiceLocatorServer);

        $prop = new \ReflectionProperty(Date::class, '_Now');
        $prop->setAccessible(true);
        $prop->setValue(null, null);

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

    private function makeReservationItem(
        string $refNum,
        Date $startDate,
        Date $endDate,
        string $title = 'Test Reservation',
        string $resourceName = 'Room A',
        string $userFirstName = 'John',
        string $userLastName = 'Doe',
        int $resourceId = 1,
        int $reservationId = 1,
        int $userId = 1
    ): ReservationItemView {
        $item = new ReservationItemView(
            $refNum,
            $startDate,
            $endDate,
            $resourceName,
            $resourceId,
            $reservationId,
            ReservationUserLevel::OWNER,
            $title,
            '',
            1,
            $userFirstName,
            $userLastName,
            $userId
        );
        return $item;
    }

    /**
     * @return array<string, mixed>
     */
    private function agendaVars(IReservationListing $listing, DateRange $range): array
    {
        return [
            'Reservations' => $listing,
            'Timezone' => 'UTC',
            'Range' => $range,
            'Width' => '14.285714285714%',
            'ReservationUrl' => 'https://example.com/reservation?refnum=',
            'ScheduleUrl' => 'https://example.com/schedule?date=',
            'TitleFormatter' => new EmbeddedCalendarTitleFormatter('UTC', 'agenda', ''),
            'ScriptUrl' => 'https://example.com',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function weekVars(IReservationListing $listing, DateRange $range): array
    {
        return [
            'Reservations' => $listing,
            'Timezone' => 'UTC',
            'Range' => $range,
            'Width' => '14.285714285714%',
            'ReservationUrl' => 'https://example.com/reservation?refnum=',
            'ScheduleUrl' => 'https://example.com/schedule?date=',
            'TitleFormatter' => new EmbeddedCalendarTitleFormatter('UTC', 'week', ''),
            'ScriptUrl' => 'https://example.com',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function monthVars(IReservationListing $listing, DateRange $range): array
    {
        return [
            'Reservations' => $listing,
            'Timezone' => 'UTC',
            'Range' => $range,
            'Width' => '14.285714285714%',
            'ReservationUrl' => 'https://example.com/reservation?refnum=',
            'ScheduleUrl' => 'https://example.com/schedule?date=',
            'TitleFormatter' => new EmbeddedCalendarTitleFormatter('UTC', 'month', ''),
            'ScriptUrl' => 'https://example.com',
        ];
    }

    // ── agenda ───────────────────────────────────────────────────────────────

    /**
     * No reservations: "NoReservationsFound" message appears.
     */
    public function testAgendaEmptyMatchesSmarty(): void
    {
        $range = new DateRange(
            Date::Parse('2025-06-15', 'UTC'),
            Date::Parse('2025-06-20', 'UTC')
        );
        $vars = $this->agendaVars(new EmptyReservationListing(), $range);
        $this->assertParity(
            'Export/embedded-calendar-agenda.tpl',
            'Export/embedded-calendar-agenda.twig',
            $vars
        );
    }

    /**
     * Two reservations on different dates: date headers appear.
     * color='' (no color set) — color conditional takes the false branch in both engines.
     */
    public function testAgendaWithReservationsMatchesSmarty(): void
    {
        $range = new DateRange(
            Date::Parse('2025-06-15', 'UTC'),
            Date::Parse('2025-06-20', 'UTC')
        );

        $start1 = Date::Parse('2025-06-15 09:00:00', 'UTC');
        $end1 = Date::Parse('2025-06-15 10:00:00', 'UTC');
        $item1 = $this->makeReservationItem('REF001', $start1, $end1, 'Morning Meeting');

        $start2 = Date::Parse('2025-06-17 14:00:00', 'UTC');
        $end2 = Date::Parse('2025-06-17 15:00:00', 'UTC');
        $item2 = $this->makeReservationItem('REF002', $start2, $end2, 'Afternoon Session', 'Lab B', 'Jane', 'Smith', 2, 2, 2);

        $listing = new ReservationListing('UTC', $range);
        $listing->Add($item1);
        $listing->Add($item2);

        $vars = $this->agendaVars($listing, $range);
        $this->assertParity(
            'Export/embedded-calendar-agenda.tpl',
            'Export/embedded-calendar-agenda.twig',
            $vars
        );
    }

    /**
     * Two reservations on the same date: only one date header appears (second suppressed).
     */
    public function testAgendaWithReservationsSameDateMatchesSmarty(): void
    {
        $range = new DateRange(
            Date::Parse('2025-06-15', 'UTC'),
            Date::Parse('2025-06-20', 'UTC')
        );

        $start1 = Date::Parse('2025-06-15 09:00:00', 'UTC');
        $end1 = Date::Parse('2025-06-15 10:00:00', 'UTC');
        $item1 = $this->makeReservationItem('REF003', $start1, $end1, 'First Session');

        $start2 = Date::Parse('2025-06-15 14:00:00', 'UTC');
        $end2 = Date::Parse('2025-06-15 15:00:00', 'UTC');
        $item2 = $this->makeReservationItem('REF004', $start2, $end2, 'Second Session', 'Room A', 'Jane', 'Doe', 1, 2, 2);

        $listing = new ReservationListing('UTC', $range);
        $listing->Add($item1);
        $listing->Add($item2);

        $vars = $this->agendaVars($listing, $range);
        $this->assertParity(
            'Export/embedded-calendar-agenda.tpl',
            'Export/embedded-calendar-agenda.twig',
            $vars
        );
    }

    // ── week ─────────────────────────────────────────────────────────────────

    /**
     * No reservations: day-name headers and today-highlight rendered, no event rows.
     */
    public function testWeekEmptyMatchesSmarty(): void
    {
        $range = new DateRange(
            Date::Parse('2025-06-09', 'UTC'),
            Date::Parse('2025-06-16', 'UTC')
        );
        $vars = $this->weekVars(new EmptyReservationListing(), $range);
        $this->assertParity(
            'Export/embedded-calendar-week.tpl',
            'Export/embedded-calendar-week.twig',
            $vars
        );
    }

    /**
     * One reservation on Wednesday.
     * color='' (no color set) — color conditional takes the false branch in both engines.
     */
    public function testWeekWithReservationsMatchesSmarty(): void
    {
        $range = new DateRange(
            Date::Parse('2025-06-09', 'UTC'),
            Date::Parse('2025-06-16', 'UTC')
        );

        $start = Date::Parse('2025-06-11 10:00:00', 'UTC');
        $end = Date::Parse('2025-06-11 11:00:00', 'UTC');
        $item = $this->makeReservationItem('REF005', $start, $end, 'Wednesday Meeting');

        $listing = new ReservationListing('UTC', $range);
        $listing->Add($item);

        $vars = $this->weekVars($listing, $range);
        $this->assertParity(
            'Export/embedded-calendar-week.tpl',
            'Export/embedded-calendar-week.twig',
            $vars
        );
    }

    // ── month ─────────────────────────────────────────────────────────────────

    /**
     * No reservations: 6-week grid rendered, today-highlight, foreachelse &nbsp;.
     */
    public function testMonthEmptyMatchesSmarty(): void
    {
        $range = new DateRange(
            Date::Parse('2025-06-01', 'UTC'),
            Date::Parse('2025-06-30', 'UTC')
        );
        $vars = $this->monthVars(new EmptyReservationListing(), $range);
        $this->assertParity(
            'Export/embedded-calendar-month.tpl',
            'Export/embedded-calendar-month.twig',
            $vars
        );
    }

    /**
     * One reservation: TitleFormatter output exercised.
     * color='' (no color set) — color conditional takes the false branch in both engines.
     */
    public function testMonthWithReservationsMatchesSmarty(): void
    {
        $range = new DateRange(
            Date::Parse('2025-06-01', 'UTC'),
            Date::Parse('2025-06-30', 'UTC')
        );

        $start = Date::Parse('2025-06-15 09:00:00', 'UTC');
        $end = Date::Parse('2025-06-15 10:00:00', 'UTC');
        $item = $this->makeReservationItem('REF006', $start, $end, 'June Meeting');

        $listing = new ReservationListing('UTC', $range);
        $listing->Add($item);

        $vars = $this->monthVars($listing, $range);
        $this->assertParity(
            'Export/embedded-calendar-month.tpl',
            'Export/embedded-calendar-month.twig',
            $vars
        );
    }

    // ── color-branch coverage ─────────────────────────────────────────────────

    /**
     * Agenda: color conditional true branch.
     *
     * Smarty emits a stray } after the style attribute due to the way {if}
     * tags close inside an HTML attribute context; Twig correctly omits it.
     * This is a known intentional divergence — Twig is correct.
     *
     * We assert Twig output separately rather than using assertParity().
     */
    public function testAgendaColorConditionalTwigOutput(): void
    {
        $range = new DateRange(
            Date::Parse('2025-06-15', 'UTC'),
            Date::Parse('2025-06-20', 'UTC')
        );

        $start = Date::Parse('2025-06-15 09:00:00', 'UTC');
        $end = Date::Parse('2025-06-15 10:00:00', 'UTC');
        $item = $this->makeReservationItem('REF-COLOR-A', $start, $end, 'Colored Meeting');
        $item->ResourceColor = '#3399ff';

        $listing = new ReservationListing('UTC', $range);
        $listing->Add($item);

        $vars = $this->agendaVars($listing, $range);

        // Twig: color conditional renders the style attribute correctly (no stray }).
        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigOutput = $twig->render('Export/embedded-calendar-agenda.twig');

        $this->assertStringContainsString('background-color:#3399ff !important', $twigOutput);
        // Twig must NOT emit a stray } immediately after the closing double-quote of the style attribute.
        $this->assertStringNotContainsString('!important"}'."\n", $twigOutput);
        $this->assertStringNotContainsString('!important"}>', $twigOutput);

        // Smarty emits a stray } after the style attribute — document the known bug.
        // Smarty emits a stray } after the style attribute; Twig correctly omits it.
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyOutput = $smarty->render('Export/embedded-calendar-agenda.tpl');

        $this->assertStringContainsString('background-color:#3399ff !important', $smartyOutput);
        $this->assertStringContainsString('!important"}>', $smartyOutput);
    }

    /**
     * Week: color conditional true branch.
     *
     * Smarty emits a stray } after the style attribute; Twig correctly omits it.
     * We assert each engine independently rather than using assertParity().
     */
    public function testWeekColorConditionalTwigOutput(): void
    {
        $range = new DateRange(
            Date::Parse('2025-06-09', 'UTC'),
            Date::Parse('2025-06-16', 'UTC')
        );

        $start = Date::Parse('2025-06-11 10:00:00', 'UTC');
        $end = Date::Parse('2025-06-11 11:00:00', 'UTC');
        $item = $this->makeReservationItem('REF-COLOR-W', $start, $end, 'Colored Wednesday');
        $item->ResourceColor = '#3399ff';

        $listing = new ReservationListing('UTC', $range);
        $listing->Add($item);

        $vars = $this->weekVars($listing, $range);

        // Twig: color conditional renders the style attribute correctly (no stray }).
        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigOutput = $twig->render('Export/embedded-calendar-week.twig');

        $this->assertStringContainsString('background-color:#3399ff !important', $twigOutput);
        // Twig must NOT emit a stray } after the closing double-quote of the style attribute.
        $this->assertStringNotContainsString('!important"}', $twigOutput);

        // Smarty emits a stray } after the style attribute; Twig correctly omits it.
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyOutput = $smarty->render('Export/embedded-calendar-week.tpl');

        $this->assertStringContainsString('background-color:#3399ff !important', $smartyOutput);
        $this->assertStringContainsString('!important"', $smartyOutput);
        // The stray } appears on its own line after the style value in the week template.
        $this->assertMatchesRegularExpression('/!important"\s*\}/', $smartyOutput);
    }

    /**
     * Month: color conditional true branch.
     *
     * Smarty emits a stray } after the style attribute; Twig correctly omits it.
     * We assert each engine independently rather than using assertParity().
     */
    public function testMonthColorConditionalTwigOutput(): void
    {
        $range = new DateRange(
            Date::Parse('2025-06-01', 'UTC'),
            Date::Parse('2025-06-30', 'UTC')
        );

        $start = Date::Parse('2025-06-15 09:00:00', 'UTC');
        $end = Date::Parse('2025-06-15 10:00:00', 'UTC');
        $item = $this->makeReservationItem('REF-COLOR-M', $start, $end, 'Colored June Meeting');
        $item->ResourceColor = '#3399ff';

        $listing = new ReservationListing('UTC', $range);
        $listing->Add($item);

        $vars = $this->monthVars($listing, $range);

        // Twig: color conditional renders the style attribute correctly (no stray }).
        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigOutput = $twig->render('Export/embedded-calendar-month.twig');

        $this->assertStringContainsString('background-color:#3399ff !important', $twigOutput);
        // Twig must NOT emit a stray } after the closing double-quote of the style attribute.
        $this->assertStringNotContainsString('!important"}', $twigOutput);

        // Smarty emits a stray } after the style attribute; Twig correctly omits it.
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyOutput = $smarty->render('Export/embedded-calendar-month.tpl');

        $this->assertStringContainsString('background-color:#3399ff !important', $smartyOutput);
        $this->assertStringContainsString('!important"', $smartyOutput);
        // The stray } appears on its own line after the style value in the month template.
        $this->assertMatchesRegularExpression('/!important"\s*\}/', $smartyOutput);
    }
}
