<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class ReminderRequestResponse
{
    public function __construct(public $value, public $interval)
    {
    }

    public static function Example()
    {
        return new ReminderRequestResponse(15, ReservationReminderInterval::Hours . ' or ' . ReservationReminderInterval::Minutes . ' or ' . ReservationReminderInterval::Days);
    }
}
