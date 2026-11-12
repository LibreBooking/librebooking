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
                // Every failure needs a message: some pages only respond when
                // there are errors to report
                $errorMessage = $result->ErrorMessage() ?? Resources::GetInstance()->GetString('ReservationFailed');
                $retryMessage = $result->RetryMessage();

                return new ReservationValidationResult(
                    canBeSaved: false,
                    errors: [$errorMessage],
                    warnings: [],
                    canBeRetried: $result->CanBeRetried(),
                    retryParams: $result->RetryParameters(),
                    retryMessages: $retryMessage === null ? [] : [$retryMessage],
                    canJoinWaitList: $result->CanJoinWaitlist()
                );
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
