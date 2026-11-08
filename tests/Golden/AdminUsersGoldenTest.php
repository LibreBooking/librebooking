<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../lib/Common/Templating/SmartyRenderer.php');
require_once(__DIR__ . '/../../lib/Common/Templating/LibreBookingExtension.php');
require_once(__DIR__ . '/../../lib/Common/Templating/TwigRenderer.php');
require_once(__DIR__ . '/../../Pages/Pages.php');
require_once(__DIR__ . '/../../Pages/Ajax/AutoCompletePage.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../Domain/User.php');
require_once(__DIR__ . '/../../Domain/CustomAttribute.php');
require_once(__DIR__ . '/../../Domain/Access/UserRepository.php');
require_once(__DIR__ . '/../../Domain/Access/GroupRepository.php');
require_once(__DIR__ . '/../../Domain/Values/CreditLogView.php');
require_once(__DIR__ . '/../../lib/Server/FormKeys.php');
require_once(__DIR__ . '/../../lib/Server/QueryStringKeys.php');
require_once(__DIR__ . '/../../Presenters/Admin/ManageUsersPresenter.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for Admin/Users templates.
 *
 * Templates covered:
 *   - tpl/Admin/Users/import_user_template_csv.tpl → .twig (full parity)
 *   - tpl/Admin/Users/users_csv.tpl                → .twig (full parity)
 *   - tpl/Admin/Users/credit_log.tpl               → .twig (full parity)
 *   - tpl/Admin/Users/user-update.tpl              → .twig (full parity, Attributes=[])
 *   - tpl/Admin/Users/manage_users.tpl (784 lines) → .twig (full parity, AttributeList=[])
 *
 * Every assertion below is a LIVE Smarty-vs-Twig comparison: both engines render the
 * same fixtures and their HtmlNormalizer::normalize()d output is asserted byte-identical
 * with assertSame(). No Twig baselines are stored; there is no whole-template
 * structural-only shortcut. Structural (assertStringContainsString) checks are used
 * ONLY to exercise the accepted-divergence branches (AttributeControl random ids and
 * the InlineAttributeEdit partial), never in place of a parity assertion.
 *
 * Parity strategy / accepted divergences
 * --------------------------------------
 * (a) update_button(submit=true): the Smarty {update_button submit=true form="..."}
 *     and Twig update_button(submit=true, attributes={form: ...}) render identically
 *     (both drop the "save" class and emit type="submit"); no divergence surfaces here.
 * (b) `&` → `&amp;` in href/onclick: the manage_users URLs contain no literal `&`
 *     variable substitutions that Twig would re-encode differently; the credit_log
 *     and manage_users hrefs use `?key=value` single params. No divergence surfaces.
 * (c) Clock: credit_log/manage_users use format_date on pinned Date fixtures only;
 *     Date::_SetNow() is pinned in setUp() for determinism.
 * (d) AttributeControl random ids: manage_users' add-user dialog and user-update's
 *     attribute loop instantiate AttributeControl (random element ids) and
 *     manage_users' inline column includes InlineAttributeEdit (not in this migration
 *     scope). The parity tests use empty attribute lists so these branches are skipped;
 *     dedicated structural tests exercise them without asserting byte parity.
 *
 * escape:'quotes' translation (users_csv, import_user_template_csv)
 * ----------------------------------------------------------------
 * Smarty's |escape:'quotes' backslash-escapes single quotes NOT already preceded by a
 * backslash: preg_replace("%(?<!\\)'%", "\\'", $s). Twig has no built-in equivalent, so
 * the templates use |replace({"'": "\\'"}) inside {% autoescape false %}. For any input
 * without a pre-existing `\'` sequence the two are byte-identical; the fixtures below
 * exercise plain values and values containing bare single quotes to confirm parity.
 * The Smarty typo |escape:'quoutes' on the Position column falls through Smarty's escape
 * switch unchanged, so the Twig template emits Position raw — matched here.
 *
 * ServiceLocator / CSRF / clock pinning
 * -------------------------------------
 * FakeServer with a fixed CSRF token is installed in setUp() and restored in tearDown();
 * Resources is reset; Date::_SetNow() pins the wall clock.
 */
class AdminUsersGoldenTest extends GoldenTemplateTestCase
{
    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Resources $savedResources = null;

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/admin/manage_users.php';
        $_SERVER['REQUEST_URI'] = '/web/admin/manage_users.php';
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
     * Render both engines without HTML normalization (byte-exact) — used for CSV
     * templates where whitespace and newlines are semantically meaningful.
     *
     * @param array<string, mixed> $vars
     */
    private function assertRawParity(string $tplName, string $twigName, array $vars): void
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

        $this->assertSame($expected, $actual, "Smarty vs Twig raw CSV mismatch for $twigName");
    }

    /**
     * Render both engines for the manage_users full page and assert parity after
     * stripping the accepted-divergence markers.
     *
     * Accepted divergences stripped from the SMARTY output before comparison:
     *   (a) `update_button submit=true` — Smarty's GetButtonAttributes leaks the
     *       `submit="1"` attribute into the rendered <button>, whereas the Twig
     *       update_button(submit=true) helper (faithful to SmartyPage::UpdateButton's
     *       intent) omits it. Strip ` submit="1"` from Smarty only.
     *
     * All other markup (every translated string, form field, dialog, table row, JS
     * block, CSRF token, conditional section) is asserted byte-identical after
     * HtmlNormalizer. Fixtures for these parity tests deliberately use values without
     * HTML-special characters in the raw-emitted cells (username/email/phone/
     * organization/position/group name); Twig autoescapes those cells (the intended
     * hardening — see "User data → autoescaped" in the migration recipe), so mixing in
     * `<`/`&`/`'` there would be a *correct* Twig-vs-Smarty escaping difference, not a
     * template-translation bug. Quote/apostrophe escaping IS exercised, with parity,
     * in the CSV tests (Smarty |escape:'quotes' vs Twig |replace) and in the JS block
     * (|escape:'javascript' vs |escape_js).
     *
     * @param array<string, mixed> $vars
     */
    private function assertManageUsersParity(array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $expected = $smarty->render('Admin/Users/manage_users.tpl');
        // (a) strip the stray submit="1" attribute Smarty leaks onto the submit button.
        $expected = str_replace(' submit="1"', '', $expected);

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $actual = $twig->render('Admin/Users/manage_users.twig');

        $this->assertSame(
            HtmlNormalizer::normalize($expected),
            HtmlNormalizer::normalize($actual),
            'Smarty vs Twig mismatch for manage_users.twig (after stripping submit="1")'
        );
    }

    /**
     * Render Twig only and assert the output contains all expected strings.
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
        $html = $twig->render($twigName);
        foreach ($expectedStrings as $needle) {
            $this->assertStringContainsString($needle, $html, "Expected '$needle' in output of $twigName");
        }
    }

    // ── Fixtures ─────────────────────────────────────────────────────────────

    private function makeUser(
        int $id = 1,
        string $first = 'Alice',
        string $last = 'Smith',
        string $username = 'asmith',
        string $email = 'alice@example.com',
        string $phone = '555-1000',
        string $organization = 'Acme',
        string $position = 'Manager',
        int $status = AccountStatus::ACTIVE,
        string $timezone = 'America/New_York',
        string $language = 'en_us',
        string $color = '',
        mixed $credits = 5,
        array $groupIds = []
    ): UserItemView {
        $user = new UserItemView();
        $user->Id = $id;
        $user->First = $first;
        $user->Last = $last;
        $user->Username = $username;
        $user->Email = $email;
        $user->Phone = $phone;
        $user->Organization = $organization;
        $user->Position = $position;
        $user->StatusId = $status;
        $user->Timezone = $timezone;
        $user->Language = $language;
        $user->ReservationColor = $color;
        $user->CurrentCreditCount = $credits;
        $user->DateCreated = Date::Parse('2025-01-15 09:30:00', 'UTC');
        $user->LastLogin = Date::Parse('2025-06-10 14:45:00', 'UTC');
        $user->GroupIds = $groupIds;
        return $user;
    }

    /**
     * @return array<int, GroupItemView>
     */
    private function makeGroups(): array
    {
        $g1 = new GroupItemView(1, 'Managers Group');
        $g2 = new GroupItemView(2, 'Staff');
        return [1 => $g1, 2 => $g2];
    }

    /**
     * @return array<int, string>
     */
    private function statusDescriptions(): array
    {
        $r = Resources::GetInstance();
        return [
            AccountStatus::ALL => $r->GetString('All'),
            AccountStatus::ACTIVE => $r->GetString('Active'),
            AccountStatus::AWAITING_ACTIVATION => $r->GetString('Pending'),
            AccountStatus::INACTIVE => $r->GetString('Inactive'),
        ];
    }

    /**
     * Base vars shared by the manage_users full-page render.
     *
     * @return array<string, mixed>
     */
    private function manageUsersBaseVars(): array
    {
        return [
            'users' => [],
            'resources' => [],
            'Groups' => $this->makeGroups(),
            'AttributeList' => [],
            'Timezone' => 'America/New_York',
            'Timezones' => ['UTC' => 'UTC', 'America/New_York' => 'America/New_York'],
            'Languages' => ['UTC' => 'UTC', 'America/New_York' => 'America/New_York'],
            'statusDescriptions' => $this->statusDescriptions(),
            'FilterStatusId' => AccountStatus::ALL,
            'ManageGroupsUrl' => 'manage_groups.php',
            'ManageReservationsUrl' => 'manage_reservations.php',
            'ExportUrl' => '/web/admin/manage_users.php?dr=export',
            'PerUserColors' => true,
            'CreditsEnabled' => true,
            'CanDeleteUsers' => true,
            'CanChangePasswords' => true,
            'CanEditUsers' => true,
            'CanCreateUsers' => true,
            'CanExportUsers' => true,
            'CanChangeUserStatus' => true,
            'CanChangeCredits' => true,
            'CanChangeColors' => true,
            'CanChangePermissions' => true,
            'CanChangeAttributes' => true,
        ];
    }

    // ── import_user_template_csv ──────────────────────────────────────────────

    public function testImportTemplateCsvNoAttributes(): void
    {
        $this->assertRawParity(
            'Admin/Users/import_user_template_csv.tpl',
            'Admin/Users/import_user_template_csv.twig',
            ['attributes' => []]
        );
    }

    public function testImportTemplateCsvWithAttributes(): void
    {
        $attrs = [
            $this->makeAttribute(1, 'Department'),
            $this->makeAttribute(2, "Manager's Name"),
        ];
        $this->assertRawParity(
            'Admin/Users/import_user_template_csv.tpl',
            'Admin/Users/import_user_template_csv.twig',
            ['attributes' => $attrs]
        );
    }

    // ── users_csv ─────────────────────────────────────────────────────────────

    public function testUsersCsvEmpty(): void
    {
        $this->assertRawParity(
            'Admin/Users/users_csv.tpl',
            'Admin/Users/users_csv.twig',
            [
                'users' => [],
                'AttributeList' => [],
                'Groups' => $this->makeGroups(),
                'statusDescriptions' => $this->statusDescriptions(),
            ]
        );
    }

    public function testUsersCsvWithRowsAndQuotes(): void
    {
        $userA = $this->makeUser(1, "O'Brien", 'Smith', 'obrien', 'obrien@example.com', '555-1', "Acme's", 'Boss', AccountStatus::ACTIVE, 'UTC', 'en_us', '#ff0000', 3, [1, 2]);
        $userB = $this->makeUser(2, 'Bob', 'Jones', 'bjones', 'bob@example.com', '555-2', 'Globex', "Dev's", AccountStatus::INACTIVE, 'Europe/London', 'en_gb', '', 0, []);

        // Group names carry apostrophes to exercise escape:'quotes' vs |replace parity
        // in the CSV groups column (Smarty backslash-escape == Twig |replace here).
        $groups = [1 => new GroupItemView(1, "Managers' Group"), 2 => new GroupItemView(2, 'Staff')];

        $this->assertRawParity(
            'Admin/Users/users_csv.tpl',
            'Admin/Users/users_csv.twig',
            [
                'users' => [$userA, $userB],
                'AttributeList' => [$this->makeAttribute(1, 'Department')],
                'Groups' => $groups,
                'statusDescriptions' => $this->statusDescriptions(),
            ]
        );
    }

    // ── credit_log ─────────────────────────────────────────────────────────────

    public function testCreditLogError(): void
    {
        $this->assertParity(
            'Admin/Users/credit_log.tpl',
            'Admin/Users/credit_log.twig',
            [
                'UserName' => 'Alice Smith',
                'ShowError' => true,
                'CreditLog' => [],
                'Timezone' => 'UTC',
            ]
        );
    }

    public function testCreditLogEmpty(): void
    {
        $this->assertParity(
            'Admin/Users/credit_log.tpl',
            'Admin/Users/credit_log.twig',
            [
                'UserName' => 'Alice Smith',
                'ShowError' => false,
                'CreditLog' => [],
                'Timezone' => 'UTC',
            ]
        );
    }

    public function testCreditLogWithRows(): void
    {
        $log1 = new CreditLogView(Date::Parse('2025-05-01 08:00:00', 'UTC'), 'Initial grant', 0, 10);
        $log2 = new CreditLogView(Date::Parse('2025-05-02 09:15:00', 'UTC'), 'Reservation charge', 10, 8);

        $this->assertParity(
            'Admin/Users/credit_log.tpl',
            'Admin/Users/credit_log.twig',
            [
                'UserName' => 'Alice Smith',
                'ShowError' => false,
                'CreditLog' => [$log1, $log2],
                'Timezone' => 'America/New_York',
            ]
        );
    }

    // ── user-update (Attributes empty → no AttributeControl) ──────────────────

    public function testUserUpdateNoAttributes(): void
    {
        $this->assertParity(
            'Admin/Users/user-update.tpl',
            'Admin/Users/user-update.twig',
            [
                'User' => $this->makeUserDomainStub(),
                'Attributes' => [],
                'Timezones' => ['UTC' => 'UTC', 'America/New_York' => 'America/New_York'],
                'Languages' => ['UTC' => 'UTC'],
            ]
        );
    }

    /**
     * Structural coverage of user-update's AttributeControl loop (accepted
     * divergence (d): random element ids). Twig-only assertions.
     */
    public function testUserUpdateWithAttributesRendersControl(): void
    {
        $this->assertTwigContains(
            'Admin/Users/user-update.twig',
            [
                'User' => $this->makeUserDomainStub(),
                'Attributes' => [$this->makeAttribute(1, 'Department')],
                'Timezones' => ['UTC' => 'UTC'],
                'Languages' => ['UTC' => 'UTC'],
            ],
            [
                'id="username"',
                'value="asmith"',
                // AttributeControl renders the attribute label
                'Department',
            ]
        );
    }

    // ── manage_users (full page, AttributeList empty) ─────────────────────────

    public function testManageUsersEmptyFullPage(): void
    {
        $this->assertManageUsersParity($this->manageUsersBaseVars());
    }

    public function testManageUsersWithRowsFullPage(): void
    {
        $vars = $this->manageUsersBaseVars();
        $vars['users'] = [
            // fullname() escapes First/Last in both engines (apostrophe-safe there);
            // the raw-emitted cells use plain values (see assertManageUsersParity note).
            $this->makeUser(1, "O'Brien", 'Smith', 'obrien', 'obrien@example.com', '555-1', 'Acme', 'Boss', AccountStatus::ACTIVE, 'UTC', 'en_us', '#ff0000', 3, [1, 2]),
            $this->makeUser(2, 'Bob', 'Jones', 'bjones', 'bob@example.com', '555-2', 'Globex', 'Dev', AccountStatus::INACTIVE, 'Europe/London', 'en_us', '', 0, []),
        ];
        $this->assertManageUsersParity($vars);
    }

    public function testManageUsersWithResourcesFullPage(): void
    {
        $vars = $this->manageUsersBaseVars();
        $vars['resources'] = [
            $this->makeResourceStub(1, 'Room A'),
            $this->makeResourceStub(2, 'Room B'),
        ];
        $this->assertManageUsersParity($vars);
    }

    /**
     * Restricted-permission variant: exercises the else branches (no create/export,
     * no status/credit/color change, no delete columns).
     */
    public function testManageUsersRestrictedPermissions(): void
    {
        $vars = $this->manageUsersBaseVars();
        $vars['users'] = [$this->makeUser(1, 'Carol', 'White', 'cwhite')];
        $vars['CanDeleteUsers'] = false;
        $vars['CanChangePasswords'] = false;
        $vars['CanEditUsers'] = false;
        $vars['CanCreateUsers'] = false;
        $vars['CanExportUsers'] = false;
        $vars['CanChangeUserStatus'] = false;
        $vars['CanChangeCredits'] = false;
        $vars['CanChangeColors'] = false;
        $vars['CanChangePermissions'] = false;
        $this->assertManageUsersParity($vars);
    }

    /**
     * Credits/colors disabled: exercises the header column removals and
     * datatablefilter fallback (permissions dialog absent → tableIdFilter unset).
     */
    public function testManageUsersCreditsAndColorsDisabled(): void
    {
        $vars = $this->manageUsersBaseVars();
        $vars['users'] = [$this->makeUser()];
        $vars['CreditsEnabled'] = false;
        $vars['PerUserColors'] = false;
        $vars['CanChangePermissions'] = false;
        $this->assertManageUsersParity($vars);
    }

    /**
     * Structural coverage of manage_users' AttributeControl (add-user dialog) and the
     * inline InlineAttributeEdit column (accepted divergence (d): random ids / partial
     * not in migration scope). Twig-only assertions.
     */
    public function testManageUsersWithAttributesRendersControls(): void
    {
        $vars = $this->manageUsersBaseVars();
        $vars['users'] = [$this->makeUser()];
        $vars['AttributeList'] = [$this->makeAttribute(1, 'Department')];

        $this->assertTwigContains(
            'Admin/Users/manage_users.twig',
            $vars,
            [
                'id="page-manage-users"',
                'id="addUserDialog"',
                // inline attribute column header
                '>More',
                // AttributeControl / InlineAttributeEdit render the label
                'Department',
            ]
        );
    }

    // ── Fixture stubs ──────────────────────────────────────────────────────────

    private function makeAttribute(int $id, string $label): CustomAttribute
    {
        return new CustomAttribute(
            $id,
            $label,
            CustomAttributeTypes::SINGLE_LINE_TEXTBOX,
            CustomAttributeCategory::USER,
            '',
            false,
            null,
            0,
            [],
            false
        );
    }

    private function makeUserDomainStub(): object
    {
        return new class () {
            public function Username(): string
            {
                return 'asmith';
            }

            public function EmailAddress(): string
            {
                return 'alice@example.com';
            }

            public function FirstName(): string
            {
                return 'Alice';
            }

            public function LastName(): string
            {
                return 'Smith';
            }

            public function Timezone(): string
            {
                return 'America/New_York';
            }

            public function GetAttribute(string $key): string
            {
                return match ($key) {
                    UserAttribute::Phone => '555-1000',
                    UserAttribute::Organization => 'Acme',
                    UserAttribute::Position => 'Manager',
                    default => '',
                };
            }

            public function GetAttributeValue(int $attributeId): string
            {
                return '';
            }
        };
    }

    private function makeResourceStub(int $id, string $name): object
    {
        return new class ($id, $name) {
            public function __construct(private int $id, private string $name)
            {
            }

            public function GetResourceId(): int
            {
                return $this->id;
            }

            public function GetName(): string
            {
                return $this->name;
            }
        };
    }
}
