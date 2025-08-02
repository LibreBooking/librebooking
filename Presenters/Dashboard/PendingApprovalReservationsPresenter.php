<?php

require_once(ROOT_DIR . 'Controls/Dashboard/UpcomingReservations.php');

class PendingApprovalReservationsPresenter
{
    /**
     * @var IAditionalUpcomingReservationsFieldsControl
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

    public function __construct(IAditionalUpcomingReservationsFieldsControl $control, IReservationViewRepository $repository)
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
        $endOfMonth = $today->AddDays(DateDiff::getMonthRemainingDays($timezone)+1);
        $endOfYear = $today->AddDays(DateDiff::getYearRemainingDays($timezone)+1);

        $consolidated = [];

        if (ServiceLocator::GetServer()->GetUserSession()->IsAdmin){
            $consolidated = $this->repository->GetReservationsPendingApproval($now, $this->searchUserId, $this->searchUserLevel, null, null,true);
        }

        elseif (ServiceLocator::GetServer()->GetUserSession()->IsResourceAdmin){
            $groupResourceIds = $this->GetUserAdminResources($user->UserId);
            
            if($groupResourceIds != null){
                $consolidated = $this->repository->GetReservationsPendingApproval($now, $this->searchUserId, $this->searchUserLevel, null, $groupResourceIds,true);
            }
        }

        $tomorrow = $today->AddDays(1);
        $startOfNextWeek = $today->AddDays(7-$dayOfWeek);
        $endOfNextWeek = $startOfNextWeek->AddDays(7);

        $todays = [];
        $tomorrows = [];
        $thisWeeks = [];
        $nextWeeks = [];
        $thisMonths = [];
        $thisYears = [];
        $futures = [];

        foreach ($consolidated as $reservation) {
            $start = $reservation->StartDate->ToTimezone($timezone);
            
            if ($start->DateEquals($today)) {
                $todays[] = $reservation;
            } elseif ($start->DateEquals($tomorrow)) {
                $tomorrows[] = $reservation;
            } elseif ($start->LessThan($startOfNextWeek)) {
                $thisWeeks[] = $reservation;
            } elseif ($start->LessThan($endOfNextWeek)) {
                $nextWeeks[] = $reservation;
            }elseif ($start->LessThan($endOfMonth)) {
                $thisMonths[] = $reservation;
            } elseif ($start->LessThan($endOfYear)){
                $thisYears[] = $reservation;
            } else {
                $futures[] = $reservation;
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
        $this->control->BindThisMonth($thisMonths);
        $this->control->BindThisYear($thisYears);
        $this->control->BindRemaining($futures);
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

        //If a given reservation is pending approval a user who is only a schedule admin can't approve them, only a resource admin that manages the resource of that same reservation
        //However if this schedule admin is a resource admin (even if he does not manage the resource) and that resource is in the schedule he can approve (or reject)
        if (ServiceLocator::GetServer()->GetUserSession()->IsScheduleAdmin && ServiceLocator::GetServer()->GetUserSession()->IsResourceAdmin){
            $resourceIds = $resourceRepo->GetScheduleAdminResourceIds($userId, $resourceIds);
        }

        return $resourceIds;
    }
}

