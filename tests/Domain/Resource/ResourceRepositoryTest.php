<?php

require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'tests/fakes/namespace.php');

class ResourceRepositoryTest extends TestBase
{
    /**
     * @var ResourceRepository
     */
    private $repository;

    public function setUp(): void
    {
        $this->repository = new ResourceRepository();
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testCanGetAllResourcesForASchedule()
    {
        $expected = [];
        $scheduleId = 10;

        $ra = new FakeResourceAccess();
        $rows = $ra->Rows();
        $this->db->SetRow(0, $rows);

        foreach ($rows as $row) {
            $expected[] = BookableResource::Create($row);
        }

        $resourceAccess = new ResourceRepository();
        $resources = $resourceAccess->GetScheduleResources($scheduleId);

        $filter = new SqlFilterEquals(new SqlFilterColumn('r', ColumnNames::SCHEDULE_ID), $scheduleId);
        $filter = $filter->_And(new SqlFilterNotEquals(new SqlFilterColumn('r', ColumnNames::RESOURCE_STATUS_ID), ResourceStatus::HIDDEN));
        $this->assertEquals(new FilterCommand(new GetAllResourcesCommand(), $filter), $this->db->_Commands[0]);
        $this->assertTrue($this->db->GetReader(0)->_FreeCalled);
        $this->assertEquals(count($rows), count($resources));
        $this->assertEquals($expected, $resources);
    }

    public function testCanUpdateResource()
    {
        $id = 8383;
        $name = "name";
        $location = "location";
        $contact = "contact";
        $notes = "notes";
        $minLength = 720;
        $maxLength = 727272;
        $autoAssign = 1;
        $requiresApproval = 0;
        $allowMultiday = 1;
        $maxParticipants = 100;
        $minNoticeAdd = 11111;
        $maxNotice = 22222;
        $description = "description";
        $scheduleId = 19819;
        $imageName = 'something.png';
        $adminGroupId = 232;
        $allowSubscription = true;
        $sortOrder = 3;
        $resourceTypeId = 111;
        $reasonId = 19;
        $bufferTime = 88881;
        $color = '#cccccc';
        $enableCheckin = true;
        $autoReleaseMinutes = 40;
        $credits = 100;
        $peakCredits = 200;
        $minNoticeUpdate = 19291;
        $minNoticeDelete = 9919;

        $resource = new BookableResource(
            $id,
            $name,
            $location,
            $contact,
            $notes,
            $minLength,
            $maxLength,
            $autoAssign,
            $requiresApproval,
            $allowMultiday,
            $maxParticipants,
            $minNoticeAdd,
            $maxNotice,
            $description,
            $scheduleId
        );
        $resource->SetImage($imageName);
        $resource->ChangeStatus(ResourceStatus::AVAILABLE, $reasonId);
        $resource->SetAdminGroupId($adminGroupId);
        $resource->EnableSubscription();
        $resource->SetSortOrder($sortOrder);
        $resource->SetResourceTypeId($resourceTypeId);
        $resource->SetBufferTime($bufferTime);
        $resource->SetColor($color);
        $resource->SetCheckin($enableCheckin, $autoReleaseMinutes);
        $resource->SetCreditsPerSlot($credits);
        $resource->SetPeakCreditsPerSlot($peakCredits);
        $resource->SetMinNoticeUpdate($minNoticeUpdate);
        $resource->SetMinNoticeDelete($minNoticeDelete);

        $publicId = $resource->GetPublicId();

        $this->repository->Update($resource);

        $expectedUpdateResourceCommand = new UpdateResourceCommand(
            $id,
            $name,
            $location,
            $contact,
            $notes,
            new TimeInterval($minLength),
            new TimeInterval($maxLength),
            $autoAssign,
            $requiresApproval,
            $allowMultiday,
            $maxParticipants,
            new TimeInterval($minNoticeAdd),
            new TimeInterval($maxNotice),
            $description,
            $imageName,
            $scheduleId,
            $adminGroupId,
            $allowSubscription,
            $publicId,
            $sortOrder,
            $resourceTypeId,
            ResourceStatus::AVAILABLE,
            $reasonId,
            new TimeInterval($bufferTime),
            '#cccccc',
            $enableCheckin,
            $autoReleaseMinutes,
            true,
            $credits,
            $peakCredits,
            new TimeInterval($minNoticeUpdate),
            new TimeInterval($minNoticeDelete),
            ResourceProperties::FromResource($resource)->Serialize()
        );

        $actualUpdateResourceCommand = $this->db->_Commands[0];

        $this->assertEquals($expectedUpdateResourceCommand, $actualUpdateResourceCommand);
    }

    public function testUpdatingAutoAssignToTrueAssignsPermissionsOnUpdate()
    {
        $resourceId = 123;

        $resource = new FakeBookableResource($resourceId);
        $resource->SetAutoAssign('0');
        $resource->SetAutoAssign('1');
        $resource->SetClearAllPermissions(true);

        $this->repository->Update($resource);

        $this->assertTrue($this->db->ContainsCommand(new AutoAssignResourcePermissionsCommand($resourceId)));
        $this->assertTrue($this->db->ContainsCommand(new AutoAssignClearResourcePermissionsCommand($resourceId)));
    }

    public function testCanAddResourceWithMinimumAttributes()
    {
        $name = "name";
        $scheduleId = 828;
        $resourceId = 8888;
        $autoAssign = true;
        $groupId = 111;

        $resource = BookableResource::CreateNew($name, $scheduleId, $autoAssign);
        $resource->SetAdminGroupId($groupId);

        $this->db->_ExpectedInsertId = $resourceId;

        $this->repository->Add($resource);

        $expectedAddCommand = new AddResourceCommand($name, $scheduleId, $autoAssign, $groupId);
        $assignResourcePermissions = new AutoAssignResourcePermissionsCommand($resourceId);
        $actualAddResourceCommand = $this->db->_Commands[0];
        $actualAssignResourcePermissions = $this->db->_Commands[1];

        $this->assertEquals($expectedAddCommand, $actualAddResourceCommand);
        $this->assertEquals($assignResourcePermissions, $actualAssignResourcePermissions);
    }

    public function testDeletingAResourceRemovesAllAssociatedData()
    {
        $resourceId = 100;
        $resource = BookableResource::CreateNew('name', 1);
        $resource->SetResourceId($resourceId);

        $this->repository->Delete($resource);

        $deleteReservations = new DeleteResourceReservationsCommand($resourceId);
        $deleteResources = new DeleteResourceCommand($resourceId);

        $actualDeleteReservations = $this->db->_Commands[0];
        $actualDeleteResources = $this->db->_Commands[1];

        $this->assertEquals($deleteReservations, $actualDeleteReservations);
        $this->assertEquals($deleteResources, $actualDeleteResources);
    }

    public function testGetsAccessories()
    {
        $ar = new ReservationAccessoryRow();
        $ar
        ->WithAccessory(1, 3, "name", 3)
        ->WithAccessory(2, 23, "slkjdf", 3);

        $this->db->SetRows($ar->Rows());

        $getAccessoriesCommand = new GetAllAccessoriesCommand();

        /** @var AccessoryDto[] $accessories */
        $accessories = $this->repository->GetAccessoryList();

        $this->assertEquals($getAccessoriesCommand, $this->db->_LastCommand);
        $this->assertEquals(2, count($accessories));
        $this->assertEquals(1, $accessories[0]->Id);
        $this->assertEquals("name", $accessories[0]->Name);
        $this->assertEquals(3, $accessories[0]->QuantityAvailable);
    }

    public function testLoadsResourceByPublicId()
    {
        $publicId = uniqid();

        $fr = new FakeResourceAccess();
        $rows = $fr->Rows();
        $this->db->SetRow(0, $rows);

        $car = new CustomAttributeValueRow();
        $car
        ->With(1, 'value')
        ->With(2, 'value2');
        $this->db->SetRow(1, $car->Rows());
        $loadResourceCommand = new GetResourceByPublicIdCommand($publicId);
        $attributes = new GetAttributeValuesCommand(1, CustomAttributeCategory::RESOURCE);

        $resource = $this->repository->LoadByPublicId($publicId);

        $this->assertTrue($this->db->ContainsCommand($loadResourceCommand));
        $this->assertTrue($this->db->ContainsCommand($attributes));
        $this->assertNotNull($resource);
        $this->assertEquals('value', $resource->GetAttributeValue(1));
        $this->assertEquals('value2', $resource->GetAttributeValue(2));
    }

    public function testLoadsResourceById()
    {
        $id = 1;

        $fr = new FakeResourceAccess();
        $rows = $fr->Rows();
        $this->db->SetRow(0, $rows);

        $car = new CustomAttributeValueRow();
        $car
        ->With(1, 'value')
        ->With(2, 'value2');
        $this->db->SetRow(1, $car->Rows());
        $this->db->SetRow(2, [ [ColumnNames::RESOURCE_ID => $id, ColumnNames::RESOURCE_GROUP_ID => 1]]);

        $loadResourceCommand = new GetResourceByIdCommand($id);
        $attributes = new GetAttributeValuesCommand(1, CustomAttributeCategory::RESOURCE);
        $groups = new GetResourceGroupAssignmentsCommand($id);

        $resource = $this->repository->LoadById($id);

        $this->assertTrue($this->db->ContainsCommand($loadResourceCommand));
        $this->assertTrue($this->db->ContainsCommand($attributes));
        $this->assertTrue($this->db->ContainsCommand($groups));
        $this->assertNotNull($resource);
        $this->assertEquals('value', $resource->GetAttributeValue(1));
        $this->assertEquals('value2', $resource->GetAttributeValue(2));
    }

    public function testUpdatesAttributes()
    {
        $id = 11;
        $unchanged = new AttributeValue(1, 'value');
        $toChange = new AttributeValue(2, 'value');
        $toAdd = new AttributeValue(3, 'value');

        $resource = new FakeBookableResource($id);
        $resource->WithAttribute($unchanged);
        $resource->WithAttribute(new AttributeValue(100, 'should be removed'));
        $resource->WithAttribute(new AttributeValue(2, 'new value'));

        $attributes = [$unchanged, $toChange, $toAdd];
        $resource->ChangeAttributes($attributes);

        $this->repository->Update($resource);

        $addNewCommand = new AddAttributeValueCommand($toAdd->AttributeId, $toAdd->Value, $id, CustomAttributeCategory::RESOURCE);
        $removeOldCommand = new RemoveAttributeValueCommand(100, $id);
        $removeUpdated = new RemoveAttributeValueCommand($toChange->AttributeId, $id);
        $addUpdated = new AddAttributeValueCommand($toChange->AttributeId, $toChange->Value, $id, CustomAttributeCategory::RESOURCE);

        $this->assertEquals($removeOldCommand, $this->db->_Commands[1]);
        $this->assertEquals(
            $removeUpdated,
            $this->db->_Commands[2],
            "need to remove before adding to make sure changed values are not immediately deleted"
        );
        $this->assertEquals($addUpdated, $this->db->_Commands[3]);
        $this->assertEquals($addNewCommand, $this->db->_Commands[4]);
    }

    public function testGetsResourceGroups()
    {
        $scheduleId = 123;

        $groupRows = new ResourceGroupRow();
        $groupRows
        ->With(1, 'group1')
        ->With(2, 'group1a', 1)
        ->With(3, 'group1a1', 2)
        ->With(4, 'group2')
        ->With(5, 'group2a', 4)
        ->With(6, 'group3')
        ->With(7, 'group1b', 1);

        $assignmentRows = new ResourceGroupAssignmentRow();
        $assignmentRows
        ->With(1, 'resource1', 3)
        ->With(2, 'resource2', 3)
        ->With(3, 'resource3', 4)
        ->With(4, 'resource4', 5)
        ->With(5, 'resource5', 5);

        $resourceRows = new FakeResourceAccess();
        $resourceRows->With(1, 'resource1')
                ->With(2, 'resource2')
                ->With(3, 'resource3')
                ->With(4, 'resource4')
                ->With(5, 'resource5');

        $this->db->SetRow(0, $groupRows->Rows());
        $this->db->SetRow(1, $assignmentRows->Rows());
        $this->db->SetRow(2, $resourceRows->Rows());

        $resourceGroupTree = $this->repository->GetResourceGroups($scheduleId, new SkipResource5Filter());
        $groups = $resourceGroupTree->GetGroups();
        $groupList = $resourceGroupTree->GetGroupList(false);

        $getResourceGroupsCommand = new GetAllResourceGroupsCommand();
        $getResourceGroupAssignments = new GetAllResourceGroupAssignmentsCommand($scheduleId);

        $this->assertEquals(4, count($groups));

        $this->assertEquals(0, $groups[0]->id, 'should have added an "all" group');
        $this->assertEquals('group1', $groups[1]->label);
        $this->assertEquals(1, $groups[1]->id);
        $this->assertEquals(null, $groups[1]->parent_id);
        $this->assertEquals(2, count($groups[1]->children));

        $this->assertEquals('group1a', $groups[1]->children[0]->label);
        $this->assertEquals('group1a1', $groups[1]->children[0]->children[0]->label);
        $this->assertEquals('resource1', $groups[1]->children[0]->children[0]->children[0]->label);
        $this->assertEquals('resource2', $groups[1]->children[0]->children[0]->children[1]->label);
        $this->assertEquals('resource3', $groups[2]->children[1]->label);

        $this->assertEquals($getResourceGroupsCommand, $this->db->_Commands[0]);
        $this->assertEquals($getResourceGroupAssignments, $this->db->_Commands[1]);

        $this->assertEquals(count($groupRows->Rows()), count($groupList));
        $this->assertEquals('group1a1', $groupList[3]->name);
    }

    public function testAddsResourceToGroup()
    {
        $resourceId = 189282;
        $groupId = 100;

        $this->repository->AddResourceToGroup($resourceId, $groupId);

        $expectedCommand = new AddResourceToGroupCommand($resourceId, $groupId);
        $this->assertEquals($expectedCommand, $this->db->_LastCommand);
    }

    public function testRemovesResourceFromGroup()
    {
        $resourceId = 189282;
        $groupId = 100;

        $this->repository->RemoveResourceFromGroup($resourceId, $groupId);

        $expectedCommand = new RemoveResourceFromGroupCommand($resourceId, $groupId);
        $this->assertEquals($expectedCommand, $this->db->_LastCommand);
    }

    public function testAddsResourceGroup()
    {
        $name = 'gn';
        $parentId = 123;

        $group = ResourceGroup::Create($name, $parentId);

        $id = 99292;

        $this->db->_ExpectedInsertId = $id;

        $addedGroup = $this->repository->AddResourceGroup($group);

        $expectedCommand = new AddResourceGroupCommand($name, $parentId);
        $this->assertEquals($expectedCommand, $this->db->_LastCommand);

        $this->assertEquals($id, $addedGroup->id);
    }

    public function testUpdatesResourceGroup()
    {
        $id = 123;
        $parentId = 999;
        $newParentId = 988;

        $group = new ResourceGroup($id, 'name', $parentId);
        $group->MoveTo($newParentId);

        $this->repository->UpdateResourceGroup($group);

        $expectedCommand = new UpdateResourceGroupCommand($id, 'name', $newParentId);
        $this->assertEquals($expectedCommand, $this->db->_LastCommand);
    }

    public function testDeletesResourceGroup()
    {
        $id = 123;
        $this->repository->DeleteResourceGroup($id);

        $expectedCommand = new DeleteResourceGroupCommand($id);
        $this->assertEquals($expectedCommand, $this->db->_LastCommand);
    }

    public function testGetsAllResourceTypes()
    {
        $rows = new ResourceTypeRow();
        $rows
        ->With(1, 'resourcetype1', 'description')
        ->With(2, 'resourcetype2', null)
        ->With(3, 'resourcetype3', '');

        $this->db->SetRows($rows->Rows());

        /** @var ResourceType[] $types */
        $types = $this->repository->GetResourceTypes();

        $this->assertEquals(3, count($types));
        $this->assertEquals(1, $types[0]->Id());
        $this->assertEquals('resourcetype1', $types[0]->Name());
        $this->assertEquals('description', $types[0]->Description());
        $this->assertEquals('a', $types[0]->GetAttributeValue(1));

        $expectedCommand = new GetAllResourceTypesCommand();
        $this->assertEquals($expectedCommand, $this->db->_LastCommand);
    }

    public function testAddsNewResourceType()
    {
        $name = 'name';
        $description = 'description';

        $type = ResourceType::CreateNew($name, $description);

        $this->repository->AddResourceType($type);

        $expectedCommand = new AddResourceTypeCommand($name, $description);
        $this->assertEquals($expectedCommand, $this->db->_LastCommand);
    }

    public function testUpdatesResourceType()
    {
        $unchanged = new AttributeValue(1, 'value');
        $toChange = new AttributeValue(2, 'value');
        $toAdd = new AttributeValue(3, 'value');

        $id = 11;
        $type = new ResourceType($id, 'name', 'desc');

        $type->WithAttribute($unchanged);
        $type->WithAttribute(new AttributeValue(100, 'should be removed'));
        $type->WithAttribute(new AttributeValue(2, 'new value'));

        $attributes = [$unchanged, $toChange, $toAdd];

        $type->ChangeAttributes($attributes);

        $this->repository->UpdateResourceType($type);

        $addNewCommand = new AddAttributeValueCommand($toAdd->AttributeId, $toAdd->Value, $id, CustomAttributeCategory::RESOURCE_TYPE);
        $removeOldCommand = new RemoveAttributeValueCommand(100, $id);
        $removeUpdated = new RemoveAttributeValueCommand($toChange->AttributeId, $id);
        $addUpdated = new AddAttributeValueCommand($toChange->AttributeId, $toChange->Value, $id, CustomAttributeCategory::RESOURCE_TYPE);
        $expectedCommand = new UpdateResourceTypeCommand($type->Id(), $type->Name(), $type->Description());

        $this->assertEquals($expectedCommand, $this->db->_Commands[0]);
        $this->assertEquals($removeOldCommand, $this->db->_Commands[1]);
        $this->assertEquals($removeUpdated, $this->db->_Commands[2], "need to remove before adding to make sure changed values are not immediately deleted");
        $this->assertEquals($addUpdated, $this->db->_Commands[3]);
        $this->assertEquals($addNewCommand, $this->db->_Commands[4]);
    }

    public function testRemovesResourceType()
    {
        $this->repository->RemoveResourceType(123);

        $expectedCommand = new DeleteResourceTypeCommand(123);
        $this->assertEquals($expectedCommand, $this->db->_LastCommand);
    }

    public function testLoadsResourceType()
    {
        $rows = new ResourceTypeRow();
        $rows->With(1, 'resourcetype1', 'description');

        $car = new CustomAttributeValueRow();
        $car
        ->With(1, 'value')
        ->With(2, 'value2');
        $this->db->SetRow(0, $rows->Rows());
        $this->db->SetRow(1, $car->Rows());

        $resourceType = $this->repository->LoadResourceType(123);

        $this->assertEquals(1, $resourceType->Id());
        $this->assertEquals('resourcetype1', $resourceType->Name());

        $this->assertTrue($this->db->ContainsCommand(new GetResourceTypeCommand(123)));
        $this->assertTrue($this->db->ContainsCommand(new GetAttributeValuesCommand(123, CustomAttributeCategory::RESOURCE_TYPE)));
        $this->assertEquals('value', $resourceType->GetAttributeValue(1));
        $this->assertEquals('value2', $resourceType->GetAttributeValue(2));
    }

    public function testGetsUsersWithPermission()
    {
        $resourceId = 123;

        $userRows = new UserRow();
        $userRows->With(1)->With(2);
        $rows = $userRows->Rows();
        $rows[0][ColumnNames::PERMISSION_TYPE] = ResourcePermissionType::Full;
        $rows[1][ColumnNames::PERMISSION_TYPE] = ResourcePermissionType::View;

        $this->db->SetRows($rows);

        $list = $this->repository->GetUsersWithPermission($resourceId);
        /** @var UserPermissionItemView[] $results */
        $results = $list->Results();

        $this->assertTrue($this->db->ContainsCommand(new GetResourceUserPermissionCommand($resourceId, AccountStatus::ACTIVE)));
        $this->assertEquals(2, $list->PageInfo()->Total);
        $this->assertEquals(1, $results[0]->Id);
        $this->assertEquals(ResourcePermissionType::Full, $results[0]->PermissionType);
        $this->assertEquals(2, $results[1]->Id);
        $this->assertEquals(ResourcePermissionType::View, $results[1]->PermissionType);
    }

    public function testGetsGroupsWithPermission()
    {
        $resourceId = 123;

        $rows = [
                [ColumnNames::GROUP_ID => 1, ColumnNames::GROUP_NAME => 'g1', ColumnNames::GROUP_ISDEFAULT => 0, ColumnNames::PERMISSION_TYPE => ResourcePermissionType::Full],
                [ColumnNames::GROUP_ID => 2, ColumnNames::GROUP_NAME => 'g2', ColumnNames::GROUP_ISDEFAULT => 0, ColumnNames::PERMISSION_TYPE => ResourcePermissionType::View],
        ];
        $this->db->SetRows($rows);

        $g1 = new GroupPermissionItemView(1, 'g1');
        $g1->PermissionType = ResourcePermissionType::Full;
        $g2 = new GroupPermissionItemView(2, 'g2');
        $g2->PermissionType = ResourcePermissionType::View;

        $list = $this->repository->GetGroupsWithPermission($resourceId);
        $results = $list->Results();

        $this->assertTrue($this->db->ContainsCommand(new GetResourceGroupPermissionCommand($resourceId)));
        $this->assertEquals(2, $list->PageInfo()->Total);
        $this->assertEquals($g1, $results[0]);
        $this->assertEquals($g2, $results[1]);
    }
}

class SkipResource5Filter implements IResourceFilter
{
    public function ShouldInclude($resource)
    {
        return $resource->GetId() != 5;
    }
}
