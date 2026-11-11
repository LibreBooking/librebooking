<?php

interface IReservationPersistenceFactory
{
    public function CreateAddService(): IReservationPersistenceService;

    /**
     * The service for updating, approving, checking in or moving a reservation.
     */
    public function CreateUpdateService(): IUpdateReservationPersistenceService;

    public function CreateDeleteService(): IDeleteReservationPersistenceService;
}
