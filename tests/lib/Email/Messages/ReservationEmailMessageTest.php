<?php

declare(strict_types=1);

use LibreBooking\Calendar\IcsMethod;
use PHPUnit\Framework\Attributes\DataProvider;

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationEmailMessage.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationCreatedEmail.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationCreatedEmailAdmin.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationUpdatedEmail.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationApprovedEmail.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationDeletedEmail.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationShareEmail.php');
require_once(ROOT_DIR . 'lib/Email/Messages/GuestAddedEmail.php');
require_once(ROOT_DIR . 'lib/Email/Messages/GuestDeletedEmail.php');
require_once(ROOT_DIR . 'lib/Email/Messages/InviteeAddedEmail.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ParticipantAddedEmail.php');

class ReservationEmailMessageTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();

        $this->fakeConfig->SetKey(ConfigKeys::EMAIL_DEFAULT_FROM_ADDRESS, 'bookings@example.com');
        $this->fakeConfig->SetKey(ConfigKeys::EMAIL_DEFAULT_FROM_NAME, 'Example Bookings');
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testIcsAttachmentListsOnlyTheOwnerAsAttendeeEvenWhenParticipantsExist()
    {
        // other participants are invited by separate emails
        $ownerId = 1;
        $owner = new FakeUser($ownerId, 'owner@example.com');
        $owner->ChangeName('Owner', 'Person');

        $participantId = 2;
        $inviteeId = 3;

        $userRepo = new FakeUserRepository();
        $userRepo->_UserDtos[$participantId] = new UserDto($participantId, 'Part', 'One', 'part1@example.com');
        $userRepo->_UserDtos[$inviteeId] = new UserDto($inviteeId, 'Invite', 'Two', 'invite2@example.com');

        $instance = new TestReservation();
        $instance->WithParticipant($participantId);
        $instance->WithInvitee($inviteeId);
        $instance->WithParticipatingGuest('guest1@example.com');
        $instance->WithInvitedGuest('guest2@example.com');

        $series = new TestReservationSeries();
        $series->WithOwnerId($ownerId);
        $series->WithCurrentInstance($instance);

        $attributeRepo = new FakeAttributeRepository();

        $message = new ReservationCreatedEmail($owner, $series, null, $attributeRepo, $userRepo);
        (fn () => $this->PopulateIcsAttachment($instance, []))->call($message);

        $ics = $message->AttachmentContents();
        $unfolded = str_replace("\r\n ", '', $ics);

        $this->assertStringContainsString('METHOD:REQUEST', $ics);
        $this->assertEquals('text/calendar; charset=UTF-8; method=REQUEST', $message->AttachmentMimeType());
        // the site's default address is the organizer and the owner the only attendee
        $this->assertStringContainsString('ORGANIZER;CN=Example Bookings:mailto:bookings@example.com', $unfolded);
        $this->assertSame(1, substr_count($unfolded, 'ATTENDEE'));
        $this->assertStringContainsString('ATTENDEE;CN=Owner Person;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=FALSE:mailto:owner@example.com', $unfolded);
    }

    /**
     * @return array<string, array{class-string, 'owner'|'address'|'user', IcsMethod, string}>
     */
    public static function emailTypes(): array
    {
        $rows = [];
        $types = [
            ['ReservationCreatedEmail', 'owner', IcsMethod::REQUEST],
            ['ReservationUpdatedEmail', 'owner', IcsMethod::REQUEST],
            ['ReservationApprovedEmail', 'owner', IcsMethod::REQUEST],
            ['ReservationDeletedEmail', 'owner', IcsMethod::CANCEL],
            ['ReservationShareEmail', 'address', IcsMethod::REQUEST, 'ROLE=NON-PARTICIPANT;PARTSTAT=NEEDS-ACTION'],
            ['GuestAddedEmail', 'address', IcsMethod::REQUEST],
            ['GuestUpdatedEmail', 'address', IcsMethod::REQUEST],
            ['GuestDeletedEmail', 'address', IcsMethod::CANCEL],
            ['InviteeAddedEmail', 'user', IcsMethod::REQUEST],
            ['InviteeUpdatedEmail', 'user', IcsMethod::REQUEST],
            ['InviteeRemovedEmail', 'user', IcsMethod::CANCEL],
            ['ParticipantAddedEmail', 'user', IcsMethod::REQUEST, 'ROLE=REQ-PARTICIPANT;PARTSTAT=ACCEPTED'],
            ['ParticipantUpdatedEmail', 'user', IcsMethod::REQUEST, 'ROLE=REQ-PARTICIPANT;PARTSTAT=ACCEPTED'],
            ['ParticipantDeletedEmail', 'user', IcsMethod::CANCEL],
        ];
        foreach ($types as $type) {
            [$class, $recipient, $method] = $type;
            $rows[$class] = [$class, $recipient, $method, $type[3] ?? 'ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION'];
        }

        return $rows;
    }

    /**
     * @param 'owner'|'address'|'user' $recipient
     */
    #[DataProvider('emailTypes')]
    public function testEveryEmailTypeAttachesTheRightMethodWithTheRecipientAsAttendee(string $class, string $recipient, IcsMethod $method, string $attendeeParams)
    {
        $owner = new FakeUser(1, 'owner@example.com');
        $owner->ChangeName('Owner', 'Person');

        $participantId = 2;
        $userRepo = new FakeUserRepository();
        $userRepo->_UserDtos[$participantId] = new UserDto($participantId, 'Part', 'One', 'part1@example.com');

        $instance = new TestReservation();
        $instance->WithParticipant($participantId);
        $instance->WithInvitedGuest('guest@example.com');

        $series = new TestReservationSeries();
        $series->WithOwnerId(1);
        $series->WithCurrentInstance($instance);

        $attributeRepo = new FakeAttributeRepository();
        $message = match ($recipient) {
            'owner' => new $class($owner, $series, null, $attributeRepo, $userRepo),
            'address' => new $class($owner, 'someone@example.com', $series, $attributeRepo, $userRepo),
            'user' => new $class($owner, new FakeUser(5, 'invitee@example.com'), $series, $attributeRepo, $userRepo),
        };
        (fn () => $this->PopulateIcsAttachment($instance, []))->call($message);

        $ics = $message->AttachmentContents();

        $this->assertStringContainsString('METHOD:' . $method->value, $ics);
        $this->assertEquals('text/calendar; charset=UTF-8; method=' . $method->value, $message->AttachmentMimeType());
        $this->assertSame(1, substr_count($ics, 'ATTENDEE;'));
        // the attendee is whoever receives the email
        $attendee = ['owner' => 'owner@example.com', 'address' => 'someone@example.com', 'user' => 'invitee@example.com'][$recipient];
        $this->assertMatchesRegularExpression('/^ATTENDEE;[^\r\n]*' . preg_quote($attendeeParams, '/') . ';RSVP=FALSE:mailto:' . preg_quote($attendee, '/') . '\r$/m', str_replace("\r\n ", '', $ics));
        if ($method === IcsMethod::CANCEL) {
            $this->assertStringContainsString('STATUS:CANCELLED', $ics);
        } else {
            $this->assertStringNotContainsString('STATUS:CANCELLED', $ics);
        }
        // a missing SEQUENCE counts as 0 and the DTSTAMP orders the messages
        $this->assertStringNotContainsString('SEQUENCE', $ics);
    }

    public function testUpdatedEmailHasTheSubjectForTheOwnerNotTheOneForAdmins()
    {
        $owner = new FakeUser(1, 'owner@example.com');
        $series = new TestReservationSeries();
        $series->WithOwnerId(1);
        $series->WithCurrentInstance(new TestReservation());

        $message = new ReservationUpdatedEmail($owner, $series, null, new FakeAttributeRepository(), new FakeUserRepository());

        $resourceName = $series->Resource()->GetName();
        $this->assertSame(Resources::GetInstance()->GetString('ReservationUpdatedSubjectWithResource', [$resourceName]), $message->Subject());
        $this->assertNotSame(Resources::GetInstance()->GetString('ReservationUpdatedAdminSubjectWithResource', [$resourceName]), $message->Subject());
    }

    public function testIcsAttachmentRendersWithoutBookedBySet()
    {
        // date placeholders need a timezone on the fallback session, even if the owner has none
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_LABELS_ICS_SUMMARY, '{title} {startdate}');
        // the owner session needs permission to the resource to reach the formatting
        $this->db->SetRows([[ColumnNames::RESOURCE_ID => 1]]);

        $ownerId = 1;
        $owner = new FakeUser($ownerId, 'owner@example.com');
        $owner->ChangeName('Owner', 'Person');
        $owner->SetTimezone('');

        $instance = new TestReservation();

        $series = new TestReservationSeries();
        $series->WithOwnerId($ownerId);
        $series->WithCurrentInstance($instance);
        $series->WithBookedBy(null);

        $userRepo = new FakeUserRepository();
        $attributeRepo = new FakeAttributeRepository();

        $message = new ReservationCreatedEmail($owner, $series, null, $attributeRepo, $userRepo);
        (fn () => $this->PopulateIcsAttachment($instance, []))->call($message);

        $ics = $message->AttachmentContents();
        $unfolded = str_replace("\r\n ", '', $ics);

        $this->assertStringContainsString('ORGANIZER;CN=Example Bookings:mailto:bookings@example.com', $unfolded);
    }

    public function testOrganizerIsTheActualSenderWhenNoDefaultFromAddressIsSet()
    {
        // EmailService then sends from the message's own From() address
        $this->fakeConfig->SetKey(ConfigKeys::EMAIL_DEFAULT_FROM_ADDRESS, '');
        $this->fakeConfig->SetKey(ConfigKeys::EMAIL_DEFAULT_FROM_NAME, '');

        $owner = new FakeUser(1, 'owner@example.com');
        $series = new TestReservationSeries();
        $series->WithOwnerId(1);
        $series->WithCurrentInstance(new TestReservation());

        $message = new InviteeAddedEmail($owner, new FakeUser(5, 'invitee@example.com'), $series, new FakeAttributeRepository(), new FakeUserRepository());
        (fn () => $this->PopulateIcsAttachment($series->CurrentInstance(), []))->call($message);

        $unfolded = str_replace("\r\n ", '', $message->AttachmentContents());
        $from = $message->From();

        $this->assertStringContainsString('ORGANIZER;CN=' . $from->Name() . ':mailto:' . $from->Address(), $unfolded);
        $this->assertStringContainsString(':mailto:invitee@example.com', $unfolded);
        $this->assertSame(1, substr_count($unfolded, 'ATTENDEE;'));
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
        $message = new ReservationCreatedEmailAdmin($admin, $this->Owner(), $series, $series->Resource(), new FakeAttributeRepository());
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
