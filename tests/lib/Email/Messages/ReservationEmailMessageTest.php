<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Email/EmailService.php');
require_once(ROOT_DIR . 'tests/fakes/TestReservationEmailMessage.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationCreatedEmail.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationCreatedEmailAdmin.php');

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

    public function testBodyEscapesParticipantsAndGuestsWithoutDoubleEncoding()
    {
        $instance = new TestReservation();
        $instance->WithParticipants([2]);
        $instance->WithInvitees([3]);
        $instance->WithParticipatingGuest('"><b>guest</b>@example.com');
        $instance->WithInvitedGuest('invited&amp;guest@example.com');

        $userRepository = new FakeUserRepository();
        $userRepository->_UserDtos = [
            2 => new UserDto(2, '<script>alert(2)</script>', 'Participant', 'participant@example.com'),
            3 => new UserDto(3, 'O&#039;Brien', 'Invitee', 'invitee@example.com'),
        ];

        $message = new ReservationCreatedEmail($this->Owner(), $this->Series($instance), 'en_us', new FakeAttributeRepository(), $userRepository);
        $body = $message->Body();

        $this->assertStringContainsString('&lt;script&gt;alert(2)&lt;/script&gt; Participant', $body);
        $this->assertStringContainsString('O&#039;Brien Invitee', $body);
        $this->assertStringContainsString('&quot;&gt;&lt;b&gt;guest&lt;/b&gt;@example.com', $body);
        $this->assertStringContainsString('invited&amp;guest@example.com', $body);
        $this->assertStringNotContainsString('<script>', $body);
        $this->assertStringNotContainsString('<b>guest', $body);
        $this->assertStringNotContainsString('&amp;amp;', $body);
        $this->assertStringNotContainsString('&amp;#039;', $body);
    }

    public function testAdminBodyEscapesOwnerAndCreatorWithoutDoubleEncoding()
    {
        $bookedBy = new UserSession(2);
        $bookedBy->FirstName = '<a href="https://evil.example">Verify</a>';
        $bookedBy->LastName = 'Booker';

        $series = $this->Series(new TestReservation());
        $series->WithBookedBy($bookedBy);

        $admin = new UserDto(10, 'Admin', 'User', 'admin@example.com', null, 'en_us');
        $message = new ReservationCreatedEmailAdmin($admin, $this->Owner(), $series, $series->Resource(), new FakeAttributeRepository(), new FakeUserRepository());
        $body = $message->Body();

        $this->assertStringContainsString('&lt;img src=x onerror=alert(1)&gt; Smith &amp; Sons', $body);
        $this->assertStringContainsString('&lt;a href=&quot;https://evil.example&quot;&gt;Verify&lt;/a&gt; Booker', $body);
        $this->assertStringNotContainsString('<img src=x', $body);
        $this->assertStringNotContainsString('<a href="https://evil.example"', $body);
        $this->assertStringNotContainsString('&amp;amp;', $body);
    }

    public function testParticipantsForwardUserMembersWithEscapedStrings()
    {
        $instance = new TestReservation();
        $instance->WithParticipants([2, 3]);

        $userRepository = $this->createStub(IUserRepository::class);
        $userRepository->method('GetById')->willReturnMap([
            [2, new UserDto(2, '<b>First</b>', 'O&#039;Last', 'participant@example.com', null, null, null, 5)],
            [3, null],
        ]);

        $message = new ParticipantsForTestReservationEmailMessage($this->Owner(), $this->Series($instance), 'en_us', new FakeAttributeRepository(), $userRepository);
        [$participant, $missing] = $message->ParticipantsForTest();

        $this->assertSame('&lt;b&gt;First&lt;/b&gt;', $participant->FirstName());
        $this->assertSame('&lt;b&gt;First&lt;/b&gt; O&#039;Last (participant@example.com)', $participant->FullName);
        $this->assertTrue(isset($participant->EmailAddress));
        $this->assertSame(5, $participant->CurrentCreditCount());
        $this->assertNull($missing);
    }

    private function Owner(): FakeUser
    {
        $owner = new FakeUser(1, 'owner@example.com');
        $owner->ChangeName('<img src=x onerror=alert(1)>', 'Smith &amp; Sons');

        return $owner;
    }

    private function Series(TestReservation $instance): TestReservationSeries
    {
        $this->fakeResources->SetDateFormat('reservation_email', 'Y-m-d H:i');
        $this->fakeResources->SetDateFormat('google', 'Ymd\THis\Z');

        $resource = new FakeBookableResource(1, 'Room');
        $resource->SetScheduleId(1);

        $series = new TestReservationSeries();
        $series->WithOwnerId(1);
        $series->WithResource($resource);
        $series->WithTitle('Title');
        $series->WithDescription('Description');
        $series->WithCurrentInstance($instance);

        return $series;
    }
}

class ParticipantsForTestReservationEmailMessage extends ReservationCreatedEmail
{
    public function ParticipantsForTest(): array
    {
        $this->PopulateTemplate();

        return $this->email->getTemplateVars('Participants');
    }
}
