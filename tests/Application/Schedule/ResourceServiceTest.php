<?php

require_once(ROOT_DIR . 'lib/Application/Schedule/ResourceService.php');

class ResourceServiceTest extends TestBase
{
    /**
     * @var IPermissionService|PHPUnit\Framework\MockObject\MockObject
     */
    private $permissionService;

    /**
     * @var IResourceRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $resourceRepository;

    /**
     * @var IAttributeService|PHPUnit\Framework\MockObject\MockObject
     */
    private $attributeService;

    /**
     * @var IUserRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $userRepository;

    /**
     * @var ResourceService
     */
    private $resourceService;

    /**
     * @var IAccessoryRepository
     */
    private $accessoryRepository;

    public function setUp(): void
    {
        $this->permissionService = $this->createMock('IPermissionService');
        $this->resourceRepository = $this->createMock('IResourceRepository');
        $this->attributeService = $this->createMock('IAttributeService');
        $this->userRepository = $this->createMock('IUserRepository');
        $this->accessoryRepository = $this->createMock('IAccessoryRepository');

        $this->resourceService = new ResourceService(
            $this->resourceRepository,
            $this->permissionService,
            $this->attributeService,
            $this->userRepository,
            $this->accessoryRepository
        );

        parent::setup();
    }

    public function testResourceServiceChecksPermissionForEachResource()
    {
        $scheduleId = 100;
        $user = $this->fakeUser;

        $resource1 = new FakeBookableResource(1, 'resource1');
        $resource2 = new FakeBookableResource(2, 'resource2');
        $resource3 = new FakeBookableResource(3, 'resource3');
        $resource4 = new FakeBookableResource(4, 'resource4');
        $resources = [$resource1, $resource2, $resource3, $resource4];

        $this->resourceRepository
                ->expects($this->once())
                ->method('GetScheduleResources')
                ->with($this->equalTo($scheduleId))
                ->willReturn($resources);

        $matcher = $this->exactly(4);
        $this->permissionService
                ->expects($matcher)
                ->method('CanAccessResource')
                ->willReturnCallback(function ($res, $u) use ($matcher, $resource1, $resource2, $resource3, $resource4, $user) {
                    $this->assertEquals($u, $user);
                    match ($matcher->numberOfInvocations())
                    {
                        1 => $this->assertEquals($res, $resource1),
                        2 => $this->assertEquals($res, $resource2),
                        3 => $this->assertEquals($res, $resource3),
                        4 => $this->assertEquals($res, $resource4)
                    };
                    return $matcher->numberOfInvocations() == 4 ? false : true;
                });

        $this->permissionService
                ->expects($this->exactly(3))
                ->method('CanBookResource')
                ->willReturn(true);

        $resourceDto1 = new ResourceDto(
            1,
            'resource1',
            true,
            true,
            $resource1->GetScheduleId(),
            $resource1->GetMinLength(),
            $resource1->GetResourceTypeId(),
            $resource1->GetAdminGroupId(),
            $resource1->GetScheduleAdminGroupId(),
            $resource1->GetStatusId(),
            $resource1->GetRequiresApproval(),
            $resource1->IsCheckInEnabled(),
            $resource1->IsAutoReleased(),
            $resource1->GetAutoReleaseMinutes(),
            $resource1->GetColor(),
            $resource1->GetMaxConcurrentReservations()
        );
        $resourceDto2 = new ResourceDto(
            2,
            'resource2',
            true,
            true,
            $resource2->GetScheduleId(),
            $resource2->GetMinLength(),
            $resource2->GetResourceTypeId(),
            $resource2->GetAdminGroupId(),
            $resource2->GetScheduleAdminGroupId(),
            $resource2->GetStatusId(),
            $resource2->GetRequiresApproval(),
            $resource2->IsCheckInEnabled(),
            $resource2->IsAutoReleased(),
            $resource2->GetAutoReleaseMinutes(),
            $resource2->GetColor(),
            $resource2->GetMaxConcurrentReservations()
        );
        $resourceDto3 = new ResourceDto(
            3,
            'resource3',
            true,
            true,
            $resource3->GetScheduleId(),
            $resource3->GetMinLength(),
            $resource3->GetResourceTypeId(),
            $resource3->GetAdminGroupId(),
            $resource3->GetScheduleAdminGroupId(),
            $resource3->GetStatusId(),
            $resource3->GetRequiresApproval(),
            $resource3->IsCheckInEnabled(),
            $resource3->IsAutoReleased(),
            $resource3->GetAutoReleaseMinutes(),
            $resource3->GetColor(),
            $resource3->GetMaxConcurrentReservations()
        );
        $resourceDto4 = new ResourceDto(
            4,
            'resource4',
            false,
            false,
            $resource4->GetScheduleId(),
            $resource4->GetMinLength(),
            $resource4->GetResourceTypeId(),
            $resource4->GetAdminGroupId(),
            $resource4->GetScheduleAdminGroupId(),
            $resource4->GetStatusId(),
            $resource4->GetRequiresApproval(),
            $resource4->IsCheckInEnabled(),
            $resource4->IsAutoReleased(),
            $resource4->GetAutoReleaseMinutes(),
            $resource4->GetColor(),
            $resource4->GetMaxConcurrentReservations()
        );
        $expected = [$resourceDto1, $resourceDto2, $resourceDto3, $resourceDto4];

        $actual = $this->resourceService->GetScheduleResources($scheduleId, true, $user);

        $this->assertEquals($expected, $actual);
    }

    public function testGetAllChecksPermissionForEachResource()
    {
        $session = $this->fakeUser;

        $resource1 = new FakeBookableResource(1, 'resource1');
        $resource2 = new FakeBookableResource(2, 'resource2');
        $resources = [$resource1, $resource2];

        $user = new FakeUser();
        $user->_IsResourceAdmin = true;

        $this->resourceRepository
                ->expects($this->once())
                ->method('GetResourceList')
                ->willReturn($resources);

        $matcher = $this->exactly(2);
        $this->permissionService
                ->expects($matcher)
                ->method('CanAccessResource')
                ->willReturnCallback(function($res, $ses) use ($resource1, $resource2, $session, $matcher)
                {
                    $this->assertEquals($ses, $session);
                    match ($matcher->numberOfInvocations())
                    {
                        1 => $this->assertEquals($res, $resource1),
                        2 => $this->assertEquals($res, $resource2),
                    };
                    return $matcher->numberOfInvocations() == 2;
                });

        $this->permissionService
                ->expects($this->once())
                ->method('CanBookResource')
                ->with(
                    $this->equalTo($resource2),
                    $this->equalTo($session)
                )
                ->willReturn(true);


        $this->userRepository->expects($this->any())
                             ->method('LoadById')
                             ->with($this->equalTo($session->UserId))
                             ->willReturn($user);

        $resourceDto1 = new ResourceDto(
            1,
            'resource1',
            false,
            false,
            $resource1->GetScheduleId(),
            $resource1->GetMinLength(),
            $resource1->GetResourceTypeId(),
            $resource1->GetAdminGroupId(),
            $resource1->GetScheduleAdminGroupId(),
            $resource1->GetStatusId(),
            $resource1->GetRequiresApproval(),
            $resource1->IsCheckInEnabled(),
            $resource1->IsAutoReleased(),
            $resource1->GetAutoReleaseMinutes(),
            $resource1->GetColor(),
            $resource1->GetMaxConcurrentReservations()
        );
        $resourceDto2 = new ResourceDto(
            2,
            'resource2',
            true,
            true,
            $resource2->GetScheduleId(),
            $resource2->GetMinLength(),
            $resource2->GetResourceTypeId(),
            $resource2->GetAdminGroupId(),
            $resource2->GetScheduleAdminGroupId(),
            $resource2->GetStatusId(),
            $resource2->GetRequiresApproval(),
            $resource2->IsCheckInEnabled(),
            $resource2->IsAutoReleased(),
            $resource2->GetAutoReleaseMinutes(),
            $resource2->GetColor(),
            $resource2->GetMaxConcurrentReservations()
        );

        $expected = [$resourceDto1, $resourceDto2];

        $actual = $this->resourceService->GetAllResources(true, $session);

        $this->assertEquals($expected, $actual);
    }

    public function testChecksStatusOfEachResourceWhenGettingAll()
    {
        $scheduleId = 100;
        $session = $this->fakeUser;

        $user = new FakeUser();
        $user->_IsResourceAdmin = false;

        $resource1 = new FakeBookableResource(1, 'resource1');
        $resource1->ChangeStatus(ResourceStatus::UNAVAILABLE);
        $resources = [$resource1];

        $this->resourceRepository
                ->expects($this->any())
                ->method('GetScheduleResources')
                ->with($this->equalTo($scheduleId))
                ->willReturn($resources);

        $this->permissionService
                ->expects($this->any())
                ->method('CanAccessResource')
                ->with(
                    $this->equalTo($resource1),
                    $this->equalTo($session)
                )
                ->willReturn(true);

        $this->userRepository->expects($this->any())
                             ->method('LoadById')
                             ->with($this->equalTo($session->UserId))
                             ->willReturn($user);

        $resourceDto1 = new ResourceDto(
            1,
            'resource1',
            false,
            false,
            $resource1->GetScheduleId(),
            $resource1->GetMinLength(),
            $resource1->GetResourceTypeId(),
            $resource1->GetAdminGroupId(),
            $resource1->GetScheduleAdminGroupId(),
            $resource1->GetStatusId(),
            $resource1->GetRequiresApproval(),
            $resource1->IsCheckInEnabled(),
            $resource1->IsAutoReleased(),
            $resource1->GetAutoReleaseMinutes(),
            $resource1->GetColor(),
            $resource1->GetMaxConcurrentReservations()
        );

        $expected = [$resourceDto1];

        $actualInclusive = $this->resourceService->GetScheduleResources($scheduleId, true, $session);

        $this->assertEquals($expected, $actualInclusive);

        $actualExcluded = $this->resourceService->GetScheduleResources($scheduleId, false, $session);
        $this->assertEquals([], $actualExcluded);
    }

    public function testResourcesAreNotReturnedIfNotIncludingInaccessibleResources()
    {
        $scheduleId = 100;
        $user = $this->fakeUser;

        $resource1 = new FakeBookableResource(1, 'resource1');

        $this->resourceRepository
                ->expects($this->once())
                ->method('GetScheduleResources')
                ->with($this->equalTo($scheduleId))
                ->willReturn([$resource1]);

        $this->permissionService
                ->expects($this->once())
                ->method('CanAccessResource')
                ->with($this->equalTo($resource1))
                ->willReturn(false);

        $includeInaccessibleResources = false;
        $actual = $this->resourceService->GetScheduleResources($scheduleId, $includeInaccessibleResources, $user);

        $this->assertEquals(0, count($actual));
    }

    public function testGetsAccessoriesFromRepository()
    {
        $accessories = [new Accessory(4, "lksjdf", 23)];

        $this->accessoryRepository
                ->expects($this->once())
                ->method('LoadAll')
                ->willReturn($accessories);

        $actualAccessories = $this->resourceService->GetAccessories();

        $this->assertEquals($accessories, $actualAccessories);
    }

    public function testFiltersResources()
    {
        $scheduleId = 122;
        $resourceId = 4;

        $resource1 = new FakeBookableResource(1, 'resource1');
        $resource2 = new FakeBookableResource(2, 'resource2');
        $resource3 = new FakeBookableResource(3, 'resource3');
        $resource4 = new FakeBookableResource(4, 'resource4');
        $resources = [$resource1, $resource2, $resource3, $resource4];

        $this->resourceRepository
                ->expects($this->once())
                ->method('GetScheduleResources')
                ->with($this->equalTo($scheduleId))
                ->willReturn($resources);

        $this->permissionService
                ->expects($this->any())
                ->method('CanAccessResource')
                ->with($this->anything(), $this->anything())
                ->willReturn(true);

        $filter = $this->createMock('IScheduleResourceFilter');

        $filter->expects($this->once())
               ->method('FilterResources')
               ->with($this->equalTo($resources), $this->equalTo($this->resourceRepository))
               ->willReturn([$resourceId]);

        $resources = $this->resourceService->GetScheduleResources($scheduleId, true, $this->fakeUser, $filter);

        $this->assertEquals(1, count($resources));
        $this->assertEquals(4, $resources[0]->GetId());
    }

    public function testGetsResourceCustomAttributes()
    {
        $customAttributes = [new FakeCustomAttribute(1)];

        $this->attributeService->expects($this->once())
                               ->method('GetByCategory')
                               ->with($this->equalTo(CustomAttributeCategory::RESOURCE))
                               ->willReturn($customAttributes);

        $attributes = $this->resourceService->GetResourceAttributes();

        $this->assertEquals(1, count($attributes));
        $this->assertEquals($customAttributes[0]->Id(), $attributes[0]->Id());
    }

    public function testGetsResourceTypeCustomAttributes()
    {
        $customAttributes = [new FakeCustomAttribute(1)];

        $this->attributeService->expects($this->once())
                               ->method('GetByCategory')
                               ->with($this->equalTo(CustomAttributeCategory::RESOURCE_TYPE))
                               ->willReturn($customAttributes);

        $attributes = $this->resourceService->GetResourceTypeAttributes();

        $this->assertEquals(1, count($attributes));
        $this->assertEquals($customAttributes[0]->Id(), $attributes[0]->Id());
    }

    public function testFiltersResourcesWhenGettingResourceGroups()
    {
        $scheduleId = 18;
        $expectedGroups = new FakeResourceGroupTree();
        $expectedGroups->AddGroup(new ResourceGroup(1, 'g'));

        $this->resourceRepository->expects($this->once())
                                 ->method('GetResourceGroups')
                                 ->with($this->equalTo($scheduleId), $this->anything())
                                 ->willReturn($expectedGroups);

        $groups = $this->resourceService->GetResourceGroups($scheduleId, $this->fakeUser);

        $this->assertEquals($expectedGroups, $groups);
    }
}
