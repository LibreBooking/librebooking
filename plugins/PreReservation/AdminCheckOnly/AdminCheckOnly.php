<?php

require_once(dirname(__FILE__) . '/AdminCheckOnlyConfigKeys.php');
require_once(dirname(__FILE__) . '/AdminCheckInOnlyValidation.php');
require_once(dirname(__FILE__) . '/AdminCheckOutOnlyValidation.php');

class AdminCheckOnly implements IPreReservationFactory
{
    /**
     * @var PreReservationFactory
     */
    private $factoryToDecorate;

    public function __construct(PreReservationFactory $factoryToDecorate)
    {
        $this->factoryToDecorate = $factoryToDecorate;

        Configuration::Instance()->Register(
            dirname(__FILE__) . '/AdminCheckOnly.config.php',
            __DIR__ . '/.env',
            AdminCheckOnlyConfigKeys::CONFIG_ID,
            false,
            AdminCheckOnlyConfigKeys::class
        );
    }

    public function CreatePreAddService(UserSession $userSession)
    {
        return  $this->factoryToDecorate->CreatePreAddService($userSession);
    }

    public function CreatePreUpdateService(UserSession $userSession)
    {
        return $this->factoryToDecorate->CreatePreUpdateService($userSession);
    }

    public function CreatePreDeleteService(UserSession $userSession)
    {
        return $this->factoryToDecorate->CreatePreDeleteService($userSession);
    }

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
        $base = $this->factoryToDecorate->CreatePreCheckinService($userSession);
        return new AdminCheckInOnlyValidation($base, $userSession);
    }

    /**
     * @param UserSession $userSession
     * @return IReservationValidationService
     */
    public function CreatePreCheckoutService(UserSession $userSession)
    {
        $base = $this->factoryToDecorate->CreatePreCheckoutService($userSession);
        return new AdminCheckOutOnlyValidation($base, $userSession);
    }
}
