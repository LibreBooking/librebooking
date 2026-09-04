<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../lib/Common/Templating/SmartyRenderer.php');
require_once(__DIR__ . '/../../lib/Common/Templating/LibreBookingExtension.php');
require_once(__DIR__ . '/../../lib/Common/Templating/TwigRenderer.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../Domain/CustomAttribute.php');
require_once(__DIR__ . '/../../Domain/Announcement.php');
require_once(__DIR__ . '/../../Domain/Access/QuotaRepository.php');
require_once(__DIR__ . '/../../Pages/Pages.php');
require_once(__DIR__ . '/../../Presenters/Admin/ManageAccessoriesPresenter.php');
require_once(__DIR__ . '/../../Presenters/Admin/ManageAnnouncementsPresenter.php');
require_once(__DIR__ . '/../../Presenters/Admin/ManageQuotasPresenter.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for Admin root templates.
 *
 * Templates covered:
 *   - tpl/Admin/InlineAttributeEdit.tpl       → .twig (parity with accepted divergences)
 *   - tpl/Admin/manage_accessories.tpl         → .twig (parity with accepted divergences)
 *   - tpl/Admin/manage_announcements.tpl       → .twig (parity with accepted divergences)
 *   - tpl/Admin/manage_quotas.tpl              → .twig (parity after stripping data-default)
 *
 * Parity strategy
 * ---------------
 * manage_accessories: Full parity after stripping the stray submit="1" attribute
 *   emitted by Smarty's {update_button submit="true"} but not by Twig's update_button()
 *   (accepted divergence (a)).
 *
 * manage_announcements: Parity with two accepted divergences:
 *   (a) submit="1" stray attribute on update_button
 *   (b) `{translate key={Pages::NameFromId(...)}}` — Smarty calls the static method
 *       inline; Twig uses an inline lookup map `{1:'Dashboard',...}` producing identical
 *       translated output for all valid DisplayPage values (1 and 5).
 *   Rich-text announcement cell: both engines pass through sanitize_rich_text;
 *   regression tested via assertTwigContains.
 *   JS escape chain in addAnnouncement(): Smarty uses |escape:"quotes"|regex_replace;
 *   Twig uses |escapequotes|replace — both produce the same escaping for typical content.
 *
 * manage_quotas: Parity after stripping nondeterministic `data-default` attributes
 *   (clock-based H:00 values from now()/Date::Now()). Both engines use the live clock;
 *   Date::_SetNow() does not affect these calls.
 *   translate() with HTML-snippet args: Twig passes captured {% set %} blocks as args
 *   (marked raw at output); Smarty passes {capture} blocks. Both engines produce
 *   functionally identical HTML, normalised to the same output.
 *
 * InlineAttributeEdit: Parity with accepted divergences:
 *   (c) DatePickerSetupControl renders a control with a random id suffix; both engines
 *       share the same PHP random-id generation so they match for a given request, but
 *       the test strips `id="flatpickr-*"` / `for="flatpickr-*"` from both before compare.
 *
 * Security gaps noted (not fixed per ADDITIVE ONLY rule):
 *   - `{update_button form="accessoryResourcesForm" submit="true"}` in accessories
 *     emits submit="1" in Smarty output only — Twig button omits this unknown attribute.
 */
class AdminRootGoldenTest extends GoldenTemplateTestCase
{
    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Resources $savedResources = null;

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/admin/manage_accessories.php';
        $_SERVER['REQUEST_URI'] = '/web/admin/manage_accessories.php';
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
     * Render both Smarty and Twig and assert parity after stripping submit="1"
     * (stray attr from Smarty's {update_button submit="true"}) from BOTH outputs.
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

        // Strip stray submit="true"/"1" attr from BOTH — Smarty's {update_button submit="true"}
        // emits it via AppendAttributes (any unknown param becomes an HTML attribute);
        // Twig's update_button() absorbs `submit` as a named PHP parameter and does not
        // emit it as an HTML attribute (accepted divergence (a)).
        $smartyHtml = preg_replace('/\s+submit="(?:true|1)"/', '', $smartyHtml);
        $twigHtml   = preg_replace('/\s+submit="(?:true|1)"/', '', $twigHtml);

        $this->assertSame(
            HtmlNormalizer::normalize($smartyHtml),
            HtmlNormalizer::normalize($twigHtml),
            "Smarty vs Twig mismatch for $twigName (after stripping submit attr)"
        );
    }

    /**
     * Render both engines for manage_quotas and assert parity after stripping
     * nondeterministic data-default attributes (clock-based H:00 values).
     *
     * @param array<string, mixed> $vars
     */
    private function assertQuotasPageParity(array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyHtml = $smarty->render('Admin/manage_quotas.tpl');

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigHtml = $twig->render('Admin/manage_quotas.twig');

        // Strip data-default="..." (clock-based H:00 values) from BOTH outputs before
        // normalization. Both engines use the live PHP clock; Date::_SetNow() does not
        // affect these values (accepted divergence: clock dependency).
        $smartyHtml = preg_replace('/\s+data-default=\'[^\']*\'/', '', $smartyHtml);
        $twigHtml   = preg_replace('/\s+data-default=\'[^\']*\'/', '', $twigHtml);

        $this->assertSame(
            HtmlNormalizer::normalize($smartyHtml),
            HtmlNormalizer::normalize($twigHtml),
            'Smarty vs Twig mismatch for manage_quotas.twig (after stripping data-default)'
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

    // ── Fixture factories ─────────────────────────────────────────────────────

    /** @return object */
    private function makeResource(int $id = 1, string $name = 'Room A')
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

    /** @return object */
    private function makeGroup(int $id = 1, string $name = 'Admins')
    {
        return new class ($id, $name) {
            public int $Id;
            public string $Name;

            public function __construct(int $id, string $name)
            {
                $this->Id = $id;
                $this->Name = $name;
            }
        };
    }

    /** @return object */
    private function makeAccessory(int $id = 1, string $name = 'Projector', ?int $quantity = null, int $associated = 0)
    {
        return new class ($id, $name, $quantity, $associated) {
            public int $Id;
            public string $Name;
            public ?int $QuantityAvailable;
            public int $AssociatedResources;

            public function __construct(int $id, string $name, ?int $quantity, int $associated)
            {
                $this->Id = $id;
                $this->Name = $name;
                $this->QuantityAvailable = $quantity;
                $this->AssociatedResources = $associated;
            }
        };
    }

    private function makeAnnouncement(
        int $id = 1,
        string $text = 'Hello world',
        string $start = '2025-06-01 00:00:00',
        string $end = '2025-06-30 00:00:00',
        int $priority = 1,
        array $groupIds = [],
        array $resourceIds = [],
        int $displayPage = 1,
        bool $canEmail = false
    ): Announcement {
        $a = new Announcement(
            $id,
            $text,
            Date::Parse($start, 'UTC'),
            Date::Parse($end, 'UTC'),
            $priority,
            $groupIds,
            $resourceIds,
            $displayPage
        );
        if ($canEmail) {
            // CanEmail() is true when groupIds or resourceIds are not empty.
            // (Already set via constructor.) Nothing extra needed.
        }
        return $a;
    }

    private function makeQuotaAllDay(int $id = 1, string $unit = 'hours', string $duration = 'day'): QuotaItemView
    {
        return new QuotaItemView(
            $id,
            10,
            $unit,
            $duration,
            '',           // groupName → AllGroups
            '',           // resourceName → AllResources
            '',           // scheduleName → AllSchedules
            '',           // enforcedStartTime (empty → AllDay)
            '',           // enforcedEndTime
            [],           // enforcedDays (empty → Everyday)
            QuotaScope::IncludeCompleted
        );
    }

    private function makeQuotaTimed(int $id = 2): QuotaItemView
    {
        return new QuotaItemView(
            $id,
            5,
            QuotaUnit::Reservations,
            QuotaDuration::Week,
            'Faculty',
            'Lab A',
            'Main Schedule',
            '09:00',
            '17:00',
            [1, 3, 5],    // Mon, Wed, Fri
            QuotaScope::ExcludeCompleted
        );
    }

    private function makeSimpleCustomAttribute(
        int $id = 1,
        string $label = 'Department',
        int $type = CustomAttributeTypes::SINGLE_LINE_TEXTBOX
    ): CustomAttribute {
        return new CustomAttribute(
            $id,
            $label,
            $type,
            CustomAttributeCategory::USER,
            '',
            false,
            null,
            0,
            [],
            false
        );
    }

    private function makeSelectListAttribute(int $id = 2): CustomAttribute
    {
        return new CustomAttribute(
            $id,
            'Room Type',
            CustomAttributeTypes::SELECT_LIST,
            CustomAttributeCategory::RESOURCE,
            '',
            false,
            'Small,Medium,Large',
            0,
            [],
            false
        );
    }

    // ── manage_accessories ────────────────────────────────────────────────────

    /**
     * @return array<string, mixed>
     */
    private function makeAccessoriesVars(bool $withRows = false): array
    {
        $accessories = [];
        $resources = [];
        if ($withRows) {
            $accessories = [
                $this->makeAccessory(1, 'Projector', null, 0),   // unlimited
                $this->makeAccessory(2, 'Whiteboard', 3, 2),
            ];
            $resources = [
                $this->makeResource(10, 'Conference Room'),
                $this->makeResource(11, 'Lab'),
            ];
        }
        return [
            'accessories' => $accessories,
            'resources' => $resources,
        ];
    }

    public function testManageAccessoriesEmpty(): void
    {
        $this->assertParityNoSubmit(
            'Admin/manage_accessories.tpl',
            'Admin/manage_accessories.twig',
            $this->makeAccessoriesVars(false)
        );
    }

    public function testManageAccessoriesWithRows(): void
    {
        $this->assertParityNoSubmit(
            'Admin/manage_accessories.tpl',
            'Admin/manage_accessories.twig',
            $this->makeAccessoriesVars(true)
        );
    }

    /**
     * Structural check: key UI elements present in manage_accessories.twig.
     */
    public function testManageAccessoriesTwigContainsKeyElements(): void
    {
        $this->assertTwigContains(
            'Admin/manage_accessories.twig',
            $this->makeAccessoriesVars(true),
            [
                'id="page-manage-accessories"',
                'id="addForm"',
                'id="deleteDialog"',
                'id="editDialog"',
                'id="accessoryResourcesDialog"',
                'id="csrf_token"',
                'value="golden-test-csrf-token"',
                'AccessoryManagement',
                'accessoryManagement.addAccessory',
                'Projector',
                'Conference Room',
            ]
        );
    }

    // ── manage_announcements ──────────────────────────────────────────────────

    /**
     * @return array<string, mixed>
     */
    private function makeAnnouncementsVars(bool $withRows = false, bool $withCanEmail = false): array
    {
        $announcements = [];
        $groups = [];
        $resources = [];
        $timezone = 'UTC';

        if ($withRows) {
            $group1 = $this->makeGroup(1, 'Staff');
            $group2 = $this->makeGroup(2, 'Faculty');
            $groups = [1 => $group1, 2 => $group2];

            $resource1 = $this->makeResource(10, 'Seminar Room');
            $resources = [10 => $resource1];

            // Simple announcement: dashboard page, no email
            $ann1 = $this->makeAnnouncement(1, 'System maintenance tonight', '2025-06-10 00:00:00', '2025-06-11 00:00:00', 1, [], [], 1, false);
            $announcements[] = $ann1;

            if ($withCanEmail) {
                // Announcement with groups and resources (CanEmail → true because groupIds populated)
                $ann2 = $this->makeAnnouncement(2, 'Welcome back!', '2025-06-01 00:00:00', '2025-06-30 00:00:00', 5, [1, 2], [10], 5, true);
                $announcements[] = $ann2;
            }
        }

        return [
            'announcements' => $announcements,
            'Groups' => $groups,
            'Resources' => $resources,
            'timezone' => $timezone,
        ];
    }

    public function testManageAnnouncementsEmpty(): void
    {
        $this->assertParityNoSubmit(
            'Admin/manage_announcements.tpl',
            'Admin/manage_announcements.twig',
            $this->makeAnnouncementsVars(false)
        );
    }

    public function testManageAnnouncementsWithRowsNoEmail(): void
    {
        $this->assertParityNoSubmit(
            'Admin/manage_announcements.tpl',
            'Admin/manage_announcements.twig',
            $this->makeAnnouncementsVars(true, false)
        );
    }

    public function testManageAnnouncementsWithCanEmail(): void
    {
        $this->assertParityNoSubmit(
            'Admin/manage_announcements.tpl',
            'Admin/manage_announcements.twig',
            $this->makeAnnouncementsVars(true, true)
        );
    }

    /**
     * Structural check: key UI elements present in manage_announcements.twig.
     */
    public function testManageAnnouncementsTwigContainsKeyElements(): void
    {
        $this->assertTwigContains(
            'Admin/manage_announcements.twig',
            $this->makeAnnouncementsVars(true, true),
            [
                'id="page-manage-announcements"',
                'id="addForm"',
                'id="deleteDialog"',
                'id="editDialog"',
                'id="emailDialog"',
                'id="csrf_token"',
                'value="golden-test-csrf-token"',
                'announcementManagement.addAnnouncement',
                'AnnouncementManagement',
                // CanEmail branch rendered
                'bi-envelope icon',
            ]
        );
    }

    // ── manage_quotas ─────────────────────────────────────────────────────────

    /**
     * @return array<string, mixed>
     */
    private function makeQuotasVars(bool $withQuotas = false): array
    {
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

        $resource = $this->makeResource(1, 'Conference Room');
        $group = $this->makeGroup(1, 'Staff');

        return [
            'Schedules' => [$schedule],
            'Resources' => [$resource],
            'Groups' => [$group],
            'Quotas' => $withQuotas ? [$this->makeQuotaAllDay(), $this->makeQuotaTimed()] : [],
            'DayNames' => [
                0 => 'DaySundayAbbr',
                1 => 'DayMondayAbbr',
                2 => 'DayTuesdayAbbr',
                3 => 'DayWednesdayAbbr',
                4 => 'DayThursdayAbbr',
                5 => 'DayFridayAbbr',
                6 => 'DaySaturdayAbbr',
            ],
            'TimeFormat' => Resources::GetInstance()->GetDateFormat('period_time'),
        ];
    }

    public function testManageQuotasEmpty(): void
    {
        $this->assertQuotasPageParity($this->makeQuotasVars(false));
    }

    public function testManageQuotasWithAllDayQuota(): void
    {
        $this->assertQuotasPageParity($this->makeQuotasVars(true));
    }

    /**
     * Structural check: key UI elements in manage_quotas.twig.
     */
    public function testManageQuotasTwigContainsKeyElements(): void
    {
        $this->assertTwigContains(
            'Admin/manage_quotas.twig',
            $this->makeQuotasVars(true),
            [
                'id="page-manage-quotas"',
                'id="addQuotaForm"',
                'id="deleteDialog"',
                'id="csrf_token"',
                'value="golden-test-csrf-token"',
                'QuotaManagement',
                'quotaManagement.init',
                // QuotaUnit constants rendered
                QuotaUnit::Hours,
                QuotaUnit::Reservations,
                // QuotaDuration constants rendered
                QuotaDuration::Day,
                QuotaDuration::Week,
                QuotaDuration::Month,
                QuotaDuration::Year,
                // QuotaScope constants rendered
                QuotaScope::IncludeCompleted,
                QuotaScope::ExcludeCompleted,
                // Quota list renders
                'class="quotaItem clearfix"',
            ]
        );
    }

    // ── InlineAttributeEdit ───────────────────────────────────────────────────

    /**
     * When the attribute does not apply to the entity, both engines render nothing.
     */
    public function testInlineAttributeEditNotApplicable(): void
    {
        // An attribute with entityIds=[99] does NOT apply to entity id=1
        $attr = new CustomAttribute(
            1,
            'Department',
            CustomAttributeTypes::SINGLE_LINE_TEXTBOX,
            CustomAttributeCategory::RESOURCE,
            '',
            false,
            null,
            0,
            [99],    // only applies to entity 99
            false
        );

        $vars = ['attribute' => $attr, 'id' => 1, 'value' => '', 'url' => '/ajax/inline.php'];
        $this->assertParity('Admin/InlineAttributeEdit.tpl', 'Admin/InlineAttributeEdit.twig', $vars);
    }

    /**
     * Text-type attribute that applies to the entity.
     */
    public function testInlineAttributeEditText(): void
    {
        $attr = $this->makeSimpleCustomAttribute(1, 'Department', CustomAttributeTypes::SINGLE_LINE_TEXTBOX);
        $vars = ['attribute' => $attr, 'id' => 42, 'value' => 'Engineering', 'url' => '/ajax/inline.php'];
        $this->assertParity('Admin/InlineAttributeEdit.tpl', 'Admin/InlineAttributeEdit.twig', $vars);
    }

    /**
     * Multi-line textarea type.
     */
    public function testInlineAttributeEditTextarea(): void
    {
        $attr = $this->makeSimpleCustomAttribute(2, 'Notes', CustomAttributeTypes::MULTI_LINE_TEXTBOX);
        $vars = ['attribute' => $attr, 'id' => 42, 'value' => 'Some notes here', 'url' => '/ajax/inline.php'];
        $this->assertParity('Admin/InlineAttributeEdit.tpl', 'Admin/InlineAttributeEdit.twig', $vars);
    }

    /**
     * Checkbox type.
     */
    public function testInlineAttributeEditCheckbox(): void
    {
        $attr = $this->makeSimpleCustomAttribute(3, 'Approved', CustomAttributeTypes::CHECKBOX);
        $vars = ['attribute' => $attr, 'id' => 42, 'value' => '1', 'url' => '/ajax/inline.php'];
        $this->assertParity('Admin/InlineAttributeEdit.tpl', 'Admin/InlineAttributeEdit.twig', $vars);
    }

    /**
     * Select list with possible values.
     */
    public function testInlineAttributeEditSelectList(): void
    {
        $attr = $this->makeSelectListAttribute(4);
        $vars = ['attribute' => $attr, 'id' => 42, 'value' => 'Medium', 'url' => '/ajax/inline.php'];
        $this->assertParity('Admin/InlineAttributeEdit.tpl', 'Admin/InlineAttributeEdit.twig', $vars);
    }

    /**
     * Select list — required (no empty option emitted).
     */
    public function testInlineAttributeEditSelectListRequired(): void
    {
        $attr = new CustomAttribute(
            5,
            'Priority',
            CustomAttributeTypes::SELECT_LIST,
            CustomAttributeCategory::USER,
            '',
            true,    // required — no empty option
            'Low,Medium,High',
            0,
            [],
            false
        );
        $vars = ['attribute' => $attr, 'id' => 42, 'value' => 'Low', 'url' => '/ajax/inline.php'];
        $this->assertParity('Admin/InlineAttributeEdit.tpl', 'Admin/InlineAttributeEdit.twig', $vars);
    }

    /**
     * Datetime type with empty value (shows dash).
     * The DatePickerSetupControl emits a random flatpickr id; strip id/for attrs containing
     * 'flatpickr' from both outputs before compare so the random suffix doesn't break parity.
     */
    public function testInlineAttributeEditDatetimeEmpty(): void
    {
        $attr = $this->makeSimpleCustomAttribute(6, 'Expiry', CustomAttributeTypes::DATETIME);
        $vars = ['attribute' => $attr, 'id' => 42, 'value' => '', 'url' => '/ajax/inline.php'];

        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyHtml = $smarty->render('Admin/InlineAttributeEdit.tpl');

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigHtml = $twig->render('Admin/InlineAttributeEdit.twig');

        // Strip random flatpickr ids from BOTH outputs before normalization.
        $strip = static function (string $html): string {
            $html = preg_replace('/\s+id="flatpickr-[^"]*"/', '', $html);
            $html = preg_replace('/\s+for="flatpickr-[^"]*"/', '', $html);
            return (string) $html;
        };

        $this->assertSame(
            HtmlNormalizer::normalize($strip($smartyHtml)),
            HtmlNormalizer::normalize($strip($twigHtml)),
            'Smarty vs Twig mismatch for InlineAttributeEdit.twig (datetime empty, after stripping flatpickr ids)'
        );
    }

    /**
     * Datetime type with a populated value (shows formatted date).
     */
    public function testInlineAttributeEditDatetimeWithValue(): void
    {
        $attr = $this->makeSimpleCustomAttribute(7, 'Expiry', CustomAttributeTypes::DATETIME);
        $dateValue = Date::Parse('2025-06-15 10:00:00', 'UTC');
        $vars = ['attribute' => $attr, 'id' => 42, 'value' => $dateValue, 'url' => '/ajax/inline.php'];

        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyHtml = $smarty->render('Admin/InlineAttributeEdit.tpl');

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigHtml = $twig->render('Admin/InlineAttributeEdit.twig');

        $strip = static function (string $html): string {
            $html = preg_replace('/\s+id="flatpickr-[^"]*"/', '', $html);
            $html = preg_replace('/\s+for="flatpickr-[^"]*"/', '', $html);
            return (string) $html;
        };

        $this->assertSame(
            HtmlNormalizer::normalize($strip($smartyHtml)),
            HtmlNormalizer::normalize($strip($twigHtml)),
            'Smarty vs Twig mismatch for InlineAttributeEdit.twig (datetime with value, after stripping flatpickr ids)'
        );
    }
}
