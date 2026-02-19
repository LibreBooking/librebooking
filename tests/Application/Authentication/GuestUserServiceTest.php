<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Authentication/GuestUserService.php');

class GuestUserServiceTest extends TestBase
{
    /**
     * @var FakeAuthentication
     */
    private $authentication;

    /**
     * @var GuestUserService
     */
    private $service;

    /**
     * @var FakeRegistration
     */
    private $registration;

    public function setUp(): void
    {
        $this->authentication = new FakeAuthentication();
        $this->registration = new FakeRegistration();
        $this->service = new GuestUserService($this->authentication, $this->registration);
        parent::setup();
    }

    public function testWhenUserAlreadyExists()
    {
        $email = 'test@email.com';
        $session = new FakeUserSession();
        $this->authentication->_UserSession = $session;

        $user = $this->service->CreateOrLoad($email);

        $this->assertEquals($session, $user);
    }

    public function testWhenUserDoesntExistCreateGuest()
    {
        $email = 'guest@user.com';
        $this->authentication->_UserSession = new NullUserSession();

        $this->service->CreateOrLoad($email);

        $this->assertEquals($email, $this->registration->_Login);
        $this->assertEquals($email, $this->registration->_Email);
    }
}
