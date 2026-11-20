<?php

class ReservationValidationFactory implements IReservationValidationFactory
{
    /**
     * @var array|string[]
     */
    private $creationStrategies = [];

    public function __construct()
    {
        $this->creationStrategies[ReservationAction::Approve] = 'CreateApprovalService';
        $this->creationStrategies[ReservationAction::Create] = 'CreateAddService';
        $this->creationStrategies[ReservationAction::Delete] = 'CreateDeleteService';
        $this->creationStrategies[ReservationAction::Update] = 'CreateUpdateService';
        $this->creationStrategies[ReservationAction::Checkin] = 'CreateCheckinService';
        $this->creationStrategies[ReservationAction::Checkout] = 'CreateCheckoutService';
    }

    public function Create($reservationAction, $userSession)
    {
        if (array_key_exists($reservationAction, $this->creationStrategies)) {
            $createMethod = $this->creationStrategies[$reservationAction];
            return $this->$createMethod($userSession);
        }

        return new NullReservationValidationService();
    }

    private function CreateAddService(UserSession $userSession)
    {
        $factory = PluginManager::Instance()->LoadPreReservation();
        return $factory->CreatePreAddService($userSession);
    }

    private function CreateUpdateService(UserSession $userSession)
    {
        $factory = PluginManager::Instance()->LoadPreReservation();
        return $factory->CreatePreUpdateService($userSession);
    }

    private function CreateDeleteService(UserSession $userSession)
    {
        $factory = PluginManager::Instance()->LoadPreReservation();
        return $factory->CreatePreDeleteService($userSession);
    }

    private function CreateApprovalService(UserSession $userSession)
    {
        $factory = PluginManager::Instance()->LoadPreReservation();
        if (method_exists($factory, 'CreatePreApprovalService')) {
            return $factory->CreatePreApprovalService($userSession);
        }
        return new NullReservationValidationService();
    }

    private function CreateCheckinService(UserSession $userSession)
    {
        $factory = PluginManager::Instance()->LoadPreReservation();
        if (method_exists($factory, 'CreatePreCheckinService')) {
            return $factory->CreatePreCheckinService($userSession);
        }
        return new NullReservationValidationService();
    }

    private function CreateCheckoutService(UserSession $userSession)
    {
        $factory = PluginManager::Instance()->LoadPreReservation();
        if (method_exists($factory, 'CreatePreCheckoutService')) {
            return $factory->CreatePreCheckoutService($userSession);
        }
        return new NullReservationValidationService();
    }
}

class NullReservationValidationService implements IReservationValidationService
{
    public function Validate($series, $retryParameters = null)
    {
        return new ReservationValidationResult();
    }
}
