<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class FakeScheduleRepository implements IScheduleRepository
{
    public $_GetAllCalled = false;

    /**
     * @var array|Schedule[]
     */
    public $_AllRows = [];

    public $_DefaultScheduleId = 1;
    public $_DefaultDaysVisible = 7;
    public $_DefaultStartTime = '06:00';
    public $_DefaultEndTime = '17:00';
    public $_DefaultDayStart = 0;

    /**
     * @var FakeSchedule
     */
    public $_Schedule;

    /**
     * @var ScheduleLayout|null
     */
    public $_Layout;

    /**
     * @var Schedule[]
     */
    public $_Schedules = [];

    public $_CustomLayouts = [];
    public $_PublicScheduleIds = [];

    public function __construct()
    {
        $this->_AllRows = $this->_GetAllRows();
        $this->_Schedule = new FakeSchedule();
    }

    /**
     * @var Schedule
     */
    public static $Schedule1;

    public static function Initialize()
    {
        self::$Schedule1 = new Schedule(1, "schedule 1", true, '09:00', '20:00', 0, 1);
    }

    public function GetRows()
    {
        return [
                self::GetRow(
                    $this->_DefaultScheduleId,
                    'schedule 1',
                    1,
                    $this->_DefaultDayStart,
                    $this->_DefaultDaysVisible,
                    'America/Chicago',
                    null,
                    false,
                    null,
                    null,
                    '2018-01-01',
                    '2019-01-1'
                ),
                self::GetRow(2, 'schedule 2', 0, 0, 5, 'America/Chicago'),
        ];
    }

    private function _GetAllRows()
    {
        $rows = $this->GetRows();
        $expected = [];

        foreach ($rows as $item) {
            $schedule = new Schedule(
                $item[ColumnNames::SCHEDULE_ID],
                $item[ColumnNames::SCHEDULE_NAME],
                $item[ColumnNames::SCHEDULE_DEFAULT],
                $item[ColumnNames::SCHEDULE_WEEKDAY_START],
                $item[ColumnNames::SCHEDULE_DAYS_VISIBLE],
                $item[ColumnNames::TIMEZONE_NAME]
            );
            $schedule->SetAdminGroupId($item[ColumnNames::SCHEDULE_ADMIN_GROUP_ID]);
            $schedule->SetAvailability(
                Date::FromDatabase($item[ColumnNames::SCHEDULE_AVAILABLE_START_DATE]),
                Date::FromDatabase($item[ColumnNames::SCHEDULE_AVAILABLE_END_DATE])
            );
            $expected[] = $schedule;
        }

        return $expected;
    }

    public function GetAll()
    {
        if (!empty($this->_Schedules)) {
            return $this->_Schedules;
        }
        return [new Schedule($this->_DefaultScheduleId, 'defaultsched', true, $this->_DefaultDayStart, $this->_DefaultDaysVisible)];
    }

    public function AddScheduleLayout($scheduleId, ILayoutCreation $layout)
    {
        $schedule1 = new Schedule(100, 'sched1', false, DayOfWeek::MONDAY, 7);
        $defaultSchedule = new Schedule($this->_DefaultScheduleId, 'defaultsched', true, Schedule::Today, 7);
        return [$schedule1, $defaultSchedule];
    }

    public static function GetRow(
        $id = 1,
        $name = 'name',
        $isDefault = false,
        $weekdayStart = 0,
        $daysVisible = 7,
        $timezone = 'America/Chicago',
        $layoutId = null,
        $allowCalendarSubscription = false,
        $publicId = null,
        $adminId = null,
        $availableStart = null,
        $availableEnd = null,
        $totalConcurrentReservations = 0,
        $maxResourcesPerReservation = 0
    ) {
        return [
                ColumnNames::SCHEDULE_ID => $id,
                ColumnNames::SCHEDULE_NAME => $name,
                ColumnNames::SCHEDULE_DEFAULT => $isDefault,
                ColumnNames::SCHEDULE_WEEKDAY_START => $weekdayStart,
                ColumnNames::SCHEDULE_DAYS_VISIBLE => $daysVisible,
                ColumnNames::TIMEZONE_NAME => $timezone,
                ColumnNames::LAYOUT_ID => $layoutId,
                ColumnNames::ALLOW_CALENDAR_SUBSCRIPTION => $allowCalendarSubscription,
                ColumnNames::PUBLIC_ID => $publicId,
                ColumnNames::SCHEDULE_ADMIN_GROUP_ID => $adminId,
                ColumnNames::SCHEDULE_AVAILABLE_START_DATE => $availableStart,
                ColumnNames::SCHEDULE_AVAILABLE_END_DATE => $availableEnd,
                ColumnNames::SCHEDULE_DEFAULT_STYLE => ScheduleStyle::Standard,
                ColumnNames::LAYOUT_TYPE => ScheduleLayout::Standard,
                ColumnNames::TOTAL_CONCURRENT_RESERVATIONS => $totalConcurrentReservations,
                ColumnNames::MAX_RESOURCES_PER_RESERVATION => $maxResourcesPerReservation,
        ];
    }

    /**
     * @param int $scheduleId
     * @return Schedule
     */
    public function LoadById($scheduleId)
    {
        return $this->_Schedule;
    }

    /**
     * @param string $publicId
     * @return Schedule
     */
    public function LoadByPublicId($publicId)
    {
        return $this->_Schedule;
    }

    /**
     * @param Schedule $schedule
     */
    public function Update(Schedule $schedule)
    {
        // TODO: Implement Update() method.
    }

    /**
     * @param Schedule $schedule
     */
    public function Delete(Schedule $schedule)
    {
        // TODO: Implement Delete() method.
    }

    /**
     * @param Schedule $schedule
     * @param int $copyLayoutFromScheduleId
     * @return int $insertedScheduleId
     */
    public function Add(Schedule $schedule, $copyLayoutFromScheduleId)
    {
        // TODO: Implement Add() method.
        return null;
    }

    /**
     * @param int $scheduleId
     * @param ILayoutFactory $layoutFactory factory to use to create the schedule layout
     * @return IScheduleLayout
     */
    public function GetLayout($scheduleId, ILayoutFactory $layoutFactory)
    {
        return $this->_Layout;
    }

    /**
     * @param int $pageNumber
     * @param int $pageSize
     * @param string|null $sortField
     * @param string|null $sortDirection
     * @param ISqlFilter $filter
     * @return PageableData|Schedule[]
     */
    public function GetList($pageNumber, $pageSize, $sortField = null, $sortDirection = null, $filter = null)
    {
        // TODO: Implement GetList() method.
        return null;
    }

    /**
     * @param int $scheduleId
     * @param ScheduleLayout $layout
     */
    public function UpdatePeakTimes($scheduleId, ScheduleLayout $layout)
    {
        // TODO: Implement UpdatePeakTimes() method.
    }

    public function GetCustomLayoutPeriods(Date $date, $scheduleId)
    {
        return $this->_CustomLayouts[$date->Timestamp()];
    }

    /**
     * @param Date $date
     * @param SchedulePeriod[] $periods
     */
    public function _AddCustomLayout($date, $periods)
    {
        $this->_CustomLayouts[$date->Timestamp()] = $periods;
    }

    /**
     * @param Date $start
     * @param Date $end
     * @param int $scheduleId
     * @return SchedulePeriod[]
     */
    public function GetCustomLayoutPeriodsInRange(Date $start, Date $end, $scheduleId)
    {
        // TODO: Implement GetCustomLayoutPeriodsInRange() method.
        return null;
    }

    /**
     * @param int $scheduleId
     * @param Date $start
     * @param Date $end
     */
    public function AddCustomLayoutPeriod($scheduleId, Date $start, Date $end)
    {
        // TODO: Implement AddCustomLayoutPeriod() method.
    }

    /**
     * @param int $scheduleId
     * @param Date $start
     */
    public function DeleteCustomLayoutPeriod($scheduleId, Date $start)
    {
        // TODO: Implement DeleteCustomLayoutPeriod() method.
    }

    public function GetPublicScheduleIds()
    {
        return $this->_PublicScheduleIds;
    }
}
