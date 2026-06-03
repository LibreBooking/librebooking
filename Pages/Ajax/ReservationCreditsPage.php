<?php

require_once(ROOT_DIR . 'Presenters/Reservation/ReservationCreditsPresenter.php');

interface IReservationCreditsPage extends IRepeatOptionsComposite
{
    /**
     * @return int
     */
    public function GetUserId();

    /**
     * @return int
     */
    public function GetResourceId();

    /**
     * @return string
     */
    public function GetStartDate();

    /**
     * @return string
     */
    public function GetEndDate();

    /**
     * @return string
     */
    public function GetStartTime();

    /**
     * @return string
     */
    public function GetEndTime();

    /**
     * @return int[]
     */
    public function GetResources();

    /**
     * @return string
     */
    public function GetReferenceNumber();

    /**
     * @param int $creditsRequired
     * @param string $cost
     */
    public function SetCreditRequired($creditsRequired, $cost);
}

class ReservationCreditsPage extends Page implements IReservationCreditsPage
{
    /**
     * @var ReservationCreditsPresenter
     */
    private $presenter;

    public function __construct()
    {
        parent::__construct();

        $this->presenter = new ReservationCreditsPresenter(
            $this,
            new ReservationRepository(),
            new ScheduleRepository(),
            new ResourceRepository(),
            new PaymentRepository()
        );
    }

    public function PageLoad()
    {
        $this->EnforceCSRFCheck();
        $userSession = ServiceLocator::GetServer()->GetUserSession();
        $this->presenter->PageLoad($userSession);
    }

    public function GetUserId()
    {
        return (int)$this->GetForm(FormKeys::USER_ID);
    }

    public function GetResourceId()
    {
        return (int)$this->GetForm(FormKeys::RESOURCE_ID);
    }

    public function GetStartDate()
    {
        return $this->GetForm(FormKeys::BEGIN_DATE);
    }

    public function GetEndDate()
    {
        return $this->GetForm(FormKeys::END_DATE);
    }

    public function GetStartTime()
    {
        return $this->GetForm(FormKeys::BEGIN_PERIOD);
    }

    public function GetEndTime()
    {
        return $this->GetForm(FormKeys::END_PERIOD);
    }

    public function GetResources()
    {
        $resources = $this->GetForm(FormKeys::ADDITIONAL_RESOURCES);
        if (is_null($resources)) {
            return [];
        }

        if (!is_array($resources)) {
            $resources = [$resources];
        }

        $resourceIds = [];
        foreach ($resources as $resourceId) {
            if (is_scalar($resourceId)) {
                $resourceIds[] = (int)$resourceId;
            }
        }

        return $resourceIds;
    }

    public function GetRepeatType()
    {
        return $this->GetForm(FormKeys::REPEAT_OPTIONS);
    }

    public function GetRepeatInterval()
    {
        return $this->GetForm(FormKeys::REPEAT_EVERY);
    }

    public function GetRepeatWeekdays()
    {
        $days = [];

        $sun = $this->GetForm(FormKeys::REPEAT_SUNDAY);
        if (!empty($sun)) {
            $days[] = 0;
        }

        $mon = $this->GetForm(FormKeys::REPEAT_MONDAY);
        if (!empty($mon)) {
            $days[] = 1;
        }

        $tue = $this->GetForm(FormKeys::REPEAT_TUESDAY);
        if (!empty($tue)) {
            $days[] = 2;
        }

        $wed = $this->GetForm(FormKeys::REPEAT_WEDNESDAY);
        if (!empty($wed)) {
            $days[] = 3;
        }

        $thu = $this->GetForm(FormKeys::REPEAT_THURSDAY);
        if (!empty($thu)) {
            $days[] = 4;
        }

        $fri = $this->GetForm(FormKeys::REPEAT_FRIDAY);
        if (!empty($fri)) {
            $days[] = 5;
        }

        $sat = $this->GetForm(FormKeys::REPEAT_SATURDAY);
        if (!empty($sat)) {
            $days[] = 6;
        }

        return $days;
    }

    public function GetRepeatMonthlyType()
    {
        return $this->GetForm(FormKeys::REPEAT_MONTHLY_TYPE);
    }

    public function GetRepeatTerminationDate()
    {
        return $this->GetForm(FormKeys::END_REPEAT_DATE);
    }

    public function GetRepeatCustomDates()
    {
        $dates = $this->GetForm(FormKeys::REPEAT_CUSTOM_DATES);
        if (!is_array($dates) || empty($dates)) {
            return [];
        }

        return $dates;
    }

    public function GetReferenceNumber()
    {
        return $this->GetForm(FormKeys::REFERENCE_NUMBER);
    }

    public function SetCreditRequired($creditsRequired, $cost)
    {
        $this->SetJson(['creditsRequired' => $creditsRequired, 'cost' => $cost]);
    }
}
