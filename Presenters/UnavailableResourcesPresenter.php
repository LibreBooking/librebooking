<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Presenters/UnavailableResourcesPresenter.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class UnavailableResourcesPresenter
{
    /**
     * @var IAvailableResourcesPage
     */
    private $page;
    /**
     * @var IReservationConflictIdentifier
     */
    private $reservationConflictIdentifier;
    /**
     * @var UserSession
     */
    private $userSession;
    /**
     * @var IResourceRepository
     */
    private $resourceRepository;
    /**
     * @var IReservationRepository
     */
    private $reservationRepository;

    public function __construct(
        IAvailableResourcesPage $page,
        IReservationConflictIdentifier $reservationConflictIdentifier,
        UserSession $userSession,
        IResourceRepository $resourceRepository,
        IReservationRepository $reservationRepository
    ) {
        $this->page = $page;
        $this->reservationConflictIdentifier = $reservationConflictIdentifier;
        $this->userSession = $userSession;
        $this->resourceRepository = $resourceRepository;
        $this->reservationRepository = $reservationRepository;
    }

    public function PageLoad()
    {
        $duration = DateRange::Create(
            $this->page->GetStartDate() . ' ' . $this->page->GetStartTime(),
            $this->page->GetEndDate() . ' ' . $this->page->GetEndTime(),
            $this->userSession->Timezone
        );

        $resources = $this->resourceRepository->GetScheduleResources($this->page->GetScheduleId());

        $unavailable = [];
        $referenceNumber = $this->page->GetReferenceNumber();
        $series = null;
        $existingSeries = false;
        if (!empty($referenceNumber)) {
            $series = $this->reservationRepository->LoadByReferenceNumber($referenceNumber);
            $series->UpdateDuration($duration);
            $existingSeries = true;
        }

        foreach ($resources as $resource) {
            if (!$existingSeries) {
                $series = ReservationSeries::Create($this->userSession->UserId, $resource, '', '', $duration, new RepeatNone(), $this->userSession);
            }
            $conflict = $this->reservationConflictIdentifier->GetConflicts($series);

            if (!$conflict->AllowReservation()) {
                $unavailable[] = $resource->GetId();
            }
        }

        $this->page->BindUnavailable(array_unique($unavailable));
    }
}
