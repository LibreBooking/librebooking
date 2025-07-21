<?php

require_once(ROOT_DIR . 'WebServices/ResourcesWebService.php');

class ResourcesWebServiceTest extends TestBase
{
    /**
     * @var FakeRestServer
     */
    private $server;

    /**
     * @var IResourceRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $repository;

    /**
     * @var IReservationViewRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $reservationRepository;

    /**
     * @var IAttributeService|PHPUnit\Framework\MockObject\MockObject
     */
    private $attributeService;

    /**
     * @var ResourcesWebService
     */
    private $service;

    public function setUp(): void
    {
        parent::setup();

        $this->server = new FakeRestServer();
        $this->repository = $this->createMock('IResourceRepository');
        $this->reservationRepository = $this->createMock('IReservationViewRepository');
        $this->attributeService = $this->createMock('IAttributeService');

        $this->service = new ResourcesWebService($this->server, $this->repository, $this->attributeService, $this->reservationRepository);
    }

    public function testGetsResourceById()
    {
        $resourceId = 8282;
        $resource = new FakeBookableResource($resourceId);
        $resource->SetBufferTime(3600);

        $attributes = new AttributeList();

        $this->repository->expects($this->once())
                         ->method('LoadById')
                         ->with($this->equalTo($resourceId))
                         ->willReturn($resource);

        $this->repository->expects($this->once())
                         ->method('GetUserResourceIdList')
                         ->willReturn([$resourceId]);

        $this->attributeService->expects($this->once())
                               ->method('GetAttributes')
                               ->with(
                                   $this->equalTo(CustomAttributeCategory::RESOURCE),
                                   $this->equalTo([$resourceId])
                               )
                               ->willReturn($attributes);

        $this->service->GetResource($resourceId);

        $this->assertEquals(new ResourceResponse($this->server, $resource, $attributes), $this->server->_LastResponse);
    }

    public function testWhenNotFound()
    {
        $resourceId = 8282;
        $this->repository->expects($this->once())
                         ->method('LoadById')
                         ->with($this->equalTo($resourceId))
                         ->willReturn(BookableResource::Null());

        $this->repository->expects($this->once())
                         ->method('GetUserResourceIdList')
                         ->willReturn([$resourceId]);

        $this->service->GetResource($resourceId);

        $this->assertEquals(RestResponse::NotFound(), $this->server->_LastResponse);
    }

    public function testGetsResourceList()
    {
        $resourceId = 123;
        $resources[] = new FakeBookableResource($resourceId);
        $attributes = new AttributeList();

        $this->repository->expects($this->once())
                         ->method('GetUserResourceList')
                         ->willReturn($resources);

        $this->attributeService->expects($this->once())
                               ->method('GetAttributes')
                               ->with(
                                   $this->equalTo(CustomAttributeCategory::RESOURCE),
                                   $this->equalTo([$resourceId])
                               )
                               ->willReturn($attributes);

        $this->service->GetAll();

        $this->assertEquals(
            new ResourcesResponse($this->server, $resources, $attributes),
            $this->server->_LastResponse
        );
    }

    public function testGetsStatuses()
    {
        $this->service->GetStatuses();

        $this->assertEquals(new ResourceStatusResponse(), $this->server->_LastResponse);
    }

    public function testGetsStatusReasons()
    {
        $reasons = [new ResourceStatusReason(1, ResourceStatus::AVAILABLE)];

        $this->repository->expects($this->once())
                         ->method('GetStatusReasons')
                         ->willReturn($reasons);

        $this->service->GetStatusReasons();

        $this->assertEquals(new ResourceStatusReasonsResponse($reasons), $this->server->_LastResponse);
    }

    public function testGetsAllResourceAvailability()
    {
        $resourceId1 = 1;
        $resourceId2 = 2;
        $resourceId3 = 3;

        $startTime = Date::Now()->AddHours(-1);
        $endTime = Date::Now()->AddHours(1);
        $resources = [new FakeBookableResource($resourceId1), new FakeBookableResource($resourceId2), new FakeBookableResource($resourceId3)];

        $this->repository->expects($this->once())
                         ->method('GetUserResourceList')
                         ->willReturn($resources);

        $conflicting = new TestReservationItemView(1, $startTime, $endTime, $resourceId1);
        $upcoming = new TestReservationItemView(2, $endTime, $endTime->AddHours(3), $resourceId1);
        $upcoming2 = new TestReservationItemView(3, $endTime->AddHours(3), $endTime->AddHours(4), $resourceId1);
        $upcoming3 = new TestReservationItemView(4, $endTime->AddHours(5), $endTime->AddHours(6), $resourceId1);
        $upcoming4 = new TestReservationItemView(5, $endTime->AddHours(3), $endTime->AddHours(3), $resourceId2);
        $upcoming5 = new TestReservationItemView(6, $startTime, $endTime->AddHours(2), $resourceId3);
        $reservations = [$conflicting, $upcoming, $upcoming2, $upcoming3, $upcoming4, $upcoming5];

        $endDate = Date::Now()->AddDays(7);
        $this->reservationRepository->expects($this->once())
                                    ->method('GetReservations')
                                    ->willReturn($reservations);

        $this->service->GetAvailability();

        $resources = [
            new ResourceAvailabilityResponse($this->server, $resources[0], $conflicting, null, $upcoming2->EndDate, $endDate),
            new ResourceAvailabilityResponse($this->server, $resources[1], null, $upcoming4, $upcoming4->StartDate, $endDate),
            new ResourceAvailabilityResponse($this->server, $resources[2], $upcoming5, null, $upcoming5->EndDate, $endDate),
        ];

        $this->assertEquals(new ResourcesAvailabilityResponse($resources), $this->server->_LastResponse);
    }

    public function testGetsAllResourceAvailabilityForARequestTime()
    {
        $date = Date::Parse('2014-01-01 04:30:00', 'America/Chicago');
        $this->server->SetQueryString(WebServiceQueryStringKeys::DATE_TIME, $date->ToIso());

        $resources = [new FakeBookableResource(1)];

        $this->repository->expects($this->once())
                         ->method('GetUserResourceList')
                         ->willReturn($resources);

        $reservations = [];

        $endDate = $date->AddDays(7);
        $this->reservationRepository->expects($this->once())
                                    ->method('GetReservations')
                                    ->with(
                                        $this->equalTo($date->ToUtc()),
                                        $this->equalTo($endDate->ToUtc())
                                    )
                                    ->willReturn($reservations);

        $this->service->GetAvailability();
    }

    public function testGetsSingleResourceAvailability()
    {
        $resourceId1 = 1;
        $resource = new FakeBookableResource($resourceId1);
        $this->server->SetQueryString(WebServiceQueryStringKeys::RESOURCE_ID, $resourceId1);

        $this->repository->expects($this->once())
                         ->method('LoadById')
                         ->with($this->equalTo($resourceId1))
                         ->willReturn($resource);

        $this->repository->expects($this->once())
                         ->method('GetUserResourceIdList')
                         ->willReturn([$resourceId1]);

        $reservations = [];

        $endDate = Date::Now()->AddDays(7);
        $this->reservationRepository->expects($this->once())
                                    ->method('GetReservations')
                                    ->with(
                                        $this->equalTo(Date::Now()),
                                        $this->equalTo($endDate),
                                        $this->isEmpty(),
                                        $this->isEmpty(),
                                        $this->isEmpty(),
                                        $this->equalTo($resourceId1)
                                    )
                                    ->willReturn($reservations);

        $this->service->GetAvailability($resourceId1);
    }

    public function testGetsSingleResourceAvailabilityForARequestTime()
    {
        $date = Date::Parse('2014-01-01 04:30:00', 'America/Chicago');
        $this->server->SetQueryString(WebServiceQueryStringKeys::DATE_TIME, $date->ToIso());
        $resourceId1 = 1;
        $resource = new FakeBookableResource($resourceId1);
        $this->server->SetQueryString(WebServiceQueryStringKeys::RESOURCE_ID, $resourceId1);

        $this->repository->expects($this->once())
                         ->method('LoadById')
                         ->with($this->equalTo($resourceId1))
                         ->willReturn($resource);

        $this->repository->expects($this->once())
                         ->method('GetUserResourceIdList')
                         ->willReturn([$resourceId1]);

        $reservations = [];

        $endDate = $date->AddDays(7);
        $this->reservationRepository->expects($this->once())
                                    ->method('GetReservations')
                                    ->with(
                                        $this->equalTo($date->ToUtc()),
                                        $this->equalTo($endDate->ToUtc()),
                                        $this->isEmpty(),
                                        $this->isEmpty(),
                                        $this->isEmpty(),
                                        $this->equalTo($resourceId1)
                                    )
                                    ->willReturn($reservations);

        $this->service->GetAvailability($resourceId1);
    }
}
