<?php

class FakeReservationConflictIdentifier implements IReservationConflictIdentifier
{
    /**
     * @var ReservationConflictResult
     */
    public $_Conflicts = null;

    /**
     * @var array|ReservationConflictResult[]
     */
    public $_IndexedConflicts = [];

    private $_GetConflictCall = 0;
    /**
     * @var ReservationSeries[]
     */
    public $_Series;

    public function GetConflicts($reservationSeries)
    {
        $this->_Series[] = $reservationSeries;
        if (!empty($this->_IndexedConflicts)) {
            return $this->_IndexedConflicts[$this->_GetConflictCall++];
        }
        return $this->_Conflicts;
    }
}

class FakeReservationConflictResult extends ReservationConflictResult
{
    public $_AllowReservation = true;

    public function __construct($allowReservation = true)
    {
        $this->_AllowReservation = $allowReservation;
        parent::__construct([], 0, false, 1);
    }

    public function AllowReservation($numberOfConflictsSkipped = 0)
    {
        return $this->_AllowReservation;
    }
}
