<?php

/**
 * Class DatePickerSetupControl
 *
 * Control to configure a DatePicker (based on Flatpickr) with optional time support,
 * multiple dates, min/max dates, and localized day/month names.
 *
 * Input variables (Set/Get):
 *
 * @property string $ControlId       ID of the input that will be converted into a datepicker.
 * @property string|null $AltId      ID of an alternate input (optional), used for hidden or inline dates.
 * @property bool $HasTimepicker     Indicates if the picker includes time (true) or only date (false). Default: false.
 * @property bool $Inline            Whether the picker should be displayed inline instead of as a popup. Default: false.
 * @property string|null $OnSelect   JS callback triggered when a date is selected (optional).
 * @property string|null $OnClose    JS callback triggered when the picker is closed (optional).
 * @property int $FirstDay           First day of the week (0 = Sunday, 1 = Monday, etc.). Default: 0.
 * @property string|array|Date|DateTime|null $DefaultDate
 *                                  Default date(s) to show. Can be:
 *                                  - string in 'Y-m-d' or 'Y-m-d H:i' format
 *                                  - Date object (normalized to 'Y-m-d' or 'Y-m-d H:i', based on HasTimepicker)
 *                                  - DateTime object (normalized to 'Y-m-d' or 'Y-m-d H:i', based on HasTimepicker)
 *                                  - array of any of the above for multiple dates; object values use the same normalization
 * @property bool|null $Multiple     Allow selection of multiple dates. Default: null (implicitly false).
 * @property string|null $AltFormat  Alternative date format to display. Accepts either:
 *                                  - a resource key resolved via Resources (e.g., 'short_datetime')
 *                                  - a raw PHP date format string used directly (e.g., 'd.m.Y H:i')
 *                                     Default: calculated from Resources.
 * @property string $DateFormat      Internal format used for backend submission ('Y-m-d' or 'Y-m-d H:i').
 * @property bool $Time24Hr          Indicates if time is in 24-hour format (true) or AM/PM (false). Calculated automatically.
 * @property string $DayNames         Full day names for the picker (['Sunday', ...]).
 * @property string $DayNamesShort    Short day names (['Sun', ...]).
 * @property string $DayNamesMin      Minimal day names (['S', ...]).
 * @property string $MonthNames       Full month names.
 * @property string $MonthNamesShort  Abbreviated month names.
 * @property bool $ShowWeekNumbers    Whether week numbers are shown in the picker.
 * @property string|null $MinDate     Minimum selectable date (optional).
 * @property string|null $MaxDate     Maximum selectable date (optional).
 * @property string $DefaultDateJson  JSON-encoded DefaultDate for embedding in JS.
 * @property string $AltFormatJson    JSON-encoded AltFormat for safe embedding in inline JS.
 *
 * Usage in Smarty template:
 *
 * {control
 *     type="DatePickerSetupControl"
 *     ControlId=$pickerControlId
 *     HasTimepicker=true
 *     Inline=true
 *     DefaultDate=$value
 *     AltFormat=$AltFormat
 * }
 *
 * Example:
 * {assign var="pickerControlId" value="myDate"}
 * {assign var="value" value="2026-04-04 14:00"}
 * {assign var="AltFormat" value="short_datetime"}
 * {control
 *     type="DatePickerSetupControl"
 *     ControlId=$pickerControlId
 *     HasTimepicker=true
 *     DefaultDate=$value
 *     AltFormat=$AltFormat
 * }
 */

require_once(ROOT_DIR . 'Controls/Control.php');

class DatePickerSetupControl extends Control
{
    public function __construct(SmartyPage $smarty)
    {
        parent::__construct($smarty);
    }

    public function PageLoad()
    {
        $this->SetDefault('NumberOfMonths', 1);
        $this->SetDefault('ShowButtonPanel', 'false');
        $this->Set('ControlId', $this->Get('ControlId'));
        $this->SetDefault('Inline', false);
        $this->SetDefault('OnSelect', null);
        $this->SetDefault('OnClose', null);
        $this->SetDefault('FirstDay', 0);

        /****************************** Remove upon completion of Flatpickr deployment */
        $controlId = $this->Get('ControlId');
        $altId = $this->Get('AltId');

        $elementsToTrigger = '#' . $controlId;
        if (!empty($altId)) {
            $altId = str_replace(']', '\\\\]', str_replace('[', '\\\\[', $altId));
            $elementsToTrigger .= ",#$altId";
        }

        $this->Set('ControlId', $controlId);
        $this->Set('AltId', $altId);

        /****************************** */

        $hasTimepicker = $this->Get('HasTimepicker');
        $this->Set('HasTimepicker', $hasTimepicker);

        $defaultPhpAltFormat = Resources::GetInstance()->GetDateFormat('schedule_daily');
        $periodTimeFormat = Resources::GetInstance()->GetDateFormat('period_time');
        if ($hasTimepicker) {
            $defaultPhpAltFormat .= ' ' . $periodTimeFormat;
        }

        $phpAltFormat = $this->ResolvePhpAltFormat($defaultPhpAltFormat);

        if ($hasTimepicker && !$this->HasTimeComponentInPhpFormat($phpAltFormat)) {
            $phpAltFormat = trim($phpAltFormat . ' ' . $periodTimeFormat);
        }

        $altFormat = $this->phpDateFormatToFlatpickr($phpAltFormat);

        if ($hasTimepicker) {
            $time24hr = !$this->HasUnescapedAmPmTokenInPhpFormat($phpAltFormat);
            $dateFormat = 'Y-m-d H:i';      // backend format
            $this->Set('Time24Hr', $time24hr);
        } else {
            $dateFormat = 'Y-m-d';
            $this->Set('Time24Hr', false);
        }

        $this->Set('AltFormat', $altFormat);
        $this->Set('AltFormatJson', $this->JsonEncodeForInlineScript($altFormat));
        $this->Set('DateFormat', $dateFormat);
        $multiple = $this->Get('Multiple');
        $this->Set('Multiple', $multiple);
        $this->Set('DayNamesMin', $this->GetJsDayNames('two'));
        $this->Set('DayNamesShort', $this->GetJsDayNames('abbr'));
        $this->Set('DayNames', $this->GetJsDayNames('full'));
        $this->Set('MonthNames', $this->GetJsMonthNames('full'));
        $this->Set('MonthNamesShort', $this->GetJsMonthNames('abbr'));
        $this->Set('ShowWeekNumbers', Configuration::Instance()->GetKey(ConfigKeys::SCHEDULE_SHOW_WEEK_NUMBERS, new BooleanConverter()));
        $defaultDate = $this->Get('DefaultDate');
        $defaultDateFormat = $hasTimepicker ? 'Y-m-d H:i' : 'Y-m-d';

        if (is_array($defaultDate)) {
            $dates = [];

            foreach ($defaultDate as $d) {
                if ($d instanceof Date) {
                    $dates[] = $d->Format($defaultDateFormat);
                } elseif ($d instanceof DateTime) {
                    $dates[] = $d->format($defaultDateFormat);
                } elseif (is_string($d)) {
                    $dates[] = $d;
                }
            }

            $this->Set('DefaultDate', $dates);
        } elseif ($defaultDate instanceof Date) {
            $this->Set('DefaultDate', $defaultDate->Format($defaultDateFormat));
        } elseif ($defaultDate instanceof DateTime) {
            $this->Set('DefaultDate', $defaultDate->format($defaultDateFormat));
        }

        $encodedDefaultDate = 'null';
        $defaultDateForJs = $this->Get('DefaultDate');
        if (!empty($defaultDateForJs)) {
            $encodedDefaultDate = $this->JsonEncodeForInlineScript($defaultDateForJs);
        }
        $this->Set('DefaultDateJson', $encodedDefaultDate);

        $this->SetDefault('MinDate', null);
        $this->SetDefault('MaxDate', null);

        if (!$altId) {
            $this->Display('Controls/DatePickerSetup.tpl');
        } else {
            $this->Display('Controls/DateSetup.tpl');
        }
    }

    /**
     * Encodes values as JSON for safe embedding inside inline <script> blocks.
     * Uses JSON_HEX_* flags to neutralize characters that can break out of JS
     * strings or terminate script tags (for example </script>), reducing XSS risk.
     */
    private function JsonEncodeForInlineScript(mixed $value): string
    {
        $json = json_encode(
            $value,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        );
        return $json === false ? 'null' : $json;
    }

    private function SetDefault($key, $value)
    {
        $item = $this->Get($key);
        if ($item == null) {
            $this->Set($key, $value);
        }
    }
    private function GetJsDayNames($dayKey)
    {
        return $this->GetJsArrayValues(Resources::GetInstance()->GetDays($dayKey));
    }

    private function GetJsMonthNames($monthKey)
    {
        return $this->GetJsArrayValues(Resources::GetInstance()->GetMonths($monthKey));
    }

    private function GetJsArrayValues($values)
    {
        return "['" . implode("','", $values) . "']";
    }

    private function phpDateFormatToFlatpickr($format)
    {
        $replacements = [
            'Y' => 'Y',
            'y' => 'y',
            'm' => 'm',
            'n' => 'n',
            'd' => 'd',
            'j' => 'j',
            'D' => 'D',
            'l' => 'l',
            'M' => 'M',
            'F' => 'F',
            'g' => 'h',
            'h' => 'h',
            'G' => 'H',
            'H' => 'H',
            'i' => 'i',
            's' => 'S',
            'A' => 'K', // AM/PM
            'a' => 'K', // am/pm
            '.' => '.',
            '/' => '/',
            '-' => '-',
            ' ' => ' ',
            ':' => ':'
        ];

        $result = '';
        $unsupported = [];
        $isEscaped = false;

        $chars = str_split((string)$format);
        foreach ($chars as $c) {
            if ($isEscaped) {
                // PHP and Flatpickr both use backslash to escape literals.
                $result .= '\\' . $c;
                $isEscaped = false;
                continue;
            }

            if ($c === '\\') {
                $isEscaped = true;
                continue;
            }

            if (isset($replacements[$c])) {
                $result .= $replacements[$c];
                continue;
            }

            if (ctype_alpha($c)) {
                $unsupported[$c] = true;
                $result .= '\\' . $c;
                continue;
            }

            $result .= $c;
        }

        if ($isEscaped) {
            $result .= '\\\\';
        }

        if (!empty($unsupported)) {
            trigger_error(
                sprintf(
                    'DatePickerSetupControl: unsupported PHP date format token(s) converted to literals: %s (format: %s)',
                    implode(', ', array_keys($unsupported)),
                    (string)$format
                ),
                E_USER_NOTICE
            );
        }

        return $result;
    }

    private function ResolvePhpAltFormat(string $defaultPhpFormat): string
    {
        $altFormat = $this->Get('AltFormat');
        if (empty($altFormat)) {
            return $defaultPhpFormat;
        }

        $resources = Resources::GetInstance();

        return $resources->HasDateFormat($altFormat)
            ? $resources->GetDateFormat($altFormat)
            : $altFormat;
    }

    private function HasTimeComponentInPhpFormat(string $format): bool
    {
        $isEscaped = false;
        $chars = str_split($format);

        foreach ($chars as $c) {
            if ($isEscaped) {
                $isEscaped = false;
                continue;
            }

            if ($c === '\\') {
                $isEscaped = true;
                continue;
            }

            if (in_array($c, ['g', 'G', 'h', 'H', 'i', 's', 'a', 'A'], true)) {
                return true;
            }
        }

        return false;
    }

    private function HasUnescapedAmPmTokenInPhpFormat(string $format): bool
    {
        $isEscaped = false;
        $chars = str_split($format);

        foreach ($chars as $c) {
            if ($isEscaped) {
                $isEscaped = false;
                continue;
            }

            if ($c === '\\') {
                $isEscaped = true;
                continue;
            }

            if ($c === 'a' || $c === 'A') {
                return true;
            }
        }

        return false;
    }
}
