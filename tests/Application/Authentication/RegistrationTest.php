<?php

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

class RegistrationTest extends TestBase
{
    /**
     * @var Registration
     */
    private $registration;

    /**
     * @var FakePasswordEncryption
     */
    private $fakeEncryption;

    /**
     * @var IUserRepository
     */
    private $userRepository;

    /**
     * @var FakeGroupViewRepository
     */
    private $groupRepository;

    private $login = 'testlogin';
    private $email = 'test@test.com';
    private $fname = 'First';
    private $lname = 'Last';
    private $phone = '123.123.1234';
    private $organization = 'organization';
    private $position = 'position';
    private $additionalFields = [];
    private $password = 'password';
    private $timezone = 'America/New_York';
    private $language = 'en_US';
    private $homepageId = 1;
    private $attributes = [];
    private $groups = null;
    private $acceptTerms = true;

    public function setUp(): void
    {
        parent::setup();

        $this->userRepository = $this->createMock('IUserRepository');
        $this->groupRepository = new FakeGroupViewRepository();

        $this->fakeEncryption = new FakePasswordEncryption();
        $this->registration = new Registration($this->fakeEncryption, $this->userRepository, null, null, $this->groupRepository);

        $this->additionalFields = ['phone' => $this->phone, 'organization' => $this->organization, 'position' => $this->position];
        $this->attributes = [new AttributeValue(1, 1)];
    }

    public function teardown(): void
    {
        parent::teardown();
        $this->registration = null;
    }

    public function testRegistersUserWhenNoManualActivationRequired()
    {
        $this->fakeConfig->SetKey(ConfigKeys::REGISTRATION_REQUIRE_EMAIL_ACTIVATION, null);

        $this->groups = [new UserGroup(1, 'whaterver')];

        $user = User::Create(
            $this->fname,
            $this->lname,
            $this->email,
            $this->login,
            $this->language,
            $this->timezone,
            $this->fakeEncryption->_Encrypted,
            $this->fakeEncryption->_Salt,
            $this->homepageId
        );

        $user->ChangeAttributes($this->phone, $this->organization, $this->position);
        $user->ChangeCustomAttributes($this->attributes);
        $user->WithGroups($this->groups);
        $user->AcceptTerms($this->acceptTerms);

        $this->userRepository->expects($this->once())
            ->method('Add')
            ->with($this->equalTo($user));

        $registeredUser = $this->registration->Register(
            $this->login,
            $this->email,
            $this->fname,
            $this->lname,
            $this->password,
            $this->timezone,
            $this->language,
            $this->homepageId,
            $this->additionalFields,
            $this->attributes,
            $this->groups,
            $this->acceptTerms
        );

        $this->assertTrue($this->fakeEncryption->_EncryptPasswordCalled);
        $this->assertEquals($this->password, $this->fakeEncryption->_LastPassword);
        $this->assertEquals($user, $registeredUser);
    }

    public function testDoesNotActivateUserIfManualActivationIsRequired()
    {
        $this->fakeConfig->SetKey(ConfigKeys::REGISTRATION_REQUIRE_EMAIL_ACTIVATION, 'true');

        $user = User::CreatePending(
            $this->fname,
            $this->lname,
            $this->email,
            $this->login,
            $this->language,
            $this->timezone,
            $this->fakeEncryption->_Encrypted,
            $this->fakeEncryption->_Salt,
            $this->homepageId
        );

        $user->ChangeAttributes($this->phone, $this->organization, $this->position);
        $user->ChangeCustomAttributes($this->attributes);

        $this->userRepository->expects($this->once())
            ->method('Add')
            ->with($this->equalTo($user));

        $registeredUser = $this->registration->Register(
            $this->login,
            $this->email,
            $this->fname,
            $this->lname,
            $this->password,
            $this->timezone,
            $this->language,
            $this->homepageId,
            $this->additionalFields,
            $this->attributes
        );

        $this->assertTrue($this->fakeEncryption->_EncryptPasswordCalled);
        $this->assertEquals($this->password, $this->fakeEncryption->_LastPassword);
        $this->assertEquals($user, $registeredUser);
    }

    public function testAdminRegistrationNeverNeedsActivation()
    {
        $this->fakeConfig->SetKey(ConfigKeys::REGISTRATION_REQUIRE_EMAIL_ACTIVATION, 'true');

        $user = User::Create(
            $this->fname,
            $this->lname,
            $this->email,
            $this->login,
            $this->language,
            $this->timezone,
            $this->fakeEncryption->_Encrypted,
            $this->fakeEncryption->_Salt,
            $this->homepageId
        );

        $user->ChangeAttributes($this->phone, $this->organization, $this->position);
        $user->ChangeCustomAttributes($this->attributes);

        $this->userRepository->expects($this->once())
            ->method('Add')
            ->with($this->equalTo($user));

        $adminRegistration = new AdminRegistration($this->fakeEncryption, $this->userRepository);

        $registeredUser = $adminRegistration->Register(
            $this->login,
            $this->email,
            $this->fname,
            $this->lname,
            $this->password,
            $this->timezone,
            $this->language,
            $this->homepageId,
            $this->additionalFields,
            $this->attributes
        );

        $this->assertTrue($this->fakeEncryption->_EncryptPasswordCalled);
        $this->assertEquals($this->password, $this->fakeEncryption->_LastPassword);
        $this->assertEquals($user, $registeredUser);
    }

    public function testAutoAssignsAllResourcesForThisUser()
    {
        $expectedUserId = 100;
        $this->userRepository->expects($this->once())
            ->method('Add')
            ->with($this->anything())
            ->willReturn($expectedUserId);

        $this->registration->Register(
            $this->login,
            $this->email,
            $this->fname,
            $this->lname,
            $this->password,
            $this->timezone,
            $this->language,
            $this->homepageId,
            $this->additionalFields
        );

        $command = new AutoAssignPermissionsCommand($expectedUserId);

        $this->assertEquals($command, $this->db->_Commands[0]);
    }

    public function testAutoSubscribesUserToEmails()
    {
        $this->fakeConfig->SetKey(ConfigKeys::REGISTRATION_AUTO_SUBSCRIBE_EMAIL, 'true');

        $user = User::Create(
            $this->fname,
            $this->lname,
            $this->email,
            $this->login,
            $this->language,
            $this->timezone,
            $this->fakeEncryption->_Encrypted,
            $this->fakeEncryption->_Salt,
            $this->homepageId
        );

        $user->ChangeAttributes($this->phone, $this->organization, $this->position);

        $user->ChangeEmailPreference(new ReservationApprovedEvent(), true);
        $user->ChangeEmailPreference(new ReservationCreatedEvent(), true);
        $user->ChangeEmailPreference(new ReservationUpdatedEvent(), true);
        $user->ChangeEmailPreference(new ReservationDeletedEvent(), true);
        $user->ChangeEmailPreference(new ReservationSeriesEndingEvent(), true);
        $user->ChangeEmailPreference(new ParticipationChangedEvent(), true);

        $this->userRepository->expects($this->once())
            ->method('Add')
            ->with($this->equalTo($user));

        $this->registration->Register(
            $this->login,
            $this->email,
            $this->fname,
            $this->lname,
            $this->password,
            $this->timezone,
            $this->language,
            $this->homepageId,
            $this->additionalFields
        );
    }

    public function testSynchronizeUpdatesExistingUser()
    {
        $userId = 111;

        $username = 'un';
        $email = 'em';
        $fname = 'fn';
        $lname = 'ln';
        $phone = 'ph';
        $inst = 'or';
        $title = 'title';
        $encryptedPassword = $this->fakeEncryption->_Encrypted;
        $salt = $this->fakeEncryption->_Salt;
        $groups = [new UserGroup(1, '1'), new UserGroup(2, '2')];

        $this->groupRepository->_AddGroup(new GroupItemView(1, '1'));
        $this->groupRepository->_AddGroup(new GroupItemView(2, '2'));

        $updatedUser = new User();
        $updatedUser->ChangeGroups($groups);

        $this->userRepository->expects($this->once())
            ->method('UserExists')
            ->with($this->equalTo($email), $this->equalTo($username))
            ->willReturn(true);

        $this->userRepository->expects($this->once())
            ->method('LoadByUsername')
            ->with($this->equalTo($username))
            ->willReturn($updatedUser);

        $this->userRepository->expects($this->once())
            ->method('Update')
            ->with($this->equalTo($updatedUser))
            ->willReturn($updatedUser);

        $user = new AuthenticatedUser($username, $email, $fname, $lname, 'password', 'en_US', 'UTC', $phone, $inst, $title, ['1', '2']);
        $expectedCommand = new UpdateUserFromLdapCommand($username, $email, $fname, $lname, $encryptedPassword, $salt, $phone, $inst, $title);

        $this->registration->Synchronize($user);

        $this->assertTrue($this->db->ContainsCommand($expectedCommand));
    }

    public function testSynchronizeRegistersNewUser()
    {
        $username = 'un';
        $email = 'em';
        $fname = 'fn';
        $lname = 'ln';
        $phone = 'ph';
        $inst = 'or';
        $title = 'title';
        $langCode = 'en_US';
        $timezone = 'UTC';

        $groups = ['group1', 'g2'];

        $this->groupRepository->_AddGroup(new GroupItemView(1, 'group1'));
        $this->groupRepository->_AddGroup(new GroupItemView(2, 'g2'));

        $encryptedPassword = $this->fakeEncryption->_Encrypted;
        $salt = $this->fakeEncryption->_Salt;

        $user = new AuthenticatedUser($username, $email, $fname, $lname, 'password', $langCode, $timezone, $phone, $inst, $title, $groups);

        $expectedUser = User::Create(
            $fname,
            $lname,
            $email,
            $username,
            $langCode,
            $timezone,
            $encryptedPassword,
            $salt,
            Pages::DEFAULT_HOMEPAGE_ID
        );

        $expectedUser->ChangeAttributes($phone, $inst, $title);
        $expectedUser->WithGroups([new UserGroup(1, 'group1'), new UserGroup(2, 'g2')]);

        $this->userRepository->expects($this->once())
            ->method('UserExists')
            ->with($this->equalTo($email), $this->equalTo($username))
            ->willReturn(null);

        $this->userRepository->expects($this->once())
            ->method('Add')
            ->with($this->equalTo($expectedUser));

        $this->registration->Synchronize($user);
    }

    public function testAuthenticatedUserReturnsNullsForAllBlankValues()
    {
        $username = 'un';
        $email = 'em';
        $fname = '';
        $lname = ' ';
        $phone = '   ';
        $inst = 'or';
        $title = 'title';
        $langCode = 'en_US';
        $timezone = 'UTC';

        $user = new AuthenticatedUser($username, $email, $fname, $lname, 'password', $langCode, $timezone, $phone, $inst, $title);

        $this->assertNull($user->FirstName(), "needs to be null to make sure we do not clear values in the database");
        $this->assertNull($user->LastName(), "needs to be null to make sure we do not clear values in the database");
        $this->assertNull($user->Phone(), "needs to be null to make sure we do not clear values in the database");
        $this->assertEquals($email, $user->Email());
    }

    public function testSyncsGroups()
    {
        $userRepository = new FakeUserRepository();
        $userRepository->_Exists = false;

        $username = 'un';
        $email = 'e';

        $user = new AuthenticatedUser($username, $email, '', '', 'password', '', '', '', '', '', ['Group1', 'Group2', 'Group3']);

        $this->groupRepository->_AddGroup(new GroupItemView(1, 'Group1'));
        $this->groupRepository->_AddGroup(new GroupItemView(3, 'Group3'));
        $this->groupRepository->_AddGroup(new GroupItemView(4, 'Group4'));

        $registration = new Registration($this->fakeEncryption, $userRepository, null, null, $this->groupRepository);

        $registration->Synchronize($user);

        $userGroups = $userRepository->_AddedUser->Groups();
        $this->assertEquals(2, count($userGroups));
        $this->assertEquals(new UserGroup(1, 'Group1'), $userGroups[0]);
        $this->assertEquals(new UserGroup(3, 'Group3'), $userGroups[1]);
    }
}
