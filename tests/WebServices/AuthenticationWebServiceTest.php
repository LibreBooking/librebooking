<?php

require_once(ROOT_DIR . 'WebServices/AuthenticationWebService.php');
require_once(ROOT_DIR . 'Domain/Values/WebService/WebServiceUserSession.php');

class AuthenticationWebServiceTest extends TestBase
{
    /**
     * @var IWebServiceAuthentication|PHPUnit\Framework\MockObject\MockObject
     */
    private $authentication;

    /**
     * @var AuthenticationWebService
     */
    private $service;

    /**
     * @var FakeRestServer
     */
    private $server;

    public function setUp(): void
    {
        parent::setup();

        $this->authentication = $this->createMock('IWebServiceAuthentication');
        $this->server = new FakeRestServer();

        $this->service = new AuthenticationWebService($this->server, $this->authentication);
    }

    public function testLogsUserInIfValidCredentials()
    {
        $username = 'un';
        $password = 'pw';
    $session = new WebServiceUserSession(1);

        $request = new AuthenticationRequest($username, $password);
        $this->server->SetRequest($request);

        $this->authentication->expects($this->once())
                ->method('Validate')
                ->with($this->equalTo($username), $this->equalTo($password))
                ->willReturn(true);

        $this->authentication->expects($this->once())
                ->method('Login')
                ->with($this->equalTo($username))
                ->willReturn($session);

    $this->service->Authenticate();

        $expectedResponse = AuthenticationResponse::Success($this->server, $session, 0);
        $expectedResponseCode = RestResponse::OK_CODE;
        $this->assertEquals($expectedResponse, $this->server->_LastResponse);
        $this->assertEquals($expectedResponseCode, $this->server->_LastResponseCode);
    }

    public function testRestrictsUserIfInvalidCredentials()
    {
        $username = 'un';
        $password = 'pw';

        $request = new AuthenticationRequest($username, $password);
        $this->server->SetRequest($request);

        $this->authentication->expects($this->once())
                ->method('Validate')
                ->with($this->equalTo($username), $this->equalTo($password))
                ->willReturn(false);

    $this->service->Authenticate();

        $expectedResponse = AuthenticationResponse::Failed();
        $expectedResponseCode = RestResponse::UNAUTHORIZED_CODE;
        $this->assertEquals($expectedResponse, $this->server->_LastResponse);
        $this->assertEquals($expectedResponseCode, $this->server->_LastResponseCode);
    }

    public function testSignsUserOut()
    {
        $userId = 'ddddd';
        $sessionToken = 'sssss';

        $request = new SignOutRequest($userId, $sessionToken);
        $this->server->SetRequest($request);

        $this->authentication->expects($this->once())
                ->method('Logout')
                ->with($this->equalTo($userId), $this->equalTo($sessionToken));

    $this->service->SignOut();
    }
}
