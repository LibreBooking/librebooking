<?php

//$serverTimezone = ini_get('date.timezone');
//if (empty($serverTimezone))
//{
//	Log::Debug('The server timezone is not set properly in your php.ini file. It is highly recommended that this value is set.');
//}

class Date
{
    /**
     * @var DateTime
     */
    private $date;
    private $parts;
    private $timezone;
    private $timestring;
    private $timestamp;

    public const SHORT_FORMAT = 'Y-m-d H:i:s';

    // Only used for testing
    private static $_Now = null;

    /**
     * Creates a Date with the provided timestamp and timezone
     * Defaults to current time
     * Defaults to server.timezone configuration setting
     *
     * @param string $timestring
     * @param string $timezone
     */
    public function __construct($timestring = null, $timezone = null)
    {
        $this->InitializeTimezone($timezone ?? '');

        $this->date = new DateTime($timestring ?? '', new DateTimeZone($this->timezone));
        $this->timestring = $this->date->format(self::SHORT_FORMAT);
        $this->timestamp = $this->date->format('U');
        $this->InitializeParts();
    }

    private function InitializeTimezone($timezone)
    {
        $this->timezone = $timezone;
        if (empty($timezone)) {
            $this->timezone = date_default_timezone_get();
        }
    }

    /**
     * Creates a new Date object with the given year, month, day, and optional $hour, $minute, $secord and $timezone
     * @return Date
     */
    public static function Create($year, $month, $day, $hour = 0, $minute = 0, $second = 0, $timezone = null)
    {
        if ($month > 12) {
            $yearOffset = floor($month / 12);
            $year = $year + $yearOffset;
            $month = $month - ($yearOffset * 12);
        }

        return new Date(sprintf(
            '%04d-%02d-%02d %02d:%02d:%02d',
            $year,
            $month,
            $day,
            $hour,
            $minute,
            $second
        ), $timezone);
    }

    /**
     * Creates a new Date object from the given string and $timezone
     * @param string $dateString
     * @param string|null $timezone
     * @return Date
     */
    public static function Parse($dateString, $timezone = null)
    {
        if (empty($dateString)) {
            return NullDate::Instance();
        }
        return new Date($dateString, $timezone);
    }

    /**
     * @param string $dateString
     * @return Date
     */
    public static function ParseExact($dateString)
    {
        if (empty($dateString)) {
            return NullDate::Instance();
        }

        /*
         * This wasn't producing correct results.
         * Parameter $datestring is provided in ISO 8601 format and therefore has the correct timezone
         * This then needs to be converted to UTC.
         *
                $offset = '';
                $strLen = strlen($dateString);
                $hourAdjustment = 0;
                $minuteAdjustment = 0;
                if ($strLen > 5)
                {
                    $offset = substr($dateString, -5);
                    $hourAdjustment = substr($offset, 1, 2);
                    $minuteAdjustment = substr($offset, 3, 2);
                }

                if (BookedStringHelper::Contains($offset, '+'))
                {
                    $hourAdjustment *= -1;
                    $minuteAdjustment *= -1;
                }

                $parsed = date_parse($dateString);

                $d = Date::Create($parsed['year'], $parsed['month'], $parsed['day'], $parsed['hour'] + $hourAdjustment, $parsed['minute'] + $minuteAdjustment,						  $parsed['second'], 'UTC');
         */

        $dt = new DateTime($dateString);
        $utc = $dt->setTimezone(new DateTimeZone('UTC'));

        $d = Date::Create($utc->format('Y'), $utc->format('m'), $utc->format('d'), $utc->format('H'), $utc->format('i'), $utc->format('s'), 'UTC');

        return $d;
    }

    /**
     * Returns a Date object representing the current date/time in the server's timezone
     *
     * @return Date
     */
    public static function Now()
    {
        if (isset(self::$_Now)) {
            return self::$_Now;
        }

        return new Date('now');
    }

    /**
     * Formats the Date with the provided format
     *
     * @param string $format
     * @return string
     */
    public function Format($format)
    {
        return $this->date->format($format);
    }

    /**
     * Returns the Date adjusted into the provided timezone
     *
     * @param string $timezone
     * @return Date
     */
    public function ToTimezone($timezone)
    {
        if ($this->Timezone() == $timezone) {
            return $this->Copy();
        }

        $date = new DateTime($this->timestring, new DateTimeZone($this->timezone));

        $date->setTimezone(new DateTimeZone($timezone));
        $adjustedDate = $date->format(Date::SHORT_FORMAT);

        return new Date($adjustedDate, $timezone);
    }

    /**
     * @return Date
     */
    public function Copy()
    {
        return new Date($this->timestring, $this->Timezone());
    }

    /**
     * Returns the Date adjusted into UTC
     *
     * @return Date
     */
    public function ToUtc()
    {
        return $this->ToTimezone('UTC');
    }

    /**
     * @return string
     */
    public function ToIso()
    {
        //		$offset = $this->date->getOffset();
        //		$hours = intval(intval($offset) / 3600);
        //		$minutes  = intval(($offset / 60) % 60);
        //		printf("offset = %d%d", $hours, $minutes);
        //		//die(' off '  .$offset . ' tz ' . $this->date->getTimezone()->getOffset());
        return $this->Format(DateTime::ISO8601);
    }

    /**
     * Formats the Date into a format that is accepted by the database
     *
     * @return string
     */
    public function ToDatabase()
    {
        return $this->ToUtc()->Format('Y-m-d H:i:s');
    }

    /**
     * @param string $databaseValue
     * @return Date
     */
    public static function FromDatabase($databaseValue)
    {
        if (empty($databaseValue)) {
            return NullDate::Instance();
        }
        return Date::Parse($databaseValue, 'UTC');
    }

    /**
     * Returns the current Date as a timestamp
     *
     * @return int
     */
    public function Timestamp()
    {
        return $this->timestamp;
    }

    /**
     * Returns the Time part of the Date
     *
     * @return Time
     */
    public function GetTime()
    {
        return new Time($this->Hour(), $this->Minute(), $this->Second(), $this->Timezone());
    }

    /**
     * Returns the Date only part of the date.  Hours, Minutes and Seconds will be 0
     *
     * @return Date
     */
    public function GetDate()
    {
        return Date::Create($this->Year(), $this->Month(), $this->Day(), 0, 0, 0, $this->Timezone());
    }

    /**
     * Compares this date to the one passed in
     * Returns:
     * -1 if this date is less than the passed in date
     * 0 if the dates are equal
     * 1 if this date is greater than the passed in date
     * @param Date $date
     * @return int comparison result
     */
    public function Compare(Date $date)
    {
        $date2 = $date;
        if ($date2->Timezone() != $this->Timezone()) {
            $date2 = $date->ToTimezone($this->timezone);
        }

        if ($this->Timestamp() < $date2->Timestamp()) {
            return -1;
        } else {
            if ($this->Timestamp() > $date2->Timestamp()) {
                return 1;
            }
        }

        return 0;
    }

    /**
     * Compares the time component of this date to the one passed in
     * Returns:
     * -1 if this time is less than the passed in time
     * 0 if the times are equal
     * 1 if this times is greater than the passed in times
     * @param Date $date
     * @return int comparison result
     */
    public function CompareTime(Date $date)
    {
        $date2 = $date;
        if ($date2->Timezone() != $this->Timezone()) {
            $date2 = $date->ToTimezone($this->timezone);
        }

        $hourCompare = ($this->Hour() - $date2->Hour());
        $minuteCompare = ($this->Minute() - $date2->Minute());
        $secondCompare = ($this->Second() - $date2->Second());

        if ($hourCompare < 0 || ($hourCompare == 0 && $minuteCompare < 0) || ($hourCompare == 0 && $minuteCompare == 0 && $secondCompare < 0)) {
            return -1;
        } else {
            if ($hourCompare > 0 || ($hourCompare == 0 && $minuteCompare > 0) || ($hourCompare == 0 && $minuteCompare == 0 && $secondCompare > 0)) {
                return 1;
            }
        }

        return 0;
    }

    /**
     * Compares the time component of this date to the one passed in
     * Returns:
     * -1 if this time is less than the passed in time
     * 0 if the times are equal
     * 1 if this times is greater than the passed in times
     * @param Time $time
     * @return int comparison result
     */
    public function CompareTimes(Time $time)
    {
        return $this->GetTime()->Compare($time);
    }

    /**
     * Compares the time component of this date to the one passed in
     * @param Time $time
     * @return bool if the current object is greater than the one passed in
     */
    public function TimeLessThan(Time $time)
    {
        return $this->GetTime()->Compare($time) < 0;
    }

    /**
     * Compares this date to the one passed in
     * @param Date $end
     * @return bool if the current object is greater than the one passed in
     */
    public function GreaterThan(Date $end)
    {
        return $this->Compare($end) > 0;
    }

    /**
     * Compares this date to the one passed in
     * @param Date $end
     * @return bool if the current object is greater than the one passed in
     */
    public function GreaterThanOrEqual(Date $end)
    {
        return $this->Compare($end) >= 0;
    }

    /**
     * Compares this date to the one passed in
     * @param Date $end
     * @return bool if the current object is less than the one passed in
     */
    public function LessThan(Date $end)
    {
        return $this->Compare($end) < 0;
    }

    /**
     * Compares this date to the one passed in
     * @param Date $end
     * @return bool if the current object is less than the one passed in
     */
    public function LessThanOrEqual(Date $end)
    {
        return $this->Compare($end) <= 0;
    }

    /**
     * Compare the 2 dates
     *
     * @param Date $date
     * @return bool
     */
    public function Equals(Date $date)
    {
        return $this->Compare($date) == 0;
    }

    /**
     * @param Date $date
     * @return bool
     */
    public function DateEquals(Date $date)
    {
        $date2 = $date;
        if ($date2->Timezone() != $this->Timezone()) {
            $date2 = $date->ToTimezone($this->timezone);
        }

        return ($this->Day() == $date2->Day() && $this->Month() == $date2->Month() && $this->Year() == $date2->Year());
    }

    public function DateCompare(Date $date)
    {
        $date2 = $date;
        if ($date2->Timezone() != $this->Timezone()) {
            $date2 = $date->ToTimezone($this->timezone);
        }

        $d1 = (int)$this->Format('Ymd');
        $d2 = (int)$date2->Format('Ymd');

        if ($d1 > $d2) {
            return 1;
        }
        if ($d1 < $d2) {
            return -1;
        }
        return 0;
    }

    /**
     * @return bool
     */
    public function IsMidnight()
    {
        return $this->Hour() == 0 && $this->Minute() == 0 && $this->Second() == 0;
    }

    /**
     * @return bool
     */
    public function IsWeekday()
    {
        $weekday = $this->Weekday();
        return $weekday != 0 && $weekday != 6;
    }

    /**
     * @return bool
     */
    public function IsWeekend()
    {
        return !$this->IsWeekday();
    }

    private function getOperator(int $number): string
    {
        return $number < 0 ? ' -' : ' +';
    }
    /**
     * @param int $days
     * @return Date
     */
    public function AddDays($days)
    {
        // can also use DateTime->modify()
        return new Date($this->Format(self::SHORT_FORMAT) . $this->getOperator($days) . abs($days) . ' days', $this->timezone);
    }

    /**
     * @param int $months
     * @return Date
     */
    public function AddMonths($months)
    {
        return new Date($this->Format(self::SHORT_FORMAT) . $this->getOperator($months) . abs($months) . ' months', $this->timezone);
    }

    /**
     * @param int $years
     * @return Date
     */
    public function AddYears($years)
    {
        return new Date($this->Format(self::SHORT_FORMAT) . $this->getOperator($years) . abs($years) . ' years', $this->timezone);
    }

    /**
     * @param int $minutes
     * @return Date
     */
    public function AddMinutes($minutes)
    {
        $ts = $this->ToUtc()->Timestamp() + ($minutes * 60);
        $utcDate = new Date(gmdate(self::SHORT_FORMAT, $ts), 'UTC');
        return $utcDate->ToTimezone($this->timezone);
        //return new Date($this->Format(self::SHORT_FORMAT) . " +" . $minutes . " minutes", $this->timezone);
    }

    /**
     * @param int $minutes
     * @return Date
     */
    public function SubtractMinutes($minutes)
    {
        $ts = $this->ToUtc()->Timestamp() - ($minutes * 60);
        $utcDate = new Date(gmdate(self::SHORT_FORMAT, $ts), 'UTC');
        return $utcDate->ToTimezone($this->timezone);
        //return new Date($this->Format(self::SHORT_FORMAT) . " +" . $minutes . " minutes", $this->timezone);
    }

    /**
     * @param int $hours
     * @return Date
     */
    public function AddHours($hours)
    {
        return new Date($this->Format(self::SHORT_FORMAT) . $this->getOperator($hours) . abs($hours) . ' hours', $this->timezone);
    }

    /**
     * @param int $minutes
     * @return Date
     */
    public function RemoveMinutes($minutes)
    {
        return new Date($this->Format(self::SHORT_FORMAT) . ' -' . $minutes . ' minutes', $this->timezone);
    }

    /**
     * @param Time $time
     * @param bool $isEndTime
     * @return Date
     */
    public function SetTime(Time $time, $isEndTime = false)
    {
        $date = Date::Create(
            $this->Year(),
            $this->Month(),
            $this->Day(),
            $time->Hour(),
            $time->Minute(),
            $time->Second(),
            $this->Timezone()
        );

        if ($isEndTime) {
            if ($time->Hour() == 0 && $time->Minute() == 0 && $time->Second() == 0) {
                return $date->AddDays(1);
            }
        }

        return $date;
    }

    /**
     * @param string $time
     * @param bool $isEndTime
     * @return Date
     */
    public function SetTimeString($time, $isEndTime = false)
    {
        return $this->SetTime(Time::Parse($time, $this->Timezone()), $isEndTime);
    }

    /**
     * @param Date $date
     * @return DateDiff
     */
    public function GetDifference(Date $date)
    {
        return DateDiff::BetweenDates($this, $date);
    }

    /**
     * @param DateDiff $difference
     * @return Date
     */
    public function ApplyDifference(DateDiff $difference)
    {
        if ($difference->IsNull()) {
            return $this->Copy();
        }

        $newTimestamp = $this->Timestamp() + $difference->TotalSeconds();
        $dateStr = gmdate(self::SHORT_FORMAT, $newTimestamp);
        $date = new DateTime($dateStr, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone($this->Timezone()));

        return new Date($date->format(self::SHORT_FORMAT), $this->Timezone());
    }

    private function InitializeParts()
    {
        $parts = explode(' ', $this->date->format('H i s m d Y w'));

        $this->parts['hours'] = $parts[0];
        $this->parts['minutes'] = $parts[1];
        $this->parts['seconds'] = $parts[2];
        $this->parts['mon'] = $parts[3];
        $this->parts['mday'] = $parts[4];
        $this->parts['year'] = $parts[5];
        $this->parts['wday'] = $parts[6];
    }

    /**
     * @return int
     */
    public function Hour()
    {
        return $this->parts['hours'];
    }

    /**
     * @return int
     */
    public function Minute()
    {
        return $this->parts['minutes'];
    }

    /**
     * @return int
     */
    public function Second()
    {
        return $this->parts['seconds'];
    }

    /**
     * @return int
     */
    public function Month()
    {
        return $this->parts['mon'];
    }

    /**
     * @return int
     */
    public function Day()
    {
        return $this->parts['mday'];
    }

    /**
     * @return int
     */
    public function Year()
    {
        return $this->parts['year'];
    }

    /**
     * @return int
     */
    public function Weekday()
    {
        return $this->parts['wday'];
    }

    /**
     * @return int
     */
    public function WeekNumber()
    {
        return $this->Format('W');
    }

    public function Timezone()
    {
        return $this->timezone;
    }

    /**
     * Only used for unit testing
     * @param Date $date
     */
    public static function _SetNow(Date $date)
    {
        if (is_null($date)) {
            self::$_Now = null;
        } else {
            self::$_Now = $date;
        }
    }

    /**
     * Only used for unit testing
     */
    public static function _ResetNow()
    {
        self::$_Now = null;
    }

    public function ToString()
    {
        return $this->Format('Y-m-d H:i:s') . ' ' . $this->timezone;
    }

    public function __toString()
    {
        return $this->ToString();
    }

    /**
     * @static
     * @return Date
     */
    public static function Min()
    {
        return Date::Parse('0001-01-01', 'UTC');
    }

    /**
     * @static
     * @return Date
     */
    public static function Max()
    {
        return Date::Parse('9999-01-01', 'UTC');
    }

    /**
     * @return Date
     */
    public function ToTheMinute()
    {
        $time = $this->GetTime();
        return $this->SetTime(new Time($time->Hour(), $time->Minute(), 0, $this->Timezone()));
    }

    /**
     * @param TimeInterval $interval
     * @return Date
     */
    public function SubtractInterval(TimeInterval $interval)
    {
        return $this->ApplyDifference($interval->Diff()->Invert());
    }

    /**
     * @param TimeInterval $interval
     * @return Date
     */
    public function AddInterval(TimeInterval $interval)
    {
        return $this->ApplyDifference($interval->Diff());
    }


    /**
     * Implements bubble sort algorithm to sort dates from array
     */
    public static function BubbleSort($array, $sortBy = 'StartDate')
    {
        for ($i = 0; $i < count($array); $i++) {
            $swapped = false;
            for ($j = 0; $j < count($array) - $i - 1; $j++) {

                if ($array[$j]->$sortBy->DateCompare($array[$j + 1]->$sortBy) == 1) {
                    $t = $array[$j];
                    $array[$j] = $array[$j + 1];
                    $array[$j + 1] = $t;
                    $swapped = true;
                } elseif ($array[$j]->$sortBy->DateCompare($array[$j + 1]->$sortBy) == 0) {
                    if ($array[$j]->$sortBy->CompareTime($array[$j + 1]->$sortBy) == 1) {
                        $t = $array[$j];
                        $array[$j] = $array[$j + 1];
                        $array[$j + 1] = $t;
                        $swapped = true;
                    }
                }
            }

            if ($swapped == false) {
                break;
            }
        }
        return $array;
    }
}

class NullDate extends Date
{
    /**
     * @var NullDate
     */
    private static $ndate;

    public function __construct()
    {
        //parent::__construct();
    }

    public static function Instance()
    {
        if (self::$ndate == null) {
            self::$ndate = new NullDate();
        }

        return self::$ndate;
    }

    public function Format($format)
    {
        return '';
    }

    public function ToString()
    {
        return '';
    }

    public function ToDatabase()
    {
        return null;
    }

    public function ToTimezone($timezone)
    {
        return $this;
    }

    public function Compare(Date $date)
    {
        return -1;
    }

    public function LessThan(Date $end)
    {
        return false;
    }

    public function GreaterThan(Date $end)
    {
        return false;
    }

    public function Timestamp()
    {
        return 0;
    }

    public function ToIso()
    {
        return '';
    }
}

class DateDiff
{
    /**
     * @var int
     */
    private $seconds = 0;

    /**
     * @param int $seconds
     */
    public function __construct($seconds)
    {
        $this->seconds = intval($seconds);
    }

    /**
     * @return int
     */
    public function TotalSeconds()
    {
        return $this->seconds;
    }

    public function Days()
    {
        $days = intval($this->seconds / 86400);
        return $days;
    }

    public function Hours()
    {
        $hours = intval($this->seconds / 3600) - intval($this->Days() * 24);
        return $hours;
    }

    public function Minutes()
    {
        $minutes = intval($this->seconds / 60) % 60;
        return $minutes;
    }

    /**
     * @static
     * @param Date $date1
     * @param Date $date2
     * @return DateDiff
     */
    public static function BetweenDates(Date $date1, Date $date2)
    {
        if ($date1->Equals($date2)) {
            return DateDiff::Null();
        }

        $compareDate = $date2;
        if ($date1->Timezone() != $date2->Timezone()) {
            $compareDate = $date2->ToTimezone($date1->Timezone());
        }

        return new DateDiff($compareDate->Timestamp() - $date1->Timestamp());
    }

    /**
     * @static
     * @param string $timeString in #d#h#m, for example 2d22h13m for 2 days 22 hours 13 minutes
     * @return DateDiff
     */
    public static function FromTimeString($timeString)
    {
        $hasDayHourMinute = strpos($timeString, 'd') !== false || strpos($timeString, 'h') !== false || strpos($timeString, 'm') !== false;
        $hasTime = (strpos($timeString, ':') !== false);
        if (!$hasDayHourMinute && !$hasTime) {
            throw new Exception('Time format must contain at least a day, hour or minute. For example: 12d1h22m or be a valid time HH:mm');
        }

        if ($hasTime) {
            $parts = explode(':', $timeString);

            if (count($parts) == 3) {
                $day = $parts[0];
                $hour = $parts[1];
                $minute = $parts[2];
            } else {
                $day = 0;
                $hour = $parts[0];
                $minute = $parts[1];
            }
            return self::Create($day, $hour, $minute);
        } else {
            $matches = [];

            preg_match('/(\d*d)?(\d*h)?(\d*m)?/i', $timeString, $matches);

            $day = 0;
            $hour = 0;
            $minute = 0;
            $num_set = 0;

            if (isset($matches[1])) {
                $num_set++;
                $day = intval(substr($matches[1], 0, -1));
            }
            if (isset($matches[2])) {
                $num_set++;
                $hour = intval(substr($matches[2], 0, -1));
            }
            if (isset($matches[3])) {
                $num_set++;
                $minute = intval(substr($matches[3], 0, -1));
            }

            if ($num_set == 0) {
                /**
                 * We didn't actually match anything, throw an exception
                 * instead of silently returning 0
                 */

                throw new Exception('Time format must be in day, hour, minute order');
            }

            return self::Create($day, $hour, $minute);
        }
    }

    /**
     * @static
     * @param int $days
     * @param int $hours
     * @param int $minutes
     * @return DateDiff
     */
    public static function Create($days, $hours, $minutes)
    {
        return new DateDiff(($days * 24 * 60 * 60) + ($hours * 60 * 60) + ($minutes * 60));
    }

    /**
     * @static
     * @return DateDiff
     */
    public static function null()
    {
        return new DateDiff(0);
    }

    /**
     * @return bool
     */
    public function IsNull()
    {
        return $this->seconds == 0;
    }

    /**
     * @param DateDiff $diff
     * @return DateDiff
     */
    public function Add(DateDiff $diff)
    {
        return new DateDiff($this->seconds + $diff->seconds);
    }

    /**
     * @param DateDiff $diff
     * @return DateDiff
     */
    public function Subtract(DateDiff $diff)
    {
        return new DateDiff($this->seconds - $diff->seconds);
    }

    /**
     * @param DateDiff $diff
     * @return bool
     */
    public function GreaterThan(DateDiff $diff)
    {
        return $this->seconds > $diff->seconds;
    }

    /**
     * @param DateDiff $diff
     * @return bool
     */
    public function GreaterThanOrEqual(DateDiff $diff)
    {
        return $this->seconds >= $diff->seconds;
    }

    /**
     * @return DateDiff
     */
    public function Invert()
    {
        return new DateDiff($this->seconds * -1);
    }

    /**
     * @param false $short
     * @return string
     */
    public function ToString($short = false)
    {
        if ($short) {
            if ($this->TotalSeconds() > 0) {
                return $this->Days() . 'd' . $this->Hours() . 'h' . $this->Minutes() . 'm';
            }
            return '';
        }

        return $this->__toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $str = '';

        if ($this->Days() > 0) {
            $str .= $this->Days() . ' ' . Resources::GetInstance()->GetString('days') . ' ';
        }
        if ($this->Hours() > 0) {
            $str .= $this->Hours() . ' ' . Resources::GetInstance()->GetString('hours') . ' ';
        }
        if ($this->Minutes() > 0) {
            $str .= $this->Minutes() . ' ' . Resources::GetInstance()->GetString('minutes') . ' ';
        }

        return trim($str);
    }


    /**
     * Gets the number of remaining days in the present month
     */
    public static function getMonthRemainingDays($timezone)
    {
        date_default_timezone_set($timezone);           //NECESSARY??
        $currentDate = new DateTime();
        $endOfMonth = new DateTime($currentDate->format('Y-m-t 23:59:59'));
        $interval = $currentDate->diff($endOfMonth);
        $daysUntilEndOfMonth = $interval->days;

        return $daysUntilEndOfMonth;
    }

    /**
     * Gets the number of remaining days in the present year
     */
    public static function getYearRemainingDays($timezone)
    {
        date_default_timezone_set($timezone);           //NECESSARY??
        $currentDate = new DateTime();
        $endOfYear = new DateTime($currentDate->format('Y-12-31 23:59:59'));
        $interval = $currentDate->diff($endOfYear);
        $daysUntilEndOfYear = $interval->days;

        return $daysUntilEndOfYear;
    }
}
