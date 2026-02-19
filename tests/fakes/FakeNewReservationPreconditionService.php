<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class FakeNewReservationPreconditionService implements INewReservationPreconditionService
{
    /**
     * @param INewReservationPage $page
     */
    public function CheckAll(INewReservationPage $page, UserSession $user)
    {
        // TODO: Implement CheckAll() method.
    }
}
