<?php

require_once(ROOT_DIR . 'Pages/Ajax/ReservationSavePage.php');
require_once(ROOT_DIR . 'Presenters/Reservation/ReservationPresenterFactory.php');

interface IReservationUpdatePage extends IReservationSavePage
{
    /**
     * @return string
     */
    public function GetReferenceNumber();

    /**
     * @return string One of SeriesUpdateScope::ThisInstance, ::FullSeries, ::FutureInstances
     */
    public function GetSeriesUpdateScope();

    /*
     * @return array|int[]
     */
    public function GetRemovedAttachmentIds();
}

class ReservationUpdatePage extends ReservationSavePage implements IReservationUpdatePage
{
    /**
     * @var ReservationUpdatePresenter
     */
    private $_presenter;

    /**
     * @var bool
     */
    private $_reservationSavedSuccessfully = false;

    public function __construct()
    {
        parent::__construct();

        $factory = new ReservationPresenterFactory();
        $this->_presenter = $factory->Update($this, ServiceLocator::GetServer()->GetUserSession());
    }

    public function PageLoad()
    {
        try {
            $this->EnforceCSRFCheck();
            $reservation = $this->_presenter->BuildReservation();
            $this->_presenter->HandleReservation($reservation);

            if ($this->_reservationSavedSuccessfully) {
                $this->Set('Resources', $reservation->AllResources());
                $this->Set('Instances', $reservation->SortedInstances());
                $this->Set('Timezone', ServiceLocator::GetServer()->GetUserSession()->Timezone);
                $this->Display('Ajax/reservation/update_successful.tpl');
            } else {
                $this->Display('Ajax/reservation/save_failed.tpl');
            }
        } catch (Exception $ex) {
            Log::Error('ReservationUpdatePage - Critical error saving reservation: %s', $ex);
            $this->Display('Ajax/reservation/reservation_error.tpl');
        }
    }

    public function SetSaveSuccessfulMessage($succeeded)
    {
        $this->_reservationSavedSuccessfully = $succeeded;
    }

    public function SetReferenceNumber($referenceNumber)
    {
        $this->Set('ReferenceNumber', $referenceNumber);
    }

    public function SetErrors($errors)
    {
        $this->Set('Errors', $errors);
    }

    public function SetWarnings($warnings)
    {
        // set warnings variable
    }

    public function GetReservationId()
    {
        return $this->GetForm(FormKeys::RESERVATION_ID);
    }

    public function GetSeriesUpdateScope()
    {
        $scope = $this->GetForm(FormKeys::SERIES_UPDATE_SCOPE);

        // Missing/non-scalar input falls back to the existing safe default.
        if (!is_scalar($scope) || $scope === '') {
            return SeriesUpdateScope::FullSeries;
        }

        $scope = (string)$scope;
        if (!SeriesUpdateScope::IsValid($scope)) {
            throw new InvalidArgumentException('Invalid series update scope');
        }

        return $scope;
    }

    public function GetRemovedAttachmentIds()
    {
        $fileIds = $this->GetForm(FormKeys::REMOVED_FILE_IDS);
        if (is_array($fileIds)) {
            return array_keys($fileIds);
        }

        return [];
    }
}
