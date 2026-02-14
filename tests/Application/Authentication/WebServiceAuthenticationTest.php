<?php

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

class WebServiceAuthenticationTest extends TestBase
{
    /**
     * @var FakeAuth
     */
    private $fakeAuth;

    /**
     * @var WebServiceAuthentication
     */
    private $webAuth;

    private $username = 'LoGInName';
    private $password = 'password';

    private IUserSessionRepository&\PHPUnit\Framework\MockObject\MockObject $userSessionRepository;

    public function setUp(): void
    {
        parent::setup();

        WebServiceSessionToken::$_Token = 'hard coded token';

        $this->fakeAuth = new FakeAuth();
        $this->userSessionRepository = $this->createMock('IUserSessionRepository');

        $this->webAuth = new WebServiceAuthentication($this->fakeAuth, $this->userSessionRepository);
    }

    public function testValidatePassesThrough()
    {
        $this->fakeAuth->_ValidateResult = true;

        $isValid = $this->webAuth->Validate($this->username, $this->password);

        $this->assertEquals($this->username, $this->fakeAuth->_LastLogin);
        $this->assertEquals($this->password, $this->fakeAuth->_LastPassword);
        $this->assertTrue($isValid);
    }

    public function testSuccessfulLoginSetsSessionInDatabase()
    {
        $session = new FakeUserSession();
        $this->fakeAuth->_Session = $session;
        $expectedSession = WebServiceUserSession::FromSession($session);

        $this->userSessionRepository->expects($this->once())
                ->method('LoadBySessionToken')
                ->with($this->equalTo($expectedSession->SessionToken))
                ->willReturn(null);

        $this->userSessionRepository->expects($this->once())
                ->method('Add')
                ->with($this->equalTo($expectedSession));

        $actualSession = $this->webAuth->Login($this->username);

        $this->assertEquals($expectedSession, $actualSession);
    }

    public function testUpdateWhenSessionExistsInDatabase()
    {
        $user = new FakeUserSession();
        $this->fakeAuth->_Session = $user;
        $expectedSession = WebServiceUserSession::FromSession($user);

        $this->userSessionRepository->expects($this->once())
                ->method('LoadBySessionToken')
                ->with($this->equalTo($expectedSession->SessionToken))
                ->willReturn(new WebServiceUserSession(123));

        $this->userSessionRepository->expects($this->once())
                ->method('Update')
                ->with($this->equalTo($expectedSession));

        $actualSession = $this->webAuth->Login($this->username);

        $this->assertEquals($expectedSession, $actualSession);
    }

    public function testLogsUserOutIfUserIdAndSessionTokenMatch()
    {
        $sessionToken = 'token';
        $userId = 91919;

        $userSession = new WebServiceUserSession($userId);
        $userSession->UserId = $userId;

        $this->userSessionRepository->expects($this->once())
                ->method('LoadBySessionToken')
                ->with($this->equalTo($sessionToken))
                ->willReturn($userSession);

        $this->userSessionRepository->expects($this->once())
                ->method('Delete')
                ->with($this->equalTo($userSession));

        $this->webAuth->Logout($userId, $sessionToken);

        $this->assertTrue($this->fakeAuth->_LogoutCalled);
    }

    public function testDoesNotLogUserOutIfUserIdAndSessionTokenMismatch()
    {
        $userId = 123;
        $sessionToken = 'token';

        $userSession = new WebServiceUserSession(999);

        $this->userSessionRepository->expects($this->once())
                ->method('LoadBySessionToken')
                ->with($this->equalTo($sessionToken))
                ->willReturn($userSession);

        $this->webAuth->Logout($userId, $sessionToken);

        $this->assertFalse($this->fakeAuth->_LogoutCalled);
    }
}
