<?php

require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');

class CurrentUserCanReserveForUserRule implements IReservationValidationRule
{
    /**
     * @var UserSession
     */
    private $userSession;

    /**
     * @var IAuthorizationService
     */
    private $authorizationService;

    public function __construct(UserSession $userSession, IAuthorizationService $authorizationService)
    {
        $this->userSession = $userSession;
        $this->authorizationService = $authorizationService;
    }

    public function Validate($reservationSeries, $retryParameters)
    {
        $reservationUserId = $reservationSeries->UserId();
        $isAllowed = $this->userSession->UserId == $reservationUserId
            || $this->authorizationService->CanReserveFor($this->userSession, $reservationUserId);

        return new ReservationRuleResult($isAllowed, Resources::GetInstance()->GetString('InvalidReservationData'));
    }
}
