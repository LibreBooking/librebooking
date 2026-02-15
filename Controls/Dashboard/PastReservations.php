<?php

require_once(ROOT_DIR . 'Controls/Dashboard/DashboardItem.php');
require_once(ROOT_DIR . 'Presenters/Dashboard/PastReservationsPresenter.php');
require_once(ROOT_DIR . 'Presenters/Dashboard/MissingCheckInOutReservationsPresenter.php');
require_once(ROOT_DIR . 'Domain/Access/ReservationViewRepository.php');

class PastReservations extends DashboardItem implements IPastReservationsControl
{
    /**
     * @var PastReservationsPresenter|MissingCheckInOutReservationsPresenter
     */
    protected $presenter;

    public function __construct(SmartyPage $smarty)
    {
        parent::__construct($smarty);
        $this->presenter = new PastReservationsPresenter($this, new ReservationViewRepository());
    }

    public function PageLoad()
    {
        $this->Set('DefaultTitle', Resources::GetInstance()->GetString('NoTitleLabel'));
        $this->presenter->SetSearchCriteria(ServiceLocator::GetServer()->GetUserSession()->UserId, ReservationUserLevel::ALL);
        $this->presenter->PageLoad();
        $this->Display('past_reservations.tpl');
    }

    public function SetTimezone($timezone)
    {
        $this->Set('Timezone', $timezone);
    }

    public function SetTotal($total)
    {
        $this->Set('Total', $total);
    }

    public function SetUserId($userId)
    {
        $this->Set('UserId', $userId);
    }

    public function BindToday($reservations)
    {
        $this->Set('TodaysReservations', $reservations);
    }

    public function BindYesterday($reservations)
    {
        $this->Set('YesterdayReservations', $reservations);
    }

    public function BindThisWeek($reservations)
    {
        $this->Set('ThisWeeksReservations', $reservations);
    }

    public function BindPreviousWeek($reservations)
    {
        $this->Set('PreviousWeekReservations', $reservations);
    }

    public function SetAllowCheckin($allowCheckin)
    {
        $this->Set('allowCheckin', $allowCheckin);
    }

    public function SetAllowCheckout($allowCheckout)
    {
        $this->Set('allowCheckout', $allowCheckout);
    }
}

interface IPastReservationsControl
{
    public function SetTimezone($timezone);
    public function SetTotal($total);
    public function SetUserId($userId);

    public function SetAllowCheckin($allowCheckin);
    public function SetAllowCheckout($allowCheckout);

    public function BindToday($reservations);
    public function BindYesterday($reservations);
    public function BindThisWeek($reservations);
    public function BindPreviousWeek($reservations);
}

interface IRemainingPastReservationsControl extends IPastReservationsControl
{
    public function BindRemaining($reservations);
}

class AllPastReservations extends PastReservations
{
    public function PageLoad()
    {
        $this->Set('DefaultTitle', Resources::GetInstance()->GetString('NoTitleLabel'));
        $this->presenter->SetSearchCriteria(ReservationViewRepository::ALL_USERS, ReservationUserLevel::ALL);
        $this->presenter->PageLoad();
        $this->Display('admin_upcoming_reservations.tpl');
    }
}

class MissingCheckInOutReservations extends PastReservations implements IRemainingPastReservationsControl
{
    /**
     * @var MissingCheckInOutReservationsPresenter
     */
    protected $presenter;

    public function __construct(SmartyPage $smarty)
    {
        parent::__construct($smarty);
        $this->presenter = new MissingCheckInOutReservationsPresenter($this, new ReservationViewRepository());
    }

    public function PageLoad()
    {
        $this->Set('DefaultTitle', Resources::GetInstance()->GetString('NoTitleLabel'));
        $this->presenter->SetSearchCriteria(ReservationViewRepository::ALL_USERS, ReservationUserLevel::ALL);
        $this->presenter->PageLoad();
        $this->Display('missing_check_in_out_reservations.tpl');
    }

    public function BindRemaining($reservations)
    {
        $this->Set('RemainingReservations', $reservations);
    }
}
