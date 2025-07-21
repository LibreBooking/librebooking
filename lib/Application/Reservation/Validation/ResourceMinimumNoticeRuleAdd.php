<?php

class ResourceMinimumNoticeRuleAdd implements IReservationValidationRule
{
    /**
     * @var UserSession
     */
    protected $userSession;

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
            if ($this->EnforceMinimumNotice($resource)) {
                $minStartDate = Date::Now()->ApplyDifference($this->GetMinimumNotice($resource)->Interval());

                /* @var Reservation $instance */
                foreach ($this->GetInstances($reservationSeries) as $instance) {
                    if ($this->ViolatesMinStartRule($instance, $minStartDate)) {
                        return new ReservationRuleResult(false, $r->GetString($this->GetErrorKey(), $minStartDate->ToTimezone($this->userSession->Timezone)->Format($r->GeneralDateTimeFormat())));
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

    /**
     * @param BookableResource $resource
     * @return bool
     */
    protected function EnforceMinimumNotice($resource)
    {
        return $resource->HasMinNoticeAdd();
    }

    /**
     * @param BookableResource $resource
     * @return TimeInterval
     */
    protected function GetMinimumNotice($resource)
    {
        return $resource->GetMinNoticeAdd();
    }

    /**
     * @return string
     */
    protected function GetErrorKey()
    {
        return 'MinNoticeError';
    }

    /**
     * @param Reservation $instance
     * @param Date $minStartDate
     * @return bool
     */
    protected function ViolatesMinStartRule($instance, $minStartDate)
    {
        return $instance->StartDate()->LessThan($minStartDate);
    }
}
