<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class FakeGroupViewRepository implements IGroupViewRepository
{
    private $_groupList = [];

    public function __construct()
    {
    }

    /**
     * @param GroupItemView $groupItemView
     */
    public function _AddGroup($groupItemView)
    {
        $this->_groupList[] = $groupItemView;
    }
    /**
     * @param int $pageNumber
     * @param int $pageSize
     * @param string $sortField
     * @param string $sortDirection
     * @param ISqlFilter $filter
     * @return PageableData|GroupItemView[]
     */
    public function GetList(
        $pageNumber = null,
        $pageSize = null,
        $sortField = null,
        $sortDirection = null,
        $filter = null
    ) {
        return new PageableData($this->_groupList);
    }

    /**
     * @param int|array|int[] $groupIds
     * @param int $pageNumber
     * @param int $pageSize
     * @param ISqlFilter $filter
     * @param AccountStatus|int $accountStatus
     * @return PageableData|GroupUserView[]
     */
    public function GetUsersInGroup(
        $groupIds,
        $pageNumber = null,
        $pageSize = null,
        $filter = null,
        $accountStatus = AccountStatus::ALL
    ) {
        return [];
    }

    /**
     * @param $roleLevel int|RoleLevel
     * @return GroupItemView[]|array
     */
    public function GetGroupsByRole($roleLevel)
    {
        return [];
    }

    public function GetPermissionList()
    {
        return [];
    }
}
