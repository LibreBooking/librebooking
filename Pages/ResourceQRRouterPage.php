<?php

require_once(ROOT_DIR . 'Pages/Page.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class ResourceQRRouterPage extends Page
{
    public function __construct()
    {
        parent::__construct();
    }

    public function PageLoad()
    {
        $resourceId = $this->GetQuerystring(QueryStringKeys::RESOURCE_ID);

        $referenceNumber = $this->GetOwnActiveCheckinReferenceNumber($resourceId);
        if (!empty($referenceNumber)) {
            $page = sprintf('%s/%s?%s=%s', Configuration::Instance()->GetScriptUrl(), Pages::RESERVATION, QueryStringKeys::REFERENCE_NUMBER, $referenceNumber);
        } else {
            $page = sprintf('%s/%s?%s=%s', Configuration::Instance()->GetScriptUrl(), Pages::RESERVATION, QueryStringKeys::RESOURCE_ID, $resourceId);
        }

        $this->Redirect($page);
    }

    private function GetOwnActiveCheckinReferenceNumber($resourceId): ?string
    {
        if (!$this->IsAuthenticated()) {
            return null;
        }

        $userId = $this->server->GetUserSession()->UserId;
        $repo = new ReservationViewRepository();
        /** @var ReservationItemView[] $reservations */
        $reservations = $repo->GetReservations(Date::Now(), Date::Now(), $userId, ReservationUserLevel::OWNER, null, $resourceId);

        foreach ($reservations as $reservation) {
            if ($reservation->StartDate->LessThanOrEqual(Date::Now())
              && $reservation->EndDate->GreaterThanOrEqual(Date::Now())
              && $reservation->RequiresCheckin()) {
                return $reservation->ReferenceNumber;
            }
        }

        return null;
    }
}
