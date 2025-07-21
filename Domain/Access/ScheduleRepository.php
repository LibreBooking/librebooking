<?php

require_once(ROOT_DIR . 'Domain/ScheduleLayout.php');
require_once(ROOT_DIR . 'Domain/Schedule.php');
require_once(ROOT_DIR . 'Domain/SchedulePeriod.php');
require_once(ROOT_DIR . 'lib/Database/Commands/namespace.php');

interface IScheduleRepository
{
    /**
     * @return array|Schedule[]
     */
    public function GetAll();

    /**
     * @param int $scheduleId
     * @return Schedule
     */
    public function LoadById($scheduleId);

    /**
     * @param string $publicId
     * @return Schedule
     */
    public function LoadByPublicId($publicId);

    /**
     * @param Schedule $schedule
     */
    public function Update(Schedule $schedule);

    /**
     * @param Schedule $schedule
     */
    public function Delete(Schedule $schedule);

    /**
     * @param Schedule $schedule
     * @param int $copyLayoutFromScheduleId
     * @return int $insertedScheduleId
     */
    public function Add(Schedule $schedule, $copyLayoutFromScheduleId);

    /**
     * @param int $scheduleId
     * @param ILayoutFactory $layoutFactory factory to use to create the schedule layout
     * @return IScheduleLayout
     */
    public function GetLayout($scheduleId, ILayoutFactory $layoutFactory);

    /**
     * @param int $scheduleId
     * @param ILayoutCreation $layout
     */
    public function AddScheduleLayout($scheduleId, ILayoutCreation $layout);

    /**
     * @param Date $periodDate
     * @param int $scheduleId
     * @return SchedulePeriod[]
     */
    public function GetCustomLayoutPeriods(Date $periodDate, $scheduleId);

    /**
     * @param Date $start
     * @param Date $end
     * @param int $scheduleId
     * @return SchedulePeriod[]
     */
    public function GetCustomLayoutPeriodsInRange(Date $start, Date $end, $scheduleId);

    /**
     * @param int $pageNumber
     * @param int $pageSize
     * @param string|null $sortField
     * @param string|null $sortDirection
     * @param ISqlFilter $filter
     * @return PageableData|Schedule[]
     */
    public function GetList($pageNumber, $pageSize, $sortField = null, $sortDirection = null, $filter = null);

    /**
     * @param int $scheduleId
     * @param ScheduleLayout $layout
     */
    public function UpdatePeakTimes($scheduleId, ScheduleLayout $layout);

    /**
     * @param int $scheduleId
     * @param Date $start
     * @param Date $end
     */
    public function AddCustomLayoutPeriod($scheduleId, Date $start, Date $end);

    /**
     * @param int $scheduleId
     * @param Date $start
     */
    public function DeleteCustomLayoutPeriod($scheduleId, Date $start);

    /**
     * @return array all public schedule ids in key value id=>publicid
     */
    public function GetPublicScheduleIds();
}

interface ILayoutFactory
{
    /**
     * @return IScheduleLayout
     */
    public function CreateLayout();

    /**
     * @param IScheduleRepository $repository
     * @param int $scheduleId
     * @return IScheduleLayout
     */
    public function CreateCustomLayout(IScheduleRepository $repository, $scheduleId);
}

class ScheduleLayoutFactory implements ILayoutFactory
{
    private $_targetTimezone;

    /**
     * @param string $targetTimezone target timezone of layout
     */
    public function __construct($targetTimezone = null)
    {
        $this->_targetTimezone = $targetTimezone;
    }

    public function CreateLayout()
    {
        return new ScheduleLayout($this->_targetTimezone);
    }

    public function CreateCustomLayout(IScheduleRepository $repository, $scheduleId)
    {
        return new CustomScheduleLayout($this->_targetTimezone, $scheduleId, $repository);
    }
}

class ReservationLayoutFactory implements ILayoutFactory
{
    private $_targetTimezone;

    /**
     * @param string $targetTimezone target timezone of layout
     */
    public function __construct($targetTimezone)
    {
        $this->_targetTimezone = $targetTimezone;
    }

    public function CreateLayout()
    {
        return new ReservationLayout($this->_targetTimezone);
    }

    public function CreateCustomLayout(IScheduleRepository $repository, $scheduleId)
    {
        return new CustomScheduleLayout($this->_targetTimezone, $scheduleId, $repository);
    }
}

class ScheduleRepository implements IScheduleRepository
{
    /**
     * @var DomainCache
     */
    private $_cache;

    public function __construct()
    {
        $this->_cache = new DomainCache();
    }

    public function GetAll()
    {
        $schedules = [];

        $reader = ServiceLocator::GetDatabase()->Query(new GetAllSchedulesCommand());

        while ($row = $reader->GetRow()) {
            $schedules[] = Schedule::FromRow($row);
        }

        $reader->Free();

        return $schedules;
    }

    public function LoadById($scheduleId)
    {
        if (!$this->_cache->Exists($scheduleId)) {
            $schedule = null;

            $reader = ServiceLocator::GetDatabase()->Query(new GetScheduleByIdCommand($scheduleId));

            if ($row = $reader->GetRow()) {
                $schedule = Schedule::FromRow($row);
            }

            $reader->Free();

            $this->_cache->Add($scheduleId, $schedule);
            return $schedule;
        }

        return $this->_cache->Get($scheduleId);
    }

    public function LoadByPublicId($publicId)
    {
        $schedule = Schedule::Null();

        $reader = ServiceLocator::GetDatabase()->Query(new GetScheduleByPublicIdCommand($publicId));

        if ($row = $reader->GetRow()) {
            $schedule = Schedule::FromRow($row);
        }

        $reader->Free();

        return $schedule;
    }

    public function Update(Schedule $schedule)
    {
        ServiceLocator::GetDatabase()->Execute(new UpdateScheduleCommand(
            $schedule->GetId(),
            $schedule->GetName(),
            $schedule->GetIsDefault(),
            $schedule->GetWeekdayStart(),
            $schedule->GetDaysVisible(),
            $schedule->GetIsCalendarSubscriptionAllowed(),
            $schedule->GetPublicId(),
            $schedule->GetAdminGroupId(),
            $schedule->GetAvailabilityBegin(),
            $schedule->GetAvailabilityEnd(),
            $schedule->GetDefaultStyle(),
            $schedule->GetTotalConcurrentReservations(),
            $schedule->GetMaxResourcesPerReservation()
        ));

        if ($schedule->GetIsDefault()) {
            ServiceLocator::GetDatabase()->Execute(new SetDefaultScheduleCommand($schedule->GetId()));
        }
    }

    public function Add(Schedule $schedule, $copyLayoutFromScheduleId)
    {
        $source = $this->LoadById($copyLayoutFromScheduleId);

        $db = ServiceLocator::GetDatabase();

        $scheduleId = $db->ExecuteInsert(new AddScheduleCommand(
            $schedule->GetName(),
            $schedule->GetIsDefault(),
            $schedule->GetWeekdayStart(),
            $schedule->GetDaysVisible(),
            $source->GetLayoutId(),
            $schedule->GetAdminGroupId()
        ));

        if ($source->HasCustomLayout()) {
            $layout = $this->GetLayout($scheduleId, new ScheduleLayoutFactory($source->GetTimezone()));
            $this->AddScheduleLayout($scheduleId, $layout);
        }

        return $scheduleId;
    }

    public function Delete(Schedule $schedule)
    {
        ServiceLocator::GetDatabase()->Execute(new DeleteScheduleCommand($schedule->GetId()));
    }

    public function GetLayout($scheduleId, ILayoutFactory $layoutFactory)
    {
        $reader = ServiceLocator::GetDatabase()->Query(new GetLayoutCommand($scheduleId));

        /** @var ScheduleLayout $layout */
        $layout = null;

        while ($row = $reader->GetRow()) {
            if ($layout == null) {
                if ($row[ColumnNames::LAYOUT_TYPE] == 1) {
                    $layout = $layoutFactory->CreateCustomLayout($this, $scheduleId);
                } else {
                    $layout = $layoutFactory->CreateLayout();
                }
            }

            $timezone = $row[ColumnNames::BLOCK_TIMEZONE];
            $start = Time::Parse($row[ColumnNames::BLOCK_START], $timezone);
            $end = Time::Parse($row[ColumnNames::BLOCK_END], $timezone);
            $label = $row[ColumnNames::BLOCK_LABEL];
            $periodType = $row[ColumnNames::BLOCK_CODE];
            $dayOfWeek = $row[ColumnNames::BLOCK_DAY_OF_WEEK];

            if ($periodType == PeriodTypes::RESERVABLE) {
                $layout->AppendPeriod($start, $end, $label, $dayOfWeek);
            } else {
                $layout->AppendBlockedPeriod($start, $end, $label, $dayOfWeek);
            }
        }
        $reader->Free();

        $reader = ServiceLocator::GetDatabase()->Query(new GetPeakTimesCommand($scheduleId));
        if ($row = $reader->GetRow()) {
            $layout->ChangePeakTimes(PeakTimes::FromRow($row));
        }

        $reader->Free();

        return $layout;
    }

    public function GetCustomLayoutPeriods(Date $date, $scheduleId)
    {
        $command = new GetCustomLayoutCommand($date, $scheduleId);
        $reader = ServiceLocator::GetDatabase()->Query($command);

        $periods = [];

        while ($row = $reader->GetRow()) {
            $timezone = $row[ColumnNames::BLOCK_TIMEZONE];
            $start = Date::FromDatabase($row[ColumnNames::BLOCK_START])->ToTimezone($timezone);
            $end = Date::FromDatabase($row[ColumnNames::BLOCK_END])->ToTimezone($timezone);

            $periods[] = new SchedulePeriod($start, $end);
        }

        $reader->Free();

        return $periods;
    }

    public function GetCustomLayoutPeriodsInRange(Date $start, Date $end, $scheduleId)
    {
        $command = new GetCustomLayoutRangeCommand($start, $end, $scheduleId);
        $reader = ServiceLocator::GetDatabase()->Query($command);

        $periods = [];

        while ($row = $reader->GetRow()) {
            $timezone = $row[ColumnNames::BLOCK_TIMEZONE];
            $start = Date::FromDatabase($row[ColumnNames::BLOCK_START])->ToTimezone($timezone);
            $end = Date::FromDatabase($row[ColumnNames::BLOCK_END])->ToTimezone($timezone);

            $periods[] = new SchedulePeriod($start, $end);
        }

        $reader->Free();

        return $periods;
    }

    public function AddScheduleLayout($scheduleId, ILayoutCreation $layout)
    {
        $db = ServiceLocator::GetDatabase();
        $timezone = $layout->Timezone();

        $layoutType = $layout->GetType();
        $addLayoutCommand = new AddLayoutCommand($timezone, $layoutType);
        $layoutId = $db->ExecuteInsert($addLayoutCommand);

        if ($layoutType == ScheduleLayout::Standard) {
            $days = [null];
            if ($layout->UsesDailyLayouts()) {
                $days = DayOfWeek::Days();
            }

            foreach ($days as $day) {
                $slots = $layout->GetSlots($day);

                /* @var LayoutPeriod $slot */
                foreach ($slots as $slot) {
                    $db->Execute(new AddLayoutTimeCommand($layoutId, $slot->Start, $slot->End, $slot->PeriodType, $slot->Label, $day));
                }
            }
        }

        $db->Execute(new UpdateScheduleLayoutCommand($scheduleId, $layoutId));

        $db->Execute(new DeleteOrphanLayoutsCommand());
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
        $command = new GetAllSchedulesCommand();

        if ($filter != null) {
            $command = new FilterCommand($command, $filter);
        }

        $builder = ['Schedule', 'FromRow'];
        return PageableDataStore::GetList($command, $builder, $pageNumber, $pageSize);
    }

    public function UpdatePeakTimes($scheduleId, ScheduleLayout $layout)
    {
        ServiceLocator::GetDatabase()->Execute(new DeletePeakTimesCommand($scheduleId));

        if ($layout->HasPeakTimesDefined()) {
            $peakTimes = $layout->GetPeakTimes();
            ServiceLocator::GetDatabase()->Execute(new AddPeakTimesCommand(
                $scheduleId,
                $peakTimes->IsAllDay(),
                $peakTimes->GetBeginTime(),
                $peakTimes->GetEndTime(),
                $peakTimes->IsEveryDay(),
                implode(',', $peakTimes->GetWeekdays()),
                $peakTimes->IsAllYear(),
                $peakTimes->GetBeginDay(),
                $peakTimes->GetBeginMonth(),
                $peakTimes->GetEndDay(),
                $peakTimes->GetEndMonth()
            ));
        }
    }

    public function AddCustomLayoutPeriod($scheduleId, Date $start, Date $end)
    {
        ServiceLocator::GetDatabase()->Execute(new AddCustomLayoutPeriodCommand($scheduleId, $start, $end));
    }

    public function DeleteCustomLayoutPeriod($scheduleId, Date $start)
    {
        ServiceLocator::GetDatabase()->Execute(new DeleteCustomLayoutPeriodCommand($scheduleId, $start));
    }

    public function GetPublicScheduleIds()
    {
        $ids = [];
        $command = new GetSchedulesPublicCommand();
        $reader = ServiceLocator::GetDatabase()->Query($command);
        while ($row = $reader->GetRow()) {
            $ids[$row[ColumnNames::SCHEDULE_ID]] = $row[ColumnNames::PUBLIC_ID];
        }

        $reader->Free();

        return $ids;
    }
}
