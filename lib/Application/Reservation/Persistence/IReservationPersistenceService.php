<?php

/**
 * @template T of ReservationSeries the kind of series the service persists:
 *           a new ReservationSeries, or an ExistingReservationSeries for
 *           updates and deletes
 */
interface IReservationPersistenceService
{
    /**
     * @param T $reservation
     */
    public function Persist($reservation);
}
