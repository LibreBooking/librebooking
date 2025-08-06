<?php

require_once(dirname(__FILE__) . '/AdminCheckInOnlyValidation.php');
require_once(dirname(__FILE__) . '/AdminCheckOutOnlyValidation.php');
require_once(dirname(__FILE__) . '/AdminCheckOnlyConfigKeys.php');
class AdminCheckOnly implements IPreReservationFactory
{
    /**
     * @var PreReservationFactory
     */
    private $factoryToDecorate;

    public function __construct(PreReservationFactory $factoryToDecorate)
    {
        $this->factoryToDecorate = $factoryToDecorate;

        require_once(dirname(__FILE__) . '/AdminCheckOnly.config.php');

        Configuration::Instance()->Register(
            dirname(__FILE__) . '/AdminCheckOnly.config.php',
            '',
            'AdminCheckOnly',
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
