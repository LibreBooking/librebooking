<?php

require_once(ROOT_DIR . 'Controls/Control.php');

class RecurrenceControl extends Control
{
    public function PageLoad()
    {
        $this->Set('RepeatEveryOptions', range(1, 20));
        $this->Set(
            'RepeatOptions',
            [
                'none' => ['key' => 'DoesNotRepeat', 'everyKey' => ''],
                'daily' => ['key' => 'Daily', 'everyKey' => 'days'],
                'weekly' => ['key' => 'Weekly', 'everyKey' => 'weeks'],
                'monthly' => ['key' => 'Monthly', 'everyKey' => 'months'],
                'yearly' => ['key' => 'Yearly', 'everyKey' => 'years'],
                'custom' => ['key' => 'Custom', 'everyKey' => 'custom'],
            ]
        );
        $this->Set(
            'DayNames',
            [
                0 => 'DaySundayAbbr',
                1 => 'DayMondayAbbr',
                2 => 'DayTuesdayAbbr',
                3 => 'DayWednesdayAbbr',
                4 => 'DayThursdayAbbr',
                5 => 'DayFridayAbbr',
                6 => 'DaySaturdayAbbr',
            ]
        );

        $this->Display('Controls/RecurrenceDiv.tpl');
    }
}
