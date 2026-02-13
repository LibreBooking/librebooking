<?php

class ReportUtilizationData
{
    /**
     * @var IScheduleLayout[]
     */
    private $layouts = [];
    /**
     * @var ReportDailyAvailability[]
     */
    private $weekdayAvailability = [];

    /**
     * @var ReportDailyAvailability[]
     */
    private $customAvailability = [];

    /**
     * @var array
     */
    private $data;
    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;
    /**
     * @var Report_Range
     */
    private $range;
    /**
     * @var int[]
     */
    private $totals = [];
    /**
     * @var int[]
     */
    private $unavailable = [];
    /**
     * @var string
     */
    private $timezone;

    /**
     * @param array $data
     * @param IScheduleRepository $scheduleRepository
     * @param Report_Range $range
     * @param string $timezone
     */
    public function __construct($data, $scheduleRepository, $range, $timezone)
    {
        $this->data = $data;
        $this->scheduleRepository = $scheduleRepository;
        $this->range = $range;
        $this->timezone = $timezone;
    }

    public function Rows()
    {
        $earliest = Date::Max();
        $latest = Date::Min();
        $resources = [];

        foreach ($this->data as $row) {
            $scheduleId = $row[ColumnNames::SCHEDULE_ID];
            $resourceId = $row[ColumnNames::RESOURCE_ID];
            $type = $row[ColumnNames::UTILIZATION_TYPE];

            if (!array_key_exists($resourceId, $resources)) {
                $resources[$resourceId] = $row;
            }

            $start = Date::FromDatabase($row[ColumnNames::RESERVATION_START])->ToTimezone($this->timezone);
            $end = Date::FromDatabase($row[ColumnNames::RESERVATION_END])->ToTimezone($this->timezone);

            if ($start->LessThan($earliest)) {
                $earliest = $start;
            }
            if ($end->GreaterThan($latest)) {
                $latest = $end;
            }

            if ($type == 1) {
                $this->AddReservation($start, $end, $resourceId, $scheduleId);
            } else {
                $this->AddBlackout($start, $end, $resourceId, $scheduleId);
            }
        }

        $rows = [];
        $rowIndex = 0;
        if ($this->range->Equals(Report_Range::ALL_TIME)) {
            $dates = (new DateRange($earliest, $latest))->Dates();
        } else {
            $dates = $this->range->Dates();
        }

        foreach ($dates as $date) {
            foreach ($resources as $rid => $row) {
                $rows[$rowIndex][ColumnNames::RESOURCE_ID] = $rid;
                $rows[$rowIndex][ColumnNames::RESOURCE_NAME_ALIAS] = $row[ColumnNames::RESOURCE_NAME_ALIAS];
                $rows[$rowIndex][ColumnNames::UTILIZATION] = $this->GetUtilization($date, $rid, $row[ColumnNames::SCHEDULE_ID]);
                $rows[$rowIndex][ColumnNames::DATE] = $date;
                $rows[$rowIndex][ColumnNames::SCHEDULE_ID] = $row[ColumnNames::SCHEDULE_ID];
                $rowIndex++;
            }
        }

        return $rows;
    }

    /**
     * @param int $scheduleId
     * @return IScheduleLayout
     */
    private function GetLayout($scheduleId)
    {
        if (!array_key_exists($scheduleId, $this->layouts)) {
            $this->layouts[$scheduleId] = $this->scheduleRepository->GetLayout($scheduleId, new ScheduleLayoutFactory($this->timezone));
        }

        return $this->layouts[$scheduleId];
    }

    /**
     * @param Date $date
     * @param IScheduleLayout $layout
     * @param int $scheduleId
     */
    private function CacheTotalAvailability($date, $layout, $scheduleId)
    {
        $key = $date->Weekday() . $scheduleId;
        if (array_key_exists($key, $this->weekdayAvailability)) {
            return;
        }
        $seconds = 0;
        $first = null;
        $last = null;
        $periods = $layout->GetLayout($date->GetDate());

        foreach ($periods as $period) {
            if ($period->IsReservable()) {
                if ($first == null) {
                    $first = $period;
                }
                $last = $period;
                $seconds += DateDiff::BetweenDates($period->BeginDate(), $period->EndDate())->TotalSeconds();
            }
        }

        if ($first == null) {
            $first = $periods[0];
        }
        if ($last == null) {
            $last = $periods[count($periods) - 1];
        }

        $this->weekdayAvailability[$key] = new ReportDailyAvailability($seconds, $first->Begin(), $last->End());
    }

    /**
     * @param Date $date
     * @param IScheduleLayout $layout
     * @param int $scheduleId
     */
    private function CacheCustomAvailability($date, $layout, $scheduleId)
    {
        $key = $date->Timestamp() . $scheduleId;

        if (array_key_exists($key, $this->customAvailability)) {
            return;
        }

        $total = 0;
        $periods = $layout->GetLayout($date);
        $endOfDay = new Time(0, 0, 0, $layout->Timezone());
        foreach ($periods as $period) {
            if ($period->IsReservable()) {
                $total += DateDiff::BetweenDates($period->BeginDate(), $period->EndDate())->TotalSeconds();
            }
        }

        $this->customAvailability[$key] = new ReportDailyAvailability($total, $endOfDay, $endOfDay);
    }

    /**
     * @param Date $date
     * @param int $scheduleId
     * @return ReportDailyAvailability
     */
    private function GetTotalAvailability($date, $scheduleId)
    {
        $layout = $this->GetLayout($scheduleId);
        if ($layout->UsesCustomLayout()) {
            $this->CacheCustomAvailability($date, $layout, $scheduleId);
            return $this->customAvailability[$date->Timestamp() . $scheduleId];
        }

        $this->CacheTotalAvailability($date, $layout, $scheduleId);

        $key = $date->Weekday() . $scheduleId;

        return $this->weekdayAvailability[$key];
    }

    /**
     * @param Date $start
     * @param Date $end
     * @param int $resourceId
     * @param int $scheduleId
     */
    private function AddReservation(Date $start, Date $end, $resourceId, $scheduleId)
    {
        $this->AddItem($start, $end, $resourceId, $scheduleId, true);
    }

    /**
     * @param Date $start
     * @param Date $end
     * @param int $resourceId
     * @param int $scheduleId
     */
    private function AddBlackout(Date $start, Date $end, $resourceId, $scheduleId)
    {
        $this->AddItem($start, $end, $resourceId, $scheduleId, false);
    }

    /**
     * @param Date $start
     * @param Date $end
     * @param int $resourceId
     * @param int $scheduleId
     * @param bool $add
     */
    private function AddItem(Date $start, Date $end, $resourceId, $scheduleId, $add)
    {
        if (!$start->DateEquals($end)) {
            $dates[] = $start;
            $currentDate = $start->AddDays(1);
            while (!$currentDate->GreaterThan($end)) {
                if (!$currentDate->DateEquals($start) && !$currentDate->DateEquals($end)) {
                    $dates[] = $currentDate->GetDate();
                }
                $currentDate = $currentDate->AddDays(1);
            }
            $dates[] = $end;

            /** @var Date $date */
            foreach ($dates as $date) {
                $a = $this->GetTotalAvailability($date, $scheduleId);

                if ($date->DateEquals($start)) {
                    $secondsReserved = DateDiff::BetweenDates($date, $date->SetTimeString($a->Latest(), true))->TotalSeconds();
                } elseif ($date->DateEquals($end)) {
                    $secondsReserved = DateDiff::BetweenDates($date->SetTimeString($a->Earliest()), $date)->TotalSeconds();
                } else {
                    $secondsReserved = $a->TotalSeconds();
                }

                if ($add) {
                    $this->AddTotal($date, $resourceId, $secondsReserved);
                } else {
                    $this->SubtractAvailable($date, $resourceId, $secondsReserved);
                }
            }
        } else {
            $secondsReserved = DateDiff::BetweenDates($start, $end)->TotalSeconds();
            if ($add) {
                $this->AddTotal($start, $resourceId, $secondsReserved);
            } else {
                $this->SubtractAvailable($start, $resourceId, $secondsReserved);
            }
        }
    }

    private function AddTotal(Date $date, $resourceId, $total)
    {
        //        echo sprintf("%s reserved on %s for %s\n", $total / 60 / 60, $date->Format('Y-m-d'), $resourceId);

        $key = $resourceId . $date->GetDate()->Timestamp();
        if (!array_key_exists($key, $this->totals)) {
            $this->totals[$key] = $total;
        } else {
            $this->totals[$key] += $total;
        }
    }

    private function SubtractAvailable(Date $date, $resourceId, $total)
    {
        $key = $resourceId . $date->GetDate()->Timestamp();
        $this->unavailable[$key] = $total;
    }

    private function GetUnavailable(Date $date, $resourceId)
    {
        $key = $resourceId . $date->GetDate()->Timestamp();
        if (array_key_exists($key, $this->unavailable)) {
            return $this->unavailable[$key];
        }

        return 0;
    }

    private function GetTotal(Date $date, $resourceId)
    {
        $key = $resourceId . $date->GetDate()->Timestamp();
        if (!array_key_exists($key, $this->totals)) {
            return 0;
        }
        return max(0, $this->totals[$key]);
    }

    /**
     * @param Date $date
     * @param int $resourceId
     * @param int $scheduleId
     * @return float
     */
    private function GetUtilization(Date $date, $resourceId, $scheduleId)
    {
        $total = $this->GetTotal($date, $resourceId);
        $available = $this->GetTotalAvailability($date, $scheduleId);
        $unavailable = $this->GetUnavailable($date, $resourceId);

        $availableSeconds = $available->TotalSeconds() - $unavailable;
        if ($availableSeconds == 0) {
            return 0;
        }
        return 100 * (round($total / $availableSeconds, 2));
    }
}

class ReportDailyAvailability
{
    /**
     * @var int
     */
    private $totalSeconds;
    /**
     * @var Time
     */
    private $earliest;
    /**
     * @var Time
     */
    private $latest;

    public function __construct($totalSeconds, Time $earliest, Time $latest)
    {
        $this->totalSeconds = $totalSeconds;
        $this->earliest = $earliest;
        $this->latest = $latest;
    }

    /**
     * @return int
     */
    public function TotalSeconds()
    {
        return $this->totalSeconds;
    }

    /**
     * @return Time
     */
    public function Earliest()
    {
        return $this->earliest;
    }

    /**
     * @return Time
     */
    public function Latest()
    {
        return $this->latest;
    }
}
