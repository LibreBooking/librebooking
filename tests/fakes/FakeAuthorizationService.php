<?php

declare(strict_types=1);

class FakeAuthorizationService implements IAuthorizationService
{
    public $_IsApplicationAdministrator = false;
    public $_IsResourceAdministrator = false;
    public $_IsGroupAdministrator = false;
    public $_CanReserveForOthers = false;
    public $_CanReserveFor = false;
    public $_CanApproveFor = false;
    public $_CanEditForResource = false;
    public $_CanApproveForResource = false;
    public $_IsAdminFor = false;

    public function __construct()
    {
    }

    /**
     * @param User $user
     * @return bool
     */
    public function IsApplicationAdministrator(User $user)
    {
        return $this->_IsApplicationAdministrator;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function IsResourceAdministrator(User $user)
    {
        return $this->_IsResourceAdministrator;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function IsGroupAdministrator(User $user)
    {
        return $this->_IsGroupAdministrator;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function IsScheduleAdministrator(User $user)
    {
        return $this->_IsGroupAdministrator;
    }

    /**
     * @param UserSession $reserver user who is requesting access to perform action
     * @return bool
     */
    public function CanReserveForOthers(UserSession $reserver)
    {
        return $this->_CanReserveForOthers;
    }

    /**
     * @param UserSession $reserver user who is requesting access to perform action
     * @param int $reserveForId user to reserve for
     * @return bool
     */
    public function CanReserveFor(UserSession $reserver, $reserveForId)
    {
        return $this->_CanReserveFor;
    }

    /**
     * @param UserSession $approver user who is requesting access to perform action
     * @param int $approveForId user to approve for
     * @return bool
     */
    public function CanApproveFor(UserSession $approver, $approveForId)
    {
        return $this->_CanApproveFor;
    }

    /**
     * @param UserSession $user
     * @param IResource $resource
     * @return bool
     */
    public function CanEditForResource(UserSession $user, IResource $resource)
    {
        return $this->_CanEditForResource;
    }

    /**
     * @param UserSession $user
     * @param IResource $resource
     * @return bool
     */
    public function CanApproveForResource(UserSession $user, IResource $resource)
    {
        return $this->_CanApproveForResource;
    }

    /**
     * @param UserSession $userSession
     * @param int $otherUserId
     * @return bool
     */
    public function IsAdminFor(UserSession $userSession, $otherUserId)
    {
        return $this->_IsAdminFor;
    }
}
