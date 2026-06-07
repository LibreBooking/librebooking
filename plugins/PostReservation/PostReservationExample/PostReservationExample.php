<?php

class PostReservationExample implements IPostReservationFactory
{
    /**
     * @var PostReservationFactory
     */
    private $factoryToDecorate;

    public function __construct(PostReservationFactory $factoryToDecorate)
    {
        $this->factoryToDecorate = $factoryToDecorate;
    }

    /**
     * @param UserSession $userSession
     * @return IReservationNotificationService
     */
    public function CreatePostAddService(UserSession $userSession)
    {
        // custom logic to be executed
        $base = $this->factoryToDecorate->CreatePostAddService($userSession);
        return new PostReservationCreatedExample($base);
    }

    /**
     * @param UserSession $userSession
     * @return IReservationNotificationService
     */
    public function CreatePostUpdateService(UserSession $userSession)
    {
        $base = $this->factoryToDecorate->CreatePostUpdateService($userSession);
        return new PostReservationUpdateExample($base);
    }

    /**
     * @param UserSession $userSession
     * @return IReservationNotificationService
     */
    public function CreatePostDeleteService(UserSession $userSession)
    {
        // showing how to not add custom behavior during the post deletion stage
        return $this->factoryToDecorate->CreatePostDeleteService($userSession);
    }

    /**
     * @param UserSession $userSession
     * @return IReservationNotificationService
     */
    public function CreatePostApproveService(UserSession $userSession)
    {
        // showing how to not add custom behavior during the post approval stage
        return $this->factoryToDecorate->CreatePostApproveService($userSession);
    }

    /**
     * @param UserSession $userSession
     * @return IReservationNotificationService
     */
    public function CreatePostCheckinService(UserSession $userSession)
    {
        return $this->factoryToDecorate->CreatePostCheckinService($userSession);
    }

    /**
     * @param UserSession $userSession
     * @return IReservationNotificationService
     */
    public function CreatePostCheckoutService(UserSession $userSession)
    {
        return $this->factoryToDecorate->CreatePostCheckoutService($userSession);
    }
}

class PostReservationCreatedExample implements IReservationNotificationService
{
    /**
     * @var IReservationNotificationService
     */
    private $base;

    public function __construct(IReservationNotificationService $base)
    {
        $this->base = $base;
    }

    /**
     * @param $reservationSeries ReservationSeries|ExistingReservationSeries
     * @return void
     */
    public function Notify($reservationSeries)
    {
        // implement any custom post reservation created logic here

        // then let the main application continue
        $this->base->Notify($reservationSeries);
    }
}

class PostReservationUpdateExample implements IReservationNotificationService
{
    /**
     * @var IReservationNotificationService
     */
    private $base;

    public function __construct(IReservationNotificationService $base)
    {
        $this->base = $base;
    }

    /**
     * @param $reservationSeries ReservationSeries|ExistingReservationSeries
     * @return void
     */
    public function Notify($reservationSeries)
    {
        // implement any custom post reservation updated logic here

        // do not call the base Notify method if you want to completely override the base behavior
    }
}
