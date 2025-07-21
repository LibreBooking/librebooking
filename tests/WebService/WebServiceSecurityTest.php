<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class WebServiceSecurityTest extends TestBase
{
    private $sessionToken = 'sessionToken';
    private $userId = 'userId';

    /**
     * @var FakeWebServiceUserSession
     */
    private $session;

    /**
     * @var WebServiceSecurity
     */
    private $security;
    /**
     * @var IRestServer|PHPUnit\Framework\MockObject\MockObject
     */
    private $server;
    /**
     * @var IUserSessionRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $userSessionRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->userSessionRepository = $this->createMock('IUserSessionRepository');
        $this->server = $this->createMock('IRestServer');

        $this->security = new WebServiceSecurity($this->userSessionRepository);

        $this->session = new FakeWebServiceUserSession($this->userId);
        $this->session->SessionToken = $this->sessionToken;
        $this->session->SessionExpiration = WebServiceExpiration::Create();
    }

    public function testSetsUserSessionIfValidAndNotExpired()
    {
        $this->server->expects($this->exactly(2))
                ->method('GetHeader')
                ->willReturnMap(
                [
                    [WebServiceHeaders::SESSION_TOKEN, $this->sessionToken],
                    [WebServiceHeaders::USER_ID, $this->userId]
                ]);

        $this->userSessionRepository->expects($this->once())
                ->method('LoadBySessionToken')
                ->with($this->equalTo($this->sessionToken))
                ->willReturn($this->session);

        $this->userSessionRepository->expects($this->once())
                ->method('Update')
                ->with($this->equalTo($this->session));

        $this->server->expects($this->once())
                ->method('SetSession')
                ->with($this->equalTo($this->session));

        $wasHandled = $this->security->HandleSecureRequest($this->server);

        $this->assertTrue($wasHandled);
        $this->assertTrue($this->session->_SessionExtended);
    }

    public function testDeletesExpiredSessions()
    {
        $this->session->_IsExpired = true;

        $this->server->expects($this->exactly(2))
                ->method('GetHeader')
                ->willReturnMap(
                [
                    [WebServiceHeaders::SESSION_TOKEN, $this->sessionToken],
                    [WebServiceHeaders::USER_ID, $this->userId]
                ]);

        $this->userSessionRepository->expects($this->once())
                ->method('LoadBySessionToken')
                ->with($this->equalTo($this->sessionToken))
                ->willReturn($this->session);

        $this->userSessionRepository->expects($this->once())
                ->method('Delete')
                ->with($this->equalTo($this->session));

        $wasHandled = $this->security->HandleSecureRequest($this->server);

        $this->assertFalse($wasHandled);
        $this->assertFalse($this->session->_SessionExtended);
    }

    public function testHandlesSessionNotFound()
    {
        $this->server->expects($this->exactly(2))
                ->method('GetHeader')
                ->willReturnMap(
                [
                    [WebServiceHeaders::SESSION_TOKEN, $this->sessionToken],
                    [WebServiceHeaders::USER_ID, $this->userId]
                ]);

        $this->userSessionRepository->expects($this->once())
                ->method('LoadBySessionToken')
                ->with($this->equalTo($this->sessionToken))
                ->willReturn(null);

        $wasHandled = $this->security->HandleSecureRequest($this->server);

        $this->assertFalse($wasHandled);
    }

    public function testHandlesSessionMisMatch()
    {
        $this->server->expects($this->exactly(2))
                ->method('GetHeader')
                ->willReturnMap(
                [
                    [WebServiceHeaders::SESSION_TOKEN, 'not the right token'],
                    [WebServiceHeaders::USER_ID, 'not the right id']
                ]);

        $this->userSessionRepository->expects($this->once())
                ->method('LoadBySessionToken')
                ->with($this->equalTo('not the right token'))
                ->willReturn($this->session);

        $wasHandled = $this->security->HandleSecureRequest($this->server);

        $this->assertFalse($wasHandled);
    }

    public function testHandlesAdminRequest()
    {
        $this->session->IsAdmin = true;
        $this->server->expects($this->exactly(2))
                ->method('GetHeader')
                ->willReturnMap(
                [
                    [WebServiceHeaders::SESSION_TOKEN, $this->sessionToken],
                    [WebServiceHeaders::USER_ID, $this->userId]
                ]);

        $this->userSessionRepository->expects($this->once())
                ->method('LoadBySessionToken')
                ->with($this->equalTo($this->sessionToken))
                ->willReturn($this->session);

        $this->userSessionRepository->expects($this->once())
                ->method('Update')
                ->with($this->equalTo($this->session));

        $this->server->expects($this->once())
                ->method('SetSession')
                ->with($this->equalTo($this->session));

        $wasHandled = $this->security->HandleSecureRequest($this->server, true);

        $this->assertTrue($wasHandled);
        $this->assertTrue($this->session->_SessionExtended);
    }

    public function testHandlesWhenUserIsNotAdmin()
    {
        $this->session->IsAdmin = false;
        $this->server->expects($this->exactly(2))
                ->method('GetHeader')
                ->willReturnMap(
                [
                    [WebServiceHeaders::SESSION_TOKEN, $this->sessionToken],
                    [WebServiceHeaders::USER_ID, $this->userId]
                ]);

        $this->userSessionRepository->expects($this->once())
                ->method('LoadBySessionToken')
                ->with($this->equalTo($this->sessionToken))
                ->willReturn($this->session);

        $wasHandled = $this->security->HandleSecureRequest($this->server, true);

        $this->assertFalse($wasHandled);
        $this->assertFalse($this->session->_SessionExtended);
    }
}
