<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Reservation/PrivacyFilter.php');

class FakePrivacyFilter implements IPrivacyFilter
{
    public $_CanViewUser = true;
    public $_CanViewDetails = true;

    /**
     * @var UserSession
     */
    public $_LastViewUserUserSession;

    /**
     * @var ReservationView
     */
    public $_LastViewUserReservation;

    /**
     * @var UserSession
     */
    public $_LastViewDetailsUserSession;

    /**
     * @var ReservationView
     */
    public $_LastViewDetailsReservation;

    public function CanViewUser(UserSession $currentUser, $reservationView = null, $ownerId = null)
    {
        $this->_LastViewUserUserSession = $currentUser;
        $this->_LastViewUserReservation = $reservationView;

        return $this->_CanViewUser;
    }


    public function CanViewDetails(UserSession $currentUser, $reservationView = null, $ownerId = null)
    {
        $this->_LastViewDetailsUserSession = $currentUser;
        $this->_LastViewDetailsReservation = $reservationView;

        return $this->_CanViewDetails;
    }
}
