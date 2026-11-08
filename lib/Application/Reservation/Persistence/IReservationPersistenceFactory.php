<?php

interface IReservationPersistenceFactory
{
    /**
     * @param string $reservationAction one of the ReservationAction constants
     */
    public function Create(string $reservationAction): IReservationPersistenceService;
}
