<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/ResourceRepository.php');

class FakeResourceRepository implements IResourceRepository
{
    /**
     * @var array|BookableResource[]
     */
    public $_ResourceList = [];
    /**
     * @var array|BookableResource[]
     */
    public $_ScheduleResourceList = [];

    /**
     * @var FakeBookableResource
     */
    public $_Resource;

    /**
     * @var BookableResource|FakeBookableResource
     */
    public $_UpdatedResource;

    public $_NamedResources = [];

    public $_PublicResourceIds = [];

    public function GetResourceIdList(): array
    {
        throw new Exception('Not implemented');
    }

    public function GetUserResourceList()
    {
        return [];
    }

    public function GetUserResourceIdList(): array
    {
        return [];
    }

    public function GetUserList($resourceIds, $pageNumber, $pageSize, $sortField = null, $sortDirection = null, $filter = null)
    {
        return [];
    }

    public function GetUserResourcePermissions($userId, $resourceIds = [])
    {
    }

    public function GetUserGroupResourcePermissions($userId, $resourceIds = [])
    {
    }

    public function GetResourceAdminResourceIds($userId, $resourceIds = [])
    {
    }

    public function GetScheduleAdminResourceIds($userId, $resourceIds = [])
    {
    }

    public function GetScheduleResources($scheduleId)
    {
        return $this->_ScheduleResourceList;
    }

    public function LoadById($resourceId)
    {
        if (isset($this->_ResourceList[$resourceId])) {
            return $this->_ResourceList[$resourceId];
        }
        return $this->_Resource;
    }

    public function LoadByPublicId($publicId)
    {
        return $this->_Resource;
    }

    public function LoadByName($name)
    {
        return $this->_NamedResources[$name];
    }

    public function Add(BookableResource $resource)
    {
        $this->_ResourceList[] = $resource;
        return count($this->_ResourceList);
    }

    public function Update(BookableResource $resource)
    {
        $this->_UpdatedResource = $resource;
    }

    public function Delete(BookableResource $resource)
    {
        // TODO: Implement Delete() method.
    }

    public function GetResourceList()
    {
        return $this->_ResourceList;
    }

    public function GetList($pageNumber, $pageSize, $sortField = null, $sortDirection = null, $filter = null)
    {
        return [];
    }

    public function GetAccessoryList($sortField = null, $sortDirection = null)
    {
        return [];
    }

    public function GetResourceGroups($scheduleId = null, $resourceFilter = null)
    {
        throw new LogicException('GetResourceGroups() not implemented in FakeResourceRepository');
    }

    public function AddResourceToGroup($resourceId, $groupId)
    {
        // TODO: Implement AddResourceToGroup() method.
    }

    public function RemoveResourceFromGroup($resourceId, $groupId)
    {
        // TODO: Implement RemoveResourceFromGroup() method.
    }

    public function AddResourceGroup(ResourceGroup $group)
    {
        throw new LogicException('AddResourceGroup() not implemented in FakeResourceRepository');
    }

    public function LoadResourceGroup($groupId)
    {
        throw new LogicException('LoadResourceGroup() not implemented in FakeResourceRepository');
    }

    public function LoadResourceGroupByPublicId($publicResourceGroupId)
    {
        throw new LogicException('LoadResourceGroupByPublicId() not implemented in FakeResourceRepository');
    }

    public function UpdateResourceGroup(ResourceGroup $group)
    {
        // TODO: Implement UpdateResourceGroup() method.
    }

    public function DeleteResourceGroup($groupId)
    {
        // TODO: Implement DeleteResourceGroup() method.
    }

    public function GetResourceTypes()
    {
        return [];
    }

    public function LoadResourceType($resourceTypeId)
    {
        throw new LogicException('LoadResourceType() not implemented in FakeResourceRepository');
    }

    public function AddResourceType(ResourceType $type)
    {
        return 1;
    }

    public function UpdateResourceType(ResourceType $type)
    {
        // TODO: Implement UpdateResourceType() method.
    }

    public function RemoveResourceType($id)
    {
        // TODO: Implement RemoveResourceType() method.
    }

    public function GetStatusReasons()
    {
        return [];
    }

    public function AddStatusReason($statusId, $reasonDescription)
    {
        return 1;
    }

    public function UpdateStatusReason($reasonId, $reasonDescription)
    {
        // TODO: Implement UpdateStatusReason() method.
    }

    public function RemoveStatusReason($reasonId)
    {
        // TODO: Implement RemoveStatusReason() method.
    }

    public function GetUsersWithPermission(
        $resourceId,
        $pageNumber = null,
        $pageSize = null,
        $filter = null,
        $accountStatus = AccountStatus::ACTIVE
    ) {
        return [];
    }

    public function GetGroupsWithPermission($resourceId, $pageNumber = null, $pageSize = null, $filter = null)
    {
        return [];
    }

    public function GetUsersWithPermissionsIncludingGroups($resourceId, $pageNumber = null, $pageSize = null, $filter = null, $accountStatus = AccountStatus::ACTIVE)
    {
        return [];
    }

    public function ChangeResourceGroupPermission($resourceId, $groupId, $type)
    {
        // TODO: Implement ChangeResourceGroupPermission() method.
    }

    public function ChangeResourceUserPermission($resourceId, $userId, $type)
    {
        // TODO: Implement ChangeResourceUserPermission() method.
    }

    public function GetPublicResourceIds()
    {
        return $this->_PublicResourceIds;
    }

    public function GetResourceGroupsList()
    {
        throw new LogicException('GetResourceGroupsList() method is not implemented in FakeResourceRepository');
    }
}
