<?php

require_once(ROOT_DIR . 'Pages/Ajax/ReservationAttributesPrintPage.php');

require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class ReservationAttributesPrintPresenter
{
    /**
     * @var IReservationAttributesPage
     */
    private $page;

    /**
     * @var IAttributeService
     */
    private $attributeService;

    /**
     * @var IAuthorizationService
     */
    private $authorizationService;

    /**
     * @var IPrivacyFilter
     */
    private $privacyFilter;

    /**
     * @var IReservationViewRepository
     */
    private $reservationViewRepository;

    public function __construct(
        IReservationAttributesPrintPage $page,
        IAttributeService $attributeService,
        IAuthorizationService $authorizationService,
        IPrivacyFilter $privacyFilter,
        IReservationViewRepository $reservationViewRepository
    ) {
        $this->page = $page;
        $this->attributeService = $attributeService;
        $this->authorizationService = $authorizationService;
        $this->privacyFilter = $privacyFilter;
        $this->reservationViewRepository = $reservationViewRepository;
    }

    public function PageLoad(UserSession $userSession)
    {
        $hideReservations = !Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_VIEW_RESERVATIONS, new BooleanConverter());
        $hideDetails = Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_HIDE_RESERVATION_DETAILS, new BooleanConverter());
        if (($hideReservations || $hideDetails) && !$userSession->IsLoggedIn()) {
            return;
        }
        $requestedUserId = $this->page->GetRequestedUserId();
        $requestedReferenceNumber = $this->page->GetRequestedReferenceNumber();
        $resourceIds = $this->page->GetRequestedResourceIds();

        $reservationView = new ReservationView();
        $canViewDetails = true;

        if (!empty($requestedReferenceNumber)) {
            $reservationView = $this->reservationViewRepository->GetReservationForEditing($requestedReferenceNumber);
            $canViewDetails = $this->privacyFilter->CanViewDetails($userSession, $reservationView, $requestedUserId);
        }

        $attributes = [];

        if ($canViewDetails) {
            $attributes = $this->attributeService->GetReservationAttributes($userSession, $reservationView, $requestedUserId, $resourceIds);
        }

        $this->page->SetAttributes($attributes);
    }

    /**
     * @param UserSession $userSession
     * @param $requestedUserId
     * @return bool
     */
    private function CanReserveFor(UserSession $userSession, $requestedUserId)
    {
        return $userSession->UserId == $requestedUserId || $this->authorizationService->CanReserveFor($userSession, $requestedUserId);
    }
}
