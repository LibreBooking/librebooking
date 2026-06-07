<?php

require_once(dirname(__FILE__) . '/PreReservationExampleConfigKeys.php');
require_once(dirname(__FILE__) . '/PreReservationExampleValidation.php');

class PreReservationExample implements IPreReservationFactory
{
    /**
     * @var PreReservationFactory
     */
    private $factoryToDecorate;

    public function __construct(PreReservationFactory $factoryToDecorate)
    {
        $this->factoryToDecorate = $factoryToDecorate;

        Configuration::Instance()->Register(
            dirname(__FILE__) . '/PreReservationExample.config.php',
            __DIR__ . '/.env',
            PreReservationExampleConfigKeys::CONFIG_ID,
            false,
            PreReservationExampleConfigKeys::class
        );
    }

    public function CreatePreAddService(UserSession $userSession)
    {
        $base = $this->factoryToDecorate->CreatePreAddService($userSession);
        return new PreReservationExampleValidation($base);
    }

    public function CreatePreUpdateService(UserSession $userSession)
    {
        $base =  $this->factoryToDecorate->CreatePreUpdateService($userSession);
        return new PreReservationExampleValidation($base);
    }

    public function CreatePreDeleteService(UserSession $userSession)
    {
        return $this->factoryToDecorate->CreatePreDeleteService($userSession);
    }

    /**
     * @param UserSession $userSession
     * @return IReservationValidationService
     */
    public function CreatePreApprovalService(UserSession $userSession)
    {
        return $this->factoryToDecorate->CreatePreApprovalService($userSession);
    }

    /**
     * @param UserSession $userSession
     * @return IReservationValidationService
     */
    public function CreatePreCheckinService(UserSession $userSession)
    {
        return $this->factoryToDecorate->CreatePreCheckinService($userSession);
    }

    /**
     * @param UserSession $userSession
     * @return IReservationValidationService
     */
    public function CreatePreCheckoutService(UserSession $userSession)
    {
        return $this->factoryToDecorate->CreatePreCheckoutService($userSession);
    }
}
