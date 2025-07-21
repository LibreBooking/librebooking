<?php

require_once(ROOT_DIR . 'WebServices/ResourcesWriteWebService.php');

class ResourcesWriteWebServiceTest extends TestBase
{
    /**
     * @var ResourcesWriteWebService
     */
    private $service;

    /**
     * @var FakeRestServer
     */
    private $server;

    /**
     * @var PHPUnit\Framework\MockObject\MockObject|IResourceSaveController
     */
    private $controller;

    public function setUp(): void
    {
        parent::setup();

        $this->server = new FakeRestServer();
        $this->controller = $this->createMock('IResourceSaveController');

        $this->service = new ResourcesWriteWebService($this->server, $this->controller);
    }

    public function testCanCreateNewResource()
    {
        $resourceId = '1';

        $request = new ResourceRequest();
        $this->server->SetRequest($request);

        $controllerResult = new ResourceControllerResult($resourceId);

        $this->controller->expects($this->once())
                ->method('Create')
                ->with($this->equalTo($request), $this->equalTo($this->server->GetSession()))
                ->willReturn($controllerResult);

        $this->service->Create();

        $this->assertEquals(new ResourceCreatedResponse($this->server, $resourceId), $this->server->_LastResponse);
    }

    public function testFailedCreate()
    {
        $request = new ResourceRequest();
        $this->server->SetRequest($request);

        $errors = ['error'];
        $controllerResult = new ResourceControllerResult(null, $errors);

        $this->controller->expects($this->once())
                ->method('Create')
                ->with($this->equalTo($request), $this->equalTo($this->server->GetSession()))
                ->willReturn($controllerResult);

        $this->service->Create();

        $this->assertEquals(new FailedResponse($errors), $this->server->_LastResponse);
        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
    }

    public function testCanUpdateResource()
    {
        $resourceId = '1';

        $request = new ResourceRequest();
        $this->server->SetRequest($request);

        $controllerResult = new ResourceControllerResult($resourceId);

        $this->controller->expects($this->once())
                ->method('Update')
                ->with(
                    $this->equalTo($resourceId),
                    $this->equalTo($request),
                    $this->equalTo($this->server->GetSession())
                )
                ->willReturn($controllerResult);

        $this->service->Update($resourceId);

        $this->assertEquals(new ResourceUpdatedResponse($this->server, $resourceId), $this->server->_LastResponse);
    }

    public function testFailedUpdate()
    {
        $resourceId = 123;
        $request = new ResourceRequest();
        $this->server->SetRequest($request);

        $errors = ['error'];
        $controllerResult = new ResourceControllerResult(null, $errors);

        $this->controller->expects($this->once())
                ->method('Update')
                ->with(
                    $this->equalTo($resourceId),
                    $this->equalTo($request),
                    $this->equalTo($this->server->GetSession())
                )
                ->willReturn($controllerResult);

        $this->service->Update($resourceId);

        $this->assertEquals(new FailedResponse($errors), $this->server->_LastResponse);
        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
    }

    public function testCanDeleteResource()
    {
        $resourceId = '1';

        $controllerResult = new ResourceControllerResult($resourceId);

        $this->controller->expects($this->once())
                ->method('Delete')
                ->with($this->equalTo($resourceId), $this->equalTo($this->server->GetSession()))
                ->willReturn($controllerResult);

        $this->service->Delete($resourceId);

        $this->assertEquals(new DeletedResponse(), $this->server->_LastResponse);
    }

    public function testFailedDelete()
    {
        $resourceId = 123;

        $errors = ['error'];
        $controllerResult = new ResourceControllerResult(null, $errors);

        $this->controller->expects($this->once())
                ->method('Delete')
                ->with($this->equalTo($resourceId), $this->equalTo($this->server->GetSession()))
                ->willReturn($controllerResult);

        $this->service->Delete($resourceId);

        $this->assertEquals(new FailedResponse($errors), $this->server->_LastResponse);
        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
    }
}
