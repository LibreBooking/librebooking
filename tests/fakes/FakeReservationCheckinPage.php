<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Ajax/ReservationCheckinPage.php');

class FakeReservationCheckinPage implements IReservationCheckinPage
{
    public $_ReferenceNumber;
    public $_Action;
    public $_RetryParameters = [];

    /**
     * @param bool $succeeded
     */
    public function SetSaveSuccessfulMessage($succeeded)
    {
        // TODO: Implement SetSaveSuccessfulMessage() method.
    }

    /**
     * @param array|string[] $errors
     */
    public function SetErrors($errors)
    {
        // TODO: Implement SetErrors() method.
    }

    /**
     * @param array|string[] $warnings
     */
    public function SetWarnings($warnings)
    {
        // TODO: Implement SetWarnings() method.
    }

    /**
     * @param array|string[] $messages
     */
    public function SetRetryMessages($messages)
    {
        // TODO: Implement SetRetryMessages() method.
    }

    /**
     * @param bool $canBeRetried
     */
    public function SetCanBeRetried($canBeRetried)
    {
        // TODO: Implement SetCanBeRetried() method.
    }

    /**
     * @param ReservationRetryParameter[] $retryParameters
     */
    public function SetRetryParameters($retryParameters)
    {
        $this->_RetryParameters = $retryParameters;
    }

    /**
     * @return ReservationRetryParameter[]
     */
    public function GetRetryParameters()
    {
        return $this->_RetryParameters;
    }

    /**
     * @return string
     */
    public function GetReferenceNumber()
    {
        return $this->_ReferenceNumber;
    }

    /**
     * @return string
     */
    public function GetAction()
    {
        return $this->_Action;
    }

    /**
     * @param bool $canJoinWaitlist
     */
    public function SetCanJoinWaitList($canJoinWaitlist)
    {
        // TODO: Implement SetCanJoinWaitList() method.
    }
}
