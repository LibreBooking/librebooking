<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Schedule.php');

class FakeSchedule extends Schedule
{
    public function __construct($id = 1, $name = 'test', $isDefault = true, $weekdayStart = 0, $daysVisible = 7)
    {
        parent::__construct($id, $name, $isDefault, $weekdayStart, $daysVisible);
        $this->_timezone = 'America/Chicago';
        $this->SetAvailability(new Date('2018-01-01', $this->_timezone), new Date('2018-02-02', $this->_timezone));
    }
}
