<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class GroupAdminManageReservationsService extends ManageReservationsService implements IManageReservationsService
{
    /**
     * @var IUserRepository
     */
    private $userRepository;

    /**
     * @param IReservationViewRepository $reservationViewRepository
     * @param IUserRepository $userRepository
     * @param IReservationAuthorization $authorization
     * @param IReservationHandler|null $reservationHandler
     * @param IUpdateReservationPersistenceService|null $persistenceService
     */
    public function __construct(
        IReservationViewRepository $reservationViewRepository,
        IUserRepository $userRepository,
        IReservationAuthorization $authorization,
        $reservationHandler = null,
        $persistenceService = null
    ) {
        parent::__construct($reservationViewRepository, $authorization, $reservationHandler, $persistenceService);

        $this->userRepository = $userRepository;
    }

    /**
     * @param $pageNumber int
     * @param $pageSize int
     * @param null|string $sortField
     * @param null|string $sortDirection
     * @param $filter ReservationFilter
     * @param $userSession UserSession
     * @return PageableData|ReservationItemView[]
     */
    public function LoadFiltered($pageNumber, $pageSize, $sortField, $sortDirection, $filter, $userSession)
    {
        $user = $this->userRepository->LoadById($userSession->UserId);

        $adminGroups = $user->GetAdminGroups();
        $groupIds = [];
        foreach ($adminGroups as $group) {
            $groupIds[] = $group->GroupId;
        }

        $command = new GetFullGroupReservationListCommand($groupIds);

        if ($filter != null) {
            $command = new FilterCommand($command, $filter->GetFilter());
        }

        $builder = ['ReservationItemView', 'Populate'];
        return PageableDataStore::GetList($command, $builder, $pageNumber, $pageSize, $sortField, $sortDirection);
    }
}
