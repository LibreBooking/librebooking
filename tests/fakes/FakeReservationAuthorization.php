<?php

declare(strict_types=1);

class FakeReservationAuthorization implements IReservationAuthorization
{
    public $_CanChangeUsers = true;
    public $_CanEdit = true;
    public $_CanApprove = true;
    public $_CanViewDetails = true;

    /**
     * @param UserSession $currentUser
     * @return bool
     */
    public function CanChangeUsers(UserSession $currentUser)
    {
        return $this->_CanChangeUsers;
    }

    /**
     * @param ReservationView $reservationView
     * @param UserSession $currentUser
     * @return bool
     */
    public function CanEdit(ReservationView $reservationView, UserSession $currentUser)
    {
        return $this->_CanEdit;
    }

    /**
     * @param ReservationView $reservationView
     * @param UserSession $currentUser
     * @return bool
     */
    public function CanApprove(ReservationView $reservationView, UserSession $currentUser)
    {
        return $this->_CanApprove;
    }

    /**
     * @param ReservationView $reservationView
     * @param UserSession $currentUser
     * @return bool
     */
    public function CanViewDetails(ReservationView $reservationView, UserSession $currentUser)
    {
        return $this->_CanViewDetails;
    }
}
