<?php

class ReservationCanBeCheckedInRule implements IReservationValidationRule
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
     * @param ExistingReservationSeries $reservationSeries
     * @param null|ReservationRetryParameter[] $retryParameters
     * @return ReservationRuleResult
     */
    public function Validate($reservationSeries, $retryParameters)
    {
        $isOk = true;
        $atLeastOneReservationRequiresCheckIn = false;
        $checkinMinutes = Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_CHECKIN_MINUTES_PRIOR, new IntConverter());
        $checkinAdminOnly = Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_CHECKIN_ADMIN_ONLY, new BooleanConverter());

        $reservation = $reservationSeries->CurrentInstance();
        $tooEarly = Date::Now()->LessThan($reservation->StartDate()->AddMinutes(-$checkinMinutes));
        $tooLate = Date::Now()->GreaterThanOrEqual($reservation->EndDate());
        $isAdministrator = $this->userSession->IsAdmin;

        if ($checkinAdminOnly && !$isAdministrator) {
            $isOk = false;
        }

        foreach ($reservationSeries->AllResources() as $resource) {
            if ($resource->IsCheckInEnabled()) {
                $atLeastOneReservationRequiresCheckIn = true;
            }
            $pastCheckinTime = $this->PastCheckinTime($resource, $reservationSeries);
            if ($pastCheckinTime || $tooEarly || $tooLate) {
                Log::Debug('Reservation %s cannot be checked in to. Past checkin time: %s, Too early: %s, Past end: %s', $reservation->ReferenceNumber(), $pastCheckinTime, $tooEarly, $tooLate);
                $isOk = false;
                break;
            }
        }

        return new ReservationRuleResult($isOk && $atLeastOneReservationRequiresCheckIn, Resources::GetInstance()->GetString('ReservationCannotBeCheckedInTo'));
    }

    private function PastCheckinTime(BookableResource $resource, ExistingReservationSeries $reservationSeries)
    {
        if (!$resource->IsAutoReleased() || !$resource->IsCheckInEnabled()) {
            return false;
        }

        $latestCheckin = $reservationSeries->CurrentInstance()->StartDate()->AddMinutes($resource->GetAutoReleaseMinutes());

        return Date::Now()->GreaterThan($latestCheckin);
    }
}
