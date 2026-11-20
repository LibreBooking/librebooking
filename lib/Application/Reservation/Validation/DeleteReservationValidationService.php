<?php

require_once(ROOT_DIR . 'lib/Application/Reservation/Notification/IReservationNotificationService.php');

class DeleteReservationValidationService implements IReservationValidationService
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
