<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'Presenters/Reservation/ReservationPresenterFactory.php');
require_once(ROOT_DIR . 'Pages/Ajax/IReservationSaveResultsView.php');

interface IReservationWaitlistPage extends IReservationSaveResultsView
{
    /**
     * @return string
     */
    public function GetReferenceNumber();

    /**
     * @return string
     */
    public function GetAction();

    /**
     * @return int
     */
    public function GetUserId();

    /**
     * @return string
     */
    public function GetStartDate();

    /**
     * @return string
     */
    public function GetEndDate();

    /**
     * @return string
     */
    public function GetStartTime();

    /**
     * @return string
     */
    public function GetEndTime();

    /**
     * @return int
     */
    public function GetResourceId();
}


class ReservationWaitlistPage extends SecurePage implements IReservationWaitlistPage
{
    /**
     * @var ReservationWaitlistPresenter
     */
    private $_presenter;

    /**
     * @var bool
     */
    private $_reservationSavedSuccessfully;

    public function __construct()
    {
        parent::__construct();

        $factory = new ReservationPresenterFactory();
        $this->_presenter = $factory->JoinWaitlist($this, ServiceLocator::GetServer()->GetUserSession());
    }

    public function PageLoad()
    {
        try {
            $this->EnforceCSRFCheck();
            $this->_presenter->PageLoad();
            $this->Display('Ajax/reservation/waitlist_added.tpl');
        } catch (Exception $ex) {
            Log::Error('ReservationWaitlistPage - Critical error for reservation: %s', $ex);
            $this->Display('Ajax/reservation/reservation_error.tpl');
        }
    }

    public function GetReferenceNumber()
    {
        return $this->GetForm(FormKeys::REFERENCE_NUMBER);
    }

    public function GetAction()
    {
        return ReservationAction::WaitList;
    }

    /**
     * @param bool $succeeded
     */
    public function SetSaveSuccessfulMessage($succeeded)
    {
        $this->_reservationSavedSuccessfully = $succeeded;
    }

    /**
     * @param array|string[] $errors
     */
    public function SetErrors($errors)
    {
        // TODO: Implement SetErrors() method.
    }

    public function SetWarnings($warnings)
    {
        // no-op
    }

    public function SetRetryMessages($messages)
    {
        // no-op
    }

    public function SetCanBeRetried($canBeRetried)
    {
        // no-op
    }

    public function SetRetryParameters($retryParameters)
    {
        // no-op
    }

    public function GetRetryParameters()
    {
        return [];
    }

    /**
     * @param bool $canJoinWaitlist
     */
    public function SetCanJoinWaitList($canJoinWaitlist)
    {
        // no-op
    }

    public function GetUserId()
    {
        return (int)$this->GetForm(FormKeys::USER_ID);
    }

    public function GetStartDate()
    {
        return $this->GetForm(FormKeys::BEGIN_DATE);
    }

    public function GetEndDate()
    {
        return $this->GetForm(FormKeys::END_DATE);
    }

    public function GetStartTime()
    {
        return $this->GetForm(FormKeys::BEGIN_PERIOD);
    }

    public function GetEndTime()
    {
        return $this->GetForm(FormKeys::END_PERIOD);
    }

    public function GetResourceId()
    {
        return (int)$this->GetForm(FormKeys::RESOURCE_ID);
    }
}
