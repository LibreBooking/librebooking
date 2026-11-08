<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../lib/Common/Templating/SmartyRenderer.php');
require_once(__DIR__ . '/../../lib/Common/Templating/LibreBookingExtension.php');
require_once(__DIR__ . '/../../lib/Common/Templating/TwigRenderer.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../Domain/CustomAttribute.php');
require_once(__DIR__ . '/../../Domain/RepeatOptions.php');
require_once(__DIR__ . '/../../Controls/Control.php');
require_once(__DIR__ . '/../../Controls/CheckboxControl.php');
require_once(__DIR__ . '/../../Controls/DatePickerSetupControl.php');
require_once(__DIR__ . '/../../Controls/RecurrenceControl.php');
require_once(__DIR__ . '/../../Controls/AttributeControl.php');
require_once(__DIR__ . '/../../lib/Application/Attributes/Attribute.php');

use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Live Smarty-vs-Twig golden comparison for Controls templates.
 *
 * Templates covered:
 *   - tpl/Controls/Checkbox.tpl              → .twig  (full parity)
 *   - tpl/Controls/DatePickerSetup.tpl       → .twig  (full parity)
 *   - tpl/Controls/DateSetup.tpl             → .twig  (full parity)
 *   - tpl/Controls/RecurrenceDiv.tpl         → .twig  (full parity)
 *   - tpl/Controls/Attributes/Checkbox.tpl   → .twig  (full parity)
 *   - tpl/Controls/Attributes/Date.tpl       → .twig  (structural — DatePickerSetupControl output)
 *   - tpl/Controls/Attributes/MultiLineTextbox.tpl  → .twig  (full parity)
 *   - tpl/Controls/Attributes/SelectList.tpl → .twig  (full parity)
 *   - tpl/Controls/Attributes/SingleLineTextbox.tpl → .twig (full parity)
 *
 * Parity strategy
 * ---------------
 * All templates use full assertParity (normalized byte-equal) except:
 *
 *   - Attributes/Date.twig: invokes DatePickerSetupControl internally,
 *     which depends on Resources date formats. We use real Resources (reset in
 *     setUp) so output IS deterministic and full parity is asserted.
 *
 * Note on Controls/Checkbox.twig: the label variable is a translated string
 * (plain text from Resources). Smarty does not escape it; Twig autoescapes and
 * then |raw is applied in the .twig file. Since the value is plain text with no
 * HTML special chars, HtmlNormalizer collapses whitespace identically and parity holds.
 *
 * The $id variable appears in JS and in id attribute. Both engines render it
 * identically (HTML escaping of plain alphanumeric IDs is a no-op).
 */
class ControlsGoldenTest extends GoldenTemplateTestCase
{
    private ?Resources $savedResources = null;

    /** @var array<string, mixed> */
    private array $savedServer = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();
        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/index.php';
        $_SERVER['REQUEST_URI'] = '/web/reservation.php';
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->savedServer;
        Resources::SetInstance($this->savedResources);
        parent::tearDown();
    }

    /**
     * Render both Smarty and Twig with the given vars and assert normalized parity.
     *
     * Both renderers use renderControlTemplate() which:
     * - SmartyRenderer: always renders the .tpl via Smarty
     * - TwigRenderer: renders the .twig file if it exists, else falls back to Smarty
     *
     * @param array<string, mixed> $vars
     */
    private function assertParity(string $tplName, string $twigName, array $vars): void
    {
        $smartyHtml = (new SmartyRenderer())->renderControlTemplate($tplName, $vars);
        $twigHtml = (new TwigRenderer())->renderControlTemplate($tplName, $vars);

        $this->assertSame(
            HtmlNormalizer::normalize($smartyHtml),
            HtmlNormalizer::normalize($twigHtml),
            "Smarty vs Twig mismatch for $twigName"
        );
    }

    /**
     * Render Twig only and assert the output contains expected strings.
     *
     * @param array<string, mixed>  $vars
     * @param string[] $expectedStrings
     */
    private function assertTwigControlContains(string $tplName, array $vars, array $expectedStrings): void
    {
        $html = (new TwigRenderer())->renderControlTemplate($tplName, $vars);
        foreach ($expectedStrings as $needle) {
            $this->assertStringContainsString($needle, $html, "Expected '$needle' in output of $tplName");
        }
    }

    // ── Controls/Checkbox ────────────────────────────────────────────────────

    public function testCheckboxUnchecked(): void
    {
        $vars = [
            'id'    => 'cb-test-1',
            'name'  => 'sendReminder',
            'class' => 'send-reminder',
            'label' => 'Send Reminder',
        ];
        $this->assertParity('Controls/Checkbox.tpl', 'Controls/Checkbox.twig', $vars);
    }

    public function testCheckboxWithExtraClass(): void
    {
        $vars = [
            'id'    => 'allow-participation',
            'name'  => 'allowParticipation',
            'class' => 'allow-participation extra-class',
            'label' => 'Allow Participation',
        ];
        $this->assertParity('Controls/Checkbox.tpl', 'Controls/Checkbox.twig', $vars);
    }

    // ── Controls/DatePickerSetup ─────────────────────────────────────────────

    /**
     * DatePickerSetupControl sets variables then calls Display('Controls/DatePickerSetup.tpl').
     * We test the control rendering via renderControlTemplate with pre-set variables
     * (mimicking what DatePickerSetupControl::PageLoad would have set).
     *
     * Since the JS template content is entirely static/deterministic, full parity holds.
     */
    public function testDatePickerSetupBasic(): void
    {
        // Simulate what DatePickerSetupControl::PageLoad sets on the vars array
        $vars = $this->makeDatePickerVars('startDate', false);
        $this->assertParity('Controls/DatePickerSetup.tpl', 'Controls/DatePickerSetup.twig', $vars);
    }

    public function testDatePickerSetupWithTimepicker(): void
    {
        $vars = $this->makeDatePickerVars('startDateTime', true);
        $this->assertParity('Controls/DatePickerSetup.tpl', 'Controls/DatePickerSetup.twig', $vars);
    }

    public function testDatePickerSetupMultipleMode(): void
    {
        $vars = $this->makeDatePickerVars('multiDate', false);
        $vars['Multiple'] = true;
        $this->assertParity('Controls/DatePickerSetup.tpl', 'Controls/DatePickerSetup.twig', $vars);
    }

    public function testDatePickerSetupWithMinMaxDate(): void
    {
        $vars = $this->makeDatePickerVars('rangeDate', false);
        $vars['MinDate'] = '2025-01-01';
        $vars['MaxDate'] = '2025-12-31';
        $this->assertParity('Controls/DatePickerSetup.tpl', 'Controls/DatePickerSetup.twig', $vars);
    }

    public function testDatePickerSetupWithDefaultDate(): void
    {
        $vars = $this->makeDatePickerVars('prefilledDate', false);
        $vars['DefaultDateJson'] = '"2025-06-15"';
        $this->assertParity('Controls/DatePickerSetup.tpl', 'Controls/DatePickerSetup.twig', $vars);
    }

    public function testDatePickerSetupWithOnSelect(): void
    {
        $vars = $this->makeDatePickerVars('pickDate', false);
        $vars['OnSelect'] = 'function(selectedDates, dateStr) { console.log(dateStr); }';
        $this->assertParity('Controls/DatePickerSetup.tpl', 'Controls/DatePickerSetup.twig', $vars);
    }

    public function testDatePickerSetupInlineMode(): void
    {
        $vars = $this->makeDatePickerVars('inlinePicker', false);
        $vars['Inline'] = true;
        $this->assertParity('Controls/DatePickerSetup.tpl', 'Controls/DatePickerSetup.twig', $vars);
    }

    public function testDatePickerSetupFirstDayMonday(): void
    {
        $vars = $this->makeDatePickerVars('mondayFirst', false);
        $vars['FirstDay'] = 1;
        $this->assertParity('Controls/DatePickerSetup.tpl', 'Controls/DatePickerSetup.twig', $vars);
    }

    public function testDatePickerSetupFirstDayOutOfRange(): void
    {
        // FirstDay = 7 (out of 0-6 range) → no firstDayOfWeek in locale block
        $vars = $this->makeDatePickerVars('picker7', false);
        $vars['FirstDay'] = 7;
        $this->assertParity('Controls/DatePickerSetup.tpl', 'Controls/DatePickerSetup.twig', $vars);
    }

    /**
     * Build the pre-processed vars that DatePickerSetupControl::PageLoad would have set,
     * using fixed deterministic values so both engines produce identical output.
     *
     * @return array<string, mixed>
     */
    private function makeDatePickerVars(string $controlId, bool $hasTimepicker): array
    {
        return [
            'ControlId'       => $controlId,
            'AltId'           => null,
            'Inline'          => false,
            'Multiple'        => false,
            'AltInput'        => true,
            'AltFormatJson'   => '"d.m.Y"',
            'DateFormat'      => $hasTimepicker ? 'Y-m-d H:i' : 'Y-m-d',
            'DefaultDateJson' => 'null',
            'MinDate'         => null,
            'MaxDate'         => null,
            'HasTimepicker'   => $hasTimepicker,
            'Time24Hr'        => $hasTimepicker,
            'OnSelect'        => null,
            'OnClose'         => null,
            'FirstDay'        => 0,
            'DayNamesShort'   => "['Sun','Mon','Tue','Wed','Thu','Fri','Sat']",
            'DayNames'        => "['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']",
            'MonthNames'      => "['January','February','March','April','May','June','July','August','September','October','November','December']",
            'MonthNamesShort' => "['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']",
            'NumberOfMonths'  => 1,
            'ShowWeekNumbers' => false,
        ];
    }

    // ── Controls/DateSetup ───────────────────────────────────────────────────

    public function testDateSetupNoDefaults(): void
    {
        $vars = [
            'ControlId'   => 'startDate',
            'AltId'       => 'startDateAlt',
            'MinDate'     => null,
            'MaxDate'     => null,
            'DefaultDate' => null,
        ];
        $this->assertParity('Controls/DateSetup.tpl', 'Controls/DateSetup.twig', $vars);
    }

    public function testDateSetupWithAltId(): void
    {
        $vars = [
            'ControlId'   => 'endDate',
            'AltId'       => 'endDateHidden',
            'MinDate'     => null,
            'MaxDate'     => null,
            'DefaultDate' => null,
        ];
        $this->assertParity('Controls/DateSetup.tpl', 'Controls/DateSetup.twig', $vars);
    }

    public function testDateSetupWithMinMaxAndDefault(): void
    {
        $vars = [
            'ControlId'   => 'bookDate',
            'AltId'       => '',
            'MinDate'     => Date::Parse('2025-01-01', 'UTC'),
            'MaxDate'     => Date::Parse('2025-12-31', 'UTC'),
            'DefaultDate' => Date::Parse('2025-06-15', 'UTC'),
        ];
        $this->assertParity('Controls/DateSetup.tpl', 'Controls/DateSetup.twig', $vars);
    }

    // ── Controls/RecurrenceDiv ───────────────────────────────────────────────

    private function makeRecurrenceVars(?string $prefix = null): array
    {
        $vars = [
            'RepeatEveryOptions' => range(1, 20),
            'RepeatOptions'      => [
                'none'    => ['key' => 'DoesNotRepeat', 'everyKey' => ''],
                'daily'   => ['key' => 'Daily', 'everyKey' => 'days'],
                'weekly'  => ['key' => 'Weekly', 'everyKey' => 'weeks'],
                'monthly' => ['key' => 'Monthly', 'everyKey' => 'months'],
                'yearly'  => ['key' => 'Yearly', 'everyKey' => 'years'],
                'custom'  => ['key' => 'Custom', 'everyKey' => 'custom'],
            ],
        ];

        if ($prefix !== null) {
            $vars['prefix'] = $prefix;
        }

        return $vars;
    }

    public function testRecurrenceDivNoPrefix(): void
    {
        $this->assertParity(
            'Controls/RecurrenceDiv.tpl',
            'Controls/RecurrenceDiv.twig',
            $this->makeRecurrenceVars()
        );
    }

    public function testRecurrenceDivWithPrefix(): void
    {
        $this->assertParity(
            'Controls/RecurrenceDiv.tpl',
            'Controls/RecurrenceDiv.twig',
            $this->makeRecurrenceVars('blackout')
        );
    }

    // ── Controls/Attributes/Checkbox ────────────────────────────────────────

    private function makeAttrCheckboxVars(bool $readonly, bool $searchmode, string $value, bool $required = false, bool $tooltip = false): array
    {
        $attr = new LBAttribute(
            new CustomAttribute(
                1,
                'Accessible',
                CustomAttributeTypes::CHECKBOX,
                CustomAttributeCategory::RESOURCE,
                '',
                $required,
                null,
                1
            ),
            $value
        );

        return [
            'attribute'     => $attr,
            'attributeId'   => 'attr_cb_1',
            'attributeName' => 'customAttribute[1]',
            'class'         => 'mb-2',
            'readonly'      => $readonly,
            'searchmode'    => $searchmode,
            'tooltip'       => $tooltip,
        ];
    }

    public function testAttrCheckboxEditable(): void
    {
        $vars = $this->makeAttrCheckboxVars(false, false, '');
        $this->assertParity('Controls/Attributes/Checkbox.tpl', 'Controls/Attributes/Checkbox.twig', $vars);
    }

    public function testAttrCheckboxChecked(): void
    {
        $vars = $this->makeAttrCheckboxVars(false, false, '1', true);
        $this->assertParity('Controls/Attributes/Checkbox.tpl', 'Controls/Attributes/Checkbox.twig', $vars);
    }

    public function testAttrCheckboxReadonlyTrue(): void
    {
        $vars = $this->makeAttrCheckboxVars(true, false, '1');
        $this->assertParity('Controls/Attributes/Checkbox.tpl', 'Controls/Attributes/Checkbox.twig', $vars);
    }

    public function testAttrCheckboxReadonlyFalseValue(): void
    {
        $vars = $this->makeAttrCheckboxVars(true, false, '0');
        $this->assertParity('Controls/Attributes/Checkbox.tpl', 'Controls/Attributes/Checkbox.twig', $vars);
    }

    public function testAttrCheckboxReadonlyWithTooltip(): void
    {
        $vars = $this->makeAttrCheckboxVars(true, false, '1', false, true);
        $this->assertParity('Controls/Attributes/Checkbox.tpl', 'Controls/Attributes/Checkbox.twig', $vars);
    }

    public function testAttrCheckboxSearchmode(): void
    {
        $vars = $this->makeAttrCheckboxVars(false, true, '1');
        $this->assertParity('Controls/Attributes/Checkbox.tpl', 'Controls/Attributes/Checkbox.twig', $vars);
    }

    public function testAttrCheckboxSearchmodeNoSelection(): void
    {
        $vars = $this->makeAttrCheckboxVars(false, true, '');
        $this->assertParity('Controls/Attributes/Checkbox.tpl', 'Controls/Attributes/Checkbox.twig', $vars);
    }

    // ── Controls/Attributes/MultiLineTextbox ─────────────────────────────────

    private function makeAttrMultiLineVars(bool $readonly, bool $searchmode, string $value = 'Test text', bool $required = false, bool $tooltip = false): array
    {
        $attr = new LBAttribute(
            new CustomAttribute(
                2,
                'Notes',
                CustomAttributeTypes::MULTI_LINE_TEXTBOX,
                CustomAttributeCategory::RESERVATION,
                '',
                $required,
                null,
                1
            ),
            $value
        );

        return [
            'attribute'     => $attr,
            'attributeId'   => 'attr_ml_2',
            'attributeName' => 'customAttribute[2]',
            'class'         => 'mb-2',
            'readonly'      => $readonly,
            'searchmode'    => $searchmode,
            'tooltip'       => $tooltip,
        ];
    }

    public function testAttrMultiLineEditable(): void
    {
        $vars = $this->makeAttrMultiLineVars(false, false);
        $this->assertParity('Controls/Attributes/MultiLineTextbox.tpl', 'Controls/Attributes/MultiLineTextbox.twig', $vars);
    }

    public function testAttrMultiLineRequired(): void
    {
        $vars = $this->makeAttrMultiLineVars(false, false, 'Text', true);
        $this->assertParity('Controls/Attributes/MultiLineTextbox.tpl', 'Controls/Attributes/MultiLineTextbox.twig', $vars);
    }

    public function testAttrMultiLineReadonly(): void
    {
        $vars = $this->makeAttrMultiLineVars(true, false, 'Some note');
        $this->assertParity('Controls/Attributes/MultiLineTextbox.tpl', 'Controls/Attributes/MultiLineTextbox.twig', $vars);
    }

    public function testAttrMultiLineReadonlyWithTooltip(): void
    {
        $vars = $this->makeAttrMultiLineVars(true, false, 'Some note', false, true);
        $this->assertParity('Controls/Attributes/MultiLineTextbox.tpl', 'Controls/Attributes/MultiLineTextbox.twig', $vars);
    }

    public function testAttrMultiLineSearchmode(): void
    {
        $vars = $this->makeAttrMultiLineVars(false, true);
        $this->assertParity('Controls/Attributes/MultiLineTextbox.tpl', 'Controls/Attributes/MultiLineTextbox.twig', $vars);
    }

    // ── Controls/Attributes/SelectList ───────────────────────────────────────

    private function makeAttrSelectListVars(bool $readonly, bool $searchmode, string $value = '', bool $required = false, bool $tooltip = false): array
    {
        $attr = new LBAttribute(
            new CustomAttribute(
                3,
                'Room Type',
                CustomAttributeTypes::SELECT_LIST,
                CustomAttributeCategory::RESOURCE,
                '',
                $required,
                'Small,Medium,Large',
                1
            ),
            $value
        );

        return [
            'attribute'     => $attr,
            'attributeId'   => 'attr_sl_3',
            'attributeName' => 'customAttribute[3]',
            'class'         => 'mb-2',
            'readonly'      => $readonly,
            'searchmode'    => $searchmode,
            'tooltip'       => $tooltip,
        ];
    }

    public function testAttrSelectListEditable(): void
    {
        $vars = $this->makeAttrSelectListVars(false, false);
        $this->assertParity('Controls/Attributes/SelectList.tpl', 'Controls/Attributes/SelectList.twig', $vars);
    }

    public function testAttrSelectListWithSelectedValue(): void
    {
        $vars = $this->makeAttrSelectListVars(false, false, 'Medium');
        $this->assertParity('Controls/Attributes/SelectList.tpl', 'Controls/Attributes/SelectList.twig', $vars);
    }

    public function testAttrSelectListRequired(): void
    {
        $vars = $this->makeAttrSelectListVars(false, false, '', true);
        $this->assertParity('Controls/Attributes/SelectList.tpl', 'Controls/Attributes/SelectList.twig', $vars);
    }

    public function testAttrSelectListReadonly(): void
    {
        $vars = $this->makeAttrSelectListVars(true, false, 'Large');
        $this->assertParity('Controls/Attributes/SelectList.tpl', 'Controls/Attributes/SelectList.twig', $vars);
    }

    public function testAttrSelectListReadonlyWithTooltip(): void
    {
        $vars = $this->makeAttrSelectListVars(true, false, 'Small', false, true);
        $this->assertParity('Controls/Attributes/SelectList.tpl', 'Controls/Attributes/SelectList.twig', $vars);
    }

    public function testAttrSelectListSearchmode(): void
    {
        $vars = $this->makeAttrSelectListVars(false, true);
        $this->assertParity('Controls/Attributes/SelectList.tpl', 'Controls/Attributes/SelectList.twig', $vars);
    }

    // ── Controls/Attributes/SingleLineTextbox ────────────────────────────────

    private function makeAttrSingleLineVars(bool $readonly, bool $searchmode, string $value = '', bool $required = false, bool $tooltip = false, bool $hasClass = true): array
    {
        $attr = new LBAttribute(
            new CustomAttribute(
                4,
                'Department',
                CustomAttributeTypes::SINGLE_LINE_TEXTBOX,
                CustomAttributeCategory::USER,
                '',
                $required,
                null,
                1
            ),
            $value
        );

        $vars = [
            'attribute'     => $attr,
            'attributeId'   => 'attr_sl_4',
            'attributeName' => 'customAttribute[4]',
            'readonly'      => $readonly,
            'searchmode'    => $searchmode,
            'tooltip'       => $tooltip,
        ];

        if ($hasClass) {
            $vars['class'] = 'mb-2';
        }

        return $vars;
    }

    public function testAttrSingleLineEditable(): void
    {
        $vars = $this->makeAttrSingleLineVars(false, false);
        $this->assertParity('Controls/Attributes/SingleLineTextbox.tpl', 'Controls/Attributes/SingleLineTextbox.twig', $vars);
    }

    public function testAttrSingleLineWithValue(): void
    {
        $vars = $this->makeAttrSingleLineVars(false, false, 'Engineering');
        $this->assertParity('Controls/Attributes/SingleLineTextbox.tpl', 'Controls/Attributes/SingleLineTextbox.twig', $vars);
    }

    public function testAttrSingleLineRequired(): void
    {
        $vars = $this->makeAttrSingleLineVars(false, false, '', true);
        $this->assertParity('Controls/Attributes/SingleLineTextbox.tpl', 'Controls/Attributes/SingleLineTextbox.twig', $vars);
    }

    public function testAttrSingleLineReadonly(): void
    {
        $vars = $this->makeAttrSingleLineVars(true, false, 'HR');
        $this->assertParity('Controls/Attributes/SingleLineTextbox.tpl', 'Controls/Attributes/SingleLineTextbox.twig', $vars);
    }

    public function testAttrSingleLineReadonlyWithTooltip(): void
    {
        $vars = $this->makeAttrSingleLineVars(true, false, 'HR', false, true);
        $this->assertParity('Controls/Attributes/SingleLineTextbox.tpl', 'Controls/Attributes/SingleLineTextbox.twig', $vars);
    }

    public function testAttrSingleLineSearchmode(): void
    {
        $vars = $this->makeAttrSingleLineVars(false, true);
        $this->assertParity('Controls/Attributes/SingleLineTextbox.tpl', 'Controls/Attributes/SingleLineTextbox.twig', $vars);
    }

    public function testAttrSingleLineNoClass(): void
    {
        $vars = $this->makeAttrSingleLineVars(false, false, '', false, false, false);
        $this->assertParity('Controls/Attributes/SingleLineTextbox.tpl', 'Controls/Attributes/SingleLineTextbox.twig', $vars);
    }

    // ── Controls/Attributes/Date ─────────────────────────────────────────────

    /**
     * Attributes/Date.twig renders DatePickerSetupControl via {{ control(...) }}.
     * With Resources reset to default (deterministic), both engines should produce
     * identical output. We assert full parity.
     */
    private function makeAttrDateVars(bool $readonly, bool $searchmode, string $value = '', bool $required = false, bool $tooltip = false): array
    {
        $attr = new LBAttribute(
            new CustomAttribute(
                5,
                'End Date',
                CustomAttributeTypes::DATETIME,
                CustomAttributeCategory::RESERVATION,
                '',
                $required,
                null,
                1
            ),
            $value
        );

        return [
            'attribute'     => $attr,
            'attributeId'   => 'attr_dt_5',
            'attributeName' => 'customAttribute[5]',
            'class'         => 'mb-2',
            'readonly'      => $readonly,
            'searchmode'    => $searchmode,
            'tooltip'       => $tooltip,
        ];
    }

    public function testAttrDateEditable(): void
    {
        $vars = $this->makeAttrDateVars(false, false);
        $this->assertParity('Controls/Attributes/Date.tpl', 'Controls/Attributes/Date.twig', $vars);
    }

    public function testAttrDateReadonly(): void
    {
        $vars = $this->makeAttrDateVars(true, false, '2025-06-15 10:00');
        $this->assertParity('Controls/Attributes/Date.tpl', 'Controls/Attributes/Date.twig', $vars);
    }

    public function testAttrDateReadonlyWithTooltip(): void
    {
        $vars = $this->makeAttrDateVars(true, false, '2025-06-15 10:00', false, true);
        $this->assertParity('Controls/Attributes/Date.tpl', 'Controls/Attributes/Date.twig', $vars);
    }

    public function testAttrDateRequired(): void
    {
        $vars = $this->makeAttrDateVars(false, false, '', true);
        $this->assertParity('Controls/Attributes/Date.tpl', 'Controls/Attributes/Date.twig', $vars);
    }
}
