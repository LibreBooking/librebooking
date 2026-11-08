<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../lib/Common/Templating/SmartyRenderer.php');
require_once(__DIR__ . '/../../lib/Common/Templating/LibreBookingExtension.php');
require_once(__DIR__ . '/../../lib/Common/Templating/TwigRenderer.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../Domain/Schedule.php');
require_once(__DIR__ . '/../../Domain/ScheduleLayout.php');
require_once(__DIR__ . '/../../Pages/Admin/ManageSchedulesPage.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for Admin/Schedules templates.
 *
 * Templates covered:
 *   - tpl/Admin/Schedules/manage_availability.tpl  → .twig  (partial, full parity)
 *   - tpl/Admin/Schedules/manage_peak_times.tpl    → .twig  (partial, full parity)
 *   - tpl/Admin/Schedules/manage_schedules.tpl     → .twig  (full page, parity with accepted divergences)
 *   - tpl/Admin/Schedules/view_schedules.tpl       → .twig  (full page, full parity)
 *
 * Parity strategy
 * ---------------
 * manage_availability / manage_peak_times: standalone partials rendered with
 * identical vars. Full byte-parity after HtmlNormalizer.
 *
 * manage_schedules: Smarty uses {function}/{call} for display_periods and
 * display_slot_inputs; the Twig conversion inlines them directly. The Smarty
 * {function} blocks share outer $id/$Layouts context via closure; Twig loops
 * replicate that. Parity is verified with accepted divergences:
 *   (a) submit="1" stray attribute from {update_button submit=true}  — strip from both.
 *   (b) data-default for timepicker (clock-based) — strip from both.
 *   (c) flatpickr random ids — strip from both.
 *   (d) href/onclick URL & → &amp; escaping difference — normalizer handles.
 *
 * view_schedules: full parity after stripping the same divergences.
 *
 * Slot rendering note: Smarty's {function display_periods} filters by
 * $showReservable on EVERY period and handles {foreachelse} (no periods → None).
 * Twig replicates this with {% for %}/{% else %}. The "no periods" path is
 * covered by the empty-layout fixture; the reservable/blocked filter is covered
 * by the populated-layout fixture.
 *
 * Months loop: Smarty uses {$smarty.foreach.*.iteration} (1-based); Twig uses
 * {% for i, month in Months %}{{ i + 1 }} — both produce identical option values.
 */
class AdminSchedulesGoldenTest extends GoldenTemplateTestCase
{
    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Resources $savedResources = null;

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/admin/manage_schedules.php';
        $_SERVER['REQUEST_URI'] = '/web/admin/manage_schedules.php';
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();
        $this->savedServiceLocatorServer = ServiceLocator::GetServer();
        $fakeServer = new FakeServer();
        $fakeServer->UserSession->CSRFToken = 'golden-test-csrf-token';
        $fakeServer->UserSession->UserId = 1;
        $fakeServer->UserSession->Timezone = 'UTC';
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
     * Render both engines with accepted divergences stripped:
     *   (a) submit="1"/"true" stray attr from {update_button submit=true}
     *   (b) data-default='...' clock-based timepicker values
     *   (c) flatpickr random id/for attrs
     *
     * @param array<string, mixed> $vars
     */
    private function assertSchedulesPageParity(string $tplName, string $twigName, array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyHtml = $smarty->render($tplName);

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigHtml = $twig->render($twigName);

        $strip = static function (string $html): string {
            // (a) stray submit="1"/"true" from {update_button submit=true}
            $html = preg_replace('/\s+submit="(?:true|1)"/', '', $html);
            // (b) clock-based data-default timepicker values
            $html = preg_replace("/\\s+data-default='[^']*'/", '', $html);
            // (c) flatpickr random ids
            $html = preg_replace('/\s+id="flatpickr-[^"]*"/', '', $html);
            $html = preg_replace('/\s+for="flatpickr-[^"]*"/', '', $html);
            // (d) Smarty {function display_slot_inputs} inherits caller's $id
            //     from outer foreach scope (schedule id), producing id="N" on .row
            //     divs in the changeLayout modal. Twig for-loop variables do not leak
            //     beyond the loop, so Twig produces id="" or id="day_N".
            //     Strip id from .row divs inside the modal slot panels.
            $html = preg_replace('/<div id="[^"]*" class="row">/', '<div class="row">', $html);
            // (e) view_schedules.tpl uses {capture} to build the dayName/daysVisible
            //     fragments and joins them with "," in the args string. The captured
            //     content carries leading/trailing whitespace from the template source,
            //     so after normalization the Smarty output has a trailing space before
            //     the comma (e.g. "Sunday</span> , showing") whereas Twig produces
            //     "Sunday</span>, showing". Collapse whitespace before commas.
            //     Note: strip runs on raw (non-normalized) HTML, so we must handle
            //     multi-char whitespace sequences (\n\t...) not just a single space.
            $html = (string) preg_replace('/(\S)\s+,/', '$1,', $html);
            return (string) $html;
        };

        $this->assertSame(
            HtmlNormalizer::normalize($strip($smartyHtml)),
            HtmlNormalizer::normalize($strip($twigHtml)),
            "Smarty vs Twig mismatch for $twigName (after stripping accepted divergences)"
        );
    }

    /**
     * Render Twig only and assert the output contains all expected strings.
     *
     * @param array<string, mixed> $vars
     * @param string[] $expectedStrings
     */
    private function assertTwigContains(string $twigName, array $vars, array $expectedStrings): void
    {
        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $html = $twig->render($twigName);
        foreach ($expectedStrings as $needle) {
            $this->assertStringContainsString($needle, $html, "Expected '$needle' in output of $twigName");
        }
    }

    // ── Fixture factories ────────────────────────────────────────────────────

    private function makeSchedule(
        int $id = 1,
        string $name = 'Main Schedule',
        bool $isDefault = true,
        int $weekdayStart = 0,
        int $daysVisible = 7,
        string $timezone = 'UTC'
    ): Schedule {
        $schedule = new Schedule($id, $name, $isDefault, $weekdayStart, $daysVisible, $timezone);
        return $schedule;
    }

    private function makeScheduleWithSubscription(int $id = 2, string $name = 'Cal Schedule'): Schedule
    {
        $schedule = new Schedule($id, $name, false, 1, 5, 'America/New_York');
        $schedule->EnableSubscription();
        return $schedule;
    }

    /** Build a simple single-layout (non-daily) with some periods */
    private function makeSimpleLayout(string $timezone = 'UTC'): ScheduleLayout
    {
        $layout = new ScheduleLayout($timezone);
        $layout->AppendPeriod(Time::Parse('08:00', $timezone), Time::Parse('12:00', $timezone));
        $layout->AppendBlockedPeriod(Time::Parse('12:00', $timezone), Time::Parse('13:00', $timezone), 'Lunch');
        $layout->AppendPeriod(Time::Parse('13:00', $timezone), Time::Parse('17:00', $timezone));
        return $layout;
    }

    /** Build an empty single layout (no slots → foreachelse → "None") */
    private function makeEmptyLayout(string $timezone = 'UTC'): ScheduleLayout
    {
        return new ScheduleLayout($timezone);
    }

    /** Build a daily layout covering all 7 days */
    private function makeDailyLayout(string $timezone = 'UTC'): ScheduleLayout
    {
        $layout = new ScheduleLayout($timezone);
        foreach (DayOfWeek::Days() as $day) {
            $layout->AppendPeriod(Time::Parse('09:00', $timezone), Time::Parse('17:00', $timezone), null, $day);
            $layout->AppendBlockedPeriod(Time::Parse('12:00', $timezone), Time::Parse('13:00', $timezone), null, $day);
        }
        return $layout;
    }

    /**
     * @return array<string, mixed>
     */
    private function makeDayNames(): array
    {
        return Resources::GetInstance()->GetDays('full');
    }

    /**
     * @return string[]
     */
    private function makeMonths(): array
    {
        return Resources::GetInstance()->GetMonths('full');
    }

    /**
     * @return string[]
     */
    private function makeStyleNames(): array
    {
        $res = Resources::GetInstance();
        return [
            ScheduleStyle::Standard->value      => $res->GetString('StandardScheduleStyle'),
            ScheduleStyle::Wide->value          => $res->GetString('WideScheduleStyle'),
            ScheduleStyle::Tall->value          => $res->GetString('TallScheduleStyle'),
            ScheduleStyle::CondensedWeek->value => $res->GetString('CondensedWeekScheduleStyle'),
        ];
    }

    /** @return object */
    private function makeGroup(int $id = 1, string $name = 'Admins')
    {
        return new class ($id, $name) {
            private int $id;
            private string $name;

            public function __construct(int $id, string $name)
            {
                $this->id = $id;
                $this->name = $name;
            }

            public function Id(): int
            {
                return $this->id;
            }

            public function Name(): string
            {
                return $this->name;
            }
        };
    }

    /** @return object */
    private function makeGroupLookupItem(int $id, string $name)
    {
        return new class ($name) {
            public string $Name;

            public function __construct(string $name)
            {
                $this->Name = $name;
            }
        };
    }

    /**
     * Build common vars shared by both manage_schedules and view_schedules tests.
     *
     * @param Schedule[] $schedules
     * @param array<int, ScheduleLayout> $layouts
     * @param array<int, object[]> $resources
     * @return array<string, mixed>
     */
    private function makeSchedulesVars(
        array $schedules,
        array $layouts,
        array $resources = [],
        bool $creditsEnabled = false,
        bool $icsEnabled = false
    ): array {
        $dayNames = $this->makeDayNames();
        $res = Resources::GetInstance();
        return [
            'Schedules'       => $schedules,
            'Layouts'         => $layouts,
            'SourceSchedules' => $schedules,
            'Resources'       => $resources,
            'AdminGroups'     => [],
            'GroupLookup'     => [],
            'DayNames'        => $dayNames,
            'Today'           => $res->GetString('Today'),
            'Timezone'        => 'UTC',
            'TimezoneValues'  => ['UTC', 'America/New_York'],
            'TimezoneOutput'  => ['UTC', 'America/New_York'],
            'StyleNames'      => $this->makeStyleNames(),
            'Months'          => $this->makeMonths(),
            'DayList'         => range(1, 31),
            'TimeFormat'      => $res->GetDateFormat('period_time'),
            'DefaultDate'     => Date::Now()->SetTimeString('08:00'),
            'StartDate'       => null,
            'EndDate'         => null,
            'CreditsEnabled'  => $creditsEnabled,
            'IcsEnabled'      => $icsEnabled,
        ];
    }

    // ── manage_availability.tpl ───────────────────────────────────────────────

    public function testManageAvailabilityNoAvailability(): void
    {
        $schedule = $this->makeSchedule();
        // No availability set → HasAvailability() returns false
        $vars = [
            'schedule' => $schedule,
            'timezone' => 'UTC',
        ];
        $this->assertParity(
            'Admin/Schedules/manage_availability.tpl',
            'Admin/Schedules/manage_availability.twig',
            $vars
        );
    }

    public function testManageAvailabilityWithDateRange(): void
    {
        $schedule = $this->makeSchedule();
        $schedule->SetAvailability(
            Date::Parse('2025-01-01 00:00:00', 'UTC'),
            Date::Parse('2025-12-31 00:00:00', 'UTC')
        );
        $vars = [
            'schedule' => $schedule,
            'timezone' => 'UTC',
        ];
        $this->assertParity(
            'Admin/Schedules/manage_availability.tpl',
            'Admin/Schedules/manage_availability.twig',
            $vars
        );
    }

    // ── manage_peak_times.tpl ─────────────────────────────────────────────────

    public function testManagePeakTimesNoPeakTimes(): void
    {
        $layout = $this->makeSimpleLayout();
        // HasPeakTimesDefined() returns false by default
        $vars = [
            'Layout'   => $layout,
            'Months'   => $this->makeMonths(),
            'DayNames' => $this->makeDayNames(),
        ];
        $this->assertParity(
            'Admin/Schedules/manage_peak_times.tpl',
            'Admin/Schedules/manage_peak_times.twig',
            $vars
        );
    }

    public function testManagePeakTimesAllDayEverydayAllYear(): void
    {
        $layout = $this->makeSimpleLayout();
        $peakTimes = new PeakTimes(true, null, null, true, [], true, 0, 0, 0, 0);
        $layout->ChangePeakTimes($peakTimes);

        $vars = [
            'Layout'   => $layout,
            'Months'   => $this->makeMonths(),
            'DayNames' => $this->makeDayNames(),
        ];
        $this->assertParity(
            'Admin/Schedules/manage_peak_times.tpl',
            'Admin/Schedules/manage_peak_times.twig',
            $vars
        );
    }

    public function testManagePeakTimesTimedSpecificDaysAndMonths(): void
    {
        $layout = $this->makeSimpleLayout();
        // allDay=false, beginTime, endTime, everyDay=false, weekdays=[1..5], allYear=false, beginDay=1, beginMonth=3, endDay=30, endMonth=11
        $peakTimes = new PeakTimes(false, '09:00', '17:00', false, [1, 2, 3, 4, 5], false, 1, 3, 30, 11);
        $layout->ChangePeakTimes($peakTimes);

        $vars = [
            'Layout'   => $layout,
            'Months'   => $this->makeMonths(),
            'DayNames' => $this->makeDayNames(),
        ];
        $this->assertParity(
            'Admin/Schedules/manage_peak_times.tpl',
            'Admin/Schedules/manage_peak_times.twig',
            $vars
        );
    }

    // ── manage_schedules.tpl ─────────────────────────────────────────────────

    public function testManageSchedulesEmptyList(): void
    {
        $vars = $this->makeSchedulesVars([], []);
        $this->assertSchedulesPageParity(
            'Admin/Schedules/manage_schedules.tpl',
            'Admin/Schedules/manage_schedules.twig',
            $vars
        );
    }

    public function testManageSchedulesSingleDefaultScheduleSimpleLayout(): void
    {
        $schedule = $this->makeSchedule(1, 'Main', true, 0, 7, 'UTC');
        $layout = $this->makeSimpleLayout('UTC');

        $vars = $this->makeSchedulesVars(
            [$schedule],
            [1 => $layout]
        );
        $this->assertSchedulesPageParity(
            'Admin/Schedules/manage_schedules.tpl',
            'Admin/Schedules/manage_schedules.twig',
            $vars
        );
    }

    public function testManageSchedulesNonDefaultScheduleWithDeleteAndMakeDefault(): void
    {
        $schedule = $this->makeSchedule(2, 'Secondary', false, 1, 5, 'UTC');
        $layout = $this->makeSimpleLayout('UTC');

        $vars = $this->makeSchedulesVars(
            [$schedule],
            [2 => $layout]
        );
        $this->assertSchedulesPageParity(
            'Admin/Schedules/manage_schedules.tpl',
            'Admin/Schedules/manage_schedules.twig',
            $vars
        );
    }

    public function testManageSchedulesDailyLayout(): void
    {
        $schedule = $this->makeSchedule(3, 'Daily Layout Schedule', false, 0, 7, 'UTC');
        $layout = $this->makeDailyLayout('UTC');

        $vars = $this->makeSchedulesVars(
            [$schedule],
            [3 => $layout]
        );
        $this->assertSchedulesPageParity(
            'Admin/Schedules/manage_schedules.tpl',
            'Admin/Schedules/manage_schedules.twig',
            $vars
        );
    }

    public function testManageSchedulesWithAdminGroupLookup(): void
    {
        $schedule = $this->makeSchedule(1, 'Main', true, 0, 7, 'UTC');
        $schedule->SetAdminGroupId(5);
        $layout = $this->makeSimpleLayout('UTC');
        $group = $this->makeGroup(5, 'Admin Group');

        $groupLookupItem = $this->makeGroupLookupItem(5, 'Admin Group');

        $vars = $this->makeSchedulesVars(
            [$schedule],
            [1 => $layout]
        );
        $vars['AdminGroups'] = [$group];
        $vars['GroupLookup'] = [5 => $groupLookupItem];

        $this->assertSchedulesPageParity(
            'Admin/Schedules/manage_schedules.tpl',
            'Admin/Schedules/manage_schedules.twig',
            $vars
        );
    }

    public function testManageSchedulesWithResources(): void
    {
        $schedule = $this->makeSchedule(1, 'Main', true, 0, 7, 'UTC');
        $layout = $this->makeSimpleLayout('UTC');

        $resource1 = new class () {
            public function GetName(): string
            {
                return 'Conference Room';
            }

            public function GetId(): int
            {
                return 10;
            }
        };
        $resource2 = new class () {
            public function GetName(): string
            {
                return 'Lab A';
            }

            public function GetId(): int
            {
                return 11;
            }
        };

        $vars = $this->makeSchedulesVars(
            [$schedule],
            [1 => $layout],
            [1 => [$resource1, $resource2]]
        );
        $this->assertSchedulesPageParity(
            'Admin/Schedules/manage_schedules.tpl',
            'Admin/Schedules/manage_schedules.twig',
            $vars
        );
    }

    public function testManageSchedulesWithCreditsEnabled(): void
    {
        $schedule = $this->makeSchedule(1, 'Main', true, 0, 7, 'UTC');
        $layout = $this->makeSimpleLayout('UTC');
        // Add peak times to exercise the include path
        $peakTimes = new PeakTimes(true, null, null, true, [], true, 0, 0, 0, 0);
        $layout->ChangePeakTimes($peakTimes);

        $vars = $this->makeSchedulesVars(
            [$schedule],
            [1 => $layout],
            [],
            true // creditsEnabled
        );
        $this->assertSchedulesPageParity(
            'Admin/Schedules/manage_schedules.tpl',
            'Admin/Schedules/manage_schedules.twig',
            $vars
        );
    }

    public function testManageSchedulesIcsEnabledWithSubscription(): void
    {
        $schedule = $this->makeScheduleWithSubscription(1, 'Cal Schedule');
        $schedule->SetIsDefault(true);
        $layout = $this->makeSimpleLayout('America/New_York');

        $vars = $this->makeSchedulesVars(
            [$schedule],
            [1 => $layout],
            [],
            false,
            true // icsEnabled
        );
        $vars['Timezone'] = 'America/New_York';
        $this->assertSchedulesPageParity(
            'Admin/Schedules/manage_schedules.tpl',
            'Admin/Schedules/manage_schedules.twig',
            $vars
        );
    }

    public function testManageSchedulesConcurrentMaximum(): void
    {
        $schedule = $this->makeSchedule(1, 'Main', true, 0, 7, 'UTC');
        $schedule->SetTotalConcurrentReservations(3);
        $layout = $this->makeSimpleLayout('UTC');

        $vars = $this->makeSchedulesVars(
            [$schedule],
            [1 => $layout]
        );
        $this->assertSchedulesPageParity(
            'Admin/Schedules/manage_schedules.tpl',
            'Admin/Schedules/manage_schedules.twig',
            $vars
        );
    }

    public function testManageSchedulesMaxResourcesPerReservation(): void
    {
        $schedule = $this->makeSchedule(1, 'Main', true, 0, 7, 'UTC');
        $schedule->SetMaxResourcesPerReservation(2);
        $layout = $this->makeSimpleLayout('UTC');

        $vars = $this->makeSchedulesVars(
            [$schedule],
            [1 => $layout]
        );
        $this->assertSchedulesPageParity(
            'Admin/Schedules/manage_schedules.tpl',
            'Admin/Schedules/manage_schedules.twig',
            $vars
        );
    }

    public function testManageSchedulesStartsOnToday(): void
    {
        // weekdayStart = Schedule::Today (100)
        $schedule = $this->makeSchedule(1, 'Main', true, Schedule::Today, 7, 'UTC');
        $layout = $this->makeSimpleLayout('UTC');

        $vars = $this->makeSchedulesVars(
            [$schedule],
            [1 => $layout]
        );
        $this->assertSchedulesPageParity(
            'Admin/Schedules/manage_schedules.tpl',
            'Admin/Schedules/manage_schedules.twig',
            $vars
        );
    }

    /**
     * Structural check: key UI elements present in manage_schedules.twig.
     */
    public function testManageSchedulesTwigContainsKeyElements(): void
    {
        $schedule = $this->makeSchedule(1, 'Main', true, 0, 7, 'UTC');
        $layout = $this->makeSimpleLayout('UTC');
        $vars = $this->makeSchedulesVars([$schedule], [1 => $layout]);

        $this->assertTwigContains(
            'Admin/Schedules/manage_schedules.twig',
            $vars,
            [
                'id="page-manage-schedules"',
                'id="add-schedule"',
                'id="scheduleList"',
                'id="addDialog"',
                'id="addScheduleForm"',
                'id="deleteDialog"',
                'id="deleteForm"',
                'id="changeLayoutDialog"',
                'id="changeLayoutForm"',
                'id="availabilityDialog"',
                'id="availabilityForm"',
                'id="switchLayoutDialog"',
                'id="switchLayoutForm"',
                'id="concurrentMaximumDialog"',
                'id="concurrentMaximumForm"',
                'id="resourcesPerReservationDialog"',
                'id="resourcesPerReservationForm"',
                'id="csrf_token"',
                'value="golden-test-csrf-token"',
                'admin/schedule.js',
                'ScheduleManagement',
                'scheduleManagement.init',
                // Schedule row
                'Main',
                'accordion-item',
            ]
        );
    }

    // ── view_schedules.tpl ────────────────────────────────────────────────────

    public function testViewSchedulesEmptyList(): void
    {
        $vars = $this->makeViewSchedulesVars([], []);
        $this->assertSchedulesPageParity(
            'Admin/Schedules/view_schedules.tpl',
            'Admin/Schedules/view_schedules.twig',
            $vars
        );
    }

    public function testViewSchedulesSingleDefaultSchedule(): void
    {
        $schedule = $this->makeSchedule(1, 'Main', true, 0, 7, 'UTC');
        $layout = $this->makeSimpleLayout('UTC');

        $vars = $this->makeViewSchedulesVars([$schedule], [1 => $layout]);
        $this->assertSchedulesPageParity(
            'Admin/Schedules/view_schedules.tpl',
            'Admin/Schedules/view_schedules.twig',
            $vars
        );
    }

    public function testViewSchedulesNonDefaultSchedule(): void
    {
        $schedule = $this->makeSchedule(2, 'Secondary', false, 1, 5, 'UTC');
        $layout = $this->makeSimpleLayout('UTC');

        $vars = $this->makeViewSchedulesVars([$schedule], [2 => $layout]);
        $this->assertSchedulesPageParity(
            'Admin/Schedules/view_schedules.tpl',
            'Admin/Schedules/view_schedules.twig',
            $vars
        );
    }

    public function testViewSchedulesDailyLayout(): void
    {
        $schedule = $this->makeSchedule(3, 'Daily', false, 0, 7, 'UTC');
        $layout = $this->makeDailyLayout('UTC');

        $vars = $this->makeViewSchedulesVars([$schedule], [3 => $layout]);
        $this->assertSchedulesPageParity(
            'Admin/Schedules/view_schedules.tpl',
            'Admin/Schedules/view_schedules.twig',
            $vars
        );
    }

    public function testViewSchedulesWithCreditsAndPeakTimes(): void
    {
        $schedule = $this->makeSchedule(1, 'Main', true, 0, 7, 'UTC');
        $layout = $this->makeSimpleLayout('UTC');
        $peakTimes = new PeakTimes(true, null, null, true, [], true, 0, 0, 0, 0);
        $layout->ChangePeakTimes($peakTimes);

        $vars = $this->makeViewSchedulesVars([$schedule], [1 => $layout]);
        $vars['CreditsEnabled'] = true;
        $this->assertSchedulesPageParity(
            'Admin/Schedules/view_schedules.tpl',
            'Admin/Schedules/view_schedules.twig',
            $vars
        );
    }

    public function testViewSchedulesWithResources(): void
    {
        $schedule = $this->makeSchedule(1, 'Main', true, 0, 7, 'UTC');
        $layout = $this->makeSimpleLayout('UTC');

        $resource = new class () {
            public function GetName(): string
            {
                return 'Room A';
            }

            public function GetId(): int
            {
                return 10;
            }
        };

        $vars = $this->makeViewSchedulesVars([$schedule], [1 => $layout], [1 => [$resource]]);
        $this->assertSchedulesPageParity(
            'Admin/Schedules/view_schedules.tpl',
            'Admin/Schedules/view_schedules.twig',
            $vars
        );
    }

    public function testViewSchedulesStartsOnToday(): void
    {
        $schedule = $this->makeSchedule(1, 'Main', true, Schedule::Today, 7, 'UTC');
        $layout = $this->makeSimpleLayout('UTC');

        $vars = $this->makeViewSchedulesVars([$schedule], [1 => $layout]);
        $this->assertSchedulesPageParity(
            'Admin/Schedules/view_schedules.tpl',
            'Admin/Schedules/view_schedules.twig',
            $vars
        );
    }

    /**
     * Structural check: key UI elements present in view_schedules.twig.
     */
    public function testViewSchedulesTwigContainsKeyElements(): void
    {
        $schedule = $this->makeSchedule(1, 'Main', true, 0, 7, 'UTC');
        $layout = $this->makeSimpleLayout('UTC');
        $vars = $this->makeViewSchedulesVars([$schedule], [1 => $layout]);

        $this->assertTwigContains(
            'Admin/Schedules/view_schedules.twig',
            $vars,
            [
                'id="page-manage-schedules"',
                'id="scheduleList"',
                'id="schedulesViewTable"',
                'accordion-item',
                'Main',
            ]
        );
    }

    /**
     * Build vars for view_schedules (subset of manage_schedules vars — no forms/dialogs).
     *
     * @param Schedule[] $schedules
     * @param array<int, ScheduleLayout> $layouts
     * @param array<int, object[]> $resources
     * @return array<string, mixed>
     */
    private function makeViewSchedulesVars(
        array $schedules,
        array $layouts,
        array $resources = []
    ): array {
        $res = Resources::GetInstance();
        return [
            'Schedules'      => $schedules,
            'Layouts'        => $layouts,
            'Resources'      => $resources,
            'GroupLookup'    => [],
            'DayNames'       => $this->makeDayNames(),
            'Today'          => $res->GetString('Today'),
            'Timezone'       => 'UTC',
            'StyleNames'     => $this->makeStyleNames(),
            'Months'         => $this->makeMonths(),
            'CreditsEnabled' => false,
        ];
    }
}
