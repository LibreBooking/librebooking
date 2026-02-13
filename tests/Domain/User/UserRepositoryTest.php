<?php

class UserRepositoryTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testGetAllUsersReturnsAllActiveUsers()
    {
        $timezone = 'UTC';
        $language = 'en_us';
        $preferences = 'n1=v1';
        $creditCount = 100;

        $userRows = [
            [ColumnNames::USER_ID => 1, ColumnNames::FIRST_NAME => 'f1', ColumnNames::LAST_NAME => 'l1', ColumnNames::EMAIL => 'e1', ColumnNames::TIMEZONE_NAME => $timezone, ColumnNames::LANGUAGE_CODE => $language],
            [ColumnNames::USER_ID => 2, ColumnNames::FIRST_NAME => 'f2', ColumnNames::LAST_NAME => 'l2', ColumnNames::EMAIL => 'e2', ColumnNames::TIMEZONE_NAME => $timezone, ColumnNames::LANGUAGE_CODE => $language, ColumnNames::USER_PREFERENCES => $preferences, ColumnNames::CREDIT_COUNT => $creditCount],
            [ColumnNames::USER_ID => 3, ColumnNames::FIRST_NAME => 'f3', ColumnNames::LAST_NAME => 'l3', ColumnNames::EMAIL => 'e3', ColumnNames::TIMEZONE_NAME => $timezone, ColumnNames::LANGUAGE_CODE => $language],
        ];

        $this->db->SetRows($userRows);

        $userRepository = new UserRepository();
        $users = $userRepository->GetAll();

        $user1 = new UserDto(1, 'f1', 'l1', 'e1', $timezone, $language);
        $user2 = new UserDto(2, 'f2', 'l2', 'e2', $timezone, $language, $preferences, $creditCount);
        $user3 = new UserDto(3, 'f3', 'l3', 'e3', $timezone, $language);

        $getAllUsersCommand = new GetAllUsersByStatusCommand(AccountStatus::ACTIVE);

        $this->assertEquals($user1, $users[0]);
        $this->assertEquals($user2, $users[1]);
        $this->assertEquals($user3, $users[2]);

        $this->assertEquals(1, count($this->db->_Commands));
        $this->assertEquals($getAllUsersCommand, $this->db->_Commands[0]);
    }

    public function testLoadsUserFromDatabaseIfNotInCache()
    {
        $userId = 982;
        $loadByIdCommand = new GetUserByIdCommand($userId);
        $loadEmailPreferencesCommand = new GetUserEmailPreferencesCommand($userId);
        $loadPermissionsCommand = new GetUserPermissionsCommand($userId);
        $loadGroupsCommand = new GetUserGroupsCommand($userId, null);
        $attributesCommand = new GetAttributeValuesCommand($userId, CustomAttributeCategory::USER);
        $loadOwnedGroups = new GetGroupsIManageCommand($userId);
        $loadPreferences = new GetUserPreferencesCommand($userId);

        $userRow = $this->GetUserRow($userId);
        $emailPrefRows = $this->GetEmailPrefRows();
        $permissionsRows = $this->GetPermissionsRows();
        $groupsRows = $this->GetGroupsRows();
        $attributeRows = $this->GetAttributeRows();
        $ownedGroupRows = $this->GetOwnedGroupRows();
        $preferencesRows = $this->GetPreferenceRows();

        $this->db->SetRow(0, [$userRow]);
        $this->db->SetRow(1, $emailPrefRows);
        $this->db->SetRow(2, $permissionsRows);
        $this->db->SetRow(3, $groupsRows);
        $this->db->SetRow(4, $attributeRows);
        $this->db->SetRow(5, $ownedGroupRows);
        $this->db->SetRow(6, $preferencesRows);

        $row = $userRow;

        $userRepository = new UserRepository();
        $user = $userRepository->LoadById($userId);

        $this->assertEquals(7, count($this->db->_Commands));
        $this->assertEquals($loadByIdCommand, $this->db->_Commands[0]);
        $this->assertTrue($this->db->ContainsCommand($loadEmailPreferencesCommand));
        $this->assertTrue($this->db->ContainsCommand($loadPermissionsCommand));
        $this->assertTrue($this->db->ContainsCommand($loadGroupsCommand));
        $this->assertTrue($this->db->ContainsCommand($attributesCommand));
        $this->assertTrue($this->db->ContainsCommand($loadOwnedGroups));
        $this->assertTrue($this->db->ContainsCommand($loadPreferences));

        $this->assertEquals($row[ColumnNames::FIRST_NAME], $user->FirstName());
        $this->assertTrue($user->WantsEventEmail(new ReservationCreatedEvent()));
        $this->assertContains($permissionsRows[1][ColumnNames::RESOURCE_ID], $user->GetAllowedResourceIds());
        $this->assertEquals($row[ColumnNames::PHONE_NUMBER], $user->GetAttribute(UserAttribute::Phone));

        $row1 = $groupsRows[0];
        $row2 = $groupsRows[1];
        $row3 = $groupsRows[2];

        $group1 = new UserGroup(
            $row1[ColumnNames::GROUP_ID],
            $row1[ColumnNames::GROUP_NAME],
            $row1[ColumnNames::GROUP_ADMIN_GROUP_ID],
            $row1[ColumnNames::ROLE_LEVEL]
        );
        $group1->AddRole($row2[ColumnNames::ROLE_LEVEL]);
        $group2 = new UserGroup(
            $row3[ColumnNames::GROUP_ID],
            $row3[ColumnNames::GROUP_NAME],
            $row3[ColumnNames::GROUP_ADMIN_GROUP_ID],
            $row3[ColumnNames::ROLE_LEVEL]
        );

        $groups = $user->Groups();
        $this->assertEquals(2, count($groups));
        $this->assertTrue(in_array($group1, $groups));
        $this->assertTrue(in_array($group2, $groups));
        $this->assertEquals('value', $user->GetAttributeValue(1));
        $this->assertEquals('value2', $user->GetAttributeValue(2));
        $this->assertEquals('v1', $user->GetPreference('n1'));
    }

    public function testLoadsUserByPublicId()
    {
        $userRow = $this->GetUserRow();
        $emailPrefRows = $this->GetEmailPrefRows();
        $permissionsRows = $this->GetPermissionsRows();
        $groupsRows = $this->GetGroupsRows();
        $attributeRows = $this->GetAttributeRows();
        $ownedGroupRows = $this->GetOwnedGroupRows();
        $preferenceRows = $this->GetPreferenceRows();

        $this->db->SetRow(0, [$userRow]);
        $this->db->SetRow(1, $emailPrefRows);
        $this->db->SetRow(2, $permissionsRows);
        $this->db->SetRow(3, $groupsRows);
        $this->db->SetRow(4, $attributeRows);
        $this->db->SetRow(5, $ownedGroupRows);
        $this->db->SetRow(6, $preferenceRows);

        $publicId = uniqid();
        $userRepository = new UserRepository();

        $user = $userRepository->LoadByPublicId($publicId);

        $loadByIdCommand = new GetUserByPublicIdCommand($publicId);
        $this->assertEquals(7, count($this->db->_Commands));
        $this->assertTrue($this->db->ContainsCommand($loadByIdCommand));

        $this->assertNotNull($user);
    }

    public function testLoadsUserFromCacheIfAlreadyLoadedFromDatabase()
    {
        $userId = 1;

        $row = $this->GetUserRow();
        $this->db->SetRow(0, [$row]);
        $this->db->SetRow(1, $this->GetEmailPrefRows());
        $this->db->SetRow(2, $this->GetPermissionsRows());
        $this->db->SetRow(3, $this->GetGroupsRows());
        $this->db->SetRow(4, $this->GetAttributeRows());
        $this->db->SetRow(5, $this->GetOwnedGroupRows());
        $this->db->SetRow(6, $this->GetPreferenceRows());

        $userRepository = new UserRepository();
        $user = $userRepository->LoadById($userId);
        $user = $userRepository->LoadById($userId); // 2nd call should load from cache

        $this->assertEquals(7, count($this->db->_Commands));
    }

    public function testCanLoadUserByUserName()
    {
        $userName = 'username';
        $userId = 982;
        $loginCommand = new LoginCommand(strtolower($userName));
        $loadEmailPreferencesCommand = new GetUserEmailPreferencesCommand($userId);
        $loadPermissionsCommand = new GetUserPermissionsCommand($userId);
        $loadGroupsCommand = new GetUserGroupsCommand($userId, null);
        $loadOwnedGroups = new GetGroupsIManageCommand($userId);
        $loadPreferences = new GetUserPreferencesCommand($userId);

        $userRow = $this->GetUserRow($userId);
        $emailPrefRows = $this->GetEmailPrefRows();
        $permissionsRows = $this->GetPermissionsRows();
        $groupsRows = $this->GetGroupsRows();
        $attributeRows = $this->GetAttributeRows();
        $ownedGroupRows = $this->GetOwnedGroupRows();
        $preferenceRows = $this->GetPreferenceRows();

        $this->db->SetRow(0, [$userRow]);
        $this->db->SetRow(1, $emailPrefRows);
        $this->db->SetRow(2, $permissionsRows);
        $this->db->SetRow(3, $groupsRows);
        $this->db->SetRow(4, $attributeRows);
        $this->db->SetRow(5, $ownedGroupRows);
        $this->db->SetRow(6, $preferenceRows);

        $userRepository = new UserRepository();
        $user = $userRepository->LoadByUsername($userName);

        $this->assertEquals(7, count($this->db->_Commands));
        $this->assertTrue($this->db->ContainsCommand($loginCommand));
        $this->assertTrue($this->db->ContainsCommand($loadEmailPreferencesCommand));
        $this->assertTrue($this->db->ContainsCommand($loadPermissionsCommand));
        $this->assertTrue($this->db->ContainsCommand($loadGroupsCommand));
        $this->assertTrue($this->db->ContainsCommand($loadOwnedGroups));
        $this->assertTrue($this->db->ContainsCommand($loadPreferences));

        $this->assertEquals($userId, $user->Id());
    }

    public function testCanGetPageableListOfUsers()
    {
        $page = 3;
        $pageSize = 5;
        $count = 51;
        $totalPages = 11;
        $offset = 10;
        $countRow = ['total' => $count];
        $row1 = $this->GetUserRow(1, 'first', 'last', 'email', 'un1', '2011-01-01', 'utc', AccountStatus::ACTIVE, null, 'pref1=val1,pref2=val2');
        $row2 = $this->GetUserRow(2, 'first', 'last', 'email', null, '2010-01-01');
        $userRows = [$row1, $row2];

        $this->db->SetRow(0, [$countRow]);
        $this->db->SetRow(1, $userRows);

        $userRepo = new UserRepository();
        $pageable = $userRepo->GetList($page, $pageSize);

        $this->assertEquals($count, $pageable->PageInfo()->Total);
        $this->assertEquals($totalPages, $pageable->PageInfo()->TotalPages);
        $this->assertEquals($pageSize, $pageable->PageInfo()->PageSize);
        $this->assertEquals($page, $pageable->PageInfo()->CurrentPage);

        $results = $pageable->Results();
        $this->assertEquals(UserItemView::Create($row1), $results[0]);
        $this->assertEquals(UserItemView::Create($row2), $results[1]);

        $this->assertEquals($offset, $this->db->_Offset);
        $this->assertEquals($pageSize, $this->db->_Limit);
    }

    public function testUpdateSetsUserProperties()
    {
        $userId = 987;
        $loginTime = '2010-01-01';
        $language = 'en_gb';
        $user = new User();
        $user->WithId($userId);

        $password = 'password';
        $salt = 'salt';
        $homepageId = 19;
        $fname = 'f';
        $lname = 'l';
        $email = 'e';
        $username = 'u';
        $timezone = 'America/New_York';
        $scheduleId = 99;
        $credits = 100;

        $user->ChangePassword($password, $salt);
        $user->ChangeName($fname, $lname);
        $user->ChangeEmailAddress($email);
        $user->ChangeUsername($username);
        $user->ChangeDefaultHomePage($homepageId);
        $user->ChangeTimezone($timezone);
        $user->EnableSubscription();
        $user->Login($loginTime, $language);
        $user->ChangeDefaultSchedule($scheduleId);
        $user->ChangeCurrentCredits($credits);

        $publicId = $user->GetPublicId();

        $command = new UpdateUserCommand(
            $userId,
            $user->StatusId(),
            $password,
            $salt,
            $fname,
            $lname,
            $email,
            $username,
            $homepageId,
            $timezone,
            $loginTime,
            true,
            $publicId,
            $language,
            $scheduleId,
            $credits
        );

        $repo = new UserRepository();
        $repo->Update($user);

        $this->assertTrue($this->db->ContainsCommand($command));
    }

    public function testAddsAndUpdatesUserPreferences()
    {
        $userId = 987;
        $user = new User();
        $user->WithId($userId);

        $preferences = new UserPreferences();
        $preferences->Add('pref1', 'val1');
        $preferences->Add('pref2', 'val2');

        $user->WithPreferences($preferences);

        $user->ChangePreference('pref2', 'val3');
        $user->ChangePreference('pref3', 'val4');

        $repo = new UserRepository();
        $repo->Update($user);

        $this->assertTrue($this->db->ContainsCommand(new DeleteAllUserPreferences($userId)));
        $this->assertTrue($this->db->ContainsCommand(new AddUserPreferenceCommand($userId, 'pref2', 'val3')));
        $this->assertTrue($this->db->ContainsCommand(new AddUserPreferenceCommand($userId, 'pref3', 'val4')));
    }

    public function testOnlyUpdatesPermissionsIfTheyHaveChanged()
    {
        $userId = 987;
        $user = new User();
        $user->WithId($userId);
        $user->WithAllowedPermissions([1, 2, 3, 5]);
        $user->ChangeAllowedPermissions([2, 3, 4, 6]);
        $user->WithViewablePermission([7, 8, 9]);
        $user->ChangeViewPermissions([7, 5, 10]);

        $deletePermissionsCommand1 = new DeleteUserResourcePermission($userId, 1);
        $deletePermissionsCommand2 = new DeleteUserResourcePermission($userId, 5);
        $deletePermissionsCommand3 = new DeleteUserResourcePermission($userId, 8);
        $deletePermissionsCommand4 = new DeleteUserResourcePermission($userId, 9);
        $addPermissionsCommand1 = new AddUserResourcePermission($userId, 4, ResourcePermissionType::Full);
        $addPermissionsCommand2 = new AddUserResourcePermission($userId, 6, ResourcePermissionType::Full);
        $addPermissionsCommand3 = new AddUserResourcePermission($userId, 5, ResourcePermissionType::View);
        $addPermissionsCommand4 = new AddUserResourcePermission($userId, 10, ResourcePermissionType::View);

        $repo = new UserRepository();
        $repo->Update($user);

        $deleteCommands = $this->db->GetCommandsOfType('DeleteUserResourcePermission');
        $insertCommands = $this->db->GetCommandsOfType('AddUserResourcePermission');
        $this->assertEquals(4, count($deleteCommands));
        $this->assertEquals(4, count($insertCommands));

        $this->assertTrue($this->db->ContainsCommand($deletePermissionsCommand1));
        $this->assertTrue($this->db->ContainsCommand($deletePermissionsCommand2));
        $this->assertTrue($this->db->ContainsCommand($deletePermissionsCommand3));
        $this->assertTrue($this->db->ContainsCommand($deletePermissionsCommand4));
        $this->assertTrue($this->db->ContainsCommand($addPermissionsCommand1));
        $this->assertTrue($this->db->ContainsCommand($addPermissionsCommand2));
        $this->assertTrue($this->db->ContainsCommand($addPermissionsCommand3));
        $this->assertTrue($this->db->ContainsCommand($addPermissionsCommand4));
    }

    public function testUpdatesAttributesIfTheyHaveChanged()
    {
        $userId = 28;
        $phone = '123.456.7890';
        $organization = 'org';
        $position = 'pos';

        $user = new User();
        $user->WithId($userId);

        $user->ChangeAttributes($phone, $organization, $position);

        $repo = new UserRepository();
        $repo->Update($user);

        $updateAttributesCommand = new UpdateUserAttributesCommand($userId, $phone, $organization, $position);
        $this->assertTrue($this->db->ContainsCommand($updateAttributesCommand));
    }

    public function testUpdatesCustomAttributes()
    {
        $userId = 11;
        $unchanged = new AttributeValue(1, 'value');
        $toChange = new AttributeValue(2, 'value');
        $toAdd = new AttributeValue(3, 'value');

        $user = new User();
        $user->WithId($userId);
        $user->WithAttribute($unchanged);
        $user->WithAttribute(new AttributeValue(100, 'should be removed'));
        $user->WithAttribute(new AttributeValue(2, 'new value'));

        $attributes = [$unchanged, $toChange, $toAdd];
        $user->ChangeCustomAttributes($attributes);

        $repo = new UserRepository();
        $repo->Update($user);

        $addNewCommand = new AddAttributeValueCommand($toAdd->AttributeId, $toAdd->Value, $userId, CustomAttributeCategory::USER);
        $removeOldCommand = new RemoveAttributeValueCommand(100, $userId);
        $removeUpdated = new RemoveAttributeValueCommand($toChange->AttributeId, $userId);
        $addUpdated = new AddAttributeValueCommand($toChange->AttributeId, $toChange->Value, $userId, CustomAttributeCategory::USER);

        $this->assertEquals($removeOldCommand, $this->db->_Commands[1]);
        $this->assertEquals($removeUpdated, $this->db->_Commands[2], 'need to remove before adding to make sure changed values are not immediately deleted');
        $this->assertEquals($addUpdated, $this->db->_Commands[3]);
        $this->assertEquals($addNewCommand, $this->db->_Commands[4]);
    }

    public function testDeletesUserById()
    {
        $userId = 8282;

        $command = new DeleteUserCommand($userId);

        $repo = new UserRepository();
        $repo->DeleteById($userId);

        $this->assertTrue($this->db->ContainsCommand($command));
    }

    public function testChangesEmailPreferences()
    {
        $id = 123;
        $user = new User();
        $user->WithId($id);
        $emailPreferences = new EmailPreferences();
        $emailPreferences->Add(EventCategory::Reservation, ReservationEvent::Updated);
        $user->WithEmailPreferences($emailPreferences);

        $user->ChangeEmailPreference(new ReservationUpdatedEvent(), false);
        $user->ChangeEmailPreference(new ReservationCreatedEvent(), false);
        $user->ChangeEmailPreference(new ReservationApprovedEvent(), true);

        $repo = new UserRepository();
        $repo->Update($user);

        $addEmailPreferenceCommand = new AddEmailPreferenceCommand($id, EventCategory::Reservation, ReservationEvent::Approved);
        $removeEmailPreferenceCommand = new DeleteEmailPreferenceCommand($id, EventCategory::Reservation, ReservationEvent::Updated);

        $this->assertTrue($this->db->ContainsCommand($addEmailPreferenceCommand));
        $this->assertTrue($this->db->ContainsCommand($removeEmailPreferenceCommand));
    }

    public function testAddsUser()
    {
        Date::_SetNow(Date::Now());

        $expectedId = 999;
        $firstName = 'f';
        $lastName = 'l';
        $emailAddress = 'e';
        $userName = 'u';
        $language = 'la';
        $timezone = 't';
        $password = 'p';
        $passwordSalt = 'ps';
        $phone = 'ph';
        $organization = 'o';
        $position = 'po';
        $scheduleId = 123232;

        $attr1 = new AttributeValue(3, 'value');
        $attr2 = new AttributeValue(4, 'value');

        $user = User::Create($firstName, $lastName, $emailAddress, $userName, $language, $timezone, $password, $passwordSalt);
        $user->ChangeAttributes($phone, $organization, $position);
        $user->ChangeCustomAttributes([$attr1, $attr2]);
        $user->ChangeEmailPreference(new ReservationApprovedEvent(), true);
        $user->EnablePublicProfile();
        $user->ChangeDefaultSchedule($scheduleId);
        $user->AcceptTerms(true);
        $publicId = $user->GetPublicId();

        $this->db->_ExpectedInsertId = $expectedId;
        $repo = new UserRepository();
        $newId = $repo->Add($user);

        $command = new RegisterUserCommand(
            $userName,
            $emailAddress,
            $firstName,
            $lastName,
            $password,
            $passwordSalt,
            $timezone,
            $language,
            Pages::DEFAULT_HOMEPAGE_ID,
            $phone,
            $organization,
            $position,
            AccountStatus::ACTIVE,
            $publicId,
            $scheduleId,
            Date::Now()
        );

        $addAttr1Command = new AddAttributeValueCommand($attr1->AttributeId, $attr1->Value, $expectedId, CustomAttributeCategory::USER);
        $addAttr2Command = new AddAttributeValueCommand($attr2->AttributeId, $attr2->Value, $expectedId, CustomAttributeCategory::USER);
        $addEmailPreferenceCommand = new AddEmailPreferenceCommand($expectedId, EventCategory::Reservation, ReservationEvent::Approved);

        $this->assertTrue($this->db->ContainsCommand($command));
        $this->assertTrue($this->db->ContainsCommand($addAttr1Command));
        $this->assertTrue($this->db->ContainsCommand($addAttr2Command));
        $this->assertTrue($this->db->ContainsCommand($addEmailPreferenceCommand));

        $this->assertEquals($expectedId, $newId);
        $this->assertEquals($expectedId, $user->Id());
    }

    public function testGetsResourceAdmins()
    {
        $resourceId = 123;
        $repo = new UserRepository();

        $command = new GetAllResourceAdminsCommand($resourceId);
        $userRows = [
            $this->GetUserRow(1, 'admin', 'guy', 'email'),
            $this->GetUserRow(),
            $this->GetUserRow(),
        ];

        $this->db->SetRows($userRows);

        $admins = $repo->GetResourceAdmins($resourceId);

        $admin = $admins[0];

        $this->assertEquals(3, count($admins));
        $this->assertEquals(1, $admin->Id());
        $this->assertEquals('admin', $admin->FirstName());
        $this->assertEquals('guy', $admin->LastName());
        $this->assertEquals($command, $this->db->_LastCommand);
    }

    public function testGetsApplicationAdmins()
    {
        $repo = new UserRepository();

        $command = new GetAllApplicationAdminsCommand($this->fakeConfig->GetAllAdminEmails());
        $userRows = [
            $this->GetUserRow(1, 'admin', 'guy', 'email'),
            $this->GetUserRow(),
            $this->GetUserRow(),
        ];

        $this->db->SetRows($userRows);

        $admins = $repo->GetApplicationAdmins();

        $admin = $admins[0];

        $this->assertEquals(3, count($admins));
        $this->assertEquals(1, $admin->Id());
        $this->assertEquals('admin', $admin->FirstName());
        $this->assertEquals('guy', $admin->LastName());
        $this->assertEquals($command, $this->db->_LastCommand);
    }

    public function testGetsGroupAdmins()
    {
        $userId = 1232;
        $repo = new UserRepository();

        $command = new GetAllGroupAdminsCommand($userId);
        $userRows = [
            $this->GetUserRow(1, 'admin', 'guy', 'email'),
            $this->GetUserRow(),
            $this->GetUserRow(),
        ];

        $this->db->SetRows($userRows);

        $admins = $repo->GetGroupAdmins($userId);

        $admin = $admins[0];

        $this->assertEquals(3, count($admins));
        $this->assertEquals(1, $admin->Id());
        $this->assertEquals('admin', $admin->FirstName());
        $this->assertEquals('guy', $admin->LastName());
        $this->assertEquals($command, $this->db->_LastCommand);
    }

    public function testAddsAccountActivation()
    {
        $userId = 11;
        $code = uniqid();

        $user = new FakeUser($userId);
        $repo = new UserRepository();

        $repo->AddActivation($user, $code);

        $command = new AddAccountActivationCommand($userId, $code);

        $this->assertEquals($command, $this->db->_LastCommand);
    }

    public function testFindUserIdByCode()
    {
        $code = 'code';
        $userId = 111;

        $this->db->SetRows([[ColumnNames::USER_ID => $userId]]);

        $repo = new UserRepository();
        $actualUserId = $repo->FindUserIdByCode($code);

        $command = new GetUserIdByActivationCodeCommand($code);

        $this->assertEquals($command, $this->db->_LastCommand);
        $this->assertEquals($userId, $actualUserId);
    }

    public function testDeleteActivation()
    {
        $code = 'code';

        $repo = new UserRepository();
        $repo->DeleteActivation($code);

        $command = new DeleteAccountActivationCommand($code);

        $this->assertEquals($command, $this->db->_LastCommand);
    }

    public function testChecksIfUserExists()
    {
        $email = 'em';
        $userName = 'un';
        $expectedId = 123;

        $this->db->SetRows([[ColumnNames::USER_ID => $expectedId]]);

        $repo = new UserRepository();
        $actualId = $repo->UserExists($email, $userName);

        $this->assertEquals($expectedId, $actualId);
    }

    public function testLogsCreditChanges()
    {
        $user = new User();
        $user->WithId(1);
        $user->WithCredits(10);
        $user->ChangeCurrentCredits(5, 'message');

        $repo = new UserRepository();
        $repo->Update($user);

        $command = new LogCreditActivityCommand(1, 10, 5, 'message');

        $this->assertTrue($this->db->ContainsCommand($command));
    }

    public function testAdminOnlyAttributesAreUnchangedOnUpdateIfNotProvided()
    {
        $regularAttribute = new AttributeValue(1, 'regular');
        $adminAttribute = new AttributeValue(2, 'admin');
        $user = User::FromRow($this->GetUserRow());
        $user->WithAttribute($regularAttribute, false);
        $user->WithAttribute($adminAttribute, true);

        $user->ChangeCustomAttributes([$regularAttribute]);

        $this->assertEquals('admin', $user->GetAttributeValue(2));
        $this->assertEquals(0, count($user->GetRemovedAttributes()));
    }

    private function GetUserRow(
        $userId = 1,
        $first = 'first',
        $last = 'last',
        $email = 'e@mail.com',
        $userName = 'username',
        $lastLogin = null,
        $timezone = 'UTC',
        $statusId = AccountStatus::ACTIVE,
        $scheduleId = 123,
        $preferences = null,
        $creditCount = null
    ) {
        $row =
                [
                    ColumnNames::USER_ID => $userId,
                    ColumnNames::USERNAME => $userName,
                    ColumnNames::FIRST_NAME => $first,
                    ColumnNames::LAST_NAME => $last,
                    ColumnNames::EMAIL => $email,
                    ColumnNames::LAST_LOGIN => $lastLogin,
                    ColumnNames::LANGUAGE_CODE => 'en_us',
                    ColumnNames::TIMEZONE_NAME => $timezone,
                    ColumnNames::USER_STATUS_ID => $statusId,
                    ColumnNames::PASSWORD => 'encryptedPassword',
                    ColumnNames::SALT => 'passwordsalt',
                    ColumnNames::HOMEPAGE_ID => 3,
                    ColumnNames::PHONE_NUMBER => '123-456-7890',
                    ColumnNames::POSITION => 'head honcho',
                    ColumnNames::ORGANIZATION => 'earth',
                    ColumnNames::USER_CREATED => '2011-01-04 12:12:12',
                    ColumnNames::ALLOW_CALENDAR_SUBSCRIPTION => 1,
                    ColumnNames::PUBLIC_ID => uniqid(),
                    ColumnNames::DEFAULT_SCHEDULE_ID => $scheduleId,
                    ColumnNames::USER_PREFERENCES => $preferences,
                    ColumnNames::CREDIT_COUNT => $creditCount
                ];

        return $row;
    }

    private function GetEmailPrefRows()
    {
        $row = [
            [ColumnNames::EVENT_CATEGORY => 'reservation', ColumnNames::EVENT_TYPE => ReservationEvent::Created],
            [ColumnNames::EVENT_CATEGORY => 'reservation', ColumnNames::EVENT_TYPE => ReservationEvent::Updated],
        ];

        return $row;
    }

    private function GetPermissionsRows()
    {
        return [
            [ColumnNames::RESOURCE_ID => 1, ColumnNames::PERMISSION_TYPE => ResourcePermissionType::Full],
            [ColumnNames::RESOURCE_ID => 2, ColumnNames::PERMISSION_TYPE => ResourcePermissionType::Full],
            [ColumnNames::RESOURCE_ID => 3, ColumnNames::PERMISSION_TYPE => ResourcePermissionType::Full],
        ];
    }

    private function GetGroupsRows()
    {
        $groupId1 = 98017;
        $groupId2 = 128736;
        return [
            [ColumnNames::GROUP_ID => $groupId1, ColumnNames::GROUP_NAME => 'group1', ColumnNames::GROUP_ADMIN_GROUP_ID => null, ColumnNames::ROLE_LEVEL => RoleLevel::GROUP_ADMIN],
            [ColumnNames::GROUP_ID => $groupId1, ColumnNames::GROUP_NAME => 'group1', ColumnNames::GROUP_ADMIN_GROUP_ID => null, ColumnNames::ROLE_LEVEL => RoleLevel::RESOURCE_ADMIN],
            [ColumnNames::GROUP_ID => $groupId2, ColumnNames::GROUP_NAME => 'group1', ColumnNames::GROUP_ADMIN_GROUP_ID => $groupId1, ColumnNames::ROLE_LEVEL => RoleLevel::NONE],
        ];
    }

    private function GetOwnedGroupRows()
    {
        return [
            [ColumnNames::GROUP_ID => 10000, ColumnNames::GROUP_NAME => 'G1'],
            [ColumnNames::GROUP_ID => 20000, ColumnNames::GROUP_NAME => 'G2'],
        ];
    }

    private function GetPreferenceRows()
    {
        return [
            [ColumnNames::USER_ID => 1, ColumnNames::PREFERENCE_NAME => 'n1', ColumnNames::PREFERENCE_VALUE => 'v1'],
            [ColumnNames::USER_ID => 1, ColumnNames::PREFERENCE_NAME => 'n2', ColumnNames::PREFERENCE_VALUE => 'v2'],
        ];
    }

    private function GetAttributeRows()
    {
        $car = new CustomAttributeValueRow();
        $car->With(1, 'value')
            ->With(2, 'value2');
        return $car->Rows();
    }
}
