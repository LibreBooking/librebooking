<?php

declare(strict_types=1);

use LibreBooking\Calendar\IcsMethod;
use PHPUnit\Framework\Attributes\DataProvider;

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationEmailMessage.php');
foreach (['ReservationCreatedEmail', 'ReservationUpdatedEmail', 'ReservationApprovedEmail', 'ReservationDeletedEmail', 'ReservationShareEmail', 'GuestAddedEmail', 'GuestDeletedEmail', 'InviteeAddedEmail', 'ParticipantAddedEmail'] as $emailClass) {
    require_once(ROOT_DIR . "lib/Email/Messages/{$emailClass}.php");
}

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

        $message = new TestReservationEmailMessage($owner, $series, null, $attributeRepo, $userRepo);
        $message->PopulateIcsAttachmentForTest($instance, []);

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
     * @return array<string, array{class-string, string, IcsMethod}>
     */
    public static function emailTypes(): array
    {
        $rows = [];
        $types = [
            ['ReservationCreatedEmail', 'owner', IcsMethod::REQUEST],
            ['ReservationUpdatedEmail', 'owner', IcsMethod::REQUEST],
            ['ReservationApprovedEmail', 'owner', IcsMethod::REQUEST],
            ['ReservationDeletedEmail', 'owner', IcsMethod::CANCEL],
            ['ReservationShareEmail', 'address', IcsMethod::REQUEST],
            ['GuestAddedEmail', 'address', IcsMethod::REQUEST],
            ['GuestUpdatedEmail', 'address', IcsMethod::REQUEST],
            ['GuestDeletedEmail', 'address', IcsMethod::CANCEL],
            ['InviteeAddedEmail', 'user', IcsMethod::REQUEST],
            ['InviteeUpdatedEmail', 'user', IcsMethod::REQUEST],
            ['ParticipantAddedEmail', 'user', IcsMethod::REQUEST],
            ['ParticipantUpdatedEmail', 'user', IcsMethod::REQUEST],
            ['InviteeRemovedEmail', 'user', IcsMethod::CANCEL],
        ];
        foreach ($types as [$class, $recipient, $method]) {
            $rows[$class] = [$class, $recipient, $method];
        }

        return $rows;
    }

    #[DataProvider('emailTypes')]
    public function testEveryEmailTypeAttachesTheRightMethodWithTheRecipientAsAttendee(string $class, string $recipient, IcsMethod $method)
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
        $this->assertStringContainsString(':mailto:' . $attendee, str_replace("\r\n ", '', $ics));
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

        $message = new TestReservationEmailMessage($owner, $series, null, $attributeRepo, $userRepo);
        $message->PopulateIcsAttachmentForTest($instance, []);

        $ics = $message->AttachmentContents();
        $unfolded = str_replace("\r\n ", '', $ics);

        $this->assertStringContainsString('ORGANIZER;CN=Example Bookings:mailto:bookings@example.com', $unfolded);
    }
}
