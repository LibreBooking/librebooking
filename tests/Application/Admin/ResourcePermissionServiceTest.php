<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Admin/ResourcePermissionService.php');

class ResourcePermissionServiceTest extends TestBase
{
    private FakeResourceRepository $resourceRepo;
    private ResourcePermissionService $service;

    public function setUp(): void
    {
        parent::setup();

        $this->resourceRepo = new FakeResourceRepository();
        $this->service = new ResourcePermissionService($this->resourceRepo);
    }

    public function testGetResourceIdsThatUserCanAdminister()
    {
        $this->resourceRepo->_ResourceList = [
            new FakeBookableResource(1),
            new FakeBookableResource(2),
            new FakeBookableResource(3),
        ];

        $adminUser = new FakeUser();
        $adminUser->_ResourceAdminResourceIds = [1, 3];
        $adminUser->_IsResourceAdmin = false;

        $result = $this->service->GetResourceIdsThatUserCanAdminister($adminUser);

        sort($result);
        $this->assertEquals([1, 3], $result);
    }

    public function testParseSubmittedPermissionsFiltersToAdminScope()
    {
        $submitted = ['1_0', '2_1', '99_0'];
        $adminManagedIds = [1, 2];

        $result = $this->service->ParseSubmittedPermissions($submitted, $adminManagedIds);

        $this->assertEquals([1], $result['full']);
        $this->assertEquals([2], $result['view']);
    }

    public function testParseSubmittedPermissionsSkipsNone()
    {
        $submitted = ['1_none', '2_0'];
        $adminManagedIds = [1, 2];

        $result = $this->service->ParseSubmittedPermissions($submitted, $adminManagedIds);

        $this->assertEquals([2], $result['full']);
        $this->assertEquals([], $result['view']);
    }

    public function testParseSubmittedPermissionsHandlesNull()
    {
        $result = $this->service->ParseSubmittedPermissions(null, [1, 2]);

        $this->assertEquals([], $result['full']);
        $this->assertEquals([], $result['view']);
    }

    public function testParseSubmittedPermissionsHandlesMalformedInput()
    {
        $submitted = ['1'];  // Missing permission type
        $adminManagedIds = [1];

        $result = $this->service->ParseSubmittedPermissions($submitted, $adminManagedIds);

        // "1" has no permission type, so it is skipped
        $this->assertEquals([], $result['full']);
        $this->assertEquals([], $result['view']);
    }

    public function testParseSubmittedPermissionsRejectsMalformedResourceIds()
    {
        // "1abc" would cast to 1 via (int), granting access to resource 1
        $submitted = ['1abc_0', '-2_0', ' 1_1', '_0', '2_0'];
        $adminManagedIds = [1, 2];

        $result = $this->service->ParseSubmittedPermissions(
            submittedPermissions: $submitted,
            managedResourceIds: $adminManagedIds
        );

        $this->assertEquals([2], $result['full']);
        $this->assertEquals([], $result['view']);
    }

    public function testParseSubmittedPermissionsRejectsExtraSegments()
    {
        $submitted = ['1_0_extra', '1_1_0', '2_1'];
        $adminManagedIds = [1, 2];

        $result = $this->service->ParseSubmittedPermissions(
            submittedPermissions: $submitted,
            managedResourceIds: $adminManagedIds
        );

        $this->assertEquals([], $result['full']);
        $this->assertEquals([2], $result['view']);
    }

    public function testParseSubmittedPermissionsRejectsInvalidPermissionType()
    {
        // "abc" would cast to 0 via (int), falsely matching Full Access;
        // only 0 (full) and 1 (view) are permission types
        $submitted = ['1_abc', '1_blah', '1_7', '1_', '2_0'];
        $adminManagedIds = [1, 2];

        $result = $this->service->ParseSubmittedPermissions($submitted, $adminManagedIds);

        $this->assertEquals([2], $result['full']);
        $this->assertEquals([], $result['view']);
    }

    public function testParseSubmittedPermissionsSkipsNonScalarEntries()
    {
        // A crafted POST can send nested arrays instead of strings
        $submitted = [['nested', 'array'], '2_0', ['0' => ['x' => '1']]];
        $adminManagedIds = [1, 2];

        $result = $this->service->ParseSubmittedPermissions($submitted, $adminManagedIds);

        $this->assertEquals([2], $result['full']);
        $this->assertEquals([], $result['view']);
    }

    public function testMergeWithPreservedPermissions()
    {
        $existingIds = [1, 5, 10];
        $adminManagedIds = [1, 2, 3];
        $submittedIds = [1, 3];

        $result = $this->service->MergeWithPreservedPermissions(
            existingIds: $existingIds,
            managedResourceIds: $adminManagedIds,
            submittedIds: $submittedIds,
        );

        sort($result);
        // 5 and 10 are outside admin scope (preserved), 1 and 3 are submitted
        $this->assertEquals([1, 3, 5, 10], $result);
    }

    public function testMergeWithPreservedPermissionsNoDuplicates()
    {
        $existingIds = [1, 5];
        $adminManagedIds = [1, 2];
        $submittedIds = [1];  // Resource 1 is both existing and submitted

        $result = $this->service->MergeWithPreservedPermissions(
            existingIds: $existingIds,
            managedResourceIds: $adminManagedIds,
            submittedIds: $submittedIds,
        );

        sort($result);
        $this->assertEquals([1, 5], $result);
    }
}
