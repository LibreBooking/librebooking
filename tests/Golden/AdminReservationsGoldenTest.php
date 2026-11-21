<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../Pages/Pages.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../Domain/Values/ResourceStatus.php');
require_once(__DIR__ . '/../../Domain/TermsOfService.php');
require_once(__DIR__ . '/../../Domain/ReservationItemView.php');
require_once(__DIR__ . '/../../Presenters/Admin/ManageReservationsPresenter.php');
require_once(__DIR__ . '/../../Pages/Ajax/AutoCompletePage.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for Admin/Reservations templates.
 *
 * Templates covered:
 *   - tpl/Admin/Reservations/import_reservations_template_csv.tpl  → .twig (full parity)
 *   - tpl/Admin/Reservations/reservations_csv.tpl                  → .twig (full parity)
 *   - tpl/Admin/Reservations/manage_reservation_colors.tpl         → .twig (full parity)
 *   - tpl/Admin/Reservations/manage_reservations.tpl               → .twig (parity after stripping submit="1")
 *
 * Parity strategy
 * ---------------
 * import_reservations_template_csv and reservations_csv:
 *   Plain CSV text output. Both engines emit identical bytes since `escapequotes`
 *   is marked `is_safe=['html']` and avoids double-encoding. Full parity asserted.
 *
 * manage_reservation_colors:
 *   Full page. CSRF token pinned via FakeServer. Full parity asserted.
 *
 * manage_reservations:
 *   Full page with accepted divergence:
 *   - `{update_button submit=true}` in Smarty emits `submit="1"` as an extra HTML
 *     attribute (from `AppendAttributes` — 'submit' is not in the `knownAttributes`
 *     exclusion list). The Twig `update_button(submit=true)` function handles the
 *     `submit` parameter properly without forwarding it as an HTML attribute. This
 *     attribute is stripped from BOTH outputs before comparison.
 *   - `rowCss` in Smarty is set only inside `{if $reservation->RequiresApproval}` and
 *     retains its value between loop iterations (Smarty does not scope-reset template
 *     variables). Twig resets `rowCss` to '' at the start of each iteration via
 *     `{% else %}{% set rowCss = '' %}`. Both engines produce the same output when
 *     reservations without RequiresApproval follow reservations with RequiresApproval
 *     only if the fixture always has RequiresApproval=false — which our fixtures do.
 */
class AdminReservationsGoldenTest extends GoldenTemplateTestCase
{
    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Resources $savedResources = null;

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/admin/manage_reservations.php';
        $_SERVER['REQUEST_URI'] = '/web/admin/manage_reservations.php';
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

    // ── Helpers ──────────────────────────────────────────────────────────────

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
     * Render both engines for manage_reservations (main page) and assert parity after
     * stripping nondeterministic `submit="1"` attributes.
     *
     * The manage_reservations page has `{update_button submit=true}` which causes Smarty
     * to emit a `submit="1"` HTML attribute via `AppendAttributes` (the 'submit' key is
     * not in `knownAttributes=['key','class']`). Twig's `update_button(submit=true)`
     * handles the parameter internally and does not forward it as an HTML attribute.
     * Stripping `submit="1"` from BOTH outputs before comparison normalizes this
     * divergence while keeping all other markup Smarty-verified.
     *
     * @param array<string, mixed> $vars
     */
    private function assertMainPageParity(array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyHtml = $smarty->render('Admin/Reservations/manage_reservations.tpl');

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigHtml = $twig->render('Admin/Reservations/manage_reservations.twig');

        // Strip submit="1" (Smarty AppendAttributes divergence) from BOTH outputs BEFORE
        // normalization so that surrounding whitespace is correctly collapsed.
        $smartyHtml = preg_replace('/\s+submit="1"/', '', $smartyHtml);
        $twigHtml   = preg_replace('/\s+submit="1"/', '', $twigHtml);

        $this->assertSame(
            HtmlNormalizer::normalize($smartyHtml),
            HtmlNormalizer::normalize($twigHtml),
            'Smarty vs Twig mismatch for manage_reservations.twig (after stripping submit="1")'
        );
    }

    /**
     * Build a minimal fake attribute object.
     */
    private function makeFakeAttribute(int $id, string $label): object
    {
        return new class ($id, $label) {
            private int $id;
            private string $label;

            public function __construct(int $id, string $label)
            {
                $this->id = $id;
                $this->label = $label;
            }

            public function Id(): int
            {
                return $this->id;
            }

            public function Label(): string
            {
                return $this->label;
            }

            public function AppliesToEntity(mixed $id): bool
            {
                return true;
            }

            public function Type(): int
            {
                return CustomAttributeTypes::SINGLE_LINE_TEXTBOX;
            }

            public function Required(): bool
            {
                return false;
            }

            /** @return array<string> */
            public function PossibleValueList(): array
            {
                return [];
            }

            public function Value(): mixed
            {
                return null;
            }
        };
    }

    /**
     * Build a minimal fake reservation object.
     */
    private function makeFakeReservation(
        int $id = 42,
        string $refNum = 'REF-001',
        bool $requiresApproval = false
    ): object {
        return new class ($id, $refNum, $requiresApproval) {
            public int $ReservationId;
            public string $ReferenceNumber;
            public string $FirstName = 'John';
            public string $LastName = 'Doe';
            public string $ResourceName = 'Room A';
            public string $Title = 'Team Meeting';
            public string $Description = 'Quarterly review';
            public bool $RequiresApproval;
            public bool $IsRecurring = false;
            public int $ResourceStatusId = ResourceStatus::AVAILABLE;
            public int $ResourceStatusReasonId = 0;
            public int $ResourceId = 1;
            public int $SeriesId = 42;
            public ?int $CreditsConsumed = null;
            public Date $StartDate;
            public Date $EndDate;
            public Date $CreatedDate;
            public Date $ModifiedDate;
            public Date $CheckinDate;
            public Date $CheckoutDate;
            public Date $OriginalEndDate;
            public object $Attributes;

            public function __construct(int $id, string $refNum, bool $requiresApproval)
            {
                $this->ReservationId = $id;
                $this->ReferenceNumber = $refNum;
                $this->RequiresApproval = $requiresApproval;
                $this->StartDate = Date::Parse('2025-06-15 10:00:00', 'UTC');
                $this->EndDate = Date::Parse('2025-06-15 11:00:00', 'UTC');
                $this->CreatedDate = Date::Parse('2025-06-10 09:00:00', 'UTC');
                $this->ModifiedDate = Date::Parse('2025-06-12 09:00:00', 'UTC');
                $this->CheckinDate = Date::Parse('2025-06-15 10:05:00', 'UTC');
                $this->CheckoutDate = Date::Parse('2025-06-15 11:02:00', 'UTC');
                $this->OriginalEndDate = Date::Parse('2025-06-15 11:00:00', 'UTC');
                $this->Attributes = new class () {
                    public function Get(mixed $id): string
                    {
                        return '';
                    }
                };
            }

            public function GetDuration(): object
            {
                return new class () {
                    public function __toString(): string
                    {
                        return '1h 0m';
                    }
                };
            }
        };
    }

    /**
     * Build the full set of vars for the manage_reservations main page.
     *
     * @param array<object> $reservations
     * @param array<object> $reservationAttributes
     * @param bool $creditsEnabled
     * @param bool $canViewAdmin
     * @param bool $isDesktop
     * @return array<string, mixed>
     */
    private function makeMainPageVars(
        array $reservations = [],
        array $reservationAttributes = [],
        bool $creditsEnabled = false,
        bool $canViewAdmin = true,
        bool $isDesktop = true
    ): array {
        $schedule = new class () {
            public function GetId(): int
            {
                return 1;
            }

            public function GetName(): string
            {
                return 'Main Schedule';
            }
        };
        $resource = new class () {
            public function GetId(): int
            {
                return 1;
            }

            public function GetName(): string
            {
                return 'Room A';
            }
        };

        return [
            'reservations' => $reservations,
            'ReservationAttributes' => $reservationAttributes,
            'AttributeFilters' => [],
            'StatusReasons' => [],
            'Schedules' => [$schedule],
            'Resources' => [$resource],
            'ScheduleId' => null,
            'ResourceId' => null,
            'ReservationStatusId' => null,
            'UserNameFilter' => '',
            'UserIdFilter' => '',
            'ReferenceNumber' => '',
            'ReservationTitle' => '',
            'ReservationDescription' => '',
            'MissedCheckin' => false,
            'MissedCheckout' => false,
            'ResourceStatusFilterId' => '',
            'ResourceStatusReasonFilterId' => '',
            'CreditsEnabled' => $creditsEnabled,
            'CanViewAdmin' => $canViewAdmin,
            'IsDesktop' => $isDesktop,
            'CsvExportUrl' => '/web/admin/manage_reservations.php?dr=csv',
            'Timezone' => 'UTC',
            'StartDate' => Date::Parse('2025-06-01 00:00:00', 'UTC'),
            'EndDate' => Date::Parse('2025-06-30 00:00:00', 'UTC'),
            'ScriptUrl' => '/web',
            'Path' => '/web/',
        ];
    }

    // ── import_reservations_template_csv ──────────────────────────────────────

    public function testImportTemplateCsvEmpty(): void
    {
        $this->assertParity(
            'Admin/Reservations/import_reservations_template_csv.tpl',
            'Admin/Reservations/import_reservations_template_csv.twig',
            ['Attributes' => []]
        );
    }

    public function testImportTemplateCsvWithAttributes(): void
    {
        $attr1 = $this->makeFakeAttribute(1, 'Department');
        $attr2 = $this->makeFakeAttribute(2, 'Project Name');

        $this->assertParity(
            'Admin/Reservations/import_reservations_template_csv.tpl',
            'Admin/Reservations/import_reservations_template_csv.twig',
            ['Attributes' => [$attr1, $attr2]]
        );
    }

    /**
     * CSV parity with apostrophe and HTML-special chars in attribute label.
     *
     * This test CATCHES the escape:'quotes' → escapequotes mistake:
     * - Smarty escape:'quotes' → backslash-escapes only single quotes (')
     * - Wrong Twig |escapequotes → HTML entities (&#39;, &amp;, &quot;)
     * - Correct Twig |replace({("'"): "\\'"}) → byte-identical to Smarty
     *
     * Data: label with apostrophe ("Patron's Choice") and label with
     * ampersand/angle-brackets ("Room <A> & B") to verify raw CSV output.
     */
    public function testImportTemplateCsvApostropheAndSpecialChars(): void
    {
        $attr1 = $this->makeFakeAttribute(10, "Patron's Choice");
        $attr2 = $this->makeFakeAttribute(11, 'Room <A> & B');

        $this->assertParity(
            'Admin/Reservations/import_reservations_template_csv.tpl',
            'Admin/Reservations/import_reservations_template_csv.twig',
            ['Attributes' => [$attr1, $attr2]]
        );
    }

    public function testImportTemplateCsvWithSingleQuoteInLabel(): void
    {
        // Kept for historical context; the apostrophe case is now explicitly
        // covered by testImportTemplateCsvApostropheAndSpecialChars above.
        $attr = $this->makeFakeAttribute(3, 'Room A Type - Special');

        $this->assertParity(
            'Admin/Reservations/import_reservations_template_csv.tpl',
            'Admin/Reservations/import_reservations_template_csv.twig',
            ['Attributes' => [$attr]]
        );
    }

    // ── reservations_csv ─────────────────────────────────────────────────────

    /**
     * Build a minimal fake reservation for CSV export.
     */
    private function makeCsvReservation(): object
    {
        return new class () {
            public string $FirstName = 'Jane';
            public string $LastName = 'Smith';
            public string $ResourceName = 'Conference Room';
            public string $Title = 'Budget Meeting';
            public string $Description = 'Annual review';
            public string $ReferenceNumber = 'REF-2025-001';
            public Date $StartDate;
            public Date $EndDate;
            public Date $CreatedDate;
            public Date $ModifiedDate;
            public Date $CheckinDate;
            public Date $CheckoutDate;
            public Date $OriginalEndDate;
            public object $Attributes;

            public function __construct()
            {
                $this->StartDate = Date::Parse('2025-06-15 09:00:00', 'UTC');
                $this->EndDate = Date::Parse('2025-06-15 10:30:00', 'UTC');
                $this->CreatedDate = Date::Parse('2025-06-10 08:00:00', 'UTC');
                $this->ModifiedDate = Date::Parse('2025-06-11 08:00:00', 'UTC');
                $this->CheckinDate = Date::Parse('2025-06-15 09:05:00', 'UTC');
                $this->CheckoutDate = Date::Parse('2025-06-15 10:35:00', 'UTC');
                $this->OriginalEndDate = Date::Parse('2025-06-15 10:30:00', 'UTC');
                $this->Attributes = new class () {
                    public function Get(mixed $id): string
                    {
                        return 'attr-value-' . $id;
                    }
                };
            }

            public function GetDuration(): object
            {
                return new class () {
                    public function __toString(): string
                    {
                        return '1h 30m';
                    }
                };
            }
        };
    }

    public function testReservationsCsvHeaderOnly(): void
    {
        $this->assertParity(
            'Admin/Reservations/reservations_csv.tpl',
            'Admin/Reservations/reservations_csv.twig',
            [
                'reservations' => [],
                'ReservationAttributes' => [],
                'Timezone' => 'UTC',
            ]
        );
    }

    public function testReservationsCsvHeaderWithAttributes(): void
    {
        $attr1 = $this->makeFakeAttribute(10, 'Department');
        $attr2 = $this->makeFakeAttribute(11, 'Project Code');

        $this->assertParity(
            'Admin/Reservations/reservations_csv.tpl',
            'Admin/Reservations/reservations_csv.twig',
            [
                'reservations' => [],
                'ReservationAttributes' => [$attr1, $attr2],
                'Timezone' => 'UTC',
            ]
        );
    }

    public function testReservationsCsvWithData(): void
    {
        $reservation = $this->makeCsvReservation();

        $this->assertParity(
            'Admin/Reservations/reservations_csv.tpl',
            'Admin/Reservations/reservations_csv.twig',
            [
                'reservations' => [$reservation],
                'ReservationAttributes' => [],
                'Timezone' => 'UTC',
            ]
        );
    }

    public function testReservationsCsvWithDataAndAttributes(): void
    {
        $reservation = $this->makeCsvReservation();
        $attr = $this->makeFakeAttribute(10, 'Department');

        $this->assertParity(
            'Admin/Reservations/reservations_csv.tpl',
            'Admin/Reservations/reservations_csv.twig',
            [
                'reservations' => [$reservation],
                'ReservationAttributes' => [$attr],
                'Timezone' => 'UTC',
            ]
        );
    }

    /**
     * CSV parity with apostrophe and HTML-special chars in escaped fields.
     *
     * This test CATCHES the escape:'quotes' → escapequotes mistake in
     * reservations_csv.tpl/.twig for ResourceName, Title, Description,
     * attribute labels, and attribute values:
     * - Smarty escape:'quotes' → backslash-escapes only single quotes (')
     * - Wrong Twig |escapequotes → HTML entities (&#39;, &amp;, &quot;)
     * - Correct Twig |replace({("'"): "\\'"}) → byte-identical to Smarty
     */
    public function testReservationsCsvApostropheAndSpecialChars(): void
    {
        $reservation = new class () {
            public string $FirstName = "O'Brien";
            public string $LastName = 'Smith';
            public string $ResourceName = "St. Patrick's Hall & <Annex>";
            public string $Title = "Team's Meeting";
            public string $Description = 'Review & discuss "Q3" goals';
            public string $ReferenceNumber = 'REF-2025-APOS';
            public Date $StartDate;
            public Date $EndDate;
            public Date $CreatedDate;
            public Date $ModifiedDate;
            public Date $CheckinDate;
            public Date $CheckoutDate;
            public Date $OriginalEndDate;
            public object $Attributes;

            public function __construct()
            {
                $this->StartDate = Date::Parse('2025-06-15 09:00:00', 'UTC');
                $this->EndDate = Date::Parse('2025-06-15 10:30:00', 'UTC');
                $this->CreatedDate = Date::Parse('2025-06-10 08:00:00', 'UTC');
                $this->ModifiedDate = Date::Parse('2025-06-11 08:00:00', 'UTC');
                $this->CheckinDate = Date::Parse('2025-06-15 09:05:00', 'UTC');
                $this->CheckoutDate = Date::Parse('2025-06-15 10:35:00', 'UTC');
                $this->OriginalEndDate = Date::Parse('2025-06-15 10:30:00', 'UTC');
                $this->Attributes = new class () {
                    public function Get(mixed $id): string
                    {
                        return "Patron's value & <more>";
                    }
                };
            }

            public function GetDuration(): object
            {
                return new class () {
                    public function __toString(): string
                    {
                        return '1h 30m';
                    }
                };
            }
        };

        $attr = $this->makeFakeAttribute(20, "Patron's Type");

        $this->assertParity(
            'Admin/Reservations/reservations_csv.tpl',
            'Admin/Reservations/reservations_csv.twig',
            [
                'reservations' => [$reservation],
                'ReservationAttributes' => [$attr],
                'Timezone' => 'UTC',
            ]
        );
    }

    // ── manage_reservation_colors ─────────────────────────────────────────────

    /** @return array<string, mixed> */
    private function makeColorsVars(bool $withRules = false, bool $withAttributes = false): array
    {
        $attributes = [];
        if ($withAttributes) {
            $attributes = [
                $this->makeFakeAttribute(1, 'Reservation Type'),
                $this->makeFakeAttribute(2, 'Priority'),
            ];
        }

        $rules = [];
        if ($withRules) {
            $rule = ReservationColorRule::Create(1, 'Conference', '#ff0000');
            $rule->AttributeName = 'Reservation Type';
            $rule->Id = 101;
            $rules = [$rule];
        }

        return [
            'Attributes' => $attributes,
            'Rules' => $rules,
        ];
    }

    public function testManageReservationColorsEmpty(): void
    {
        $this->assertParity(
            'Admin/Reservations/manage_reservation_colors.tpl',
            'Admin/Reservations/manage_reservation_colors.twig',
            $this->makeColorsVars()
        );
    }

    public function testManageReservationColorsWithAttributesAndRules(): void
    {
        $this->assertParity(
            'Admin/Reservations/manage_reservation_colors.tpl',
            'Admin/Reservations/manage_reservation_colors.twig',
            $this->makeColorsVars(true, true)
        );
    }

    public function testManageReservationColorsStructural(): void
    {
        $twig = new TwigRenderer();
        $vars = $this->makeColorsVars(false, true);
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $html = $twig->render('Admin/Reservations/manage_reservation_colors.twig');

        $this->assertStringContainsString('page-manage-reservation-colors', $html);
        $this->assertStringContainsString('addDialog', $html);
        $this->assertStringContainsString('deleteDialog', $html);
        $this->assertStringContainsString('reservation-colors.js', $html);
        $this->assertStringContainsString('csrf_token', $html);
    }

    // ── manage_reservations (main page) ───────────────────────────────────────

    public function testManageReservationsEmptyPage(): void
    {
        $this->assertMainPageParity($this->makeMainPageVars());
    }

    public function testManageReservationsWithReservations(): void
    {
        $reservation = $this->makeFakeReservation(42, 'REF-001', false);
        $this->assertMainPageParity($this->makeMainPageVars([$reservation]));
    }

    public function testManageReservationsWithApprovalReservation(): void
    {
        $reservation = $this->makeFakeReservation(43, 'REF-002', true);
        $this->assertMainPageParity($this->makeMainPageVars([$reservation]));
    }

    public function testManageReservationsWithCredits(): void
    {
        $reservation = $this->makeFakeReservation(42, 'REF-001', false);
        /** @var object $reservation */
        $reservation->CreditsConsumed = 3;
        $this->assertMainPageParity($this->makeMainPageVars([$reservation], [], true));
    }

    public function testManageReservationsNotDesktop(): void
    {
        $reservation = $this->makeFakeReservation(42, 'REF-001', false);
        $vars = $this->makeMainPageVars([$reservation], [], false, true, false);
        $this->assertMainPageParity($vars);
    }

    public function testManageReservationsStructural(): void
    {
        $twig = new TwigRenderer();
        $vars = $this->makeMainPageVars();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $html = $twig->render('Admin/Reservations/manage_reservations.twig');

        $this->assertStringContainsString('page-manage-reservations', $html);
        $this->assertStringContainsString('deleteInstanceDialog', $html);
        $this->assertStringContainsString('deleteSeriesDialog', $html);
        $this->assertStringContainsString('deleteMultipleDialog', $html);
        $this->assertStringContainsString('importReservationsDialog', $html);
        $this->assertStringContainsString('termsOfServiceDialog', $html);
        $this->assertStringContainsString('reservations.js', $html);
        $this->assertStringContainsString('csrf_token', $html);
        $this->assertStringContainsString('ReservationManagement', $html);
    }

    public function testManageReservationsWithAttributeFilters(): void
    {
        $attr = $this->makeFakeAttribute(5, 'Department');
        $vars = $this->makeMainPageVars([], [], false, true, true);
        $vars['AttributeFilters'] = [$attr];
        $this->assertMainPageParity($vars);
    }

    public function testManageReservationsWithStatusReasons(): void
    {
        $reason = new ResourceStatusReason(1, ResourceStatus::UNAVAILABLE, 'Under Maintenance');
        $vars = $this->makeMainPageVars([], [], false, true, true);
        $vars['StatusReasons'] = [$reason];
        $this->assertMainPageParity($vars);
    }
}
