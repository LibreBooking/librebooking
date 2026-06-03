<?php

require_once(ROOT_DIR . 'lib/Common/namespace.php');

interface IRepeatOptions
{
    /**
     * Gets array of DateRange objects
     *
     * @param DateRange $startingDates
     * @return array|DateRange[]
     */
    public function GetDates(DateRange $startingDates);

    public function ConfigurationString();

    public function RepeatType();

    public function Equals(IRepeatOptions $repeatOptions);

    public function HasSameConfigurationAs(IRepeatOptions $repeatOptions);

    public function TerminationDate();
}

interface IRepeatOptionsComposite
{
    /**
     * @return string
     */
    public function GetRepeatType();

    /**
     * @return string|null
     */
    public function GetRepeatInterval();

    /**
     * @return int[]|null
     */
    public function GetRepeatWeekdays();

    /**
     * @return string|null
     */
    public function GetRepeatMonthlyType();

    /**
     * @return string|null
     */
    public function GetRepeatTerminationDate();

    /**
     * @return string[]
     */
    public function GetRepeatCustomDates();
}

abstract class RepeatOptionsAbstract implements IRepeatOptions
{
    /**
     * @var int
     */
    protected $_interval;

    /**
     * @var Date
     */
    protected $_terminationDate;

    /**
     * @return Date
     */
    public function TerminationDate()
    {
        return $this->_terminationDate;
    }

    /**
     * @param int $interval
     * @param Date $terminationDate
     */
    protected function __construct($interval, $terminationDate)
    {
        $this->_interval = $interval;
        $this->_terminationDate = $terminationDate;
    }

    public function ConfigurationString()
    {
        return sprintf('interval=%s|termination=%s', $this->_interval, $this->_terminationDate->ToDatabase());
    }

    public function Equals(IRepeatOptions $repeatOptions)
    {
        return $this->ConfigurationString() == $repeatOptions->ConfigurationString();
    }

    public function HasSameConfigurationAs(IRepeatOptions $repeatOptions)
    {
        return get_class($this) == get_class($repeatOptions) && $this->_interval == $repeatOptions->_interval;
    }
}

class RepeatType
{
    public const None = 'none';
    public const Daily = 'daily';
    public const Weekly = 'weekly';
    public const Monthly = 'monthly';
    public const Yearly = 'yearly';
    public const Custom = 'custom';

    /**
     * @param string $value
     * @return bool
     */
    public static function IsDefined($value)
    {
        switch ($value) {
            case self::None:
            case self::Daily:
            case self::Weekly:
            case self::Monthly:
            case self::Yearly:
            case self::Custom:
                return true;
            default:
                return false;

        }
    }
}

class RepeatMonthlyType
{
    public const DayOfMonth = 'dayOfMonth';
    public const DayOfWeek = 'dayOfWeek';

    /**
     * @param string $value
     * @return bool
     */
    public static function IsDefined($value)
    {
        switch ($value) {
            case self::DayOfMonth:
            case self::DayOfWeek:
                return true;
            default:
                return false;
        }
    }
}

class RepeatNone implements IRepeatOptions
{
    public function GetDates(DateRange $startingDate)
    {
        return [];
    }

    public function RepeatType()
    {
        return RepeatType::None;
    }

    public function ConfigurationString()
    {
        return '';
    }

    public function Equals(IRepeatOptions $repeatOptions)
    {
        return get_class($this) == get_class($repeatOptions);
    }

    public function HasSameConfigurationAs(IRepeatOptions $repeatOptions)
    {
        return $this->Equals($repeatOptions);
    }

    public function TerminationDate()
    {
        return Date::Now();
    }
}

class RepeatDaily extends RepeatOptionsAbstract
{
    /**
     * @param int $interval
     * @param Date $terminationDate
     */
    public function __construct($interval, $terminationDate)
    {
        parent::__construct($interval, $terminationDate);
    }

    public function GetDates(DateRange $startingRange)
    {
        $dates = [];
        $startDate = $startingRange->GetBegin()->AddDays($this->_interval);
        $endDate = $startingRange->GetEnd()->AddDays($this->_interval);

        while ($startDate->DateCompare($this->_terminationDate) <= 0) {
            $dates[] = new DateRange($startDate, $endDate);
            $startDate = $startDate->AddDays($this->_interval);
            $endDate = $endDate->AddDays($this->_interval);
        }

        return $dates;
    }

    public function RepeatType()
    {
        return RepeatType::Daily;
    }
}

class RepeatWeekly extends RepeatOptionsAbstract
{
    /**
     * @var array
     */
    private $_daysOfWeek = [];

    /**
     * @param int $interval
     * @param Date $terminationDate
     * @param array $daysOfWeek
     */
    public function __construct($interval, $terminationDate, $daysOfWeek)
    {
        parent::__construct($interval, $terminationDate);

        if ($daysOfWeek == null) {
            $daysOfWeek = [];
        }
        $this->_daysOfWeek = $daysOfWeek;
        if ($this->_daysOfWeek != null) {
            sort($this->_daysOfWeek);
        }
    }

    public function GetDates(DateRange $startingRange)
    {
        if (empty($this->_daysOfWeek)) {
            $this->_daysOfWeek = [$startingRange->GetBegin()->Weekday()];
        }

        $dates = [];

        $startDate = $startingRange->GetBegin();
        $endDate = $startingRange->GetEnd();

        $startWeekday = $startDate->Weekday();
        foreach ($this->_daysOfWeek as $weekday) {
            if ($startWeekday < $weekday) {
                $start = $startDate->AddDays($weekday - $startWeekday);
                $end = $endDate->AddDays($weekday - $startWeekday);

                $dates[] = new DateRange($start, $end);
            }
        }

        $rawStart = $startingRange->GetBegin();
        $rawEnd = $startingRange->GetEnd();

        $week = 1;

        while ($startDate->DateCompare($this->_terminationDate) <= 0) {
            $weekOffset = (7 * $this->_interval * $week);

            for ($day = 0; $day < count($this->_daysOfWeek); $day++) {
                $intervalOffset = $weekOffset + ($this->_daysOfWeek[$day] - $startWeekday);
                $startDate = $rawStart->AddDays($intervalOffset);
                $endDate = $rawEnd->AddDays($intervalOffset);

                if ($startDate->DateCompare($this->_terminationDate) <= 0) {
                    $dates[] = new DateRange($startDate, $endDate);
                }
            }

            $week++;
        }

        return $dates;
    }

    public function RepeatType()
    {
        return RepeatType::Weekly;
    }

    public function ConfigurationString()
    {
        $config = parent::ConfigurationString();
        return sprintf('%s|days=%s', $config, implode(',', $this->_daysOfWeek));
    }

    public function HasSameConfigurationAs(IRepeatOptions $repeatOptions)
    {
        if (!$repeatOptions instanceof self) {
            return false;
        }

        return parent::HasSameConfigurationAs($repeatOptions) && $this->_daysOfWeek == $repeatOptions->_daysOfWeek;
    }
}

class RepeatDayOfMonth extends RepeatOptionsAbstract
{
    /**
     * @param int $interval
     * @param Date $terminationDate
     */
    public function __construct($interval, $terminationDate)
    {
        parent::__construct($interval, $terminationDate);
    }

    public function GetDates(DateRange $startingRange)
    {
        $dates = [];

        $startDate = $startingRange->GetBegin();
        $endDate = $startingRange->GetEnd();

        $rawStart = $startingRange->GetBegin();
        $rawEnd = $startingRange->GetEnd();

        $monthsFromStart = 1;
        while ($startDate->DateCompare($this->_terminationDate) <= 0) {
            $monthAdjustment = $monthsFromStart * $this->_interval;
            if ($this->DayExistsInNextMonth($rawStart, $monthAdjustment)) {
                $startDate = $this->GetNextMonth($rawStart, $monthAdjustment);
                $endDate = $this->GetNextMonth($rawEnd, $monthAdjustment);
                if ($startDate->DateCompare($this->_terminationDate) <= 0) {
                    $dates[] = new DateRange($startDate, $endDate);
                }
            }
            $monthsFromStart++;
        }

        return $dates;
    }

    public function RepeatType()
    {
        return RepeatType::Monthly;
    }

    public function ConfigurationString()
    {
        $config = parent::ConfigurationString();
        return sprintf('%s|type=%s', $config, RepeatMonthlyType::DayOfMonth);
    }

    private function DayExistsInNextMonth($date, $monthsFromStart)
    {
        $dateToCheck = Date::Create($date->Year(), $date->Month(), 1, 0, 0, 0, $date->Timezone());
        $nextMonth = $this->GetNextMonth($dateToCheck, $monthsFromStart);

        $daysInMonth = $nextMonth->Format('t');
        return $date->Day() <= $daysInMonth;
    }

    /**
     * @param Date $date
     * @param int $monthsFromStart
     * @return Date
     */
    private function GetNextMonth($date, $monthsFromStart)
    {
        $yearOffset = 0;
        $computedMonth = $date->Month() + $monthsFromStart;
        $month = $computedMonth;

        if ($computedMonth > 12) {
            $yearOffset = (int)($computedMonth - 1) / 12;
            $month = ($computedMonth - 1) % 12 + 1;
        }

        return Date::Create(
            $date->Year() + $yearOffset,
            $month,
            $date->Day(),
            $date->Hour(),
            $date->Minute(),
            $date->Second(),
            $date->Timezone()
        );
    }
}

class RepeatWeekDayOfMonth extends RepeatOptionsAbstract
{
    private $_typeList = [1 => 'first', 2 => 'second', 3 => 'third', 4 => 'fourth', 5 => 'fifth'];
    private $_dayList = [0 => 'sunday', 1 => 'monday', 2 => 'tuesday', 3 => 'wednesday', 4 => 'thursday', 5 => 'friday', 6 => 'saturday'];

    /**
     * @param int $interval
     * @param Date $terminationDate
     */
    public function __construct($interval, $terminationDate)
    {
        parent::__construct($interval, $terminationDate);
    }

    public function GetDates(DateRange $startingRange)
    {
        $dates = [];

        $startDate = $startingRange->GetBegin();
        $endDate = $startingRange->GetEnd();

        $durationStart = $startingRange->GetBegin();
        $firstWeekdayOfMonth = date('w', mktime(0, 0, 0, $durationStart->Month(), 1, $durationStart->Year()));

        $weekNumber = $this->GetWeekNumber($durationStart, $firstWeekdayOfMonth);
        $dayOfWeek = $durationStart->Weekday();
        $startMonth = $durationStart->Month();
        $startYear = $durationStart->Year();

        $monthsFromStart = 1;
        while ($startDate->DateCompare($this->_terminationDate) <= 0) {
            $computedMonth = $startMonth + $monthsFromStart * $this->_interval;
            $month = ($computedMonth - 1) % 12 + 1;
            $year = $startYear + (int)(($computedMonth - 1) / 12);

            $dayOfMonth = strtotime("{$this->_typeList[$weekNumber]} {$this->_dayList[$dayOfWeek]} $year-$month-00");
            $calculatedDate = date('Y-m-d', $dayOfMonth);
            $calculatedMonth = explode('-', $calculatedDate);

            $startDateString = $calculatedDate . " {$startDate->Hour()}:{$startDate->Minute()}:{$startDate->Second()}";
            $startDate = Date::Parse($startDateString, $startDate->Timezone());

            if ($month == $calculatedMonth[1]) {
                if ($startDate->DateCompare($this->_terminationDate) <= 0) {
                    $endDateString = $calculatedDate . " {$endDate->Hour()}:{$endDate->Minute()}:{$endDate->Second()}";
                    $endDate = Date::Parse($endDateString, $endDate->Timezone());

                    $dates[] = new DateRange($startDate, $endDate);
                }
            }

            $monthsFromStart++;
        }

        return $dates;
    }

    public function RepeatType()
    {
        return RepeatType::Monthly;
    }

    public function ConfigurationString()
    {
        $config = parent::ConfigurationString();
        return sprintf('%s|type=%s', $config, RepeatMonthlyType::DayOfWeek);
    }

    private function GetWeekNumber(Date $firstDate, $firstWeekdayOfMonth)
    {
        $week = ceil($firstDate->Day() / 7);
        return $week;
    }
}

class RepeatYearly extends RepeatOptionsAbstract
{
    /**
     * @param int $interval
     * @param Date $terminationDate
     */
    public function __construct($interval, $terminationDate)
    {
        parent::__construct($interval, $terminationDate);
    }

    public function GetDates(DateRange $startingRange)
    {
        $dates = [];
        $begin = $startingRange->GetBegin();
        $end = $startingRange->GetEnd();

        $nextStartYear = $begin->Year();
        $nextEndYear = $end->Year();
        $timezone = $begin->Timezone();

        $startDate = $begin;

        while ($startDate->DateCompare($this->_terminationDate) <= 0) {
            $nextStartYear = $nextStartYear + $this->_interval;
            $nextEndYear = $nextEndYear + $this->_interval;

            $startDate = Date::Create(
                $nextStartYear,
                $begin->Month(),
                $begin->Day(),
                $begin->Hour(),
                $begin->Minute(),
                $begin->Second(),
                $timezone
            );
            $endDate = Date::Create(
                $nextEndYear,
                $end->Month(),
                $end->Day(),
                $end->Hour(),
                $end->Minute(),
                $end->Second(),
                $timezone
            );

            if ($startDate->DateCompare($this->_terminationDate) <= 0) {
                $dates[] = new DateRange($startDate, $endDate);
            }
        }

        return $dates;
    }

    public function RepeatType()
    {
        return RepeatType::Yearly;
    }
}

class RepeatCustom implements IRepeatOptions
{
    /**
     * @var Date[]
     */
    private $repeatDates;

    /**
     * @param Date[] $repeatDates
     */
    public function __construct($repeatDates)
    {
        $this->repeatDates = $repeatDates;
    }

    public function GetDates(DateRange $startingDates)
    {
        $duration = $startingDates->Duration();
        $ranges = [];
        foreach ($this->repeatDates as $date) {
            $repeatStart = $date->SetTime($startingDates->GetBegin()->GetTime());
            $repeatEnd = $repeatStart->ApplyDifference($duration);
            $ranges[] = new DateRange($repeatStart, $repeatEnd);
        }

        return $ranges;
    }

    public function ConfigurationString()
    {
        return '';
    }

    public function RepeatType()
    {
        return RepeatType::Custom;
    }

    public function TerminationDate()
    {
        return new NullDate();
    }

    public function Equals(IRepeatOptions $repeatOptions)
    {
        return get_class($this) == get_class($repeatOptions) && $this->DatesEqual($repeatOptions);
    }

    public function HasSameConfigurationAs(IRepeatOptions $repeatOptions)
    {
        return $this->Equals($repeatOptions);
    }

    private function DatesEqual(RepeatCustom $other)
    {
        if (count($this->repeatDates) != count($other->repeatDates)) {
            return false;
        }

        for ($i = 0; $i < count($this->repeatDates); $i++) {
            if (!$this->repeatDates[$i]->Equals($other->repeatDates[$i])) {
                return false;
            }
        }

        return true;
    }
}

class RepeatOptionsFactory
{
    /**
     * @param string $repeatType must be option in RepeatType enum
     * @param int $interval
     * @param Date $terminationDate
     * @param array $weekdays
     * @param string $monthlyType
     * @param Date[] $repeatDates
     * @return IRepeatOptions
     */
    public function Create($repeatType, $interval, $terminationDate, $weekdays, $monthlyType, $repeatDates)
    {
        switch ($repeatType) {
            case RepeatType::Daily:
                return new RepeatDaily($interval, $terminationDate);

            case RepeatType::Weekly:
                return new RepeatWeekly($interval, $terminationDate, $weekdays);

            case RepeatType::Monthly:
                return ($monthlyType == RepeatMonthlyType::DayOfMonth) ? new RepeatDayOfMonth($interval, $terminationDate) : new RepeatWeekDayOfMonth($interval, $terminationDate);

            case RepeatType::Yearly:
                return new RepeatYearly($interval, $terminationDate);

            case RepeatType::Custom:
                return new RepeatCustom($repeatDates);
        }

        return new RepeatNone();
    }

    /**
     * @param IRepeatOptionsComposite $composite
     * @param string $terminationDateTimezone
     * @return IRepeatOptions
     */
    public function CreateFromComposite(IRepeatOptionsComposite $composite, $terminationDateTimezone)
    {
        $repeatType = $composite->GetRepeatType();
        $interval = $composite->GetRepeatInterval();
        $weekdays = $composite->GetRepeatWeekdays();
        $monthlyType = $composite->GetRepeatMonthlyType();
        $customDates = [];
        foreach ($composite->GetRepeatCustomDates() as $repeat) {
            $customDates[] = Date::Parse($repeat, $terminationDateTimezone);
        }
        $terminationDate = Date::Parse($composite->GetRepeatTerminationDate(), $terminationDateTimezone);

        return $this->Create($repeatType, $interval, $terminationDate, $weekdays, $monthlyType, $customDates);
    }
}

class RepeatConfiguration
{
    /**
     * @var string
     */
    public $Type;

    /**
     * @var string
     */
    public $Interval;

    /**
     * @var Date
     */
    public $TerminationDate;

    /**
     * @var array
     */
    public $Weekdays;

    /**
     * @var string
     */
    public $MonthlyType;

    /**
     * @param string $repeatType
     * @param string $configurationString
     * @return RepeatConfiguration
     */
    public static function Create($repeatType, $configurationString)
    {
        $allparts = explode('|', (string) ($configurationString ?? ''));
        $configParts = [];

        if (!empty($allparts[0])) {
            foreach ($allparts as $part) {
                $keyValue = explode('=', $part);

                if (!empty($keyValue[0])) {
                    $configParts[$keyValue[0]] = $keyValue[1];
                }
            }
        }

        $config = new RepeatConfiguration();
        $config->Type = empty($repeatType) ? RepeatType::None : $repeatType;

        $config->Interval = self::Get($configParts, 'interval');
        $config->SetTerminationDate(self::Get($configParts, 'termination'));
        $config->SetWeekdays(self::Get($configParts, 'days'));
        $config->MonthlyType = self::Get($configParts, 'type');

        return $config;
    }

    protected function __construct()
    {
    }

    private static function Get($array, $key)
    {
        if (isset($array[$key])) {
            return $array[$key];
        }

        return null;
    }

    private function SetTerminationDate($terminationDateString)
    {
        if (!empty($terminationDateString)) {
            $this->TerminationDate = Date::FromDatabase($terminationDateString);
        } else {
            $this->TerminationDate = NullDate::Instance();
        }
    }

    private function SetWeekdays($weekdays)
    {
        if ($weekdays != null && $weekdays != '') {
            $this->Weekdays = explode(',', $weekdays);
        }
    }
}
