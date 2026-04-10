<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'WebServices/ResourcesWebService.php');

class ResourcesWebServiceTest extends TestBase
{
    /**
     * @var FakeRestServer
     */
    private $server;

    private IResourceRepository&\PHPUnit\Framework\MockObject\MockObject $repository;

    private IReservationViewRepository&\PHPUnit\Framework\MockObject\MockObject $reservationRepository;

    private IAttributeService&\PHPUnit\Framework\MockObject\MockObject $attributeService;

    private IScheduleRepository&\PHPUnit\Framework\MockObject\MockObject $scheduleRepository;

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
        $this->scheduleRepository = $this->createMock('IScheduleRepository');

        $this->service = new ResourcesWebService(
            server: $this->server,
            resourceRepository: $this->repository,
            attributeService: $this->attributeService,
            reservationRepository: $this->reservationRepository,
            scheduleRepository: $this->scheduleRepository
        );
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

    public function testFiltersResourceListByScheduleId()
    {
        $scheduleId = 5;
        $matchingResourceId = 123;
        $otherResourceId = 456;

        $matchingResource = new FakeBookableResource($matchingResourceId);
        $matchingResource->SetScheduleId($scheduleId);

        $otherResource = new FakeBookableResource($otherResourceId);
        $otherResource->SetScheduleId(99);

        $this->server->SetQueryString(WebServiceQueryStringKeys::SCHEDULE_ID, $scheduleId);

        $this->scheduleRepository->expects($this->once())
                                 ->method('LoadById')
                                 ->with($scheduleId)
                                 ->willReturn(new Schedule(id: $scheduleId, name: null, isDefault: false, weekdayStart: null, daysVisible: null));

        $this->repository->expects($this->once())
                         ->method('GetUserResourceList')
                         ->willReturn([$matchingResource, $otherResource]);

        $attributes = new AttributeList();
        $this->attributeService->expects($this->once())
                               ->method('GetAttributes')
                               ->with(
                                   $this->equalTo(CustomAttributeCategory::RESOURCE),
                                   $this->equalTo([$matchingResourceId])
                               )
                               ->willReturn($attributes);

        $this->service->GetAll();

        $this->assertEquals(
            new ResourcesResponse($this->server, [$matchingResource], $attributes),
            $this->server->_LastResponse
        );
    }

    public function testFiltersResourceListByMultipleScheduleIds()
    {
        $scheduleId1 = 5;
        $scheduleId2 = 7;
        $resourceId1 = 123;
        $resourceId2 = 456;
        $otherResourceId = 789;

        $resource1 = new FakeBookableResource($resourceId1);
        $resource1->SetScheduleId($scheduleId1);

        $resource2 = new FakeBookableResource($resourceId2);
        $resource2->SetScheduleId($scheduleId2);

        $otherResource = new FakeBookableResource($otherResourceId);
        $otherResource->SetScheduleId(99);

        $this->server->SetQueryString(WebServiceQueryStringKeys::SCHEDULE_ID, "$scheduleId1,$scheduleId2");

        $this->scheduleRepository->expects($this->exactly(2))
                                 ->method('LoadById')
                                 ->willReturnMap([
                                     [$scheduleId1, new Schedule(id: $scheduleId1, name: null, isDefault: false, weekdayStart: null, daysVisible: null)],
                                     [$scheduleId2, new Schedule(id: $scheduleId2, name: null, isDefault: false, weekdayStart: null, daysVisible: null)],
                                 ]);

        $this->repository->expects($this->once())
                         ->method('GetUserResourceList')
                         ->willReturn([$resource1, $resource2, $otherResource]);

        $attributes = new AttributeList();
        $this->attributeService->expects($this->once())
                               ->method('GetAttributes')
                               ->with(
                                   $this->equalTo(CustomAttributeCategory::RESOURCE),
                                   $this->equalTo([$resourceId1, $resourceId2])
                               )
                               ->willReturn($attributes);

        $this->service->GetAll();

        $this->assertEquals(
            new ResourcesResponse($this->server, [$resource1, $resource2], $attributes),
            $this->server->_LastResponse
        );
    }

    public function testReturns404ForNonExistentScheduleId()
    {
        $this->server->SetQueryString(WebServiceQueryStringKeys::SCHEDULE_ID, '999');

        $this->scheduleRepository->expects($this->once())
                                 ->method('LoadById')
                                 ->with(999)
                                 ->willReturn(null);

        $this->repository->expects($this->never())
                         ->method('GetUserResourceList');

        $this->service->GetAll();

        $this->assertEquals(RestResponse::NOT_FOUND_CODE, $this->server->_LastResponseCode);
        $this->assertEquals(RestResponse::NotFound(), $this->server->_LastResponse);
    }

    public function testReturns404WhenOneOfMultipleScheduleIdsDoesNotExist()
    {
        // ID 1 exists, ID 999 does not — the endpoint must return 404 regardless.
        $this->server->SetQueryString(WebServiceQueryStringKeys::SCHEDULE_ID, '1,999');

        $this->scheduleRepository->expects($this->exactly(2))
                                 ->method('LoadById')
                                 ->willReturnMap([
                                     [1, new Schedule(id: 1, name: null, isDefault: false, weekdayStart: null, daysVisible: null)],
                                     [999, null],
                                 ]);

        $this->repository->expects($this->never())
                         ->method('GetUserResourceList');

        $this->service->GetAll();

        $this->assertEquals(RestResponse::NOT_FOUND_CODE, $this->server->_LastResponseCode);
        $this->assertEquals(RestResponse::NotFound(), $this->server->_LastResponse);
    }

    public function testDeduplicatesScheduleIds()
    {
        $scheduleId = 1;
        $resource = new FakeBookableResource(resourceId: 1, name: 'resource-1');
        $resource->SetScheduleId($scheduleId);

        // Three duplicate IDs — parsePositiveIntegerIds should reduce these to one.
        $this->server->SetQueryString(WebServiceQueryStringKeys::SCHEDULE_ID, '1,1,1');

        // The core assertion: LoadById must be called exactly once, not three times,
        // proving de-duplication happened before the existence-check loop.
        $this->scheduleRepository->expects($this->once())
                                 ->method('LoadById')
                                 ->with($scheduleId)
                                 ->willReturn(new Schedule(id: $scheduleId, name: null, isDefault: false, weekdayStart: null, daysVisible: null));

        $this->repository->expects($this->once())
                         ->method('GetUserResourceList')
                         ->willReturn([$resource]);

        // Stub with an empty AttributeList so the response can be built; content is not under test here.
        $this->attributeService->method('GetAttributes')->willReturn(new AttributeList());

        $this->service->GetAll();
    }

    public function testReturns400ForNonIntegerScheduleId()
    {
        $this->server->SetQueryString(WebServiceQueryStringKeys::SCHEDULE_ID, '1,abc,2');

        $this->repository->expects($this->never())
                         ->method('GetUserResourceList');

        $this->service->GetAll();

        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
        $this->assertEquals(
            RestResponse::BadRequest("Invalid scheduleId 'abc': must be a positive integer"),
            $this->server->_LastResponse
        );
    }

    public function testReturns400ForZeroScheduleId()
    {
        $this->server->SetQueryString(WebServiceQueryStringKeys::SCHEDULE_ID, '1,0,2');

        $this->repository->expects($this->never())
                         ->method('GetUserResourceList');

        $this->service->GetAll();

        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
        $this->assertEquals(
            RestResponse::BadRequest("Invalid scheduleId '0': must be a positive integer"),
            $this->server->_LastResponse
        );
    }

    public function testReturns400ForZeroScheduleIdAlone()
    {
        $this->server->SetQueryString(WebServiceQueryStringKeys::SCHEDULE_ID, '0');

        $this->repository->expects($this->never())
                         ->method('GetUserResourceList');

        $this->service->GetAll();

        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
        $this->assertEquals(
            RestResponse::BadRequest("Invalid scheduleId '0': must be a positive integer"),
            $this->server->_LastResponse
        );
    }

    private function buildGroupTree(int $groupId, array $resourceIds): ResourceGroupTree
    {
        $tree = new ResourceGroupTree();
        $tree->AddGroup(new ResourceGroup($groupId, "Group $groupId"));
        foreach ($resourceIds as $resourceId) {
            $tree->AddAssignment(new ResourceGroupAssignment(
                group_id: $groupId,
                resource_name: "Resource $resourceId",
                resource_id: $resourceId,
                resourceAdminGroupId: null,
                scheduleId: 1,
                statusId: ResourceStatus::AVAILABLE,
                scheduleAdminGroupId: null,
                requiresApproval: false,
                isCheckInEnabled: false,
                isAutoReleased: false,
                autoReleaseMinutes: null,
                minLength: null,
                resourceTypeId: null,
                color: null,
                maxConcurrentReservations: null
            ));
        }
        return $tree;
    }

    public function testFiltersResourceListByGroupId()
    {
        $groupId = 3;
        $matchingResourceId = 123;
        $otherResourceId = 456;

        $matchingResource = new FakeBookableResource($matchingResourceId);
        $otherResource = new FakeBookableResource($otherResourceId);

        $this->server->SetQueryString(WebServiceQueryStringKeys::GROUP_ID, $groupId);

        $this->repository->expects($this->once())
                         ->method('GetUserResourceList')
                         ->willReturn([$matchingResource, $otherResource]);

        $groupTree = $this->buildGroupTree($groupId, [$matchingResourceId]);
        $this->repository->expects($this->once())
                         ->method('GetResourceGroups')
                         ->willReturn($groupTree);

        $attributes = new AttributeList();
        $this->attributeService->expects($this->once())
                               ->method('GetAttributes')
                               ->with(
                                   $this->equalTo(CustomAttributeCategory::RESOURCE),
                                   $this->equalTo([$matchingResourceId])
                               )
                               ->willReturn($attributes);

        $this->service->GetAll();

        $this->assertEquals(
            new ResourcesResponse($this->server, [$matchingResource], $attributes),
            $this->server->_LastResponse
        );
    }

    public function testFiltersResourceListByMultipleGroupIds()
    {
        $groupId1 = 3;
        $groupId2 = 7;
        $resourceId1 = 123;
        $resourceId2 = 456;
        $otherResourceId = 789;

        $resource1 = new FakeBookableResource($resourceId1);
        $resource2 = new FakeBookableResource($resourceId2);
        $otherResource = new FakeBookableResource($otherResourceId);

        $this->server->SetQueryString(WebServiceQueryStringKeys::GROUP_ID, "$groupId1,$groupId2");

        $this->repository->expects($this->once())
                         ->method('GetUserResourceList')
                         ->willReturn([$resource1, $resource2, $otherResource]);

        $tree = new ResourceGroupTree();
        $tree->AddGroup(new ResourceGroup($groupId1, "Group $groupId1"));
        $tree->AddGroup(new ResourceGroup($groupId2, "Group $groupId2"));
        $tree->AddAssignment(new ResourceGroupAssignment(
            group_id: $groupId1,
            resource_name: "Resource $resourceId1",
            resource_id: $resourceId1,
            resourceAdminGroupId: null,
            scheduleId: 1,
            statusId: ResourceStatus::AVAILABLE,
            scheduleAdminGroupId: null,
            requiresApproval: false,
            isCheckInEnabled: false,
            isAutoReleased: false,
            autoReleaseMinutes: null,
            minLength: null,
            resourceTypeId: null,
            color: null,
            maxConcurrentReservations: null
        ));
        $tree->AddAssignment(new ResourceGroupAssignment(
            group_id: $groupId2,
            resource_name: "Resource $resourceId2",
            resource_id: $resourceId2,
            resourceAdminGroupId: null,
            scheduleId: 1,
            statusId: ResourceStatus::AVAILABLE,
            scheduleAdminGroupId: null,
            requiresApproval: false,
            isCheckInEnabled: false,
            isAutoReleased: false,
            autoReleaseMinutes: null,
            minLength: null,
            resourceTypeId: null,
            color: null,
            maxConcurrentReservations: null
        ));

        $this->repository->expects($this->once())
                         ->method('GetResourceGroups')
                         ->willReturn($tree);

        $attributes = new AttributeList();
        $this->attributeService->expects($this->once())
                               ->method('GetAttributes')
                               ->with(
                                   $this->equalTo(CustomAttributeCategory::RESOURCE),
                                   $this->equalTo([$resourceId1, $resourceId2])
                               )
                               ->willReturn($attributes);

        $this->service->GetAll();

        $this->assertEquals(
            new ResourcesResponse($this->server, [$resource1, $resource2], $attributes),
            $this->server->_LastResponse
        );
    }

    public function testFiltersResourceListByGroupIdIncludesSubGroups()
    {
        $parentGroupId = 3;
        $childGroupId = 5;
        $matchingResourceId = 123;
        $otherResourceId = 456;

        $matchingResource = new FakeBookableResource($matchingResourceId);
        $otherResource = new FakeBookableResource($otherResourceId);

        $this->server->SetQueryString(WebServiceQueryStringKeys::GROUP_ID, $parentGroupId);

        $this->repository->expects($this->once())
                         ->method('GetUserResourceList')
                         ->willReturn([$matchingResource, $otherResource]);

        $tree = new ResourceGroupTree();
        $tree->AddGroup(new ResourceGroup($parentGroupId, 'Parent'));
        $tree->AddGroup(new ResourceGroup($childGroupId, 'Child', $parentGroupId));
        $tree->AddAssignment(new ResourceGroupAssignment(
            group_id: $childGroupId,
            resource_name: "Resource $matchingResourceId",
            resource_id: $matchingResourceId,
            resourceAdminGroupId: null,
            scheduleId: 1,
            statusId: ResourceStatus::AVAILABLE,
            scheduleAdminGroupId: null,
            requiresApproval: false,
            isCheckInEnabled: false,
            isAutoReleased: false,
            autoReleaseMinutes: null,
            minLength: null,
            resourceTypeId: null,
            color: null,
            maxConcurrentReservations: null
        ));

        $this->repository->expects($this->once())
                         ->method('GetResourceGroups')
                         ->willReturn($tree);

        $attributes = new AttributeList();
        $this->attributeService->expects($this->once())
                               ->method('GetAttributes')
                               ->with(
                                   $this->equalTo(CustomAttributeCategory::RESOURCE),
                                   $this->equalTo([$matchingResourceId])
                               )
                               ->willReturn($attributes);

        $this->service->GetAll();

        $this->assertEquals(
            new ResourcesResponse($this->server, [$matchingResource], $attributes),
            $this->server->_LastResponse
        );
    }

    public function testReturns400ForNonIntegerGroupId()
    {
        $this->server->SetQueryString(WebServiceQueryStringKeys::GROUP_ID, '1,abc,2');

        $this->repository->expects($this->never())
                         ->method('GetUserResourceList');

        $this->service->GetAll();

        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
        $this->assertEquals(
            RestResponse::BadRequest("Invalid groupId 'abc': must be a positive integer"),
            $this->server->_LastResponse
        );
    }

    public function testReturns400ForZeroGroupId()
    {
        $this->server->SetQueryString(WebServiceQueryStringKeys::GROUP_ID, '0');

        $this->repository->expects($this->never())
                         ->method('GetUserResourceList');

        $this->service->GetAll();

        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
        $this->assertEquals(
            RestResponse::BadRequest("Invalid groupId '0': must be a positive integer"),
            $this->server->_LastResponse
        );
    }

    public function testReturns404ForNonExistentGroupId()
    {
        $this->server->SetQueryString(WebServiceQueryStringKeys::GROUP_ID, '999');

        $this->repository->expects($this->once())
                         ->method('GetUserResourceList')
                         ->willReturn([]);

        $groupTree = new ResourceGroupTree();
        $this->repository->expects($this->once())
                         ->method('GetResourceGroups')
                         ->willReturn($groupTree);

        $this->service->GetAll();

        $this->assertEquals(RestResponse::NOT_FOUND_CODE, $this->server->_LastResponseCode);
        $this->assertEquals(RestResponse::NotFound(), $this->server->_LastResponse);
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

        $this->service->GetAvailability();
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

        $this->service->GetAvailability();
    }
}
