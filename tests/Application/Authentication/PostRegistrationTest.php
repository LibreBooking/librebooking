<?php

declare(strict_types=1);

class PostRegistrationTest extends TestBase
{
    /**
     * @var FakeRegistrationPage
     */
    private $page;

    /**
     * @var FakeWebAuthentication
     */
    private $fakeAuth;

    /**
     * @var FakeUser
     */
    private $user;

    /**
     * @var IPostRegistration
     */
    private $postRegistration;

    /**
     * @var FakeActivation
     */
    private $activation;

    /**
     * @var WebLoginContext
     */
    private $context;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeRegistrationPage();
        $this->fakeAuth = new FakeWebAuthentication();

        $this->activation = new FakeActivation();

        $this->user = new FakeUser();
        $this->user->ChangeEmailAddress('e@m.com');
        $this->context = new WebLoginContext(new LoginData(false, 'en_us'));

        $this->postRegistration = new PostRegistration($this->fakeAuth, $this->activation);
    }

    public function testRedirectsToHomepageIfUserIsActive()
    {
        $this->user->SetStatus(AccountStatus::ACTIVE);
        $this->user->ChangeDefaultHomePage(2);
        $this->postRegistration->HandleSelfRegistration($this->user, $this->page, $this->context);

        $this->assertTrue($this->fakeAuth->_LoginCalled);
        $this->assertEquals($this->user->EmailAddress(), $this->fakeAuth->_LastLogin);
        $this->assertFalse($this->fakeAuth->_LastLoginContext->GetData()->Persist);
        $this->assertEquals(Pages::UrlFromId(2), $this->page->_RedirectDestination);
    }

    public function testRedirectsToActiveIfUserNeedsActivation()
    {
        $this->user->SetStatus(AccountStatus::AWAITING_ACTIVATION);

        $this->postRegistration->HandleSelfRegistration($this->user, $this->page, $this->context);

        $this->assertFalse($this->fakeAuth->_LoginCalled);
        $this->assertTrue($this->activation->_NotifyCalled);
        $this->assertEquals($this->user, $this->activation->_NotifiedUser);
        $this->assertEquals(Pages::ACTIVATION, $this->page->_RedirectDestination);
    }
}
