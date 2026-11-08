<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class CalendarExportPresenter
{
    /**
     * @var ICalendarExportPage
     */
    private $page;

    /**
     * @var IReservationViewRepository
     */
    private $reservationViewRepository;

    /**
     * @var ICalendarExportValidator
     */
    private $validator;

    private IPrivacyFilter $privacyFilter;

    public function __construct(
        ICalendarExportPage $page,
        IReservationViewRepository $reservationViewRepository,
        ICalendarExportValidator $validator,
        IPrivacyFilter $privacyFilter
    ) {
        $this->page = $page;
        $this->reservationViewRepository = $reservationViewRepository;
        $this->validator = $validator;
        $this->privacyFilter = $privacyFilter;
    }

    public function PageLoad(UserSession $currentUser)
    {
        if (!$this->validator->IsValid()) {
            return;
        }

        $referenceNumber = $this->page->GetReferenceNumber();

        $reservations = [];
        if (!empty($referenceNumber)) {
            $res = $this->reservationViewRepository->GetReservationForEditing($referenceNumber);
            $item = ReservationItemView::FromReservationView($res);
            $reservations = [new iCalendarReservationView($item, $currentUser, $this->privacyFilter)];
        }

        $this->page->SetReservations($reservations);
    }
}
