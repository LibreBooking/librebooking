<?php

require_once(ROOT_DIR . 'lib/Config/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');
require_once(ROOT_DIR . 'lib/Server/namespace.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Presenters/Schedule/SchedulePageBuilder.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');

interface ISchedulePresenter
{
    public function PageLoad(UserSession $user);
}

class SchedulePresenter extends ActionPresenter implements ISchedulePresenter
{
    /**
     * @var ISchedulePage
     */
    private $_page;

    /**
     * @var IScheduleService
     */
    private $_scheduleService;

    /**
     * @var IResourceService
     */
    private $_resourceService;

    /**
     * @var ISchedulePageBuilder
     */
    private $_builder;

    /**
     * @var IReservationService
     */
    private $_reservationService;

    /**
     * @param ISchedulePage $page
     * @param IScheduleService $scheduleService
     * @param IResourceService $resourceService
     * @param ISchedulePageBuilder $schedulePageBuilder
     * @param IReservationService $reservationService
     */
    public function __construct(
        ISchedulePage $page,
        IScheduleService $scheduleService,
        IResourceService $resourceService,
        ISchedulePageBuilder $schedulePageBuilder,
        IReservationService $reservationService
    ) {
        parent::__construct($page);
        $this->_page = $page;
        $this->_scheduleService = $scheduleService;
        $this->_resourceService = $resourceService;
        $this->_builder = $schedulePageBuilder;
        $this->_reservationService = $reservationService;
    }

    public function PageLoad(UserSession $user, $loadReservations = false)
    {
        $showInaccessibleResources = $this->_page->ShowInaccessibleResources();

        $schedules = $this->_scheduleService->GetAll($showInaccessibleResources, $user);

        if (count($schedules) == 0) {
            $this->_page->ShowPermissionError(true);
            return;
        }

        $this->_page->ShowPermissionError(false);

        $currentSchedule = $this->_builder->GetCurrentSchedule($this->_page, $schedules, $user);
        $targetTimezone = $this->_page->GetDisplayTimezone($user, $currentSchedule);

        $activeScheduleId = $currentSchedule->GetId();
        $this->_builder->BindSchedules($this->_page, $schedules, $currentSchedule);

        $scheduleDates = $this->_builder->GetScheduleDates($user, $currentSchedule, $this->_page);
        $this->_builder->BindDisplayDates($this->_page, $scheduleDates, $currentSchedule);
        $this->_builder->BindSpecificDates($user, $this->_page, $this->_page->GetSelectedDates(), $currentSchedule);

        $resourceGroups = $this->_resourceService->GetResourceGroups($activeScheduleId, $user);
        $this->_builder->BindResourceGroups($this->_page, $resourceGroups);

        $resourceTypes = $this->_resourceService->GetResourceTypes();
        $this->_builder->BindResourceTypes($this->_page, $resourceTypes);

        $resourceAttributes = $this->_resourceService->GetResourceAttributes();
        $resourceTypeAttributes = $this->_resourceService->GetResourceTypeAttributes();

        $filter = $this->_builder->GetResourceFilter($activeScheduleId, $this->_page);
        $this->_builder->BindResourceFilter($this->_page, $filter, $resourceAttributes, $resourceTypeAttributes);

        $this->UserResourcePermissions();

        $resources = $this->_resourceService->GetScheduleResources($activeScheduleId, $showInaccessibleResources, $user, $filter);

        $reservationListing = new EmptyReservationListing();
        if ($loadReservations) {
            $rids = [];
            foreach ($resources as $resource) {
                $rids[] = $resource->Id;
            }
            $reservationListing = $this->_reservationService->GetReservations($scheduleDates, $activeScheduleId, $targetTimezone, $rids);
        }

        $dailyLayout = $this->_scheduleService->GetDailyLayout($activeScheduleId, new ScheduleLayoutFactory($targetTimezone), $reservationListing);

        $this->_builder->BindReservations($this->_page, $resources, $dailyLayout);
    }

    public function GetLayout(UserSession $user)
    {
        $scheduleId = $this->_page->GetScheduleId();
        $layoutDate = $this->_page->GetLayoutDate();

        $requestedDate = Date::Parse($layoutDate, $user->Timezone);

        $layout = $this->_scheduleService->GetLayout($scheduleId, new ScheduleLayoutFactory($user->Timezone));
        $periods = $layout->GetLayout($requestedDate);

        $this->_page->SetLayoutResponse(new ScheduleLayoutSerializable(periods: $periods, layoutDate: $requestedDate));
    }

    public function LoadReservations()
    {
        $filter = $this->_page->GetReservationRequest();
        $items = $this->_reservationService->Search($filter->DateRange(), $filter->ScheduleId(), $filter->ResourceIds(), $filter->OwnerId(), $filter->ParticipantId());
        $this->_page->BindReservations($items);
    }

    /**
     * Gets the resources the user has permissions (full access and view only permissions)
     * This is used to block a user from seeing reservation details if he has no permissions to its resources
     */
    public function UserResourcePermissions()
    {
        $resourceIds = [];

        $command = new GetUserPermissionsCommand(ServiceLocator::GetServer()->GetUserSession()->UserId);
        $reader = ServiceLocator::GetDatabase()->Query($command);

        while ($row = $reader->GetRow()) {
            $resourceIds[] = $row[ColumnNames::RESOURCE_ID];
        }
        $reader->Free();

        $this->_page->BindViewableResourceReservations($resourceIds);
    }
}
