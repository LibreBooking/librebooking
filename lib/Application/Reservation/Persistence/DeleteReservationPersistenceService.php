<?php

require_once(ROOT_DIR . 'lib/Application/Reservation/Persistence/IReservationPersistenceService.php');

/**
 * @extends IReservationPersistenceService<ExistingReservationSeries>
 */
interface IDeleteReservationPersistenceService extends IReservationPersistenceService
{
    /**
     * @param string $referenceNumber
     * @return ExistingReservationSeries
     */
    public function LoadByReferenceNumber($referenceNumber);
}

class DeleteReservationPersistenceService implements IDeleteReservationPersistenceService
{
    /**
     * @var IReservationRepository
     */
    private $_repository;

    public function __construct(IReservationRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function LoadByReferenceNumber($referenceNumber)
    {
        return $this->_repository->LoadByReferenceNumber($referenceNumber);
    }

    public function Persist($existingReservationSeries)
    {
        $this->_repository->Delete($existingReservationSeries);
    }
}
