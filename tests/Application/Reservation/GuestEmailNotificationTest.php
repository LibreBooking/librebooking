<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Notification/namespace.php');

class GuestEmailNotificationTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testSendsReservationCreatedEmailIfThereAreNewParticipants()
    {
        $ownerId = 828;
        $owner = new User();

        $guest1 = 'g1@email.com';
        $guest2 = 'g2@email.com';

        $instance1 = new TestReservation();
        $instance1->WithInvitedGuest('some@one.com');
        $instance1->ChangeInvitedGuests(['some@one.com', $guest1, $guest2]);

        $series = new TestReservationSeries();
        $series->WithOwnerId($ownerId);
        $series->WithCurrentInstance($instance1);

        $userRepo = $this->createMock('IUserRepository');
        $attributeRepo = $this->createMock('IAttributeRepository');

        $userRepo->expects($this->once())
                 ->method('LoadById')
                 ->with($this->equalTo($ownerId))
                 ->willReturn($owner);

        $notification = new GuestAddedEmailNotification($userRepo, $attributeRepo);
        $notification->Notify($series);

        $this->assertEquals(2, count($this->fakeEmailService->_Messages));
        // create it just to make sure it doesnt blow up
        $lastExpectedMessage = new GuestAddedEmail($owner, $guest2, $series, $attributeRepo, $userRepo);
        $this->assertInstanceOf('GuestAddedEmail', $this->fakeEmailService->_LastMessage);
        $this->assertEquals($guest2, $this->fakeEmailService->_LastMessage->To()[0]->Address());
    }

    public function testSendsReservationDeletedEmails()
    {
        $ownerId = 828;
        $owner = new User();

        $guest1 = 'g1@email.com';
        $guest2 = 'g2@email.com';

        $guestUser = new User();
        $guestUser->ChangeEmailAddress($guest2);

        $instance1 = new TestReservation();
        $instance1->WithParticipatingGuest($guest1);
        $instance1->WithParticipatingGuest($guest2);

        $series = new TestReservationSeries();
        $series->WithOwnerId($ownerId);
        $series->WithCurrentInstance($instance1);

        $userRepo = $this->createMock('IUserRepository');
        $attributeRepo = $this->createMock('IAttributeRepository');

        $userRepo->expects($this->once())
                 ->method('LoadById')
                 ->with($this->equalTo($ownerId))
                 ->willReturn($owner);

        $notification = new GuestDeletedEmailNotification($userRepo, $attributeRepo);
        $notification->Notify($series);

        $this->assertEquals(2, count($this->fakeEmailService->_Messages));
        $this->assertInstanceOf('GuestDeletedEmail', $this->fakeEmailService->_LastMessage);
        $this->assertEquals($guest2, $this->fakeEmailService->_LastMessage->To()->Address());
    }

    public function testSendsReservationUpdatedEmailToExistingParticipants()
    {
        $ownerId = 828;
        $owner = new User();

        $guest1 = 'g1@email.com';
        $guest2 = 'g2@email.com';

        $guestUser = new User();
        $guestUser->ChangeEmailAddress($guest2);

        $instance1 = new TestReservation();
        $instance1->WithParticipatingGuest($guest1);
        $instance1->WithParticipatingGuest($guest2);
        $instance1->ChangeInvitedGuests(['new@1.com', 'new@2.com' ]);

        $series = new TestReservationSeries();
        $series->WithOwnerId($ownerId);
        $series->WithCurrentInstance($instance1);

        $userRepo = $this->createMock('IUserRepository');
        $attributeRepo = $this->createMock('IAttributeRepository');

        $userRepo->expects($this->once())
                 ->method('LoadById')
                 ->with($this->equalTo($ownerId))
                 ->willReturn($owner);

        $notification = new GuestUpdatedEmailNotification($userRepo, $attributeRepo);
        $notification->Notify($series);

        $this->assertEquals(4, count($this->fakeEmailService->_Messages));
        $this->assertInstanceOf('GuestAddedEmail', $this->fakeEmailService->_Messages[0]);
        $this->assertInstanceOf('GuestUpdatedEmail', $this->fakeEmailService->_Messages[2]);
    }
}
