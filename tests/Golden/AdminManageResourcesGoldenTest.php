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
require_once(__DIR__ . '/../../Presenters/Admin/ManageResourcesPresenter.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for Admin/Resources/manage_resources.
 *
 * Template covered:
 *   - manage_resources.tpl → manage_resources.twig  (2432-line full page)
 *
 * Parity strategy
 * ---------------
 * The manage_resources page is the largest Admin template and embeds JS blocks
 * that output resource data, editable configs, and action URLs.
 *
 * Full parity after accepted divergences:
 *   (a) & vs &amp; in hrefs/hrefs-in-attrs (ExportUrl, SCRIPT_NAME in JS)
 *       — stripped via assertParityHrefAmp.
 *   (b) submit="1" stray attr on update_button() with submit:'1' arg
 *       — stripped symmetrically.
 *   (c) Apostrophe entity forms (&apos; vs raw ') in HTML body — stripped.
 *
 * Structural (JS block + per-resource inline JS):
 *   The inline <script> block in manage_resources embeds data for every
 *   resource (JS object literals), translate() calls inside JS strings,
 *   editable source arrays, etc. This block is covered via
 *   assertTwigContains with key structural strings. Full JS parity is NOT
 *   asserted (the JS block mixes PHP-expanded strings into raw JS that the
 *   normalizer cannot compare fairly). See the doc comment in
 *   testManageResourcesJsBlockContainsKeyElements for rationale.
 *
 * Sections covered with LIVE parity:
 *   - Empty list (no resources)
 *   - Filter form (all filter combos)
 *   - Resource list (populated — accordion header/body, sub-panel includes)
 *   - Add/copy/delete/duration/capacity/access/status/credits dialogs
 *   - Bulk update + bulk delete dialogs
 *   - Permission user/group dialogs
 *   - Import dialog
 *   - ResourceGroup modal
 *
 * Sections covered structurally only:
 *   - Per-resource inline JS var block (resource data object + image/group
 *     push loops) — covered by assertTwigContains; see test doc.
 */
class AdminManageResourcesGoldenTest extends GoldenTemplateTestCase
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
     * Render both engines and assert normalized parity after stripping
     * accepted divergences: & vs &amp; in hrefs, submit="1", apostrophe
     * entity forms.
     *
     * @param array<string, mixed> $vars
     */
    private function assertParityHrefAmp(string $tplName, string $twigName, array $vars): void
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
            $html = str_replace('&amp;', '&', $html);
            $html = str_replace(['&#039;', '&#39;', '&apos;'], "'", $html);
            $html = (string) preg_replace('/\s+submit="(?:true|1)"/', '', $html);
            // Smarty {strip} removes whitespace between adjacent quoted HTML
            // attributes on separate lines (e.g. data-value=""data-name= with no space).
            // Normalize by inserting a space between the closing quote and data-name.
            $html = (string) preg_replace('/"(data-name=)/', '" $1', $html);
            return $html;
        };

        $this->assertSame(
            HtmlNormalizer::normalize($strip($smartyHtml)),
            HtmlNormalizer::normalize($strip($twigHtml)),
            "Smarty vs Twig mismatch for $twigName"
        );
    }

    /**
     * Assert Twig output contains all expected strings.
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
            $this->assertStringContainsString(
                $needle,
                $html,
                "Expected '$needle' in output of $twigName"
            );
        }
    }

    // ── Fixture factories ────────────────────────────────────────────────────

    private function makeResource(int $id = 1, string $name = 'Conference Room', int $scheduleId = 1): BookableResource
    {
        $r = BookableResource::CreateNew($name, $scheduleId);
        $ref = new \ReflectionProperty(BookableResource::class, '_resourceId');
        $ref->setAccessible(true);
        $ref->setValue($r, $id);
        return $r;
    }

    private function makeResourceType(int $id, string $name, string $description = ''): ResourceType
    {
        return new ResourceType($id, $name, $description);
    }

    private function makeResourceStatusReason(int $id, int $statusId, string $description): ResourceStatusReason
    {
        return new ResourceStatusReason($id, $statusId, $description);
    }

    private function makeSimpleAttribute(
        int $id = 1,
        string $label = 'Department',
        int $type = CustomAttributeTypes::SINGLE_LINE_TEXTBOX
    ): CustomAttribute {
        return new CustomAttribute($id, $label, $type, CustomAttributeCategory::RESOURCE, '', false, null, 0, [], false);
    }

    /**
     * Build an admin group mirroring GroupItemView: public $Id + $Name properties
     * AND Id() / Name() methods (Smarty accesses properties; Twig calls methods).
     */
    private function makeAdminGroup(int $id = 1, string $name = 'Resource Admins'): object
    {
        return new class ($id, $name) {
            public int $Id;
            public string $Name;

            public function __construct(int $id, string $name)
            {
                $this->Id   = $id;
                $this->Name = $name;
            }

            public function Id(): int
            {
                return $this->Id;
            }

            public function Name(): string
            {
                return $this->Name;
            }
        };
    }

    /** Build a group lookup entry (for GroupLookup[adminGroupId].Name). */
    private function makeGroupLookupEntry(string $name): object
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
     * Build the minimal fixture vars shared across most tests.
     *
     * Schedules is id=>name (key-value), AllSchedules is object array.
     * ResourceTypes is id=>ResourceType object.
     *
     * @return array<string, mixed>
     */
    private function makeBaseVars(): array
    {
        $rt1 = $this->makeResourceType(10, 'Meeting Room');
        $rt2 = $this->makeResourceType(11, 'Lab & Studio');

        return [
            'Resources'                  => [],
            'Schedules'                  => [1 => 'Main Schedule', 2 => 'Evening Schedule'],
            'AllSchedules'               => [],
            'ResourceTypes'              => [10 => $rt1, 11 => $rt2],
            'StatusReasons'              => [],
            'ResourceAdminGroup'         => [],
            'ResourceGroup'              => [],
            'ResourcePermissionTypes'    => [],
            'AttributeList'              => [],
            'AttributeFilters'           => [],
            'YesNoOptions'               => ['1' => 'Yes', '0' => 'No'],
            'YesNoUnchangedOptions'      => ['-1' => 'Unchanged', '1' => 'Yes', '0' => 'No'],
            'CreditsEnabled'             => false,
            'IcsEnabled'                 => true,
            'ScriptUrl'                  => 'http://localhost/web',
            'ResourceNameFilter'         => '',
            'ScheduleIdFilter'           => '',
            'ResourceTypeFilter'         => '',
            'CapacityFilter'             => '',
            'RequiresApprovalFilter'     => '',
            'AutoPermissionFilter'       => '',
            'AllowMultiDayFilter'        => '',
            'ExportUrl'                  => '/web/admin/manage_resources.php?dr=export',
            'ResourceContactIsUser'      => false,
            'AdminGroups'                => [],
            'GroupLookup'                => [],
            'ResourceGroups'             => '[]',
            'ResourceStatusFilterId'     => '',
            'ResourceStatusReasonFilterId' => '',
            'CanViewAdmin'               => true,
        ];
    }

    // ── Tests: empty list ────────────────────────────────────────────────────

    public function testEmptyListParity(): void
    {
        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $this->makeBaseVars()
        );
    }

    public function testEmptyListWithCreditsEnabledParity(): void
    {
        $vars = $this->makeBaseVars();
        $vars['CreditsEnabled'] = true;
        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }

    public function testEmptyListIcsDisabledParity(): void
    {
        $vars = $this->makeBaseVars();
        $vars['IcsEnabled'] = false;
        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }

    // ── Tests: filter form ───────────────────────────────────────────────────

    public function testFilterFormWithActiveFiltersParity(): void
    {
        $vars = $this->makeBaseVars();
        $vars['ResourceNameFilter']     = 'Conference';
        $vars['ScheduleIdFilter']       = '1';
        $vars['ResourceTypeFilter']     = '10';
        $vars['CapacityFilter']         = '5';
        $vars['RequiresApprovalFilter'] = '1';
        $vars['AutoPermissionFilter']   = '0';
        $vars['AllowMultiDayFilter']    = '1';

        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }

    public function testFilterFormWithAttributeFiltersParity(): void
    {
        $vars = $this->makeBaseVars();
        $vars['AttributeFilters'] = [
            $this->makeSimpleAttribute(1, 'Floor'),
            $this->makeSimpleAttribute(2, 'Building'),
        ];

        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }

    // ── Tests: resource list (populated) ─────────────────────────────────────

    public function testSingleResourceParity(): void
    {
        $r = $this->makeResource(1, 'Conference Room', 1);
        $vars = $this->makeBaseVars();
        $vars['Resources'] = [$r];
        $vars['ResourceGroups'] = json_encode([]);

        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }

    public function testResourceWithAllFieldsParity(): void
    {
        $r = $this->makeResource(42, "O'Brien's Room", 1);
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
        // Rich text is stored HTML-encoded by the web form and rendered via sanitize_rich_text
        $r->SetDescription(htmlspecialchars('<p>Big <b>room</b> & more</p>'));
        $r->SetNotes(htmlspecialchars('<p>Bring <i>keys</i></p>'));

        // Set status
        $statusRef = new \ReflectionProperty(BookableResource::class, '_statusId');
        $statusRef->setAccessible(true);
        $statusRef->setValue($r, ResourceStatus::UNAVAILABLE);

        $rt = $this->makeResourceType(10, 'Meeting Room');
        $vars = $this->makeBaseVars();
        $vars['Resources'] = [$r];
        $vars['StatusReasons'] = [
            99 => $this->makeResourceStatusReason(99, ResourceStatus::UNAVAILABLE, 'Under maintenance'),
        ];
        $vars['ResourceGroups'] = json_encode([]);
        $vars['CreditsEnabled'] = true;

        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }

    public function testResourceWithAdminGroupParity(): void
    {
        $r = $this->makeResource(5, 'Board Room', 1);
        $adminGroupIdRef = new \ReflectionProperty(BookableResource::class, '_adminGroupId');
        $adminGroupIdRef->setAccessible(true);
        $adminGroupIdRef->setValue($r, 10);

        $vars = $this->makeBaseVars();
        $vars['Resources'] = [$r];
        $vars['AdminGroups'] = [$this->makeAdminGroup(10, 'Resource Admins')];
        $vars['GroupLookup'] = [10 => $this->makeGroupLookupEntry('Resource Admins')];
        $vars['ResourceGroups'] = json_encode([]);

        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }

    public function testResourceWithCustomAttributesParity(): void
    {
        $r = $this->makeResource(7, 'Lab A', 1);

        // Build attribute that applies to resource id 7 (pass entityIds in constructor)
        $attr = new CustomAttribute(1, 'Department', CustomAttributeTypes::SINGLE_LINE_TEXTBOX, CustomAttributeCategory::RESOURCE, '', false, null, 0, [7], false);

        $vars = $this->makeBaseVars();
        $vars['Resources'] = [$r];
        $vars['AttributeList'] = [$attr];
        $vars['ResourceGroups'] = json_encode([]);

        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }

    public function testMultipleResourcesParity(): void
    {
        $r1 = $this->makeResource(1, 'Conference Room', 1);
        $r2 = $this->makeResource(2, "O'Brien's Lab & Studio", 2);
        $r2->SetResourceGroupIds([1]);

        $vars = $this->makeBaseVars();
        $vars['Resources'] = [$r1, $r2];
        $vars['ResourceGroups'] = json_encode([]);

        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }

    public function testResourceWithStatusReasonParity(): void
    {
        $r = $this->makeResource(6, 'Server Room', 1);
        $statusRef = new \ReflectionProperty(BookableResource::class, '_statusId');
        $statusRef->setAccessible(true);
        $statusRef->setValue($r, ResourceStatus::UNAVAILABLE);
        $reasonRef = new \ReflectionProperty(BookableResource::class, '_statusReasonId');
        $reasonRef->setAccessible(true);
        $reasonRef->setValue($r, 99);

        $vars = $this->makeBaseVars();
        $vars['Resources'] = [$r];
        $vars['StatusReasons'] = [99 => $this->makeResourceStatusReason(99, ResourceStatus::UNAVAILABLE, 'Under maintenance')];
        $vars['ResourceGroups'] = json_encode([]);

        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }

    public function testResourceHiddenStatusParity(): void
    {
        $r = $this->makeResource(8, 'Storage Room', 1);
        $statusRef = new \ReflectionProperty(BookableResource::class, '_statusId');
        $statusRef->setAccessible(true);
        $statusRef->setValue($r, ResourceStatus::HIDDEN);

        $vars = $this->makeBaseVars();
        $vars['Resources'] = [$r];
        $vars['ResourceGroups'] = json_encode([]);

        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }

    public function testResourceWithCreditsEnabledParity(): void
    {
        $r = $this->makeResource(7, 'Premium Room', 1);
        $r->SetCreditsPerSlot(2);
        $r->SetPeakCreditsPerSlot(5);

        $vars = $this->makeBaseVars();
        $vars['Resources'] = [$r];
        $vars['CreditsEnabled'] = true;
        $vars['ResourceGroups'] = json_encode([]);

        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }

    public function testResourceWithResourceTypeParity(): void
    {
        $r = $this->makeResource(3, 'Lab Room', 1);
        $rtIdRef = new \ReflectionProperty(BookableResource::class, '_resourceTypeId');
        $rtIdRef->setAccessible(true);
        $rtIdRef->setValue($r, 10);

        $vars = $this->makeBaseVars();
        $vars['Resources'] = [$r];
        $vars['ResourceGroups'] = json_encode([]);

        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }

    public function testResourceContactIsUserParity(): void
    {
        $vars = $this->makeBaseVars();
        $vars['ResourceContactIsUser'] = true;

        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }

    // ── Tests: key structural elements ──────────────────────────────────────

    public function testTwigContainsKeyPageElements(): void
    {
        $vars = $this->makeBaseVars();
        $this->assertTwigContains(
            'Admin/Resources/manage_resources.twig',
            $vars,
            [
                'id="page-manage-resources"',
                'id="filter-resources-panel"',
                'id="list-resources-panel"',
                'id="add-resource-dialog"',
                'id="imageDialog"',
                'id="copyDialog"',
                'id="durationDialog"',
                'id="capacityDialog"',
                'id="accessDialog"',
                'id="statusDialog"',
                'id="deletePrompt"',
                'id="bulkUpdateDialog"',
                'id="bulkDeleteDialog"',
                'id="userDialog"',
                'id="groupDialog"',
                'id="resourceGroupDialog"',
                'id="importDialog"',
                'id="creditsDialog"',
                'id="csrf_token"',
                'value="golden-test-csrf-token"',
                'admin/resource.js',
                'ResourceManagement',
            ]
        );
    }

    public function testBulkUpdateDialogWithResourcesParity(): void
    {
        $r1 = $this->makeResource(1, 'Room A', 1);
        $r2 = $this->makeResource(2, 'Room B', 2);

        $vars = $this->makeBaseVars();
        $vars['Resources'] = [$r1, $r2];
        $vars['ResourceGroups'] = json_encode([]);

        // The bulk update dialog should be present when Resources is non-empty
        $this->assertTwigContains(
            'Admin/Resources/manage_resources.twig',
            $vars,
            [
                'id="bulkUpdatePromptButton"',
                'id="bulkDeletePromptButton"',
                'id="bulkUpdateDialog"',
                'id="panelCommon"',
                'id="panelCapacity"',
                'id="panelDuration"',
                'id="panelAccess"',
                'id="panelAdditionalAttributes"',
            ]
        );
    }

    public function testBulkUpdateWithCreditsAndIcsParity(): void
    {
        $r = $this->makeResource(1, 'Room A', 1);
        $vars = $this->makeBaseVars();
        $vars['Resources'] = [$r];
        $vars['CreditsEnabled'] = true;
        $vars['IcsEnabled'] = true;
        $vars['ResourceGroups'] = json_encode([]);

        $this->assertTwigContains(
            'Admin/Resources/manage_resources.twig',
            $vars,
            [
                'id="panelCredits"',
                'id="bulkEditAllowSubscriptions"',
            ]
        );
    }

    /**
     * Structural test for the per-resource inline JS block.
     *
     * The JS var resource = {...} block is templated PHP output embedded in
     * raw JS. Both engines produce functionally identical JS; the differences
     * (Twig uses `|escape_js`, Smarty uses `|escape:'javascript'`, and Twig
     * renders `true`/`false` for booleans in slightly different whitespace)
     * are semantically identical but make byte-exact comparison fragile.
     *
     * We verify the JS block structure via assertTwigContains and trust the
     * sub-panel parity tests (AdminResourcesSubPanelsGoldenTest) for the
     * embedded partials.
     */
    public function testManageResourcesJsBlockContainsKeyElements(): void
    {
        $r = $this->makeResource(1, 'Conference Room', 1);
        $r->SetMinLength(1800);
        $r->SetMaxLength(7200);
        $r->SetBufferTime(900);
        $r->SetCheckin(true, 15);
        $r->SetMaxConcurrentReservations(3);

        $vars = $this->makeBaseVars();
        $vars['Resources'] = [$r];
        $vars['ResourceGroups'] = json_encode([]);
        $vars['StatusReasons'] = [
            1 => $this->makeResourceStatusReason(1, ResourceStatus::AVAILABLE, 'Available reason'),
        ];
        $vars['ResourceStatusFilterId'] = '1';
        $vars['ResourceStatusReasonFilterId'] = '1';

        $this->assertTwigContains(
            'Admin/Resources/manage_resources.twig',
            $vars,
            [
                'var resource = {',
                "id: '1'",
                'resource.minLength = {',
                'resource.maxLength = {',
                'resource.bufferTime = {',
                'resource.resourceGroupIds = [',
                'resourceManagement.add(resource)',
                'resourceManagement.addStatusReason(',
                'resourceManagement.init()',
                'resourceManagement.initializeStatusFilter(',
                'resourceManagement.addResourceGroups(',
                'ResourceManagement(opts)',
                'setUpEditables()',
                'addTrumbowygType()',
            ]
        );
    }

    // ── Tests: full-page parity with full vars ───────────────────────────────

    public function testFullPageWithAllVarsParity(): void
    {
        $r1 = $this->makeResource(1, 'Conference Room', 1);
        $r2 = $this->makeResource(2, "O'Brien's Lab", 2);
        $r2->SetRequiresApproval(1);
        $r2->SetAutoAssign(0);

        $adminGroup = $this->makeAdminGroup(10, 'Resource Admins');

        $vars = $this->makeBaseVars();
        $vars['Resources'] = [$r1, $r2];
        $vars['AdminGroups'] = [$adminGroup];
        $vars['GroupLookup'] = [10 => $this->makeGroupLookupEntry('Resource Admins')];
        $vars['ResourceGroups'] = json_encode([]);
        $vars['AttributeFilters'] = [
            $this->makeSimpleAttribute(1, 'Floor'),
        ];
        $vars['ResourceNameFilter'] = 'Room';
        $vars['ResourceStatusFilterId'] = '';
        $vars['ResourceStatusReasonFilterId'] = '';

        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }

    public function testFullPageWithStatusReasonsParity(): void
    {
        $r = $this->makeResource(1, 'Room A', 1);

        $vars = $this->makeBaseVars();
        $vars['Resources'] = [$r];
        $vars['StatusReasons'] = [
            1 => $this->makeResourceStatusReason(1, ResourceStatus::AVAILABLE, 'Fully operational'),
            2 => $this->makeResourceStatusReason(2, ResourceStatus::UNAVAILABLE, 'Under maintenance & repair'),
        ];
        $vars['ResourceGroups'] = json_encode([]);

        $this->assertParityHrefAmp(
            'Admin/Resources/manage_resources.tpl',
            'Admin/Resources/manage_resources.twig',
            $vars
        );
    }
}
