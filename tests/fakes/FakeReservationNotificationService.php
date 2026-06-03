<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Reservation/Notification/IReservationNotificationService.php');

class FakeReservationNotificationService implements IReservationNotificationService
{
    /**
     * @var ReservationSeries
     */
    public $_ReservationNotified;

    public function Notify($reservationSeries)
    {
        $this->_ReservationNotified = $reservationSeries;
    }
}
