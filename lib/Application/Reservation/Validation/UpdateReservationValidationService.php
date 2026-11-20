<?php

class UpdateReservationValidationService implements IReservationValidationService
{
    /**
     * @var IReservationValidationService
     */
    private $ruleProcessor;

    /**
     * @param IReservationValidationService $ruleProcessor
     */
    public function __construct($ruleProcessor)
    {
        $this->ruleProcessor = $ruleProcessor;
    }

    public function Validate($series, $retryParameters = null)
    {
        return $this->ruleProcessor->Validate($series, $retryParameters);
    }
}
