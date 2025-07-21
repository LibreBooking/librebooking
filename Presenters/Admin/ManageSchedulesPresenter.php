<?php

require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'config/timezones.php');
require_once(ROOT_DIR . 'lib/Common/Validators/namespace.php');

class ManageSchedules
{
    public const ActionAdd = 'add';
    public const ActionChangeLayout = 'changeLayout';
    public const ActionChangeStartDay = 'startDay';
    public const ActionChangeDaysVisible = 'daysVisible';
    public const ActionMakeDefault = 'makeDefault';
    public const ActionRename = 'rename';
    public const ActionDelete = 'delete';
    public const ActionEnableSubscription = 'enableSubscription';
    public const ActionDisableSubscription = 'disableSubscription';
    public const ChangeAdminGroup = 'changeAdminGroup';
    public const ActionChangePeakTimes = 'ActionChangePeakTimes';
    public const ActionChangeAvailability = 'ActionChangeAvailability';
    public const ActionSwitchLayoutType = 'ActionSwitchLayoutType';
    public const ActionAddLayoutSlot = 'addLayoutSlot';
    public const ActionUpdateLayoutSlot = 'updateLayoutSlot';
    public const ActionDeleteLayoutSlot = 'deleteLayoutSlot';
    public const ActionChangeDefaultStyle = 'changeDefaultStyle';
    public const ActionChangeMaximumConcurrent = 'changeMaximumConcurrent';
    public const ActionChangeResourcesPerReservation = 'changeResourcesPerReservation';
}

class ManageScheduleService
{
    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @var IResourceRepository
     */
    private $resourceRepository;

    /**
     * @var array|Schedule[]
     */
    private $_all;

    public function __construct(IScheduleRepository $scheduleRepository, IResourceRepository $resourceRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
        $this->resourceRepository = $resourceRepository;
    }

    /**
     * @return array|Schedule[]
     */
    public function GetAll()
    {
        if (is_null($this->_all)) {
            $this->_all = $this->scheduleRepository->GetAll();
        }
        return $this->_all;
    }

    /**
     * @return array|Schedule[]
     */
    public function GetSourceSchedules()
    {
        return $this->GetAll();
    }

    /**
     * @param Schedule $schedule
     * @return IScheduleLayout
     */
    public function GetLayout($schedule)
    {
        return $this->scheduleRepository->GetLayout($schedule->GetId(), new ScheduleLayoutFactory($schedule->GetTimezone()));
    }

    /**
     * @param string $name
     * @param int $daysVisible
     * @param int $startDay
     * @param int $copyLayoutFromScheduleId
     */
    public function Add($name, $daysVisible, $startDay, $copyLayoutFromScheduleId)
    {
        $schedule = new Schedule(null, $name, false, $startDay, $daysVisible);
        $this->scheduleRepository->Add($schedule, $copyLayoutFromScheduleId);
    }

    /**
     * @param int $scheduleId
     * @param string $name
     */
    public function Rename($scheduleId, $name)
    {
        $schedule = $this->scheduleRepository->LoadById($scheduleId);
        $schedule->SetName($name);
        $this->scheduleRepository->Update($schedule);
    }

    /**
     * @param int $scheduleId
     * @param int|null $startDay
     * @param int|null $daysVisible
     */
    public function ChangeSettings($scheduleId, $startDay, $daysVisible)
    {
        Log::Debug('Changing scheduleId %s, WeekdayStart: %s, DaysVisible %s', $scheduleId, $startDay, $daysVisible);
        $schedule = $this->scheduleRepository->LoadById($scheduleId);
        if (!is_null($startDay)) {
            $schedule->SetWeekdayStart($startDay);
        }

        if (!is_null($daysVisible)) {
            $schedule->SetDaysVisible($daysVisible);
        }

        $this->scheduleRepository->Update($schedule);
    }

    /**
     * @param int $scheduleId
     * @param string $timezone
     * @param string $reservableSlots
     * @param string $blockedSlots
     */
    public function ChangeLayout($scheduleId, $timezone, $reservableSlots, $blockedSlots)
    {
        $layout = ScheduleLayout::Parse($timezone, $reservableSlots, $blockedSlots);
        $this->scheduleRepository->AddScheduleLayout($scheduleId, $layout);
    }

    /**
     * @param int $scheduleId
     * @param string $timezone
     * @param string[] $reservableSlots
     * @param string[] $blockedSlots
     */
    public function ChangeDailyLayout($scheduleId, $timezone, $reservableSlots, $blockedSlots)
    {
        $layout = ScheduleLayout::ParseDaily($timezone, $reservableSlots, $blockedSlots);
        $this->scheduleRepository->AddScheduleLayout($scheduleId, $layout);
    }

    /**
     * @param int $scheduleId
     */
    public function MakeDefault($scheduleId)
    {
        $schedule = $this->scheduleRepository->LoadById($scheduleId);
        $schedule->SetIsDefault(true);

        $this->scheduleRepository->Update($schedule);
    }

    /**
     * @param int $scheduleId
     * @param int $moveResourcesToThisScheduleId
     */
    public function Delete($scheduleId, $moveResourcesToThisScheduleId)
    {
        $resources = $this->resourceRepository->GetScheduleResources($scheduleId);
        foreach ($resources as $resource) {
            $resource->SetScheduleId($moveResourcesToThisScheduleId);
            $this->resourceRepository->Update($resource);
        }

        $schedule = $this->scheduleRepository->LoadById($scheduleId);
        $this->scheduleRepository->Delete($schedule);
    }

    /**
     * @param int $scheduleId
     */
    public function EnableSubscription($scheduleId)
    {
        $schedule = $this->scheduleRepository->LoadById($scheduleId);
        $schedule->EnableSubscription();
        Configuration::Instance()->EnableSubscription();
        $this->scheduleRepository->Update($schedule);
    }

    /**
     * @param int $scheduleId
     */
    public function DisableSubscription($scheduleId)
    {
        $schedule = $this->scheduleRepository->LoadById($scheduleId);
        $schedule->DisableSubscription();
        $this->scheduleRepository->Update($schedule);
    }

    /**
     * @param int $scheduleId
     * @param int $adminGroupId
     */
    public function ChangeAdminGroup($scheduleId, $adminGroupId)
    {
        $schedule = $this->scheduleRepository->LoadById($scheduleId);
        $schedule->SetAdminGroupId($adminGroupId);
        $this->scheduleRepository->Update($schedule);
    }

    /**
     * @param int $pageNumber
     * @param int $pageSize
     * @return PageableData|BookableResource[]
     */
    public function GetList($pageNumber, $pageSize)
    {
        return $this->scheduleRepository->GetList($pageNumber, $pageSize);
    }

    /**
     * @param int $scheduleId
     * @param PeakTimes $peakTimes
     * @return IScheduleLayout
     */
    public function ChangePeakTimes($scheduleId, PeakTimes $peakTimes)
    {
        $layout = $this->scheduleRepository->GetLayout($scheduleId, new ScheduleLayoutFactory(null));
        $layout->ChangePeakTimes($peakTimes);
        $this->scheduleRepository->UpdatePeakTimes($scheduleId, $layout);

        return $layout;
    }

    public function DeletePeakTimes($scheduleId)
    {
        $layout = $this->scheduleRepository->GetLayout($scheduleId, new ScheduleLayoutFactory(null));
        $layout->RemovePeakTimes();
        $this->scheduleRepository->UpdatePeakTimes($scheduleId, $layout);

        return $layout;
    }

    /**
     * @return BookableResource[] resources indexed by scheduleId
     */
    public function GetResources()
    {
        $resources = [];

        $all = $this->resourceRepository->GetResourceList();
        /** @var BookableResource $resource */
        foreach ($all as $resource) {
            $resources[$resource->GetScheduleId()][] = $resource;
        }

        return $resources;
    }

    /**
     * @param int $scheduleId
     * @return Schedule
     */
    public function DeleteAvailability($scheduleId)
    {
        $schedule = $this->scheduleRepository->LoadById($scheduleId);
        $schedule->SetAvailableAllYear();
        $this->scheduleRepository->Update($schedule);

        return $schedule;
    }

    /**
     * @param int $scheduleId
     * @param Date $start
     * @param Date $end
     * @return Schedule
     */
    public function UpdateAvailability($scheduleId, $start, $end)
    {
        Log::Debug('Updating schedule availability. schedule %s, start %s, end %s', $scheduleId, $start, $end);
        $schedule = $this->scheduleRepository->LoadById($scheduleId);
        $schedule->SetAvailability($start, $end);
        $this->scheduleRepository->Update($schedule);

        return $schedule;
    }

    /**
     * @param int $scheduleId
     * @param int $layoutType
     */
    public function SwitchLayoutType($scheduleId, $layoutType)
    {
        Log::Debug("Switching layout type. ScheduleId %s", $scheduleId);

        $schedule = $this->scheduleRepository->LoadById($scheduleId);
        $targetTimezone = $schedule->GetTimezone();
        if ($layoutType == ScheduleLayout::Standard) {
            $layout = new ScheduleLayout($targetTimezone);
            $layout->AppendPeriod(Time::Parse('00:00', $targetTimezone), Time::Parse('00:00', $targetTimezone));
            $this->scheduleRepository->AddScheduleLayout($scheduleId, $layout);
        } else {
            $this->scheduleRepository->AddScheduleLayout($scheduleId, new CustomScheduleLayout($targetTimezone, $scheduleId, $this->scheduleRepository));
        }
    }

    public function GetCustomLayoutPeriods($start, $end, $scheduleId)
    {
        return $this->scheduleRepository->GetCustomLayoutPeriodsInRange($start, $end, $scheduleId);
    }

    /**
     * @param int $scheduleId
     * @param Date $start
     * @param Date $end
     */
    public function AddCustomLayoutPeriod($scheduleId, $start, $end)
    {
        Log::Debug("Adding custom layout period. ScheduleId %s", $scheduleId);

        $overlappingPeriod = $this->CustomLayoutPeriodOverlaps($scheduleId, $start, $end);
        if ($overlappingPeriod == null) {
            $this->scheduleRepository->AddCustomLayoutPeriod($scheduleId, $start, $end);
        }
    }

    /**
     * @param int $scheduleId
     * @param Date $start
     * @param Date $end
     * @param Date $originalStart
     */
    public function UpdateCustomLayoutPeriod($scheduleId, $start, $end, $originalStart)
    {
        Log::Debug("Updating custom layout period. ScheduleId %s", $scheduleId);
//        $overlappingPeriod = $this->CustomLayoutPeriodOverlaps($scheduleId, $start, $end);
//        if ($overlappingPeriod != null) {
//
//            if ($overlappingPeriod->BeginDate()->Equals($originalStart))
//            {
        $this->scheduleRepository->DeleteCustomLayoutPeriod($scheduleId, $originalStart);
        $this->scheduleRepository->AddCustomLayoutPeriod($scheduleId, $start, $end);
//            }
//        }
//        else {
//            $this->scheduleRepository->AddCustomLayoutPeriod($scheduleId, $start, $end);
//        }
    }

    /**
     * @param int $scheduleId
     * @param Date $start
     * @param Date $end
     */
    public function DeleteCustomLayoutPeriod($scheduleId, $start, $end)
    {
        $this->scheduleRepository->DeleteCustomLayoutPeriod($scheduleId, $start);
    }

    /**
     * @param int $scheduleId
     * @param Date $start
     * @param Date $end
     * @return SchedulePeriod|null
     */
    private function CustomLayoutPeriodOverlaps($scheduleId, Date $start, Date $end)
    {
        $overlaps = null;
        $periods = $this->scheduleRepository->GetCustomLayoutPeriodsInRange($start, $end, $scheduleId);
        foreach ($periods as $period) {
            if ($start->LessThanOrEqual($period->BeginDate()) && $end->GreaterThan($period->BeginDate())) {
                $overlaps = $period;
            }
        }
        return $overlaps;
    }

    public function ChangeDefaultStyle($scheduleId, $defaultStyle)
    {
        $schedule = $this->scheduleRepository->LoadById($scheduleId);
        $schedule->SetDefaultStyle($defaultStyle);
        $this->scheduleRepository->Update($schedule);
    }

    public function ChangeConcurrentReservationMaximum($scheduleId, $max, $unlimited)
    {
        $schedule = $this->scheduleRepository->LoadById($scheduleId);
        $schedule->SetTotalConcurrentReservations($unlimited ? 0 : intval($max));
        $this->scheduleRepository->Update($schedule);
    }

    public function ChangeResourcesPerReservation($scheduleId, $max, $unlimited)
    {
        $schedule = $this->scheduleRepository->LoadById($scheduleId);
        $schedule->SetMaxResourcesPerReservation($unlimited ? 0 : intval($max));
        $this->scheduleRepository->Update($schedule);
    }
}

class ManageSchedulesPresenter extends ActionPresenter
{
    /**
     * @var IManageSchedulesPage
     */
    private $page;

    /**
     * @var ManageScheduleService
     */
    private $manageSchedulesService;

    /**
     * @var IGroupViewRepository
     */
    private $groupViewRepository;

    public function __construct(
        IManageSchedulesPage $page,
        ManageScheduleService $manageSchedulesService,
        IGroupViewRepository $groupViewRepository
    ) {
        parent::__construct($page);
        $this->page = $page;
        $this->manageSchedulesService = $manageSchedulesService;
        $this->groupViewRepository = $groupViewRepository;

        $this->AddAction(ManageSchedules::ActionAdd, 'Add');
        $this->AddAction(ManageSchedules::ActionChangeLayout, 'ChangeLayout');
        $this->AddAction(ManageSchedules::ActionChangeStartDay, 'ChangeStartDay');
        $this->AddAction(ManageSchedules::ActionChangeDaysVisible, 'ChangeDaysVisible');
        $this->AddAction(ManageSchedules::ActionMakeDefault, 'MakeDefault');
        $this->AddAction(ManageSchedules::ActionRename, 'Rename');
        $this->AddAction(ManageSchedules::ActionDelete, 'Delete');
        $this->AddAction(ManageSchedules::ActionEnableSubscription, 'EnableSubscription');
        $this->AddAction(ManageSchedules::ActionDisableSubscription, 'DisableSubscription');
        $this->AddAction(ManageSchedules::ChangeAdminGroup, 'ChangeAdminGroup');
        $this->AddAction(ManageSchedules::ActionChangePeakTimes, 'ChangePeakTimes');
        $this->AddAction(ManageSchedules::ActionChangeAvailability, 'ChangeAvailability');
        $this->AddAction(ManageSchedules::ActionSwitchLayoutType, 'SwitchLayoutType');
        $this->AddAction(ManageSchedules::ActionAddLayoutSlot, 'AddLayoutSlot');
        $this->AddAction(ManageSchedules::ActionUpdateLayoutSlot, 'UpdateLayoutSlot');
        $this->AddAction(ManageSchedules::ActionDeleteLayoutSlot, 'DeleteLayoutSlot');
        $this->AddAction(ManageSchedules::ActionChangeDefaultStyle, 'ChangeDefaultStyle');
        $this->AddAction(ManageSchedules::ActionChangeMaximumConcurrent, 'ChangeMaximumConcurrentReservations');
        $this->AddAction(ManageSchedules::ActionChangeResourcesPerReservation, 'ChangeResourcesPerReservation');
    }

    public function PageLoad()
    {
        $results = $this->manageSchedulesService->GetList($this->page->GetPageNumber(), null);
        $schedules = $results->Results();

        $sourceSchedules = $this->manageSchedulesService->GetSourceSchedules();
        $resources = $this->manageSchedulesService->GetResources();

        $layouts = [];
        /* @var Schedule $schedule */
        foreach ($schedules as $schedule) {
            $layout = $this->manageSchedulesService->GetLayout($schedule);
            $layouts[$schedule->GetId()] = $layout;
        }

        $this->page->BindGroups($this->groupViewRepository->GetGroupsByRole(RoleLevel::SCHEDULE_ADMIN));

        $this->page->BindSchedules($schedules, $layouts, $sourceSchedules);
        $this->page->BindPageInfo($results->PageInfo());
        $this->page->BindResources($resources);
        $this->PopulateTimezones();
    }

    private function PopulateTimezones()
    {
        $timezoneValues = [];
        $timezoneOutput = [];

        foreach ($GLOBALS['APP_TIMEZONES'] as $timezone) {
            $timezoneValues[] = $timezone;
            $timezoneOutput[] = $timezone;
        }

        $this->page->SetTimezones($timezoneValues, $timezoneOutput);
    }

    /**
     * @internal should only be used for testing
     */
    public function Add()
    {
        $copyLayoutFromScheduleId = $this->page->GetSourceScheduleId();
        $name = $this->page->GetScheduleName();
        $weekdayStart = $this->page->GetStartDay();
        $daysVisible = $this->page->GetDaysVisible();

        Log::Debug('Adding schedule with name %s', $name);

        $this->manageSchedulesService->Add($name, $daysVisible, $weekdayStart, $copyLayoutFromScheduleId);
    }

    /**
     * @internal should only be used for testing
     */
    public function Rename()
    {
        $this->manageSchedulesService->Rename($this->page->GetScheduleId(), $this->page->GetValue());
    }

    /**
     * @internal should only be used for testing
     */
    public function ChangeStartDay()
    {
        $this->manageSchedulesService->ChangeSettings($this->page->GetScheduleId(), $this->page->GetValue(), null);
    }

    /**
     * @internal should only be used for testing
     */
    public function ChangeDaysVisible()
    {
        $this->manageSchedulesService->ChangeSettings($this->page->GetScheduleId(), null, $this->page->GetValue());
    }

    /**
     * @internal should only be used for testing
     */
    public function ChangeLayout()
    {
        $scheduleId = $this->page->GetScheduleId();
        $timezone = $this->page->GetLayoutTimezone();
        $usingSingleLayout = $this->page->GetUsingSingleLayout();

        Log::Debug(
            'Changing layout for scheduleId=%s. timezone=%s, usingSingleLayout=%s',
            $scheduleId,
            $timezone,
            $usingSingleLayout
        );
        if ($usingSingleLayout) {
            $reservableSlots = $this->page->GetReservableSlots();
            $blockedSlots = $this->page->GetBlockedSlots();
            $this->manageSchedulesService->ChangeLayout($scheduleId, $timezone, $reservableSlots, $blockedSlots);
        } else {
            $reservableSlots = $this->page->GetDailyReservableSlots();
            $blockedSlots = $this->page->GetDailyBlockedSlots();
            $this->manageSchedulesService->ChangeDailyLayout($scheduleId, $timezone, $reservableSlots, $blockedSlots);
        }
    }

    /**
     * @internal should only be used for testing
     */
    public function ChangeAdminGroup()
    {
        $this->manageSchedulesService->ChangeAdminGroup($this->page->GetScheduleId(), $this->page->GetValue());
    }

    /**
     * @internal should only be used for testing
     */
    public function MakeDefault()
    {
        $this->manageSchedulesService->MakeDefault($this->page->GetScheduleId());
    }

    /**
     * @internal should only be used for testing
     */
    public function Delete()
    {
        $this->manageSchedulesService->Delete($this->page->GetScheduleId(), $this->page->GetTargetScheduleId());
    }

    public function EnableSubscription()
    {
        $this->manageSchedulesService->EnableSubscription($this->page->GetScheduleId());
    }

    public function DisableSubscription()
    {
        $this->manageSchedulesService->DisableSubscription($this->page->GetScheduleId());
    }

    public function ChangePeakTimes()
    {
        $scheduleId = $this->page->GetScheduleId();
        $deletePeak = $this->page->GetDeletePeakTimes();

        if ($deletePeak) {
            $layout = $this->manageSchedulesService->DeletePeakTimes($scheduleId);
        } else {
            $allDay = $this->page->GetPeakAllDay();
            $beginTime = $this->page->GetPeakBeginTime();
            $endTime = $this->page->GetPeakEndTime();

            $everyDay = $this->page->GetPeakEveryDay();
            $peakDays = $this->page->GetPeakWeekdays();

            $allYear = $this->page->GetPeakAllYear();
            $beginDay = $this->page->GetPeakBeginDay();
            $beginMonth = $this->page->GetPeakBeginMonth();
            $endDay = $this->page->GetPeakEndDay();
            $endMonth = $this->page->GetPeakEndDMonth();

            $peakTimes = new PeakTimes($allDay, $beginTime, $endTime, $everyDay, $peakDays, $allYear, $beginDay, $beginMonth, $endDay, $endMonth);
            $layout = $this->manageSchedulesService->ChangePeakTimes($scheduleId, $peakTimes);
        }
        $this->page->DisplayPeakTimes($layout);
    }

    public function ChangeAvailability()
    {
        $availableAllYear = $this->page->GetAvailableAllYear();
        $start = $this->page->GetAvailabilityBegin();
        $end = $this->page->GetAvailabilityEnd();
        $scheduleId = $this->page->GetScheduleId();
        $timezone = ServiceLocator::GetServer()->GetUserSession()->Timezone;

        if ($availableAllYear || empty($start) || empty($end)) {
            $schedule = $this->manageSchedulesService->DeleteAvailability($scheduleId);
        } else {
            $schedule = $this->manageSchedulesService->UpdateAvailability($scheduleId, Date::Parse($start, $timezone), Date::Parse($end, $timezone));
        }

        $this->page->DisplayAvailability($schedule, $timezone);
    }

    public function SwitchLayoutType()
    {
        $scheduleId = $this->page->GetScheduleId();
        $layoutType = $this->page->GetLayoutType();
        $this->manageSchedulesService->SwitchLayoutType($scheduleId, $layoutType);
    }

    public function ProcessDataRequest($dataRequest)
    {
        if ($dataRequest == 'events') {
            $timezone = ServiceLocator::GetServer()->GetUserSession()->Timezone;
            $start = Date::Parse($this->page->GetCustomLayoutStartRange(), $timezone);
            $end = Date::Parse($this->page->GetCustomLayoutEndRange(), $timezone);
            $scheduleId = $this->page->GetScheduleId();

            $periods = $this->manageSchedulesService->GetCustomLayoutPeriods($start, $end, $scheduleId);

            $events = [];
            $dateFormat = Resources::GetInstance()->GetDateFormat('fullcalendar');
            foreach ($periods as $period) {
                $events[] = [
                        'id' => $period->BeginDate()->Format(Date::SHORT_FORMAT),
                        'start' => $period->BeginDate()->Format($dateFormat),
                        'end' => $period->EndDate()->Format($dateFormat),
                        'allDay' => false,
                        'startEditable' => true,
                ];
            }

            $this->page->BindEvents($events);
        }
    }

    public function AddLayoutSlot()
    {
        $timezone = ServiceLocator::GetServer()->GetUserSession()->Timezone;
        $start = Date::Parse($this->page->GetSlotStart(), $timezone);
        $end = Date::Parse($this->page->GetSlotEnd(), $timezone);
        $scheduleId = $this->page->GetScheduleId();

        Log::Debug('Adding custom layout slot. Start %s, End %s, Schedule %s', $start, $end, $scheduleId);
        $this->manageSchedulesService->AddCustomLayoutPeriod($scheduleId, $start, $end);
    }

    public function UpdateLayoutSlot()
    {
        $timezone = ServiceLocator::GetServer()->GetUserSession()->Timezone;
        $start = Date::Parse($this->page->GetSlotStart(), $timezone);
        $end = Date::Parse($this->page->GetSlotEnd(), $timezone);
        $originalStart = Date::Parse($this->page->GetSlotId(), $timezone);
        $scheduleId = $this->page->GetScheduleId();

        Log::Debug('Updating custom layout slot. Start %s, End %s, Schedule %s', $start, $end, $scheduleId);
        $this->manageSchedulesService->UpdateCustomLayoutPeriod($scheduleId, $start, $end, $originalStart);
    }

    public function DeleteLayoutSlot()
    {
        $timezone = ServiceLocator::GetServer()->GetUserSession()->Timezone;
        $start = Date::Parse($this->page->GetSlotStart(), $timezone);
        $end = Date::Parse($this->page->GetSlotEnd(), $timezone);
        $scheduleId = $this->page->GetScheduleId();

        Log::Debug('Deleting custom layout slot. Start %s, End %s, Schedule %s', $start, $end, $scheduleId);
        $this->manageSchedulesService->DeleteCustomLayoutPeriod($scheduleId, $start, $end);
    }

    public function ChangeDefaultStyle()
    {
        $scheduleId = $this->page->GetScheduleId();
        $style = $this->page->GetValue();

        Log::Debug('Changing default style. Schedule %s, Style %s', $scheduleId, $style);

        $this->manageSchedulesService->ChangeDefaultStyle($scheduleId, $style);
    }

    public function ChangeMaximumConcurrentReservations()
    {
        $scheduleId = $this->page->GetScheduleId();
        $max = $this->page->GetMaximumConcurrentReservations();
        $unlimited = $this->page->GetIsUnlimitedConcurrentReservations();

        Log::Debug('Changing maximum number of concurrent reservations. Schedule %s, Max %s, Unlimited %s', $scheduleId, $max, $unlimited);

        $this->manageSchedulesService->ChangeConcurrentReservationMaximum($scheduleId, $max, $unlimited);
    }

    public function ChangeResourcesPerReservation()
    {
        $scheduleId = $this->page->GetScheduleId();
        $max = $this->page->GetMaximumResourcesPerReservation();
        $unlimited = $this->page->GetIsUnlimitedMaximumResourcesPerReservation();

        Log::Debug('Changing maximum number of resources per reservation. Schedule %s, Max %s, Unlimited %s', $scheduleId, $max, $unlimited);

        $this->manageSchedulesService->ChangeResourcesPerReservation($scheduleId, $max, $unlimited);
    }

    protected function LoadValidators($action)
    {
        if ($action == ManageSchedules::ActionChangeLayout) {
            $validateSingle = $this->page->GetUsingSingleLayout();
            if ($validateSingle) {
                $reservableSlots = $this->page->GetReservableSlots();
                $blockedSlots = $this->page->GetBlockedSlots();
            } else {
                $reservableSlots = $this->page->GetDailyReservableSlots();
                $blockedSlots = $this->page->GetDailyBlockedSlots();
            }
            $this->page->RegisterValidator(
                'layoutValidator',
                new LayoutValidator($reservableSlots, $blockedSlots, $validateSingle)
            );
        }
    }
}
