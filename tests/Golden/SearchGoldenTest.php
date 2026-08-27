<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');
require_once(__DIR__ . '/../../Domain/ReservationItemView.php');
require_once(__DIR__ . '/../../Domain/Access/ResourceRepository.php');
require_once(__DIR__ . '/../../Domain/Values/ResourceStatus.php');
require_once(__DIR__ . '/../../Presenters/SearchAvailabilityPresenter.php');
require_once(__DIR__ . '/../../Domain/Schedule.php');

/**
 * Live Smarty-vs-Twig golden comparison for Search and SearchAvailability templates:
 *   - tpl/Search/search-reservations.tpl               → tpl/Search/search-reservations.twig
 *   - tpl/Search/search-reservations-results.tpl        → tpl/Search/search-reservations-results.twig
 *   - tpl/SearchAvailability/search-availability.tpl    → tpl/SearchAvailability/search-availability.twig
 *   - tpl/SearchAvailability/search-availability-results.tpl
 *       → tpl/SearchAvailability/search-availability-results.twig
 *
 * Both engines are rendered in the same process with identical template variables
 * and superglobal state; normalized outputs are asserted byte-identical.
 *
 * CSRF token nondeterminism: templates using csrf_token() both call
 * ServiceLocator::GetServer()->GetUserSession()->CSRFToken, so wiring a FakeServer
 * with a stable token produces identical output from both engines.
 *
 * Date nondeterminism: Today/Tomorrow are fixed Date objects passed as fixture vars
 * so both engines produce identical output regardless of when the test runs.
 */
class SearchGoldenTest extends GoldenTemplateTestCase
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
     * Like assertParity but strips timepicker data-default attributes before comparison.
     *
     * The search-availability template uses {Date::Now()->format('H:00')} in Smarty,
     * which always returns the live current time. In Twig we use Today.Format('H:00')
     * with a fixed fixture. Stripping data-default="..." from both outputs before
     * comparing keeps the structural assertion meaningful without relying on clock time.
     *
     * @param array<string, mixed> $vars
     */
    private function assertAvailabilityParity(string $tplName, string $twigName, array $vars): void
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

        // Strip nondeterministic data-default="..." on timepicker selects;
        // Smarty calls Date::Now() live while Twig uses the fixture Today.
        $stripDataDefault = static function (string $html): string {
            return preg_replace('/\s+data-default="[^"]*"/', '', $html) ?? $html;
        };

        $this->assertSame(
            HtmlNormalizer::normalize($stripDataDefault($expected)),
            HtmlNormalizer::normalize($stripDataDefault($actual)),
            "Smarty vs Twig mismatch for $twigName"
        );
    }

    // ── search-reservations ──────────────────────────────────────────────────

    /**
     * Empty Resources and Schedules: foreach loops emit nothing.
     */
    public function testSearchReservationsEmptyMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/search-reservations.php';
        $this->assertParity(
            'Search/search-reservations.tpl',
            'Search/search-reservations.twig',
            $vars
        );
    }

    /**
     * Resources and Schedules populated: select options are rendered.
     */
    public function testSearchReservationsWithResourcesAndSchedulesMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/search-reservations.php';

        $resource1 = new ResourceDto(
            1,
            'Room A',
            true,
            true,
            1,
            TimeInterval::None(),
            null,
            null,
            null,
            ResourceStatus::AVAILABLE,
            false,
            false,
            false,
            null,
            null,
            null
        );
        $resource2 = new ResourceDto(
            2,
            'Lab B',
            true,
            true,
            1,
            TimeInterval::None(),
            null,
            null,
            null,
            ResourceStatus::AVAILABLE,
            false,
            false,
            false,
            null,
            null,
            null
        );

        $schedule1 = new Schedule(1, 'Main Schedule', true, 0, 7);

        $vars['Resources'] = [$resource1, $resource2];
        $vars['Schedules'] = [$schedule1];

        $this->assertParity(
            'Search/search-reservations.tpl',
            'Search/search-reservations.twig',
            $vars
        );
    }

    // ── search-reservations-results ──────────────────────────────────────────

    /**
     * Basic reservation with RequiresApproval=false: no pending CSS class.
     */
    public function testSearchReservationsResultsMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/search-reservations-results.php';
        $this->assertParity(
            'Search/search-reservations-results.tpl',
            'Search/search-reservations-results.twig',
            $vars
        );
    }

    /**
     * Reservation with RequiresApproval=true: adds pending table-warning CSS class.
     */
    public function testSearchReservationsResultsRequiresApprovalMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/search-reservations-results.php';
        $vars['Reservations'][0]->RequiresApproval = true;
        $this->assertParity(
            'Search/search-reservations-results.tpl',
            'Search/search-reservations-results.twig',
            $vars
        );
    }

    // ── search-availability ──────────────────────────────────────────────────

    /**
     * No resources or attributes: loops emit nothing.
     */
    public function testSearchAvailabilityEmptyMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/search-availability.php';
        $vars['Resources'] = [];
        $this->assertAvailabilityParity(
            'SearchAvailability/search-availability.tpl',
            'SearchAvailability/search-availability.twig',
            $vars
        );
    }

    /**
     * Resources populated: resource select options are rendered.
     */
    public function testSearchAvailabilityWithResourcesMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/search-availability.php';
        $this->assertAvailabilityParity(
            'SearchAvailability/search-availability.tpl',
            'SearchAvailability/search-availability.twig',
            $vars
        );
    }

    // ── search-availability-results ──────────────────────────────────────────

    /**
     * Empty Openings: shows the "no available matching times" alert.
     */
    public function testSearchAvailabilityResultsEmptyMatchesSmarty(): void
    {
        $this->assertParity(
            'SearchAvailability/search-availability-results.tpl',
            'SearchAvailability/search-availability-results.twig',
            ['Openings' => []]
        );
    }

    /**
     * Openings with SameDate=true and SameDate=false, one with color, one without.
     */
    public function testSearchAvailabilityResultsWithOpeningsMatchesSmarty(): void
    {
        $vars = require __DIR__ . '/fixtures/search-availability-results.php';
        $this->assertParity(
            'SearchAvailability/search-availability-results.tpl',
            'SearchAvailability/search-availability-results.twig',
            $vars
        );
    }
}
