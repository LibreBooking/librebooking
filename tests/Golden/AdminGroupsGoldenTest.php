<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../lib/Common/Templating/SmartyRenderer.php');
require_once(__DIR__ . '/../../lib/Common/Templating/LibreBookingExtension.php');
require_once(__DIR__ . '/../../lib/Common/Templating/TwigRenderer.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../Domain/Access/GroupRepository.php');
require_once(__DIR__ . '/../../Presenters/Admin/ManageGroupsPresenter.php');
require_once(__DIR__ . '/../../Pages/Ajax/AutoCompletePage.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for Admin/Groups templates.
 *
 * Templates covered:
 *   - tpl/Admin/Groups/groups_csv.tpl   → .twig (full parity)
 *   - tpl/Admin/Groups/manage_groups.tpl → .twig (parity after stripping submit= attr)
 *
 * Parity strategy
 * ---------------
 * groups_csv: rendered with both engines using identical fixture data.
 * HtmlNormalizer collapses whitespace (including CSV line endings), so the
 * comparison is content-parity after normalization. Full parity asserted.
 *
 * manage_groups: full-page including globalheader/globalfooter (both migrated).
 * CSRF pinned via FakeServer. Clock pinned via Date::_SetNow().
 *
 * Accepted divergences handled by stripping from BOTH outputs before compare:
 *   (a) {update_button submit="true"} → Smarty emits submit="true" as extra attr;
 *       Twig omits it. Three occurrences: permissionsForm, resourceAdminForm,
 *       scheduleAdminForm. Strip: /\s*submit="[^"]*"/.
 *
 * Faithful 1:1 security gap note:
 *   Line 28 of the Smarty template already has rel="noopener noreferrer" on the
 *   export link (target="_blank" to same-origin download URL). The Twig template
 *   preserves this verbatim — no extra hardening added.
 *   The import template download link (same-origin) does NOT have
 *   rel="noopener noreferrer" in the Smarty source; preserved faithfully in Twig.
 */
class AdminGroupsGoldenTest extends GoldenTemplateTestCase
{
    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Resources $savedResources = null;

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/admin/manage_groups.php';
        $_SERVER['REQUEST_URI'] = '/web/admin/manage_groups.php';
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();
        $this->savedServiceLocatorServer = ServiceLocator::GetServer();
        $fakeServer = new FakeServer();
        $fakeServer->UserSession->CSRFToken = 'golden-test-csrf-token';
        $fakeServer->UserSession->UserId = 1;
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
     * Render both engines and assert parity after stripping the stray submit=
     * attribute emitted by Smarty's {update_button submit="true"}.
     *
     * Accepted divergence (a): Smarty's UpdateButton passes the raw params to
     * GetButtonAttributes() which does NOT exclude 'submit', so it emits
     * submit="true" as an extra HTML attribute. The Twig update_button function
     * receives $submit as a typed PHP bool and never re-emits it as an attribute.
     * We strip submit="..." from BOTH outputs before normalization so that all
     * other markup (including form="...", class, label, indicator) is Smarty-verified.
     *
     * @param array<string, mixed> $vars
     */
    private function assertMainPageParity(array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyHtml = $smarty->render('Admin/Groups/manage_groups.tpl');

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigHtml = $twig->render('Admin/Groups/manage_groups.twig');

        // Strip stray submit="..." attribute from BOTH before normalization.
        $smartyHtml = preg_replace('/\s+submit="[^"]*"/', '', $smartyHtml);
        $twigHtml   = preg_replace('/\s+submit="[^"]*"/', '', $twigHtml);

        $this->assertSame(
            HtmlNormalizer::normalize($smartyHtml),
            HtmlNormalizer::normalize($twigHtml),
            'Smarty vs Twig mismatch for manage_groups.twig (after stripping submit= attr)'
        );
    }

    // ── Fixture builders ──────────────────────────────────────────────────────

    /**
     * Build a GroupItemView with method-call support (Id, Name, IsDefault, Roles).
     */
    private function makeGroup(
        int $id,
        string $name,
        ?string $adminGroupName = null,
        int $isDefault = 0,
        array $roles = []
    ): GroupItemView {
        return new GroupItemView($id, $name, $adminGroupName, $isDefault, $roles);
    }

    /**
     * Build an extended-admin group (group admin + resource admin + schedule admin).
     */
    private function makeExtendedAdminGroup(int $id, string $name): GroupItemView
    {
        return new GroupItemView($id, $name, null, 0, [
            RoleLevel::GROUP_ADMIN,
            RoleLevel::RESOURCE_ADMIN,
            RoleLevel::SCHEDULE_ADMIN,
        ]);
    }

    /**
     * Build a RoleDto.
     */
    private function makeRole(int $id, string $name, int $level): RoleDto
    {
        return new RoleDto($id, $name, $level);
    }

    /**
     * Build an anonymous resource object for the permissions / resource-admin dialogs.
     */
    private function makeResource(int $id, string $name): object
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

            public function GetResourceId(): int
            {
                return $this->id;
            }
        };
    }

    /**
     * Build an anonymous schedule object for the schedule-admin dialog.
     */
    private function makeSchedule(int $id, string $name): object
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

    /**
     * Minimal base vars for the main page (CanChangeRoles=false).
     *
     * @return array<string, mixed>
     */
    private function baseVars(): array
    {
        return [
            'groups'          => [],
            'resources'       => [],
            'Roles'           => [],
            'AdminGroups'     => [],
            'Schedules'       => [],
            'chooseText'      => 'Choose...',
            'CanChangeRoles'  => false,
            'CanImportGroups' => false,
            'CanExportGroups' => false,
        ];
    }

    // ── groups_csv.tpl tests ──────────────────────────────────────────────────

    /**
     * CSV with no groups — just the header row.
     */
    public function testGroupsCsvEmpty(): void
    {
        $this->assertParity(
            'Admin/Groups/groups_csv.tpl',
            'Admin/Groups/groups_csv.twig',
            [
                'Groups'           => [],
                'Users'            => [],
                'PermissionsWrite' => [],
                'PermissionsRead'  => [],
            ]
        );
    }

    /**
     * CSV with one plain group (no admin, not default, no members, no permissions).
     */
    public function testGroupsCsvOneGroupNoMembersNoPerms(): void
    {
        $group = $this->makeGroup(1, 'Staff', null, 0, []);

        $this->assertParity(
            'Admin/Groups/groups_csv.tpl',
            'Admin/Groups/groups_csv.twig',
            [
                'Groups'           => [$group],
                'Users'            => [1 => []],
                'PermissionsWrite' => [1 => []],
                'PermissionsRead'  => [1 => []],
            ]
        );
    }

    /**
     * CSV with a default group that has a group-admin name and members.
     */
    public function testGroupsCsvDefaultGroupWithMembers(): void
    {
        $group = $this->makeGroup(2, 'Admins', 'Super Group', 1, []);

        $user1 = new stdClass();
        $user1->Email = 'alice@example.com';

        $user2 = new stdClass();
        $user2->Email = 'bob@example.com';

        $this->assertParity(
            'Admin/Groups/groups_csv.tpl',
            'Admin/Groups/groups_csv.twig',
            [
                'Groups'           => [$group],
                'Users'            => [2 => [$user1, $user2]],
                'PermissionsWrite' => [2 => []],
                'PermissionsRead'  => [2 => []],
            ]
        );
    }

    /**
     * CSV with an app-admin group with full and read-only permissions.
     */
    public function testGroupsCsvAppAdminWithPermissions(): void
    {
        $group = $this->makeGroup(3, 'App Admins', null, 0, [RoleLevel::APPLICATION_ADMIN]);

        // Anonymous permission objects
        $pWrite = new class () {
            public function ResourceName(): string
            {
                return 'Conference Room A';
            }
        };
        $pRead = new class () {
            public function ResourceName(): string
            {
                return 'Lab B';
            }
        };

        $this->assertParity(
            'Admin/Groups/groups_csv.tpl',
            'Admin/Groups/groups_csv.twig',
            [
                'Groups'           => [$group],
                'Users'            => [3 => []],
                'PermissionsWrite' => [3 => [$pWrite]],
                'PermissionsRead'  => [3 => [$pRead]],
            ]
        );
    }

    /**
     * CSV with multiple groups covering various role combinations.
     */
    public function testGroupsCsvMultipleGroups(): void
    {
        $g1 = $this->makeGroup(1, 'Staff', null, 0, []);
        $g2 = $this->makeGroup(2, 'Resource Admins', 'Super Group', 1, [RoleLevel::RESOURCE_ADMIN]);
        $g3 = $this->makeGroup(3, 'Group Admins', null, 0, [RoleLevel::GROUP_ADMIN]);

        $user1 = new stdClass();
        $user1->Email = 'user1@example.com';

        $this->assertParity(
            'Admin/Groups/groups_csv.tpl',
            'Admin/Groups/groups_csv.twig',
            [
                'Groups'           => [$g1, $g2, $g3],
                'Users'            => [1 => [$user1], 2 => [], 3 => []],
                'PermissionsWrite' => [1 => [], 2 => [], 3 => []],
                'PermissionsRead'  => [1 => [], 2 => [], 3 => []],
            ]
        );
    }

    // ── manage_groups.tpl — minimal / import / export flags ──────────────────

    /**
     * Empty groups list, no roles, no import/export, no CanChangeRoles.
     */
    public function testManageGroupsEmptyNoFeatures(): void
    {
        $this->assertMainPageParity($this->baseVars());
    }

    /**
     * CanExportGroups=true: export link visible.
     */
    public function testManageGroupsWithExport(): void
    {
        $vars = array_merge($this->baseVars(), ['CanExportGroups' => true]);
        $this->assertMainPageParity($vars);
    }

    /**
     * CanImportGroups=true: import button and dialog visible.
     */
    public function testManageGroupsWithImport(): void
    {
        $vars = array_merge($this->baseVars(), ['CanImportGroups' => true]);
        $this->assertMainPageParity($vars);
    }

    /**
     * Both import and export enabled.
     */
    public function testManageGroupsWithImportAndExport(): void
    {
        $vars = array_merge($this->baseVars(), [
            'CanImportGroups' => true,
            'CanExportGroups' => true,
        ]);
        $this->assertMainPageParity($vars);
    }

    // ── manage_groups.tpl — group rows (CanChangeRoles=false) ────────────────

    /**
     * Single plain group, default=false, no admin name.
     */
    public function testManageGroupsOneGroupNotDefault(): void
    {
        $vars = array_merge($this->baseVars(), [
            'groups' => [$this->makeGroup(1, 'Staff', null, 0)],
        ]);
        $this->assertMainPageParity($vars);
    }

    /**
     * Single group that is default (auto-add). Admin name shown.
     */
    public function testManageGroupsOneGroupDefaultWithAdmin(): void
    {
        $vars = array_merge($this->baseVars(), [
            'groups'     => [$this->makeGroup(2, 'Members', 'Managers', 1)],
            'chooseText' => 'Choose...',
        ]);
        $this->assertMainPageParity($vars);
    }

    /**
     * Multiple groups covering default/non-default.
     */
    public function testManageGroupsMultipleGroups(): void
    {
        $vars = array_merge($this->baseVars(), [
            'groups' => [
                $this->makeGroup(1, 'Staff', null, 0),
                $this->makeGroup(2, 'Managers', 'Admins', 1),
                $this->makeGroup(3, 'Guests', null, 0),
            ],
        ]);
        $this->assertMainPageParity($vars);
    }

    // ── manage_groups.tpl — CanChangeRoles=true (roles/admin dialogs) ────────

    /**
     * CanChangeRoles with a plain group (not extended admin).
     */
    public function testManageGroupsCanChangeRolesPlainGroup(): void
    {
        $role = $this->makeRole(1, 'Application Administrator', RoleLevel::APPLICATION_ADMIN);
        $vars = array_merge($this->baseVars(), [
            'CanChangeRoles' => true,
            'groups'         => [$this->makeGroup(1, 'Staff', null, 0)],
            'Roles'          => [$role],
            'AdminGroups'    => [$this->makeGroup(10, 'Admins')],
        ]);
        $this->assertMainPageParity($vars);
    }

    /**
     * CanChangeRoles with an extended-admin group — shows dropdown with sub-links.
     */
    public function testManageGroupsCanChangeRolesExtendedAdmin(): void
    {
        $role1 = $this->makeRole(1, 'Group Admin', RoleLevel::GROUP_ADMIN);
        $role2 = $this->makeRole(2, 'Resource Admin', RoleLevel::RESOURCE_ADMIN);
        $role3 = $this->makeRole(3, 'Schedule Admin', RoleLevel::SCHEDULE_ADMIN);
        $vars = array_merge($this->baseVars(), [
            'CanChangeRoles' => true,
            'groups'         => [$this->makeExtendedAdminGroup(5, 'Power Users')],
            'Roles'          => [$role1, $role2, $role3],
            'AdminGroups'    => [$this->makeGroup(10, 'Admins')],
        ]);
        $this->assertMainPageParity($vars);
    }

    /**
     * CanChangeRoles with resources populated (permissions + resource-admin dialogs).
     */
    public function testManageGroupsCanChangeRolesWithResources(): void
    {
        $resources = [
            $this->makeResource(1, 'Room A'),
            $this->makeResource(2, 'Lab B'),
        ];
        $vars = array_merge($this->baseVars(), [
            'CanChangeRoles' => true,
            'groups'         => [$this->makeGroup(1, 'Staff', null, 0)],
            'resources'      => $resources,
            'Roles'          => [],
            'AdminGroups'    => [],
        ]);
        $this->assertMainPageParity($vars);
    }

    /**
     * CanChangeRoles with schedules populated (schedule-admin dialog).
     */
    public function testManageGroupsCanChangeRolesWithSchedules(): void
    {
        $schedules = [
            $this->makeSchedule(10, 'Main Schedule'),
            $this->makeSchedule(20, 'After Hours'),
        ];
        $vars = array_merge($this->baseVars(), [
            'CanChangeRoles' => true,
            'groups'         => [$this->makeGroup(1, 'Staff', null, 0)],
            'Schedules'      => $schedules,
            'Roles'          => [],
            'AdminGroups'    => [],
        ]);
        $this->assertMainPageParity($vars);
    }

    /**
     * Full combination: all features enabled, mixed groups, roles, resources, schedules.
     */
    public function testManageGroupsFullCombination(): void
    {
        $role1 = $this->makeRole(1, 'Group Admin', RoleLevel::GROUP_ADMIN);
        $role2 = $this->makeRole(2, 'Resource Admin', RoleLevel::RESOURCE_ADMIN);
        $vars = array_merge($this->baseVars(), [
            'CanChangeRoles'  => true,
            'CanImportGroups' => true,
            'CanExportGroups' => true,
            'groups'          => [
                $this->makeGroup(1, 'Staff', null, 0),
                $this->makeExtendedAdminGroup(2, 'Admins'),
            ],
            'resources'       => [
                $this->makeResource(1, 'Room A'),
                $this->makeResource(2, 'Lab B'),
            ],
            'Roles'           => [$role1, $role2],
            'AdminGroups'     => [$this->makeGroup(5, 'Super Admins')],
            'Schedules'       => [$this->makeSchedule(10, 'Main Schedule')],
            'chooseText'      => 'Choose...',
        ]);
        $this->assertMainPageParity($vars);
    }
}
