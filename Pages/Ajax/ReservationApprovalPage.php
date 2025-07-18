<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'Pages/Ajax/IReservationSaveResultsView.php');
require_once(ROOT_DIR . 'Presenters/Reservation/ReservationApprovalPresenter.php');

interface IReservationApprovalPage extends IReservationSaveResultsView
{
    /**
     * @return string
     */
    public function GetReferenceNumber();
}

class ReservationApprovalPage extends SecurePage implements IReservationApprovalPage
{
    public function PageLoad()
    {
        try {
            $this->EnforceCSRFCheck();
            $reservationAction = ReservationAction::Approve;
            $factory = new ReservationPersistenceFactory();
            $persistenceService = $factory->Create($reservationAction);
            $handler = ReservationHandler::Create(
                $reservationAction,
                $persistenceService,
                ServiceLocator::GetServer()->GetUserSession()
            );
            $auth = new ReservationAuthorization(PluginManager::Instance()->LoadAuthorization());

            $presenter = new ReservationApprovalPresenter($this, $persistenceService, $handler, $auth, ServiceLocator::GetServer()->GetUserSession());
            $presenter->PageLoad();
        } catch (Exception $ex) {
            Log::Error('ReservationApprovalPage - Critical error saving reservation: %s', $ex);
            $this->Display('Ajax/reservation/reservation_error.tpl');
        }
    }

    /**
     * @return string
     */
    public function GetReferenceNumber()
    {
        return $this->GetQuerystring(QueryStringKeys::REFERENCE_NUMBER);
    }

    /**
     * @param bool $succeeded
     */
    public function SetSaveSuccessfulMessage($succeeded)
    {
        if ($succeeded) {
            $this->SetJson(['approved' => "$succeeded"]);
        }
    }

    public function SetErrors($errors)
    {
        if (!empty($errors)) {
            $this->SetJson(['approved' => "false"], $errors);
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

    /**
     * @param bool $canJoinWaitlist
     */
    public function SetCanJoinWaitList($canJoinWaitlist)
    {
        // no-op
    }
}
