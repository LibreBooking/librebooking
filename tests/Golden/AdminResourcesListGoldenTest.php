<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../lib/Common/Templating/SmartyRenderer.php');
require_once(__DIR__ . '/../../lib/Common/Templating/LibreBookingExtension.php');
require_once(__DIR__ . '/../../lib/Common/Templating/TwigRenderer.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../Domain/CustomAttribute.php');
require_once(__DIR__ . '/../../Domain/ResourceType.php');
require_once(__DIR__ . '/../../Domain/Values/ResourceStatus.php');
require_once(__DIR__ . '/../../lib/Application/Schedule/namespace.php');
require_once(__DIR__ . '/../../Pages/Admin/ManageResourcesPage.php');
require_once(__DIR__ . '/../../Pages/Admin/ManageResourceGroupsPage.php');
require_once(__DIR__ . '/../../Pages/Admin/ManageResourceTypesPage.php');
require_once(__DIR__ . '/../../Pages/Admin/ManageResourceStatusPage.php');
require_once(__DIR__ . '/../../Presenters/Admin/ManageResourcesPresenter.php');
require_once(__DIR__ . '/../../Presenters/Admin/ManageResourceGroupsPresenter.php');
require_once(__DIR__ . '/../../Presenters/Admin/ManageResourceTypesPresenter.php');
require_once(__DIR__ . '/../../Presenters/Admin/ManageResourceStatusPresenter.php');
require_once(__DIR__ . '/../../Presenters/ViewResourcesPresenter.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for Admin/Resources list/misc templates.
 *
 * Templates covered (8):
 *   - view_resources.tpl           → .twig  (parity with accepted divergences)
 *   - manage_resource_menu.tpl     → .twig  (full parity)
 *   - manage_resource_groups.tpl   → .twig  (structural — full page, JS-heavy)
 *   - manage_resource_status.tpl   → .twig  (parity with accepted divergences)
 *   - manage_resource_types.tpl    → .twig  (parity with accepted divergences)
 *   - show_resource_qr.tpl         → .twig  (full parity)
 *   - resources_csv.tpl            → .twig  (autoescape-off, CSV quote-escape parity)
 *   - import_resource_template_csv.tpl → .twig (autoescape-off, CSV quote-escape parity)
 *
 * Parity strategy
 * ---------------
 * view_resources.twig: Full parity after accepted divergences:
 *   (a) resource.GetSortOrder()|default:"0" vs GetSortOrder() ?: '0' — when sort order
 *       is null, Smarty default:"0" outputs "0" and Twig ?: '0' does the same.
 *   (b) sanitize_rich_text for Description/Notes — both engines call the same PHP filter.
 *   (c) resource.GetAdminGroupId() null check: Smarty uses `!== null && isset(...)`,
 *       Twig uses `is not null and ... in ...` — functionally identical.
 *   (d) & vs &amp; in hrefs — normalizer handles via strip function.
 *
 * manage_resource_menu.twig: Full parity. $Path is used verbatim.
 *
 * manage_resource_status.twig: Parity. Smarty {function} macro becomes {% macro %}.
 *
 * manage_resource_types.twig: Parity with accepted divergences:
 *   (e) type.Id()|raw in JS: Twig uses raw for numeric id in JS object literal.
 *   (f) update_button() stray submit attr stripped.
 *
 * manage_resource_groups.twig: Structural assertions only — full page, JS-heavy,
 *   ResourceGroups is JSON, tree library initialization — no meaningful Smarty parity
 *   for the script block. Key structural elements verified via assertTwigContains.
 *
 * show_resource_qr.twig: Full parity.
 *
 * resources_csv.twig: autoescape false, |replace({("'"): "\\'"}) for quote escaping.
 *   assertSame on raw rendered output (no HtmlNormalizer — it's plain text CSV).
 *   Special-char fixtures: apostrophes, &, <, " in resource names/descriptions.
 *
 * import_resource_template_csv.twig: Same CSV approach, header-only template.
 *
 * Accepted divergences:
 *   (a) & vs &amp; in hrefs in view_resources (stripped)
 *   (b) submit="1" stray attr on update_button (stripped in manage_resource_types)
 *   (c) Twig: type.Id()|raw emits raw int in JS; Smarty does the same via {$type->Id()}
 */
class AdminResourcesListGoldenTest extends GoldenTemplateTestCase
{
    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Resources $savedResources = null;

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/admin/manage_resources.php';
        $_SERVER['REQUEST_URI'] = '/web/admin/manage_resources.php';
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();
        $this->savedServiceLocatorServer = ServiceLocator::GetServer();
        $fakeServer = new FakeServer();
        $fakeServer->UserSession->CSRFToken = 'golden-test-csrf-token';
        $fakeServer->UserSession->UserId = 1;
        $fakeServer->UserSession->IsAdmin = true;
        $fakeServer->UserSession->IsResourceAdmin = false;
        $fakeServer->UserSession->IsScheduleAdmin = false;
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
     * Strip submit="1" stray attr from both before comparison.
     *
     * @param array<string, mixed> $vars
     */
    private function assertParityNoSubmit(string $tplName, string $twigName, array $vars): void
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

        $smartyHtml = preg_replace('/\s+submit="(?:true|1)"/', '', $smartyHtml);
        $twigHtml   = preg_replace('/\s+submit="(?:true|1)"/', '', $twigHtml);

        $this->assertSame(
            HtmlNormalizer::normalize($smartyHtml),
            HtmlNormalizer::normalize($twigHtml),
            "Smarty vs Twig mismatch for $twigName (after stripping submit attr)"
        );
    }

    /**
     * Strip & vs &amp; divergence in hrefs from both before comparison.
     * Also strips submit="1" stray attr.
     *
     * @param array<string, mixed> $vars
     */
    private function assertParityHrefAmp(string $tplName, string $twigName, array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyHtml = (string) preg_replace_callback(
            '/resource-name="([^"]*)"/',
            static fn (array $m): string => 'resource-name="' . stripslashes($m[1]) . '"',
            $smarty->render($tplName)
        );

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigHtml = $twig->render($twigName);

        $strip = static function (string $html): string {
            $html = str_replace('&amp;', '&', $html);
            // Normalize all apostrophe encodings (Twig uses &#039;, Smarty uses raw ')
            $html = str_replace(['&#039;', '&#39;', '&apos;'], "'", $html);
            $html = (string) preg_replace('/\s+submit="(?:true|1)"/', '', $html);
            return $html;
        };

        $this->assertSame(
            HtmlNormalizer::normalize($strip($smartyHtml)),
            HtmlNormalizer::normalize($strip($twigHtml)),
            "Smarty vs Twig mismatch for $twigName (after stripping href &amp; + submit)"
        );
    }

    /**
     * Assert CSV template output is byte-identical between Smarty and Twig.
     * No HtmlNormalizer — CSV is plain text.
     *
     * @param array<string, mixed> $vars
     */
    private function assertCsvParity(string $tplName, string $twigName, array $vars): void
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
            $expected,
            $actual,
            "Smarty vs Twig CSV mismatch for $twigName"
        );
    }

    /**
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

    private function makeResource(int $id = 1, string $name = 'Conference Room', int $scheduleId = 1): BookableResource
    {
        $r = BookableResource::CreateNew($name, $scheduleId);
        // Force a stable id via reflection
        $ref = new \ReflectionProperty(BookableResource::class, '_resourceId');
        $ref->setAccessible(true);
        $ref->setValue($r, $id);
        return $r;
    }

    private function makeResourceWithAllFields(): BookableResource
    {
        $r = $this->makeResource(42, "O'Brien's Room", 2);
        $r->SetMinLength(1800);
        $r->SetMaxLength(7200);
        $r->SetBufferTime(900);
        $r->SetAllowMultiday(true);
        $r->SetRequiresApproval(1);
        $r->SetAutoAssign(0);
        $r->SetMaxParticipants(20);
        $r->SetCheckin(true, 15);
        $r->SetResourceGroupIds([1, 2]);
        $r->SetColor('#ff0000');
        return $r;
    }

    /** @return object */
    private function makeSchedule(int $id = 1, string $name = 'Main Schedule')
    {
        return new class ($id, $name) {
            public function __construct(private int $id, private string $name)
            {
            }

            public function GetId(): int
            {
                return $this->id;
            }

            public function GetName(): string
            {
                return $this->name;
            }
        };
    }

    /** @return object */
    private function makeAdminGroup(string $name = 'Resource Admins')
    {
        return new class ($name) {
            public string $Name;

            public function __construct(string $name)
            {
                $this->Name = $name;
            }
        };
    }

    /** @return object */
    private function makeResourceGroupItem(string $name)
    {
        return new class ($name) {
            public string $name;

            public function __construct(string $name)
            {
                $this->name = $name;
            }
        };
    }

    private function makeResourceStatusReason(int $id, int $statusId, string $description): ResourceStatusReason
    {
        return new ResourceStatusReason($id, $statusId, $description);
    }

    private function makeResourceType(int $id, string $name, string $description = ''): ResourceType
    {
        return new ResourceType($id, $name, $description);
    }

    /**
     * @return array<string, mixed>
     */
    private function makeBaseViewVars(): array
    {
        $schedule1 = $this->makeSchedule(1, 'Main Schedule');
        $schedule2 = $this->makeSchedule(2, 'Evening Schedule');

        $resourceType1 = $this->makeResourceType(10, 'Meeting Room');
        $resourceType2 = $this->makeResourceType(11, 'Lab & Studio');

        return [
            'Resources'              => [],
            'Schedules'              => [1 => $schedule1, 2 => $schedule2],
            'AllSchedules'           => [$schedule1, $schedule2],
            'ResourceTypes'          => [10 => $resourceType1, 11 => $resourceType2],
            'StatusReasons'          => [],
            'ResourceAdminGroup'     => [],
            'ResourceGroup'          => [],
            'ResourcePermissionTypes' => [],
            'AttributeList'          => [],
            'AttributeFilters'       => [],
            'YesNoOptions'           => ['1' => 'Yes', '0' => 'No'],
            'CreditsEnabled'         => false,
            'IcsEnabled'             => true,
            'ScriptUrl'              => 'http://localhost/web',
            'ResourceNameFilter'     => '',
            'ScheduleIdFilter'       => '',
            'ResourceTypeFilter'     => '',
            'CapacityFilter'         => '',
            'RequiresApprovalFilter' => '',
            'AutoPermissionFilter'   => '',
            'AllowMultiDayFilter'    => '',
        ];
    }

    private function makeSimpleAttribute(
        int $id = 1,
        string $label = 'Department',
        int $type = CustomAttributeTypes::SINGLE_LINE_TEXTBOX
    ): CustomAttribute {
        return new CustomAttribute($id, $label, $type, CustomAttributeCategory::RESOURCE, '', false, null, 0, [], false);
    }

    // ── show_resource_qr.tpl ─────────────────────────────────────────────────

    public function testShowResourceQrParity(): void
    {
        $vars = [
            'QRImageUrl'   => '/path/to/qr.png',
            'ResourceName' => 'Conference Room A',
        ];
        $this->assertParity(
            'Admin/Resources/show_resource_qr.tpl',
            'Admin/Resources/show_resource_qr.twig',
            $vars
        );
    }

    public function testShowResourceQrWithSpecialChars(): void
    {
        $vars = [
            'QRImageUrl'   => '/path/to/qr.png?id=42&token=abc',
            'ResourceName' => "O'Brien's Lab & Studio",
        ];
        // Accepted divergence: Twig autoescape HTML-encodes & in src and ' in text
        // while Smarty does not. Normalize both to raw before comparing.
        $this->assertParityHrefAmp(
            'Admin/Resources/show_resource_qr.tpl',
            'Admin/Resources/show_resource_qr.twig',
            $vars
        );
    }

    // ── manage_resource_menu.tpl ─────────────────────────────────────────────

    public function testResourceMenuNonAdminParity(): void
    {
        $vars = [
            'Path'                 => '/web/',
            'CanViewResourceAdmin' => false,
            'CanViewScheduleAdmin' => false,
            'ResourcePageTitleKey' => 'ManageResources',
        ];
        $this->assertParity(
            'Admin/Resources/manage_resource_menu.tpl',
            'Admin/Resources/manage_resource_menu.twig',
            $vars
        );
    }

    public function testResourceMenuResourceAdminParity(): void
    {
        $vars = [
            'Path'                 => '/web/',
            'CanViewResourceAdmin' => true,
            'CanViewScheduleAdmin' => false,
            'ResourcePageTitleKey' => 'ManageResourceGroups',
        ];
        $this->assertParity(
            'Admin/Resources/manage_resource_menu.tpl',
            'Admin/Resources/manage_resource_menu.twig',
            $vars
        );
    }

    public function testResourceMenuScheduleAdminParity(): void
    {
        $vars = [
            'Path'                 => '/web/',
            'CanViewResourceAdmin' => false,
            'CanViewScheduleAdmin' => true,
            'ResourcePageTitleKey' => 'ManageResourceTypes',
        ];
        $this->assertParity(
            'Admin/Resources/manage_resource_menu.tpl',
            'Admin/Resources/manage_resource_menu.twig',
            $vars
        );
    }

    // ── manage_resource_status.tpl ───────────────────────────────────────────

    /**
     * @return array<string, mixed>
     */
    private function makeStatusVars(bool $withReasons = false): array
    {
        $reasons = [
            ResourceStatus::AVAILABLE   => [],
            ResourceStatus::UNAVAILABLE => [],
            ResourceStatus::HIDDEN      => [],
        ];

        if ($withReasons) {
            $reasons[ResourceStatus::AVAILABLE][]   = $this->makeResourceStatusReason(1, ResourceStatus::AVAILABLE, 'Fully operational');
            $reasons[ResourceStatus::UNAVAILABLE][] = $this->makeResourceStatusReason(2, ResourceStatus::UNAVAILABLE, 'Under maintenance');
            $reasons[ResourceStatus::UNAVAILABLE][] = $this->makeResourceStatusReason(3, ResourceStatus::UNAVAILABLE, 'In use & reserved');
            $reasons[ResourceStatus::HIDDEN][]      = $this->makeResourceStatusReason(4, ResourceStatus::HIDDEN, 'Decommissioned');
        }

        return [
            'StatusReasons' => $reasons,
            'Path'          => '/web/',
            'CanViewResourceAdmin' => false,
            'CanViewScheduleAdmin' => false,
        ];
    }

    public function testManageResourceStatusEmptyParity(): void
    {
        $this->assertParity(
            'Admin/Resources/manage_resource_status.tpl',
            'Admin/Resources/manage_resource_status.twig',
            $this->makeStatusVars(false)
        );
    }

    public function testManageResourceStatusWithReasonsParity(): void
    {
        // Accepted divergence: Twig autoescape HTML-encodes & in body text (e.g. "In use & reserved")
        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resource_status.tpl',
            'Admin/Resources/manage_resource_status.twig',
            $this->makeStatusVars(true)
        );
    }

    public function testManageResourceStatusTwigContainsKeyElements(): void
    {
        $this->assertTwigContains(
            'Admin/Resources/manage_resource_status.twig',
            $this->makeStatusVars(true),
            [
                'id="page-manage-resource-status"',
                'id="panelAvailable"',
                'id="panelUnavailable"',
                'id="panelHidden"',
                'id="addDialog"',
                'id="editDialog"',
                'id="deleteDialog"',
                'id="csrf_token"',
                'value="golden-test-csrf-token"',
                'Fully operational',
                'Under maintenance',
                'resource-status.js',
            ]
        );
    }

    // ── manage_resource_types.tpl ────────────────────────────────────────────

    /**
     * @return array<string, mixed>
     */
    private function makeTypesVars(bool $withTypes = false, bool $withAttributes = false): array
    {
        $types = [];
        $attrs = [];

        if ($withTypes) {
            $types = [
                $this->makeResourceType(1, 'Meeting Room', 'For team meetings'),
                $this->makeResourceType(2, 'Lab & Studio', 'Creative space'),
            ];
        }

        if ($withAttributes) {
            $attrs = [
                $this->makeSimpleAttribute(1, 'Capacity'),
                $this->makeSimpleAttribute(2, 'Floor'),
            ];
        }

        return [
            'ResourceTypes' => $types,
            'AttributeList' => $attrs,
            'Path'          => '/web/',
            'CanViewResourceAdmin' => false,
            'CanViewScheduleAdmin' => false,
        ];
    }

    public function testManageResourceTypesEmptyParity(): void
    {
        $this->assertParityNoSubmit(
            'Admin/Resources/manage_resource_types.tpl',
            'Admin/Resources/manage_resource_types.twig',
            $this->makeTypesVars(false)
        );
    }

    public function testManageResourceTypesWithRowsParity(): void
    {
        // Accepted divergence: Twig autoescape HTML-encodes & in body text (resource type names)
        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resource_types.tpl',
            'Admin/Resources/manage_resource_types.twig',
            $this->makeTypesVars(true)
        );
    }

    public function testManageResourceTypesWithAttributesParity(): void
    {
        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resource_types.tpl',
            'Admin/Resources/manage_resource_types.twig',
            $this->makeTypesVars(true, true)
        );
    }

    public function testManageResourceTypesTwigContainsKeyElements(): void
    {
        $this->assertTwigContains(
            'Admin/Resources/manage_resource_types.twig',
            $this->makeTypesVars(true, true),
            [
                'id="page-manage-resource-types"',
                'id="addForm"',
                'id="editDialog"',
                'id="deleteDialog"',
                'id="csrf_token"',
                'value="golden-test-csrf-token"',
                'Meeting Room',
                'ResourceTypeManagement',
                'resource-types.js',
            ]
        );
    }

    // ── manage_resource_groups.tpl ───────────────────────────────────────────

    /**
     * @return array<string, mixed>
     */
    private function makeGroupsVars(): array
    {
        $r1 = $this->makeResource(1, 'Conference Room');
        $r2 = $this->makeResource(2, "O'Brien's Lab");

        return [
            'Resources'       => [$r1, $r2],
            'ResourceGroups'  => '[]',
            'Path'            => '/web/',
            'CanViewResourceAdmin' => false,
            'CanViewScheduleAdmin' => false,
        ];
    }

    public function testManageResourceGroupsTwigContainsKeyElements(): void
    {
        $this->assertTwigContains(
            'Admin/Resources/manage_resource_groups.twig',
            $this->makeGroupsVars(),
            [
                'id="page-manage-resource-groups"',
                'id="group-tree"',
                'id="resource-list"',
                'id="renameDialog"',
                'id="deleteDialog"',
                'id="addChildDialog"',
                'id="csrf_token"',
                'value="golden-test-csrf-token"',
                'Conference Room',
                'ResourceGroupManagement',
                'resource-groups.js',
                'jqtree',
                'jquery-contextmenu',
            ]
        );
    }

    public function testManageResourceGroupsParity(): void
    {
        // Full parity after stripping accepted divergences:
        // (a) Twig autoescape encodes ' in resource names as &apos; in HTML body text;
        //     Smarty leaves them as raw ' (autoescape off by default).
        // (b) & in hrefs: &amp; vs & (same as other full-page templates).
        // (c) resource-name="" is HTML-escaped by Twig; Smarty JS-escaped it (O\'Brien),
        //     which put the backslashes into the jqTree label read via .attr().
        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resource_groups.tpl',
            'Admin/Resources/manage_resource_groups.twig',
            $this->makeGroupsVars()
        );
    }

    // ── view_resources.tpl ───────────────────────────────────────────────────

    public function testViewResourcesEmptyListParity(): void
    {
        $this->assertParityHrefAmp(
            'Admin/Resources/view_resources.tpl',
            'Admin/Resources/view_resources.twig',
            $this->makeBaseViewVars()
        );
    }

    public function testViewResourcesWithFiltersParity(): void
    {
        $vars = $this->makeBaseViewVars();
        $vars['ResourceNameFilter']     = 'Conference';
        $vars['ScheduleIdFilter']       = '1';
        $vars['ResourceTypeFilter']     = '10';
        $vars['CapacityFilter']         = '5';
        $vars['RequiresApprovalFilter'] = '1';
        $vars['AutoPermissionFilter']   = '0';
        $vars['AllowMultiDayFilter']    = '1';

        $this->assertParityHrefAmp(
            'Admin/Resources/view_resources.tpl',
            'Admin/Resources/view_resources.twig',
            $vars
        );
    }

    public function testViewResourcesWithResourcesParity(): void
    {
        $r1 = $this->makeResource(1, 'Conference Room', 1);
        $r2 = $this->makeResource(2, "O'Brien's Lab & Studio", 2);
        $r2->SetResourceGroupIds([1]);
        $r2->SetColor('#3366cc');

        $vars = $this->makeBaseViewVars();
        $vars['Resources'] = [$r1, $r2];
        $vars['ResourcePermissionTypes'] = [1 => 0, 2 => 1];
        $vars['ResourceGroup'] = [1 => $this->makeResourceGroupItem('Labs')];

        $this->assertParityHrefAmp(
            'Admin/Resources/view_resources.tpl',
            'Admin/Resources/view_resources.twig',
            $vars
        );
    }

    public function testViewResourcesWithAdminGroupParity(): void
    {
        $r = $this->makeResource(5, 'Board Room', 1);
        // Set admin group id via reflection
        $ref = new \ReflectionProperty(BookableResource::class, '_adminGroupId');
        $ref->setAccessible(true);
        $ref->setValue($r, 10);

        $vars = $this->makeBaseViewVars();
        $vars['Resources'] = [$r];
        $vars['ResourceAdminGroup'] = [10 => $this->makeAdminGroup('Resource Admins')];
        $vars['ResourcePermissionTypes'] = [5 => 0];

        $this->assertParityHrefAmp(
            'Admin/Resources/view_resources.tpl',
            'Admin/Resources/view_resources.twig',
            $vars
        );
    }

    public function testViewResourcesWithStatusReasonParity(): void
    {
        $r = $this->makeResource(6, 'Server Room', 1);
        // Force status to unavailable: use reflection on _statusId
        $ref = new \ReflectionProperty(BookableResource::class, '_statusId');
        $ref->setAccessible(true);
        $ref->setValue($r, ResourceStatus::UNAVAILABLE);
        // Force status reason id
        $reasonRef = new \ReflectionProperty(BookableResource::class, '_statusReasonId');
        $reasonRef->setAccessible(true);
        $reasonRef->setValue($r, 99);

        $vars = $this->makeBaseViewVars();
        $vars['Resources'] = [$r];
        $vars['StatusReasons'] = [99 => $this->makeResourceStatusReason(99, ResourceStatus::UNAVAILABLE, 'Under maintenance')];
        $vars['ResourcePermissionTypes'] = [6 => 0];

        $this->assertParityHrefAmp(
            'Admin/Resources/view_resources.tpl',
            'Admin/Resources/view_resources.twig',
            $vars
        );
    }

    public function testViewResourcesWithAttributeFiltersParity(): void
    {
        $vars = $this->makeBaseViewVars();
        $vars['AttributeFilters'] = [
            $this->makeSimpleAttribute(1, 'Floor'),
            $this->makeSimpleAttribute(2, 'Building'),
        ];

        $this->assertParityHrefAmp(
            'Admin/Resources/view_resources.tpl',
            'Admin/Resources/view_resources.twig',
            $vars
        );
    }

    public function testViewResourcesWithCreditsEnabledParity(): void
    {
        $r = $this->makeResource(7, 'Premium Room', 1);
        $r->SetCreditsPerSlot(2);
        $r->SetPeakCreditsPerSlot(5);

        $vars = $this->makeBaseViewVars();
        $vars['Resources'] = [$r];
        $vars['CreditsEnabled'] = true;
        $vars['ResourcePermissionTypes'] = [7 => 0];

        $this->assertParityHrefAmp(
            'Admin/Resources/view_resources.tpl',
            'Admin/Resources/view_resources.twig',
            $vars
        );
    }

    // ── resources_csv.tpl — CSV special-char fixtures ───────────────────────

    /**
     * @return array<string, mixed>
     */
    private function makeCsvVars(array $resources, array $attributes = []): array
    {
        $schedule = $this->makeSchedule(1, 'Main Schedule');
        $resourceType = $this->makeResourceType(10, 'Lab & Rooms');

        $groupLookup = new class ('Resource Admins') {
            public string $Name;

            public function __construct(string $name)
            {
                $this->Name = $name;
            }
        };

        return [
            'Resources'       => $resources,
            'Schedules'       => [1 => 'Main Schedule'],
            'ResourceTypes'   => [10 => $resourceType],
            'GroupLookup'     => [0 => $groupLookup],
            'ResourceGroupList' => [
                1 => $this->makeResourceGroupItem("O'Brien Labs"),
                2 => $this->makeResourceGroupItem('& Associates'),
            ],
            'AttributeList'   => $attributes,
        ];
    }

    public function testResourcesCsvHeaderOnlyNoResources(): void
    {
        $vars = $this->makeCsvVars([]);
        $this->assertCsvParity(
            'Admin/Resources/resources_csv.tpl',
            'Admin/Resources/resources_csv.twig',
            $vars
        );
    }

    public function testResourcesCsvSimpleResource(): void
    {
        $r = $this->makeResource(1, 'Conference Room', 1);
        $vars = $this->makeCsvVars([$r]);
        $this->assertCsvParity(
            'Admin/Resources/resources_csv.tpl',
            'Admin/Resources/resources_csv.twig',
            $vars
        );
    }

    /**
     * Golden CSV special-char fixture: apostrophes, &, <, ".
     * Proves escape:'quotes' parity — apostrophes get backslash-escaped,
     * & / < / " are left raw (CSV doesn't HTML-encode them).
     */
    public function testResourcesCsvApostropheAndSpecialChars(): void
    {
        $r = $this->makeResource(2, "O'Brien's Room & Lab", 1);
        $r->SetResourceGroupIds([1, 2]);

        $attrs = [
            $this->makeSimpleAttribute(1, "Room's Type"),
            $this->makeSimpleAttribute(2, 'Category & Tag'),
        ];

        $vars = $this->makeCsvVars([$r], $attrs);

        // Render both and compare
        $this->assertCsvParity(
            'Admin/Resources/resources_csv.tpl',
            'Admin/Resources/resources_csv.twig',
            $vars
        );

        // Prove apostrophe IS backslash-escaped in Twig output (not HTML-encoded)
        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigOutput = $twig->render('Admin/Resources/resources_csv.twig');

        // apostrophe in resource name → backslash-escaped
        $this->assertStringContainsString("O\\'Brien\\'s Room", $twigOutput, 'Apostrophe should be backslash-escaped');
        // & in resource name → kept as literal & (not &amp;)
        $this->assertStringContainsString('& Lab', $twigOutput, '& should be literal in CSV output');
        // attribute label with apostrophe → escaped
        $this->assertStringContainsString("Room\\'s Type", $twigOutput, 'Attribute label apostrophe should be backslash-escaped');
    }

    public function testResourcesCsvMultipleResources(): void
    {
        $r1 = $this->makeResource(1, 'Conference Room', 1);
        $r2 = $this->makeResource(2, "O'Brien's Lab", 1);
        $r2->SetResourceGroupIds([1]);
        $r3 = $this->makeResource(3, 'Studio & Workshop', 1);

        $vars = $this->makeCsvVars([$r1, $r2, $r3]);
        $this->assertCsvParity(
            'Admin/Resources/resources_csv.tpl',
            'Admin/Resources/resources_csv.twig',
            $vars
        );
    }

    public function testResourcesCsvWithAttributes(): void
    {
        $r = $this->makeResource(1, 'Room A', 1);
        $attrs = [
            $this->makeSimpleAttribute(1, "Room's Purpose"),
            $this->makeSimpleAttribute(2, 'Location & Floor'),
        ];
        $vars = $this->makeCsvVars([$r], $attrs);
        $this->assertCsvParity(
            'Admin/Resources/resources_csv.tpl',
            'Admin/Resources/resources_csv.twig',
            $vars
        );
    }

    // ── import_resource_template_csv.tpl ─────────────────────────────────────

    public function testImportTemplateCsvNoAttributes(): void
    {
        $vars = ['attributes' => []];
        $this->assertCsvParity(
            'Admin/Resources/import_resource_template_csv.tpl',
            'Admin/Resources/import_resource_template_csv.twig',
            $vars
        );
    }

    public function testImportTemplateCsvWithAttributes(): void
    {
        $attrs = [
            $this->makeSimpleAttribute(1, 'Department'),
            $this->makeSimpleAttribute(2, 'Floor'),
        ];
        $vars = ['attributes' => $attrs];
        $this->assertCsvParity(
            'Admin/Resources/import_resource_template_csv.tpl',
            'Admin/Resources/import_resource_template_csv.twig',
            $vars
        );
    }

    /**
     * CSV import template with apostrophes and special chars in attribute labels.
     */
    public function testImportTemplateCsvApostropheAndSpecialChars(): void
    {
        $attrs = [
            $this->makeSimpleAttribute(1, "Room's Type"),
            $this->makeSimpleAttribute(2, 'Category & Tag'),
            $this->makeSimpleAttribute(3, 'Notes "quoted"'),
        ];
        $vars = ['attributes' => $attrs];

        $this->assertCsvParity(
            'Admin/Resources/import_resource_template_csv.tpl',
            'Admin/Resources/import_resource_template_csv.twig',
            $vars
        );

        // Prove apostrophe IS backslash-escaped in Twig output
        $twig = new TwigRenderer();
        $twig->assign('attributes', $attrs);
        $twigOutput = $twig->render('Admin/Resources/import_resource_template_csv.twig');

        $this->assertStringContainsString("Room\\'s Type", $twigOutput, 'Apostrophe in attr label should be backslash-escaped');
        $this->assertStringContainsString('Category & Tag', $twigOutput, '& in attr label should be literal in CSV output');
    }
}
