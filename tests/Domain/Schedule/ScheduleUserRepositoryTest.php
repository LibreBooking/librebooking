<?php

declare(strict_types=1);

class ScheduleUserRepositoryTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testPullsAllResourcesAndGroupsForUser()
    {
        $userId = 10;

        $userResourceRoles = [
            [ColumnNames::USER_ID => $userId, ColumnNames::RESOURCE_ID => 1, ColumnNames::RESOURCE_NAME => 'r1', ColumnNames::PERMISSION_TYPE => 0],
            [ColumnNames::USER_ID => $userId, ColumnNames::RESOURCE_ID => 2, ColumnNames::RESOURCE_NAME => 'r2', ColumnNames::PERMISSION_TYPE => 0],
            [ColumnNames::USER_ID => $userId, ColumnNames::RESOURCE_ID => 3, ColumnNames::RESOURCE_NAME => 'r3', ColumnNames::PERMISSION_TYPE => 0],
        ];

        $groupResourceRoles = [
            [ColumnNames::GROUP_ID => 200, ColumnNames::RESOURCE_ID => 2, ColumnNames::RESOURCE_NAME => 'r2', ColumnNames::PERMISSION_TYPE => 0],
            [ColumnNames::GROUP_ID => 100, ColumnNames::RESOURCE_ID => 3, ColumnNames::RESOURCE_NAME => 'r3', ColumnNames::PERMISSION_TYPE => 0],
            [ColumnNames::GROUP_ID => 100, ColumnNames::RESOURCE_ID => 4, ColumnNames::RESOURCE_NAME => 'r4', ColumnNames::PERMISSION_TYPE => 0],
            [ColumnNames::GROUP_ID => 200, ColumnNames::RESOURCE_ID => 5, ColumnNames::RESOURCE_NAME => 'r5', ColumnNames::PERMISSION_TYPE => 0],
        ];

        $groupAdminResources = [
            [ColumnNames::RESOURCE_ID => 6, ColumnNames::RESOURCE_NAME => 'r6'],
        ];

        $this->db->SetRow(0, $userResourceRoles);
        $this->db->SetRow(1, $groupResourceRoles);
        $this->db->SetRow(2, $groupAdminResources);

        $repo = new ScheduleUserRepository();
        $user = $repo->GetUser($userId);

        $userPermissionsCommand = new GetUserPermissionsCommand($userId);
        $groupPermissionsCommand = new SelectUserGroupPermissions($userId);

        $this->assertEquals(3, count($this->db->_Commands));
        $this->assertTrue($this->db->ContainsCommand($userPermissionsCommand));
        $this->assertTrue($this->db->ContainsCommand($groupPermissionsCommand));

        $this->assertEquals(6, count($user->GetAllResources()), 'excludes the dupes');
    }

    public function testGetsAllUniqueResourcesForUserAndGroup()
    {
        $userId = 99;

        $rid1 = 1;
        $rid2 = 2;
        $r1 = new ScheduleResource($rid1, 'resource 1');
        $r2 = new ScheduleResource($rid2, 'resource 2');
        $resources = [$r1, $r2];

        $rid3 = 3;
        $rid4 = 4;
        $r3 = new ScheduleResource($rid3, 'resource 3');
        $r4 = new ScheduleResource($rid4, 'resource 4');
        $r5 = new ScheduleResource(5, 'resource 5');
        $r6 = new ScheduleResource(6, 'resource 6');

        $g1 = new ScheduleGroup(100, [$r1, $r3], [$r5]);
        $g2 = new ScheduleGroup(200, [$r1, $r4, $r3], [$r6]);
        $groupPermissions = [$g1, $g2];

        $view = [$r5];

        $user = new ScheduleUser($userId, $resources, $view, $groupPermissions, []);

        $permittedResources = $user->GetAllResources();

        $this->assertEquals(6, count($permittedResources));
        $this->assertContains($r1, $permittedResources);
        $this->assertContains($r2, $permittedResources);
        $this->assertContains($r3, $permittedResources);
        $this->assertContains($r4, $permittedResources);
        $this->assertContains($r5, $permittedResources);
        $this->assertContains($r6, $permittedResources);

        $bookable = $user->GetBookableResources();
        $this->assertEquals(4, count($bookable));

        $viewable = $user->GetViewOnlyResources();
        $this->assertEquals(2, count($viewable));
    }
}
