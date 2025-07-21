<?php

require_once(ROOT_DIR . 'Presenters/ActivationPresenter.php');

class ActivationPresenterTest extends TestBase
{
    /**
     * @var ActivationPresenter
     */
    private $presenter;

    /**
     * @var IActivationPage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var FakeActivation
     */
    private $accountActivation;

    /**
     * @var FakeWebAuthentication
     */
    private $auth;

    public function setUp(): void
    {
        parent::setup();

        $this->page = $this->createMock('IActivationPage');
        $this->accountActivation = new FakeActivation();
        $this->auth = new FakeWebAuthentication();

        $this->presenter = new ActivationPresenter($this->page, $this->accountActivation, $this->auth);
    }

    public function testActivatesAccount()
    {
        $user = new FakeUser(12);

        $activationSuccess = new ActivationResult(true, $user);
        $this->accountActivation->_ActivationResult = $activationSuccess;
        $activationCode = uniqid();

        $this->page->expects($this->once())
                ->method('GetActivationCode')
                ->willReturn($activationCode);

        $this->page->expects($this->once())
                ->method('Redirect')
                ->with($this->equalTo(Pages::UrlFromId($user->Homepage())));

        $this->presenter->PageLoad();

        $this->assertEquals($activationCode, $this->accountActivation->_LastActivationCode);
        $this->assertTrue($this->auth->_LoginCalled);
        $this->assertEquals($user->EmailAddress(), $this->auth->_LastLogin);
        $this->assertEquals(new WebLoginContext(new LoginData(false, $user->Language())), $this->auth->_LastLoginContext);
    }

    public function testWhenAccountCannotBeActivated()
    {
        $activationFailed = new ActivationResult(false);
        $this->accountActivation->_ActivationResult = $activationFailed;
        $activationCode = uniqid();

        $this->page->expects($this->once())
                ->method('GetActivationCode')
                ->willReturn($activationCode);

        $this->page->expects($this->once())
                ->method('ShowError');

        $this->presenter->PageLoad();
    }
}
