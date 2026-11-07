<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'WebServices/Responses/ReservationsResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/ReservationResponse.php');

class ReservationsWebService
{
    /**
     * @var IRestServer
     */
    private $server;

    /**
     * @var IReservationViewRepository
     */
    private $reservationViewRepository;

    /**
     * @var IPrivacyFilter
     */
    private $privacyFilter;

    /**
     * @var IAttributeService
     */
    private $attributeService;

    private IReservationAuthorization $reservationAuthorization;

    private ?IResourceRepository $resourceRepository;

    public function __construct(
        IRestServer $server,
        IReservationViewRepository $reservationViewRepository,
        IPrivacyFilter $privacyFilter,
        IAttributeService $attributeService,
        IReservationAuthorization $reservationAuthorization,
        ?IResourceRepository $resourceRepository = null
    ) {
        $this->reservationAuthorization = $reservationAuthorization;
        $this->resourceRepository = $resourceRepository;
        $this->server = $server;
        $this->reservationViewRepository = $reservationViewRepository;
        $this->attributeService = $attributeService;
        $this->privacyFilter = $privacyFilter;
    }

    /**
     * @name GetReservations
     * @description Gets a list of reservations for the specified parameters.
     * Optional query string parameters: userId, resourceId, scheduleId, startDateTime, endDateTime.
     * If no dates are provided, reservations for the next two weeks will be returned.
     * If dates do not include the timezone offset, the timezone of the authenticated user will be assumed.
     * @response ReservationsResponse
     * @return void
     */
    public function GetReservations()
    {
        $startDate = $this->GetStartDate();
        $endDate = $this->GetEndDate();
        $userId = $this->GetUserId();
        $resourceId = $this->GetResourceId();
        $scheduleId = $this->GetScheduleId();

        Log::Debug('GetReservations called. userId=%s, startDate=%s, endDate=%s', $userId, $startDate, $endDate);

        $allReservations = $this->reservationViewRepository->GetReservations(
            $startDate,
            $endDate,
            $userId,
            null,
            $scheduleId,
            $resourceId
        );

        $userSession = $this->server->GetSession();
        if ($userSession->IsAdmin) {
            $reservations = $allReservations;
        } else {
            $allowedResourceIds = $this->GetResourceRepository()->GetUserResourceIdList();
            $reservations = [];
            foreach ($allReservations as $reservation) {
                if (in_array($reservation->ResourceId, $allowedResourceIds)) {
                    $reservations[] = $reservation;
                }
            }
        }
        $response = new ReservationsResponse($this->server, $reservations, $this->privacyFilter, $startDate, $endDate);
        $this->server->WriteResponse($response);
    }

    /**
     * @name GetReservation
     * @param string $referenceNumber
     * @description Loads a specific reservation by reference number
     * @response ReservationResponse
     * @return void
     */
    public function GetReservation($referenceNumber)
    {
        Log::Debug('GetReservation called. $referenceNumber=%s', $referenceNumber);

        $reservation = $this->reservationViewRepository->GetReservationForEditing($referenceNumber);

        if (!empty($reservation->ReferenceNumber) && !$this->CanViewReservation($reservation)) {
            // Respond as if the reservation does not exist so reference
            // numbers cannot be probed.
            Log::Debug('GetReservation denied. $referenceNumber=%s', $referenceNumber);
            $this->server->WriteResponse(RestResponse::NotFound(), RestResponse::NOT_FOUND_CODE);
            return;
        }

        if (!empty($reservation->ReferenceNumber)) {
            $attributes = $this->attributeService->GetByCategory(CustomAttributeCategory::RESERVATION);
            $response = new ReservationResponse(
                server: $this->server,
                reservation: $reservation,
                privacyFilter: $this->privacyFilter,
                reservationAuthorization: $this->reservationAuthorization,
                attributes: $attributes,
            );
            $this->server->WriteResponse($response);
        } else {
            $this->server->WriteResponse($response = RestResponse::NotFound(), RestResponse::NOT_FOUND_CODE);
        }
    }

    private function GetResourceRepository(): IResourceRepository
    {
        if ($this->resourceRepository === null) {
            $this->resourceRepository = new ResourceRepository();
        }
        return $this->resourceRepository;
    }

    /**
     * Mirrors the web reservation view (tpl/Reservation/view.tpl): admins,
     * the owner, participants, invitees, and users with access to the
     * primary resource or any additional resource may view the reservation.
     */
    private function CanViewReservation(ReservationView $reservation): bool
    {
        $userSession = $this->server->GetSession();
        if ($userSession->IsAdmin || $reservation->OwnerId == $userSession->UserId) {
            return true;
        }

        foreach ([$reservation->Participants, $reservation->Invitees] as $users) {
            foreach ($users as $user) {
                if ($user->UserId == $userSession->UserId) {
                    return true;
                }
            }
        }

        $reservationResourceIds = array_merge([$reservation->ResourceId], $reservation->AdditionalResourceIds);
        $allowedResourceIds = $this->GetResourceRepository()->GetUserResourceIdList();

        return count(array_intersect($reservationResourceIds, $allowedResourceIds)) > 0;
    }

    /**
     * @param int|null $userId
     * @param int|null $resourceId
     * @param int|null $scheduleId
     * @return bool
     */
    private function FilterProvided($userId, $resourceId, $scheduleId)
    {
        return !empty($userId) || !empty($resourceId) || !empty($scheduleId);
    }

    /**
     * @return Date
     */
    private function GetStartDate()
    {
        return $this->GetBaseDate(WebServiceQueryStringKeys::START_DATE_TIME);
    }

    /**
     * @return Date
     */
    private function GetEndDate()
    {
        return $this->GetBaseDate(WebServiceQueryStringKeys::END_DATE_TIME, 14);
    }

    /**
     * @param string $queryStringKey
     * @return Date
     */
    private function GetBaseDate($queryStringKey, $defaultNumberOfDays = 0)
    {
        $dateQueryString = $this->server->GetQueryString($queryStringKey);
        if (empty($dateQueryString)) {
            return Date::Now()->AddDays($defaultNumberOfDays);
        }

        return WebServiceDate::GetDate($dateQueryString, $this->server->GetSession());
    }

    /**
     * @return int|null
     */
    private function GetUserId()
    {
        $userIdValue = $this->server->GetQueryString(WebServiceQueryStringKeys::USER_ID);
        if (empty($userIdValue)) {
            return null;
        }

        return intval($userIdValue);
    }

    /**
     * @return int|null
     */
    private function GetResourceId()
    {
        $resourceIdValue = $this->server->GetQueryString(WebServiceQueryStringKeys::RESOURCE_ID);
        return empty($resourceIdValue) ? null : intval($resourceIdValue);
    }

    /**
     * @return int|null
     */
    private function GetScheduleId()
    {
        $scheduleIdValue = $this->server->GetQueryString(WebServiceQueryStringKeys::SCHEDULE_ID);
        return empty($scheduleIdValue) ? null : intval($scheduleIdValue);
    }
}
