<?php

class ReservationDetailsFilter
{
    /**
     * @param Date|null $reservationStart
     * @param Date|null $reservationEnd
     * @return bool
     */
    public static function HideReservationDetails($reservationStart = null, $reservationEnd = null)
    {
        $hideReservationDetails = Configuration::Instance()->GetKey(
            ConfigKeys::PRIVACY_HIDE_RESERVATION_DETAILS,
            new LowerCaseConverter()
        );
        if ($hideReservationDetails == 'past' && $reservationEnd != null) {
            return $reservationEnd->LessThan(Date::Now());
        } elseif ($hideReservationDetails == 'future' && $reservationEnd != null) {
            return $reservationEnd->GreaterThan(Date::Now());
        } elseif ($hideReservationDetails == 'current' && $reservationStart != null) {
            return $reservationStart->LessThan(Date::Now());
        }

        $converter = new BooleanConverter();
        return $converter->Convert($hideReservationDetails);
    }

    /**
     * Public (not logged-in) users have no resource permission rows, so
     * they may open reservation details only when both settings allow it.
     * @return bool
     */
    public static function CanPublicViewDetails()
    {
        $converter = new BooleanConverter();
        $config = Configuration::Instance();

        return !ServiceLocator::GetServer()->GetUserSession()->IsLoggedIn()
            && $config->GetKey(ConfigKeys::PRIVACY_VIEW_RESERVATIONS, $converter)
            && $config->GetKey(ConfigKeys::PRIVACY_VIEW_PUBLIC_RESERVATION_DETAILS, $converter);
    }
}
