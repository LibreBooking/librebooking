<?php

require_once(ROOT_DIR . 'lib/Application/Reservation/Persistence/IReservationPersistenceService.php');

/**
 * @extends IReservationPersistenceService<ExistingReservationSeries>
 */
interface IUpdateReservationPersistenceService extends IReservationPersistenceService
{
    /**
     * @param int $reservationInstanceId
     * @return ExistingReservationSeries
     */
    public function LoadByInstanceId($reservationInstanceId);

    /**
     * @param string $referenceNumber
     * @return ExistingReservationSeries
     */
    public function LoadByReferenceNumber($referenceNumber);
}

class UpdateReservationPersistenceService implements IUpdateReservationPersistenceService
{
    /**
     * @var IReservationRepository
     */
    private $_repository;

    public function __construct(IReservationRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function LoadByInstanceId($reservationInstanceId)
    {
        return $this->_repository->LoadById($reservationInstanceId);
    }

    public function Persist($existingReservationSeries)
    {
        $this->_repository->Update($existingReservationSeries);
    }

    public function LoadByReferenceNumber($referenceNumber)
    {
        return $this->_repository->LoadByReferenceNumber($referenceNumber);
    }
}
