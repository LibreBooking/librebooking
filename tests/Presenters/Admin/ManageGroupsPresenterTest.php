<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/Admin/ManageGroupsPresenter.php');
require_once(ROOT_DIR . 'Pages/Admin/ManageGroupsPage.php');

class ManageGroupsPresenterTest extends TestBase
{
    private FakeManageGroupsPage $page;
    private FakeUserRepository $userRepo;
    private FakeResourceRepository $resourceRepo;
    private IGroupRepository&IGroupViewRepository&\PHPUnit\Framework\MockObject\MockObject $groupRepository;
    private IScheduleRepository&\PHPUnit\Framework\MockObject\MockObject $scheduleRepository;
    private ManageGroupsPresenter $presenter;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeManageGroupsPage();
        $this->userRepo = new FakeUserRepository();
        $this->resourceRepo = new FakeResourceRepository();
        $this->groupRepository = $this->createMockForIntersectionOfInterfaces([IGroupRepository::class, IGroupViewRepository::class]);
        $this->scheduleRepository = $this->createMock(IScheduleRepository::class);

        $this->presenter = new ManageGroupsPresenter(
            $this->page,
            $this->groupRepository,
            $this->resourceRepo,
            $this->scheduleRepository,
            $this->userRepo,
        );
    }

    public function testChangePermissionsWithAdminScopeCheck()
    {
        // Admin can manage resources [1, 2, 3]
        // Group has full=[1, 5], view=[2, 6]
        // Admin submits full=[1], view=[3]
        // Expected: full=[1, 5], view=[3, 6]
        $adminManageableIds = [1, 2, 3];
        $submittedResourceIds = ['1_0', '3_1'];

        $allResourceIds = [1, 2, 3, 5, 6];
        $resources = [];
        foreach ($allResourceIds as $rid) {
            $resources[] = new FakeBookableResource($rid);
        }

        $groupId = 100;
        $adminUserId = $this->fakeUser->UserId;

        $group = new Group(0, 'test group');
        $group->WithId($groupId);
        $group->WithFullPermission(1);
        $group->WithFullPermission(5);
        $group->WithViewablePermission(2);
        $group->WithViewablePermission(6);

        $adminUser = new FakeUser();
        $adminUser->_ResourceAdminResourceIds = $adminManageableIds;
        $adminUser->_IsResourceAdmin = false;

        $this->page->_GroupId = $groupId;
        $this->page->_AllowedResourceIds = $submittedResourceIds;

        $this->resourceRepo->_ResourceList = $resources;

        $this->userRepo->_UserById[$adminUserId] = $adminUser;

        $this->groupRepository
            ->expects($this->once())
            ->method('LoadById')
            ->with($groupId)
            ->willReturn($group);

        $this->groupRepository
            ->expects($this->once())
            ->method('Update')
            ->with($group);

        $this->presenter->ChangePermissions();

        $actualFull = $group->AllowedResourceIds();
        $actualView = $group->AllowedViewResourceIds();
        sort($actualFull);
        sort($actualView);
        $this->assertEquals([1, 5], $actualFull);
        $this->assertEquals([3, 6], $actualView);
    }

    public function testChangePermissionsRejectsResourcesOutsideAdminScope()
    {
        // Admin can manage resources [1, 2]
        // Admin tries to submit permissions for resource 99 (outside scope)
        // Group has no existing permissions
        // Expected: no permissions set (resource 99 rejected)
        $adminManageableIds = [1, 2];
        $submittedResourceIds = ['99_0'];

        $resources = [
            new FakeBookableResource(1),
            new FakeBookableResource(2),
            new FakeBookableResource(99),
        ];

        $groupId = 200;
        $adminUserId = $this->fakeUser->UserId;

        $group = new Group(0, 'test group');
        $group->WithId($groupId);

        $adminUser = new FakeUser();
        $adminUser->_ResourceAdminResourceIds = $adminManageableIds;
        $adminUser->_IsResourceAdmin = false;

        $this->page->_GroupId = $groupId;
        $this->page->_AllowedResourceIds = $submittedResourceIds;

        $this->resourceRepo->_ResourceList = $resources;

        $this->userRepo->_UserById[$adminUserId] = $adminUser;

        $this->groupRepository
            ->method('LoadById')
            ->willReturn($group);

        $this->groupRepository
            ->expects($this->once())
            ->method('Update')
            ->with($group);

        $this->presenter->ChangePermissions();

        $this->assertEquals([], $group->AllowedResourceIds());
        $this->assertEquals([], $group->AllowedViewResourceIds());
    }

    public function testChangeRolesUpdatesGroupWhenApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = true;

        $groupId = 100;
        $group = new Group(0, 'test group');
        $group->WithId($groupId);

        $this->page->_GroupId = $groupId;

        $this->groupRepository
            ->expects($this->once())
            ->method('LoadById')
            ->with($groupId)
            ->willReturn($group);

        $this->groupRepository
            ->expects($this->once())
            ->method('Update')
            ->with($group);

        $this->presenter->ChangeRoles();
    }

    public function testChangeRolesIsDeniedWhenNotApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = true;
        $this->page->_GroupId = 100;

        $this->groupRepository->expects($this->never())->method('LoadById');
        $this->groupRepository->expects($this->never())->method('Update');

        $this->presenter->ChangeRoles();
    }

    public function testChangeGroupAdminIsDeniedWhenNotApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = true;
        $this->page->_GroupId = 100;

        $this->groupRepository->expects($this->never())->method('LoadById');
        $this->groupRepository->expects($this->never())->method('Update');

        $this->presenter->ChangeGroupAdmin();
    }

    public function testChangeAdminGroupsIsDeniedWhenNotApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = true;
        $this->page->_GroupId = 100;

        $this->groupRepository->expects($this->never())->method('LoadById');
        $this->groupRepository->expects($this->never())->method('Update');

        $this->presenter->ChangeAdminGroups();
    }

    public function testChangeResourceGroupsIsDeniedWhenNotApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = true;
        $this->page->_GroupId = 100;

        $this->presenter->ChangeResourceGroups();

        $this->assertNull($this->resourceRepo->_UpdatedResource);
    }

    public function testChangeScheduleGroupsIsDeniedWhenNotApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = true;
        $this->page->_GroupId = 100;

        $this->scheduleRepository->expects($this->never())->method('Update');

        $this->presenter->ChangeScheduleGroups();
    }

    public function testImportIsDeniedWhenNotApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = true;

        $this->groupRepository->expects($this->never())->method('Add');
        $this->groupRepository->expects($this->never())->method('Update');

        $this->presenter->Import();

        $this->assertEquals(0, $this->page->_ImportResult->importCount);
        $this->assertEquals(['User is not an admin'], $this->page->_ImportResult->messages);
    }
}

class FakeManageGroupsPage extends FakeActionPageBase implements IManageGroupsPage
{
    public ?int $_GroupId = null;

    /**
     * @var CsvImportResult
     */
    public $_ImportResult;
    /** @var string[]|null */
    public ?array $_AllowedResourceIds = null;

    public function GetGroupId()
    {
        return $this->_GroupId;
    }

    public function BindGroups($groups)
    {
    }

    public function BindPageInfo(PageInfo $pageInfo)
    {
    }

    public function GetPageNumber()
    {
        return 1;
    }

    public function GetPageSize()
    {
        return 10;
    }

    public function SetJsonResponse($response)
    {
    }

    public function GetUserId()
    {
        return 0;
    }

    public function BindResources($resources)
    {
    }

    public function BindSchedules($schedules)
    {
    }

    public function BindRoles($roles)
    {
    }

    public function GetAllowedResourceIds()
    {
        return $this->_AllowedResourceIds;
    }

    public function GetGroupName()
    {
        return '';
    }

    public function GetRoleIds()
    {
        return [];
    }

    public function BindAdminGroups($adminGroups)
    {
    }

    public function GetAdminGroupId()
    {
        return 0;
    }

    public function AutomaticallyAddToGroup()
    {
        return false;
    }

    public function GetUserIds()
    {
        return [];
    }

    public function GetGroupAdminIds()
    {
        return [];
    }

    public function GetResourceAdminIds()
    {
        return [];
    }

    public function GetScheduleAdminIds()
    {
        return [];
    }

    public function Export($groups, $users, $permissionsWrite, $permissionsRead)
    {
    }

    public function GetImportFile()
    {
        return new UploadedFile([]);
    }

    public function ShowTemplateCsv()
    {
    }

    public function SetImportResult($importResult)
    {
        $this->_ImportResult = $importResult;
    }

    public function GetUpdateOnImport()
    {
        return false;
    }

    public function GetSortField()
    {
        return null;
    }

    public function GetSortDirection()
    {
        return null;
    }
}
