<?php

class ReservationPersistenceFactory implements IReservationPersistenceFactory
{
    public function CreateAddService(): IReservationPersistenceService
    {
        return new AddReservationPersistenceService(new ReservationRepository());
    }

    public function CreateUpdateService(): IUpdateReservationPersistenceService
    {
        return new UpdateReservationPersistenceService(new ReservationRepository());
    }

    public function CreateDeleteService(): IDeleteReservationPersistenceService
    {
        return new DeleteReservationPersistenceService(new ReservationRepository());
    }
}
