<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../lib/Common/Templating/SmartyRenderer.php');
require_once(__DIR__ . '/../../lib/Common/Templating/LibreBookingExtension.php');
require_once(__DIR__ . '/../../lib/Common/Templating/TwigRenderer.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../Domain/Values/ResourcePermissionType.php');
require_once(__DIR__ . '/../../lib/Application/Schedule/namespace.php');
require_once(__DIR__ . '/../../Pages/Admin/ManageResourcesPage.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for Admin/Resources sub-panel templates.
 *
 * Templates covered (8 fragment partials):
 *   - manage_resources_access.tpl  → .twig  (parity with accepted & divergence)
 *   - manage_resources_capacity.tpl → .twig  (full parity)
 *   - manage_resources_credits.tpl  → .twig  (full parity)
 *   - manage_resources_duration.tpl → .twig  (parity)
 *   - manage_resources_group_permissions.tpl → .twig  (parity)
 *   - manage_resources_groups.tpl   → .twig  (parity)
 *   - manage_resources_public.tpl   → .twig  (parity with accepted & divergence in href)
 *   - manage_resources_user_permissions.tpl  → .twig  (parity)
 *
 * Parity strategy
 * ---------------
 * All 8 templates are standalone partials rendered with identical fixture vars.
 * Full byte-parity after HtmlNormalizer for all except:
 *
 * manage_resources_public.twig: Smarty {Pages::DISPLAY_RESOURCE} in href is
 * rendered as a literal class constant (no escaping). In Twig, constant(...)
 * calls return raw strings but autoescape is enabled, so `?` separator in
 * href is not `&amp;` but it equals Smarty. Accepted divergence:
 *   (a) href & vs &amp; — normalizer does not handle this; strip from both.
 *
 * manage_resources_access: data-value attributes containing bool/int values;
 * these are identical between engines after normalization.
 *
 * Fixture shape
 * -------------
 * resource: BookableResource built via BookableResourceBuilder or CreateNew,
 *           then setters called for each field under test.
 * Groups:   array of objects with ->Id, ->Name, ->PermissionType
 * Users:    array of objects with ->Id, ->First, ->Last, ->PermissionType
 * ResourceGroupList: array<int, object{name: string}>
 */
class AdminResourcesSubPanelsGoldenTest extends GoldenTemplateTestCase
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
     * Render both engines with accepted divergences stripped.
     * Used for manage_resources_public where & vs &amp; in hrefs diverges.
     *
     * @param array<string, mixed> $vars
     */
    private function assertPublicParity(string $tplName, string $twigName, array $vars): void
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
            // (a) href & vs &amp; divergence in subscription URLs and display URLs
            $html = str_replace('&amp;', '&', $html);
            return $html;
        };

        $this->assertSame(
            HtmlNormalizer::normalize($strip($smartyHtml)),
            HtmlNormalizer::normalize($strip($twigHtml)),
            "Smarty vs Twig mismatch for $twigName (after stripping accepted divergences)"
        );
    }

    // ── Fixture factories ────────────────────────────────────────────────────

    /** Build a plain resource with no special properties set. */
    private function makeResource(int $id = 1, string $name = 'Conference Room'): BookableResource
    {
        return BookableResource::CreateNew($name, 1);
    }

    /** Build a resource with all notice fields set. */
    private function makeResourceWithNotices(): BookableResource
    {
        $r = $this->makeResource();
        $r->SetMinNoticeAdd(3600);      // 1 hour
        $r->SetMinNoticeUpdate(7200);   // 2 hours
        $r->SetMinNoticeDelete(1800);   // 30 minutes
        $r->SetMaxNotice(86400);        // 1 day
        return $r;
    }

    /** Build a resource with approval, checkin, auto-release, and concurrent settings. */
    private function makeResourceAccessEnabled(): BookableResource
    {
        $r = $this->makeResource();
        $r->SetRequiresApproval(1);
        $r->SetAutoAssign(0);
        $r->SetCheckin(true, 15);
        return $r;
    }

    /** Build a resource with auto-assign and concurrent reservations. */
    private function makeResourceConcurrent(int $maxConcurrent = 3): BookableResource
    {
        $r = $this->makeResource();
        $r->SetAutoAssign(1);
        $r->SetMaxConcurrentReservations($maxConcurrent);
        return $r;
    }

    /** Build a resource with max participants. */
    private function makeResourceWithCapacity(int $capacity = 10): BookableResource
    {
        $r = $this->makeResource();
        $r->SetMaxParticipants($capacity);
        return $r;
    }

    /** Build a resource with credits set. */
    private function makeResourceWithCredits(int $credits = 2, int $peakCredits = 5): BookableResource
    {
        $r = $this->makeResource();
        $r->SetCreditsPerSlot($credits);
        $r->SetPeakCreditsPerSlot($peakCredits);
        return $r;
    }

    /** Build a resource with duration/buffer settings. */
    private function makeResourceWithDuration(): BookableResource
    {
        $r = $this->makeResource();
        $r->SetMinLength(1800);     // 30 minutes
        $r->SetMaxLength(14400);    // 4 hours
        $r->SetBufferTime(900);     // 15 minutes
        $r->SetAllowMultiday(true);
        return $r;
    }

    /** Build a resource with subscription enabled and a stable publicId. */
    private function makeResourceWithSubscription(bool $icsEnabled = true): BookableResource
    {
        $r = $this->makeResource();
        $r->EnableSubscription();
        // Override with a stable publicId for deterministic output
        $ref = new \ReflectionProperty(BookableResource::class, '_publicId');
        $ref->setAccessible(true);
        $ref->setValue($r, 'test-public-id-1234');
        return $r;
    }

    /** Build a resource with display enabled but no subscription. */
    private function makeResourceDisplayOnly(): BookableResource
    {
        $r = $this->makeResource();
        $r->EnableDisplay();
        $ref = new \ReflectionProperty(BookableResource::class, '_publicId');
        $ref->setAccessible(true);
        $ref->setValue($r, 'display-only-id-5678');
        return $r;
    }

    /** Build a resource with group IDs. */
    private function makeResourceWithGroups(array $groupIds = [1, 2]): BookableResource
    {
        $r = $this->makeResource();
        $r->SetResourceGroupIds($groupIds);
        return $r;
    }

    /**
     * Build a permission entry (group or user).
     *
     * @return object
     */
    private function makeGroupPermission(int $id, string $name, int $permType = ResourcePermissionType::None): object
    {
        return new class ($id, $name, $permType) {
            public int $Id;
            public string $Name;
            public int $PermissionType;

            public function __construct(int $id, string $name, int $permType)
            {
                $this->Id = $id;
                $this->Name = $name;
                $this->PermissionType = $permType;
            }
        };
    }

    /**
     * Build a user permission entry.
     *
     * @return object
     */
    private function makeUserPermission(int $id, string $first, string $last, int $permType = ResourcePermissionType::None): object
    {
        return new class ($id, $first, $last, $permType) {
            public int $Id;
            public string $First;
            public string $Last;
            public int $PermissionType;

            public function __construct(int $id, string $first, string $last, int $permType)
            {
                $this->Id = $id;
                $this->First = $first;
                $this->Last = $last;
                $this->PermissionType = $permType;
            }
        };
    }

    /**
     * Build a resource group list entry (for manage_resources_groups.tpl).
     *
     * @return object
     */
    private function makeGroupListItem(string $name): object
    {
        return new class ($name) {
            public string $name;

            public function __construct(string $name)
            {
                $this->name = $name;
            }
        };
    }

    // ── manage_resources_capacity.tpl ─────────────────────────────────────────

    public function testCapacityNoLimit(): void
    {
        $r = $this->makeResource();
        $this->assertParity(
            'Admin/Resources/manage_resources_capacity.tpl',
            'Admin/Resources/manage_resources_capacity.twig',
            ['resource' => $r]
        );
    }

    public function testCapacityWithLimit(): void
    {
        $r = $this->makeResourceWithCapacity(10);
        $this->assertParity(
            'Admin/Resources/manage_resources_capacity.tpl',
            'Admin/Resources/manage_resources_capacity.twig',
            ['resource' => $r]
        );
    }

    // ── manage_resources_credits.tpl ─────────────────────────────────────────

    public function testCreditsZero(): void
    {
        $r = $this->makeResource();
        $this->assertParity(
            'Admin/Resources/manage_resources_credits.tpl',
            'Admin/Resources/manage_resources_credits.twig',
            ['resource' => $r]
        );
    }

    public function testCreditsWithValues(): void
    {
        $r = $this->makeResourceWithCredits(2, 5);
        $this->assertParity(
            'Admin/Resources/manage_resources_credits.tpl',
            'Admin/Resources/manage_resources_credits.twig',
            ['resource' => $r]
        );
    }

    // ── manage_resources_duration.tpl ─────────────────────────────────────────

    public function testDurationNone(): void
    {
        $r = $this->makeResource();
        $this->assertParity(
            'Admin/Resources/manage_resources_duration.tpl',
            'Admin/Resources/manage_resources_duration.twig',
            ['resource' => $r]
        );
    }

    public function testDurationWithAllFields(): void
    {
        $r = $this->makeResourceWithDuration();
        $this->assertParity(
            'Admin/Resources/manage_resources_duration.tpl',
            'Admin/Resources/manage_resources_duration.twig',
            ['resource' => $r]
        );
    }

    public function testDurationMultidayDisabled(): void
    {
        $r = $this->makeResource();
        $r->SetAllowMultiday(false);
        $this->assertParity(
            'Admin/Resources/manage_resources_duration.tpl',
            'Admin/Resources/manage_resources_duration.twig',
            ['resource' => $r]
        );
    }

    // ── manage_resources_access.tpl ───────────────────────────────────────────

    public function testAccessAllDefaults(): void
    {
        $r = $this->makeResource();
        $this->assertParity(
            'Admin/Resources/manage_resources_access.tpl',
            'Admin/Resources/manage_resources_access.twig',
            ['resource' => $r]
        );
    }

    public function testAccessWithAllNotices(): void
    {
        $r = $this->makeResourceWithNotices();
        $this->assertParity(
            'Admin/Resources/manage_resources_access.tpl',
            'Admin/Resources/manage_resources_access.twig',
            ['resource' => $r]
        );
    }

    public function testAccessWithApprovalAndCheckin(): void
    {
        $r = $this->makeResourceAccessEnabled();
        $this->assertParity(
            'Admin/Resources/manage_resources_access.tpl',
            'Admin/Resources/manage_resources_access.twig',
            ['resource' => $r]
        );
    }

    public function testAccessWithAutoAssignAndConcurrent(): void
    {
        $r = $this->makeResourceConcurrent(3);
        $this->assertParity(
            'Admin/Resources/manage_resources_access.tpl',
            'Admin/Resources/manage_resources_access.twig',
            ['resource' => $r]
        );
    }

    // ── manage_resources_groups.tpl ───────────────────────────────────────────

    public function testGroupsNoGroups(): void
    {
        $r = $this->makeResource();
        $this->assertParity(
            'Admin/Resources/manage_resources_groups.tpl',
            'Admin/Resources/manage_resources_groups.twig',
            [
                'resource'          => $r,
                'ResourceGroupList' => [],
            ]
        );
    }

    public function testGroupsWithOneGroup(): void
    {
        $r = $this->makeResourceWithGroups([1]);
        $this->assertParity(
            'Admin/Resources/manage_resources_groups.tpl',
            'Admin/Resources/manage_resources_groups.twig',
            [
                'resource'          => $r,
                'ResourceGroupList' => [
                    1 => $this->makeGroupListItem('Meeting Rooms'),
                ],
            ]
        );
    }

    public function testGroupsWithMultipleGroups(): void
    {
        $r = $this->makeResourceWithGroups([1, 2]);
        $this->assertParity(
            'Admin/Resources/manage_resources_groups.tpl',
            'Admin/Resources/manage_resources_groups.twig',
            [
                'resource'          => $r,
                'ResourceGroupList' => [
                    1 => $this->makeGroupListItem('Meeting Rooms'),
                    2 => $this->makeGroupListItem('Labs'),
                ],
            ]
        );
    }

    // ── manage_resources_group_permissions.tpl ────────────────────────────────

    public function testGroupPermissionsEmpty(): void
    {
        $this->assertParity(
            'Admin/Resources/manage_resources_group_permissions.tpl',
            'Admin/Resources/manage_resources_group_permissions.twig',
            ['Groups' => []]
        );
    }

    public function testGroupPermissionsWithNonePermission(): void
    {
        $g = $this->makeGroupPermission(1, 'Everyone', ResourcePermissionType::None);
        $this->assertParity(
            'Admin/Resources/manage_resources_group_permissions.tpl',
            'Admin/Resources/manage_resources_group_permissions.twig',
            ['Groups' => [$g]]
        );
    }

    public function testGroupPermissionsWithFullPermission(): void
    {
        $g = $this->makeGroupPermission(2, 'Admins', ResourcePermissionType::Full);
        $this->assertParity(
            'Admin/Resources/manage_resources_group_permissions.tpl',
            'Admin/Resources/manage_resources_group_permissions.twig',
            ['Groups' => [$g]]
        );
    }

    public function testGroupPermissionsWithViewPermission(): void
    {
        $g = $this->makeGroupPermission(3, 'Viewers', ResourcePermissionType::View);
        $this->assertParity(
            'Admin/Resources/manage_resources_group_permissions.tpl',
            'Admin/Resources/manage_resources_group_permissions.twig',
            ['Groups' => [$g]]
        );
    }

    public function testGroupPermissionsMultipleGroups(): void
    {
        $groups = [
            $this->makeGroupPermission(1, 'Everyone', ResourcePermissionType::None),
            $this->makeGroupPermission(2, 'Admins', ResourcePermissionType::Full),
            $this->makeGroupPermission(3, 'Viewers', ResourcePermissionType::View),
        ];
        $this->assertParity(
            'Admin/Resources/manage_resources_group_permissions.tpl',
            'Admin/Resources/manage_resources_group_permissions.twig',
            ['Groups' => $groups]
        );
    }

    // ── manage_resources_user_permissions.tpl ─────────────────────────────────

    public function testUserPermissionsEmpty(): void
    {
        $this->assertParity(
            'Admin/Resources/manage_resources_user_permissions.tpl',
            'Admin/Resources/manage_resources_user_permissions.twig',
            ['Users' => []]
        );
    }

    public function testUserPermissionsWithNonePermission(): void
    {
        $u = $this->makeUserPermission(1, 'John', 'Doe', ResourcePermissionType::None);
        $this->assertParity(
            'Admin/Resources/manage_resources_user_permissions.tpl',
            'Admin/Resources/manage_resources_user_permissions.twig',
            ['Users' => [$u]]
        );
    }

    public function testUserPermissionsWithFullPermission(): void
    {
        $u = $this->makeUserPermission(2, 'Jane', 'Smith', ResourcePermissionType::Full);
        $this->assertParity(
            'Admin/Resources/manage_resources_user_permissions.tpl',
            'Admin/Resources/manage_resources_user_permissions.twig',
            ['Users' => [$u]]
        );
    }

    public function testUserPermissionsWithViewPermission(): void
    {
        $u = $this->makeUserPermission(3, 'Bob', 'Jones', ResourcePermissionType::View);
        $this->assertParity(
            'Admin/Resources/manage_resources_user_permissions.tpl',
            'Admin/Resources/manage_resources_user_permissions.twig',
            ['Users' => [$u]]
        );
    }

    public function testUserPermissionsMultipleUsers(): void
    {
        $users = [
            $this->makeUserPermission(1, 'John', 'Doe', ResourcePermissionType::None),
            $this->makeUserPermission(2, 'Jane', 'Smith', ResourcePermissionType::Full),
            $this->makeUserPermission(3, 'Bob', 'Jones', ResourcePermissionType::View),
        ];
        $this->assertParity(
            'Admin/Resources/manage_resources_user_permissions.tpl',
            'Admin/Resources/manage_resources_user_permissions.twig',
            ['Users' => $users]
        );
    }

    // ── manage_resources_public.tpl ───────────────────────────────────────────

    public function testPublicNoneState(): void
    {
        // No subscription, modeEdit=false, IcsEnabled=false → None
        $r = $this->makeResource();
        $this->assertPublicParity(
            'Admin/Resources/manage_resources_public.tpl',
            'Admin/Resources/manage_resources_public.twig',
            [
                'resource'  => $r,
                'modeEdit'  => false,
                'IcsEnabled' => false,
                'ScriptUrl' => 'http://localhost/web',
            ]
        );
    }

    public function testPublicNoSubNoIcsEnabled(): void
    {
        // No subscription, modeEdit=false, IcsEnabled=true → enable subscription button
        $r = $this->makeResource();
        $this->assertPublicParity(
            'Admin/Resources/manage_resources_public.tpl',
            'Admin/Resources/manage_resources_public.twig',
            [
                'resource'  => $r,
                'modeEdit'  => false,
                'IcsEnabled' => true,
                'ScriptUrl' => 'http://localhost/web',
            ]
        );
    }

    public function testPublicWithSubscriptionModeEdit(): void
    {
        // subscription enabled, modeEdit=true → show display link
        $r = $this->makeResourceWithSubscription();
        $this->assertPublicParity(
            'Admin/Resources/manage_resources_public.tpl',
            'Admin/Resources/manage_resources_public.twig',
            [
                'resource'  => $r,
                'modeEdit'  => true,
                'IcsEnabled' => true,
                'ScriptUrl' => 'http://localhost/web',
            ]
        );
    }

    public function testPublicWithSubscriptionNotEditIcsEnabled(): void
    {
        // subscription enabled, modeEdit=false, IcsEnabled=true → full subscription UI
        $r = $this->makeResourceWithSubscription();
        $this->assertPublicParity(
            'Admin/Resources/manage_resources_public.tpl',
            'Admin/Resources/manage_resources_public.twig',
            [
                'resource'  => $r,
                'modeEdit'  => false,
                'IcsEnabled' => true,
                'ScriptUrl' => 'http://localhost/web',
            ]
        );
    }

    public function testPublicWithSubscriptionNotEditIcsDisabled(): void
    {
        // subscription enabled, modeEdit=false, IcsEnabled=false → display link only (no ics links)
        $r = $this->makeResourceWithSubscription();
        $this->assertPublicParity(
            'Admin/Resources/manage_resources_public.tpl',
            'Admin/Resources/manage_resources_public.twig',
            [
                'resource'  => $r,
                'modeEdit'  => false,
                'IcsEnabled' => false,
                'ScriptUrl' => 'http://localhost/web',
            ]
        );
    }
}
