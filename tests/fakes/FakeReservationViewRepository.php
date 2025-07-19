<?php

class FakeReservationViewRepository implements IReservationViewRepository
{
    public $_ReservationView;

    /**
     * @var ReservationItemView[]
     */
    public $_Reservations = [];

    /**
     * @var BlackoutItemView[]
     */
    public $_Blackouts = [];

    public $_LastRange;

    /**
     * @var AccessoryReservation[]
     */
    public $_AccessoryReservations = [];

    public $_Filter;

    /**
     * @var PageableData
     */
    public $_FilterResults;

    /**
     * @var int
     */
    public $_LastScheduleIds;

    /**
     * @var int
     */
    public $_LastResourceIds;

    /**
     * @var ReservationItemView[]
     */
    public $_ReservationsIteration = [];

    private $_Iteration = 0;

    public function __construct()
    {
        $this->_ReservationView = new ReservationView();
        $this->_FilterResults = new PageableData();
    }

    public function GetReservationsPendingApproval(Date $startDate, $userIds = ReservationViewRepository::ALL_USERS, $userLevel = ReservationUserLevel::OWNER, $scheduleIds = ReservationViewRepository::ALL_SCHEDULES, $resourceIds = ReservationViewRepository::ALL_RESOURCES, $consolidateByReferenceNumber = false, $participantIds = ReservationViewRepository::ALL_USERS)
    {
        return null;
    }

    public function GetReservationsMissingCheckInCheckOut(?Date $startDate, Date $endDate, $userIds = ReservationViewRepository::ALL_USERS, $userLevel = ReservationUserLevel::OWNER, $scheduleIds = ReservationViewRepository::ALL_SCHEDULES, $resourceIds = ReservationViewRepository::ALL_RESOURCES, $consolidateByReferenceNumber = false, $participantIds = ReservationViewRepository::ALL_USERS)
    {
        return null;
    }

    public function GetReservationForEditing($referenceNumber)
    {
        return $this->_ReservationView;
    }

    public function GetReservations(
        Date $startDate,
        Date $endDate,
        $userIds = ReservationViewRepository::ALL_USERS,
        $userLevel = ReservationUserLevel::OWNER,
        $scheduleIds = ReservationViewRepository::ALL_SCHEDULES,
        $resourceIds = ReservationViewRepository::ALL_RESOURCES,
        $consolidateByReferenceNumber = false,
        $participantIds = ReservationViewRepository::ALL_USERS
    ) {
        $this->_LastScheduleIds = $scheduleIds;
        $this->_LastResourceIds = $resourceIds;
        $this->_LastRange = new DateRange($startDate, $endDate);

        if (!empty($this->_ReservationsIteration)) {
            return $this->_ReservationsIteration[$this->_Iteration++];
        }

        return $this->_Reservations;
    }

    public function GetAccessoryReservationList(Date $startDate, Date $endDate, $accessoryName)
    {
        return [];
    }

    public function GetList($pageNumber, $pageSize, $sortField = null, $sortDirection = null, $filter = null)
    {
        $this->_Filter = $filter;
        return $this->_FilterResults;
    }

    public function GetBlackoutsWithin(DateRange $dateRange, $scheduleId = ReservationViewRepository::ALL_SCHEDULES, $resourceId = ReservationViewRepository::ALL_RESOURCES)
    {
        return $this->_Blackouts;
    }

    public function GetBlackoutList($pageNumber, $pageSize, $sortField = null, $sortDirection = null, $filter = null)
    {
        return new PageableData();
    }

    public function GetAccessoriesWithin(DateRange $dateRange)
    {
        return $this->_AccessoryReservations;
    }
}
