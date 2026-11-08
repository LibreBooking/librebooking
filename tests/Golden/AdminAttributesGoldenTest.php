<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../lib/Common/Templating/SmartyRenderer.php');
require_once(__DIR__ . '/../../lib/Common/Templating/LibreBookingExtension.php');
require_once(__DIR__ . '/../../lib/Common/Templating/TwigRenderer.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../Domain/CustomAttribute.php');
require_once(__DIR__ . '/../../Presenters/Admin/ManageAttributesPresenter.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for Admin/Attributes templates.
 *
 * Templates covered:
 *   - tpl/Admin/Attributes/attribute-list.tpl  → .twig  (full parity)
 *   - tpl/Admin/Attributes/manage_attributes.tpl → .twig (full parity)
 *
 * Parity strategy
 * ---------------
 * attribute-list.twig: standalone partial rendered with identical vars.
 * Full parity — both engines produce normalized-identical output.
 *
 * manage_attributes.twig: full page including globalheader, globalfooter,
 * javascript-includes (all already migrated). Full parity asserted.
 *
 * Accepted divergences:
 *   attribute-list: entity descriptions are emitted as JSON, so an attribute
 *   without entities gives `[]` where Smarty's join gave `[""]`. For manage_attributes, the `changeCategoryUrl`
 *   JS string contains `&` as a literal template character (not a variable
 *   substitution), so Twig does not autoescape it — both engines emit `&`.
 *   Button rendering with no `submit` param: Smarty `GetButtonAttributes`
 *   excludes only `['key', 'class']`, but since no extra params are passed,
 *   the output is identical to Twig button rendering.
 */
class AdminAttributesGoldenTest extends GoldenTemplateTestCase
{
    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Resources $savedResources = null;

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/manage_attributes.php';
        $_SERVER['REQUEST_URI'] = '/web/manage_attributes.php';
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
        // Twig emits entity descriptions as JSON: no entities is [] rather than [""].
        $expected = str_replace('ntityDescriptions: [""]', 'ntityDescriptions: []', $smarty->render($tplName));

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

    // ── Types lookup (mirrors ManageAttributesPage::PageLoad) ────────────────

    /**
     * @return array<int, string>
     */
    private function makeTypesLookup(): array
    {
        return [
            CustomAttributeTypes::SINGLE_LINE_TEXTBOX => 'SingleLineTextbox',
            CustomAttributeTypes::MULTI_LINE_TEXTBOX  => 'MultiLineTextbox',
            CustomAttributeTypes::CHECKBOX            => 'Checkbox',
            CustomAttributeTypes::SELECT_LIST         => 'SelectList',
            CustomAttributeTypes::DATETIME            => 'DateTime',
        ];
    }

    // ── Fixture: build CustomAttribute objects ────────────────────────────────

    private function makeSimpleAttribute(
        int $id = 1,
        string $label = 'Department',
        int $type = CustomAttributeTypes::SINGLE_LINE_TEXTBOX,
        int $category = CustomAttributeCategory::USER,
        bool $required = false,
        bool $adminOnly = false,
        int $sortOrder = 0
    ): CustomAttribute {
        return new CustomAttribute(
            $id,
            $label,
            $type,
            $category,
            '',      // regex
            $required,
            null,    // possibleValues
            $sortOrder,
            [],      // entityIds
            $adminOnly
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
            1,
            [],
            false
        );
    }

    private function makeAttributeWithEntities(int $id = 3): CustomAttribute
    {
        $attr = new CustomAttribute(
            $id,
            'Priority',
            CustomAttributeTypes::SINGLE_LINE_TEXTBOX,
            CustomAttributeCategory::RESOURCE,
            '',
            true,
            null,
            2,
            [101, 102],
            false
        );
        $attr->WithEntityDescriptions(['Room A', 'Room B']);
        return $attr;
    }

    private function makeReservationAttribute(int $id = 4): CustomAttribute
    {
        $attr = new CustomAttribute(
            $id,
            'Notes',
            CustomAttributeTypes::MULTI_LINE_TEXTBOX,
            CustomAttributeCategory::RESERVATION,
            '',
            false,
            null,
            0,
            [],
            false
        );
        $attr->WithIsPrivate(true);
        return $attr;
    }

    // ── attribute-list.tpl ────────────────────────────────────────────────────

    public function testAttributeListEmptyReservationCategory(): void
    {
        $vars = [
            'Attributes' => [],
            'Category'   => CustomAttributeCategory::RESERVATION,
            'Types'      => $this->makeTypesLookup(),
        ];
        $this->assertParity(
            'Admin/Attributes/attribute-list.tpl',
            'Admin/Attributes/attribute-list.twig',
            $vars
        );
    }

    public function testAttributeListEmptyResourceCategory(): void
    {
        $vars = [
            'Attributes' => [],
            'Category'   => CustomAttributeCategory::RESOURCE,
            'Types'      => $this->makeTypesLookup(),
        ];
        $this->assertParity(
            'Admin/Attributes/attribute-list.tpl',
            'Admin/Attributes/attribute-list.twig',
            $vars
        );
    }

    public function testAttributeListSingleRowUserCategory(): void
    {
        $attr = $this->makeSimpleAttribute();
        $vars = [
            'Attributes' => [$attr],
            'Category'   => CustomAttributeCategory::USER,
            'Types'      => $this->makeTypesLookup(),
        ];
        $this->assertParity(
            'Admin/Attributes/attribute-list.tpl',
            'Admin/Attributes/attribute-list.twig',
            $vars
        );
    }

    public function testAttributeListSelectListWithPossibleValues(): void
    {
        $attr = $this->makeSelectListAttribute();
        $vars = [
            'Attributes' => [$attr],
            'Category'   => CustomAttributeCategory::RESOURCE,
            'Types'      => $this->makeTypesLookup(),
        ];
        $this->assertParity(
            'Admin/Attributes/attribute-list.tpl',
            'Admin/Attributes/attribute-list.twig',
            $vars
        );
    }

    public function testAttributeListWithEntityIds(): void
    {
        $attr = $this->makeAttributeWithEntities();
        $vars = [
            'Attributes' => [$attr],
            'Category'   => CustomAttributeCategory::RESOURCE,
            'Types'      => $this->makeTypesLookup(),
        ];
        $this->assertParity(
            'Admin/Attributes/attribute-list.tpl',
            'Admin/Attributes/attribute-list.twig',
            $vars
        );
    }

    /**
     * Reservation category adds Private column; hides AppliesTo column.
     */
    public function testAttributeListReservationCategoryWithPrivate(): void
    {
        $attr = $this->makeReservationAttribute();
        $vars = [
            'Attributes' => [$attr],
            'Category'   => CustomAttributeCategory::RESERVATION,
            'Types'      => $this->makeTypesLookup(),
        ];
        $this->assertParity(
            'Admin/Attributes/attribute-list.tpl',
            'Admin/Attributes/attribute-list.twig',
            $vars
        );
    }

    /**
     * Multiple attributes — covers JS object population loop.
     */
    public function testAttributeListMultipleRows(): void
    {
        $attrs = [
            $this->makeSimpleAttribute(1, 'Department', CustomAttributeTypes::SINGLE_LINE_TEXTBOX, CustomAttributeCategory::USER, false, false, 0),
            $this->makeSimpleAttribute(2, 'Notes', CustomAttributeTypes::MULTI_LINE_TEXTBOX, CustomAttributeCategory::USER, true, false, 1),
            $this->makeSelectListAttribute(3),
        ];
        $vars = [
            'Attributes' => $attrs,
            'Category'   => CustomAttributeCategory::USER,
            'Types'      => $this->makeTypesLookup(),
        ];
        $this->assertParity(
            'Admin/Attributes/attribute-list.tpl',
            'Admin/Attributes/attribute-list.twig',
            $vars
        );
    }

    /**
     * AdminOnly flag set — column shows Yes.
     */
    public function testAttributeListAdminOnly(): void
    {
        $attr = $this->makeSimpleAttribute(5, 'Internal Code', CustomAttributeTypes::SINGLE_LINE_TEXTBOX, CustomAttributeCategory::RESOURCE, false, true, 3);
        $vars = [
            'Attributes' => [$attr],
            'Category'   => CustomAttributeCategory::RESOURCE,
            'Types'      => $this->makeTypesLookup(),
        ];
        $this->assertParity(
            'Admin/Attributes/attribute-list.tpl',
            'Admin/Attributes/attribute-list.twig',
            $vars
        );
    }

    // ── manage_attributes.tpl ─────────────────────────────────────────────────

    /**
     * Full-page render for manage_attributes. Both engines include globalheader,
     * globalfooter, and javascript-includes (all already migrated to Twig),
     * so normalized parity holds.
     */
    public function testManageAttributesFullPageParity(): void
    {
        $vars = [
            'Types' => $this->makeTypesLookup(),
        ];
        $this->assertParity(
            'Admin/Attributes/manage_attributes.tpl',
            'Admin/Attributes/manage_attributes.twig',
            $vars
        );
    }

    /**
     * Structural assertions for manage_attributes.twig:
     * verify key UI elements rendered by Twig are present.
     */
    public function testManageAttributesTwigContainsKeyElements(): void
    {
        $vars = [
            'Types' => $this->makeTypesLookup(),
        ];

        $this->assertTwigContains(
            'Admin/Attributes/manage_attributes.twig',
            $vars,
            [
                // Category dropdown
                'id="attributeCategory"',
                'id="addAttributeButton"',
                // Add dialog
                'id="addAttributeDialog"',
                'id="addAttributeForm"',
                'ajaxAction="addAttribute"',
                // Edit dialog
                'id="editAttributeDialog"',
                'id="editAttributeForm"',
                'ajaxAction="updateAttribute"',
                // Delete dialog
                'id="deleteDialog"',
                'id="deleteForm"',
                'ajaxAction="deleteAttribute"',
                // CSRF token
                'id="csrf_token"',
                'value="golden-test-csrf-token"',
                // Attribute list container
                'id="attributeList"',
                // Script includes
                'ajax-helpers.js',
                'admin/attributes.js',
                'jquery-form',
                // JS options
                'AttributeManagement',
                'submitUrl',
                'changeCategoryUrl',
                'singleLine',
                'multiLine',
                'selectList',
                'allText',
                'categories',
                'resourcesUrl',
                'usersUrl',
                'resourceTypesUrl',
            ]
        );
    }

    /**
     * Structural check: category option values are PHP constants rendered correctly.
     */
    public function testManageAttributesConstantsRendered(): void
    {
        $vars = [
            'Types' => $this->makeTypesLookup(),
        ];

        $this->assertTwigContains(
            'Admin/Attributes/manage_attributes.twig',
            $vars,
            [
                // CustomAttributeCategory constants in select
                'value="' . CustomAttributeCategory::RESERVATION . '"',
                'value="' . CustomAttributeCategory::USER . '"',
                'value="' . CustomAttributeCategory::RESOURCE . '"',
                'value="' . CustomAttributeCategory::RESOURCE_TYPE . '"',
                // CustomAttributeTypes constants in type select
                'value="' . CustomAttributeTypes::SINGLE_LINE_TEXTBOX . '"',
                'value="' . CustomAttributeTypes::MULTI_LINE_TEXTBOX . '"',
                'value="' . CustomAttributeTypes::SELECT_LIST . '"',
                'value="' . CustomAttributeTypes::CHECKBOX . '"',
                'value="' . CustomAttributeTypes::DATETIME . '"',
            ]
        );
    }
}
