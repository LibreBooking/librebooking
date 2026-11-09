<?php

class Group
{
    private $id;
    private $name;
    private $adminGroupId;
    private $isDefault = 0;

    private $addedUsers = [];
    private $removedUsers = [];
    private $users = [];

    private $removedPermissions = [];
    private $addedPermissions = [];
    private $allowedResourceIds = [];
    private $viewableResourceIds = [];
    private $removedViewPermissions = [];
    private $addedViewPermissions = [];

    /**
     * @var array|int[]
     */
    private $removedRoleIds = [];

    /**
     * @var array|int[]
     */
    private $addedRoleIds = [];

    /**
     * @var int[]
     */
    private array $roleIds = [];

    /**
     * @param $id int
     * @param $name string
     * @param $isDefault int
     */
    public function __construct($id, $name, $isDefault = 0)
    {
        $this->id = $id;
        $this->name = $name;
        $this->isDefault = intval($isDefault);
    }

    /**
     * @return int
     */
    public function Id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function Name()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function AdminGroupId()
    {
        return $this->adminGroupId;
    }

    /**
     * @return int
     */
    public function IsDefault()
    {
        return intval($this->isDefault);
    }

    /**
     * @param $groupName string
     * @return void
     */
    public function Rename($groupName)
    {
        $this->name = $groupName;
    }

    /**
     * @param int $isDefault
     */
    public function ChangeDefault($isDefault)
    {
        $this->isDefault = $isDefault;
    }

    /**
     * @param $userId int
     * @return void
     */
    public function AddUser($userId)
    {
        if (!$this->HasMember($userId)) {
            $this->addedUsers[] = $userId;
        }
    }

    /**
     * @param $userId int
     * @return void
     */
    public function RemoveUser($userId)
    {
        if ($this->HasMember($userId)) {
            $this->removedUsers[] = $userId;
        }
    }

    /**
     * @param $userIds int[]|array
     * @return void
     */
    public function ChangeUsers($userIds)
    {
        $diff = new ArrayDiff($this->users, $userIds);
        $removed = $diff->GetRemovedFromArray1();
        $added = $diff->GetAddedToArray1();

        if ($diff->AreDifferent()) {
            $this->removedUsers = $removed;
            $this->addedUsers = $added;

            $this->users = $userIds;
        }
    }

    /**
     * @internal
     * @return int[] array of userIds
     */
    public function AddedUsers()
    {
        return $this->addedUsers;
    }

    /**
     * @internal
     * @return int[] array of userIds
     */
    public function RemovedUsers()
    {
        return $this->removedUsers;
    }

    /**
     * @internal
     * @return array|int[]
     */
    public function AddedRoles()
    {
        return $this->addedRoleIds;
    }

    /**
     * @internal
     * @return array|int[]
     */
    public function RemovedRoles()
    {
        return $this->removedRoleIds;
    }

    /**
     * @internal
     * @param $groupId
     * @return void
     */
    public function WithId($groupId)
    {
        $this->id = $groupId;
    }

    /**
     * @internal
     * @param $userId int
     * @return void
     */
    public function WithUser($userId)
    {
        $this->users[] = $userId;
    }

    /**
     * @internal
     * @param $groupId int
     * @return void
     */
    public function WithGroupAdmin($groupId)
    {
        $this->adminGroupId = $groupId;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function HasMember($userId)
    {
        return in_array($userId, $this->users);
    }

    /**
     * @return array|int[]
     */
    public function UserIds()
    {
        return $this->users;
    }

    /**
     * @param int $allowedResourceId
     * @return void
     */
    public function WithFullPermission($allowedResourceId)
    {
        $this->allowedResourceIds[] = $allowedResourceId;
    }

    /**
     * @param int $viewableResourceId
     * @return void
     */
    public function WithViewablePermission($viewableResourceId)
    {
        $this->viewableResourceIds[] = $viewableResourceId;
    }

    /**
     * @param $role int
     * @return void
     */
    public function WithRole($role)
    {
        $this->roleIds[] = $role;
    }

    /**
     * @param int[] $viewableResourceIds
     * @return void
     */
    public function ChangeViewPermissions($viewableResourceIds = [])
    {
        $diff = new ArrayDiff($this->viewableResourceIds, $viewableResourceIds);
        $removed = $diff->GetRemovedFromArray1();
        $added = $diff->GetAddedToArray1();

        if ($diff->AreDifferent()) {
            $this->removedViewPermissions = $removed;
            $this->addedViewPermissions = $added;

            $this->viewableResourceIds = $viewableResourceIds;
        }
    }

    /**
     * @param int[] $allowedResourceIds
     * @return void
     */
    public function ChangeAllowedPermissions($allowedResourceIds = [])
    {
        $diff = new ArrayDiff($this->allowedResourceIds, $allowedResourceIds);
        $removed = $diff->GetRemovedFromArray1();
        $added = $diff->GetAddedToArray1();

        if ($diff->AreDifferent()) {
            $this->removedPermissions = $removed;
            $this->addedPermissions = $added;

            $this->allowedResourceIds = $allowedResourceIds;
        }
    }

    /**
     * @internal
     * @return int[]|array of resourceIds
     */
    public function RemovedPermissions()
    {
        return array_merge($this->removedPermissions, $this->removedViewPermissions);
    }

    /**
     * @internal
     * @return int[]|array of resourceIds
     */
    public function AddedPermissions()
    {
        return $this->addedPermissions;
    }

    /**
     * @return array|int[]
     */
    public function AllowedResourceIds()
    {
        return $this->allowedResourceIds;
    }

    /**
     * @internal
     * @return int[]|array of resourceIds
     */
    public function AddedViewPermissions()
    {
        return $this->addedViewPermissions;
    }

    /**
     * @return array|int[]
     */
    public function AllowedViewResourceIds()
    {
        return $this->viewableResourceIds;
    }

    /**
     * @return int[]
     */
    public function RoleIds(): array
    {
        return $this->roleIds;
    }

    /**
     * @param int[] $roleIds
     */
    public function ChangeRoles(array $roleIds): void
    {
        $diff = new ArrayDiff($this->roleIds, $roleIds);
        $removed = $diff->GetRemovedFromArray1();
        $added = $diff->GetAddedToArray1();

        if ($diff->AreDifferent()) {
            $this->removedRoleIds = $removed;
            $this->addedRoleIds = $added;

            $this->roleIds = $roleIds;
        }
    }

    /**
     * @param $groupId int
     * @return void
     */
    public function ChangeAdmin($groupId)
    {
        if (empty($groupId)) {
            $groupId = null;
        }
        $this->adminGroupId = $groupId;
    }

    public static function null()
    {
        return new NullGroup();
    }
}

class NullGroup extends Group
{
    public function __construct()
    {
        parent::__construct(0, null);
    }
}
