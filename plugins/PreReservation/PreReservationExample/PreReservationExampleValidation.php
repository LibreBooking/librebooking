<?php

class PreReservationExampleValidation implements IReservationValidationService
{
    /**
     * @var IReservationValidationService
     */
    private $serviceToDecorate;

    public function __construct(IReservationValidationService $serviceToDecorate)
    {
        $this->serviceToDecorate = $serviceToDecorate;
    }

    public function Validate($series, $retryParameters = null)
    {
        $result = $this->serviceToDecorate->Validate($series, $retryParameters);

        // don't bother validating this rule if others have failed
        if (!$result->CanBeSaved()) {
            return $result;
        }

        return $this->EvaluateCustomRule($series);
    }

    private function EvaluateCustomRule($series)
    {
        Log::Debug('Evaluating custom pre reservation rule');
        // make your custom checks here
        $configFile = Configuration::Instance()->File(PreReservationExampleConfigKeys::CONFIG_ID);
        $maxValue = $configFile->GetKey(PreReservationExampleConfigKeys::CUSTOM_ATTRIBUTE_MAX_VALUE);
        $customAttributeId = $configFile->GetKey(PreReservationExampleConfigKeys::CUSTOM_ATTRIBUTE_ID);

        $attributeValue = $series->GetAttributeValue($customAttributeId);

        $isValid = $attributeValue <= $maxValue;

        if ($isValid) {
            return new ReservationValidationResult();
        }

        return new ReservationValidationResult(false, "Value of custom attribute cannot be greater than $maxValue");
    }
}
