<?php

require_once(ROOT_DIR . 'Controls/Dashboard/PastReservations.php');

class PastReservationsPresenter
{
    /**
     * @var IPastReservationsControl
     */
    private $control;

    /**
     * @var IReservationViewRepository
     */
    private $repository;

    /**
     * @var int
     */
    private $searchUserId = ReservationViewRepository::ALL_USERS;

    /**
     * @var int
     */
    private $searchUserLevel = ReservationUserLevel::ALL;

    public function __construct(IPastReservationsControl $control, IReservationViewRepository $repository)
    {
        $this->control = $control;
        $this->repository = $repository;
    }

    public function SetSearchCriteria($userId, $userLevel)
    {
        $this->searchUserId = $userId;
        $this->searchUserLevel = $userLevel;
    }

    public function PageLoad()
    {
        $user = ServiceLocator::GetServer()->GetUserSession();
        $timezone = $user->Timezone;

        $now = Date::Now();
        $today = $now->ToTimezone($timezone)->GetDate();
        $dayOfWeek = $today->Weekday();

        $firstDate = $now->AddDays(-13+(6-$dayOfWeek)+1);
        $consolidated = $this->repository->GetReservations($firstDate, $now, $this->searchUserId, $this->searchUserLevel, null, null, true);
        $yesterday = $today->AddDays(-1);

        $startOfPreviousWeek = $today->AddDays(-(7+$dayOfWeek));

        $todays = [];
        $yesterdays = [];
        $thisWeeks = [];
        $previousWeeks = [];

        //TimeLessThan($now->ToTimezone($timezone)->GetTime()) -> only when the reservations end does it display 
        //                                                       (so the reservation doesn't repeat here and in upcoming reservation)
        //!$start->DateEquals($today) -> today is always GreaterThan($startOfPreviousWeek->AddDays(7)) so if a reservation hasn't ended it won't appear on ($todays)
        //                              but it also can't show in any other section (can't just use an else) 
        foreach ($consolidated as $reservation) {
            $start = $reservation->EndDate->ToTimezone($timezone);

            //The reservation gets taken out of the array if it's still ocurring so it doesn't affect the number of reservations in the displayer
            //Ex: if we have one single past reservation that is happening it won't show on the display but next to the title it will still show 1 
            //(number of reservations in consolidated) and it won't show the message "You have no past reservations"
            //By doing this we solve the issue
            if ($start->DateEquals($today)) {
                if (!$start->TimeLessThan($now->ToTimezone($timezone)->GetTime())){
                    $remove = array_search($reservation, $consolidated);
                    unset($consolidated[$remove]);
                } else {
                    $todays[] = $reservation;
                }
            } elseif ($start->DateEquals($yesterday)) {
                $yesterdays[] = $reservation;
            } elseif ($start->GreaterThan($startOfPreviousWeek->AddDays(7)) && !$start->DateEquals($today)) {
                $thisWeeks[] = $reservation;
            } else if  (!$start->DateEquals($today)){
                $previousWeeks[] = $reservation;
            }
        }

        $checkinAdminOnly = Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_CHECKIN_ADMIN_ONLY, new BooleanConverter());
        $checkoutAdminOnly = Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_CHECKOUT_ADMIN_ONLY, new BooleanConverter());

        $allowCheckin = $user->IsAdmin || !$checkinAdminOnly;
        $allowCheckout = $user->IsAdmin || !$checkoutAdminOnly;

        $this->control->SetTotal(count($consolidated));
        $this->control->SetTimezone($timezone);
        $this->control->SetUserId($user->UserId);

        $this->control->SetAllowCheckin($allowCheckin);
        $this->control->SetAllowCheckout($allowCheckout);

        $this->control->BindToday($todays);
        $this->control->BindYesterday($yesterdays);
        $this->control->BindThisWeek($thisWeeks);
        $this->control->BindPreviousWeek($previousWeeks);
    }
}
