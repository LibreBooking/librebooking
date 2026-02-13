<?php

require_once(ROOT_DIR . 'Domain/BookableResource.php');
require_once(ROOT_DIR . 'Domain/ResourceGroup.php');
require_once(ROOT_DIR . 'Domain/ResourceType.php');
require_once(ROOT_DIR . 'Domain/Access/IResourceRepository.php');
require_once(ROOT_DIR . 'Domain/Values/ResourceStatus.php');
require_once(ROOT_DIR . 'Domain/Values/AccountStatus.php');
require_once(ROOT_DIR . 'Domain/Values/AccountStatus.php');

class ResourceRepository implements IResourceRepository
{
    /**
     * @var DomainCache
     */
    private $_cache;

    public const ALL_SCHEDULES = -1;

    public function __construct()
    {
        $this->_cache = new DomainCache();
    }

    /**
     * @param int $scheduleId
     * @return array|BookableResource[]
     */
    public function GetScheduleResources($scheduleId)
    {
        if ($scheduleId == -1) {
            $filter = new SqlFilterNull();
        } else {
            $filter = new SqlFilterEquals(new SqlFilterColumn('r', ColumnNames::SCHEDULE_ID), $scheduleId);
            $filter = $filter->_And(new SqlFilterNotEquals(new SqlFilterColumn('r', ColumnNames::RESOURCE_STATUS_ID), ResourceStatus::HIDDEN));
        }
        $command = new FilterCommand(new GetAllResourcesCommand(), $filter);

        $resources = [];

        $reader = ServiceLocator::GetDatabase()->Query($command);

        while ($row = $reader->GetRow()) {
            $resources[] = BookableResource::Create($row);
        }

        $reader->Free();

        return $resources;
    }

    /**
     * Gets all the resources
     */
    public function GetResourceList()
    {
        $resources = [];
        $reader = ServiceLocator::GetDatabase()->Query(new GetAllResourcesCommand());

        while ($row = $reader->GetRow()) {
            $resources[] = BookableResource::Create($row);
        }

        $reader->Free();

        return $resources;
    }

    /**
     * Gets the resource IDs for all the resources
     */
    public function GetResourceIdList(): array
    {
        $resourceIds = [];
        $reader = ServiceLocator::GetDatabase()->Query(new GetAllResourcesCommand());
        while ($row = $reader->GetRow()) {
            $resourceId = $row[ColumnNames::RESOURCE_ID];

            if (!array_key_exists($resourceId, $resourceIds)) {
                $resourceIds[$resourceId] = $resourceId;
            }
        }
        $reader->Free();
        return $resourceIds;
    }

    /**
     * Gets, for the logged in user, the resources that the user and the user groups
     * have permissions to. Admin user will return all resources.
     */
    public function GetUserResourceList()
    {
        $userSession = ServiceLocator::GetUserSession();
        if (is_null($userSession)) {
            return [];
        }
        if ($userSession->IsAdmin) {
            return $this->GetResourceList();
        }
        $resourceIds = $this->GetUserResourceIdList();
        $resources = [];
        foreach ($resourceIds as $resourceId) {
            $resource = $this->LoadById($resourceId);
            if ($resource->GetStatusId() != ResourceStatus::HIDDEN) {
                $resources[$resourceId] = $resource;
            }
        }
        return $resources;
    }

    /**
     * Gets the resource IDs that the logged in user has permissions (full access and view only permissions)
     * This is used to block a user from seeing a resource if they don't have permissions to it.
     */
    public function GetUserResourceIdList()
    {
        $userSession = ServiceLocator::GetUserSession();
        if (is_null($userSession)) {
            return [];
        }
        if ($userSession->IsAdmin) {
            return $this->GetResourceIdList();
        }
        $resourceIds = $this->GetUserResourcePermissions(userId: $userSession->UserId);
        $resourceIds = $this->GetUserGroupResourcePermissions(userId: $userSession->UserId, resourceIds: $resourceIds);


        if ($userSession->IsResourceAdmin) {
            $resourceIds = $this->GetResourceAdminResourceIds(userId: $userSession->UserId, resourceIds: $resourceIds);
        }

        if ($userSession->IsScheduleAdmin) {
            $resourceIds = $this->GetScheduleAdminResourceIds(userId: $userSession->UserId, resourceIds: $resourceIds);
        }
        return $resourceIds;
    }

    public function GetResourceGroupsList()
    {
        $reader = ServiceLocator::GetDatabase()->Query(new GetAllResourceGroupsCommand());

        $groups = [];
        while ($row = $reader->GetRow()) {
            $groups[] = new ResourceGroup(
                $row[ColumnNames::RESOURCE_GROUP_ID],
                $row[ColumnNames::RESOURCE_GROUP_NAME],
                $row[ColumnNames::RESOURCE_GROUP_PARENT_ID]
            );
        }

        $reader->Free();

        return $groups;
    }

    public function GetList($pageNumber, $pageSize, $sortField = null, $sortDirection = null, $filter = null)
    {
        $command = new GetAllResourcesCommand();

        if ($filter != null) {
            $command = new FilterCommand($command, $filter);
        }

        $builder = ['BookableResource', 'Create'];
        return PageableDataStore::GetList($command, $builder, $pageNumber, $pageSize);
    }

    public function GetUserList($resourceIds, $pageNumber, $pageSize, $sortField = null, $sortDirection = null, $filter = null)
    {
        $command = new GetUserResourcesCommand($resourceIds);

        if ($filter != null) {
            $command = new FilterCommand($command, $filter);
        }

        $builder = ['BookableResource', 'Create'];
        return PageableDataStore::GetList($command, $builder, $pageNumber, $pageSize);
    }

    /**
     * @param int $resourceId
     * @return BookableResource
     */
    public function LoadById($resourceId)
    {
        if (!$this->_cache->Exists($resourceId)) {
            $resource = $this->LoadResource(new GetResourceByIdCommand($resourceId));

            $this->_cache->Add($resourceId, $resource);
        }

        return $this->_cache->Get($resourceId);
    }

    public function LoadByContactInfo($contact_info)
    {
        return $this->LoadResource(new GetResourceByContactInfoCommand($contact_info));
    }

    /**
     * @param string $publicId
     * @return BookableResource
     */
    public function LoadByPublicId($publicId)
    {
        return $this->LoadResource(new GetResourceByPublicIdCommand($publicId));
    }

    public function LoadByName($resourceName)
    {
        return $this->LoadResource(new GetResourceByNameCommand($resourceName));
    }

    /**
     * @param $command SqlCommand
     * @return BookableResource
     */
    private function LoadResource($command)
    {
        $reader = ServiceLocator::GetDatabase()->Query($command);

        $resource = BookableResource::Null();
        if ($row = $reader->GetRow()) {
            $resource = BookableResource::Create($row);

            $getAttributes = new GetAttributeValuesCommand($resource->GetId(), CustomAttributeCategory::RESOURCE);
            $attributeReader = ServiceLocator::GetDatabase()->Query($getAttributes);

            while ($attributeRow = $attributeReader->GetRow()) {
                $resource->WithAttribute(new AttributeValue(
                    $attributeRow[ColumnNames::ATTRIBUTE_ID],
                    $attributeRow[ColumnNames::ATTRIBUTE_VALUE]
                ));
            }

            $attributeReader->Free();

            $getGroupAssignments = new GetResourceGroupAssignmentsCommand($resource->GetId());
            $groupAssignmentReader = ServiceLocator::GetDatabase()->Query($getGroupAssignments);

            while ($groupAssignmentRow = $groupAssignmentReader->GetRow()) {
                $resource->WithResourceGroupId($groupAssignmentRow[ColumnNames::RESOURCE_GROUP_ID]);
            }

            $groupAssignmentReader->Free();
        }

        $reader->Free();

        return $resource;
    }

    public function Add(BookableResource $resource)
    {
        $db = ServiceLocator::GetDatabase();
        $addResourceCommand = new AddResourceCommand(
            $resource->GetName(),
            $resource->GetScheduleId(),
            $resource->GetAutoAssign(),
            $resource->GetAdminGroupId()
        );

        $resourceId = $db->ExecuteInsert($addResourceCommand);
        if ($resource->GetAutoAssign()) {
            $db->Execute(new AutoAssignResourcePermissionsCommand($resourceId));
        }

        $resource->SetResourceId($resourceId);
        return $resourceId;
    }

    public function Update(BookableResource $resource)
    {
        $db = ServiceLocator::GetDatabase();

        $updateResourceCommand = new UpdateResourceCommand(
            $resource->GetResourceId(),
            $resource->GetName(),
            $resource->GetLocation(),
            $resource->GetContact(),
            $resource->GetNotes(),
            $resource->GetMinLength(),
            $resource->GetMaxLength(),
            $resource->GetAutoAssign(),
            $resource->GetRequiresApproval(),
            $resource->GetAllowMultiday(),
            $resource->GetMaxParticipants(),
            $resource->GetMinNoticeAdd(),
            $resource->GetMaxNotice(),
            $resource->GetDescription(),
            $resource->GetImage(),
            $resource->GetScheduleId(),
            $resource->GetAdminGroupId(),
            $resource->GetIsCalendarSubscriptionAllowed(),
            $resource->GetPublicId(),
            $resource->GetSortOrder(),
            $resource->GetResourceTypeId(),
            $resource->GetStatusId(),
            $resource->GetStatusReasonId(),
            $resource->GetBufferTime(),
            $resource->GetColor(),
            $resource->IsCheckInEnabled(),
            $resource->GetAutoReleaseMinutes(),
            $resource->GetIsDisplayEnabled(),
            $resource->GetCreditsPerSlot(),
            $resource->GetPeakCreditsPerSlot(),
            $resource->GetMinNoticeUpdate(),
            $resource->GetMinNoticeDelete(),
            $resource->GetSerializedProperties()
        );

        $db->Execute($updateResourceCommand);

        foreach ($resource->GetRemovedAttributes() as $removed) {
            $db->Execute(new RemoveAttributeValueCommand($removed->AttributeId, $resource->GetId()));
        }

        foreach ($resource->GetAddedAttributes() as $added) {
            $db->Execute(new AddAttributeValueCommand(
                $added->AttributeId,
                $added->Value,
                $resource->GetId(),
                CustomAttributeCategory::RESOURCE
            ));
        }

        if ($resource->WasAutoAssignToggledOn()) {
            $db->Execute(new AutoAssignResourcePermissionsCommand($resource->GetId()));
        }

        if ($resource->GetClearAllPermissions()) {
            $db->Execute(new AutoAssignClearResourcePermissionsCommand($resource->GetId()));
        }
        $db->Execute(new DeleteResourceImagesCommand($resource->GetId()));

        foreach ($resource->GetImages() as $image) {
            $db->Execute(new AddResourceImageCommand($resource->GetId(), $image));
        }

        $this->_cache->Add($resource->GetId(), $resource);
    }

    public function Delete(BookableResource $resource)
    {
        Log::Debug('Deleting resource %s (%s)', $resource->GetResourceId(), $resource->GetName());

        $resourceId = $resource->GetResourceId();

        $db = ServiceLocator::GetDatabase();
        $db->Execute(new DeleteResourceReservationsCommand($resourceId));
        $db->Execute(new DeleteResourceCommand($resourceId));

        $this->_cache->Remove($resource->GetId());
    }

    public function GetAccessoryList($sortField = null, $sortDirection = null)
    {
        $command = new GetAllAccessoriesCommand();
        $accessories = [];

        if (!empty($sortField)) {
            $command = new SortCommand($command, $sortField, $sortDirection);
        }

        $reader = ServiceLocator::GetDatabase()->Query($command);

        while ($row = $reader->GetRow()) {
            $accessories[] = AccessoryDto::Create($row);
        }

        $reader->Free();

        return $accessories;
    }

    public function GetResourceGroups($scheduleId = ResourceRepository::ALL_SCHEDULES, $resourceFilter = null)
    {
        if (empty($scheduleId)) {
            $scheduleId = ResourceRepository::ALL_SCHEDULES;
        }

        $groups = ServiceLocator::GetDatabase()->Query(new GetAllResourceGroupsCommand());
        $resources = ServiceLocator::GetDatabase()->Query(new GetAllResourceGroupAssignmentsCommand($scheduleId));

        $_groups = [];
        $_assignments = [];

        /** @var BookableResource[] $resourceList */
        $resourceList = [];

        $_groups[] = new ResourceGroup(0, Resources::GetInstance()->GetString('All'));
        foreach ($this->GetScheduleResources($scheduleId) as $r) {
            $resourceList[$r->GetId()] = $r;
            $_assignments[] = new ResourceGroupAssignment(
                0,
                $r->GetName(),
                $r->GetResourceId(),
                $r->GetAdminGroupId(),
                $r->GetScheduleId(),
                $r->GetStatusId(),
                $r->GetScheduleAdminGroupId(),
                $r->GetRequiresApproval(),
                $r->IsCheckInEnabled(),
                $r->IsAutoReleased(),
                $r->GetAutoReleaseMinutes(),
                $r->GetMinimumLength(),
                $r->GetResourceTypeId(),
                $r->GetColor(),
                $r->GetMaxConcurrentReservations()
            );
        }

        while ($row = $groups->GetRow()) {
            $_groups[] = new ResourceGroup(
                $row[ColumnNames::RESOURCE_GROUP_ID],
                $row[ColumnNames::RESOURCE_GROUP_NAME],
                $row[ColumnNames::RESOURCE_GROUP_PARENT_ID]
            );
        }

        while ($row = $resources->GetRow()) {
            if ($row[ColumnNames::RESOURCE_STATUS_ID] == ResourceStatus::HIDDEN) {
                continue;
            }
            $resourceId = $row[ColumnNames::RESOURCE_ID];
            if (array_key_exists($resourceId, $resourceList)) {
                $r = $resourceList[$resourceId];
                $_assignments[] = new ResourceGroupAssignment(
                    $row[ColumnNames::RESOURCE_GROUP_ID],
                    $r->GetName(),
                    $r->GetResourceId(),
                    $r->GetAdminGroupId(),
                    $r->GetScheduleId(),
                    $r->GetStatusId(),
                    $r->GetScheduleAdminGroupId(),
                    $r->GetRequiresApproval(),
                    $r->IsCheckInEnabled(),
                    $r->IsAutoReleased(),
                    $r->GetAutoReleaseMinutes(),
                    $r->GetMinimumLength(),
                    $r->GetResourceTypeId(),
                    $r->GetColor(),
                    $r->GetMaxConcurrentReservations()
                );
            }
        }

        return $this->BuildResourceGroupTree($_groups, $_assignments, $resourceFilter);
    }

    /**
     * Gets the resource ids that the user has permissions to
     */
    public function GetUserResourcePermissions($userId, $resourceIds = [])
    {
        $command = new GetUserPermissionsCommand($userId);
        $reader = ServiceLocator::GetDatabase()->Query($command);

        while ($row = $reader->GetRow()) {
            $resourceId = $row[ColumnNames::RESOURCE_ID];

            if (!array_key_exists($resourceId, $resourceIds)) {
                $resourceIds[$resourceId] = $resourceId;
            }
        }

        $reader->Free();

        return $resourceIds;
    }

    /**
     * Gets the resource ids that the user groups have permissions to
     */
    public function GetUserGroupResourcePermissions($userId, $resourceIds = [])
    {
        $command = new SelectUserGroupPermissions($userId);
        $reader = ServiceLocator::GetDatabase()->Query($command);

        while ($row = $reader->GetRow()) {
            $resourceId = $row[ColumnNames::RESOURCE_ID];

            if (!array_key_exists($resourceId, $resourceIds)) {
                $resourceIds[$resourceId] = $resourceId;
            }
        }
        $reader->Free();

        return $resourceIds;
    }

    /**
     * Gets the resource ids that are under the responsability of the given resource user groups
     */
    public function GetResourceAdminResourceIds($userId, $resourceIds = [])
    {
        $userSession = ServiceLocator::GetUserSession();
        if (is_null($userSession)) {
            return $resourceIds;
        }
        if ($userSession->IsResourceAdmin) {
            $command = new GetResourceAdminResourcesCommand($userId);
            $reader = ServiceLocator::GetDatabase()->Query($command);

            while ($row = $reader->GetRow()) {
                $resourceId = $row[ColumnNames::RESOURCE_ID];

                if (!array_key_exists($resourceId, $resourceIds)) {
                    $resourceIds[$resourceId] = $resourceId;
                }
            }
            $reader->Free();
        }
        return $resourceIds;
    }

    /**
     * Gets the resource ids that are under the responsability of the given schedule user groups
     */
    public function GetScheduleAdminResourceIds($userId, $resourceIds = [])
    {
        $userSession = ServiceLocator::GetUserSession();
        if (is_null($userSession)) {
            return $resourceIds;
        }
        if ($userSession->IsScheduleAdmin) {
            $command = new GetScheduleAdminResourcesCommand($userId);
            $reader = ServiceLocator::GetDatabase()->Query($command);

            while ($row = $reader->GetRow()) {
                $resourceId = $row[ColumnNames::RESOURCE_ID];

                if (!array_key_exists($resourceId, $resourceIds)) {
                    $resourceIds[$resourceId] = $resourceId;
                }
            }
            $reader->Free();
        }

        return $resourceIds;
    }

    /**
     * @param $groups ResourceGroup[]
     * @param $assignments ResourceGroupAssignment[]
     * @param $resourceFilter IResourceFilter|null
     * @return ResourceGroupTree
     */
    private function BuildResourceGroupTree($groups, $assignments, $resourceFilter)
    {
        $tree = new ResourceGroupTree();

        foreach ($groups as $g) {
            $tree->AddGroup($g);
        }

        foreach ($assignments as $assignment) {
            if ($resourceFilter == null || $resourceFilter->ShouldInclude($assignment)) {
                $tree->AddAssignment($assignment);
            }
        }

        return $tree;
    }

    public function AddResourceToGroup($resourceId, $groupId)
    {
        ServiceLocator::GetDatabase()->Execute(new AddResourceToGroupCommand($resourceId, $groupId));
    }

    public function RemoveResourceFromGroup($resourceId, $groupId)
    {
        ServiceLocator::GetDatabase()->Execute(new RemoveResourceFromGroupCommand($resourceId, $groupId));
    }

    public function AddResourceGroup(ResourceGroup $group)
    {
        $id = ServiceLocator::GetDatabase()->ExecuteInsert(new AddResourceGroupCommand($group->name, $group->parent_id));

        $group->WithId($id);

        return $group;
    }

    public function LoadResourceGroup($groupId)
    {
        return $this->LoadResourceGroupByCommand(new GetResourceGroupCommand($groupId));
    }

    public function LoadResourceGroupByPublicId($publicResourceGroupId)
    {
        return $this->LoadResourceGroupByCommand(new GetResourceGroupByPublicIdCommand($publicResourceGroupId));
    }

    private function LoadResourceGroupByCommand(SqlCommand $command)
    {
        $rows = ServiceLocator::GetDatabase()->Query($command);

        if ($row = $rows->GetRow()) {
            return new ResourceGroup(
                $row[ColumnNames::RESOURCE_GROUP_ID],
                $row[ColumnNames::RESOURCE_GROUP_NAME],
                $row[ColumnNames::RESOURCE_GROUP_PARENT_ID]
            );
        }

        return null;
    }

    public function UpdateResourceGroup(ResourceGroup $group)
    {
        ServiceLocator::GetDatabase()->Execute(new UpdateResourceGroupCommand($group->id, $group->name, $group->parent_id));
    }

    public function DeleteResourceGroup($groupId)
    {
        ServiceLocator::GetDatabase()->Execute(new DeleteResourceGroupCommand($groupId));
    }

    public function GetResourceTypes()
    {
        $types = [];

        $reader = ServiceLocator::GetDatabase()
                                ->Query(new GetAllResourceTypesCommand());

        while ($row = $reader->GetRow()) {
            $types[] = new ResourceType(
                $row[ColumnNames::RESOURCE_TYPE_ID],
                $row[ColumnNames::RESOURCE_TYPE_NAME],
                $row[ColumnNames::RESOURCE_TYPE_DESCRIPTION],
                $row[ColumnNames::ATTRIBUTE_LIST]
            );
        }

        $reader->Free();

        return $types;
    }

    public function LoadResourceType($resourceTypeId)
    {
        $resourceType = null;
        $reader = ServiceLocator::GetDatabase()->Query(new GetResourceTypeCommand($resourceTypeId));
        if ($row = $reader->GetRow()) {
            $resourceType = new ResourceType(
                $row[ColumnNames::RESOURCE_TYPE_ID],
                $row[ColumnNames::RESOURCE_TYPE_NAME],
                $row[ColumnNames::RESOURCE_TYPE_DESCRIPTION]
            );

            $getAttributes = new GetAttributeValuesCommand($resourceTypeId, CustomAttributeCategory::RESOURCE_TYPE);
            $attributeReader = ServiceLocator::GetDatabase()->Query($getAttributes);

            while ($attributeRow = $attributeReader->GetRow()) {
                $resourceType->WithAttribute(new AttributeValue($attributeRow[ColumnNames::ATTRIBUTE_ID], $attributeRow[ColumnNames::ATTRIBUTE_VALUE]));
            }

            $attributeReader->Free();
        }

        $reader->Free();
        return $resourceType;
    }

    public function AddResourceType(ResourceType $type)
    {
        return ServiceLocator::GetDatabase()->ExecuteInsert(new AddResourceTypeCommand($type->Name(), $type->Description()));
    }

    public function UpdateResourceType(ResourceType $type)
    {
        $db = ServiceLocator::GetDatabase();
        $db->Execute(new UpdateResourceTypeCommand($type->Id(), $type->Name(), $type->Description()));

        foreach ($type->GetRemovedAttributes() as $removed) {
            $db->Execute(new RemoveAttributeValueCommand($removed->AttributeId, $type->Id()));
        }

        foreach ($type->GetAddedAttributes() as $added) {
            $db->Execute(new AddAttributeValueCommand(
                $added->AttributeId,
                $added->Value,
                $type->Id(),
                CustomAttributeCategory::RESOURCE_TYPE
            ));
        }
    }

    public function RemoveResourceType($id)
    {
        ServiceLocator::GetDatabase()->Execute(new DeleteResourceTypeCommand($id));
    }

    public function GetStatusReasons()
    {
        $reasons = [];

        $reader = ServiceLocator::GetDatabase()->Query(new GetAllResourceStatusReasonsCommand());

        while ($row = $reader->GetRow()) {
            $reasons[] = new ResourceStatusReason(
                $row[ColumnNames::RESOURCE_STATUS_REASON_ID],
                $row[ColumnNames::RESOURCE_STATUS_ID],
                $row[ColumnNames::RESOURCE_STATUS_DESCRIPTION]
            );
        }

        $reader->Free();

        return $reasons;
    }

    public function AddStatusReason($statusId, $reasonDescription)
    {
        return ServiceLocator::GetDatabase()->ExecuteInsert(new AddResourceStatusReasonCommand(
            $statusId,
            $reasonDescription
        ));
    }


    public function UpdateStatusReason($reasonId, $reasonDescription)
    {
        ServiceLocator::GetDatabase()->Execute(new UpdateResourceStatusReasonCommand($reasonId, $reasonDescription));
    }

    public function RemoveStatusReason($reasonId)
    {
        ServiceLocator::GetDatabase()->Execute(new DeleteResourceStatusReasonCommand($reasonId));
    }

    public function GetUsersWithPermission(
        $resourceId,
        $pageNumber = null,
        $pageSize = null,
        $filter = null,
        $accountStatus = AccountStatus::ACTIVE
    ) {
        $command = new GetResourceUserPermissionCommand($resourceId, $accountStatus);

        if ($filter != null) {
            $command = new FilterCommand($command, $filter);
        }

        $builder = ['UserPermissionItemView', 'Create'];
        return PageableDataStore::GetList($command, $builder, $pageNumber, $pageSize);
    }

    public function GetGroupsWithPermission($resourceId, $pageNumber = null, $pageSize = null, $filter = null)
    {
        $command = new GetResourceGroupPermissionCommand($resourceId);

        if ($filter != null) {
            $command = new FilterCommand($command, $filter);
        }

        $builder = ['GroupPermissionItemView', 'Create'];
        return PageableDataStore::GetList($command, $builder, $pageNumber, $pageSize);
    }

    public function GetUsersWithPermissionsIncludingGroups(
        $resourceId,
        $pageNumber = null,
        $pageSize = null,
        $filter = null,
        $accountStatus = AccountStatus::ACTIVE
    ) {
        $command = new GetResourceUserGroupPermissionCommand($resourceId, $accountStatus);

        if ($filter != null) {
            $command = new FilterCommand($command, $filter);
        }

        $builder = ['UserPermissionItemView', 'Create'];
        return PageableDataStore::GetList($command, $builder, $pageNumber, $pageSize);
    }

    public function ChangeResourceGroupPermission($resourceId, $groupId, $type)
    {
        ServiceLocator::GetDatabase()->Execute(new DeleteGroupResourcePermission($groupId, $resourceId));
        if ($type != ResourcePermissionType::None) {
            ServiceLocator::GetDatabase()->Execute(new AddGroupResourcePermission($groupId, $resourceId, $type));
        }
    }

    public function ChangeResourceUserPermission($resourceId, $userId, $type)
    {
        ServiceLocator::GetDatabase()->Execute(new DeleteUserResourcePermission($userId, $resourceId));
        if ($type != ResourcePermissionType::None) {
            ServiceLocator::GetDatabase()->Execute(new AddUserResourcePermission($userId, $resourceId, $type));
        }
    }

    public function GetPublicResourceIds()
    {
        $ids = [];
        $command = new GetResourcesPublicCommand();
        $reader = ServiceLocator::GetDatabase()->Query($command);
        while ($row = $reader->GetRow()) {
            $ids[$row[ColumnNames::RESOURCE_ID]] = $row[ColumnNames::PUBLIC_ID];
        }

        $reader->Free();

        return $ids;
    }
}

class AccessoryDto
{
    /**
     * @var int
     */
    public $Id;

    /**
     * @var string
     */
    public $Name;

    /**
     * @var int
     */
    public $QuantityAvailable;

    /**
     * @var int
     */
    public $AssociatedResources;

    /**
     * @param int $id
     * @param string $name
     * @param int $quantityAvailable
     * @param int $associatedResourceCount
     */
    public function __construct($id, $name, $quantityAvailable, $associatedResourceCount)
    {
        $this->Id = $id;
        $this->Name = $name;
        $this->QuantityAvailable = $quantityAvailable;
        $this->AssociatedResources = (int)$associatedResourceCount;
    }

    public static function Create($row)
    {
        return new AccessoryDto(
            $row[ColumnNames::ACCESSORY_ID],
            $row[ColumnNames::ACCESSORY_NAME],
            $row[ColumnNames::ACCESSORY_QUANTITY],
            $row[ColumnNames::ACCESSORY_RESOURCE_COUNT]
        );
    }
}

interface IResourceFilter
{
    /**
     * @param IResource $resource
     * @return bool
     */
    public function ShouldInclude($resource);
}

class ResourceDto implements IBookableResource
{
    /**
     * @param int $id
     * @param string $name
     * @param bool $canAccess
     * @param bool $canBook
     * @param int $scheduleId
     * @param TimeInterval $minLength
     * @param int|null $resourceTypeId
     * @param int|null $adminGroupId
     * @param int|null $scheduleAdminGroupId
     * @param int|null $statusId
     * @param bool $requiresApproval
     * @param bool $isCheckInEnabled
     * @param bool $isAutoReleased
     * @param int|null $autoReleaseMinutes
     * @param string|null $color
     * @param int|null $maxConcurrentReservations
     */
    public function __construct(
        $id,
        $name,
        $canAccess,
        $canBook,
        $scheduleId,
        $minLength,
        $resourceTypeId,
        $adminGroupId,
        $scheduleAdminGroupId,
        $statusId,
        $requiresApproval,
        $isCheckInEnabled,
        $isAutoReleased,
        $autoReleaseMinutes,
        $color,
        $maxConcurrentReservations
    ) {
        $this->Id = $id;
        $this->Name = $name;
        $this->CanAccess = $canAccess;
        $this->CanBook = $canBook;
        $this->ScheduleId = $scheduleId;
        $this->MinimumLength = $minLength;
        $this->ResourceTypeId = $resourceTypeId;
        $this->AdminGroupId = $adminGroupId;
        $this->ScheduleAdminGroupId = $scheduleAdminGroupId;
        $this->StatusId = $statusId;
        $this->RequiresApproval = $requiresApproval;
        $this->IsCheckInEnabled = $isCheckInEnabled;
        $this->IsAutoReleased = $isAutoReleased;
        $this->AutoReleaseMinutes = $autoReleaseMinutes;
        $this->Color = $color;
        $this->TextColor = '';
        if (!empty($color)) {
            $textColor = new ContrastingColor($color);
            $this->TextColor = $textColor->__toString();
        }
        $this->MaxConcurrentReservations = empty($maxConcurrentReservations) ? 1 : $maxConcurrentReservations;
    }

    /**
     * @var int
     */
    public $Id;

    /**
     * @var string
     */
    public $Name;

    /**
     * @var bool
     */
    public $CanAccess;

    /**
     * @var bool
     */
    public $CanBook;

    /**
     * @var null|int
     */
    public $ScheduleId;

    /**
     * @var null|TimeInterval
     */
    public $MinimumLength;

    /**
     * @var int|null
     */
    public $ResourceTypeId;

    /**
     * @var int|null
     */
    public $AdminGroupId;
    /**
     * @var int|null
     */
    public $ScheduleAdminGroupId;
    /**
     * @var int|null
     */
    public $StatusId;
    /**
     * @var bool
     */
    public $RequiresApproval;

    /**
     * @var bool
     */
    public $IsCheckInEnabled;

    /**
     * @var bool
     */
    public $IsAutoReleased;

    /**
     * @var int|null
     */
    public $AutoReleaseMinutes;

    /**
     * @var string|null
     */
    public $Color;

    /**
     * @var string|null
     */
    public $TextColor;

    /**
     * @var int
     */
    public $MaxConcurrentReservations;

    /**
     * alias of GetId()
     * @return int
     */
    public function GetResourceId()
    {
        return $this->Id;
    }

    /**
     * @return int
     */
    public function GetId()
    {
        return $this->Id;
    }

    /**
     * @return string
     */
    public function GetName()
    {
        return $this->Name;
    }

    /**
     * @return int|null
     */
    public function GetScheduleId()
    {
        return $this->ScheduleId;
    }

    /**
     * @return null|TimeInterval
     */
    public function GetMinimumLength()
    {
        return $this->MinimumLength;
    }

    /**
     * @return int|null
     */
    public function GetResourceType()
    {
        return $this->ResourceTypeId;
    }

    /**
     * @return int|null
     */
    public function GetResourceTypeId()
    {
        return $this->GetResourceType();
    }

    /**
     * @return int
     */
    public function GetAdminGroupId()
    {
        return $this->AdminGroupId;
    }

    /**
     * @return int
     */
    public function GetScheduleAdminGroupId()
    {
        return $this->ScheduleAdminGroupId;
    }

    /**
     * @return int|null
     */
    public function GetStatusId()
    {
        return $this->StatusId;
    }

    /**
     * @return bool
     */
    public function GetRequiresApproval()
    {
        return $this->RequiresApproval;
    }

    /**
     * @return bool
     */
    public function IsCheckInEnabled()
    {
        return $this->IsCheckInEnabled;
    }

    /**
     * @return bool
     */
    public function IsAutoReleased()
    {
        return $this->IsAutoReleased;
    }

    /**
     * @return null|int
     */
    public function GetAutoReleaseMinutes()
    {
        return $this->AutoReleaseMinutes;
    }

    /**
     * @return null|string
     */
    public function GetColor()
    {
        return $this->Color;
    }

    /**
     * @return null|string
     */
    public function GetTextColor()
    {
        return $this->TextColor;
    }

    /**
     * @return bool
     */
    public function HasColor()
    {
        return $this->Color != '' && $this->Color != null;
    }

    /**
     * @return bool
     */
    public function GetAllowConcurrentReservations()
    {
        return $this->GetMaxConcurrentReservations() > 1;
    }

    /**
     * @return int
     */
    public function GetMaxConcurrentReservations()
    {
        return max($this->MaxConcurrentReservations, 1);
    }
}

class NullResourceDto extends ResourceDto
{
    public function __construct()
    {
        parent::__construct(0, null, false, false, 0, new TimeInterval(0), null, null, null, null, false, false, false, null, null, null);
    }
}
