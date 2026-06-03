<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'WebServices/AccessoriesWebService.php');


class AccessoriesWebServiceTest extends TestBase
{
    /**
     * @var AccessoriesWebService
     */
    private $service;

    /**
     * @var FakeRestServer
     */
    private $server;

    private IResourceRepository&\PHPUnit\Framework\MockObject\MockObject $resourceRepository;

    private IAccessoryRepository&\PHPUnit\Framework\MockObject\MockObject $accessoryRepository;

    public function setUp(): void
    {
        $this->server = new FakeRestServer();
        $this->resourceRepository = $this->createMock('IResourceRepository');
        $this->accessoryRepository = $this->createMock('IAccessoryRepository');

        $this->service = new AccessoriesWebService($this->server, $this->resourceRepository, $this->accessoryRepository);
        parent::setup();
    }

    public function testGetsAllAccessories()
    {
        $accessories = [new AccessoryDto(1, 'name', 23, 1)];

        $this->resourceRepository->expects($this->once())
                ->method('GetAccessoryList')
                ->willReturn($accessories);

        $this->service->GetAll();

        $this->assertEquals(new AccessoriesResponse($this->server, $accessories), $this->server->_LastResponse);
        $this->assertEquals(RestResponse::OK_CODE, $this->server->_LastResponseCode);
    }

    public function testGetsAccessoryById()
    {
        $accessoryId = 1233;

        $accessory = new Accessory($accessoryId, 'name', 123);

        $this->accessoryRepository->expects($this->once())
                ->method('LoadById')
                ->with($this->equalTo($accessoryId))
                ->willReturn($accessory);

        $this->service->GetAccessory($accessoryId);

        $this->assertEquals(new AccessoryResponse($this->server, $accessory), $this->server->_LastResponse);
        $this->assertEquals(RestResponse::OK_CODE, $this->server->_LastResponseCode);
    }

    public function testWhenAccessoryNotFound()
    {
        $accessoryId = 1233;

        $this->accessoryRepository->expects($this->once())
                ->method('LoadById')
                ->with($this->equalTo($accessoryId))
                ->willReturn(null);

        $this->service->GetAccessory($accessoryId);

        $this->assertEquals(RestResponse::NotFound(), $this->server->_LastResponse);
        $this->assertEquals(RestResponse::NOT_FOUND_CODE, $this->server->_LastResponseCode);
    }
}
