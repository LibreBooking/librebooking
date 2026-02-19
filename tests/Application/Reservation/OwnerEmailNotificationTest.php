<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationCreatedEmail.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationUpdatedEmail.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationDeletedEmail.php');

class OwnerEmailNotificationTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testSendsReservationCreatedEmailIfUserWantsIt()
    {
        $ownerId = 100;
        $resourceId = 200;

        $resource = new FakeBookableResource($resourceId, 'name');

        $reservation = new TestReservationSeries();
        $reservation->WithOwnerId($ownerId);
        $reservation->WithResource($resource);
        $reservation->WithCurrentInstance(new TestReservation());

        $userRepo = $this->createMock('IUserRepository');
        $attributeRepo = new FakeAttributeRepository();

        $user = $this->LoadsUser($userRepo, $ownerId);

        $notification = new OwnerEmailCreatedNotification($userRepo, $attributeRepo);
        $notification->Notify($reservation);

        $expectedMessage = new ReservationCreatedEmail($user, $reservation, null, $attributeRepo, $userRepo);

        $lastMessage = $this->fakeEmailService->_LastMessage;
        $body = $lastMessage->Body();
        $this->assertInstanceOf('ReservationCreatedEmail', $lastMessage);
        $this->assertNotEmpty($lastMessage->AttachmentContents());
        //		$this->assertEquals($expectedMessage, $lastMessage);
    }

    public function testSendsReservationUpdatedEmailIfUserWantsIt()
    {
        $event = new ReservationUpdatedEvent();
        $ownerId = 100;
        $resourceId = 200;

        $resource = new FakeBookableResource($resourceId, 'name');

        $reservation = new ExistingReservationSeries();
        $reservation->WithOwner($ownerId);
        $reservation->WithPrimaryResource($resource);

        $userRepo = $this->createMock('IUserRepository');
        $attributeRepo = $this->createMock('IAttributeRepository');

        $user = $this->LoadsUser($userRepo, $ownerId);

        $notification = new OwnerEmailUpdatedNotification($userRepo, $attributeRepo);
        $notification->Notify($reservation);

        $expectedMessage = new ReservationUpdatedEmail($user, $reservation, null, $attributeRepo, $userRepo);

        $lastMessage = $this->fakeEmailService->_LastMessage;
        $this->assertInstanceOf('ReservationUpdatedEmail', $lastMessage);
        //		$this->assertEquals($expectedMessage, $lastMessage);
    }

    public function testSendsReservationDeletedEmailIfUserWantsIt()
    {
        $ownerId = 100;
        $resourceId = 200;

        $resource = new FakeBookableResource($resourceId, 'name');

        $reservation = new ExistingReservationSeries();
        $reservation->WithOwner($ownerId);
        $reservation->WithPrimaryResource($resource);

        $userRepo = $this->createMock('IUserRepository');
        $attributeRepo = $this->createMock('IAttributeRepository');

        $user = $this->LoadsUser($userRepo, $ownerId);

        $notification = new OwnerEmailDeletedNotification($userRepo, $attributeRepo);
        $notification->Notify($reservation);

        $expectedMessage = new ReservationDeletedEmail($user, $reservation, null, $attributeRepo, $userRepo);

        $lastMessage = $this->fakeEmailService->_LastMessage;
        $this->assertInstanceOf('ReservationDeletedEmail', $lastMessage);
    }

    //	public function AsksUser($user, $event)
    //	{
    //		$user->expects($this->once())
    //			->method('WantsEventEmail')
    //			->with($this->equalTo($event))
    //			->willReturn(true);
    //	}

    public function LoadsUser($userRepo, $ownerId)
    {
        $user = new FakeUser();
        $user->_WantsEmail = true;

        $userRepo->expects($this->once())
            ->method('LoadById')
            ->with($this->equalTo($ownerId))
            ->willReturn($user);

        return $user;
    }
}
