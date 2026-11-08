<?php

class ReservationPersistenceFactory implements IReservationPersistenceFactory
{
    private $services = [];
    private $creationStrategies = [];

    public function __construct()
    {
        $this->creationStrategies[ReservationAction::Approve] = 'CreateUpdateService';
        $this->creationStrategies[ReservationAction::Create] = 'CreateAddService';
        $this->creationStrategies[ReservationAction::Delete] = 'CreateDeleteService';
        $this->creationStrategies[ReservationAction::Update] = 'CreateUpdateService';
    }

    /**
     * @param string $reservationAction one of the ReservationAction constants
     */
    public function Create(string $reservationAction): IReservationPersistenceService
    {
        if (!array_key_exists($reservationAction, $this->services)) {
            $this->AddCachedService($reservationAction);
        }

        return $this->services[$reservationAction];
    }

    private function AddCachedService($reservationAction)
    {
        $createMethod = $this->creationStrategies[$reservationAction];
        $this->services[$reservationAction] = $this->$createMethod();
    }

    private function CreateAddService()
    {
        return new AddReservationPersistenceService(new ReservationRepository());
    }

    private function CreateDeleteService()
    {
        return new DeleteReservationPersistenceService(new ReservationRepository());
    }

    private function CreateUpdateService()
    {
        return new UpdateReservationPersistenceService(new ReservationRepository());
    }
}
