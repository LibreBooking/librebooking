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

        $this->fakeConfig->SetKey(
            configDef: ConfigKeys::EMAIL_DEFAULT_FROM_ADDRESS,
            value: 'bookings@example.com',
        );
        $this->fakeConfig->SetKey(
            configDef: ConfigKeys::EMAIL_DEFAULT_FROM_NAME,
            value: 'Example Bookings',
        );
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    /**
     * Builds the ICS attachment of the email and returns its contents.
     */
    private function PopulateIcs(ReservationEmailMessage $message, Reservation $instance): string
    {
        (fn () => $this->PopulateIcsAttachment(
            currentInstance: $instance,
            attributeValues: [],
        ))->call(newThis: $message);

        return $message->AttachmentContents();
    }

    /**
     * Joins the folded lines of an ICS, so a long property can be matched as one line.
     */
    private static function Unfold(string $ics): string
    {
        return str_replace(search: "\r\n ", replace: '', subject: $ics);
    }

    private function OwnerSeries(TestReservation $instance): TestReservationSeries
    {
        $series = new TestReservationSeries();
        $series->WithOwnerId(ownerId: 1);
        $series->WithCurrentInstance(currentInstance: $instance);

        return $series;
    }

    public function testIcsAttachmentListsOnlyTheOwnerAsAttendeeEvenWhenParticipantsExist()
    {
        // other participants are invited by separate emails
        $owner = new FakeUser(userId: 1, email: 'owner@example.com');
        $owner->ChangeName(firstName: 'Owner', lastName: 'Person');

        $participantId = 2;
        $inviteeId = 3;

        $userRepo = new FakeUserRepository();
        $userRepo->_UserDtos[$participantId] = new UserDto(
            userId: $participantId,
            firstName: 'Part',
            lastName: 'One',
            emailAddress: 'part1@example.com',
        );
        $userRepo->_UserDtos[$inviteeId] = new UserDto(
            userId: $inviteeId,
            firstName: 'Invite',
            lastName: 'Two',
            emailAddress: 'invite2@example.com',
        );

        $instance = new TestReservation();
        $instance->WithParticipant(participantId: $participantId);
        $instance->WithInvitee(inviteeId: $inviteeId);
        $instance->WithParticipatingGuest(guest: 'guest1@example.com');
        $instance->WithInvitedGuest(guest: 'guest2@example.com');

        $message = new ReservationCreatedEmail(
            reservationOwner: $owner,
            reservationSeries: $this->OwnerSeries(instance: $instance),
            language: null,
            attributeRepository: new FakeAttributeRepository(),
            userRepository: $userRepo,
        );

        $ics = $this->PopulateIcs(message: $message, instance: $instance);
        $unfolded = self::Unfold(ics: $ics);

        $this->assertStringContainsString('METHOD:REQUEST', $ics);
        $this->assertEquals('text/calendar; charset=UTF-8; method=REQUEST', $message->AttachmentMimeType());
        // the site's default address is the organizer and the owner the only attendee
        $this->assertStringContainsString('ORGANIZER;CN=Example Bookings:mailto:bookings@example.com', $unfolded);
        $this->assertSame(1, substr_count(haystack: $unfolded, needle: 'ATTENDEE'));
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
            ['ParticipantDeletedEmail', 'user', IcsMethod::CANCEL, 'ROLE=REQ-PARTICIPANT;PARTSTAT=ACCEPTED'],
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
        $owner = new FakeUser(userId: 1, email: 'owner@example.com');
        $owner->ChangeName(firstName: 'Owner', lastName: 'Person');

        $participantId = 2;
        $userRepo = new FakeUserRepository();
        $userRepo->_UserDtos[$participantId] = new UserDto(
            userId: $participantId,
            firstName: 'Part',
            lastName: 'One',
            emailAddress: 'part1@example.com',
        );

        $instance = new TestReservation();
        $instance->WithParticipant(participantId: $participantId);
        $instance->WithInvitedGuest(guest: 'guest@example.com');

        $series = $this->OwnerSeries(instance: $instance);
        if ($method === IcsMethod::CANCEL) {
            $series->WithMarkedForDelete();
        }

        $attributeRepo = new FakeAttributeRepository();
        $message = match ($recipient) {
            'owner' => new $class(
                reservationOwner: $owner,
                reservationSeries: $series,
                language: null,
                attributeRepository: $attributeRepo,
                userRepository: $userRepo,
            ),
            'address' => new $class($owner, 'someone@example.com', $series, $attributeRepo, $userRepo),
            'user' => new $class(
                reservationOwner: $owner,
                invitee: new FakeUser(userId: 5, email: 'invitee@example.com'),
                reservationSeries: $series,
                attributeRepository: $attributeRepo,
                userRepository: $userRepo,
            ),
        };

        $ics = $this->PopulateIcs(message: $message, instance: $instance);

        $this->assertStringContainsString('METHOD:' . $method->value, $ics);
        $this->assertEquals('text/calendar; charset=UTF-8; method=' . $method->value, $message->AttachmentMimeType());
        $this->assertSame(1, substr_count(haystack: $ics, needle: 'ATTENDEE;'));
        // the attendee is whoever receives the email
        $attendee = ['owner' => 'owner@example.com', 'address' => 'someone@example.com', 'user' => 'invitee@example.com'][$recipient];
        $attendeeLine = '/^ATTENDEE;[^\r\n]*' . preg_quote(str: $attendeeParams, delimiter: '/')
            . ';RSVP=FALSE:mailto:' . preg_quote(str: $attendee, delimiter: '/') . '\r$/m';
        $this->assertMatchesRegularExpression($attendeeLine, self::Unfold(ics: $ics));
        if ($method === IcsMethod::CANCEL) {
            $this->assertStringContainsString('STATUS:CANCELLED', $ics);
        } else {
            $this->assertStringNotContainsString('STATUS:CANCELLED', $ics);
        }
        // a missing SEQUENCE counts as 0 and the DTSTAMP orders the messages
        $this->assertStringNotContainsString('SEQUENCE', $ics);
    }

    /**
     * @return array<string, array{class-string, 'address'|'user'}>
     */
    public static function removalEmailTypes(): array
    {
        return [
            'GuestDeletedEmail' => ['GuestDeletedEmail', 'address'],
            'InviteeRemovedEmail' => ['InviteeRemovedEmail', 'user'],
            'ParticipantDeletedEmail' => ['ParticipantDeletedEmail', 'user'],
        ];
    }

    /**
     * @param 'address'|'user' $recipient
     */
    #[DataProvider('removalEmailTypes')]
    public function testRemovingOneAttendeeCancelsWithoutCancellingTheReservation(string $class, string $recipient)
    {
        // RFC 5546 §3.2.5: STATUS is left out when only an attendee is uninvited
        $owner = new FakeUser(userId: 1, email: 'owner@example.com');
        $instance = new TestReservation();
        $series = $this->OwnerSeries(instance: $instance);

        $message = $recipient === 'address'
            ? new $class($owner, 'someone@example.com', $series, new FakeAttributeRepository(), new FakeUserRepository())
            : new $class(
                reservationOwner: $owner,
                invitee: new FakeUser(userId: 5, email: 'invitee@example.com'),
                reservationSeries: $series,
                attributeRepository: new FakeAttributeRepository(),
                userRepository: new FakeUserRepository(),
            );

        $ics = $this->PopulateIcs(message: $message, instance: $instance);

        $this->assertStringContainsString('METHOD:CANCEL', $ics);
        $this->assertDoesNotMatchRegularExpression('/^STATUS/m', $ics);
    }

    public function testPendingReservationIsTentativeAndApprovedOneConfirmed()
    {
        $owner = new FakeUser(userId: 1, email: 'owner@example.com');
        $instance = new TestReservation();
        $series = $this->OwnerSeries(instance: $instance);

        $series->SetStatusId(statusId: ReservationStatus::Pending);
        $pending = new ReservationCreatedEmail(
            reservationOwner: $owner,
            reservationSeries: $series,
            language: null,
            attributeRepository: new FakeAttributeRepository(),
            userRepository: new FakeUserRepository(),
        );
        $pendingIcs = $this->PopulateIcs(message: $pending, instance: $instance);

        $series->SetStatusId(statusId: ReservationStatus::Created);
        $approved = new ReservationApprovedEmail(
            reservationOwner: $owner,
            reservationSeries: $series,
            language: null,
            attributeRepository: new FakeAttributeRepository(),
            userRepository: new FakeUserRepository(),
        );
        $approvedIcs = $this->PopulateIcs(message: $approved, instance: $instance);

        $this->assertStringContainsString('STATUS:TENTATIVE', $pendingIcs);
        $this->assertStringContainsString('STATUS:CONFIRMED', $approvedIcs);
    }

    public function testOrganizerStaysTheSameWhenAnAdministratorCancelsTheOwnersReservation()
    {
        $owner = new FakeUser(userId: 1, email: 'owner@example.com');
        $owner->ChangeName(firstName: 'Owner', lastName: 'Person');
        $instance = new TestReservation();
        $series = $this->OwnerSeries(instance: $instance);

        $ownerSession = new UserSession(id: 1);
        $ownerSession->Email = 'owner@example.com';
        $series->WithBookedBy(bookedBy: $ownerSession);
        $created = new ReservationCreatedEmail(
            reservationOwner: $owner,
            reservationSeries: $series,
            language: null,
            attributeRepository: new FakeAttributeRepository(),
            userRepository: new FakeUserRepository(),
        );
        $createdIcs = self::Unfold(ics: $this->PopulateIcs(message: $created, instance: $instance));

        $adminSession = new UserSession(id: 2);
        $adminSession->Email = 'admin@example.com';
        $series->WithBookedBy(bookedBy: $adminSession);
        $series->WithMarkedForDelete();
        $deleted = new ReservationDeletedEmail(
            reservationOwner: $owner,
            reservationSeries: $series,
            language: null,
            attributeRepository: new FakeAttributeRepository(),
            userRepository: new FakeUserRepository(),
        );
        $deletedIcs = self::Unfold(ics: $this->PopulateIcs(message: $deleted, instance: $instance));

        $organizerAndUid = function (string $ics): array {
            preg_match_all(pattern: '/^(ORGANIZER|UID)[;:].*$/m', subject: $ics, matches: $matches);

            return $matches[0];
        };

        $this->assertSame($organizerAndUid(ics: $createdIcs), $organizerAndUid(ics: $deletedIcs));
        $this->assertStringContainsString('ORGANIZER;CN=Example Bookings:mailto:bookings@example.com', $deletedIcs);
    }

    public function testOrganizerIsLeftOutWhenNoDefaultFromAddressIsSet()
    {
        // the sender would be whoever made the change, so no organizer is better than a wrong one
        $this->fakeConfig->SetKey(configDef: ConfigKeys::EMAIL_DEFAULT_FROM_ADDRESS, value: '');

        $owner = new FakeUser(userId: 1, email: 'owner@example.com');
        $instance = new TestReservation();

        $message = new InviteeAddedEmail(
            reservationOwner: $owner,
            invitee: new FakeUser(userId: 5, email: 'invitee@example.com'),
            reservationSeries: $this->OwnerSeries(instance: $instance),
            attributeRepository: new FakeAttributeRepository(),
            userRepository: new FakeUserRepository(),
        );

        $ics = $this->PopulateIcs(message: $message, instance: $instance);

        $this->assertStringNotContainsString('ORGANIZER', $ics);
        $this->assertSame(1, substr_count(haystack: $ics, needle: 'ATTENDEE;'));
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

    public function testUpdatedEmailHasTheSubjectForTheOwnerNotTheOneForAdmins()
    {
        $owner = new FakeUser(userId: 1, email: 'owner@example.com');
        $series = $this->Series(instance: new TestReservation());

        $message = new ReservationUpdatedEmail(
            reservationOwner: $owner,
            reservationSeries: $series,
            language: null,
            attributeRepository: new FakeAttributeRepository(),
            userRepository: new FakeUserRepository(),
        );

        $resourceName = $series->Resource()->GetName();
        $ownerSubject = Resources::GetInstance()->GetString(
            key: 'ReservationUpdatedSubjectWithResource',
            args: [$resourceName],
        );
        $adminSubject = Resources::GetInstance()->GetString(
            key: 'ReservationUpdatedAdminSubjectWithResource',
            args: [$resourceName],
        );
        $this->assertSame($ownerSubject, $message->Subject());
        $this->assertNotSame($adminSubject, $message->Subject());
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
