<?php

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationCreatedEmailAdmin.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationUpdatedEmailAdmin.php');

require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Notification/namespace.php');

class AdminEmailNotificationTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testSendsReservationCreatedEmailIfAdminWantsIt()
    {
        $ownerId = 100;
        $resourceId = 200;

        $resource = new FakeBookableResource($resourceId, 'name');

        $reservation = new TestReservationSeries();
        $reservation->WithOwnerId($ownerId);
        $reservation->WithResource($resource);
        $reservation->SetStatusId(ReservationStatus::Pending);

        $owner = new FakeUser($ownerId);
        $admin1 = new UserDto(1, 'f', 'l', 'e');
        $admin2 = new UserDto(2, 'f', 'l', 'e');
        $admin3 = new UserDto(3, 'f', 'l', 'e');
        $admin4 = new UserDto(4, 'f', 'l', 'e');
        $admin5 = new UserDto(5, 'f', 'l', 'e');
        $admin6 = new UserDto(6, 'f', 'l', 'e');

        $resourceAdmins = [$admin1, $admin2, $admin3];
        $appAdmins = [$admin3, $admin4, $admin1];
        $groupAdmins = [$admin5, $admin6, $admin2];

        $attributeRepo = $this->createMock('IAttributeRepository');
        $userRepo = $this->createMock('IUserRepository');
        $userRepo->expects($this->once())
                 ->method('LoadById')
                 ->with($this->equalTo($ownerId))
                 ->willReturn($owner);

        $userRepo->expects($this->once())
                 ->method('GetResourceAdmins')
                 ->with($this->equalTo($resourceId))
                 ->willReturn($resourceAdmins);

        $userRepo->expects($this->once())
                 ->method('GetApplicationAdmins')
                 ->willReturn($appAdmins);

        $userRepo->expects($this->once())
                 ->method('GetGroupAdmins')
                 ->with($this->equalTo($ownerId))
                 ->willReturn($groupAdmins);

        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_RESOURCE_ADMIN_ADD);
        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_APPLICATION_ADMIN_ADD);
        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_GROUP_ADMIN_ADD);

        $notification = new AdminEmailCreatedNotification($userRepo, $userRepo, $attributeRepo);
        $notification->Notify($reservation);

        $expectedMessage1 = new ReservationCreatedEmailAdmin($admin1, $owner, $reservation, $resource, $attributeRepo, $userRepo);
        $expectedMessage2 = new ReservationCreatedEmailAdmin($admin2, $owner, $reservation, $resource, $attributeRepo, $userRepo);

        $this->assertEquals(6, count($this->fakeEmailService->_Messages));

        $this->assertInstanceOf('ReservationCreatedEmailAdmin', $this->fakeEmailService->_Messages[0]);
        $this->assertInstanceOf('ReservationCreatedEmailAdmin', $this->fakeEmailService->_Messages[1]);
    }

    public function testSendsReservationUpdatedEmailIfAdminWantsIt()
    {
        $ownerId = 100;
        $resourceId = 200;

        $resource = new FakeBookableResource($resourceId, 'name');

        $reservation = new ExistingReservationSeries();
        $reservation->WithOwner($ownerId);
        $reservation->WithPrimaryResource($resource);

        $owner = new FakeUser($ownerId);
        $admin1 = new UserDto(1, 'f', 'l', 'e');
        $admin2 = new UserDto(2, 'f', 'l', 'e');
        $admin3 = new UserDto(3, 'f', 'l', 'e');
        $admin4 = new UserDto(4, 'f', 'l', 'e');
        $admin5 = new UserDto(5, 'f', 'l', 'e');
        $admin6 = new UserDto(6, 'f', 'l', 'e');

        $resourceAdmins = [$admin1, $admin2, $admin3];
        $appAdmins = [$admin3, $admin4, $admin1];
        $groupAdmins = [$admin5, $admin6, $admin2];

        $attributeRepo = $this->createMock('IAttributeRepository');
        $userRepo = $this->createMock('IUserRepository');
        $userRepo->expects($this->once())
                 ->method('LoadById')
                 ->with($this->equalTo($ownerId))
                 ->willReturn($owner);

        $userRepo->expects($this->once())
                 ->method('GetResourceAdmins')
                 ->with($this->equalTo($resourceId))
                 ->willReturn($resourceAdmins);

        $userRepo->expects($this->once())
                 ->method('GetApplicationAdmins')
                 ->willReturn($appAdmins);

        $userRepo->expects($this->once())
                 ->method('GetGroupAdmins')
                 ->with($this->equalTo($ownerId))
                 ->willReturn($groupAdmins);

        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_RESOURCE_ADMIN_UPDATE);
        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_APPLICATION_ADMIN_UPDATE);
        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_GROUP_ADMIN_UPDATE);

        $notification = new AdminEmailUpdatedNotification($userRepo, $userRepo, $attributeRepo);
        $notification->Notify($reservation);

        $expectedMessage1 = new ReservationUpdatedEmailAdmin($admin1, $owner, $reservation, $resource, $attributeRepo, $userRepo);
        $expectedMessage2 = new ReservationUpdatedEmailAdmin($admin2, $owner, $reservation, $resource, $attributeRepo, $userRepo);

        $this->assertEquals(6, count($this->fakeEmailService->_Messages), "send one per person, no duplicates");

        $this->assertInstanceOf('ReservationUpdatedEmailAdmin', $this->fakeEmailService->_Messages[0]);
        $this->assertInstanceOf('ReservationUpdatedEmailAdmin', $this->fakeEmailService->_Messages[1]);
    }

    public function testSendsReservationCreatedRequiresApprovalEmailIfAdminWantsIt()
    {
        $ownerId = 100;
        $resourceId = 200;

        $resource = new FakeBookableResource($resourceId, 'name');

        $reservation = new TestReservationSeries();
        $reservation->WithOwnerId($ownerId);
        $reservation->WithResource($resource);
        $reservation->SetStatusId(ReservationStatus::Pending);

        $owner = new FakeUser($ownerId);
        $admin1 = new UserDto(1, 'f', 'l', 'e');
        $admin2 = new UserDto(2, 'f', 'l', 'e');
        $admin3 = new UserDto(3, 'f', 'l', 'e');
        $admin4 = new UserDto(4, 'f', 'l', 'e');
        $admin5 = new UserDto(5, 'f', 'l', 'e');
        $admin6 = new UserDto(6, 'f', 'l', 'e');

        $resourceAdmins = [$admin1, $admin2, $admin3];
        $appAdmins = [$admin3, $admin4, $admin1];
        $groupAdmins = [$admin5, $admin6, $admin2];

        $attributeRepo = $this->createMock('IAttributeRepository');
        $userRepo = $this->createMock('IUserRepository');
        $userRepo->expects($this->once())
                 ->method('LoadById')
                 ->with($this->equalTo($ownerId))
                 ->willReturn($owner);

        $userRepo->expects($this->once())
                 ->method('GetResourceAdmins')
                 ->with($this->equalTo($resourceId))
                 ->willReturn($resourceAdmins);

        $userRepo->expects($this->once())
                 ->method('GetApplicationAdmins')
                 ->willReturn($appAdmins);

        $userRepo->expects($this->once())
                 ->method('GetGroupAdmins')
                 ->with($this->equalTo($ownerId))
                 ->willReturn($groupAdmins);

        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_RESOURCE_ADMIN_APPROVAL);
        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_APPLICATION_ADMIN_APPROVAL);
        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_GROUP_ADMIN_APPROVAL);

        $notification = new AdminEmailApprovalNotification($userRepo, $userRepo, $attributeRepo);
        $notification->Notify($reservation);

        $expectedMessage1 = new ReservationRequiresApprovalEmailAdmin($admin1, $owner, $reservation, $resource, $attributeRepo, $userRepo);
        $expectedMessage2 = new ReservationRequiresApprovalEmailAdmin($admin2, $owner, $reservation, $resource, $attributeRepo, $userRepo);

        $this->assertEquals(6, count($this->fakeEmailService->_Messages));

        $this->assertInstanceOf('ReservationRequiresApprovalEmailAdmin', $this->fakeEmailService->_Messages[0]);
        $this->assertInstanceOf('ReservationRequiresApprovalEmailAdmin', $this->fakeEmailService->_Messages[1]);
    }

    public function testDoesNotSendReservationCreatedRequiresApprovalEmailIfAdminWantsItButNotRequiresApproval()
    {
        $ownerId = 100;
        $resourceId = 200;

        $resource = new FakeBookableResource($resourceId, 'name');

        $reservation = new TestReservationSeries();
        $reservation->WithOwnerId($ownerId);
        $reservation->WithResource($resource);
        $reservation->SetStatusId(ReservationStatus::Created);

        $attributeRepo = $this->createMock('IAttributeRepository');
        $userRepo = $this->createMock('IUserRepository');

        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_RESOURCE_ADMIN_APPROVAL);
        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_APPLICATION_ADMIN_APPROVAL);
        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_GROUP_ADMIN_APPROVAL);

        $notification = new AdminEmailApprovalNotification($userRepo, $userRepo, $attributeRepo);
        $notification->Notify($reservation);

        $this->assertEquals(0, count($this->fakeEmailService->_Messages));
    }

    public function testSendsReservationUpdatedRequiresApprovalEmailIfAdminWantsIt()
    {
        $ownerId = 100;
        $resourceId = 200;

        $resource = new FakeBookableResource($resourceId, 'name');

        $reservation = new ExistingReservationSeries();
        $reservation->WithOwner($ownerId);
        $reservation->WithPrimaryResource($resource);
        $reservation->SetStatusId(ReservationStatus::Pending);

        $owner = new FakeUser($ownerId);
        $admin1 = new UserDto(1, 'f', 'l', 'e');
        $admin2 = new UserDto(2, 'f', 'l', 'e');
        $admin3 = new UserDto(3, 'f', 'l', 'e');
        $admin4 = new UserDto(4, 'f', 'l', 'e');
        $admin5 = new UserDto(5, 'f', 'l', 'e');
        $admin6 = new UserDto(6, 'f', 'l', 'e');

        $resourceAdmins = [$admin1, $admin2, $admin3];
        $appAdmins = [$admin3, $admin4, $admin1];
        $groupAdmins = [$admin5, $admin6, $admin2];

        $attributeRepo = $this->createMock('IAttributeRepository');
        $userRepo = $this->createMock('IUserRepository');
        $userRepo->expects($this->once())
                 ->method('LoadById')
                 ->with($this->equalTo($ownerId))
                 ->willReturn($owner);

        $userRepo->expects($this->once())
                 ->method('GetResourceAdmins')
                 ->with($this->equalTo($resourceId))
                 ->willReturn($resourceAdmins);

        $userRepo->expects($this->once())
                 ->method('GetApplicationAdmins')
                 ->willReturn($appAdmins);

        $userRepo->expects($this->once())
                 ->method('GetGroupAdmins')
                 ->with($this->equalTo($ownerId))
                 ->willReturn($groupAdmins);

        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_RESOURCE_ADMIN_APPROVAL);
        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_APPLICATION_ADMIN_APPROVAL);
        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_GROUP_ADMIN_APPROVAL);

        $notification = new AdminEmailApprovalNotification($userRepo, $userRepo, $attributeRepo);
        $notification->Notify($reservation);

        $expectedMessage1 = new ReservationRequiresApprovalEmailAdmin($admin1, $owner, $reservation, $resource, $attributeRepo, $userRepo);
        $expectedMessage2 = new ReservationRequiresApprovalEmailAdmin($admin2, $owner, $reservation, $resource, $attributeRepo, $userRepo);

        $this->assertEquals(6, count($this->fakeEmailService->_Messages), "send one per person, no duplicates");

        $this->assertInstanceOf('ReservationRequiresApprovalEmailAdmin', $this->fakeEmailService->_Messages[0]);
        $this->assertInstanceOf('ReservationRequiresApprovalEmailAdmin', $this->fakeEmailService->_Messages[1]);
    }

    public function testSendsReservationDeletedEmailIfAdminWantsIt()
    {
        $ownerId = 100;
        $resourceId = 200;

        $resource = new FakeBookableResource($resourceId, 'name');

        $reservation = new ExistingReservationSeries();
        $reservation->WithOwner($ownerId);
        $reservation->WithPrimaryResource($resource);

        $owner = new FakeUser($ownerId);
        $admin1 = new UserDto(1, 'f', 'l', 'e');
        $admin2 = new UserDto(2, 'f', 'l', 'e');
        $admin3 = new UserDto(3, 'f', 'l', 'e');
        $admin4 = new UserDto(4, 'f', 'l', 'e');
        $admin5 = new UserDto(5, 'f', 'l', 'e');
        $admin6 = new UserDto(6, 'f', 'l', 'e');

        $resourceAdmins = [$admin1, $admin2, $admin3];
        $appAdmins = [$admin3, $admin4, $admin1];
        $groupAdmins = [$admin5, $admin6, $admin2];

        $attributeRepo = $this->createMock('IAttributeRepository');
        $userRepo = $this->createMock('IUserRepository');
        $userRepo->expects($this->once())
                 ->method('LoadById')
                 ->with($this->equalTo($ownerId))
                 ->willReturn($owner);

        $userRepo->expects($this->once())
                 ->method('GetResourceAdmins')
                 ->with($this->equalTo($resourceId))
                 ->willReturn($resourceAdmins);

        $userRepo->expects($this->once())
                 ->method('GetApplicationAdmins')
                 ->willReturn($appAdmins);

        $userRepo->expects($this->once())
                 ->method('GetGroupAdmins')
                 ->with($this->equalTo($ownerId))
                 ->willReturn($groupAdmins);

        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_RESOURCE_ADMIN_DELETE);
        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_APPLICATION_ADMIN_DELETE);
        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_GROUP_ADMIN_DELETE);

        $notification = new AdminEmailDeletedNotification($userRepo, $userRepo, $attributeRepo);
        $notification->Notify($reservation);

        $expectedMessage1 = new ReservationDeletedEmailAdmin($admin1, $owner, $reservation, $resource, $attributeRepo, $userRepo);

        $this->assertEquals(6, count($this->fakeEmailService->_Messages), "send one per person, no duplicates");

        $this->assertInstanceOf('ReservationDeletedEmailAdmin', $this->fakeEmailService->_Messages[0]);
        $this->assertInstanceOf('ReservationDeletedEmailAdmin', $this->fakeEmailService->_Messages[1]);
    }

    public function testNothingSentIfConfiguredOff()
    {
        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_RESOURCE_ADMIN_ADD, false);
        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_APPLICATION_ADMIN_ADD, false);
        $this->EnableNotifyFor(ConfigKeys::RESERVATION_NOTIFY_GROUP_ADMIN_ADD, false);

        $notification = new AdminEmailCreatedNotification(
            $this->createMock('IUserRepository'),
            $this->createMock('IUserViewRepository'),
            $this->createMock('IAttributeRepository')
        );
        $notification->Notify(new TestReservationSeries());

        $this->assertEquals(0, count($this->fakeEmailService->_Messages));
    }

    private function EnableNotifyFor($configKey, $enabled = true)
    {
        $this->fakeConfig->SetKey($configKey, $enabled);
    }
}
