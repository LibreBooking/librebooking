<?php

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationCreatedEmailAdmin.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationUpdatedEmailAdmin.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationDeletedEmailAdmin.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationRequiresApprovalEmailAdmin.php');

abstract class AdminEmailNotification implements IReservationNotification
{
    /**
     * @var IUserRepository
     */
    protected $userRepo;

    /**
     * @var IUserViewRepository
     */
    protected $userViewRepo;

    /**
     * @var IAttributeRepository
     */
    protected $attributeRepository;

    /**
     * @param IUserRepository $userRepo
     * @param IUserViewRepository $userViewRepo
     * @param IAttributeRepository $attributeRepository
     */
    public function __construct(IUserRepository $userRepo, IUserViewRepository $userViewRepo, IAttributeRepository $attributeRepository)
    {
        $this->userRepo = $userRepo;
        $this->userViewRepo = $userViewRepo;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param ReservationSeries $reservationSeries
     * @return void
     */
    public function Notify($reservationSeries)
    {
        $resourceAdmins = [];
        $applicationAdmins = [];
        $groupAdmins = [];

        if ($this->SendForResourceAdmins($reservationSeries)) {
            $resourceAdmins = $this->userViewRepo->GetResourceAdmins($reservationSeries->ResourceId());
        }
        if ($this->SendForApplicationAdmins($reservationSeries)) {
            $applicationAdmins = $this->userViewRepo->GetApplicationAdmins();
        }
        if ($this->SendForGroupAdmins($reservationSeries)) {
            $groupAdmins = $this->userViewRepo->GetGroupAdmins($reservationSeries->UserId());
        }

        $admins = array_merge($resourceAdmins, $applicationAdmins, $groupAdmins);

        if (count($admins) == 0) {
            // skip if there is nobody to send to
            return;
        }

        $owner = $this->userRepo->LoadById($reservationSeries->UserId());
        $resource = $reservationSeries->Resource();

        $adminIds = [];
        /** @var UserDto $admin */
        foreach ($admins as $admin) {
            $id = $admin->Id();
            if (array_key_exists($id, $adminIds) || $id == $owner->Id()) {
                // only send to each person once
                continue;
            }
            $adminIds[$id] = true;

            $message = $this->GetMessage($admin, $owner, $reservationSeries, $resource);
            ServiceLocator::GetEmailService()->Send($message);
        }
    }

    /**
     * @param UserDto $admin
     * @param User $owner
     * @param ReservationSeries $reservationSeries
     * @param BookableResource $resource
     * @return IEmailMessage
     */
    abstract protected function GetMessage($admin, $owner, $reservationSeries, $resource);

    /**
     * @param ReservationSeries $reservationSeries
     * @return bool
     */
    abstract protected function SendForResourceAdmins(ReservationSeries $reservationSeries);

    /**
     * @param ReservationSeries $reservationSeries
     * @return bool
     */
    abstract protected function SendForApplicationAdmins(ReservationSeries $reservationSeries);

    /**
     * @param ReservationSeries $reservationSeries
     * @return bool
     */
    abstract protected function SendForGroupAdmins(ReservationSeries $reservationSeries);
}

class AdminEmailCreatedNotification extends AdminEmailNotification
{
    protected function GetMessage($admin, $owner, $reservationSeries, $resource)
    {
        return new ReservationCreatedEmailAdmin($admin, $owner, $reservationSeries, $resource, $this->attributeRepository, $this->userRepo);
    }

    protected function SendForResourceAdmins(ReservationSeries $reservationSeries)
    {
        return Configuration::Instance()->GetKey(
            ConfigKeys::RESERVATION_NOTIFY_RESOURCE_ADMIN_ADD,
            new BooleanConverter()
        );
    }

    protected function SendForApplicationAdmins(ReservationSeries $reservationSeries)
    {
        return Configuration::Instance()->GetKey(
            ConfigKeys::RESERVATION_NOTIFY_APPLICATION_ADMIN_ADD,
            new BooleanConverter()
        );
    }

    protected function SendForGroupAdmins(ReservationSeries $reservationSeries)
    {
        return Configuration::Instance()->GetKey(
            ConfigKeys::RESERVATION_NOTIFY_GROUP_ADMIN_ADD,
            new BooleanConverter()
        );
    }
}

class AdminEmailUpdatedNotification extends AdminEmailNotification
{
    protected function GetMessage($admin, $owner, $reservationSeries, $resource)
    {
        return new ReservationUpdatedEmailAdmin($admin, $owner, $reservationSeries, $resource, $this->attributeRepository, $this->userRepo);
    }

    protected function SendForResourceAdmins(ReservationSeries $reservationSeries)
    {
        return Configuration::Instance()->GetKey(
            ConfigKeys::RESERVATION_NOTIFY_RESOURCE_ADMIN_UPDATE,
            new BooleanConverter()
        );
    }


    protected function SendForApplicationAdmins(ReservationSeries $reservationSeries)
    {
        return Configuration::Instance()->GetKey(
            ConfigKeys::RESERVATION_NOTIFY_APPLICATION_ADMIN_UPDATE,
            new BooleanConverter()
        );
    }

    protected function SendForGroupAdmins(ReservationSeries $reservationSeries)
    {
        return Configuration::Instance()->GetKey(
            ConfigKeys::RESERVATION_NOTIFY_GROUP_ADMIN_UPDATE,
            new BooleanConverter()
        );
    }
}

class AdminEmailDeletedNotification extends AdminEmailNotification
{
    protected function GetMessage($admin, $owner, $reservationSeries, $resource)
    {
        return new ReservationDeletedEmailAdmin($admin, $owner, $reservationSeries, $resource, $this->attributeRepository, $this->userRepo);
    }

    protected function SendForResourceAdmins(ReservationSeries $reservationSeries)
    {
        return Configuration::Instance()->GetKey(
            ConfigKeys::RESERVATION_NOTIFY_RESOURCE_ADMIN_DELETE,
            new BooleanConverter()
        );
    }


    protected function SendForApplicationAdmins(ReservationSeries $reservationSeries)
    {
        return Configuration::Instance()->GetKey(
            ConfigKeys::RESERVATION_NOTIFY_APPLICATION_ADMIN_DELETE,
            new BooleanConverter()
        );
    }

    protected function SendForGroupAdmins(ReservationSeries $reservationSeries)
    {
        return Configuration::Instance()->GetKey(
            ConfigKeys::RESERVATION_NOTIFY_GROUP_ADMIN_DELETE,
            new BooleanConverter()
        );
    }
}

class AdminEmailApprovalNotification extends AdminEmailNotification
{
    protected function GetMessage($admin, $owner, $reservationSeries, $resource)
    {
        return new ReservationRequiresApprovalEmailAdmin($admin, $owner, $reservationSeries, $resource, $this->attributeRepository, $this->userRepo);
    }

    protected function SendForResourceAdmins(ReservationSeries $reservationSeries)
    {
        return $reservationSeries->RequiresApproval() &&
            Configuration::Instance()->GetKey(
                ConfigKeys::RESERVATION_NOTIFY_RESOURCE_ADMIN_APPROVAL,
                new BooleanConverter()
            );
    }

    protected function SendForApplicationAdmins(ReservationSeries $reservationSeries)
    {
        return $reservationSeries->RequiresApproval() &&
            Configuration::Instance()->GetKey(
                ConfigKeys::RESERVATION_NOTIFY_APPLICATION_ADMIN_APPROVAL,
                new BooleanConverter()
            );
    }

    protected function SendForGroupAdmins(ReservationSeries $reservationSeries)
    {
        return $reservationSeries->RequiresApproval() &&
            Configuration::Instance()->GetKey(
                ConfigKeys::RESERVATION_NOTIFY_GROUP_ADMIN_APPROVAL,
                new BooleanConverter()
            );
    }
}
