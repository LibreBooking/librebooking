<?php

require_once(ROOT_DIR . 'Presenters/UnavailableResourcesPresenter.php');

interface IAvailableResourcesPage
{
    public function GetStartDate();

    public function GetEndDate();

    public function GetStartTime();

    public function GetEndTime();

    public function GetReferenceNumber();

    /**
     * @param int[] $unavailableResourceIds
     */
    public function BindUnavailable($unavailableResourceIds);

    /**
     * @return int
     */
    public function GetScheduleId();
}

class UnavailableResourcesPage extends Page implements IAvailableResourcesPage
{
    public function __construct()
    {
        parent::__construct('', 1);
    }

    public function PageLoad()
    {
        $presenter = new UnavailableResourcesPresenter(
            $this,
            new ReservationConflictIdentifier(new ResourceAvailability(new ReservationViewRepository())),
            ServiceLocator::GetServer()->GetUserSession(),
            new ResourceRepository(),
            new ReservationRepository()
        );
        $presenter->PageLoad();
    }

    public function GetStartDate()
    {
        return $this->GetQuerystring(QueryStringKeys::START_DATE);
    }

    public function GetEndDate()
    {
        return $this->GetQuerystring(QueryStringKeys::END_DATE);
    }

    public function GetReferenceNumber()
    {
        return $this->GetQuerystring(QueryStringKeys::REFERENCE_NUMBER);
    }

    public function GetStartTime()
    {
        return $this->GetQuerystring(QueryStringKeys::START_TIME);
    }

    public function GetEndTime()
    {
        return $this->GetQuerystring(QueryStringKeys::END_TIME);
    }

    public function BindUnavailable($unavailableResourceIds)
    {
        $this->SetJson($unavailableResourceIds);
    }

    public function GetScheduleId()
    {
        return (int)$this->GetQuerystring(QueryStringKeys::SCHEDULE_ID);
    }
}
