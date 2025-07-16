<?php

require_once(ROOT_DIR . 'Pages/Admin/ManageBlackoutsPage.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class ManageBlackoutsActions
{
    public const ADD = 'add';
    public const DELETE = 'delete';
    public const LOAD = 'load';
    public const UPDATE = 'update';
    public const DELETE_MULTIPLE = 'deleteMultiple';
}

class ManageBlackoutsPresenter extends ActionPresenter
{
    /**
     * @var IManageBlackoutsPage
     */
    private $page;

    /**
     * @var IManageBlackoutsService
     */
    private $manageBlackoutsService;

    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @var IResourceRepository
     */
    private $resourceRepository;

    public function __construct(
        IManageBlackoutsPage $page,
        IManageBlackoutsService $manageBlackoutsService,
        IScheduleRepository $scheduleRepository,
        IResourceRepository $resourceRepository
    ) {
        parent::__construct($page);

        $this->page = $page;
        $this->manageBlackoutsService = $manageBlackoutsService;
        $this->scheduleRepository = $scheduleRepository;
        $this->resourceRepository = $resourceRepository;

        $this->AddAction(ManageBlackoutsActions::ADD, 'AddBlackout');
        $this->AddAction(ManageBlackoutsActions::DELETE, 'DeleteBlackout');
        $this->AddAction(ManageBlackoutsActions::LOAD, 'LoadBlackout');
        $this->AddAction(ManageBlackoutsActions::UPDATE, 'UpdateBlackout');
        $this->AddAction(ManageBlackoutsActions::DELETE_MULTIPLE, 'DeleteMultiple');
    }

    public function PageLoad($userTimezone)
    {
        $session = ServiceLocator::GetServer()->GetUserSession();

        $this->page->BindSchedules($this->scheduleRepository->GetAll());
        $this->page->BindResources($this->resourceRepository->GetResourceList());

        $startDateString = $this->page->GetStartDate();
        $endDateString = $this->page->GetEndDate();

        $startDate = $this->GetDate($startDateString, $userTimezone, -7);
        $endDate = $this->GetDate($endDateString, $userTimezone, 7);
        $scheduleId = $this->page->GetScheduleId();
        $resourceId = $this->page->GetResourceId();

        $this->page->SetStartDate($startDate);
        $this->page->SetEndDate($endDate);
        $this->page->SetScheduleId($scheduleId);
        $this->page->SetResourceId($resourceId);

        $filter = new BlackoutFilter($startDate, $endDate, $scheduleId, $resourceId);

        $blackouts = $this->manageBlackoutsService->LoadFiltered(
            $this->page->GetPageNumber(),
            null,
            $this->page->GetSortField(),
            $this->page->GetSortDirection(),
            $filter,
            $session
        );

        $this->page->BindBlackouts($blackouts->Results());
        $this->page->BindPageInfo($blackouts->PageInfo());

        $this->page->ShowPage();
    }

    private function GetDate($dateString, $timezone, $defaultDays)
    {
        $date = null;
        if (is_null($dateString)) {
            $date = Date::Now()->AddDays($defaultDays)->ToTimezone($timezone)->GetDate();
        } elseif (!empty($dateString)) {
            $date = Date::Parse($dateString, $timezone);
        }

        return $date;
    }

    public function AddBlackout()
    {
        $session = ServiceLocator::GetServer()->GetUserSession();

        $resourceIds = [];
        if ($this->page->GetApplyBlackoutToAllResources()) {
            $scheduleId = $this->page->GetBlackoutScheduleId();
            $resources = $this->resourceRepository->GetScheduleResources($scheduleId);
            foreach ($resources as $resource) {
                $resourceIds[] = $resource->GetId();
            }
        } else {
            $resourceIds[] = $this->page->GetBlackoutResourceId();
        }

        $startDate = $this->page->GetBlackoutStartDate();
        $startTime = $this->page->GetBlackoutStartTime();
        $endDate = $this->page->GetBlackoutEndDate();
        $endTime = $this->page->GetBlackoutEndTime();

        $blackoutDate = DateRange::Create($startDate . ' ' . $startTime, $endDate . ' ' . $endTime, $session->Timezone);

        $title = $this->page->GetBlackoutTitle();
        $conflictAction = $this->page->GetBlackoutConflictAction();

        $repeatOptionsFactory = new RepeatOptionsFactory();
        $repeatOptions = $repeatOptionsFactory->CreateFromComposite($this->page, $session->Timezone);

        $result = $this->manageBlackoutsService->Add(
            $blackoutDate,
            $resourceIds,
            $title,
            ReservationConflictResolution::Create($conflictAction),
            $repeatOptions
        );

        $this->page->ShowAddResult(
            $result->WasSuccessful(),
            $result->Message(),
            $result->ConflictingReservations(),
            $result->ConflictingBlackouts(),
            $session->Timezone
        );
    }

    public function DeleteBlackout()
    {
        $id = $this->page->GetBlackoutId();
        $scope = $this->page->GetSeriesUpdateScope();

        Log::Debug('Deleting blackout. BlackoutId=%s, DeleteScope=%s', $id, $scope);

        $this->manageBlackoutsService->Delete($id, $scope);
    }

    public function LoadBlackout()
    {
        $id = $this->page->GetBlackoutId();
        $session = ServiceLocator::GetServer()->GetUserSession();

        Log::Debug('Loading blackout for editing. Id=%s', $id);
        $series = $this->manageBlackoutsService->LoadBlackout($id, $session->UserId);

        if ($series != null) {
            $this->page->BindResources($this->resourceRepository->GetResourceList());
            $this->page->SetBlackoutResources($series->ResourceIds());
            $this->page->SetBlackoutId($id);
            $this->page->SetBlackoutStartDate($series->CurrentBlackout()->StartDate()->ToTimezone($session->Timezone));
            $this->page->SetBlackoutEndDate($series->CurrentBlackout()->EndDate()->ToTimezone($session->Timezone));
            $this->page->SetTitle($series->Title());
            $this->page->SetIsRecurring($series->RepeatType() != RepeatType::None);
            $repeatConfiguration = $series->RepeatConfiguration();
            $this->page->SetRepeatInterval($repeatConfiguration->Interval);
            $this->page->SetRepeatMonthlyType($repeatConfiguration->MonthlyType);
            if ($repeatConfiguration->TerminationDate != null) {
                $this->page->SetRepeatTerminationDate($repeatConfiguration->TerminationDate->ToTimezone($session->Timezone));
            }
            $this->page->SetRepeatType($repeatConfiguration->Type);
            $this->page->SetRepeatWeekdays($repeatConfiguration->Weekdays);
            $this->page->SetCustomRepeatDates($series->CustomRepeatDates());
            $this->page->SetWasBlackoutFound(true);
        } else {
            $this->page->SetWasBlackoutFound(false);
        }

        $this->page->ShowBlackout();
    }

    public function UpdateBlackout()
    {
        $session = ServiceLocator::GetServer()->GetUserSession();

        $id = $this->page->GetUpdateBlackoutId();
        $scope = $this->page->GetSeriesUpdateScope();

        Log::Debug('Updating blackout. BlackoutId=%s, UpdateScope=%s', $id, $scope);

        $resourceIds = $this->page->GetBlackoutResourceIds();
        $startDate = $this->page->GetBlackoutStartDate();
        $startTime = $this->page->GetBlackoutStartTime();
        $endDate = $this->page->GetBlackoutEndDate();
        $endTime = $this->page->GetBlackoutEndTime();
        $blackoutDate = DateRange::Create($startDate . ' ' . $startTime, $endDate . ' ' . $endTime, $session->Timezone);

        $title = $this->page->GetBlackoutTitle();
        $conflictAction = $this->page->GetBlackoutConflictAction();

        $repeatOptionsFactory = new RepeatOptionsFactory();
        $repeatOptions = $repeatOptionsFactory->CreateFromComposite($this->page, $session->Timezone);

        $result = $this->manageBlackoutsService->Update(
            $id,
            $blackoutDate,
            $resourceIds,
            $title,
            ReservationConflictResolution::Create($conflictAction),
            $repeatOptions,
            $scope
        );

        $this->page->ShowUpdateResult(
            $result->WasSuccessful(),
            $result->Message(),
            $result->ConflictingReservations(),
            $result->ConflictingBlackouts(),
            $session->Timezone
        );
    }

    public function DeleteMultiple()
    {
        $ids = $this->page->GetDeletedBlackoutIds();
        Log::Debug('Blackout multiple delete. Ids=%s', implode(',', $ids));
        foreach ($ids as $id) {
            $this->manageBlackoutsService->Delete($id, SeriesUpdateScope::ThisInstance);
        }
    }
}
