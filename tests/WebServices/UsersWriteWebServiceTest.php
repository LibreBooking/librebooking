<?php

use PHPUnit\Framework\MockObject\MockObject;

require_once(ROOT_DIR . 'WebServices/UsersWriteWebService.php');

class UsersWriteWebServiceTest extends TestBase
{
    /**
     * @var UsersWriteWebService
     */
    private $service;

    /**
     * @var FakeRestServer
     */
    private $server;

    /**
     * @var MockObject|IUserSaveController
     */
    private $controller;

    public function setUp(): void
    {
        parent::setup();

        $this->server = new FakeRestServer();
        $this->controller = $this->createMock('IUserSaveController');

        $this->service = new UsersWriteWebService($this->server, $this->controller);
    }

    public function testCanCreateNewUser()
    {
        $userId = '1';

        $userRequest = new CreateUserRequest();
        $this->server->SetRequest($userRequest);

        $controllerResult = new UserControllerResult($userId);

        $this->controller->expects($this->once())
                ->method('Create')
                ->with($this->equalTo($userRequest), $this->equalTo($this->server->GetSession()))
                ->willReturn($controllerResult);

        $this->service->Create();

        $this->assertEquals(new UserCreatedResponse($this->server, $userId), $this->server->_LastResponse);
    }

    public function testFailedCreate()
    {
        $userRequest = new CreateUserRequest();
        $this->server->SetRequest($userRequest);

        $errors = ['error'];
        $controllerResult = new UserControllerResult(null, $errors);

        $this->controller->expects($this->once())
                ->method('Create')
                ->with($this->equalTo($userRequest), $this->equalTo($this->server->GetSession()))
                ->willReturn($controllerResult);

        $this->service->Create();

        $this->assertEquals(new FailedResponse($errors), $this->server->_LastResponse);
        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
    }

    public function testCanUpdateUser()
    {
        $userId = '1';

        $userRequest = new UpdateUserRequest();
        $this->server->SetRequest($userRequest);

        $controllerResult = new UserControllerResult($userId);

        $this->controller->expects($this->once())
                ->method('Update')
                ->with(
                    $this->equalTo($userId),
                    $this->equalTo($userRequest),
                    $this->equalTo($this->server->GetSession())
                )
                ->willReturn($controllerResult);

        $this->service->Update($userId);

        $this->assertEquals(new UserUpdatedResponse($this->server, $userId), $this->server->_LastResponse);
    }

    public function testFailedUpdate()
    {
        $userId = 123;
        $userRequest = new UpdateUserRequest();
        $this->server->SetRequest($userRequest);

        $errors = ['error'];
        $controllerResult = new UserControllerResult(null, $errors);

        $this->controller->expects($this->once())
                ->method('Update')
                ->with(
                    $this->equalTo($userId),
                    $this->equalTo($userRequest),
                    $this->equalTo($this->server->GetSession())
                )
                ->willReturn($controllerResult);

        $this->service->Update($userId);

        $this->assertEquals(new FailedResponse($errors), $this->server->_LastResponse);
        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
    }

    public function testCanDeleteUser()
    {
        $userId = '1';

        $controllerResult = new UserControllerResult($userId);

        $this->controller->expects($this->once())
                ->method('Delete')
                ->with($this->equalTo($userId), $this->equalTo($this->server->GetSession()))
                ->willReturn($controllerResult);

        $this->service->Delete($userId);

        $this->assertEquals(new DeletedResponse(), $this->server->_LastResponse);
    }

    public function testFailedDelete()
    {
        $userId = 123;

        $errors = ['error'];
        $controllerResult = new UserControllerResult(null, $errors);

        $this->controller->expects($this->once())
                ->method('Delete')
                ->with($this->equalTo($userId), $this->equalTo($this->server->GetSession()))
                ->willReturn($controllerResult);

        $this->service->Delete($userId);

        $this->assertEquals(new FailedResponse($errors), $this->server->_LastResponse);
        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
    }

    public function testCanUpdatePassword()
    {
        $userId = '1';
        $password = 'new password';

        $this->server->_Request = new UpdateUserPasswordRequest();
        $this->server->_Request->password = $password;

        $controllerResult = new UserControllerResult($userId);

        $this->controller->expects($this->once())
                ->method('UpdatePassword')
                ->with($this->equalTo($userId), $this->equalTo($password), $this->equalTo($this->server->GetSession()))
                ->willReturn($controllerResult);

        $this->service->UpdatePassword($userId);

        $this->assertEquals(new UserUpdatedResponse($this->server, $userId), $this->server->_LastResponse);
    }

    public function testFailedPasswordUpdate()
    {
        $userId = 123;
        $password = 'new password';

        $errors = ['error'];
        $controllerResult = new UserControllerResult(null, $errors);

        $this->server->_Request = new UpdateUserPasswordRequest();
        $this->server->_Request->password = $password;

        $this->controller->expects($this->once())
            ->method('UpdatePassword')
            ->with($this->equalTo($userId), $this->equalTo($password), $this->equalTo($this->server->GetSession()))
            ->willReturn($controllerResult);

        $this->service->UpdatePassword($userId);

        $this->assertEquals(new FailedResponse($errors), $this->server->_LastResponse);
        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
    }
}
