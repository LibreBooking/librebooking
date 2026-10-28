<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/Install/InstallSecurityGuard.php');

class InstallSecurityGuardTest extends TestBase
{
    /**
     * @var InstallSecurityGuard
     */
    private $guard;

    public function setUp(): void
    {
        parent::setup();

        $this->guard = new InstallSecurityGuard();
    }

    public function testCorrectPasswordAuthenticates()
    {
        $this->fakeConfig->SetKey(ConfigKeys::INSTALL_PASSWORD, 'secret');

        $this->assertTrue($this->guard->ValidatePassword('secret'));
        $this->assertTrue($this->guard->IsAuthenticated());
    }

    public function testWrongPasswordDoesNotAuthenticate()
    {
        $this->fakeConfig->SetKey(ConfigKeys::INSTALL_PASSWORD, 'secret');

        $this->assertFalse($this->guard->ValidatePassword('wrong'));
        $this->assertFalse($this->guard->IsAuthenticated());
    }

    public function testEmptyConfiguredPasswordNeverAuthenticates()
    {
        $this->fakeConfig->SetKey(ConfigKeys::INSTALL_PASSWORD, '');

        $this->assertFalse($this->guard->ValidatePassword(''));
        $this->assertFalse($this->guard->IsAuthenticated());
    }

    public function testFailedAttemptClearsEarlierAuthentication()
    {
        $this->fakeConfig->SetKey(ConfigKeys::INSTALL_PASSWORD, 'secret');
        $this->guard->ValidatePassword('secret');

        $this->guard->ValidatePassword('wrong');

        $this->assertFalse($this->guard->IsAuthenticated());
    }
}
