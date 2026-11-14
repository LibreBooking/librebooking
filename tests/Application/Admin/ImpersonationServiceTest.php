<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Admin/ImpersonationService.php');

class ImpersonationServiceTest extends TestBase
{
    private IRoleService $roleService;
    private FakeUserRepository $userRepository;
    private ImpersonationService $service;

    public function setUp(): void
    {
        parent::setup();

        $this->roleService = $this->createMock('IRoleService');
        $this->roleService->method('IsApplicationAdministrator')->willReturn(false);
        $this->roleService->method('IsGroupAdministrator')->willReturn(false);
        $this->roleService->method('IsResourceAdministrator')->willReturn(false);
        $this->roleService->method('IsScheduleAdministrator')->willReturn(false);

        $this->userRepository = new FakeUserRepository();

        // The default FakeUserSession (the admin, in these tests) has UserId 1.
        $admin = new FakeUser(1, 'admin@example.com');
        $admin->Activate();
        $this->userRepository->_UserById[1] = $admin;

        $this->fakeUser->IsAdmin = true;
        $this->fakeConfig->SetKey(ConfigKeys::ADMIN_IMPERSONATION_ENABLED, true);

        $this->service = new ImpersonationService($this->roleService, $this->userRepository);
    }

    private function AddTarget(int $id = 9928, string $email = 'target@example.com'): FakeUser
    {
        $target = new FakeUser($id, $email);
        $target->Activate();
        $this->userRepository->_UserById[$id] = $target;
        return $target;
    }

    public function testStartImpersonationStashesAdminSessionAndSwitchesToTargetUser()
    {
        $adminSession = $this->fakeServer->GetUserSession();
        $this->AddTarget();

        $this->assertTrue($this->service->StartImpersonation($this->userRepository->LoadById(9928)));

        $activeSession = $this->fakeServer->GetUserSession();
        $this->assertEquals(9928, $activeSession->UserId);
        $this->assertEquals('target@example.com', $activeSession->Email);
        $this->assertFalse($activeSession->IsAdmin);

        $this->assertSame($adminSession, $this->fakeServer->GetSession(SessionKeys::IMPERSONATOR_SESSION));
        $this->assertTrue($this->service->IsImpersonating());
        $this->assertSame($adminSession, $this->service->GetImpersonator());
    }

    public function testStartImpersonationIsNoOpWhenFeatureDisabled()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ADMIN_IMPERSONATION_ENABLED, false);

        $adminSession = $this->fakeServer->GetUserSession();
        $this->AddTarget();

        $this->assertFalse($this->service->StartImpersonation($this->userRepository->LoadById(9928)));

        $this->assertSame($adminSession, $this->fakeServer->GetUserSession());
        $this->assertNull($this->fakeServer->GetSession(SessionKeys::IMPERSONATOR_SESSION));
        $this->assertFalse($this->service->IsImpersonating());
    }

    public function testStartImpersonationDeniedWhenCurrentUserIsNotAdmin()
    {
        $this->fakeUser->IsAdmin = false;
        $this->AddTarget();

        $this->assertFalse($this->service->StartImpersonation($this->userRepository->LoadById(9928)));
        $this->assertFalse($this->service->IsImpersonating());
    }

    public function testStartImpersonationDeniedWhenTargetIsSelf()
    {
        $this->assertFalse($this->service->StartImpersonation($this->userRepository->LoadById($this->fakeUser->UserId)));
        $this->assertFalse($this->service->IsImpersonating());
    }

    public function testStartImpersonationDeniedWhenTargetDoesNotExist()
    {
        $this->userRepository->_User = new User();

        $this->assertFalse($this->service->StartImpersonation($this->userRepository->LoadById(9928)));
        $this->assertFalse($this->service->IsImpersonating());
    }

    public function testStartImpersonationDeniedWhenTargetIsInactive()
    {
        $this->AddTarget()->Deactivate();

        $this->assertFalse($this->service->StartImpersonation($this->userRepository->LoadById(9928)));
        $this->assertFalse($this->service->IsImpersonating());
    }

    public function testStartImpersonationDeniedWhenTargetIsAnApplicationAdministrator()
    {
        $this->AddTarget();
        $roleService = $this->createMock('IRoleService');
        $roleService->method('IsApplicationAdministrator')->willReturn(true);
        $service = new ImpersonationService($roleService, $this->userRepository);

        $this->assertFalse($service->StartImpersonation($this->userRepository->LoadById(9928)));
        $this->assertFalse($service->IsImpersonating());
    }

    public function testStopImpersonationRebuildsAdminSessionWithoutEndingTheSession()
    {
        $adminSession = $this->fakeServer->GetUserSession();
        $this->AddTarget();
        $this->assertTrue($this->service->StartImpersonation($this->userRepository->LoadById(9928)));

        $this->service->StopImpersonation();

        $restored = $this->fakeServer->GetUserSession();
        $this->assertEquals($adminSession->UserId, $restored->UserId);
        // Rebuilt from the current User record, not copied from the stashed
        // session - reflects live data (see the FakeUser registered in setUp).
        $this->assertEquals('admin@example.com', $restored->Email);
        $this->assertNull($this->fakeServer->GetSession(SessionKeys::IMPERSONATOR_SESSION));
        $this->assertFalse($this->service->IsImpersonating());

        // Ending impersonation must not destroy the whole PHP session
        // (that's what EndSession() does, and it's reserved for logout).
        $this->assertNull($this->fakeServer->_EndedSession);
    }

    public function testStopImpersonationRefreshesRoleFlagsFromCurrentRoleService()
    {
        // Simulate a session that was stashed while the admin still had the
        // group-admin role - the role service (mocked to return false for
        // everything in setUp) represents that role having since been revoked.
        $staleAdminSession = new UserSession(1);
        $staleAdminSession->Email = 'admin@example.com';
        $staleAdminSession->IsAdmin = true;
        $staleAdminSession->IsGroupAdmin = true;
        $this->fakeServer->SetUserSession($staleAdminSession);

        $this->AddTarget();
        $this->assertTrue($this->service->StartImpersonation($this->userRepository->LoadById(9928)));

        $this->service->StopImpersonation();

        $restored = $this->fakeServer->GetUserSession();
        $this->assertFalse($restored->IsGroupAdmin);
    }

    public function testStopImpersonationRotatesCSRFToken()
    {
        $adminSession = $this->fakeServer->GetUserSession();
        $originalToken = $adminSession->CSRFToken;
        $this->AddTarget();
        $this->assertTrue($this->service->StartImpersonation($this->userRepository->LoadById(9928)));

        $this->service->StopImpersonation();

        $restored = $this->fakeServer->GetUserSession();
        $this->assertNotEmpty($restored->CSRFToken);
        $this->assertNotEquals($originalToken, $restored->CSRFToken);
    }

    public function testStopImpersonationEndsTheSessionWhenAdminAccountIsNoLongerActive()
    {
        $adminSession = $this->fakeServer->GetUserSession();
        $this->AddTarget();
        $this->assertTrue($this->service->StartImpersonation($this->userRepository->LoadById(9928)));

        $this->userRepository->_UserById[1]->Deactivate();

        $this->service->StopImpersonation();

        $this->assertEquals(SessionKeys::USER_SESSION, $this->fakeServer->_EndedSession);
    }

    public function testStopImpersonationIsNoOpWhenNotCurrentlyImpersonating()
    {
        $originalSession = $this->fakeServer->GetUserSession();

        $this->service->StopImpersonation();

        $this->assertSame($originalSession, $this->fakeServer->GetUserSession());
        $this->assertNull($this->fakeServer->_EndedSession);
    }

    public function testIsImpersonatingIsFalseByDefault()
    {
        $this->assertFalse($this->service->IsImpersonating());
        $this->assertNull($this->service->GetImpersonator());
    }
}
