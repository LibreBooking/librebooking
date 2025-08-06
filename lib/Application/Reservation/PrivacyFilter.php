<?php

require_once(ROOT_DIR . 'lib/Application/Reservation/ReservationAuthorization.php');

interface IPrivacyFilter
{
    /**
     * @param UserSession $currentUser
     * @param ReservationView|null $reservationView
     * @param int|null $ownerId
     * @return bool
     */
    public function CanViewUser(UserSession $currentUser, $reservationView = null, $ownerId = null);

    /**
     * @param UserSession $currentUser
     * @param ReservationView|null $reservationView
     * @param int|null $ownerId
     * @return bool
     */
    public function CanViewDetails(UserSession $currentUser, $reservationView = null, $ownerId = null);
}

class PrivacyFilter implements IPrivacyFilter
{
    private $cache = [];

    /**
     * @var IReservationAuthorization
     */
    private $reservationAuthorization;

    /**
     *
     * @param $reservationAuthorization IReservationAuthorization
     */
    public function __construct($reservationAuthorization = null)
    {
        $this->reservationAuthorization = $reservationAuthorization;
        if (is_null($this->reservationAuthorization)) {
            $this->reservationAuthorization = new ReservationAuthorization(PluginManager::Instance()->LoadAuthorization());
        }
    }

    public function CanViewUser(UserSession $currentUser, $reservationView = null, $ownerId = null)
    {
        $hideUserDetails = Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, new BooleanConverter());

        return $this->CanView($hideUserDetails, $currentUser, $ownerId, $reservationView);
    }

    public function CanViewDetails(UserSession $currentUser, $reservationView = null, $ownerId = null)
    {
        $hideReservationDetails = ReservationDetailsFilter::HideReservationDetails();

        if ($reservationView != null) {
            /** @var ReservationView $reservationView */
            $hideReservationDetails = ReservationDetailsFilter::HideReservationDetails($reservationView->StartDate, $reservationView->EndDate);
        }

        return $this->CanView($hideReservationDetails, $currentUser, $ownerId, $reservationView);
    }

    private function CanView($hideFlagEnabled, $userSession, $ownerId, $reservationView)
    {
        if (!$hideFlagEnabled || $userSession->IsAdmin) {
            return true;
        }

        if ($ownerId != null && $userSession->UserId == $ownerId) {
            return true;
        }

        if ($reservationView != null && is_a($reservationView, 'ReservationView')) {
            return $this->IsAuthorized($reservationView, $userSession);
        }

        return false;
    }

    /**
     * @param ReservationView $reservationView
     * @param UserSession $userSession
     * @return bool
     */
    private function IsAuthorized(ReservationView $reservationView, UserSession $userSession)
    {
        if (!$this->IsCached($reservationView, $userSession)) {
            $this->Cache(
                $reservationView,
                $userSession,
                $this->reservationAuthorization->CanViewDetails($reservationView, $userSession)
            );
        }

        return $this->GetCachedValue($reservationView, $userSession);
    }

    /**
     * @param ReservationView $reservationView
     * @param UserSession $userSession
     * @return bool
     */
    private function IsCached(ReservationView $reservationView, UserSession $userSession)
    {
        return array_key_exists($reservationView->ReferenceNumber . $userSession->UserId, $this->cache);
    }

    /**
     * @param ReservationView $reservationView
     * @param UserSession $userSession
     * @param bool $canView
     */
    private function Cache(ReservationView $reservationView, UserSession $userSession, $canView)
    {
        $this->cache[$reservationView->ReferenceNumber . $userSession->UserId] = $canView;
    }

    /**
     * @param ReservationView $reservationView
     * @param UserSession $userSession
     * @return bool
     */
    private function GetCachedValue(ReservationView $reservationView, UserSession $userSession)
    {
        return $this->cache[$reservationView->ReferenceNumber . $userSession->UserId];
    }
}

class NullPrivacyFilter implements IPrivacyFilter
{
    public function CanViewUser(UserSession $currentUser, $reservationView = null, $ownerId = null)
    {
        return true;
    }

    public function CanViewDetails(UserSession $currentUser, $reservationView = null, $ownerId = null)
    {
        return true;
    }
}
