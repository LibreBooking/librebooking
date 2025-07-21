<?php

require_once(ROOT_DIR . 'Domain/Values/DayOfWeek.php');

interface ILayoutTimezone
{
    public function Timezone();
}

interface IDailyScheduleLayout
{
    /**
     * @return bool
     */
    public function UsesDailyLayouts();
}

interface ILayoutCreation extends ILayoutTimezone, IDailyScheduleLayout
{
    /**
     * Appends a period to the schedule layout
     *
     * @param Time $startTime starting time of the schedule in specified timezone
     * @param Time $endTime ending time of the schedule in specified timezone
     * @param string $label optional label for the period
     * @param DayOfWeek|int|null $dayOfWeek
     */
    public function AppendPeriod(Time $startTime, Time $endTime, $label = null, $dayOfWeek = null);

    /**
     * Appends a period that is not reservable to the schedule layout
     *
     * @param Time $startTime starting time of the schedule in specified timezone
     * @param Time $endTime ending time of the schedule in specified timezone
     * @param string $label optional label for the period
     * @param DayOfWeek|int|null $dayOfWeek
     * @return void
     */
    public function AppendBlockedPeriod(Time $startTime, Time $endTime, $label = null, $dayOfWeek = null);

    /**
     *
     * @param DayOfWeek|int|null $dayOfWeek
     * @return LayoutPeriod[] array of LayoutPeriod
     */
    public function GetSlots($dayOfWeek = null);

    /**
     * @return int
     */
    public function GetType();
}

interface IScheduleLayout extends ILayoutCreation
{
    /**
     * @param Date $layoutDate
     * @param bool $hideBlockedPeriods
     * @return SchedulePeriod[]|array of SchedulePeriod objects
     */
    public function GetLayout(Date $layoutDate, $hideBlockedPeriods = false);

    /**
     * @param Date $date
     * @return SchedulePeriod|null period which occurs at this datetime. Includes start time, excludes end time. null if no match is found
     */
    public function GetPeriod(Date $date);

    /**
     * @param Date $startDate
     * @param Date $endDate
     * @return SlotCount
     */
    public function GetSlotCount(Date $startDate, Date $endDate);

    /**
     * @param PeakTimes $peakTimes
     */
    public function ChangePeakTimes(PeakTimes $peakTimes);

    public function RemovePeakTimes();

    /**
     * @return bool
     */
    public function FitsToHours();

    /**
     * @return bool
     */
    public function UsesCustomLayout();
}

class ScheduleLayout implements IScheduleLayout, ILayoutCreation
{
    public const Custom = 1;
    public const Standard = 0;

    /**
     * @var PeakTimes
     */
    protected $peakTimes;

    /**
     * @var array|LayoutPeriod[]
     */
    private $_periods = [];

    /**
     * @var string
     */
    private $targetTimezone;

    /**
     * @var bool
     */
    private $cached = false;

    private $cachedPeriods = [];

    /**
     * @var bool
     */
    private $usingDailyLayouts = false;

    /**
     * @var string
     */
    private $layoutTimezone;

    /**
     * @var string[]
     */
    private $startTimes = [];

    /**
     * @param string $targetTimezone target timezone of layout
     */
    public function __construct($targetTimezone = null)
    {
        $this->targetTimezone = $targetTimezone;
        if (empty($targetTimezone)) {
            $this->targetTimezone = Configuration::Instance()->GetDefaultTimezone();
        }
    }

    /**
     * @param DayOfWeek|int|null $dayOfWeek
     * @throws Exception
     * @return LayoutPeriod[]|array
     */
    public function GetSlots($dayOfWeek = null)
    {
        if (is_null($dayOfWeek)) {
            if ($this->usingDailyLayouts) {
                throw new Exception('ScheduleLayout->GetSlots() $dayOfWeek required when using daily layouts');
            }
            $periods = $this->_periods;
        } else {
            if (!$this->usingDailyLayouts) {
                throw new Exception('ScheduleLayout->GetSlots() $dayOfWeek cannot be provided when using single layout');
            }
            $periods = $this->_periods[$dayOfWeek];
        }
        $this->SortItems($periods);
        return $periods;
    }

    /**
     * Appends a period to the schedule layout
     *
     * @param Time $startTime starting time of the schedule in specified timezone
     * @param Time $endTime ending time of the schedule in specified timezone
     * @param string $label optional label for the period
     * @param DayOfWeek|int|null $dayOfWeek
     */
    public function AppendPeriod(Time $startTime, Time $endTime, $label = null, $dayOfWeek = null)
    {
        $this->AppendGenericPeriod($startTime, $endTime, PeriodTypes::RESERVABLE, $label, $dayOfWeek);
    }

    /**
     * Appends a period that is not reservable to the schedule layout
     *
     * @param Time $startTime starting time of the schedule in specified timezone
     * @param Time $endTime ending time of the schedule in specified timezone
     * @param string $label optional label for the period
     * @param DayOfWeek|int|null $dayOfWeek
     * @return void
     */
    public function AppendBlockedPeriod(Time $startTime, Time $endTime, $label = null, $dayOfWeek = null)
    {
        $this->AppendGenericPeriod($startTime, $endTime, PeriodTypes::NONRESERVABLE, $label, $dayOfWeek);
    }

    protected function AppendGenericPeriod(
        Time $startTime,
        Time $endTime,
        $periodType,
        $label = null,
        $dayOfWeek = null
    ) {
        if ($this->StartTimeCanBeAdded($startTime, $dayOfWeek)) {
            $this->layoutTimezone = $startTime->Timezone();
            $layoutPeriod = new LayoutPeriod($startTime, $endTime, $periodType, $label);
            if (!is_null($dayOfWeek)) {
                $this->usingDailyLayouts = true;
                $this->_periods[$dayOfWeek][] = $layoutPeriod;
            } else {
                $this->_periods[] = $layoutPeriod;
            }
        }
    }

    /**
     * @param Date $start
     * @param Date $end
     * @return bool
     */
    protected function SpansMidnight(Date $start, Date $end)
    {
        return !$start->DateEquals($end) && !$end->IsMidnight();
    }

    /**
     * @param Date $layoutDate
     * @param bool $hideBlockedPeriods
     * @return array|SchedulePeriod[]
     */
    public function GetLayout(Date $layoutDate, $hideBlockedPeriods = false)
    {
        $targetTimezone = $this->targetTimezone;
        $layoutDate = $layoutDate->ToTimezone($targetTimezone);

        if ($this->usingDailyLayouts) {
            return $this->GetLayoutDaily($layoutDate, $hideBlockedPeriods);
        }

        $cachedValues = $this->GetCachedValuesForDate($layoutDate);
        if (!empty($cachedValues)) {
            return $cachedValues;
        }

        $list = new PeriodList();

        $periods = $this->getPeriods($layoutDate);

        if (count($periods) <= 0) {
            throw new Exception(sprintf('No periods defined for date %s', $layoutDate));
        }

        $layoutTimezone = $periods[0]->Timezone();
        $workingDate = Date::Create(
            $layoutDate->Year(),
            $layoutDate->Month(),
            $layoutDate->Day(),
            0,
            0,
            0,
            $layoutTimezone
        );
        $midnight = $layoutDate->GetDate();

        /* @var LayoutPeriod $period */
        foreach ($periods as $period) {
            if ($hideBlockedPeriods && !$period->IsReservable()) {
                continue;
            }
            $start = $period->Start;
            $end = $period->End;
            $periodType = $period->PeriodTypeClass();
            $label = $period->Label;
            $labelEnd = null;

            // convert to target timezone
            $periodStart = $workingDate->SetTime($start)->ToTimezone($targetTimezone);
            $periodEnd = $workingDate->SetTime($end, true)->ToTimezone($targetTimezone);

            if ($periodEnd->LessThan($periodStart)) {
                $periodEnd = $periodEnd->AddDays(1);
            }

            $startTime = $periodStart->GetTime();
            $endTime = $periodEnd->GetTime();

            if ($this->BothDatesAreOff($periodStart, $periodEnd, $layoutDate)) {
                $periodStart = $layoutDate->SetTime($startTime);
                $periodEnd = $layoutDate->SetTime($endTime, true);
            }

            if ($this->SpansMidnight($periodStart, $periodEnd)) {
                if ($periodStart->LessThan($midnight)) {
                    // add compensating period at end
                    $start = $layoutDate->SetTime($startTime);
                    $end = $periodEnd->AddDays(1);
                    $list->Add($this->BuildPeriod($periodType, $start, $end, $label, $labelEnd));
                } else {
                    // add compensating period at start
                    $start = $periodStart->AddDays(-1);
                    $end = $layoutDate->SetTime($endTime, true);
                    $list->Add($this->BuildPeriod($periodType, $start, $end, $label, $labelEnd));
                }
            }

            if (!$periodStart->IsMidnight() && $periodStart->LessThan($layoutDate) && $periodEnd->DateEquals($layoutDate) && $periodEnd->IsMidnight()) {
                $periodStart = $periodStart->AddDays(1);
                $periodEnd = $periodEnd->AddDays(1);
            }

            $list->Add($this->BuildPeriod($periodType, $periodStart, $periodEnd, $label, $labelEnd));
        }

        $layout = $list->GetItems();
        $this->SortItems($layout);
        $this->AddCached($layout, $workingDate);

        return $layout;
    }

    private function GetLayoutDaily(Date $requestedDate, $hideBlockedPeriods = false)
    {
        if ($requestedDate->Timezone() != $this->targetTimezone) {
            throw new Exception('Target timezone and requested timezone do not match');
        }

        $cachedValues = $this->GetCachedValuesForDate($requestedDate);
        if (!empty($cachedValues)) {
            return $cachedValues;
        }

        // check cache
        $baseDateInLayoutTz = Date::Create(
            $requestedDate->Year(),
            $requestedDate->Month(),
            $requestedDate->Day(),
            0,
            0,
            0,
            $this->layoutTimezone
        );


        $list = new PeriodList();
        $this->AddDailyPeriods($requestedDate->Weekday(), $baseDateInLayoutTz, $requestedDate, $list, $hideBlockedPeriods);

        if ($this->layoutTimezone != $this->targetTimezone) {
            $requestedDateInTargetTz = $requestedDate->ToTimezone($this->layoutTimezone);

            $adjustment = 0;
            if ($requestedDateInTargetTz->Format('YmdH') < $requestedDate->Format('YmdH')) {
                $adjustment = -1;
            } else {
                if ($requestedDateInTargetTz->Format('YmdH') > $requestedDate->Format('YmdH')) {
                    $adjustment = 1;
                }
            }

            if ($adjustment != 0) {
                $adjustedDate = $requestedDate->AddDays($adjustment);
                $baseDateInLayoutTz = $baseDateInLayoutTz->AddDays($adjustment);
                $this->AddDailyPeriods($adjustedDate->Weekday(), $baseDateInLayoutTz, $requestedDate, $list);
            }
        }
        $layout = $list->GetItems();
        $this->SortItems($layout);
        $this->AddCached($layout, $requestedDate);
        return $layout;
    }

    /**
     * @param int $day
     * @param Date $baseDateInLayoutTz
     * @param Date $requestedDate
     * @param PeriodList $list
     * @param bool $hideBlockedPeriods
     */
    private function AddDailyPeriods($day, $baseDateInLayoutTz, $requestedDate, $list, $hideBlockedPeriods = false)
    {
        $periods = $this->_periods[$day];
        /** @var LayoutPeriod $period */
        foreach ($periods as $period) {
            if ($hideBlockedPeriods && !$period->IsReservable()) {
                continue;
            }
            $begin = $baseDateInLayoutTz->SetTime($period->Start)->ToTimezone($this->targetTimezone);
            $end = $baseDateInLayoutTz->SetTime($period->End, true)->ToTimezone($this->targetTimezone);
            // only add this period if it occurs on the requested date
            if ($begin->DateEquals($requestedDate) || ($end->DateEquals($requestedDate) && !$end->IsMidnight())) {
                $built = $this->BuildPeriod($period->PeriodTypeClass(), $begin, $end, $period->Label);
                $list->Add($built);
            }
        }
    }

    /**
     * @param array|SchedulePeriod[] $layout
     * @param Date $date
     */
    private function AddCached($layout, $date)
    {
        $this->cached = true;
        $this->cachedPeriods[$date->Format('Ymd')] = $layout;
    }

    /**
     * @param Date $date
     * @return array|SchedulePeriod[]
     */
    private function GetCachedValuesForDate($date)
    {
        $key = $date->Format('Ymd');
        if (array_key_exists($date->Format('Ymd'), $this->cachedPeriods)) {
            return $this->cachedPeriods[$key];
        }
        return null;
    }

    private function BothDatesAreOff(Date $start, Date $end, Date $layoutDate)
    {
        return !$start->DateEquals($layoutDate) && !$end->DateEquals($layoutDate);
    }

    private function BuildPeriod($periodType, Date $start, Date $end, $label, $labelEnd = null)
    {
        return new $periodType($start, $end, $label, $labelEnd);
    }

    protected function SortItems(&$items)
    {
        usort($items, ["ScheduleLayout", "SortBeginTimes"]);
    }

    public function Timezone()
    {
        return $this->targetTimezone;
    }

    /**
     * @return PeakTimes
     */
    public function GetPeakTimes()
    {
        return $this->peakTimes;
    }

    protected function AddPeriod(SchedulePeriod $period)
    {
        $this->_periods[] = $period;
    }

    /**
     * @static
     * @param SchedulePeriod|LayoutPeriod $period1
     * @param SchedulePeriod|LayoutPeriod $period2
     * @return int
     */
    public static function SortBeginTimes($period1, $period2)
    {
        return $period1->Compare($period2);
    }

    /**
     * @param string $timezone
     * @param string $reservableSlots
     * @param string $blockedSlots
     * @return ScheduleLayout
     */
    public static function Parse($timezone, $reservableSlots, $blockedSlots)
    {
        $parser = new LayoutParser($timezone);
        $parser->AddReservable($reservableSlots);
        $parser->AddBlocked($blockedSlots);
        return $parser->GetLayout();
    }

    /**
     * @param string $timezone
     * @param string[]|array $reservableSlots
     * @param string[]|array $blockedSlots
     * @throws Exception
     * @return ScheduleLayout
     */
    public static function ParseDaily($timezone, $reservableSlots, $blockedSlots)
    {
        if (count($reservableSlots) != DayOfWeek::NumberOfDays || count($blockedSlots) != DayOfWeek::NumberOfDays) {
            throw new Exception(sprintf(
                'LayoutParser ParseDaily missing slots. $reservableSlots=%s, $blockedSlots=%s',
                count($reservableSlots),
                count($blockedSlots)
            ));
        }

        for ($day = 0; $day < DayOfWeek::NumberOfDays; $day++) {
            if (trim($reservableSlots[$day]) == '' && trim($blockedSlots[$day]) == '') {
                throw new Exception('Empty slots on ' . $day);
            }
        }

        $parser = new LayoutParser($timezone);

        foreach (DayOfWeek::Days() as $day) {
            $parser->AddReservable($reservableSlots[$day], $day);
            $parser->AddBlocked($blockedSlots[$day], $day);
        }

        return $parser->GetLayout();
    }

    /**
     * @param Date $date
     * @return SchedulePeriod period which occurs at this datetime. Includes start time, excludes end time
     */
    public function GetPeriod(Date $date)
    {
        $timezone = $this->layoutTimezone;
        $tempDate = $date->ToTimezone($timezone);
        $periods = $this->getPeriods($tempDate);

        /** @var LayoutPeriod $period */
        foreach ($periods as $period) {
            $start = Date::Create(
                $tempDate->Year(),
                $tempDate->Month(),
                $tempDate->Day(),
                $period->Start->Hour(),
                $period->Start->Minute(),
                0,
                $timezone
            );
            $end = Date::Create(
                $tempDate->Year(),
                $tempDate->Month(),
                $tempDate->Day(),
                $period->End->Hour(),
                $period->End->Minute(),
                0,
                $timezone
            );

            if ($end->IsMidnight()) {
                $end = $end->AddDays(1);
            }

            if ($start->Compare($date) <= 0 && $end->Compare($date) > 0) {
                return $this->BuildPeriod($period->PeriodTypeClass(), $start, $end, $period->Label);
            }
        }

        return null;
    }

    public function UsesDailyLayouts()
    {
        return $this->usingDailyLayouts;
    }

    private function getPeriods(Date $layoutDate)
    {
        if ($this->usingDailyLayouts) {
            $dayOfWeek = $layoutDate->Weekday();
            return $this->_periods[$dayOfWeek];
        } else {
            return $this->_periods;
        }
    }

    private function StartTimeCanBeAdded(Time $startTime, $dayOfWeek = null)
    {
        $day = $dayOfWeek;
        if ($day == null) {
            $day = 0;
        }

        if (!array_key_exists($day, $this->startTimes)) {
            $this->startTimes[$day] = [];
        }

        if (array_key_exists($startTime->ToString(), $this->startTimes[$day])) {
            return false;
        }

        $this->startTimes[$day][$startTime->ToString()] = $startTime->ToString();
        return true;
    }

    /**
     * @param Date $startDate
     * @param Date $endDate
     * @return SlotCount
     */
    public function GetSlotCount(Date $startDate, Date $endDate)
    {
        $slots = 0;
        $peakSlots = 0;
        $start = $startDate->ToTimezone($this->layoutTimezone);
        $end = $endDate->ToTimezone($this->layoutTimezone);
        $testDate = $start;

        Log::Debug('s %s e %s t %s', $start, $end, $testDate);

        $periods = $this->getPeriods($startDate);

        /** var LayoutPeriod $period */
        foreach ($periods as $period) {
            if (!$period->IsReservable()) {
                continue;
            }

            if ($start->Compare($testDate->SetTime($period->Start)) <= 0 && $end->Compare($testDate->SetTime($period->End, true)) >= 0) {
                Log::Debug($period->Start);
                Log::Debug($period->End);

                $isPeak = $this->HasPeakTimesDefined() && $this->peakTimes->IsWithinPeak($testDate->SetTime($period->Start));
                if ($isPeak) {
                    $peakSlots++;
                } else {
                    $slots++;
                }
            }
        }

        return new SlotCount($slots, $peakSlots);
    }

    public function HasPeakTimesDefined()
    {
        return $this->peakTimes != null;
    }

    public function ChangePeakTimes(PeakTimes $peakTimes)
    {
        $peakTimes->InTimezone($this->layoutTimezone);
        $this->peakTimes = $peakTimes;
    }

    public function RemovePeakTimes()
    {
        $this->peakTimes = null;
    }

    public function FitsToHours()
    {
        return true;
    }

    public function UsesCustomLayout()
    {
        return false;
    }

    public function GetType()
    {
        return ScheduleLayout::Standard;
    }
}

class SlotCount
{
    public $OffPeak = 0;
    public $Peak = 0;

    public function __construct($offPeak, $peak)
    {
        $this->OffPeak = $offPeak;
        $this->Peak = $peak;
    }
}

class PeakTimes
{
    /**
     * @return bool
     */
    public function IsAllDay()
    {
        return $this->allDay;
    }

    /**
     * @return Time|null
     */
    public function GetBeginTime()
    {
        return $this->beginTime;
    }

    /**
     * @return Time|null
     */
    public function GetEndTime()
    {
        return $this->endTime;
    }

    /**
     * @return bool
     */
    public function IsEveryDay()
    {
        return $this->everyDay;
    }

    /**
     * @return int[]
     */
    public function GetWeekdays()
    {
        return $this->weekdays;
    }

    /**
     * @return bool
     */
    public function IsAllYear()
    {
        return $this->allYear;
    }

    /**
     * @return int
     */
    public function GetBeginDay()
    {
        return $this->beginDay;
    }

    /**
     * @return int
     */
    public function GetBeginMonth()
    {
        return $this->beginMonth;
    }

    /**
     * @return int
     */
    public function GetEndDay()
    {
        return $this->endDay;
    }

    /**
     * @return int
     */
    public function GetEndMonth()
    {
        return $this->endMonth;
    }

    private $allDay = false;
    private $beginTime = null;
    private $endTime = null;
    private $everyDay = false;
    private $weekdays = [];
    private $allYear = false;
    private $beginDay = 0;
    private $beginMonth = 0;
    private $endDay = 0;
    private $endMonth = 0;

    /**
     * @param bool $allDay
     * @param string|Time $beginTime
     * @param string|Time $endTime
     * @param bool $everyDay
     * @param int[] $weekdays
     * @param bool $allYear
     * @param int $beginDay
     * @param int $beginMonth
     * @param int $endDay
     * @param int $endMonth
     */
    public function __construct($allDay, $beginTime, $endTime, $everyDay, $weekdays, $allYear, $beginDay, $beginMonth, $endDay, $endMonth)
    {
        $this->allDay = $allDay;

        $this->beginTime = new NullTime();
        $this->endTime = new NullTime();
        if (!$this->allDay) {
            $this->beginTime = is_a($beginTime, 'Time') ? $beginTime : (!empty($beginTime) ? Time::Parse($beginTime) : new NullTime());
            $this->endTime = is_a($endTime, 'Time') ? $endTime : (!empty($endTime) ? Time::Parse($endTime) : new NullTime());
        }

        $this->everyDay = $everyDay;
        if (!$this->everyDay) {
            $this->weekdays = $weekdays;
        }

        $this->allYear = $allYear;

        if (!$allYear) {
            $this->beginDay = $beginDay;
            $this->beginMonth = $beginMonth;
            $this->endDay = $endDay;
            $this->endMonth = $endMonth;
        }
    }

    public static function FromRow($row)
    {
        $allDay = intval($row[ColumnNames::PEAK_ALL_DAY]);

        $beginTime = !empty($row[ColumnNames::PEAK_START_TIME]) ? Time::Parse($row[ColumnNames::PEAK_START_TIME]) : null;
        $endTime = !empty($row[ColumnNames::PEAK_END_TIME]) ? Time::Parse($row[ColumnNames::PEAK_END_TIME]) : null;

        $everyDay = intval($row[ColumnNames::PEAK_EVERY_DAY]);

        $weekdays = !empty($row[ColumnNames::PEAK_DAYS]) ? explode(',', $row[ColumnNames::PEAK_DAYS]) : [];


        $allYear = intval($row[ColumnNames::PEAK_ALL_YEAR]);

        $beginDay = $row[ColumnNames::PEAK_BEGIN_DAY];
        $beginMonth = $row[ColumnNames::PEAK_BEGIN_MONTH];
        $endDay = $row[ColumnNames::PEAK_END_DAY];
        $endMonth = $row[ColumnNames::PEAK_END_MONTH];

        return new PeakTimes($allDay, $beginTime, $endTime, $everyDay, $weekdays, $allYear, $beginDay, $beginMonth, $endDay, $endMonth);
    }

    public function IsWithinPeak(Date $date)
    {
        $year = $date->Year();
        $endYear = $year;

        $startMonth = $this->GetBeginMonth();
        $startDay = $this->GetBeginDay();
        $endMonth = $this->GetEndMonth();
        $endDay = $this->GetEndDay();
        $endDayAddition = 0;
        $startTime = $this->GetBeginTime();
        $endTime = $this->GetEndTime();
        $weekdays = $this->GetWeekdays();

        if ($this->IsAllDay()) {
            $startTime = new Time(0, 0, 0, $date->Timezone());
            $endTime = new Time(0, 0, 0, $date->Timezone());
        }

        if ($this->IsAllYear()) {
            $startMonth = 1;
            $endMonth = 1;
            $startDay = 1;
            $endDay = 1;
        }

        if ($this->IsEveryDay() || empty($weekdays) || !is_array($weekdays)) {
            $weekdays = null;
        }

        if ($endMonth <= $startMonth) {
            $endYear = $year + 1;
        }

        if ($endTime->Compare($startTime) <= 0) {
            $endDayAddition = 1;
        }

        $peakStart = Date::Create($year, $startMonth, $startDay, $startTime->Hour(), $startTime->Minute(), 0, $date->Timezone());
        $peakEnd = Date::Create($endYear, $endMonth, $endDay, $endTime->Hour(), $endTime->Minute(), 0, $date->Timezone())->AddDays($endDayAddition);

        if ($date->Compare($peakStart) >= 0 && $date->Compare($peakEnd) <= 0) {
            $isPeakHour = $this->IsAllDay() || ($date->CompareTimes($startTime) >= 0 && $date->CompareTimes($endTime) < 0);
            $isPeakWeekday = true;

            if ($weekdays != null) {
                $isPeakWeekday = in_array($date->Weekday(), $weekdays);
            }

            if ($isPeakHour && $isPeakWeekday) {
                Log::Debug('Date %s is within peak start %s end %s', $date, $peakStart, $peakEnd);
                return true;
            }
        }

        Log::Debug('Date %s is not within peak %s end %s', $date, $peakStart, $peakEnd);
        return false;
    }

    public function InTimezone($timezone)
    {
        if (!$this->IsAllDay()) {
            $this->beginTime = new Time($this->beginTime->Hour(), $this->beginTime->Minute(), 0, $timezone);
            $this->endTime = new Time($this->endTime->Hour(), $this->endTime->Minute(), 0, $timezone);
        }
    }
}

class LayoutParser
{
    private $layout;
    private $timezone;

    public function __construct($timezone)
    {
        $this->layout = new ScheduleLayout($timezone);
        $this->timezone = $timezone;
    }

    public function AddReservable($reservableSlots, $dayOfWeek = null)
    {
        $cb = [$this, 'appendPeriod'];
        $this->ParseSlots($reservableSlots, $dayOfWeek, $cb);
    }

    public function AddBlocked($blockedSlots, $dayOfWeek = null)
    {
        $cb = [$this, 'appendBlocked'];

        $this->ParseSlots($blockedSlots, $dayOfWeek, $cb);
    }

    public function GetLayout()
    {
        return $this->layout;
    }

    private function appendPeriod($start, $end, $label, $dayOfWeek = null)
    {
        $this->layout->AppendPeriod(
            Time::Parse($start, $this->timezone),
            Time::Parse($end, $this->timezone),
            $label,
            $dayOfWeek
        );
    }

    private function appendBlocked($start, $end, $label, $dayOfWeek = null)
    {
        $this->layout->AppendBlockedPeriod(
            Time::Parse($start, $this->timezone),
            Time::Parse($end, $this->timezone),
            $label,
            $dayOfWeek
        );
    }

    private function ParseSlots($allSlots, $dayOfWeek, $callback)
    {
        $trimmedSlots = trim($allSlots);
        //		if (empty($trimmedSlots) && $dayOfWeek == null)
        //		{
        //			throw new Exception('Empty slots on ' . $dayOfWeek);
        //		}

        $lines = preg_split("/[\n]/", $trimmedSlots, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($lines as $slotLine) {
            $label = null;
            $parts = preg_split(
                '/(\d?\d:\d\d\s*\-\s*\d?\d:\d\d)(.*)/',
                trim($slotLine),
                -1,
                PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
            );
            $times = explode('-', $parts[0]);
            $start = trim($times[0]);
            $end = trim($times[1]);

            if (count($parts) > 1) {
                $label = trim($parts[1]);
            }

            call_user_func($callback, $start, $end, $label, $dayOfWeek);
        }
    }
}

class LayoutPeriod
{
    /**
     * @var Time
     */
    public $Start;

    /**
     * @var Time
     */
    public $End;

    /**
     * @var PeriodTypes
     */
    public $PeriodType;

    /**
     * @var string
     */
    public $Label;

    /**
     * @return string
     */
    public function PeriodTypeClass()
    {
        if ($this->PeriodType == PeriodTypes::RESERVABLE) {
            return 'SchedulePeriod';
        }

        return 'NonSchedulePeriod';
    }

    /**
     * @return bool
     */
    public function IsReservable()
    {
        return $this->PeriodType == PeriodTypes::RESERVABLE;
    }

    /**
     * @return bool
     */
    public function IsLabelled()
    {
        return !empty($this->Label);
    }

    /**
     * @return string
     */
    public function Timezone()
    {
        return $this->Start->Timezone();
    }

    public function __construct(Time $start, Time $end, $periodType = PeriodTypes::RESERVABLE, $label = null)
    {
        $this->Start = $start;
        $this->End = $end;
        $this->PeriodType = $periodType;
        $this->Label = $label;
    }

    /**
     * Compares the starting times
     */
    public function Compare(LayoutPeriod $other)
    {
        return $this->Start->Compare($other->Start);
    }
}

class PeriodList
{
    /**
     * @var SchedulePeriod[]
     */
    private $items = [];
    private $_addedStarts = [];
    private $_addedTimes = [];
    private $_addedEnds = [];

    public function Add(SchedulePeriod $period)
    {
        if ($this->AlreadyAdded($period->BeginDate(), $period->EndDate())) {
            return;
        }

        $this->items[] = $period;
    }

    /**
     * @return SchedulePeriod[]
     */
    public function GetItems()
    {
        return $this->items;
    }

    private function AlreadyAdded(Date $start, Date $end)
    {
        $startExists = false;
        $endExists = false;

        if (array_key_exists($start->Timestamp(), $this->_addedStarts)) {
            $startExists = true;
        }

        if (array_key_exists($end->Timestamp(), $this->_addedEnds)) {
            $endExists = true;
        }

        $this->_addedTimes[$start->Timestamp()] = true;
        $this->_addedEnds[$end->Timestamp()] = true;

        return $startExists || $endExists;
    }
}

class ReservationLayout extends ScheduleLayout implements IScheduleLayout
{
    protected function SpansMidnight(Date $start, Date $end)
    {
        return false;
    }
}

class CustomScheduleLayout extends ScheduleLayout implements IScheduleLayout
{
    /**
     * @var IScheduleRepository
     */
    private $repository;

    /**
     * @var int
     */
    private $scheduleId;

    /**
     * @var SchedulePeriod[]
     */
    private $cache = [];

    /**
     * @param string $targetTimezone
     * @param int $scheduleId
     * @param IScheduleRepository $repository
     */
    public function __construct($targetTimezone, $scheduleId, IScheduleRepository $repository)
    {
        $this->repository = $repository;
        $this->scheduleId = $scheduleId;
        parent::__construct($targetTimezone);
    }

    public function UsesCustomLayout()
    {
        return true;
    }

    public function UsesDailyLayouts()
    {
        return false;
    }

    public function GetLayout(Date $layoutDate, $hideBlockedPeriods = false)
    {
        if ($this->IsCached($layoutDate)) {
            $allPeriods = $this->GetCached($layoutDate);
        } else {
            $periods = $this->repository->GetCustomLayoutPeriods($layoutDate, $this->scheduleId);
            if (count($periods) == 0) {
                return [];
            }

            $allPeriods = [];
            if (!$hideBlockedPeriods) {
                $previous = $layoutDate->GetDate();
                foreach ($periods as $period) {
                    if (!$period->BeginDate()->Equals($previous) && !$period->EndDate()->IsMidnight()) {
                        $allPeriods[] = new NonSchedulePeriod($previous, $period->BeginDate());
                    }
                    $allPeriods[] = $period;
                    $previous = $period->EndDate();
                }

                $lastPeriod = $periods[count($periods) - 1];
                if (!$lastPeriod->EndDate()->IsMidnight()) {
                    $allPeriods[] = new NonSchedulePeriod($lastPeriod->EndDate(), $lastPeriod->EndDate()->AddDays(1)->GetDate());
                }
            } else {
                $allPeriods = $periods;
            }

            $this->cache[$this->GetCacheKey($layoutDate)] = $allPeriods;
        }

        return $allPeriods;
    }

    public function GetLayoutSlots(Date $startDate, Date $endDate)
    {
    }

    public function FitsToHours()
    {
        return false;
    }

    /**
     * @param Date $layoutDate
     * @return bool
     */
    private function IsCached($layoutDate)
    {
        return array_key_exists($this->GetCacheKey($layoutDate), $this->cache);
    }

    /**
     * @param Date $layoutDate
     * @return SchedulePeriod[]
     */
    private function GetCached($layoutDate)
    {
        return $this->cache[$this->GetCacheKey($layoutDate)];
    }

    /**
     * @param Date $layoutDate
     * @return int
     */
    private function GetCacheKey(Date $layoutDate)
    {
        return $layoutDate->GetDate()->Timestamp();
    }

    public function GetPeriod(Date $date)
    {
        $layout = $this->GetLayout($date);
        foreach ($layout as $period) {
            if ($period->BeginDate()->Equals($date)) {
                return $period;
            }
        }

        return null;
    }

    public function GetSlotCount(Date $startDate, Date $endDate)
    {
        return 1;
    }

    public function GetType()
    {
        return ScheduleLayout::Custom;
    }

    public function AppendPeriod(Time $startTime, Time $endTime, $label = null, $dayOfWeek = null)
    {
    }

    public function AppendBlockedPeriod(Time $startTime, Time $endTime, $label = null, $dayOfWeek = null)
    {
    }
}
