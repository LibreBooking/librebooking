<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'Pages/Ajax/IReservationSaveResultsView.php');
require_once(ROOT_DIR . 'Presenters/Reservation/ReservationPresenterFactory.php');

interface IReservationDeletePage extends IReservationSaveResultsView
{
    /**
     * @return string
     */
    public function GetReferenceNumber();

    /**
     * @return SeriesUpdateScope|string
     */
    public function GetSeriesUpdateScope();

    /**
     * @return string
     */
    public function GetReason();
}

class ReservationDeletePage extends SecurePage implements IReservationDeletePage
{
    /**
     * @var ReservationDeletePresenter
     */
    protected $presenter;

    /**
     * @var bool
     */
    protected $reservationSavedSuccessfully = false;

    public function __construct()
    {
        parent::__construct();

        $factory = new ReservationPresenterFactory();
        $this->presenter = $factory->Delete($this, ServiceLocator::GetServer()->GetUserSession());
    }

    public function PageLoad()
    {
        try {
            $this->EnforceCSRFCheck();
            $reservation = $this->presenter->BuildReservation();
            $this->presenter->HandleReservation($reservation);

            if ($this->reservationSavedSuccessfully) {
                $this->Display('Ajax/reservation/delete_successful.tpl');
            } else {
                $this->Display('Ajax/reservation/delete_failed.tpl');
            }
        } catch (Exception $ex) {
            Log::Error('ReservationDeletePage - Critical error saving reservation: %s', $ex);
            $this->Display('Ajax/reservation/reservation_error.tpl');
        }
    }

    public function SetSaveSuccessfulMessage($succeeded)
    {
        $this->reservationSavedSuccessfully = $succeeded;
    }

    public function SetErrors($errors)
    {
        $this->Set('Errors', $errors);
    }

    public function SetWarnings($warnings)
    {
        // set warnings variable
    }

    public function GetReferenceNumber()
    {
        return $this->GetForm(FormKeys::REFERENCE_NUMBER);
    }

    public function GetSeriesUpdateScope()
    {
        return $this->GetForm(FormKeys::SERIES_UPDATE_SCOPE);
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
        // no-op
        return [];
    }

    public function SetCanJoinWaitList($canJoinWaitlist)
    {
        // no-op
    }

    public function GetReason()
    {
        return $this->GetForm(FormKeys::DELETE_REASON);
    }
}

class ReservationDeleteJsonPage extends ReservationDeletePage implements IReservationDeletePage
{
    public function __construct()
    {
        parent::__construct();
    }

    public function PageLoad()
    {
        $reservation = $this->presenter->BuildReservation();
        $this->presenter->HandleReservation($reservation);
    }

    /**
     * @param bool $succeeded
     */
    public function SetSaveSuccessfulMessage($succeeded)
    {
        if ($succeeded) {
            $this->SetJson(['deleted' => (string)$succeeded]);
        }
    }

    public function SetErrors($errors)
    {
        if (!empty($errors)) {
            $this->SetJson(['deleted' => (string)false], $errors);
        }
    }

    public function SetWarnings($warnings)
    {
        // no-op
    }

    /**
     * @param array|string[] $messages
     */
    public function SetRetryMessages($messages)
    {
        // no-op
    }

    /**
     * @param bool $canBeRetried
     */
    public function SetCanBeRetried($canBeRetried)
    {
        // no-op
    }

    /**
     * @param ReservationRetryParameter[] $retryParameters
     */
    public function SetRetryParameters($retryParameters)
    {
        // no-op
    }

    /**
     * @return ReservationRetryParameter[]
     */
    public function GetRetryParameters()
    {
        // no-op
        return [];
    }
}
