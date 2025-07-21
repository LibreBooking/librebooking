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
        return Configuration::Instance()->GetSectionKey(
            ConfigSection::RESERVATION_NOTIFY,
            ConfigKeys::NOTIFY_CREATE_RESOURCE_ADMINS,
            new BooleanConverter()
        );
    }

    protected function SendForApplicationAdmins(ReservationSeries $reservationSeries)
    {
        return Configuration::Instance()->GetSectionKey(
            ConfigSection::RESERVATION_NOTIFY,
            ConfigKeys::NOTIFY_CREATE_APPLICATION_ADMINS,
            new BooleanConverter()
        );
    }

    protected function SendForGroupAdmins(ReservationSeries $reservationSeries)
    {
        return Configuration::Instance()->GetSectionKey(
            ConfigSection::RESERVATION_NOTIFY,
            ConfigKeys::NOTIFY_CREATE_GROUP_ADMINS,
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
        return Configuration::Instance()->GetSectionKey(
            ConfigSection::RESERVATION_NOTIFY,
            ConfigKeys::NOTIFY_UPDATE_RESOURCE_ADMINS,
            new BooleanConverter()
        );
    }


    protected function SendForApplicationAdmins(ReservationSeries $reservationSeries)
    {
        return Configuration::Instance()->GetSectionKey(
            ConfigSection::RESERVATION_NOTIFY,
            ConfigKeys::NOTIFY_UPDATE_APPLICATION_ADMINS,
            new BooleanConverter()
        );
    }

    protected function SendForGroupAdmins(ReservationSeries $reservationSeries)
    {
        return Configuration::Instance()->GetSectionKey(
            ConfigSection::RESERVATION_NOTIFY,
            ConfigKeys::NOTIFY_UPDATE_GROUP_ADMINS,
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
        return Configuration::Instance()->GetSectionKey(
            ConfigSection::RESERVATION_NOTIFY,
            ConfigKeys::NOTIFY_DELETE_RESOURCE_ADMINS,
            new BooleanConverter()
        );
    }


    protected function SendForApplicationAdmins(ReservationSeries $reservationSeries)
    {
        return Configuration::Instance()->GetSectionKey(
            ConfigSection::RESERVATION_NOTIFY,
            ConfigKeys::NOTIFY_DELETE_APPLICATION_ADMINS,
            new BooleanConverter()
        );
    }

    protected function SendForGroupAdmins(ReservationSeries $reservationSeries)
    {
        return Configuration::Instance()->GetSectionKey(
            ConfigSection::RESERVATION_NOTIFY,
            ConfigKeys::NOTIFY_DELETE_GROUP_ADMINS,
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
        Configuration::Instance()->GetSectionKey(
            ConfigSection::RESERVATION_NOTIFY,
            ConfigKeys::NOTIFY_APPROVAL_RESOURCE_ADMINS,
            new BooleanConverter()
        );
    }

    protected function SendForApplicationAdmins(ReservationSeries $reservationSeries)
    {
        return $reservationSeries->RequiresApproval() &&
        Configuration::Instance()->GetSectionKey(
            ConfigSection::RESERVATION_NOTIFY,
            ConfigKeys::NOTIFY_APPROVAL_APPLICATION_ADMINS,
            new BooleanConverter()
        );
    }

    protected function SendForGroupAdmins(ReservationSeries $reservationSeries)
    {
        return $reservationSeries->RequiresApproval() &&
        Configuration::Instance()->GetSectionKey(
            ConfigSection::RESERVATION_NOTIFY,
            ConfigKeys::NOTIFY_APPROVAL_GROUP_ADMINS,
            new BooleanConverter()
        );
    }
}
