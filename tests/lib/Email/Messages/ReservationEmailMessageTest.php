<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Email/EmailService.php');
require_once(ROOT_DIR . 'tests/fakes/TestReservationEmailMessage.php');

class ReservationEmailMessageTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testIcsAttachmentMimeTypeReachesPhpMailerThroughEmailService()
    {
        $ownerId = 1;
        $owner = new FakeUser($ownerId, 'owner@example.com');
        $owner->ChangeName('Owner', 'Person');

        $instance = new TestReservation();

        $series = new TestReservationSeries();
        $series->WithOwnerId($ownerId);
        $series->WithCurrentInstance($instance);

        $message = new TestReservationEmailMessage($owner, $series, null, new FakeAttributeRepository(), new FakeUserRepository());
        $message->PopulateIcsAttachmentForTest($instance);

        $this->assertEquals('text/calendar; charset=UTF-8; method=REQUEST', $message->AttachmentMimeType());
        $this->assertEquals('reservation.ics', $message->AttachmentFileName());

        $phpMailer = $this->createMock('PHPMailer\PHPMailer\PHPMailer');
        $phpMailer->expects($this->once())
            ->method('addStringAttachment')
            ->with(
                $this->stringContains('BEGIN:VCALENDAR'),
                $this->equalTo('reservation.ics'),
                $this->anything(),
                $this->equalTo('text/calendar; charset=UTF-8; method=REQUEST')
            );

        $service = new EmailService($phpMailer);
        $service->Send($message);
    }
}
