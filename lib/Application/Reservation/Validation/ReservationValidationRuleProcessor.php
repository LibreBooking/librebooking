<?php

class ReservationValidationRuleProcessor implements IReservationValidationService
{
    /**
     * @var array|IReservationValidationRule[]
     */
    private $_validationRules = [];

    public function __construct($validationRules)
    {
        $this->_validationRules = $validationRules;
    }

    public function Validate($reservationSeries, $retryParameters = null)
    {
        /** @var IReservationValidationRule $rule */
        foreach ($this->_validationRules as $rule) {
            $result = $rule->Validate($reservationSeries, $retryParameters);
            Log::Debug('Validating rule %s. Passed?: %s', get_class($rule), $result->IsValid() . '');

            if (!$result->IsValid()) {
                return new ReservationValidationResult(false, [$result->ErrorMessage()], [], $result->CanBeRetried(), $result->RetryParameters(), [$result->RetryMessage()], $result->CanJoinWaitlist());
            }
        }

        return new ReservationValidationResult();
    }

    public function AddRule($validationRule)
    {
        $this->_validationRules[] = $validationRule;
    }

    public function PushRule($validationRule)
    {
        array_unshift($this->_validationRules, $validationRule);
    }
}
