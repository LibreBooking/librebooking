<?php

class Time
{
    // A date that is not a Daylight Savings Time transition day
    private const ANCHOR_YEAR = 2000;
    private const ANCHOR_MONTH = 1;
    private const ANCHOR_DAY = 1;
    private const FORMATS_24H_WITH_SECONDS = ['!Y-m-d H:i:s', '!Y-m-d G:i:s'];
    private const FORMATS_24H_NO_SECONDS = ['!Y-m-d H:i', '!Y-m-d G:i'];
    private const FORMATS_12H_WITH_SECONDS = ['!Y-m-d h:i:s A', '!Y-m-d h:i:s a', '!Y-m-d g:i:s A', '!Y-m-d g:i:s a'];
    private const FORMATS_12H_NO_SECONDS = ['!Y-m-d h:i A', '!Y-m-d h:i a', '!Y-m-d g:i A', '!Y-m-d g:i a'];

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
        // Use a fixed anchor date so time-only parsing/comparison is not affected
        // by the current day crossing DST boundaries.
        return Date::Create(
            self::ANCHOR_YEAR,
            self::ANCHOR_MONTH,
            self::ANCHOR_DAY,
            $this->_hour,
            $this->_minute,
            $this->_second,
            $this->_timezone
        );
    }

    /**
     * @param string $time
     * @param string $timezone, defaults to server timezone if not provided
     * @return Time
     */
    public static function Parse($time, $timezone = null)
    {
        if (empty($timezone)) {
            $timezone = date_default_timezone_get();
        }
        $dateTimeZone = new DateTimeZone($timezone);
        $time = trim((string)$time);

        $hasMeridiem = preg_match('/[[:space:]]*[ap]m$/i', $time) === 1;
        $hasSeconds = substr_count($time, ':') >= 2;

        if ($hasMeridiem) {
            $formats = $hasSeconds ? self::FORMATS_12H_WITH_SECONDS : self::FORMATS_12H_NO_SECONDS;
        } else {
            $formats = $hasSeconds ? self::FORMATS_24H_WITH_SECONDS : self::FORMATS_24H_NO_SECONDS;
        }
        static $anchorDatePrefix = null;
        $anchorDatePrefix ??= sprintf('%04d-%02d-%02d ', self::ANCHOR_YEAR, self::ANCHOR_MONTH, self::ANCHOR_DAY);
        $anchoredInput = $anchorDatePrefix . $time;

        foreach ($formats as $format) {
            $date = DateTime::createFromFormat(
                $format,
                $anchoredInput,
                $dateTimeZone
            );
            $errors = DateTime::getLastErrors();
            $hasErrors = is_array($errors) && (($errors['warning_count'] ?? 0) > 0 || ($errors['error_count'] ?? 0) > 0);

            if ($date !== false && !$hasErrors) {
                return new Time(
                    intval($date->format('H')),
                    intval($date->format('i')),
                    intval($date->format('s')),
                    $timezone
                );
            }
        }

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
