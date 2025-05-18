<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class RecurrenceRequestResponse
{
    public function __construct(public $type, public $interval, public $monthlyType, public $weekdays, public $repeatTerminationDate)
    {
    }

    public static function Example()
    {
        return new ExampleRecurrenceRequestResponse();
    }

    /**
     * @return RecurrenceRequestResponse
     */
    public static function null()
    {
        return new RecurrenceRequestResponse(RepeatType::None, null, null, [], null);
    }
}

class ExampleRecurrenceRequestResponse extends RecurrenceRequestResponse
{
    public function __construct()
    {
        $this->interval = 3;
        $this->monthlyType = RepeatMonthlyType::DayOfMonth . '|' . RepeatMonthlyType::DayOfWeek . '|null';
        $this->type = RepeatType::Daily . '|' . RepeatType::Monthly . '|' . RepeatType::None . '|' . RepeatType::Weekly . '|' . RepeatType::Yearly;
        $this->weekdays = [0, 1, 2, 3, 4, 5, 6];
        $this->repeatTerminationDate = Date::Now()->ToIso();
    }
}
