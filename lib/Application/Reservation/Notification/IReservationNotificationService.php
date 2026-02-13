<?php

interface IReservationNotificationService
{
    /**
     * @param $reservationSeries ReservationSeries|ExistingReservationSeries
     * @return void
     */
    public function Notify($reservationSeries);
}

abstract class ReservationNotificationService implements IReservationNotificationService
{
    /**
     * @var IReservationNotification[]
     */
    protected $notifications;

    /**
     * @param IReservationNotification[] $notifications
     */
    public function __construct($notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * @param $reservationSeries ReservationSeries|ExistingReservationSeries
     * @return void
     */
    public function Notify($reservationSeries)
    {
        $referenceNumber = $reservationSeries->CurrentInstance()->ReferenceNumber();

        foreach ($this->notifications as $notification) {
            try {
                Log::Debug('Calling notify on %s for reservation %s', get_class($notification), $referenceNumber);

                $notification->Notify($reservationSeries);
            } catch (Exception $ex) {
                Log::Error('Error sending notification of type %s for reservation %s. Exception: %s', get_class($notification), $referenceNumber, $ex);
            }
        }
    }
}
