<?php

declare(strict_types=1);

use LibreBooking\Application\Authentication\UserSessionBuilder;
use PHPUnit\Framework\MockObject\MockObject;

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

class GuestUserServiceTest extends TestBase
{
    private const EMAIL = 'test@email.com';
    private const USER_ID = 123;

    private IAuthentication&MockObject $authentication;

    /**
     * @var GuestUserService
     */
    private $service;

    /**
     * @var FakeRegistration
     */
    private $registration;

    /**
     * @var FakeUserRepository
     */
    private $userRepository;

    /**
     * @var FakeAuthorizationService
     */
    private $roleService;

    private FakeUser $user;

    public function setUp(): void
    {
        parent::setup();

        $this->authentication = $this->createMock(IAuthentication::class);
        $this->registration = new FakeRegistration();
        $this->userRepository = new FakeUserRepository();
        $this->user = new FakeUser(self::USER_ID, self::EMAIL);
        $this->userRepository->_User = $this->user;
        $this->roleService = new FakeAuthorizationService();

        $this->service = new GuestUserService(
            authentication: $this->authentication,
            registration: $this->registration,
            userRepository: $this->userRepository,
            userSessionBuilder: new UserSessionBuilder(roleService: $this->roleService)
        );
    }

    public function testWhenUserAlreadyExists()
    {
        $this->authentication->expects($this->never())->method('Login');

        $session = $this->service->CreateOrLoad(self::EMAIL);

        $this->assertTrue($session->IsLoggedIn());
        $this->assertSame(self::USER_ID, $session->UserId);
        $this->assertSame(self::EMAIL, $session->Email);
        $this->assertFalse($this->registration->_RegisterCalled);
        $this->assertUserWasNotChanged();
    }

    public function testExistingUserSessionKeepsTheUsersRoles()
    {
        $this->roleService->_IsApplicationAdministrator = true;
        $this->roleService->_IsResourceAdministrator = true;

        $session = $this->service->CreateOrLoad(self::EMAIL);

        $this->assertTrue($session->IsAdmin);
        $this->assertTrue($session->IsResourceAdmin);
        $this->assertFalse($session->IsGroupAdmin);
    }

    public function testWhenUserDoesntExistCreateGuest()
    {
        $email = 'guest@user.com';
        $guestSession = new FakeUserSession();
        $this->userRepository->_User = User::Null();
        $this->fakeResources->CurrentLanguage = 'en_gb';
        $this->authentication->expects($this->once())
            ->method('Login')
            ->with(
                $this->equalTo($email),
                $this->callback(fn (ILoginContext $context) => $context->GetData()->Language === 'en_gb')
            )
            ->willReturn($guestSession);

        $session = $this->service->CreateOrLoad($email);

        $this->assertSame($guestSession, $session);
        $this->assertEquals($email, $this->registration->_Login);
        $this->assertEquals($email, $this->registration->_Email);
    }

    public function testInactiveAccountIsRefusedAndLeftUntouched()
    {
        $this->user->SetStatus(AccountStatus::INACTIVE);
        // A decorating authentication plugin may activate the account in Login(), so it must not be called.
        $this->authentication->expects($this->never())->method('Login');

        $session = $this->service->CreateOrLoad(self::EMAIL);

        $this->assertFalse($session->IsLoggedIn());
        $this->assertFalse($this->registration->_RegisterCalled, 'must not register a guest over an existing account');
        $this->assertSame(AccountStatus::INACTIVE, $this->user->StatusId());
        $this->assertUserWasNotChanged();
    }

    public function testEmailExistsForAnActiveAccountWithoutLoggingIn()
    {
        $this->authentication->expects($this->never())->method('Login');

        $this->assertTrue($this->service->EmailExists(self::EMAIL));
        $this->assertUserWasNotChanged();
    }

    public function testEmailDoesNotExistForAnInactiveAccount()
    {
        $this->user->SetStatus(AccountStatus::INACTIVE);
        $this->authentication->expects($this->never())->method('Login');

        $this->assertFalse($this->service->EmailExists(self::EMAIL));
        $this->assertUserWasNotChanged();
    }

    public function testEmailDoesNotExistForAnUnknownAddress()
    {
        $this->userRepository->_User = User::Null();
        $this->authentication->expects($this->never())->method('Login');

        $this->assertFalse($this->service->EmailExists('nobody@user.com'));
    }

    private function assertUserWasNotChanged(): void
    {
        $this->assertNull($this->userRepository->_UpdatedUser);
        $this->assertNull($this->userRepository->_LoginDataUpdatedUser);
        $this->assertNull($this->userRepository->_AddedUser);
    }
}
