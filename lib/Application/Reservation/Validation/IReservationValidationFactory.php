<?php

interface IReservationValidationFactory
{
    /**
     * @param string $reservationAction one of the ReservationAction constants
     * @param UserSession $userSession
     * @return IReservationValidationService
     */
    public function Create($reservationAction, $userSession);
}
