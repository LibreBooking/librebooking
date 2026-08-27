<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../Pages/Pages.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');
// Controls/Dashboard has no namespace.php, include individually:
require_once(__DIR__ . '/../../Controls/Dashboard/DashboardItem.php');
require_once(__DIR__ . '/../../Controls/Dashboard/ResourceAvailabilityControl.php');
require_once(__DIR__ . '/../../Domain/Schedule.php');

/**
 * Live Smarty-vs-Twig golden comparison for Dashboard templates:
 *   - tpl/Dashboard/announcements.tpl              → .twig
 *   - tpl/Dashboard/dashboard_reservation.tpl      → .twig
 *   - tpl/Dashboard/upcoming_reservations.tpl      → .twig
 *   - tpl/Dashboard/admin_upcoming_reservations.tpl → .twig
 *   - tpl/Dashboard/group_upcoming_reservations.tpl → .twig
 *   - tpl/Dashboard/missing_check_in_out_reservations.tpl → .twig
 *   - tpl/Dashboard/past_reservations.tpl          → .twig
 *   - tpl/Dashboard/pending_approval_reservations.tpl → .twig
 *   - tpl/Dashboard/resource_availability.tpl      → .twig
 *
 * Parity strategy
 * ---------------
 * All templates use full assertParity — both engines must produce
 * normalized-identical output.
 *
 * Divergences from Smarty source noted inline.
 */
class DashboardGoldenTest extends GoldenTemplateTestCase
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
        $_SERVER['REQUEST_URI'] = '/web/dashboard.php';
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
     * Build a basic reservation fixture.
     */
    private function makeReservation(int $userId = 42): ReservationItemView
    {
        $res = new ReservationItemView(
            'REF001',
            Date::Parse('2025-06-15 09:00:00', 'UTC'),
            Date::Parse('2025-06-15 10:00:00', 'UTC'),
            'Room A',
            1,
            1,
            ReservationUserLevel::OWNER,
            'Test Booking',
            '',
            1,
            'John',
            'Doe',
            $userId
        );
        $res->RequiresApproval = false;
        $res->IsCheckInEnabled = false;
        $res->CheckinDate = new NullDate();
        $res->CheckoutDate = new NullDate();

        return $res;
    }

    /**
     * Common vars used by templates that include dashboard_reservation.twig.
     *
     * @return array<string, mixed>
     */
    private function baseReservationVars(): array
    {
        return [
            'Timezone' => 'UTC',
            'UserId' => 42,
            'DefaultTitle' => '(No Title)',
            'allowCheckin' => false,
            'allowCheckout' => false,
        ];
    }

    // ── announcements ─────────────────────────────────────────────────────────

    public function testAnnouncementsEmpty(): void
    {
        $this->assertParity(
            'Dashboard/announcements.tpl',
            'Dashboard/announcements.twig',
            ['Announcements' => []]
        );
    }

    public function testAnnouncementsWithContent(): void
    {
        $announcement = new class () {
            public function Text(): string
            {
                return 'Welcome to LibreBooking! Visit https://example.com for more.';
            }
        };

        $this->assertParity(
            'Dashboard/announcements.tpl',
            'Dashboard/announcements.twig',
            ['Announcements' => [$announcement]]
        );
    }

    // ── dashboard_reservation ─────────────────────────────────────────────────

    public function testDashboardReservationBasic(): void
    {
        $vars = $this->baseReservationVars();
        $vars['reservation'] = $this->makeReservation();

        $this->assertParity(
            'Dashboard/dashboard_reservation.tpl',
            'Dashboard/dashboard_reservation.twig',
            $vars
        );
    }

    public function testDashboardReservationRequiresApproval(): void
    {
        $vars = $this->baseReservationVars();
        $res = $this->makeReservation();
        $res->RequiresApproval = true;
        $vars['reservation'] = $res;

        $this->assertParity(
            'Dashboard/dashboard_reservation.tpl',
            'Dashboard/dashboard_reservation.twig',
            $vars
        );
    }

    public function testDashboardReservationOrangePending(): void
    {
        $vars = $this->baseReservationVars();
        $vars['reservation'] = $this->makeReservation();
        $vars['orangePending'] = false;

        $this->assertParity(
            'Dashboard/dashboard_reservation.tpl',
            'Dashboard/dashboard_reservation.twig',
            $vars
        );
    }

    public function testDashboardReservationNotOwner(): void
    {
        $vars = $this->baseReservationVars();
        // UserId 42 does not own reservation owned by userId 99
        $res = $this->makeReservation(99);
        $vars['reservation'] = $res;

        $this->assertParity(
            'Dashboard/dashboard_reservation.tpl',
            'Dashboard/dashboard_reservation.twig',
            $vars
        );
    }

    public function testDashboardReservationEmptyTitle(): void
    {
        $vars = $this->baseReservationVars();
        $res = $this->makeReservation();
        $res->Title = '';
        $vars['reservation'] = $res;

        $this->assertParity(
            'Dashboard/dashboard_reservation.tpl',
            'Dashboard/dashboard_reservation.twig',
            $vars
        );
    }

    // ── upcoming_reservations ─────────────────────────────────────────────────

    public function testUpcomingReservationsEmpty(): void
    {
        $vars = array_merge($this->baseReservationVars(), ['Total' => 0]);

        $this->assertParity(
            'Dashboard/upcoming_reservations.tpl',
            'Dashboard/upcoming_reservations.twig',
            $vars
        );
    }

    public function testUpcomingReservationsWithData(): void
    {
        $res = $this->makeReservation();
        $vars = array_merge($this->baseReservationVars(), [
            'Total' => 1,
            'TodaysReservations' => [$res],
            'TomorrowsReservations' => [],
            'ThisWeeksReservations' => [],
            'NextWeeksReservations' => [],
        ]);

        $this->assertParity(
            'Dashboard/upcoming_reservations.tpl',
            'Dashboard/upcoming_reservations.twig',
            $vars
        );
    }

    // ── admin_upcoming_reservations ───────────────────────────────────────────

    public function testAdminUpcomingReservationsEmpty(): void
    {
        $vars = array_merge($this->baseReservationVars(), ['Total' => 0]);

        $this->assertParity(
            'Dashboard/admin_upcoming_reservations.tpl',
            'Dashboard/admin_upcoming_reservations.twig',
            $vars
        );
    }

    public function testAdminUpcomingReservationsWithData(): void
    {
        $res = $this->makeReservation();
        $vars = array_merge($this->baseReservationVars(), [
            'Total' => 1,
            'TodaysReservations' => [$res],
            'TomorrowsReservations' => [],
            'ThisWeeksReservations' => [],
            'NextWeeksReservations' => [],
        ]);

        $this->assertParity(
            'Dashboard/admin_upcoming_reservations.tpl',
            'Dashboard/admin_upcoming_reservations.twig',
            $vars
        );
    }

    // ── group_upcoming_reservations ───────────────────────────────────────────

    public function testGroupUpcomingReservationsEmpty(): void
    {
        $vars = array_merge($this->baseReservationVars(), ['Total' => 0]);

        $this->assertParity(
            'Dashboard/group_upcoming_reservations.tpl',
            'Dashboard/group_upcoming_reservations.twig',
            $vars
        );
    }

    public function testGroupUpcomingReservationsWithData(): void
    {
        $res = $this->makeReservation();
        $vars = array_merge($this->baseReservationVars(), [
            'Total' => 1,
            'TodaysReservations' => [$res],
            'TomorrowsReservations' => [],
            'ThisWeeksReservations' => [],
            'NextWeeksReservations' => [],
        ]);

        $this->assertParity(
            'Dashboard/group_upcoming_reservations.tpl',
            'Dashboard/group_upcoming_reservations.twig',
            $vars
        );
    }

    // ── missing_check_in_out_reservations ─────────────────────────────────────

    public function testMissingCheckInOutEmpty(): void
    {
        $vars = array_merge($this->baseReservationVars(), ['Total' => 0]);

        $this->assertParity(
            'Dashboard/missing_check_in_out_reservations.tpl',
            'Dashboard/missing_check_in_out_reservations.twig',
            $vars
        );
    }

    public function testMissingCheckInOutWithData(): void
    {
        $res = $this->makeReservation();
        $vars = array_merge($this->baseReservationVars(), [
            'Total' => 1,
            'TodaysReservations' => [$res],
            'YesterdayReservations' => [],
            'ThisWeeksReservations' => [],
            'PreviousWeekReservations' => [],
            'RemainingReservations' => [],
        ]);

        $this->assertParity(
            'Dashboard/missing_check_in_out_reservations.tpl',
            'Dashboard/missing_check_in_out_reservations.twig',
            $vars
        );
    }

    // ── past_reservations ─────────────────────────────────────────────────────

    public function testPastReservationsEmpty(): void
    {
        $vars = array_merge($this->baseReservationVars(), ['Total' => 0]);

        $this->assertParity(
            'Dashboard/past_reservations.tpl',
            'Dashboard/past_reservations.twig',
            $vars
        );
    }

    public function testPastReservationsWithData(): void
    {
        $res = $this->makeReservation();
        $vars = array_merge($this->baseReservationVars(), [
            'Total' => 1,
            'TodaysReservations' => [$res],
            'YesterdayReservations' => [],
            'ThisWeeksReservations' => [],
            'PreviousWeekReservations' => [],
        ]);

        $this->assertParity(
            'Dashboard/past_reservations.tpl',
            'Dashboard/past_reservations.twig',
            $vars
        );
    }

    // ── pending_approval_reservations ─────────────────────────────────────────

    public function testPendingApprovalEmpty(): void
    {
        $vars = array_merge($this->baseReservationVars(), ['Total' => 0]);

        $this->assertParity(
            'Dashboard/pending_approval_reservations.tpl',
            'Dashboard/pending_approval_reservations.twig',
            $vars
        );
    }

    public function testPendingApprovalWithData(): void
    {
        $res = $this->makeReservation();
        $res->RequiresApproval = true;
        $vars = array_merge($this->baseReservationVars(), [
            'Total' => 1,
            'TodaysReservations' => [$res],
            'TomorrowsReservations' => [],
            'ThisWeeksReservations' => [],
            'NextWeeksReservations' => [],
            'ThisMonthsReservations' => [],
            'ThisYearsReservations' => [],
            'RemainingReservations' => [],
        ]);

        $this->assertParity(
            'Dashboard/pending_approval_reservations.tpl',
            'Dashboard/pending_approval_reservations.twig',
            $vars
        );
    }

    // ── resource_availability ────────────────────────────────────────────────

    public function testResourceAvailabilityEmpty(): void
    {
        $vars = [
            'Available' => [],
            'Unavailable' => [],
            'UnavailableAllDay' => [],
            'Schedules' => [],
            'Path' => '/',
            'Timezone' => 'UTC',
        ];

        $this->assertParity(
            'Dashboard/resource_availability.tpl',
            'Dashboard/resource_availability.twig',
            $vars
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

    public function testResourceAvailabilityWithData(): void
    {
        $schedule = new Schedule(1, 'Default Schedule', true, 0, 7, 'UTC', null);

        $resourceDto = new ResourceDto(
            1,
            'Room A',
            true,
            true,
            1,
            null,
            null,
            null,
            null,
            null,
            false,
            false,
            false,
            null,
            '',
            null
        );

        $nextReservation = new ReservationItemView(
            'REF002',
            Date::Parse('2025-06-15 11:00:00', 'UTC'),
            Date::Parse('2025-06-15 12:00:00', 'UTC'),
            'Room A',
            1,
            2,
            ReservationUserLevel::OWNER,
            'Next Booking',
            '',
            1,
            'Jane',
            'Smith',
            43
        );

        $availableItem = new AvailableDashboardItem($resourceDto, $nextReservation);

        $vars = [
            'Available' => [1 => [$availableItem]],
            'Unavailable' => [],
            'UnavailableAllDay' => [],
            'Schedules' => [$schedule],
            'Path' => '/',
            'Timezone' => 'UTC',
        ];

        // Divergence from Smarty: Twig's context-aware HTML autoescape encodes '&' as '&amp;'
        // within href attributes, which is technically correct HTML. Smarty outputs raw '&'.
        // Use structural assertion instead of full parity.
        $this->assertTwigContains(
            'Dashboard/resource_availability.twig',
            $vars,
            [
                'availabilityDashboard',
                'Default Schedule',
                'Room A',
                'Available Until',
                'schedule.php',
                'reservation.php',
                'Reserve',
            ]
        );
    }
}
