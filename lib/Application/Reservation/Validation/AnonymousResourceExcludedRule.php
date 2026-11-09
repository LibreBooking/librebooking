<?php

class AnonymousResourceExcludedRule implements IReservationValidationRule
{
    /**
     * @var IReservationValidationRule
     */
    private $rule;
    /**
     * @var UserSession
     */
    private $session;

    public function __construct(IReservationValidationRule $baseRule, UserSession $session)
    {
        $this->rule = $baseRule;
        $this->session = $session;
    }

    public function Validate($reservationSeries, $retryParameters)
    {
        if ($this->session->IsLoggedIn()) {
            return $this->rule->Validate($reservationSeries, $retryParameters);
        }

        foreach ($reservationSeries->AllResources() as $resource) {
            if ($resource->GetIsDisplayEnabled()) {
                return new ReservationRuleResult(true);
            }
        }

        return new ReservationRuleResult(false);
    }
}
