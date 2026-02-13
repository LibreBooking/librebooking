<?php

class Time
{
    private $_hour;
    private $_minute;
    private $_second;
    private $_timezone;

    public const FORMAT_HOUR_MINUTE = 'H:i';

    public function __construct($hour, $minute, $second = null, $timezone = null)
    {
        $this->_hour = intval($hour);
        $this->_minute =  intval($minute);
        $this->_second = is_null($second) ? 0 : intval($second);
        $this->_timezone = $timezone;

        if (empty($timezone)) {
            $this->_timezone = date_default_timezone_get();
        }
    }

    private function GetDate()
    {
        $parts = getdate(strtotime("$this->_hour:$this->_minute:$this->_second"));
        return new Date("{$parts['year']}-{$parts['mon']}-{$parts['mday']} $this->_hour:$this->_minute:$this->_second", $this->_timezone);
    }

    /**
     * @param string $time
     * @param string $timezone, defaults to server timezone if not provided
     * @return Time
     */
    public static function Parse($time, $timezone = null)
    {
        $date = new Date($time, $timezone);

        return new Time($date->Hour(), $date->Minute(), $date->Second(), $timezone);
    }

    public function Hour()
    {
        return $this->_hour;
    }

    public function Minute()
    {
        return $this->_minute;
    }

    public function Second()
    {
        return $this->_second;
    }

    public function Timezone()
    {
        return $this->_timezone;
    }

    public function Format($format)
    {
        return $this->GetDate()->Format($format);
    }

    public function ToDatabase()
    {
        return $this->Format('H:i:s');
    }

    /**
     * Compares this time to the one passed in
     * Returns:
     * -1 if this time is less than the passed in time
     * 0 if the times are equal
     * 1 if this time is greater than the passed in time
     * @param Time $time
     * @param Date|null $comparisonDate date to be used for time comparison
     * @return int comparison result
     */
    public function Compare(Time $time, $comparisonDate = null)
    {
        if ($comparisonDate != null) {
            $myDate = Date::Create($comparisonDate->Year(), $comparisonDate->Month(), $comparisonDate->Day(), $this->Hour(), $this->Minute(), $this->Second(), $this->Timezone());
            $otherDate = Date::Create($comparisonDate->Year(), $comparisonDate->Month(), $comparisonDate->Day(), $time->Hour(), $time->Minute(), $time->Second(), $time->Timezone());

            return ($myDate->Compare($otherDate));
        }

        return $this->GetDate()->Compare($time->GetDate());
    }

    /**
     * @param Time $time
     * @param Date|null $comparisonDate date to be used for time comparison
     * @return bool
     */
    public function Equals(Time $time, $comparisonDate = null)
    {
        return $this->Compare($time, $comparisonDate) == 0;
    }

    public function ToString()
    {
        return sprintf('%02d:%02d:%02d', $this->_hour, $this->_minute, $this->_second);
    }

    public function __toString()
    {
        return $this->ToString();
    }
}

class NullTime extends Time
{
    public function __construct()
    {
        parent::__construct(0, 0, 0, null);
    }

    public function ToDatabase()
    {
        return null;
    }

    public function ToString()
    {
        return '';
    }
}
