<?php

class ResourceMaximumNoticeRule implements IReservationValidationRule
{
    /**
     * @var UserSession
     */
    private $userSession;

    public function __construct(UserSession $userSession)
    {
        $this->userSession = $userSession;
    }

    /**
     * @see IReservationValidationRule::Validate()
     *
     * @param ReservationSeries $reservationSeries
     * @param null|ReservationRetryParameter[] $retryParameters
     * @return ReservationRuleResult
     */
    public function Validate($reservationSeries, $retryParameters)
    {
        $r = Resources::GetInstance();

        $resources = $reservationSeries->AllResources();

        foreach ($resources as $resource) {
            if ($resource->HasMaxNotice()) {
                $maxEndDate = Date::Now()->ApplyDifference($resource->GetMaxNotice()->Interval());

                /* @var $instance Reservation */
                foreach ($this->GetInstances($reservationSeries) as $instance) {
                    if ($instance->EndDate()->GreaterThan($maxEndDate)) {
                        return new ReservationRuleResult(false, $r->GetString('MaxNoticeError', $maxEndDate->ToTimezone($this->userSession->Timezone)->Format($r->GeneralDateTimeFormat())));
                    }
                }
            }
        }

        return new ReservationRuleResult();
    }

    /**
     * @param ReservationSeries $reservationSeries
     * @return Reservation[]
     */
    protected function GetInstances($reservationSeries)
    {
        return $reservationSeries->Instances();
    }
}

class ResourceMaximumNoticeCurrentInstanceRule extends ResourceMaximumNoticeRule
{
    protected function GetInstances($reservationSeries)
    {
        return [$reservationSeries->CurrentInstance()];
    }
}
