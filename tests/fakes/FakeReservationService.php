<?php

require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

class FakeReservationService implements IReservationService
{
    /**
     * @var FakeReservationListing
     */
    public $_ReservationListing;

    /**
     * @var DateRange
     */
    public $_LastDateRange;

    /**
     * @var int
     */
    public $_LastScheduleId;

    /**
     * @var string
     */
    public $_LastTimezone;

    /**
     * @var int
     */
    public $_LastResourceId;

    /**
     * @var ExistingReservationSeries
     */
    public $_Reservation;
    /**
     * @var array|ReservationListItem[]
     */
    public $_ReservationsAndBlackouts = [];

    public function __construct()
    {
        $this->_ReservationListing = new FakeReservationListing();
    }

    /**
     * @param DateRange $dateRangeUtc range of dates to search against in UTC
     * @param int $scheduleId
     * @param string $targetTimezone timezone to convert the results to
     * @param null|int $resourceIds
     * @return IReservationListing
     */
    public function GetReservations(DateRange $dateRangeUtc, $scheduleId, $targetTimezone, $resourceIds = null)
    {
        $this->_LastDateRange = $dateRangeUtc;
        $this->_LastScheduleId = $scheduleId;
        $this->_LastTimezone = $targetTimezone;
        $this->_LastResourceId = $resourceIds;

        return $this->_ReservationListing;
    }

    public function Search(DateRange $dateRange, $scheduleId, $resourceIds = null, $ownerId = null, $participantId = null)
    {
        $this->_LastDateRange = $dateRange;
        return $this->_ReservationsAndBlackouts;
    }
}

class FakeReservationListing implements IReservationListing
{
    /**
     * @var array|ReservationListItem[]

     */
    public $_Reservations = [];

    /**
     * @return int
     */
    public function Count()
    {
        return count($this->_Reservations);
    }

    /**
     * @return array|ReservationListItem[]
     */
    public function Reservations()
    {
        return $this->_Reservations;
    }

    /**
     * @param Date $date
     * @return IReservationListing
     */
    public function OnDate($date)
    {
        return $this;
    }

    /**
     * @param int $resourceId
     * @return IReservationListing
     */
    public function ForResource($resourceId)
    {
        return $this;
    }

    /**
     * @param Date $date
     * @param int $resourceId
     * @return array|ReservationListItem[]
     */
    public function OnDateForResource(Date $date, $resourceId)
    {
        return $this->_Reservations;
    }
}
