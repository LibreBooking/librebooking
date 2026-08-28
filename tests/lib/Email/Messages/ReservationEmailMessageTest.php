<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationEmailMessage.php');

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

    public function testIcsAttachmentHasNoAttendeesForPublishEmailsEvenWhenParticipantsExist()
    {
        // RFC 5546 §3.2.1: ATTENDEE MUST NOT appear in a PUBLISH VEVENT. Owner/admin
        // notification emails use PUBLISH (the recipient is not one of the attendees),
        // so no ATTENDEE line should be emitted regardless of who is on the reservation.
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
        $this->assertStringContainsString('ORGANIZER;CN=Owner Person:mailto:owner@example.com', $unfolded);
    }

    public function testIcsAttachmentIsPublishWhenReservationHasNoParticipants()
    {
        $ownerId = 1;
        $owner = new FakeUser($ownerId, 'owner@example.com');
        $owner->ChangeName('Owner', 'Person');

        $instance = new TestReservation();

        $series = new TestReservationSeries();
        $series->WithOwnerId($ownerId);
        $series->WithCurrentInstance($instance);

        $userRepo = new FakeUserRepository();
        $attributeRepo = new FakeAttributeRepository();

        $message = new TestReservationEmailMessage($owner, $series, null, $attributeRepo, $userRepo);
        $message->PopulateIcsAttachmentForTest($instance, []);

        $ics = $message->AttachmentContents();

        $this->assertStringContainsString('METHOD:PUBLISH', $ics);
        $this->assertStringNotContainsString('ATTENDEE', $ics);
        $this->assertEquals('text/calendar; charset=UTF-8; method=PUBLISH', $message->AttachmentMimeType());
    }

    public function testDeletedReservationIcsAttachmentUsesCancelMethodAndCancelledStatus()
    {
        $ownerId = 1;
        $owner = new FakeUser($ownerId, 'owner@example.com');
        $owner->ChangeName('Owner', 'Person');

        $instance = new TestReservation();

        $series = new TestReservationSeries();
        $series->WithOwnerId($ownerId);
        $series->WithCurrentInstance($instance);

        $userRepo = new FakeUserRepository();
        $attributeRepo = new FakeAttributeRepository();

        $message = new TestReservationDeletedEmail($owner, $series, null, $attributeRepo, $userRepo);
        $message->PopulateIcsAttachmentForTest($instance, []);

        $ics = $message->AttachmentContents();

        $this->assertStringContainsString('METHOD:CANCEL', $ics);
        $this->assertStringContainsString('STATUS:CANCELLED', $ics);
        // RFC 5546 §3.2.5: CANCEL SEQUENCE must exceed the original REQUEST's SEQUENCE (0).
        $this->assertStringContainsString('SEQUENCE:1', $ics);
        $this->assertEquals('text/calendar; charset=UTF-8; method=CANCEL', $message->AttachmentMimeType());
    }

    public function testSharedReservationIcsAttachmentUsesPublishMethod()
    {
        $ownerId = 1;
        $owner = new FakeUser($ownerId, 'owner@example.com');
        $owner->ChangeName('Owner', 'Person');

        $instance = new TestReservation();

        $series = new TestReservationSeries();
        $series->WithOwnerId($ownerId);
        $series->WithCurrentInstance($instance);

        $userRepo = new FakeUserRepository();
        $attributeRepo = new FakeAttributeRepository();

        $message = new TestReservationShareEmail($owner, 'friend@example.com', $series, $attributeRepo, $userRepo);
        $message->PopulateIcsAttachmentForTest($instance, []);

        $ics = $message->AttachmentContents();

        $this->assertStringContainsString('METHOD:PUBLISH', $ics);
        $this->assertStringNotContainsString('STATUS:CANCELLED', $ics);
        $this->assertEquals('text/calendar; charset=UTF-8; method=PUBLISH', $message->AttachmentMimeType());
    }

    public function testInviteeAddedIcsAttachmentUsesRequestMethod()
    {
        $ownerId = 1;
        $owner = new FakeUser($ownerId, 'owner@example.com');
        $owner->ChangeName('Owner', 'Person');

        $inviteeId = 2;
        $invitee = new FakeUser($inviteeId, 'invitee@example.com');
        $invitee->ChangeName('Invitee', 'Person');

        $instance = new TestReservation();
        $instance->WithInvitee($inviteeId);

        $series = new TestReservationSeries();
        $series->WithOwnerId($ownerId);
        $series->WithCurrentInstance($instance);

        $userRepo = new FakeUserRepository();
        $userRepo->_UserDtos[$inviteeId] = new UserDto($inviteeId, 'Invitee', 'Person', 'invitee@example.com');
        $attributeRepo = new FakeAttributeRepository();

        // This attachment is addressed to the invitee being invited — a genuine ATTENDEE
        // asked to accept/decline — so it must use REQUEST, unlike the owner-facing emails.
        $message = new TestInviteeAddedEmail($owner, $invitee, $series, $attributeRepo, $userRepo);
        $message->PopulateIcsAttachmentForTest($instance, []);

        $ics = $message->AttachmentContents();

        $this->assertStringContainsString('METHOD:REQUEST', $ics);
        $this->assertEquals('text/calendar; charset=UTF-8; method=REQUEST', $message->AttachmentMimeType());
    }

    public function testGuestAddedIcsAttachmentUsesRequestMethod()
    {
        $ownerId = 1;
        $owner = new FakeUser($ownerId, 'owner@example.com');
        $owner->ChangeName('Owner', 'Person');

        $instance = new TestReservation();
        $instance->WithParticipatingGuest('guest@example.com');

        $series = new TestReservationSeries();
        $series->WithOwnerId($ownerId);
        $series->WithCurrentInstance($instance);

        $userRepo = new FakeUserRepository();
        $attributeRepo = new FakeAttributeRepository();

        $message = new TestGuestAddedEmail($owner, 'guest@example.com', $series, $attributeRepo, $userRepo);
        $message->PopulateIcsAttachmentForTest($instance, []);

        $ics = $message->AttachmentContents();

        $this->assertStringContainsString('METHOD:REQUEST', $ics);
        $this->assertEquals('text/calendar; charset=UTF-8; method=REQUEST', $message->AttachmentMimeType());
    }

    public function testInviteeRemovedIcsAttachmentListsTheRemovedInviteeAsAttendee()
    {
        $ownerId = 1;
        $owner = new FakeUser($ownerId, 'owner@example.com');
        $owner->ChangeName('Owner', 'Person');

        $inviteeId = 2;
        $invitee = new FakeUser($inviteeId, 'invitee@example.com');
        $invitee->ChangeName('Invitee', 'Person');

        // The invitee has already been removed from the reservation by the time
        // this CANCEL notification fires, so the current instance no longer lists
        // them as an invitee.
        $instance = new TestReservation();

        $series = new TestReservationSeries();
        $series->WithOwnerId($ownerId);
        $series->WithCurrentInstance($instance);

        $userRepo = new FakeUserRepository();
        $attributeRepo = new FakeAttributeRepository();

        $message = new TestInviteeRemovedEmail($owner, $invitee, $series, $attributeRepo, $userRepo);
        $message->PopulateIcsAttachmentForTest($instance, []);

        $ics = $message->AttachmentContents();
        // RFC 5545 §3.1 folds lines longer than 75 octets; unfold before matching.
        $unfolded = str_replace("\r\n ", '', $ics);

        $this->assertStringContainsString('METHOD:CANCEL', $ics);
        $this->assertMatchesRegularExpression('/ATTENDEE[^\r\n]*mailto:invitee@example\.com/', $unfolded);
    }

    public function testInviteeAddedIcsAttachmentUsesRealOrganizerEmail()
    {
        $ownerId = 1;
        $owner = new FakeUser($ownerId, 'owner@example.com');
        $owner->ChangeName('Owner', 'Person');

        $inviteeId = 2;
        $invitee = new FakeUser($inviteeId, 'invitee@example.com');
        $invitee->ChangeName('Invitee', 'Person');

        $instance = new TestReservation();
        $instance->WithInvitee($inviteeId);

        $series = new TestReservationSeries();
        $series->WithOwnerId($ownerId);
        $series->WithCurrentInstance($instance);

        $userRepo = new FakeUserRepository();
        $userRepo->_UserDtos[$inviteeId] = new UserDto($inviteeId, 'Invitee', 'Person', 'invitee@example.com');
        $attributeRepo = new FakeAttributeRepository();

        // The reservation owner booked their own reservation here (matching
        // TestReservationSeries's default BookedBy), which must not corrupt the
        // ORGANIZER shown to the invitee being asked to accept/decline.
        $message = new TestInviteeAddedEmail($owner, $invitee, $series, $attributeRepo, $userRepo);
        $message->PopulateIcsAttachmentForTest($instance, []);

        $ics = $message->AttachmentContents();
        $unfolded = str_replace("\r\n ", '', $ics);

        $this->assertStringContainsString('ORGANIZER;CN=Owner Person:mailto:owner@example.com', $unfolded);
    }

    public function testIcsAttachmentRendersWithoutBookedBySet()
    {
        $ownerId = 1;
        $owner = new FakeUser($ownerId, 'owner@example.com');
        $owner->ChangeName('Owner', 'Person');

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

        $this->assertStringContainsString('ORGANIZER;CN=Owner Person:mailto:owner@example.com', $unfolded);
    }

    public function testGuestDeletedIcsAttachmentListsTheRemovedGuestAsAttendee()
    {
        $ownerId = 1;
        $owner = new FakeUser($ownerId, 'owner@example.com');
        $owner->ChangeName('Owner', 'Person');

        // The guest has already been removed from the reservation by the time
        // this CANCEL notification fires, so the current instance no longer lists
        // them as a guest.
        $instance = new TestReservation();

        $series = new TestReservationSeries();
        $series->WithOwnerId($ownerId);
        $series->WithCurrentInstance($instance);

        $userRepo = new FakeUserRepository();
        $attributeRepo = new FakeAttributeRepository();

        $message = new TestGuestDeletedEmail($owner, 'guest@example.com', $series, $attributeRepo, $userRepo);
        $message->PopulateIcsAttachmentForTest($instance, []);

        $ics = $message->AttachmentContents();
        $unfolded = str_replace("\r\n ", '', $ics);

        $this->assertStringContainsString('METHOD:CANCEL', $ics);
        $this->assertMatchesRegularExpression('/ATTENDEE[^\r\n]*mailto:guest@example\.com/', $unfolded);
    }
}
