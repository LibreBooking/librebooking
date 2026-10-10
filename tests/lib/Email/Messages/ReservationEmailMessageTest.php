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

    public function testIcsAttachmentHasNoAttendeesForPublishEmailsEvenWhenParticipantsExist()
    {
        // invitations are separate emails, so no attendees
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

        $this->assertStringContainsString('METHOD:PUBLISH', $ics);
        $this->assertEquals('text/calendar; charset=UTF-8; method=PUBLISH', $message->AttachmentMimeType());
        $this->assertStringNotContainsString('ATTENDEE', $ics);
        // ORGANIZER is the owner; SENT-BY is the site's default address
        $this->assertStringContainsString('ORGANIZER;CN=Owner Person;SENT-BY="mailto:bookings@example.com":mailto:owner@example.com', $unfolded);
    }

    /**
     * @return array<string, array{class-string, string, IcsMethod}>
     */
    public static function emailTypes(): array
    {
        $rows = [];
        $types = [
            ['ReservationCreatedEmail', 'owner', IcsMethod::PUBLISH],
            ['ReservationUpdatedEmail', 'owner', IcsMethod::PUBLISH],
            ['ReservationApprovedEmail', 'owner', IcsMethod::PUBLISH],
            ['ReservationDeletedEmail', 'owner', IcsMethod::CANCEL],
            ['ReservationShareEmail', 'address', IcsMethod::PUBLISH],
            ['GuestAddedEmail', 'address', IcsMethod::PUBLISH],
            ['GuestUpdatedEmail', 'address', IcsMethod::PUBLISH],
            ['GuestDeletedEmail', 'address', IcsMethod::CANCEL],
            ['InviteeAddedEmail', 'user', IcsMethod::PUBLISH],
            ['InviteeUpdatedEmail', 'user', IcsMethod::PUBLISH],
            ['ParticipantAddedEmail', 'user', IcsMethod::PUBLISH],
            ['ParticipantUpdatedEmail', 'user', IcsMethod::PUBLISH],
            ['InviteeRemovedEmail', 'user', IcsMethod::CANCEL],
        ];
        foreach ($types as [$class, $recipient, $method]) {
            $rows[$class] = [$class, $recipient, $method];
        }

        return $rows;
    }

    #[DataProvider('emailTypes')]
    public function testEveryEmailTypeAttachesTheRightMethodWithoutAttendees(string $class, string $recipient, IcsMethod $method)
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
        $this->assertStringNotContainsString('ATTENDEE', $ics);
        if ($method === IcsMethod::CANCEL) {
            $this->assertStringContainsString('STATUS:CANCELLED', $ics);
            // must exceed the original SEQUENCE (0)
            $this->assertStringContainsString('SEQUENCE:1', $ics);
        } else {
            $this->assertStringNotContainsString('STATUS:CANCELLED', $ics);
        }
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

        $this->assertStringContainsString('ORGANIZER;CN=Owner Person;SENT-BY="mailto:bookings@example.com":mailto:owner@example.com', $unfolded);
    }
}
