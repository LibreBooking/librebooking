<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class AdminCheckOutOnlyValidation implements IReservationValidationService
{
    /**
     * @var IReservationValidationService
     */
    private $serviceToDecorate;

    /**
     * @var UserSession
     */
    private $userSession;


    public function __construct(
        IReservationValidationService $serviceToDecorate,
        UserSession $userSession
    ) {
        $this->serviceToDecorate = $serviceToDecorate;
        $this->userSession = $userSession;
    }

    public function Validate($series, $retryParameters = null)
    {
        $result = $this->serviceToDecorate->Validate($series, $retryParameters);

        if (!$result->CanBeSaved()) {
            return $result;
        }



        return $this->EvaluateCustomRule($series);
    }

    private function EvaluateCustomRule($series)
    {
        $configFile = Configuration::Instance()->File(AdminCheckOnlyConfigKeys::CONFIG_ID); // Gets config file
        $customAttributeId = $configFile->GetKey(AdminCheckOnlyConfigKeys::ATTRIBUTE_CHECKOUT_ID); // Gets ID from AdminCheckOutOnly
        $resources = $series->AllResources();
        $adminChecks = 0; // Number of resources with AdminCheckOutOnly
        $userChecks = 0; // Number of resources without AdminCheckOutOnly

        foreach ($resources as $resource) { // Gets AdminCheckOutOnly from all attributes

            $attributeRepository = new AttributeRepository();
            $attributes = $attributeRepository->GetEntityValues(4, $resource->GetId());

            foreach ($attributes as $attribute) {
                if ($customAttributeId == $attribute->AttributeId) {
                    $adminCheckOnly = $attribute->Value; // Gets AdminCheckOutOnly's value

                    if ($adminCheckOnly) {
                        $adminChecks++;
                    } else {
                        $userChecks++;
                    }
                }
            }
        }
        $isAdmin = ($this->userSession->IsAdmin || $this->userSession->IsResourceAdmin);
        Log::Debug('Validating AdminCheckOutOnly resources, AdminChecks?:%s. UserChecks?:%s. Is Admin?:%s', $adminChecks, $userChecks, $isAdmin);

        //Changes message in case there is a conflict with other resources that don't have AdminCheckOnly
        if ($userChecks) {
            $customMessage = $configFile->GetKey(AdminCheckOnlyConfigKeys::MESSAGE_CHECKOUT_RESOURCE_CONFLICT);
        } else {
            $customMessage = $configFile->GetKey(AdminCheckOnlyConfigKeys::MESSAGE_CHECKOUT);
        }

        // If AdminCheckOutOnly is on and user doesn't have privileges,
        // validation fails, showing the custom message for the user
        if ($adminChecks && (!$isAdmin)) {
            return new ReservationValidationResult(false, $customMessage);
        }

        // Returns valid result if there's nothing wrong
        return new ReservationValidationResult();
    }
}
