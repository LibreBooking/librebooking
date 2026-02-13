<?php

class ResourceMaximumDurationRule implements IReservationValidationRule
{
    /**
     * @see IReservationValidationRule::Validate()
     *
     * @param ReservationSeries $reservationSeries
     * @param $retryParameters
     * @return ReservationRuleResult
     * @throws Exception
     */
    public function Validate($reservationSeries, $retryParameters)
    {
        $r = Resources::GetInstance();

        $resources = $reservationSeries->AllResources();

        foreach ($resources as $resource) {
            if ($resource->HasMaxLength()) {
                $maxDuration = $resource->GetMaxLength()->Interval();
                $start = $reservationSeries->CurrentInstance()->StartDate();
                $end = $reservationSeries->CurrentInstance()->EndDate();

                $maxEnd = $start->ApplyDifference($maxDuration);
                if ($end->GreaterThan($maxEnd)) {
                    return new ReservationRuleResult(false, $r->GetString('MaxDurationError', $maxDuration));
                }
            }
        }

        return new ReservationRuleResult();
    }
}
