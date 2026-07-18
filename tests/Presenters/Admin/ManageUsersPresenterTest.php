<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/Admin/ManageUsersPresenter.php');
require_once(ROOT_DIR . 'Pages/Admin/ManageUsersPage.php');

class ManageUsersPresenterTest extends TestBase
{
    /**
     * @var FakeManageUsersPage
     */
    private $page;

    /**
     * @var FakeUserRepository
     */
    public $userRepo;

    /**
     * @var FakeResourceRepository
     */
    public $resourceRepo;

    public IManageUsersService&\PHPUnit\Framework\MockObject\MockObject $manageUsersService;

    /**
     * @var ManageUsersPresenter
     */
    public $presenter;

    /**
     * @var FakeAttributeService
     */
    public $attributeService;

    public PasswordEncryption&\PHPUnit\Framework\MockObject\MockObject $encryption;

    public IGroupRepository&\PHPUnit\Framework\MockObject\MockObject $groupRepository;

    public IGroupViewRepository&\PHPUnit\Framework\MockObject\MockObject $groupViewRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeManageUsersPage();
        $this->userRepo = new FakeUserRepository();
        $this->resourceRepo = new FakeResourceRepository();
        $this->encryption = $this->createMock('PasswordEncryption');
        $this->manageUsersService = $this->createMock('IManageUsersService');
        $this->attributeService = new FakeAttributeService();
        $this->groupRepository = $this->createMock('IGroupRepository');
        $this->groupViewRepository = $this->createMock('IGroupViewRepository');

        $this->presenter = new ManageUsersPresenter(
            $this->page,
            $this->userRepo,
            $this->resourceRepo,
            $this->encryption,
            $this->manageUsersService,
            $this->attributeService,
            $this->groupRepository,
            $this->groupViewRepository
        );
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testBindsUsersAndAttributesAndGroups()
    {
        $userId = 123;
        $pageNumber = 1;
        $pageSize = 10;

        $result = new UserItemView();
        $result->Id = $userId;
        $results = [$result];
        $userList = new PageableData($results);

        $resourceList = [new FakeBookableResource(1)];

        $attributeList = [new FakeCustomAttribute(1, '1')];

        $user = new FakeUser();

        $this->page->_PageNumber = $pageNumber;
        $this->page->_PageSize = $pageSize;
        $this->page->_FilterStatusId = AccountStatus::ALL;

        $this->userRepo->_UserList = $userList;
        $this->userRepo->_User = $user;

        $this->resourceRepo->_ResourceList = $resourceList;

        $this->attributeService->_ByCategory[CustomAttributeCategory::USER] = $attributeList;

        $groups = [new GroupItemView(1, 'gn')];
        $groupList = new PageableData($groups);
        $this->groupViewRepository
                ->expects($this->once())
                ->method('GetList')
                ->willReturn($groupList);

        $this->presenter->PageLoad();

        $this->assertEquals($groups, $this->page->_BoundGroups);
        $this->assertEquals($userList->Results(), $this->page->_BoundUsers);
        $this->assertEquals($userList->PageInfo(), $this->page->_BoundPageInfo);
        $this->assertEquals($resourceList, $this->page->_BoundResources);
        $this->assertEquals($attributeList, $this->page->_BoundAttributes);
    }

    public function testGetsSelectedResourcesFromPageAndAssignsPermission()
    {
        $this->fakeUser->IsAdmin = true;

        $resourcesThatShouldRemainUnchanged = [5, 10];
        $adminManageableIds = [1, 2, 4, 20, 30];
        $submittedResourceIds = ['1_0', '4_0'];
        $currentFullAccessIds = [1, 20, 30];

        $expectedFullAccessIds = [1, 4, 5, 10];

        $allResourceIds = array_unique(array_merge($resourcesThatShouldRemainUnchanged, $adminManageableIds, [1, 4], $currentFullAccessIds));

        $resources = [];
        foreach ($allResourceIds as $rid) {
            $resources[] = new FakeBookableResource($rid);
        }

        $userId = 9928;
        $adminUserId = $this->fakeUser->UserId;

        $user = new FakeUser();
        $user->WithAllowedPermissions(array_merge($resourcesThatShouldRemainUnchanged, $currentFullAccessIds));

        $adminUser = new FakeUser();
        $adminUser->_ResourceAdminResourceIds = $adminManageableIds;
        $adminUser->_IsResourceAdmin = false;

        $this->page->_UserId = $userId;
        $this->page->_AllowedResourceIds = $submittedResourceIds;

        $this->resourceRepo->_ResourceList = $resources;

        $this->userRepo->_UserById[$adminUserId] = $adminUser;
        $this->userRepo->_UserById[$userId] = $user;

        $this->presenter->ChangePermissions();

        $actualFullAccess = $user->GetFullAccessResourceIds();
        sort($expectedFullAccessIds);
        sort($actualFullAccess);
        $this->assertEquals($expectedFullAccessIds, $actualFullAccess);
        $this->assertEquals($this->userRepo->_UpdatedUser, $user);
    }

    public function testViewPermissionsPreservedSeparatelyFromFullPermissions()
    {
        $this->fakeUser->IsAdmin = true;

        // Admin can manage resources [1, 2, 3]
        // User has full=[1, 5], view=[2, 6]
        // Admin submits full=[1], view=[3]
        // Expected: full=[1, 5], view=[3, 6]
        $adminManageableIds = [1, 2, 3];
        $submittedResourceIds = ['1_0', '3_1'];

        $allResourceIds = [1, 2, 3, 5, 6];
        $resources = [];
        foreach ($allResourceIds as $rid) {
            $resources[] = new FakeBookableResource($rid);
        }

        $userId = 9928;
        $adminUserId = $this->fakeUser->UserId;

        $user = new FakeUser();
        $user->WithAllowedPermissions([1, 5]);
        $user->WithViewablePermission([2, 6]);

        $adminUser = new FakeUser();
        $adminUser->_ResourceAdminResourceIds = $adminManageableIds;
        $adminUser->_IsResourceAdmin = false;

        $this->page->_UserId = $userId;
        $this->page->_AllowedResourceIds = $submittedResourceIds;

        $this->resourceRepo->_ResourceList = $resources;

        $this->userRepo->_UserById[$adminUserId] = $adminUser;
        $this->userRepo->_UserById[$userId] = $user;

        $this->presenter->ChangePermissions();

        $actualFull = $user->GetFullAccessResourceIds();
        $actualView = $user->GetViewAccessResourceIds();
        sort($actualFull);
        sort($actualView);
        $this->assertEquals([1, 5], $actualFull);
        $this->assertEquals([3, 6], $actualView);
        $this->assertEquals($this->userRepo->_UpdatedUser, $user);
    }

    public function testActivateIsDeniedWhenNotApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = true;
        $this->page->_UserId = 809;

        $this->presenter->Activate();

        $this->assertNull($this->userRepo->_UpdatedUser);
    }

    public function testActivatesUserWhenApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = true;
        $userId = 809;
        $user = new FakeUser($userId);
        $user->SetStatus(AccountStatus::INACTIVE);

        $this->page->_UserId = $userId;
        $this->userRepo->_User = $user;

        $this->presenter->Activate();

        $this->assertEquals(AccountStatus::ACTIVE, $user->StatusId());
        $this->assertSame($user, $this->userRepo->_UpdatedUser);
        $this->assertEquals(Resources::GetInstance()->GetString('Active'), $this->page->_JsonResponse);
    }

    public function testDeactivateIsDeniedWhenNotApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = true;
        $this->page->_UserId = 809;

        $this->presenter->Deactivate();

        $this->assertNull($this->userRepo->_UpdatedUser);
    }

    public function testDeactivatesUserWhenApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = true;
        $userId = 809;
        $user = new FakeUser($userId);

        $this->page->_UserId = $userId;
        $this->userRepo->_User = $user;

        $this->presenter->Deactivate();

        $this->assertEquals(AccountStatus::INACTIVE, $user->StatusId());
        $this->assertSame($user, $this->userRepo->_UpdatedUser);
        $this->assertEquals(Resources::GetInstance()->GetString('Inactive'), $this->page->_JsonResponse);
    }

    public function testChangeColorIsDeniedWhenNotApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = true;
        $this->page->_UserId = 809;

        $this->presenter->ChangeColor();

        $this->assertNull($this->userRepo->_UpdatedUser);
    }

    public function testChangesColorWhenApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = true;
        $this->fakeConfig->SetKey(ConfigKeys::SCHEDULE_USE_PER_USER_COLORS, 'true');
        $userId = 809;
        $color = '#123456';
        $user = new FakeUser($userId);

        $this->page->_UserId = $userId;
        $this->page->_ReservationColor = $color;
        $this->userRepo->_User = $user;

        $this->presenter->ChangeColor();

        $this->assertEquals($color, $user->GetPreferences()->Get(UserPreferences::RESERVATION_COLOR));
        $this->assertSame($user, $this->userRepo->_UpdatedUser);
    }

    public function testChangeColorRejectsInvalidColor()
    {
        $this->fakeUser->IsAdmin = true;
        $this->fakeConfig->SetKey(ConfigKeys::SCHEDULE_USE_PER_USER_COLORS, 'true');
        $userId = 809;
        $user = new FakeUser($userId);

        $this->page->_UserId = $userId;
        $this->page->_ReservationColor = '#123456\" onmouseover=\"alert(1)';
        $this->userRepo->_User = $user;

        $this->presenter->ChangeColor();

        $this->assertNull($user->GetPreferences()->Get(UserPreferences::RESERVATION_COLOR));
        $this->assertNull($this->userRepo->_UpdatedUser);
    }

    public function testChangeCreditsIsDeniedWhenNotApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = true;
        $this->page->_UserId = 809;

        $this->presenter->ChangeCredits();

        $this->assertNull($this->userRepo->_UpdatedUser);
    }

    public function testChangesCreditsWhenApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = true;
        $userId = 809;
        $creditCount = 25;
        $user = new FakeUser($userId);

        $this->page->_UserId = $userId;
        $this->page->_Value = $creditCount;
        $this->userRepo->_User = $user;

        $this->presenter->ChangeCredits();

        $this->assertEquals($creditCount, $user->GetCurrentCredits());
        $this->assertSame($user, $this->userRepo->_UpdatedUser);
    }

    public function testChangePermissionsIsDeniedWhenNotApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = true;
        $this->page->_UserId = 809;

        $this->presenter->ChangePermissions();

        $this->assertNull($this->userRepo->_UpdatedUser);
    }

    public function testExportUsersIsDeniedWhenNotApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = true;

        $this->presenter->ExportUsers();

        $this->assertFalse($this->page->_ShowedExportCsv);
    }

    public function testImportUsersIsDeniedWhenNotApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = true;

        $this->manageUsersService->expects($this->never())
                                 ->method('AddUser');
        $this->manageUsersService->expects($this->never())
                                 ->method('LoadUser');

        $this->presenter->ImportUsers();

        $this->assertEquals(0, $this->page->_ImportResult->importCount);
        $this->assertEquals(['User is not an admin'], $this->page->_ImportResult->messages);
    }

    public function testInviteUsersIsDeniedWhenNotApplicationAdmin()
    {
        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = true;
        $this->page->_InvitedEmails = 'invitee@email.com';

        $this->presenter->InviteUsers();

        $this->assertEquals(0, count($this->fakeEmailService->_Messages));
    }

    public function testResetPasswordDelegatesToService()
    {
        $password = 'password';
        $userId = 123;

        $this->page->_UserId = $userId;
        $this->page->_Password = $password;

        $this->manageUsersService->expects($this->once())
                                 ->method('UpdatePassword')
                                 ->with($this->equalTo($userId), $this->equalTo($password));

        $this->presenter->ResetPassword();
    }

    public function testCanUpdateUser()
    {
        $userId = 1029380;
        $fname = 'f';
        $lname = 'l';
        $username = 'un';
        $email = 'e@mail.com';
        $timezone = 'America/Chicago';
        $phone = '123-123-1234';
        $organization = 'ou';
        $position = 'position';

        $user = new FakeUser($userId);

        $attributeId = 1;
        $attributeValue = 'value';
        $attributeFormElements = [new AttributeFormElement($attributeId, $attributeValue)];
        $this->page->_UserId = $userId;
        $this->page->_FirstName = $fname;
        $this->page->_LastName = $lname;
        $this->page->_UserName = $username;
        $this->page->_Email = $email;
        $this->page->_Timezone = $timezone;
        $this->page->_Phone = $phone;
        $this->page->_Organization = $organization;
        $this->page->_Position = $position;
        $this->page->_Attributes = $attributeFormElements;

        $extraAttributes = [
            UserAttribute::Organization => $organization,
            UserAttribute::Phone => $phone,
            UserAttribute::Position => $position];

        $this->manageUsersService->expects($this->once())
                                 ->method('UpdateUser')
                                 ->with(
                                     $this->equalTo($userId),
                                     $this->equalTo($username),
                                     $this->equalTo($email),
                                     $this->equalTo($fname),
                                     $this->equalTo($lname),
                                     $this->equalTo($timezone),
                                     $this->equalTo($extraAttributes),
                                     $this->equalTo([new AttributeValue($attributeId, $attributeValue)])
                                 )
                                 ->willReturn($user);

        $this->presenter->UpdateUser();
    }

    public function testDeletesUser()
    {
        $userId = 809;
        $this->page->_UserId = $userId;

        $this->manageUsersService->expects($this->once())
                                 ->method('DeleteUser')
                                 ->with($this->equalTo($userId));

        $this->presenter->DeleteUser();
    }

    public function testDeletesUsers()
    {
        $userIds = [809, 909];
        $this->page->_DeletedUserIds = $userIds;

        $matcher = $this->exactly(2);
        $this->manageUsersService->expects($matcher)
                                 ->method('DeleteUser')
                                 ->willReturnCallback(function ($userId) use ($matcher) {
                                     match ($matcher->numberOfInvocations()) {
                                         1 => $this->assertEquals(809, $userId),
                                         2 => $this->assertEquals(909, $userId)
                                     };
                                 });

        $this->presenter->DeleteMultipleUsers();
    }

    public function testAddsUser()
    {
        $fname = 'f';
        $lname = 'l';
        $username = 'un';
        $email = 'e@mail.com';
        $timezone = 'America/Chicago';
        $lang = 'foo';
        $password = 'pw';

        $attributeId = 1;
        $attributeValue = 'value';
        $attributeFormElements = [new AttributeFormElement($attributeId, $attributeValue)];

        $userId = 1090;
        $groupId = 111;

        $user = new FakeUser($userId);

        $group = new Group($groupId, 'name');
        $group->AddUser($userId);

        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, $lang);

        $this->page->_FirstName = $fname;
        $this->page->_LastName = $lname;
        $this->page->_UserName = $username;
        $this->page->_Email = $email;
        $this->page->_Timezone = $timezone;
        $this->page->_Password = $password;
        $this->page->_Attributes = $attributeFormElements;
        $this->page->_UserGroup = $groupId;

        $this->manageUsersService->expects($this->once())
                                 ->method('AddUser')
                                 ->with(
                                     $this->equalTo($username),
                                     $this->equalTo($email),
                                     $this->equalTo($fname),
                                     $this->equalTo($lname),
                                     $this->equalTo($password),
                                     $this->equalTo($timezone),
                                     $this->equalTo($lang),
                                     $this->equalTo(Pages::DEFAULT_HOMEPAGE_ID),
                                     $this->equalTo([
                                         UserAttribute::Organization => null,
                                         UserAttribute::Phone => null,
                                         UserAttribute::Position => null]),
                                     $this->equalTo([new AttributeValue($attributeId, $attributeValue)])
                                 )
                                 ->willReturn($user);

        $this->groupRepository->expects($this->once())
                              ->method('LoadById')
                              ->with($this->equalTo($groupId))
                              ->willReturn($group);

        $this->groupRepository->expects($this->once())
                              ->method('Update')
                              ->with($this->equalTo($group));

        $this->presenter->AddUser();
    }

    public function testGetsAllUsers()
    {
        $users = [new UserDto(1, 'f', 'l', 'e')];

        $this->userRepo->_AllUsers = $users;

        $this->presenter->ProcessDataRequest('all');

        $this->assertEquals($users, $this->page->_JsonResponse);
    }

    public function testParsesImportWithHeader()
    {
        $file = new FakeUploadedFile();
        $file->Contents = "username,email,first name,last name,password,phone,organization,position,timezone,language,groups\nu1,e1,f1,l1,p1,ph1,o1,po1,t1,l1,g1";
        $csv = new UserImportCsv($file, []);

        $rows = $csv->GetRows();

        $this->assertCount(1, $rows);

        $row1 = $rows[0];
        $this->assertEquals('u1', $row1->username);
        $this->assertEquals('e1', $row1->email);
        $this->assertEquals('f1', $row1->firstName);
        $this->assertEquals('l1', $row1->lastName);
        $this->assertEquals('p1', $row1->password);
        $this->assertEquals('ph1', $row1->phone);
        $this->assertEquals('o1', $row1->organization);
        $this->assertEquals('po1', $row1->position);
        $this->assertEquals('t1', $row1->timezone);
        $this->assertEquals('l1', $row1->language);
        $this->assertEquals(['g1'], $row1->groups);
    }

    public function testDefaultsMissingColumns()
    {
        $file = new FakeUploadedFile();
        $file->Contents = "email,username,password,first name,last name\ne1,u1,p1,f1,l1";
        $csv = new UserImportCsv($file, []);

        $rows = $csv->GetRows();
        $this->assertCount(1, $rows);

        $row1 = $rows[0];
        $this->assertEquals('u1', $row1->username);
        $this->assertEquals('e1', $row1->email);
        $this->assertEquals('f1', $row1->firstName);
        $this->assertEquals('l1', $row1->lastName);
        $this->assertEquals('p1', $row1->password);
        $this->assertEquals('', $row1->phone);
        $this->assertEquals('', $row1->organization);
        $this->assertEquals('', $row1->position);
        $this->assertEquals('', $row1->timezone);
        $this->assertEquals('', $row1->language);
        $this->assertEquals([], $row1->groups);
    }

    public function testDefaultsMissingValuesInRow()
    {
        $file = new FakeUploadedFile();
        $file->Contents = "email,username,password,first name,last name\ne1,u1";
        $csv = new UserImportCsv($file, []);

        $rows = $csv->GetRows();
        $this->assertCount(1, $rows);

        $row1 = $rows[0];
        $this->assertEquals('u1', $row1->username);
        $this->assertEquals('e1', $row1->email);
        $this->assertEquals('', $row1->firstName);
        $this->assertEquals('', $row1->lastName);
        $this->assertEquals('', $row1->password);
        $this->assertEquals('', $row1->phone);
        $this->assertEquals('', $row1->organization);
        $this->assertEquals('', $row1->position);
        $this->assertEquals('', $row1->timezone);
        $this->assertEquals('', $row1->language);
        $this->assertEquals([], $row1->groups);
    }

    public function testInvalidRowsAreSkipped()
    {
        $file = new FakeUploadedFile();
        $file->Contents = "email,username,password,first name,last name\ne\ne";
        $csv = new UserImportCsv($file, []);

        $rows = $csv->GetRows();
        $skippedRowNumbers = $csv->GetSkippedRowNumbers();

        $this->assertEquals(0, count($rows));
        $this->assertEquals([1, 2], $skippedRowNumbers);
    }

    public function testShowsUserUpdate()
    {
        $userId = 1;
        $this->page->_UserId = $userId;

        $user = new FakeUser();
        $this->userRepo->_User = $user;
        $attributes = [1 => new FakeCustomAttribute(1)];
        $entityAttributeList = new AttributeList();
        $entityAttributeList->AddDefinition(new FakeCustomAttribute(1));
        $this->attributeService->_EntityAttributeList = $entityAttributeList;

        $this->presenter->ShowUpdate();

        $this->assertEquals($this->page->_BoundUpdateUser, $user);
        $this->assertEquals($this->page->_BoundUpdateAttributes, $attributes);
    }
}

class FakeManageUsersPage extends FakeActionPageBase implements IManageUsersPage
{
    /**
     * @var CsvImportResult
     */
    public $_ImportResult;
    /**
     * @var bool
     */
    public $_ShowedExportCsv = false;
    /**
     * @var string
     */
    public $_InvitedEmails = '';
    /**
     * @var int
     */
    public $_UserId;
    /**
     * @var int
     */
    public $_PageNumber;
    /**
     * @var int
     */
    public $_PageSize;
    /**
     * @var PageInfo
     */
    public $_BoundPageInfo;
    /**
     * @var UserItemView[]
     */
    public $_BoundUsers;
    /**
     * @var BookableResource[]
     */
    public $_BoundResources;
    /**
     * @var GroupItemView[]
     */
    public $_BoundGroups;
    /**
     * @var CustomAttribute[]
     */
    public $_BoundAttributes;
    /**
     * @var int
     */
    public $_FilterStatusId;
    /**
     * @var string[]
     */
    public $_AllowedResourceIds;
    /**
     * @var string
     */
    public $_Password;
    /**
     * @var string
     */
    public $_FirstName;
    /**
     * @var string
     */
    public $_LastName;
    /**
     * @var string
     */
    public $_UserName;
    /**
     * @var string
     */
    public $_Email;
    /**
     * @var string
     */
    public $_Timezone;
    /**
     * @var string
     */
    public $_Phone;
    /**
     * @var string
     */
    public $_Organization;
    /**
     * @var string
     */
    public $_Position;
    /**
     * @var int[]
     */
    public $_DeletedUserIds;
    public $_Language;
    /**
     * @var AttributeFormElement[]
     */
    public $_Attributes;
    /**
     * @var int
     */
    public $_UserGroup;
    public $_JsonResponse;
    public $_ReservationColor = '';
    public $_Value = '';
    /**
     * @var User
     */
    public $_BoundUpdateUser;
    /**
     * @var CustomAttribute[]
     */
    public $_BoundUpdateAttributes;

    public function GetPageNumber()
    {
        return $this->_PageNumber;
    }

    public function GetPageSize()
    {
        return $this->_PageSize;
    }

    public function BindPageInfo(PageInfo $pageInfo)
    {
        $this->_BoundPageInfo = $pageInfo;
    }

    public function BindUsers($users)
    {
        $this->_BoundUsers = $users;
    }

    public function GetUserId()
    {
        return $this->_UserId;
    }

    public function BindResources($resources)
    {
        $this->_BoundResources = $resources;
    }

    public function SetJsonResponse($objectToSerialize)
    {
        $this->_JsonResponse = $objectToSerialize;
    }

    /**
     * @return string[]|null
     */
    public function GetAllowedResourceIds()
    {
        return $this->_AllowedResourceIds;
    }

    public function GetPassword()
    {
        return $this->_Password;
    }

    public function GetEmail()
    {
        return $this->_Email;
    }

    public function GetUserName()
    {
        return $this->_UserName;
    }

    public function GetFirstName()
    {
        return $this->_FirstName;
    }

    public function GetLastName()
    {
        return $this->_LastName;
    }

    public function GetTimezone()
    {
        return $this->_Timezone;
    }

    public function GetPhone()
    {
        return $this->_Phone;
    }

    public function GetPosition()
    {
        return $this->_Position;
    }

    public function GetOrganization()
    {
        return $this->_Organization;
    }

    public function GetLanguage()
    {
        return $this->_Language;
    }

    public function BindAttributeList($attributeList)
    {
        $this->_BoundAttributes = $attributeList;
    }

    public function GetAttributes()
    {
        return $this->_Attributes;
    }

    public function GetFilterStatusId()
    {
        return $this->_FilterStatusId;
    }

    public function GetUserGroup()
    {
        return $this->_UserGroup;
    }

    public function BindGroups($groups)
    {
        $this->_BoundGroups = $groups;
    }

    public function GetReservationColor()
    {
        return $this->_ReservationColor;
    }

    public function GetValue()
    {
        return $this->_Value;
    }

    public function GetName()
    {
        return '';
    }

    public function ShowTemplateCSV($attributes)
    {
        // TODO: Implement ShowTemplateCSV() method.
    }

    public function GetImportFile()
    {
        return new UploadedFile([
            'name' => 'users.csv',
            'tmp_name' => __FILE__,
            'type' => 'text/csv',
            'size' => filesize(__FILE__),
            'error' => UPLOAD_ERR_OK,
        ]);
    }

    public function SetImportResult($importResult)
    {
        $this->_ImportResult = $importResult;
    }

    public function GetInvitedEmails()
    {
        return $this->_InvitedEmails;
    }

    public function ShowExportCsv()
    {
        $this->_ShowedExportCsv = true;
    }

    public function BindStatusDescriptions()
    {
        // TODO: Implement BindStatusDescriptions() method.
    }

    public function GetDeletedUserIds()
    {
        return $this->_DeletedUserIds;
    }

    public function SendEmailNotification()
    {
        return false;
    }

    public function GetUpdateOnImport()
    {
        return false;
    }

    public function ShowUserUpdate(User $user, $attributes)
    {
        $this->_BoundUpdateUser = $user;
        $this->_BoundUpdateAttributes = $attributes;
    }
}
