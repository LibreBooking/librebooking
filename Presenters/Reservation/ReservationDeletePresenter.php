<?php

require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

interface IReservationDeletePresenter
{
    /**
     * @return ExistingReservationSeries
     */
    public function BuildReservation();

    /**
     * @param ExistingReservationSeries $reservationSeries
     */
    public function HandleReservation($reservationSeries);
}

class ReservationDeletePresenter implements IReservationDeletePresenter
{
    /**
     * @var IReservationDeletePage
     */
    private $page;

    /**
     * @var IDeleteReservationPersistenceService
     */
    private $persistenceService;

    /**
     * @var IReservationHandler
     */
    private $handler;

    /**
     * @var UserSession
     */
    private $userSession;

    public function __construct(
        IReservationDeletePage $page,
        IDeleteReservationPersistenceService $persistenceService,
        IReservationHandler $handler,
        UserSession $userSession
    ) {
        $this->page = $page;
        $this->persistenceService = $persistenceService;
        $this->handler = $handler;
        $this->userSession = $userSession;
    }

    /**
     * @return ExistingReservationSeries
     */
    public function BuildReservation()
    {
        $referenceNumber = $this->page->GetReferenceNumber();
        $existingSeries = $this->persistenceService->LoadByReferenceNumber($referenceNumber);
        $existingSeries->ApplyChangesTo($this->page->GetSeriesUpdateScope());
        $existingSeries->Delete($this->userSession, $this->page->GetReason());

        return $existingSeries;
    }

    /**
     * @param ExistingReservationSeries $reservationSeries
     */
    public function HandleReservation($reservationSeries)
    {
        Log::Debug('Deleting reservation %s', $reservationSeries->CurrentInstance()->ReferenceNumber());

        $this->handler->Handle($reservationSeries, $this->page);
    }
}
