<?php

class FakeScheduleService implements IScheduleService
{
    public $_DailyLayout;

    /**
     * @var Schedule[]
     */
    public $_AllSchedules = [];

    public $_Layout;


    public function __construct()
    {
        $this->_DailyLayout = new FakeDailyLayout();
    }

    /**
     * @param bool $includeInaccessible
     * @param UserSession $session
     * @return Schedule[]
     */
    public function GetAll($includeInaccessible = true, ?UserSession $session = null)
    {
        return $this->_AllSchedules;
    }

    /**
     * @param int $scheduleId
     * @param ILayoutFactory $layoutFactory factory to use to create the schedule layout
     * @return IScheduleLayout
     */
    public function GetLayout($scheduleId, ILayoutFactory $layoutFactory)
    {
        return $this->_Layout;
    }

    /**
     * @param int $scheduleId
     * @param ILayoutFactory $layoutFactory
     * @param IReservationListing $reservationListing
     * @return IDailyLayout
     */
    public function GetDailyLayout($scheduleId, ILayoutFactory $layoutFactory, $reservationListing)
    {
        return $this->_DailyLayout;
    }
}
