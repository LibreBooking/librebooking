<?php

interface IReservationNotificationFactory
{
    /**
     * @param string $reservationAction one of the ReservationAction constants
     * @param UserSession $userSession
     * @return IReservationNotificationService
     */
    public function Create($reservationAction, $userSession);
}
