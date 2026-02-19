<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Notification/namespace.php');

class InvitationEmailNotificationTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testSendsInvitationEmailToNewInvitees()
    {
        $ownerId = 828;
        $owner = new User();
        $inviteeId1 = 50;
        $invitee1 = new User();
        $inviteeId2 = 60;
        $invitee2 = new User();

        $instance1 = new TestReservation();
        $instance1->WithAddedInvitees([$inviteeId1, $inviteeId2]);

        $series = new TestReservationSeries();
        $series->WithCurrentInstance($instance1);
        $series->WithOwnerId($ownerId);

        $userRepo = $this->createMock('IUserRepository');
        $attributeRepo = $this->createMock('IAttributeRepository');

        $userRepo->expects($this->exactly(3))
            ->method('LoadById')
            ->willReturnMap([
                [$ownerId, $owner],
                [$inviteeId1, $invitee1],
                [$inviteeId2, $invitee2]
            ]);

        $notification = new InviteeAddedEmailNotification($userRepo, $attributeRepo);
        $notification->Notify($series);

        $this->assertEquals(2, count($this->fakeEmailService->_Messages));
        $lastExpectedMessage = new InviteeAddedEmail($owner, $invitee2, $series, $attributeRepo, $userRepo);
        $this->assertInstanceOf('InviteeAddedEmail', $this->fakeEmailService->_LastMessage);
        //		$this->assertEquals($lastExpectedMessage, $this->fakeEmailService->_LastMessage);
    }
}
