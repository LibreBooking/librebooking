<?php

class ReservationReminderView
{
    /**
     * @var int
     */
    private $value;

    /**
     * @var string ReservationReminderInterval constant
     */
    private $interval;

    /**
     * @var int
     */
    private $minutes;

    public function GetValue()
    {
        return $this->value;
    }

    public function GetInterval()
    {
        return $this->interval;
    }

    public function __construct($minutes)
    {
        $this->minutes = $minutes;
        if ($minutes % 1440 == 0) {
            $this->value = $minutes / 1440;
            $this->interval = ReservationReminderInterval::Days;
        } elseif ($minutes % 60 == 0) {
            $this->value = $minutes / 60;
            $this->interval = ReservationReminderInterval::Hours;
        } else {
            $this->value = $minutes;
            $this->interval = ReservationReminderInterval::Minutes;
        }
    }

    /**
     * @return int
     */
    public function MinutesPrior()
    {
        return $this->minutes;
    }
}
