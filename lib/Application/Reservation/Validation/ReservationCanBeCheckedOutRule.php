<?php

class ReservationCanBeCheckedOutRule implements IReservationValidationRule
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
        $checkoutAdminOnly = Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_CHECKOUT_ADMIN_ONLY, new BooleanConverter());
        $tooEarly = Date::Now()->LessThan($reservationSeries->CurrentInstance()->StartDate());
        $isAdministrator = $this->userSession->IsAdmin;

        if ($checkoutAdminOnly && !$isAdministrator) {
            $isOk = false;
        }

        foreach ($reservationSeries->AllResources() as $resource) {
            if ($resource->IsCheckInEnabled()) {
                $atLeastOneReservationRequiresCheckIn = true;
            }

            if ($tooEarly || !$reservationSeries->CurrentInstance()->IsCheckedIn()) {
                $isOk = false;
                break;
            }
        }

        return new ReservationRuleResult($isOk && $atLeastOneReservationRequiresCheckIn, Resources::GetInstance()->GetString('ReservationCannotBeCheckedOutFrom'));
    }
}
