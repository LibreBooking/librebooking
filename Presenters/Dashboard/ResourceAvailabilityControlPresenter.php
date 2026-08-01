<?php

require_once(ROOT_DIR . 'Controls/Dashboard/ResourceAvailabilityControl.php');

class ResourceAvailabilityControlPresenter
{
    /**
     * @var IResourceAvailabilityControl
     */
    private $control;

    /**
     * @var IResourceService
     */
    private $resourceService;

    /**
     * @var IReservationViewRepository
     */
    private $reservationViewRepository;
    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;

    public function __construct(
        IResourceAvailabilityControl $control,
        IResourceService $resourceService,
        IReservationViewRepository $reservationViewRepository,
        IScheduleRepository $scheduleRepository
    ) {
        $this->control = $control;
        $this->resourceService = $resourceService;
        $this->reservationViewRepository = $reservationViewRepository;
        $this->scheduleRepository = $scheduleRepository;
    }

    public function PageLoad(UserSession $user)
    {
        $now = Date::Now();

        $resources = $this->resourceService->GetAllResources(false, $user);
        $reservations = $this->GetReservations($this->reservationViewRepository->GetReservations($now, $now->AddDays(30)));

        // Build schedule lookup table
        $schedules = $this->scheduleRepository->GetAll();
        $schedulesById = [];
        foreach ($schedules as $schedule) {
            $schedulesById[$schedule->GetId()] = $schedule;
        }

        $available = [];
        $unavailable = [];
        $allday = [];

        foreach ($resources as $resource) {
            if ($resource->StatusId == ResourceStatus::HIDDEN) {
                continue;
            }

            // Check schedule availability
            if (isset($schedulesById[$resource->ScheduleId])) {
                $schedule = $schedulesById[$resource->ScheduleId];
                if ($schedule->HasAvailability()) {
                    $begin = $schedule->GetAvailabilityBegin();
                    $end = $schedule->GetAvailabilityEnd();

                    // If schedule is past, don't show this resource
                    if ($now->GreaterThanOrEqual($end)) {
                        continue;
                    }

                    // If schedule is future, add to unavailable with availability begin date
                    if ($now->LessThan($begin)) {
                        $futureReservation = new ReservationItemView(
                            startDate: $begin,
                            endDate: $begin,
                            resourceName: $resource->GetName(),
                            resourceId: $resource->GetId()
                        );
                        $futureReservation->ResourceColor = $resource->GetColor();
                        $unavailable[$resource->ScheduleId][] = new UnavailableDashboardItem(
                            resource: $resource,
                            currentReservation: $futureReservation,
                            isFutureScheduleAvailability: true
                        );
                        continue;
                    }
                }
            }

            $reservation = $this->GetOngoingReservation($resource, $reservations);

            if ($reservation != null) {
                $lastReservationBeforeOpening = $this->GetLastReservationBeforeAnOpening($resource, $reservations);

                if ($lastReservationBeforeOpening == null) {
                    $lastReservationBeforeOpening = $reservation;
                }

                if (!$reservation->EndDate->DateEquals($now)) {
                    $allday[$resource->ScheduleId][] = new UnavailableDashboardItem(
                        resource: $resource,
                        currentReservation: $lastReservationBeforeOpening,
                        isFutureScheduleAvailability: false
                    );
                } else {
                    $unavailable[$resource->ScheduleId][] = new UnavailableDashboardItem(
                        resource: $resource,
                        currentReservation: $lastReservationBeforeOpening,
                        isFutureScheduleAvailability: false
                    );
                }
            } else {
                $resourceId = $resource->GetId();
                if (array_key_exists($resourceId, $reservations)) {
                    $available[$resource->ScheduleId][] = new AvailableDashboardItem($resource, $reservations[$resourceId][0]);
                } else {
                    $available[$resource->ScheduleId][] = new AvailableDashboardItem($resource);
                }
            }
        }

        $this->control->SetAvailable($available);
        $this->control->SetUnavailable($unavailable);
        $this->control->SetUnavailableAllDay($allday);
        $this->control->SetSchedules($schedules);
    }

    /**
     * @param ResourceDto $resource
     * @param ReservationItemView[][] $reservations
     * @return ReservationItemView|null
     */
    private function GetOngoingReservation($resource, $reservations)
    {
        if (array_key_exists($resource->GetId(), $reservations) && $reservations[$resource->GetId()][0]->StartDate->LessThan(Date::Now())) {
            return $reservations[$resource->GetId()][0];
        }

        return null;
    }

    /**
     * @param ReservationItemView[] $reservations
     * @return ReservationItemView[][]
     */
    private function GetReservations($reservations)
    {
        $indexed = [];
        foreach ($reservations as $reservation) {
            $indexed[$reservation->ResourceId][] = $reservation;
        }

        return $indexed;
    }

    /**
     * @param ResourceDto $resource
     * @param ReservationItemView[][] $reservations
     * @return null|ReservationItemView
     */
    private function GetLastReservationBeforeAnOpening($resource, $reservations)
    {
        $resourceId = $resource->GetId();
        if (!array_key_exists($resourceId, $reservations)) {
            return null;
        }

        $resourceReservations = $reservations[$resourceId];
        for ($i = 0; $i < count($resourceReservations) - 1; $i++) {
            $current = $resourceReservations[$i];
            $next = $resourceReservations[$i + 1];

            if ($current->EndDate->Equals($next->StartDate)) {
                continue;
            }

            return $current;
        }

        return $resourceReservations[count($resourceReservations) - 1];
    }
}
