<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class FakeScheduleLayout implements IScheduleLayout
{
    /**
     * @var SlotCount
     */
    public $_SlotCount;

    /**
     * @var SchedulePeriod[]
     */
    public $_Layout = [];

    /**
     * @var string
     */
    public $_Timezone;

    public $_UsesDailyLayouts = false;

    /**
     * @var SchedulePeriod[]
     */
    public $_DailyLayout = [];

    public function __construct()
    {
        $this->_SlotCount = new SlotCount(1, 2);
        $this->_Timezone = 'America/Chicago';
    }

    public function Timezone()
    {
        return $this->_Timezone;
    }

    /**
     * @return bool
     */
    public function UsesDailyLayouts()
    {
        return $this->_UsesDailyLayouts;
    }

    /**
     * @param Date $layoutDate
     * @param bool $hideBlockedPeriods
     * @return SchedulePeriod[]|array of SchedulePeriod objects
     */
    public function GetLayout(Date $layoutDate, $hideBlockedPeriods = false)
    {
        if (!empty($this->_DailyLayout)) {
            return $this->_DailyLayout[$layoutDate->Timestamp()] ?? [];
        }

        return $this->_Layout;
    }

    /**
     * @param Date $date
     * @param SchedulePeriod[] $layout
     */
    public function _AddDailyLayout(Date $date, $layout)
    {
        $this->_DailyLayout[$date->Timestamp()] = $layout;
    }

    /**
     * @param Date $date
     * @return SchedulePeriod|null period which occurs at this datetime. Includes start time, excludes end time. null if no match is found
     */
    public function GetPeriod(Date $date): ?SchedulePeriod
    {
        // TODO: Implement GetPeriod() method.
        return null;
    }

    /**
     * @param Date $startDate
     * @param Date $endDate
     * @return SlotCount
     * @internal param $scheduleId
     */
    public function GetSlotCount(Date $startDate, Date $endDate)
    {
        return $this->_SlotCount;
    }

    public function UpdatePeakTimes(PeakTimes $peakTimes)
    {
        // TODO: Implement UpdatePeakTimes() method.
    }


    public function AddPeakTimes(PeakTimes $peakTimes)
    {
        // TODO: Implement AddPeakTimes() method.
    }


    public function ChangePeakTimes(PeakTimes $peakTimes)
    {
        // TODO: Implement ChangePeakTimes() method.
    }

    public function RemovePeakTimes()
    {
        // TODO: Implement RemovePeakTimes() method.
    }


    public function DeletePeakTimes(int $peakTimeId)
    {
        // TODO: Implement DeletePeakTimes() method.
    }

    public function DeleteAllPeakTimes()
    {
        // TODO: Implement DeleteAllPeakTimes() method.
    }

    public function GetPeakTimes()
    {
        // TODO: Implement GetPeakTimes() method.
        return [];
    }

    /**
     * @return bool
     */
    public function FitsToHours()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function UsesCustomLayout()
    {
        return false;
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
        throw new \LogicException('AppendPeriod not implemented in FakeScheduleLayout');
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
        throw new \LogicException('AppendBlockedPeriod not implemented in FakeScheduleLayout');
    }

    /**
     *
     * @param DayOfWeek|int|null $dayOfWeek
     * @return LayoutPeriod[] array of LayoutPeriod
     */
    public function GetSlots($dayOfWeek = null)
    {
        throw new \LogicException('GetSlots not implemented in FakeScheduleLayout');
    }

    /**
     * @return int
     */
    public function GetType()
    {
        throw new \LogicException('GetType not implemented in FakeScheduleLayout');
    }
}
