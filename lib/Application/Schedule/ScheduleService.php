<?php

interface IScheduleService
{
    /**
     * @param bool $includeInaccessible
     * @param UserSession|null $session
     * @return Schedule[]
     */
    public function GetAll($includeInaccessible = true, ?UserSession $session = null);

    /**
     * @param int $scheduleId
     * @param ILayoutFactory $layoutFactory factory to use to create the schedule layout
     * @return IScheduleLayout
     */
    public function GetLayout($scheduleId, ILayoutFactory $layoutFactory);

    /**
     * @param int $scheduleId
     * @param ILayoutFactory $layoutFactory
     * @param IReservationListing $reservationListing
     * @return IDailyLayout
     */
    public function GetDailyLayout($scheduleId, ILayoutFactory $layoutFactory, $reservationListing);
}

class ScheduleService implements IScheduleService
{
    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @var IResourceService
     */
    private $resourceService;

    /**
     * @var IDailyLayoutFactory
     */
    private $dailyLayoutFactory;

    public function __construct(IScheduleRepository $scheduleRepository, IResourceService $resourceService, IDailyLayoutFactory $dailyLayoutFactory)
    {
        $this->scheduleRepository = $scheduleRepository;
        $this->resourceService = $resourceService;
        $this->dailyLayoutFactory = $dailyLayoutFactory;
    }

    public function GetAll($includeInaccessible = true, ?UserSession $session = null)
    {
        $schedules = $this->scheduleRepository->GetAll();

        if ($includeInaccessible == false) {
            $resources = $this->resourceService->GetAllResources($includeInaccessible, $session);
            $availableSchedules = [];

            if (count($resources) > 0) {
                foreach ($schedules as $schedule) {
                    foreach ($resources as $resource) {
                        if ($resource->ScheduleId == $schedule->GetId()) {
                            $availableSchedules[] = $schedule;
                            break;
                        }
                    }
                }
            }

            return $availableSchedules;
        } else {
            return $schedules;
        }
    }

    public function GetLayout($scheduleId, ILayoutFactory $layoutFactory)
    {
        return $this->scheduleRepository->GetLayout($scheduleId, $layoutFactory);
    }

    public function GetDailyLayout($scheduleId, ILayoutFactory $layoutFactory, $reservationListing)
    {
        return $this->dailyLayoutFactory->Create($reservationListing, $this->GetLayout($scheduleId, $layoutFactory));
    }

    public function GetSchedule($scheduleId)
    {
        return $this->scheduleRepository->LoadById($scheduleId);
    }
}
