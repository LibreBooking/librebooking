<?php

class FakeUserRepository implements IUserRepository
{
    /**
     * @var FakeUser
     */
    public $_User;
    /**
     * @var FakeUser
     */
    public $_UpdatedUser;
    /**
     * @var FakeUser
     */
    public $_AddedUser;

    /**
     * @var int|null
     */
    public $_Exists = null;

    /**
     * @var UserDto
     */
    public $_UserDto;
    /**
     * @var UserDto[]
     */
    public $_UserDtos;
    /**
     * @var PageableData
     */
    public $_UserList;
    /**
     * @var User[]
     */
    public $_UserById = [];
    /**
     * @var UserDto[]
     */
    public $_AllUsers = [];
    public $_DeletedUserId;

    public function __construct()
    {
        $this->_User = new FakeUser(123);
    }
    /**
     * @param int $userId
     * @return User
     */
    public function LoadById($userId)
    {
        if (array_key_exists($userId, $this->_UserById)) {
            return $this->_UserById[$userId];
        }
        return $this->_User;
    }

    /**
     * @param string $publicId
     * @return User
     */
    public function LoadByPublicId($publicId)
    {
        return $this->_User;
    }

    /**
     * @param string $userName
     * @return User
     */
    public function LoadByUsername($userName)
    {
        return $this->_User;
    }

    /**
     * @param User $user
     * @return void
     */
    public function Update(User $user)
    {
        $this->_UpdatedUser = $user;
    }

    /**
     * @param User $user
     * @return int
     */
    public function Add(User $user)
    {
        $this->_AddedUser = $user;
        return null;
    }

    /**
     * @param $userId int
     * @return void
     */
    public function DeleteById($userId)
    {
        $this->_DeletedUserId = $userId;
    }

    /**
     * @param int $userId
     * @return UserDto
     */
    public function GetById($userId)
    {
        if ($this->_UserDto != null) {
            return $this->_UserDto;
        }

        return $this->_UserDtos[$userId];
    }

    /**
     * @return UserDto[]
     */
    public function GetAll()
    {
        return $this->_AllUsers;
    }

    /**
     * @param int $pageNumber
     * @param int $pageSize
     * @param null|string $sortField
     * @param null|string $sortDirection
     * @param null|ISqlFilter $filter
     * @param AccountStatus|int $accountStatus
     * @return PageableData|UserItemView[]
     */
    public function GetList(
        $pageNumber,
        $pageSize,
        $sortField = null,
        $sortDirection = null,
        $filter = null,
        $accountStatus = AccountStatus::ALL
    ) {
        return $this->_UserList;
    }

    /**
     * @param int $resourceId
     * @return array|UserDto[]
     */
    public function GetResourceAdmins($resourceId)
    {
        // TODO: Implement GetResourceAdmins() method.
        return null;
    }

    /**
     * @return array|UserDto[]
     */
    public function GetApplicationAdmins()
    {
        // TODO: Implement GetApplicationAdmins() method.
        return null;
    }

    /**
     * @param int $userId
     * @return array|UserDto[]
     */
    public function GetGroupAdmins($userId)
    {
        // TODO: Implement GetGroupAdmins() method.
        return null;
    }

    /**
     * @param $userId int
     * @param $roleLevels int|null|array|int[]
     * @return array|UserGroup[]
     */
    public function LoadGroups($userId, $roleLevels = null)
    {
        // TODO: Implement LoadGroups() method.
        return null;
    }

    /**
     * @param string $emailAddress
     * @param string $userName
     * @return int|null
     */
    public function UserExists($emailAddress, $userName)
    {
        return $this->_Exists;
    }

    /**
     * @return int
     */
    public function GetCount()
    {
        // TODO: Implement GetCount() method.
        return null;
    }
}
