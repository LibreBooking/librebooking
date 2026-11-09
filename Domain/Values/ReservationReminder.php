<?php

class ReservationReminder
{
    private $value;
    private $minutesPrior;

    public function __construct($value, $interval)
    {
        $this->value = is_numeric($value) ? $value : 0;

        if ($interval == ReservationReminderInterval::Days) {
            $this->minutesPrior = $this->value * 60 * 24;
        } elseif ($interval == ReservationReminderInterval::Hours) {
            $this->minutesPrior = $this->value * 60;
        } else {
            $this->minutesPrior = $this->value;
        }
    }

    public static function None()
    {
        return new NullReservationReminder();
    }

    public function Enabled()
    {
        return true;
    }

    public function MinutesPrior()
    {
        return $this->minutesPrior;
    }

    /**
     * @param int $minutes
     * @return ReservationReminder
     */
    public static function FromMinutes($minutes)
    {
        if ($minutes % 1440 == 0) {
            return new ReservationReminder($minutes / 1440, ReservationReminderInterval::Days);
        } elseif ($minutes % 60 == 0) {
            return new ReservationReminder($minutes / 60, ReservationReminderInterval::Hours);
        } else {
            return new ReservationReminder($minutes, ReservationReminderInterval::Minutes);
        }
    }
}

class NullReservationReminder extends ReservationReminder
{
    public function __construct()
    {
        parent::__construct(0, null);
    }

    public function Enabled()
    {
        return false;
    }

    public function MinutesPrior()
    {
        return 0;
    }
}

class ReservationReminderInterval
{
    public const Minutes = 'minutes';
    public const Hours = 'hours';
    public const Days = 'days';
}

class ReservationReminderType
{
    public const Start = 0;
    public const End = 1;
}
