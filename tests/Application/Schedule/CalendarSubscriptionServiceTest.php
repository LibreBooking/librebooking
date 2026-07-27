<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

class CalendarSubscriptionServiceTest extends TestBase
{
    /**
     * @var CalendarSubscriptionService
     */
    private $service;

    /**
     * @var IUserRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $userRepo;

    /**
     * @var IResourceRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $resourceRepo;

    /**
     * @var IScheduleRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $scheduleRepo;

    public function setUp(): void
    {
        parent::setup();

        $this->userRepo = $this->createMock('IUserRepository');
        $this->resourceRepo = $this->createMock('IResourceRepository');
        $this->scheduleRepo = $this->createMock('IScheduleRepository');

        $this->service = new CalendarSubscriptionService($this->userRepo, $this->resourceRepo, $this->scheduleRepo);
    }

    public function testGetsUserByPublicId()
    {
        $expected = new FakeUser();
        $publicId = uniqid();

        $this->userRepo->expects($this->once())
                ->method('LoadByPublicId')
                ->with($this->equalTo($publicId))
                ->willReturn($expected);

        $actual = $this->service->GetUser($publicId);

        $this->assertEquals($expected, $actual);
    }

    public function testGetsResourceByPublicId()
    {
        $expected = new FakeBookableResource(123);
        $publicId = uniqid();

        $this->resourceRepo->expects($this->once())
                ->method('LoadByPublicId')
                ->with($this->equalTo($publicId))
                ->willReturn($expected);

        $actual = $this->service->GetResource($publicId);

        $this->assertEquals($expected, $actual);
    }

    public function testGetsScheduleByPublicId()
    {
        $expected = new FakeSchedule();
        $publicId = uniqid();

        $this->scheduleRepo->expects($this->once())
                ->method('LoadByPublicId')
                ->with($this->equalTo($publicId))
                ->willReturn($expected);

        $actual = $this->service->GetSchedule($publicId);

        $this->assertEquals($expected, $actual);
    }

    public function testGetsResourceGroupByPublicId()
    {
        $publicId = uniqid();
        $group = new ResourceGroup(5, 'Engineering Rooms');

        $this->resourceRepo->expects($this->once())
                ->method('LoadResourceGroupByPublicId')
                ->with($this->equalTo($publicId))
                ->willReturn($group);

        $actual = $this->service->GetResourceGroup($publicId);

        $this->assertEquals($group, $actual);
    }

    public function testGetsNullResourceGroupWhenGroupNotFound()
    {
        $publicId = uniqid();

        $this->resourceRepo->expects($this->once())
                ->method('LoadResourceGroupByPublicId')
                ->with($this->equalTo($publicId))
                ->willReturn(null);

        $actual = $this->service->GetResourceGroup($publicId);

        $this->assertNull($actual);
    }

    public function testGetResourceGroupReusesGroupLoadedByGetResourcesInGroup()
    {
        $publicId = uniqid();
        $group = new ResourceGroup(5, 'Engineering Rooms');
        $groups = $this->createMock('ResourceGroupTree');

        $this->resourceRepo->expects($this->once())
                ->method('LoadResourceGroupByPublicId')
                ->with($this->equalTo($publicId))
                ->willReturn($group);

        $this->resourceRepo->expects($this->once())
                ->method('GetResourceGroups')
                ->willReturn($groups);

        $groups->expects($this->once())
                ->method('GetResourceIds')
                ->with($this->equalTo($group->id))
                ->willReturn([1, 2]);

        $this->service->GetResourcesInGroup($publicId);
        $actual = $this->service->GetResourceGroup($publicId);

        $this->assertEquals($group, $actual);
    }

    public function testResolveCalendarNameReturnsScheduleNameWhenOnlyScheduleIdIsSet()
    {
        $publicScheduleId = uniqid();
        $schedule = new FakeSchedule(1, 'Engineering Schedule');

        $this->scheduleRepo->expects($this->once())
                ->method('LoadByPublicId')
                ->with($this->equalTo($publicScheduleId))
                ->willReturn($schedule);

        $actual = $this->service->ResolveCalendarName($publicScheduleId, null, null);

        $this->assertEquals('Engineering Schedule', $actual);
    }

    public function testResolveCalendarNameReturnsResourceNameWhenOnlyResourceIdIsSet()
    {
        $publicResourceId = uniqid();
        $resource = new FakeBookableResource(1, 'Meeting Room 3');

        $this->resourceRepo->expects($this->once())
                ->method('LoadByPublicId')
                ->with($this->equalTo($publicResourceId))
                ->willReturn($resource);

        $actual = $this->service->ResolveCalendarName(null, $publicResourceId, null);

        $this->assertEquals('Meeting Room 3', $actual);
    }

    public function testResolveCalendarNameResourceOverridesSchedule()
    {
        $publicScheduleId = uniqid();
        $publicResourceId = uniqid();
        $schedule = new FakeSchedule(1, 'Engineering Schedule');
        $resource = new FakeBookableResource(2, 'Meeting Room 3');

        $this->scheduleRepo->method('LoadByPublicId')->willReturn($schedule);
        $this->resourceRepo->method('LoadByPublicId')->willReturn($resource);

        $actual = $this->service->ResolveCalendarName($publicScheduleId, $publicResourceId, null);

        $this->assertEquals('Meeting Room 3', $actual);
    }

    public function testResolveCalendarNameResourceGroupOverridesScheduleWhenGroupNameIsNotEmpty()
    {
        $publicScheduleId = uniqid();
        $publicGroupId = uniqid();
        $schedule = new FakeSchedule(1, 'Engineering Schedule');
        $group = new ResourceGroup(5, 'Engineering Rooms');

        $this->scheduleRepo->expects($this->once())
                ->method('LoadByPublicId')
                ->with($this->equalTo($publicScheduleId))
                ->willReturn($schedule);

        $this->resourceRepo->expects($this->once())
                ->method('LoadResourceGroupByPublicId')
                ->with($this->equalTo($publicGroupId))
                ->willReturn($group);

        $actual = $this->service->ResolveCalendarName($publicScheduleId, null, $publicGroupId);

        $this->assertEquals('Engineering Rooms', $actual);
    }

    public function testResolveCalendarNameFallsBackToScheduleWhenResourceGroupNameIsNull()
    {
        $publicScheduleId = uniqid();
        $publicGroupId = uniqid();
        $schedule = new FakeSchedule(1, 'Engineering Schedule');

        $this->scheduleRepo->expects($this->once())
                ->method('LoadByPublicId')
                ->with($this->equalTo($publicScheduleId))
                ->willReturn($schedule);

        $this->resourceRepo->expects($this->once())
                ->method('LoadResourceGroupByPublicId')
                ->with($this->equalTo($publicGroupId))
                ->willReturn(null);

        $actual = $this->service->ResolveCalendarName($publicScheduleId, null, $publicGroupId);

        $this->assertEquals('Engineering Schedule', $actual);
    }

    public function testResolveCalendarNameReturnsNullWhenNoIdsAreSet()
    {
        $actual = $this->service->ResolveCalendarName(null, null, null);

        $this->assertNull($actual);
    }

    public function testResolveCalendarNameReturnsMyCalendarWhenOnlyUserIdIsSet()
    {
        $publicUserId = uniqid();

        $actual = $this->service->ResolveCalendarName(null, null, null, $publicUserId);

        $this->assertEquals(Resources::GetInstance()->GetString('MyCalendar'), $actual);
    }

    public function testResolveCalendarNameCombinesUserNameWithScheduleName()
    {
        $publicScheduleId = uniqid();
        $publicUserId = uniqid();
        $schedule = new FakeSchedule(1, 'Engineering Schedule');

        $this->scheduleRepo->method('LoadByPublicId')->willReturn($schedule);

        $actual = $this->service->ResolveCalendarName($publicScheduleId, null, null, $publicUserId);

        $expected = sprintf('%s - %s', Resources::GetInstance()->GetString('MyCalendar'), 'Engineering Schedule');
        $this->assertEquals($expected, $actual);
    }

    public function testResolveCalendarNameCombinesUserNameWithResourceName()
    {
        $publicResourceId = uniqid();
        $publicUserId = uniqid();
        $resource = new FakeBookableResource(1, 'Meeting Room 3');

        $this->resourceRepo->method('LoadByPublicId')->willReturn($resource);

        $actual = $this->service->ResolveCalendarName(null, $publicResourceId, null, $publicUserId);

        $expected = sprintf('%s - %s', Resources::GetInstance()->GetString('MyCalendar'), 'Meeting Room 3');
        $this->assertEquals($expected, $actual);
    }

    public function testResolveCalendarNameCombinesUserNameWithResourceGroupName()
    {
        $publicGroupId = uniqid();
        $publicUserId = uniqid();
        $group = new ResourceGroup(5, 'Engineering Rooms');

        $this->resourceRepo->method('LoadResourceGroupByPublicId')->willReturn($group);

        $actual = $this->service->ResolveCalendarName(null, null, $publicGroupId, $publicUserId);

        $expected = sprintf('%s - %s', Resources::GetInstance()->GetString('MyCalendar'), 'Engineering Rooms');
        $this->assertEquals($expected, $actual);
    }

    public function testSubscriptionDetailsAreNotEnabledWhenIcsFeatureIsDisabled()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ICS_ENABLED, false);
        $this->fakeConfig->SetKey(ConfigKeys::ICS_SUBSCRIPTION_KEY, '123');

        $details = new CalendarSubscriptionDetails(true);

        $this->assertFalse($details->IsEnabled());
    }

    public function testSubscriptionDetailsAreEnabledWhenIcsFeatureAndSubscriptionKeyAreConfigured()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ICS_ENABLED, true);
        $this->fakeConfig->SetKey(ConfigKeys::ICS_SUBSCRIPTION_KEY, '123');

        $details = new CalendarSubscriptionDetails(true);

        $this->assertTrue($details->IsEnabled());
    }
}
