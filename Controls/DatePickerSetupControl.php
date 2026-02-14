<?php

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

        if ($hasTimepicker) {
            $phpFormat = Resources::GetInstance()->GetDateFormat('schedule_daily') . ' ' . Resources::GetInstance()->GetDateFormat('period_time');

            $time24hr = (strpos($phpFormat, 'A') === false && strpos($phpFormat, 'a') === false);

            $altFormat = $this->phpDateFormatToFlatpickr($phpFormat);  // The converted format is used here.
            $dateFormat = 'Y-m-d H:i';      // backend format
            $this->Set('Time24Hr', $time24hr);
        } else {
            $altFormat = Resources::GetInstance()->GetDateFormat('schedule_daily');
            $dateFormat = 'Y-m-d';
            $this->Set('Time24Hr', false);
        }


        $this->Set('AltFormat', $altFormat);
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

        if (is_array($defaultDate)) {
            $dates = [];

            foreach ($defaultDate as $d) {
                if ($d instanceof Date) {
                    $dates[] = $d->Format('Y-m-d');
                } elseif ($d instanceof DateTime) {
                    $dates[] = $d->format('Y-m-d');
                } elseif (is_string($d)) {
                    $dates[] = $d;
                }
            }

            $this->Set('DefaultDate', $dates);
        } elseif ($defaultDate instanceof Date) {
            $this->Set('DefaultDate', $defaultDate->Format('Y-m-d'));
        }

        $this->SetDefault('MinDate', null);
        $this->SetDefault('MaxDate', null);

        if (!$altId) {
            $this->Display('Controls/DatePickerSetup.tpl');
        } else {
            $this->Display('Controls/DateSetup.tpl');
        }
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
            'g' => 'h',
            'h' => 'h',
            'G' => 'H',
            'H' => 'H',
            'i' => 'i',
            'A' => 'K', // AM/PM
            'a' => 'K', // am/pm
            '.' => '.',
            '/' => '/',
            '-' => '-',
            ' ' => ' ',
            ':' => ':'
        ];

        $result = '';
        $chars = str_split($format);
        foreach ($chars as $c) {
            $result .= $replacements[$c] ?? $c; // If there's no mapping, it leaves it as is.
        }
        return $result;
    }
}
