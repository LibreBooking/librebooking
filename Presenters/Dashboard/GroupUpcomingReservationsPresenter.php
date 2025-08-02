<?php

require_once(ROOT_DIR . 'Controls/Dashboard/UpcomingReservations.php');

class GroupUpcomingReservationsPresenter
{
    /**
     * @var IUpcomingReservationsControl
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

    public function __construct(IUpcomingReservationsControl $control, IReservationViewRepository $repository)
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
        $lastDate = $now->AddDays(13-$dayOfWeek-1);

        $consolidated = [];
        $resourceIds = $this->GetUserAdminResources($user->UserId);

        if($resourceIds != null){
            $consolidated = $this->repository->GetReservations($now, $lastDate, $this->searchUserId, $this->searchUserLevel, null, $resourceIds,true);
        } 

        $tomorrow = $today->AddDays(1);
        $startOfNextWeek = $today->AddDays(7-$dayOfWeek);

        $todays = [];
        $tomorrows = [];
        $thisWeeks = [];
        $nextWeeks = [];

        if ($consolidated != null){
            foreach ($consolidated as $reservation) {
                $start = $reservation->StartDate->ToTimezone($timezone);                

                if ($start->DateEquals($today)) {
                    $todays[] = $reservation;
                } elseif ($start->DateEquals($tomorrow)) {
                    $tomorrows[] = $reservation;
                } elseif ($start->LessThan($startOfNextWeek)) {
                    $thisWeeks[] = $reservation;
                } else {
                    $nextWeeks[] = $reservation;
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
            $this->control->BindTomorrow($tomorrows);
            $this->control->BindThisWeek($thisWeeks);
            $this->control->BindNextWeek($nextWeeks);
        }
    }

    /**
     * Gets the resource ids that are under the responsability of the given resource user groups
     */
    private function GetUserAdminResources($userId){
        $resourceIds = [];

        $resourceRepo = new ResourceRepository();

        if (ServiceLocator::GetServer()->GetUserSession()->IsResourceAdmin){    
            $resourceIds = $resourceRepo->GetResourceAdminResourceIds($userId);
        }

        if (ServiceLocator::GetServer()->GetUserSession()->IsScheduleAdmin){
            $resourceIds = $resourceRepo->GetScheduleAdminResourceIds($userId, $resourceIds);
        }

        return $resourceIds;
    }
}
