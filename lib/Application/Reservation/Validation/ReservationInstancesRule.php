<?php

class ReservationInstancesRule implements IReservationValidationRule
{
    public function Validate($reservationSeries, $retryParameters)
    {
        if (count($reservationSeries->Instances()) === 0) {
            return new ReservationRuleResult(false, Resources::GetInstance()->GetString('StartIsInPast'));
        }

        return new ReservationRuleResult();
    }
}
