<?php

require_once(ROOT_DIR . 'config/timezones.php');        //NEEDED?

class ViewSchedulesPresenter
{
    /**
     * @var ResourceViewerViewResourcesPage
     */
    private $page;

    /**
     * @var IResourceRepository
     */
    private $resourceRepo;

    /**
     * @var ManageScheduleService
     */
    private $manageSchedulesService;

    /**
     * @var IGroupViewRepository
     */
    private $groupViewRepository;

    public function __construct(
        ScheduleViewerViewSchedulesPage $page,
        ResourceRepository $resourceRepo,
        ManageScheduleService $manageSchedulesService,
        IGroupViewRepository $groupViewRepository
    ) {
        $this->page = $page;
        $this->resourceRepo = $resourceRepo;
        $this->manageSchedulesService = $manageSchedulesService;
        $this->groupViewRepository = $groupViewRepository;
    }

    public function PageLoad()
    {
        $results = $this->manageSchedulesService->GetList(null, null);
        $schedules = $results->Results();

        $sourceSchedules = $this->manageSchedulesService->GetSourceSchedules();

        $layouts = [];
        foreach ($schedules as $schedule) {
            $layout = $this->manageSchedulesService->GetLayout($schedule);
            $layouts[$schedule->GetId()] = $layout;
        }

        $this->page->BindResources($this->GetResources());
        $this->page->BindSchedules($schedules, $layouts, $sourceSchedules);
        $this->page->BindGroups($this->groupViewRepository->GetGroupsByRole(RoleLevel::SCHEDULE_ADMIN));
        $this->page->BindPageInfo($results->PageInfo());
    }

    /**
     * @return BookableResource[] resources indexed by scheduleId
     */
    public function GetResources()
    {
        $resources = [];

        $all = $this->resourceRepo->GetResourceList();
        /** @var BookableResource $resource */
        foreach ($all as $resource) {
            if ($resource->GetStatusId() != 0) {
                $resources[$resource->GetScheduleId()][] = $resource;
            }
        }

        return $resources;
    }
}
