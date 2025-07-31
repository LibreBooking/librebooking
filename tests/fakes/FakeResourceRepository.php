<?php

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

    public function GetUserResourceList() {
        return null;
    }

    public function GetUserResourceIdList(): array { 
        return [];
    }

    public function GetUserList($resourceIds, $pageNumber, $pageSize, $sortField = null, $sortDirection = null, $filter = null) {
        return null;
    }

    public function GetUserResourcePermissions($userId, $resourceIds = []) { }

    public function GetUserGroupResourcePermissions($userId, $resourceIds = []) { }

    public function GetResourceAdminResourceIds($userId, $resourceIds = []) { }

    public function GetScheduleAdminResourceIds($userId, $resourceIds = []) { }

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
        // TODO: Implement Add() method.
        return null;
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
        // TODO: Implement GetList() method.
        return null;
    }

    public function GetAccessoryList($sortField = null, $sortDirection = null)
    {
        // TODO: Implement GetAccessoryList() method.
        return null;
    }

    public function GetResourceGroups($scheduleId = null, $resourceFilter = null)
    {
        // TODO: Implement GetResourceGroups() method.
        return null;
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
        // TODO: Implement AddResourceGroup() method.
        return null;
    }

    public function LoadResourceGroup($groupId)
    {
        // TODO: Implement LoadResourceGroup() method.
        return null;
    }

    public function LoadResourceGroupByPublicId($publicResourceGroupId)
    {
        // TODO: Implement LoadResourceGroupByPublicId() method.
        return null;
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
        // TODO: Implement GetResourceTypes() method.
        return null;
    }

    public function LoadResourceType($resourceTypeId)
    {
        // TODO: Implement LoadResourceType() method.
        return null;
    }

    public function AddResourceType(ResourceType $type)
    {
        // TODO: Implement AddResourceType() method.
        return null;
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
        // TODO: Implement GetStatusReasons() method.
        return null;
    }

    public function AddStatusReason($statusId, $reasonDescription)
    {
        // TODO: Implement AddStatusReason() method.
        return null;
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
        // TODO: Implement GetUsersWithPermission() method.
        return null;
    }

    public function GetGroupsWithPermission($resourceId, $pageNumber = null, $pageSize = null, $filter = null)
    {
        // TODO: Implement GetGroupsWithPermission() method.
        return null;
    }

    public function GetUsersWithPermissionsIncludingGroups($resourceId, $pageNumber = null, $pageSize = null, $filter = null, $accountStatus = AccountStatus::ACTIVE)
    {
        // TODO: Implement GetUsersWithPermissionsIncludingGroups() method.
        return null;
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
