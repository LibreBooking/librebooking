<?php

declare(strict_types=1);

namespace LibreBooking\Tests\Application\Authentication;

use CSRFToken;
use FakeUser;
use IRoleService;
use LibreBooking\Application\Authentication\UserSessionBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use TestBase;
use UserGroup;
use UserSession;

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
require_once(ROOT_DIR . 'lib/Server/namespace.php');

class UserSessionBuilderTest extends TestBase
{
    private IRoleService&MockObject $roleService;

    private UserSessionBuilder $builder;

    private FakeUser $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->roleService = $this->createMock(IRoleService::class);
        $this->builder = new UserSessionBuilder(roleService: $this->roleService);

        $this->user = new FakeUser(userId: 191, email: 'my@email.com');
        $this->user->ChangeName('Test', 'Name');
        $this->user->ChangeTimezone('America/Chicago');
        $this->user->ChangeDefaultHomePage(2);
        $this->user->SetLanguage('en_us');
        $this->user->WithPublicId('public_id');
        $this->user->WithDefaultSchedule(111);
        $this->user->WithGroups([new UserGroup(999, 'one'), new UserGroup(888, 'two')]);
        $this->user->WithOwnedGroups([new UserGroup(777, 'owned')]);

        CSRFToken::$_Token = 'token';
    }

    public function testBuildsSessionFromUserData(): void
    {
        $this->roleService->method('IsApplicationAdministrator')->with($this->user)->willReturn(true);
        $this->roleService->method('IsGroupAdministrator')->with($this->user)->willReturn(false);
        $this->roleService->method('IsResourceAdministrator')->with($this->user)->willReturn(true);
        $this->roleService->method('IsScheduleAdministrator')->with($this->user)->willReturn(false);

        $session = $this->builder->buildUserSession(user: $this->user, loginTime: '2026-10-01 12:00:00');

        $expected = new UserSession(191);
        $expected->Email = 'my@email.com';
        $expected->FirstName = 'Test';
        $expected->LastName = 'Name';
        $expected->Timezone = 'America/Chicago';
        $expected->HomepageId = 2;
        $expected->LanguageCode = 'en_us';
        $expected->LoginTime = '2026-10-01 12:00:00';
        $expected->PublicId = 'public_id';
        $expected->ScheduleId = 111;
        $expected->IsAdmin = true;
        $expected->IsGroupAdmin = false;
        $expected->IsResourceAdmin = true;
        $expected->IsScheduleAdmin = false;
        $expected->CSRFToken = 'token';
        $expected->Groups = [999, 888];
        $expected->AdminGroups = [777];

        $this->assertEquals($expected, $session);
    }

    public function testDoesNotChangeTheUserOrWriteToTheDatabase(): void
    {
        $before = clone $this->user;

        $this->builder->buildUserSession(user: $this->user, loginTime: '2026-10-01 12:00:00');

        $this->assertEquals($before, $this->user);
        $this->assertEmpty($this->db->_Commands);
    }
}
