<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

class UserSessionFactoryTest extends TestBase
{
    /**
     * @var IRoleService|PHPUnit\Framework\MockObject\MockObject
     */
    private $roleService;

    private UserSessionFactory $factory;

    public function setup(): void
    {
        parent::setup();

        $this->roleService = $this->createMock('IRoleService');
        $this->factory = new UserSessionFactory($this->roleService);
    }

    public function testCreateBuildsSessionFromUserAndRoleService()
    {
        CSRFToken::$_Token = 'token';

        $id = 191;
        $fname = 'Test';
        $lname = 'Name';
        $email = 'my@email.com';
        $timezone = 'America/Chicago';
        $homepageId = 2;
        $languageCode = 'en_us';
        $publicId = 'public_id';
        $scheduleId = 111;
        $groups = [new UserGroup(999, '1'), new UserGroup(888, '2')];
        $loginTime = LoginTime::Now();

        $user = new FakeUser();
        $user->WithId($id);
        $user->ChangeName($fname, $lname);
        $user->ChangeEmailAddress($email);
        $user->ChangeTimezone($timezone);
        $user->ChangeDefaultHomePage($homepageId);
        $user->SetLanguage($languageCode);
        $user->WithPublicId($publicId);
        $user->WithDefaultSchedule($scheduleId);
        $user->WithGroups($groups);

        $this->roleService->method('IsApplicationAdministrator')->with($user)->willReturn(true);
        $this->roleService->method('IsGroupAdministrator')->with($user)->willReturn(true);
        $this->roleService->method('IsResourceAdministrator')->with($user)->willReturn(true);
        $this->roleService->method('IsScheduleAdministrator')->with($user)->willReturn(true);

        $actual = $this->factory->Create($user, $loginTime);

        $expected = new UserSession($id);
        $expected->FirstName = $fname;
        $expected->LastName = $lname;
        $expected->Email = $email;
        $expected->Timezone = $timezone;
        $expected->HomepageId = $homepageId;
        $expected->LanguageCode = $languageCode;
        $expected->LoginTime = $loginTime;
        $expected->PublicId = $publicId;
        $expected->ScheduleId = $scheduleId;
        $expected->IsAdmin = true;
        $expected->IsGroupAdmin = true;
        $expected->IsResourceAdmin = true;
        $expected->IsScheduleAdmin = true;
        $expected->CSRFToken = CSRFToken::$_Token;
        foreach ($groups as $group) {
            $expected->Groups[] = $group->GroupId;
        }

        $this->assertEquals($expected, $actual);
    }

    public function testCreateDoesNotCallLoginOrPersistTheUser()
    {
        $user = $this->createMock('User');
        $user->method('EmailAddress')->willReturn('a@b.com');
        $user->method('Groups')->willReturn([]);
        $user->method('GetAdminGroups')->willReturn([]);
        $user->expects($this->never())->method('Login');

        $this->factory->Create($user, LoginTime::Now());
    }
}
