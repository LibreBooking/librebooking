<?php

require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Pages/MonitorDisplayPage.php');

class MonitorDisplayPresenter extends ActionPresenter
{
    /**
     * @var IMonitorDisplayPage
     */
    private $page;
    /**
     * @var IResourceService
     */
    private $resourceService;
    /**
     * @var IReservationService
     */
    private $reservationService;
    /**
     * @var IScheduleService
     */
    private $scheduleService;
    private ILayoutFactory $layoutFactory;

    public function __construct(
        IMonitorDisplayPage $page,
        IResourceService $resourceService,
        IReservationService $reservationService,
        IScheduleService $scheduleService,
        ILayoutFactory $layoutFactory
    ) {
        parent::__construct($page);
        $this->page = $page;
        $this->resourceService = $resourceService;
        $this->reservationService = $reservationService;
        $this->scheduleService = $scheduleService;
        $this->layoutFactory = $layoutFactory;
    }

    public function PageLoad()
    {
        $schedules = $this->scheduleService->GetAll();
        $defaultId = -1;
        foreach ($schedules as $s) {
            if ($s->GetIsDefault()) {
                $defaultId = $s->GetId();
                break;
            }
        }

        $resources = $this->GetResources($defaultId);
        $this->page->BindSchedules($schedules);
        $this->page->BindResources($resources);
    }

    private function GetResources($scheduleId)
    {
        return $this->resourceService->GetScheduleResources($scheduleId, true, new NullUserSession());
    }

    public function ProcessDataRequest($dataRequest)
    {
        if ($dataRequest == 'resources') {
            $this->RequestResources();
        } elseif ($dataRequest == 'schedule') {
            $this->RequestSchedule();
        }
    }

    private function RequestResources()
    {
        $scheduleId = $this->page->GetScheduleId();
        $resourceDtos = $this->GetResources($scheduleId);
        $resources = [];
        foreach ($resourceDtos as $r) {
            $resources[] = ['id' => $r->GetId(), 'name' => $r->GetName()];
        }
        $this->page->RebindResources($resources);
    }

    private function RequestSchedule()
    {
        $scheduleId = $this->page->GetScheduleId();
        $resourceId = $this->page->GetResourceId();
        $layout = $this->scheduleService->GetLayout($scheduleId, $this->layoutFactory);

        $resources = $this->GetResources($scheduleId);
        $displayResources = $resources;

        if ($resourceId != -1) {
            foreach ($resources as $r) {
                if ($r->GetResourceId() == $resourceId) {
                    $displayResources = [$r];
                }
            }
        }

        $timezone = $layout->Timezone();
        $startDate = Date::Now()->ToTimezone($timezone)->GetDate();
        $endDate = $startDate->AddDays($this->page->GetDaysToView());
        $reservations = $this->reservationService->GetReservations(new DateRange($startDate, $endDate->AddDays(1)), $scheduleId, $timezone, $resourceId);

        $dailyLayout = $this->scheduleService->GetDailyLayout($scheduleId, $this->layoutFactory, $reservations);
        $this->page->RebindSchedule(new DateRange($startDate, $endDate), $dailyLayout, $displayResources, $this->page->GetFormat());
    }
}
