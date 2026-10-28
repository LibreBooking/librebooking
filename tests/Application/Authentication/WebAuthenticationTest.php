<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
require_once(ROOT_DIR . 'Pages/LoginPage.php');

class WebAuthenticationTest extends TestBase
{
    /**
     * @var WebLoginContext
     */
    private $loginContext;

    /**
     * @var FakeAuth
     */
    private $fakeAuth;

    /**
     * @var WebAuthentication
     */
    private $webAuth;

    private $username = 'LoGInName';
    private $password = 'password';

    public function setUp(): void
    {
        parent::setup();

        $this->fakeAuth = new FakeAuth();
        $this->loginContext = $loginContext = new WebLoginContext(new LoginData(true));
        $this->webAuth = new WebAuthentication($this->fakeAuth, $this->fakeServer);
    }

    public function testValidatePassesThrough()
    {
        $this->fakeAuth->_ValidateResult = true;

        $isValid = $this->webAuth->Validate($this->username, $this->password);

        $this->assertEquals($this->username, $this->fakeAuth->_LastLogin);
        $this->assertEquals($this->password, $this->fakeAuth->_LastPassword);
        $this->assertTrue($isValid);
    }

    public function testSuccessfulLoginSetsSession()
    {
        $user = new FakeUserSession();
        $this->fakeAuth->_Session = $user;

        $this->webAuth->Login($this->username, $this->loginContext);

        $this->assertEquals($user, $this->fakeServer->GetUserSession());
    }

    public function testLoginDoesNotSetAPersistentLoginCookie()
    {
        $this->fakeAuth->_Session = new FakeUserSession();
        $this->assertTrue($this->loginContext->GetData()->Persist, 'The deprecated flag is set for this test');

        $this->webAuth->Login($this->username, $this->loginContext);

        $this->assertNull($this->fakeServer->GetCookie(CookieKeys::PERSIST_LOGIN));
        $this->assertSame([], $this->fakeServer->Cookies);
    }

    public function testLogsUserOut()
    {
        $userSession = new FakeUserSession();
        $userSession->UserId = 100;

        $this->webAuth->Logout($userSession);

        $this->assertPersistentLoginCookieWasExpired();
        $this->assertEquals(SessionKeys::USER_SESSION, $this->fakeServer->_EndedSession);
        $this->assertTrue($this->fakeAuth->_LogoutCalled);
    }

    public function testPostLogoutExpiresThePersistentLoginCookieAndEndsTheSession()
    {
        $this->assertFalse(method_exists($this->fakeAuth, 'postLogout'), 'The default fake has no postLogout() hook');

        $this->webAuth->postLogout(new FakeUserSession());

        $this->assertPersistentLoginCookieWasExpired();
        $this->assertEquals(SessionKeys::USER_SESSION, $this->fakeServer->_EndedSession);
    }

    public function testPostLogoutCallsThePluginHookWhenItExists()
    {
        // postLogout() is not part of IAuthentication; only some plugins provide it.
        $auth = new class () extends FakeAuth {
            public ?UserSession $_PostLogoutUser = null;

            public function postLogout(UserSession $user): void
            {
                $this->_PostLogoutUser = $user;
            }
        };
        $userSession = new FakeUserSession();
        $webAuth = new WebAuthentication($auth, $this->fakeServer);

        $webAuth->postLogout($userSession);

        $this->assertSame($userSession, $auth->_PostLogoutUser);
        $this->assertEquals(SessionKeys::USER_SESSION, $this->fakeServer->_EndedSession);
    }

    public function testLogoutExpiresThePersistentLoginCookieWithoutAUserSession()
    {
        $this->webAuth->Logout(new NullUserSession());

        $this->assertPersistentLoginCookieWasExpired();
        $this->assertEquals(SessionKeys::USER_SESSION, $this->fakeServer->_EndedSession);
    }

    public function testLoggingOutRepeatedlyIsHarmless()
    {
        $userSession = new FakeUserSession();

        $this->webAuth->Logout($userSession);
        $this->webAuth->Logout($userSession);
        $this->webAuth->postLogout($userSession);
        $this->webAuth->postLogout($userSession);

        $this->assertPersistentLoginCookieWasExpired();
        $this->assertEquals(SessionKeys::USER_SESSION, $this->fakeServer->_EndedSession);
    }

    public function testHandlesLoginFailure()
    {
        $page = $this->createMock('ILoginPage');
        $this->webAuth->HandleLoginFailure($page);

        $this->assertTrue($this->fakeAuth->_HandleLoginFailureCalled);
    }

    private function assertPersistentLoginCookieWasExpired(): void
    {
        $deleted = $this->fakeServer->_DeletedCookie;
        $this->assertInstanceOf(Cookie::class, $deleted);
        $this->assertSame(CookieKeys::PERSIST_LOGIN, $deleted->Name);
        $this->assertSame('', $deleted->Value);
    }
}
