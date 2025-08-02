<?php

require_once(ROOT_DIR . 'WebServices/AccountWebService.php');

class AccountWebServiceTest extends TestBase
{
    /**
     * @var AccountWebService
     */
    private $service;

    /**
     * @var FakeRestServer
     */
    private $server;

    private $controller;

    public function setUp(): void
    {
        parent::setup();

        $this->server = new FakeRestServer();
        $this->controller = new FakeAccountController();
        $this->fakeConfig->SetKey(ConfigKeys::REGISTRATION_ALLOW_SELF, 'true');
        $this->service = new AccountWebService($this->server, $this->controller);
    }

    public function testCanCreateNewUser()
    {
        $userId = '1';

        $request = new CreateAccountRequest();
        $this->server->SetRequest($request);

        $controllerResult = new AccountControllerResult($userId);

        $this->controller->_Result = $controllerResult;

        $this->service->Create();

        $this->assertEquals(new AccountActionResponse($this->server, $userId), $this->server->_LastResponse);
        $this->assertEquals($request, $this->controller->_LastRequest);
    }

    public function testFailedCreate()
    {
        $userRequest = new CreateAccountRequest();
        $this->server->SetRequest($userRequest);

        $errors = ['error'];
        $controllerResult = new AccountControllerResult(null, $errors);

        $this->controller->_Result = $controllerResult;

        $this->service->Create();

        $this->assertEquals(new FailedResponse($errors), $this->server->_LastResponse);
        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
        $this->assertEquals($userRequest, $this->controller->_LastRequest);
    }

    public function testCanUpdateUser()
    {
        $userId = '1';

        $userRequest = new UpdateAccountRequest();
        $this->server->SetRequest($userRequest);
        $this->server->SetSession(new WebServiceUserSession($userId));

        $controllerResult = new AccountControllerResult($userId);

        $this->controller->_Result = $controllerResult;

        $this->service->Update($userId);

        $this->assertEquals(new AccountActionResponse($this->server, $userId), $this->server->_LastResponse);
        $this->assertEquals($userRequest, $this->controller->_LastRequest);
    }

    public function testFailedUpdate()
    {
        $userId = 123;
        $userRequest = new UpdateAccountRequest();
        $this->server->SetRequest($userRequest);
        $this->server->SetSession(new WebServiceUserSession($userId));

        $errors = ['error'];
        $controllerResult = new AccountControllerResult(null, $errors);

        $this->controller->_Result = $controllerResult;

        $this->service->Update($userId);

        $this->assertEquals(new FailedResponse($errors), $this->server->_LastResponse);
        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
    }

    public function testCanUpdatePassword()
    {
        $userId = '1';

        $request = new UpdateAccountPasswordRequest();
        $this->server->SetRequest($request);
        $this->server->SetSession(new WebServiceUserSession($userId));

        $controllerResult = new AccountControllerResult($userId);
        $this->controller->_Result = $controllerResult;

        $this->service->UpdatePassword($userId);

        $this->assertEquals(new AccountActionResponse($this->server, $userId), $this->server->_LastResponse);
        $this->assertEquals($request, $this->controller->_LastRequest);
    }

    public function testFailedPasswordUpdate()
    {
        $userId = 123;

        $errors = ['error'];
        $controllerResult = new AccountControllerResult(null, $errors);
        $this->controller->_Result = $controllerResult;

        $this->server->SetRequest(new UpdateAccountPasswordRequest());
        $this->server->SetSession(new WebServiceUserSession($userId));

        $this->service->UpdatePassword($userId);

        $this->assertEquals(new FailedResponse($errors), $this->server->_LastResponse);
        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
    }
}

class FakeAccountController implements IAccountController
{
    /**
     * @var AccountControllerResult
     */
    public $_Result;
    public $_LastRequest;

    public function Create(CreateAccountRequest $request)
    {
        $this->_LastRequest = $request;
        return $this->_Result;
    }

    public function Update(UpdateAccountRequest $request, WebServiceUserSession $session)
    {
        $this->_LastRequest = $request;
        return $this->_Result;
    }

    public function UpdatePassword(UpdateAccountPasswordRequest $request, WebServiceUserSession $session)
    {
        $this->_LastRequest = $request;
        return $this->_Result;
    }

    public function LoadUser(WebServiceUserSession $session)
    {
        // TODO: Implement GetUserAttributes() method.
        return null;
    }

    public function GetUserAttributes(WebServiceUserSession $session)
    {
        // TODO: Implement GetUserAttributes() method.
        return null;
    }
}
