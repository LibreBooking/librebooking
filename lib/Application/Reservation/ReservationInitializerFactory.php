<?php

class ReservationInitializerFactory implements IReservationInitializerFactory
{
    /**
     * @var ReservationUserBinder
     */
    private $userBinder;

    /**
     * @var ReservationDateBinder
     */
    private $dateBinder;

    /**
     * @var ReservationResourceBinder
     */
    private $resourceBinder;

    /**
     * @var IReservationAuthorization
     */
    private $reservationAuthorization;

    public function __construct(
        IScheduleRepository $scheduleRepository,
        IUserRepository $userRepository,
        IResourceService $resourceService,
        IReservationAuthorization $reservationAuthorization
    ) {
        $this->reservationAuthorization = $reservationAuthorization;

        $this->userBinder = new ReservationUserBinder($userRepository, $reservationAuthorization);
        $this->dateBinder = new ReservationDateBinder($scheduleRepository);
        $this->resourceBinder = new ReservationResourceBinder($resourceService, $scheduleRepository);
    }

    public function GetNewInitializer(INewReservationPage $page)
    {
        return new NewReservationInitializer(
            $page,
            $this->userBinder,
            $this->dateBinder,
            $this->resourceBinder,
            ServiceLocator::GetServer()->GetUserSession(),
            new ScheduleRepository(),
            new ResourceRepository(),
            new TermsOfServiceRepository()
        );
    }

    public function GetExistingInitializer(IExistingReservationPage $page, ReservationView $reservationView)
    {
        return new ExistingReservationInitializer(
            $page,
            $this->userBinder,
            $this->dateBinder,
            $this->resourceBinder,
            new ReservationDetailsBinder(
                $this->reservationAuthorization,
                $page,
                $reservationView,
                new PrivacyFilter($this->reservationAuthorization)
            ),
            $reservationView,
            ServiceLocator::GetServer()->GetUserSession(),
            new TermsOfServiceRepository()
        );
    }
}
