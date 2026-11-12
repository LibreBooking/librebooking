<?php

class ReservationRuleResult
{
    private $_isValid;
    private $_errorMessage;
    private $_canBeRetried;
    private $_retryMessage;
    private $_retryParameters;
    private $_canJoinWaitlist;

    /**
     * @param bool $isValid
     * @param string|null $errorMessage
     * @param bool $canBeRetried
     * @param string|null $retryMessage
     * @param array|ReservationRetryParameter[] $retryParams
     * @param bool $canJoinWaitlist
     */
    public function __construct($isValid = true, $errorMessage = null, $canBeRetried = false, $retryMessage = null, $retryParams = [], $canJoinWaitlist = false)
    {
        $this->_isValid = $isValid;
        $this->_errorMessage = $errorMessage;
        $this->_canBeRetried = $canBeRetried;
        $this->_retryMessage = $retryMessage;
        $this->_retryParameters = $retryParams;
        $this->_canJoinWaitlist = $canJoinWaitlist;
    }

    /**
     * @return bool
     */
    public function IsValid()
    {
        return $this->_isValid;
    }

    /**
     * @return string|null null for a valid result
     */
    public function ErrorMessage()
    {
        return $this->_errorMessage;
    }

    /**
     * @return bool
     */
    public function CanBeRetried()
    {
        return $this->_canBeRetried;
    }

    /**
     * @return string|null null when the reservation cannot be retried
     */
    public function RetryMessage()
    {
        return $this->_retryMessage;
    }

    public function RetryParameters()
    {
        return $this->_retryParameters;
    }

    public function CanJoinWaitlist()
    {
        return $this->_canJoinWaitlist;
    }
}
