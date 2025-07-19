<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class FakeReservationWaitlistRepository implements IReservationWaitlistRepository
{
    /**
     * @var ReservationWaitlistRequest
     */
    public $_AddedWaitlistRequest;

    public $_LastAddedId = 120;

    /**
     * @var ReservationWaitlistRequest[]
     */
    public $_AllRequests = [];

    /**
     * @param ReservationWaitlistRequest $request
     * @return int
     */
    public function Add(ReservationWaitlistRequest $request)
    {
        $this->_AddedWaitlistRequest = $request;
        return $this->_LastAddedId;
    }

    /**
     * @return ReservationWaitlistRequest[]
     */
    public function GetAll()
    {
        return $this->_AllRequests;
    }

    /**
     * @param int $waitlistId
     * @return ReservationWaitlistRequest
     */
    public function LoadById($waitlistId)
    {
        // TODO: Implement LoadById() method.
        return null;
    }

    /**
     * @param ReservationWaitlistRequest $request
     */
    public function Delete(ReservationWaitlistRequest $request)
    {
        // TODO: Implement Delete() method.
    }
}
