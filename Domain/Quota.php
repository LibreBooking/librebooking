<?php

interface IQuota
{
    /**
     * @param ReservationSeries $reservationSeries
     * @param User $user
     * @param Schedule $schedule
     * @param IReservationViewRepository $reservationViewRepository
     * @return bool
     */
    public function ExceedsQuota($reservationSeries, $user, $schedule, IReservationViewRepository $reservationViewRepository);

    /**
     * @return int
     */
    public function Id();

    public function ToString();
}

class Quota implements IQuota
{
    /**
     * @var int
     */
    private $quotaId;

    /**
     * @var \IQuotaDuration
     */
    private $duration;

    /**
     * @var \IQuotaLimit
     */
    private $limit;

    /**
     * @var int
     */
    private $resourceId;

    /**
     * @var int
     */
    private $groupId;

    /**
     * @var int
     */
    private $scheduleId;

    /**
     * @var null|Time
     */
    private $enforcedStartTime;

    /**
     * @var null|Time
     */
    private $enforcedEndTime;

    /**
     * @var array|int[]|string[]
     */
    private $enforcedDays = [];

    /**
     * @var IQuotaScope
     */
    private $scope;

    /**
     * @var Schedule
     */
    private $schedule;

    /**
     * @param int $quotaId
     * @param IQuotaDuration $duration
     * @param IQuotaLimit $limit
     * @param int $resourceId
     * @param int $groupId
     * @param int $scheduleId
     * @param string $enforcedStartTime
     * @param string|null $enforcedEndTime
     * @param array $enforcedDays
     * @param IQuotaScope $scope
     */
    public function __construct(
        $quotaId,
        $duration,
        $limit,
        $resourceId = null,
        $groupId = null,
        $scheduleId = null,
        $enforcedStartTime = null,
        $enforcedEndTime = null,
        $enforcedDays = [],
        $scope = null
    ) {
        $this->quotaId = $quotaId;
        $this->duration = $duration;
        $this->limit = $limit;
        $this->resourceId = empty($resourceId) ? null : $resourceId;
        $this->groupId = empty($groupId) ? null : $groupId;
        $this->scheduleId = empty($scheduleId) ? null : $scheduleId;
        $this->enforcedStartTime = empty($enforcedStartTime) ? null : Time::Parse($enforcedStartTime);
        $this->enforcedEndTime = empty($enforcedEndTime) ? null : Time::Parse($enforcedEndTime);
        $this->enforcedDays = empty($enforcedDays) ? [] : $enforcedDays;
        $this->scope = empty($scope) ? new QuotaScopeIncluded() : $scope;
    }

    /**
     * @static
     * @param string $duration
     * @param float $limit
     * @param string $unit
     * @param int $resourceId
     * @param int $groupId
     * @param int $scheduleId
     * @param string $enforcedStartTime
     * @param string $enforcedEndTime
     * @param array|int[]|string[] $enforcedDays
     * @param string $scope
     * @return Quota
     */
    public static function Create($duration, $limit, $unit, $resourceId, $groupId, $scheduleId, $enforcedStartTime, $enforcedEndTime, $enforcedDays, $scope)
    {
        return new Quota(
            0,
            self::CreateDuration($duration),
            self::CreateLimit($limit, $unit),
            $resourceId,
            $groupId,
            $scheduleId,
            $enforcedStartTime,
            $enforcedEndTime,
            $enforcedDays,
            self::CreateScope($scope)
        );
    }

    /**
     * @static
     * @param float $limit
     * @param string $unit QuotaUnit
     * @return IQuotaLimit
     */
    public static function CreateLimit($limit, $unit)
    {
        if ($unit == QuotaUnit::Reservations) {
            return new QuotaLimitCount($limit);
        }

        return new QuotaLimitHours($limit);
    }

    /**
     * @static
     * @param string $duration QuotaDuration
     * @return IQuotaDuration
     */
    public static function CreateDuration($duration)
    {
        if ($duration == QuotaDuration::Day) {
            return new QuotaDurationDay();
        }

        if ($duration == QuotaDuration::Week) {
            return new QuotaDurationWeek();
        }

        if ($duration == QuotaDuration::Month) {
            return new QuotaDurationMonth();
        }
        return new QuotaDurationYear();
    }

    /**
     * @param string|QuotaScope $scope
     * @return IQuotaScope
     */
    public static function CreateScope($scope)
    {
        if ($scope == QuotaScope::ExcludeCompleted) {
            return new QuotaScopeExcluded();
        }

        return new QuotaScopeIncluded();
    }

    /**
     * @param ReservationSeries $reservationSeries
     * @param User $user
     * @param Schedule $schedule
     * @param IReservationViewRepository $reservationViewRepository
     * @return bool
     */
    public function ExceedsQuota($reservationSeries, $user, $schedule, IReservationViewRepository $reservationViewRepository)
    {
        $timezone = $schedule->GetTimezone();
        $this->schedule = $schedule;

        if (!is_null($this->resourceId)) {
            $appliesToResource = false;

            foreach ($reservationSeries->AllResourceIds() as $resourceId) {
                if (!$appliesToResource && $this->AppliesToResource($resourceId)) {
                    $appliesToResource = true;
                }
            }

            if (!$appliesToResource) {
                return false;
            }
        }

        if (!is_null($this->groupId)) {
            $appliesToGroup = false;
            foreach ($user->Groups() as $group) {
                if (!$appliesToGroup && $this->AppliesToGroup($group->GroupId)) {
                    $appliesToGroup = true;
                }
            }

            if (!$appliesToGroup) {
                return false;
            }
        }

        if (!$this->AppliesToSchedule($reservationSeries->ScheduleId())) {
            return false;
        }

        if (count($reservationSeries->Instances()) == 0) {
            return false;
        }

        $dates = $this->duration->GetSearchDates($reservationSeries, $timezone, $this->GetFirstWeekday());
        $reservationsWithinRange = $reservationViewRepository->GetReservations(
            $this->GetScope()->GetSearchStartDate($dates->Start()),
            $dates->End(),
            $reservationSeries->UserId(),
            ReservationUserLevel::OWNER
        );

        try {
            $this->CheckAll($reservationsWithinRange, $reservationSeries, $timezone);
        } catch (QuotaExceededException $ex) {
            return true;
        }

        return false;
    }

    public function ToString()
    {
        return $this->__toString();
    }

    public function __toString()
    {
        return sprintf(
            'Quota Id=%s, ResourceId=%s, ScheduleId=%s, GroupId=%s, Limit=%s, Duration=%s',
            $this->quotaId,
            $this->ResourceId(),
            $this->ScheduleId(),
            $this->GroupId(),
            $this->GetLimit(),
            $this->GetDuration()
        );
    }

    /**
     * @return IQuotaLimit
     */
    public function GetLimit()
    {
        return $this->limit;
    }

    /**
     * @return IQuotaDuration
     */
    public function GetDuration()
    {
        return $this->duration;
    }

    /**
     * @return IQuotaScope
     */
    public function GetScope()
    {
        return $this->scope;
    }

    /**
     * @param int $resourceId
     * @return bool
     */
    public function AppliesToResource($resourceId)
    {
        return is_null($this->resourceId) || $this->resourceId == $resourceId;
    }

    /**
     * @param int $groupId
     * @return bool
     */
    public function AppliesToGroup($groupId)
    {
        return is_null($this->groupId) || $this->groupId == $groupId;
    }

    /**
     * @param int $scheduleId
     * @return bool
     */
    public function AppliesToSchedule($scheduleId)
    {
        return is_null($this->scheduleId) || $this->scheduleId == $scheduleId;
    }

    /**
     * @return int
     */
    public function Id()
    {
        return $this->quotaId;
    }

    /**
     * @return int|null
     */
    public function ResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @return int|null
     */
    public function GroupId()
    {
        return $this->groupId;
    }

    /**
     * @return int|null
     */
    public function ScheduleId()
    {
        return $this->scheduleId;
    }

    /**
     * @return null|Time
     */
    public function EnforcedStartTime()
    {
        return $this->enforcedStartTime;
    }

    /**
     * @return null|Time
     */
    public function EnforcedEndTime()
    {
        return $this->enforcedEndTime;
    }

    /**
     * @return array|int[]|string[]
     */
    public function EnforcedDays()
    {
        return $this->enforcedDays;
    }

    /**
     * @return int
     */
    protected function GetFirstWeekday()
    {
        if ($this->schedule != null) {
            $start = $this->schedule->GetWeekdayStart();

            if ($start == Schedule::Today) {
                return Date::Now()->ToTimezone($this->schedule->GetTimezone())->Weekday();
            }

            return $start;
        }

        return 0;
    }

    /**
     * @return bool
     */
    private function EnforcedAllDay()
    {
        return $this->enforcedStartTime == null || $this->enforcedEndTime == null;
    }

    /**
     * @return bool
     */
    private function EnforcedEveryDay()
    {
        return empty($this->enforcedDays);
    }

    /**
     * @param int $weekday
     * @return bool
     */
    private function EnforcedOnWeekday($weekday)
    {
        return in_array($weekday, $this->enforcedDays);
    }

    private function AddExisting(ReservationItemView $reservation, $timezone)
    {
        $this->_breakAndAdd($reservation->StartDate, $reservation->EndDate, $timezone);
    }

    private function AddInstance(Reservation $reservation, $timezone)
    {
        $this->_breakAndAdd($reservation->StartDate(), $reservation->EndDate(), $timezone);
    }

    /**
     * @param array|ReservationItemView[] $reservationsWithinRange
     * @param ReservationSeries $series
     * @param string $timezone
     * @throws QuotaExceededException
     */
    private function CheckAll($reservationsWithinRange, $series, $timezone)
    {
        $toBeSkipped = [];

        /** @var Reservation $instance */
        foreach ($series->Instances() as $instance) {
            $toBeSkipped[$instance->ReferenceNumber()] = true;

            if (!is_null($this->scheduleId)) {
                foreach ($series->AllResources() as $resource) {
                    // add each resource instance
                    if ($this->AppliesToResource($resource->GetResourceId())) {
                        $this->AddInstance($instance, $timezone);
                    }
                }
            } else {
                $this->AddInstance($instance, $timezone);
            }
        }

        /** @var ReservationItemView $reservation */
        foreach ($reservationsWithinRange as $reservation) {
            if (!empty($this->resourceId)) {
                $applies = ($this->AppliesToResource($reservation->ResourceId) && $series->ContainsResource($reservation->ResourceId));
            } else {
                $applies = $series->ContainsResource($reservation->ResourceId) || ($series->ScheduleId() == $reservation->ScheduleId);
            }

            if ($applies &&
                    !array_key_exists($reservation->ReferenceNumber, $toBeSkipped) &&
                    !$this->willBeDeleted($series, $reservation->ReservationId)
            ) {
                $this->AddExisting($reservation, $timezone);
            }
        }
    }

    /**
     * @param ExistingReservationSeries $series
     * @param int $reservationId
     * @return bool
     */
    private function willBeDeleted($series, $reservationId)
    {
        if (method_exists($series, 'IsMarkedForDelete')) {
            return $series->IsMarkedForDelete($reservationId);
        }

        return false;
    }

    private function _breakAndAdd(Date $startDate, Date $endDate, $timezone)
    {
        $start = $startDate->ToTimezone($timezone);
        $end = $endDate->ToTimezone($timezone);

        $range = new DateRange($start, $end);

        $ranges = $this->duration->Split($range, $this->GetFirstWeekday());

        foreach ($ranges as $dr) {
            $this->_add($dr);
        }
    }

    private function _add(DateRange $dateRange)
    {
        if (!$this->EnforcedEveryDay() && !$this->EnforcedOnWeekday($dateRange->GetBegin()->Weekday())) {
            return;
        }

        if (!$this->EnforcedAllDay()) {
            $enforcedStart = $dateRange->GetBegin()->SetTime($this->EnforcedStartTime());
            $enforcedEnd = $dateRange->GetBegin()->SetTime($this->EnforcedEndTime());
            $enforcedRange = new DateRange($enforcedStart, $enforcedEnd);
            if (!$enforcedRange->Overlaps($dateRange)) {
                return;
            }
            $newStart = $dateRange->GetBegin()->GreaterThan($enforcedStart) ? $dateRange->GetBegin() : $enforcedStart;
            $newEnd = $dateRange->GetEnd()->LessThan($enforcedEnd) ? $dateRange->GetEnd() : $enforcedEnd;
            $dateRange = new DateRange($newStart, $newEnd);
        }
        $durationKey = $this->duration->GetDurationKey($dateRange->GetBegin(), $this->GetFirstWeekday());

        $this->limit->TryAdd($dateRange->GetBegin(), $dateRange->GetEnd(), $durationKey);
    }
}

class QuotaUnit
{
    public const Hours = 'hours';
    public const Reservations = 'reservations';
}

interface IQuotaDuration
{
    /**
     * @return string QuotaDuration
     */
    public function Name();

    /**
     * @param ReservationSeries $reservationSeries
     * @param string $timezone
     * @param int $firstWeekday
     * @return QuotaSearchDates
     */
    public function GetSearchDates(ReservationSeries $reservationSeries, $timezone, $firstWeekday);

    /**
     * @param DateRange $dateRange
     * @param int $firstWeekday
     * @return array|DateRange[]
     */
    public function Split(DateRange $dateRange, $firstWeekday);

    /**
     * @param Date $date
     * @param int $firstWeekday
     * @return string
     */
    public function GetDurationKey(Date $date, $firstWeekday);
}

class QuotaSearchDates
{
    /**
     * @var \Date
     */
    private $start;

    /**
     * @var \Date
     */
    private $end;

    public function __construct(Date $start, Date $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return Date
     */
    public function Start()
    {
        return $this->start;
    }

    /**
     * @return Date
     */
    public function End()
    {
        return $this->end;
    }
}

abstract class QuotaDuration implements IQuotaDuration
{
    public const Day = 'day';
    public const Week = 'week';
    public const Month = 'month';
    public const Year = 'year';

    /**
     * @param ReservationSeries $reservationSeries
     * @return array|Date[]
     */
    protected function GetFirstAndLastReservationDates(ReservationSeries $reservationSeries)
    {
        /** @var Reservation[] $instances */
        $instances = $reservationSeries->Instances();
        usort($instances, ['Reservation', 'Compare']);

        return [$instances[0]->StartDate(), $instances[count($instances) - 1]->EndDate()];
    }

    public function __toString()
    {
        return sprintf('QuotaDuration Name=%s', $this->Name());
    }
}

class QuotaDurationDay extends QuotaDuration
{
    public function GetSearchDates(ReservationSeries $reservationSeries, $timezone, $firstWeekday)
    {
        $dates = $this->GetFirstAndLastReservationDates($reservationSeries);

        $startDate = $dates[0]->ToTimezone($timezone)->GetDate();
        $endDate = $dates[1]->ToTimezone($timezone)->AddDays(1)->GetDate();

        return new QuotaSearchDates($startDate, $endDate);
    }

    public function Split(DateRange $dateRange, $firstWeekday)
    {
        $start = $dateRange->GetBegin();
        $end = $dateRange->GetEnd();

        $ranges = [];

        if (!$start->DateEquals($end)) {
            $currentDate = $start;

            for ($i = 1; $currentDate->DateCompare($end) < 0; $i++) {
                $ranges[] = new DateRange($currentDate, $currentDate->AddDays(1)->GetDate());
                $currentDate = $start->AddDays($i)->GetDate();
            }

            if (!$currentDate->Equals($end)) {
                $ranges[] = new DateRange($currentDate, $end);
            }
        } else {
            $ranges[] = new DateRange($start, $end);
        }

        //		Log::Debug("Split %s into %s", $dateRange, var_export($ranges, true));
        return $ranges;
    }

    public function GetDurationKey(Date $date, $firstWeekday)
    {
        return sprintf("%s%s%s", $date->Year(), $date->Month(), $date->Day());
    }

    /**
     * @return string QuotaDuration
     */
    public function Name()
    {
        return QuotaDuration::Day;
    }
}

class QuotaDurationWeek extends QuotaDuration
{
    public function GetSearchDates(ReservationSeries $reservationSeries, $timezone, $firstWeekday)
    {
        $dates = $this->GetFirstAndLastReservationDates($reservationSeries);

        $startDate = $dates[0]->ToTimezone($timezone)->GetDate();
        $selectedWeekday = $startDate->Weekday();
        $adjustedDays = ($firstWeekday - $selectedWeekday);
        if ($selectedWeekday < $firstWeekday) {
            $adjustedDays = $adjustedDays - 7;
        }
        $startDate = $startDate->AddDays($adjustedDays);

        $endDate = $dates[1]->ToTimezone($timezone);
        $daysFromWeekEnd = 7 - $endDate->Weekday() + $firstWeekday;
        if ($daysFromWeekEnd > 7) {
            $daysFromWeekEnd = $daysFromWeekEnd -7;
        }
        $endDate = $endDate->AddDays($daysFromWeekEnd)->GetDate();

        return new QuotaSearchDates($startDate, $endDate);
    }

    public function GetDurationKey(Date $date, $firstWeekday)
    {
        $daysFromWeekStart = $date->Weekday() - $firstWeekday;
        if ($daysFromWeekStart < 0) {
            $daysFromWeekStart = $daysFromWeekStart + 7;
        }
        $firstDayOfWeek = $date->AddDays(-$daysFromWeekStart)->GetDate();
        return sprintf("%s%s%s", $firstDayOfWeek->Year(), $firstDayOfWeek->Month(), $firstDayOfWeek->Day());
    }

    public function Split(DateRange $dateRange, $firstWeekday)
    {
        $start = $dateRange->GetBegin();
        $end = $dateRange->GetEnd();

        $ranges = [];

        if (!$start->DateEquals($end)) {
            $nextWeek = $this->GetStartOfNextWeek($start, $firstWeekday);

            if ($nextWeek->LessThan($end)) {
                $ranges[] = new DateRange($start, $nextWeek);
                while ($nextWeek->LessThan($end)) {
                    $thisEnd = $this->GetStartOfNextWeek($nextWeek, $firstWeekday);

                    if ($thisEnd->LessThan($end)) {
                        $ranges[] = new DateRange($nextWeek, $thisEnd);
                    } else {
                        $ranges[] = new DateRange($nextWeek, $end);
                    }

                    $nextWeek = $thisEnd;
                }
            } else {
                $ranges[] = new DateRange($start, $end);
            }
        } else {
            $ranges[] = new DateRange($start, $end);
        }


        return $ranges;
    }

    /**
     * @param Date $date
     * @param int $firstWeekday
     * @return Date
     */
    private function GetStartOfNextWeek(Date $date, $firstWeekday)
    {
        $daysFromWeekEnd = 7 - $date->Weekday() + $firstWeekday;
        return $date->AddDays($daysFromWeekEnd)->GetDate();
    }

    /**
     * @return string QuotaDuration
     */
    public function Name()
    {
        return QuotaDuration::Week;
    }
}

class QuotaDurationMonth extends QuotaDuration
{
    public function GetSearchDates(ReservationSeries $reservationSeries, $timezone, $firstWeekday)
    {
        $minMax = $this->GetFirstAndLastReservationDates($reservationSeries);

        /** @var Date $start */
        $start = $minMax[0]->ToTimezone($timezone);
        /** @var Date $end */
        $end = $minMax[1]->ToTimezone($timezone);

        $searchStart = Date::Create($start->Year(), $start->Month(), 1, 0, 0, 0, $timezone);
        $searchEnd = Date::Create($end->Year(), $end->Month() + 1, 1, 0, 0, 0, $timezone);

        return new QuotaSearchDates($searchStart, $searchEnd);
    }

    public function Split(DateRange $dateRange, $firstWeekday)
    {
        $ranges = [];

        $start = $dateRange->GetBegin();
        $end = $dateRange->GetEnd();

        if (!$this->SameMonth($start, $end)) {
            $current = $start;

            while (!$this->SameMonth($current, $end)) {
                $next = $this->GetFirstOfMonth($current, 1);

                $ranges[] = new DateRange($current, $next);

                $current = $next;

                if ($this->SameMonth($current, $end)) {
                    $ranges[] = new DateRange($current, $end);
                }
            }
        } else {
            $ranges[] = $dateRange;
        }

        return $ranges;
    }

    /**
     * @param Date $date
     * @param int $monthOffset
     * @return Date
     */
    private function GetFirstOfMonth(Date $date, $monthOffset = 0)
    {
        return Date::Create($date->Year(), $date->Month() + $monthOffset, 1, 0, 0, 0, $date->Timezone());
    }

    /**
     * @param Date $d1
     * @param Date $d2
     * @return bool
     */
    private function SameMonth(Date $d1, Date $d2)
    {
        return ($d1->Month() == $d2->Month()) && ($d1->Year() == $d2->Year());
    }

    public function GetDurationKey(Date $date, $firstWeekday)
    {
        return sprintf("%s%s", $date->Year(), $date->Month());
    }

    /**
     * @return string QuotaDuration
     */
    public function Name()
    {
        return QuotaDuration::Month;
    }
}

class QuotaDurationYear extends QuotaDuration
{
    /**
     * @return string QuotaDuration
     */
    public function Name()
    {
        return QuotaDuration::Year;
    }

    public function GetSearchDates(ReservationSeries $reservationSeries, $timezone, $firstWeekday)
    {
        $minMax = $this->GetFirstAndLastReservationDates($reservationSeries);

        /** @var Date $start */
        $start = $minMax[0]->ToTimezone($timezone);
        /** @var Date $end */
        $end = $minMax[1]->ToTimezone($timezone);

        $searchStart = Date::Create($start->Year(), 1, 1, 0, 0, 0, $timezone);
        $searchEnd = Date::Create($end->Year() + 1, 1, 1, 0, 0, 0, $timezone);

        return new QuotaSearchDates($searchStart, $searchEnd);
    }

    public function Split(DateRange $dateRange, $firstWeekday)
    {
        $ranges = [];

        $start = $dateRange->GetBegin();
        $end = $dateRange->GetEnd();

        if (!$this->SameYear($start, $end)) {
            $current = $start;

            while (!$this->SameYear($current, $end)) {
                $next = $this->GetFirstOfYear($current, 1);

                $ranges[] = new DateRange($current, $next);

                $current = $next;

                if ($this->SameYear($current, $end)) {
                    $ranges[] = new DateRange($current, $end);
                }
            }
        } else {
            $ranges[] = $dateRange;
        }

        return $ranges;
    }

    /**
     * @param Date $date
     * @param int $yearOffset
     * @return Date
     */
    private function GetFirstOfYear(Date $date, $yearOffset = 0)
    {
        return Date::Create($date->Year() + $yearOffset, 1, 1, 0, 0, 0, $date->Timezone());
    }

    /**
     * @param Date $d1
     * @param Date $d2
     * @return bool
     */
    private function SameYear(Date $d1, Date $d2)
    {
        return ($d1->Year() == $d2->Year());
    }

    public function GetDurationKey(Date $date, $firstWeekday)
    {
        return sprintf("Y%s", $date->Year());
    }
}

interface IQuotaLimit
{
    /**
     * @param Date $start
     * @param Date $end
     * @param string $key
     */
    public function TryAdd($start, $end, $key);

    /**
     * @return float
     */
    public function Amount();

    /**
     * @return string|QuotaUnit
     */
    public function Name();
}

class QuotaLimitCount implements IQuotaLimit
{
    /**
     * @var array|int[]
     */
    private $aggregateCounts = [];

    /**
     * @var int
     */
    private $totalAllowed;

    /**
     * @param float $totalAllowed
     */
    public function __construct($totalAllowed)
    {
        $this->totalAllowed = $totalAllowed;
    }

    public function TryAdd($start, $end, $key)
    {
        if (array_key_exists($key, $this->aggregateCounts)) {
            $this->aggregateCounts[$key] = $this->aggregateCounts[$key] + 1;
        } else {
            $this->aggregateCounts[$key] = 1;
        }

        if ($this->aggregateCounts[$key] > $this->totalAllowed) {
            throw new QuotaExceededException("Only {$this->totalAllowed} reservations are allowed for this duration");
        }
    }

    /**
     * @return float
     */
    public function Amount()
    {
        return $this->totalAllowed;
    }

    /**
     * @return string|QuotaUnit
     */
    public function Name()
    {
        return QuotaUnit::Reservations;
    }

    public function __toString()
    {
        return sprintf('QuotaLimitCount Name=%s, Amount=%s', $this->Name(), $this->Amount());
    }
}

class QuotaLimitHours implements IQuotaLimit
{
    /**
     * @var array|DateDiff[]
     */
    private $aggregateCounts = [];

    /**
     * @var \DateDiff
     */
    private $allowedDuration;

    /**
     * @var float
     */
    private $allowedHours;

    /**
     * @param float $allowedHours
     */
    public function __construct($allowedHours)
    {
        $this->allowedHours = $allowedHours;
        $this->allowedDuration = new DateDiff($allowedHours * 3600);
    }

    /**
     * @param Date $start
     * @param Date $end
     * @param string $key
     * @throws QuotaExceededException
     */
    public function TryAdd($start, $end, $key)
    {
        $diff = $start->GetDifference($end);

        if (array_key_exists($key, $this->aggregateCounts)) {
            $this->aggregateCounts[$key] = $this->aggregateCounts[$key]->Add($diff);
        } else {
            $this->aggregateCounts[$key] = $diff;
        }

        if ($this->aggregateCounts[$key]->GreaterThan($this->allowedDuration)) {
            throw new QuotaExceededException("Cumulative reservation length cannot exceed {$this->allowedHours} hours for this duration");
        }
    }

    /**
     * @return float
     */
    public function Amount()
    {
        return $this->allowedHours;
    }

    /**
     * @return string|QuotaUnit
     */
    public function Name()
    {
        return QuotaUnit::Hours;
    }

    public function __toString()
    {
        return sprintf('QuotaLimitHours Name=%s Amount=%s', $this->Name(), $this->Amount());
    }
}

interface IQuotaScope
{
    /**
     * @return string|QuotaScope
     */
    public function Name();

    /**
     * @param Date $startDate
     * @return Date
     */
    public function GetSearchStartDate($startDate);
}

abstract class QuotaScope implements IQuotaScope
{
    public const IncludeCompleted = 'IncludeCompleted';
    public const ExcludeCompleted = 'ExcludeCompleted';

    public function __toString()
    {
        return sprintf('QuotaScope Name=%s', $this->Name());
    }
}

class QuotaScopeExcluded extends QuotaScope
{
    public function Name()
    {
        return QuotaScope::ExcludeCompleted;
    }

    public function GetSearchStartDate($startDate)
    {
        return Date::Now();
    }
}

class QuotaScopeIncluded extends QuotaScope
{
    public function Name()
    {
        return QuotaScope::IncludeCompleted;
    }

    public function GetSearchStartDate($startDate)
    {
        return $startDate;
    }
}

class QuotaExceededException extends Exception
{
    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
