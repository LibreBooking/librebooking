<?php

require_once(ROOT_DIR . 'Domain/Schedule.php');

class CalendarWeek implements ICalendarSegment
{
    /**
     * @var array|CalendarDay[]
     */
    private $indexedDays = [];

    /**
     * @var array|CalendarDay[]
     */
    private $days = [];

    /**
     * @var string
     */
    private $timezone;

    /**
     * @var array|CalendarReservation[]
     */
    private $reservations;

    public function __construct($timezone)
    {
        $this->timezone = $timezone;

        for ($i = 0; $i < 7; $i++) {
            $this->indexedDays[$i] = CalendarDay::Null();
        }
    }

    public static function FromDate($year, $month, $day, $timezone, $firstDayOfWeek = 0)
    {
        $week = new CalendarWeek($timezone);

        $date = Date::Create($year, $month, $day, 0, 0, 0, $timezone);

        $start = $date->Weekday();

        if ($firstDayOfWeek == Schedule::Today) {
            $firstDayOfWeek = 0;
        }

        $adjustedDays = ($firstDayOfWeek - $start);

        if ($start < $firstDayOfWeek) {
            $adjustedDays = $adjustedDays - 7;
        }

        $date = $date->AddDays($adjustedDays);

        for ($i = 0; $i < 7; $i++) {
            $week->AddDay(new CalendarDay($date->AddDays($i)));
        }

        return $week;
    }

    public function FirstDay()
    {
        return $this->days[0]->Date();
    }

    public function LastDay()
    {
        return $this->days[count($this->days) - 1]->Date();
    }

    public function AddReservations($reservations)
    {
        /** @var CalendarReservation $reservation */
        foreach ($reservations as $reservation) {
            $this->AddReservation($reservation);
        }
    }

    public function AddDay(CalendarDay $day)
    {
        $this->days[] = $day;
        $this->indexedDays[$day->Weekday()] = $day;
    }

    /**
     * @return array|ICalendarDay[]
     */
    public function Days()
    {
        return $this->indexedDays;
    }

    /**
     * @param CalendarReservation $reservation
     * @return void
     */
    public function AddReservation($reservation)
    {
        $this->reservations[] = $reservation;
        /** @var CalendarDay $day */
        foreach ($this->indexedDays as $day) {
            $day->AddReservation($reservation);
        }
    }

    /**
     * @return string|CalendarTypes
     */
    public function GetType()
    {
        return CalendarTypes::Week;
    }

    /**
     * @return Date
     */
    public function GetPreviousDate()
    {
        return $this->FirstDay()->AddDays(-7);
    }

    /**
     * @return Date
     */
    public function GetNextDate()
    {
        return $this->FirstDay()->AddDays(7);
    }

    /**
     * @return array|CalendarReservation[]
     */
    public function Reservations()
    {
        return $this->reservations;
    }
}
