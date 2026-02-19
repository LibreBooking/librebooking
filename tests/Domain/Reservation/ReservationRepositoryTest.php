<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

class ReservationRepositoryTest extends TestBase
{
    /**
     * @var ReservationRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setup();

        $this->repository = new ReservationRepository();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testAddReservationWithOneUserAndOneResource()
    {
        $seriesId = 100;
        $reservationId = 428;
        $userId = 232;
        $resourceId = 10978;
        $title = 'title';
        $description = 'description';
        $startCst = '2010-02-15 16:30';
        $endCst = '2010-02-16 17:00';
        $duration = DateRange::Create($startCst, $endCst, 'CST');
        $levelId = ReservationUserLevel::OWNER;
        $repeatOptions = new RepeatNone();
        $participantIds = [2, 9];
        $inviteeIds = [20, 90];
        $accessory = new ReservationAccessory(928, 3);
        $attribute = new AttributeValue(1, 'value');
        $attachment = new FakeReservationAttachment(1);
        $allowParticipation = true;
        $creditsRequired = 10;

        $startUtc = Date::Parse($startCst, 'CST')->ToUtc();
        $endUtc = Date::Parse($endCst, 'CST')->ToUtc();

        $dateCreatedUtc = Date::Parse('2010-01-01 12:14:16', 'UTC');
        Date::_SetNow($dateCreatedUtc);

        $attachmentId = 499;
        $this->db->_ExpectedInsertIds[0] = $seriesId;
        $this->db->_ExpectedInsertIds[1] = $attachmentId;
        $this->db->_ExpectedInsertIds[2] = $reservationId;

        $userSession = new FakeUserSession();

        $reservation = ReservationSeries::Create(
            $userId,
            new FakeBookableResource($resourceId),
            $title,
            $description,
            $duration,
            $repeatOptions,
            $userSession
        );
        $reservation->CurrentInstance()->SetCreditsRequired($creditsRequired);

        $repeatType = $repeatOptions->RepeatType();
        $repeatOptionsString = $repeatOptions->ConfigurationString();
        $referenceNumber = $reservation->CurrentInstance()->ReferenceNumber();

        $reservation->ChangeParticipants($participantIds);
        $reservation->ChangeInvitees($inviteeIds);
        $reservation->AddAccessory($accessory);
        $reservation->AddAttributeValue($attribute);
        $reservation->AddAttachment($attachment);
        $reservation->AllowParticipation($allowParticipation);

        $this->repository->Add($reservation);

        $insertReservationSeries = new AddReservationSeriesCommand(
            $dateCreatedUtc,
            $title,
            $description,
            $repeatType,
            $repeatOptionsString,
            ReservationTypes::Reservation,
            ReservationStatus::Created,
            $userId,
            $allowParticipation,
            null,
            $userSession->UserId
        );

        $insertReservation = new AddReservationCommand(
            $startUtc,
            $endUtc,
            $referenceNumber,
            $seriesId,
            $creditsRequired
        );

        $insertReservationResource = new AddReservationResourceCommand(
            $seriesId,
            $resourceId,
            ResourceLevel::Primary
        );

        $insertReservationUser = $this->GetAddUserCommand(
            $reservationId,
            $userId,
            $levelId
        );

        $insertReservationAccessory = new AddReservationAccessoryCommand($accessory->AccessoryId, $accessory->QuantityReserved, $seriesId);
        $insertReservationAttribute = new AddAttributeValueCommand($attribute->AttributeId, $attribute->Value, $seriesId, CustomAttributeCategory::RESERVATION);

        $insertParticipant1 = $this->GetAddUserCommand(
            $reservationId,
            $participantIds[0],
            ReservationUserLevel::PARTICIPANT
        );
        $insertParticipant2 = $this->GetAddUserCommand(
            $reservationId,
            $participantIds[1],
            ReservationUserLevel::PARTICIPANT
        );

        $insertInvitee1 = $this->GetAddUserCommand($reservationId, $inviteeIds[0], ReservationUserLevel::INVITEE);
        $insertInvitee2 = $this->GetAddUserCommand($reservationId, $inviteeIds[1], ReservationUserLevel::INVITEE);

        $addAttachment = new AddReservationAttachmentCommand(
            $attachment->FileName(),
            $attachment->FileType(),
            $attachment->FileSize(),
            $attachment->FileExtension(),
            $seriesId
        );
        $this->assertEquals(12, count($this->db->_Commands));

        $this->assertEquals($insertReservationSeries, $this->db->_Commands[0]);
        $this->assertEquals($insertReservationResource, $this->db->_Commands[1]);
        $this->assertTrue($this->db->ContainsCommand($insertReservation));
        $this->assertTrue($this->db->ContainsCommand($insertReservationUser));
        $this->assertTrue($this->db->ContainsCommand($insertParticipant1));
        $this->assertTrue($this->db->ContainsCommand($insertParticipant2));
        $this->assertTrue($this->db->ContainsCommand($insertInvitee1));
        $this->assertTrue($this->db->ContainsCommand($insertInvitee2));
        $this->assertTrue($this->db->ContainsCommand($insertReservationAccessory));
        $this->assertTrue($this->db->ContainsCommand($insertReservationAttribute));
        $this->assertTrue($this->db->ContainsCommand($addAttachment));
        $this->assertEquals($attachment->FileContents(), $this->fileSystem->_AddedFileContents);
    }

    public function testRepeatedDatesAreSaved()
    {
        $reservationSeriesId = 109;
        $reservationId = 918;
        $repeatId1 = 919;
        $repeatId3 = 921;

        $timezone = 'UTC';

        $startUtc1 = Date::Parse('2010-02-03', $timezone);
        $startUtc2 = Date::Parse('2010-02-04', $timezone);
        $startUtc3 = Date::Parse('2010-02-05', $timezone);
        $endUtc1 = Date::Parse('2010-02-06', $timezone);
        $endUtc2 = Date::Parse('2010-02-07', $timezone);
        $endUtc3 = Date::Parse('2010-02-08', $timezone);

        $dates[] = new DateRange($startUtc1, $endUtc1, $timezone);
        $dates[] = new DateRange($startUtc2, $endUtc2, $timezone);
        $dates[] = new DateRange($startUtc3, $endUtc3, $timezone);

        $duration = new TestDateRange();

        $repeats = $this->createMock('IRepeatOptions');

        $repeats->expects($this->once())
            ->method('GetDates')
            ->with($this->anything())
            ->willReturn($dates);

        $userSession = new FakeUserSession();

        $reservation = ReservationSeries::Create(
            1,
            new FakeBookableResource(1),
            null,
            null,
            $duration,
            $repeats,
            $userSession
        );

        $this->db->_ExpectedInsertIds[0] = $reservationSeriesId;
        $this->db->_ExpectedInsertIds[1] = $reservationId;
        $this->db->_ExpectedInsertIds[2] = $repeatId1;
        $this->db->_ExpectedInsertIds[3] = $repeatId3;

        $this->repository->Add($reservation);

        $instances = $reservation->Instances();

        foreach ($instances as $instance) {
            $insertRepeatCommand = new AddReservationCommand(
                $instance->StartDate()->ToUtc(),
                $instance->EndDate()->ToUtc(),
                $instance->ReferenceNumber(),
                $reservationSeriesId,
                $instance->GetCreditsRequired()
            );

            $this->assertTrue(
                in_array($insertRepeatCommand, $this->db->_Commands),
                "command $insertRepeatCommand not found"
            );
        }
    }

    public function testCanAddAdditionalResources()
    {
        $seriesId = 999;
        $id1 = 1;
        $id2 = 2;

        $reservation = new TestReservationSeries();
        $reservation->WithResource(new FakeBookableResource(837));
        $reservation->AddResource(new FakeBookableResource($id1));
        $reservation->AddResource(new FakeBookableResource($id2));

        $this->db->_ExpectedInsertId = $seriesId;

        $this->repository->Add($reservation);

        $insertResource1 = new AddReservationResourceCommand($seriesId, $id1, ResourceLevel::Additional);
        $insertResource2 = new AddReservationResourceCommand($seriesId, $id2, ResourceLevel::Additional);

        $this->assertTrue(in_array($insertResource1, $this->db->_Commands));
        $this->assertTrue(in_array($insertResource2, $this->db->_Commands));
    }

    public function testAddsReservationReminders()
    {
        $seriesId = 999;
        $reservation = new TestReservationSeries();
        $reservation->WithResource(new FakeBookableResource(837));
        $reservation->AddStartReminder(new ReservationReminder(15, ReservationReminderInterval::Hours));
        $reservation->AddEndReminder(new ReservationReminder(400, ReservationReminderInterval::Minutes));

        $minutesPriorStart = 60 * 15;
        $minutesPriorEnd = 400;

        $this->db->_ExpectedInsertId = $seriesId;

        $this->repository->Add($reservation);

        $insertStartReminder = new AddReservationReminderCommand($seriesId, $minutesPriorStart, ReservationReminderType::Start);
        $insertEndReminder = new AddReservationReminderCommand($seriesId, $minutesPriorEnd, ReservationReminderType::End);

        $this->assertTrue($this->db->ContainsCommand($insertStartReminder));
        $this->assertTrue($this->db->ContainsCommand($insertEndReminder));
    }

    public function testLoadByIdFullyHydratesReservationSeriesObject()
    {
        $seriesId = 10;
        $reservationId = 1;
        $referenceNumber = 'currentInstanceRefNum';
        $ownerId = 10;
        $resourceId = 100;
        $scheduleId = 1000;
        $title = 'title';
        $description = 'description';
        $resourceId1 = 99;
        $resourceId2 = 999;
        $begin = '2010-01-05 12:30:00';
        $end = '2010-01-05 18:30:00';
        $duration = DateRange::Create($begin, $end, 'UTC');
        $interval = 3;
        $repeatType = RepeatType::Daily;
        $terminationDateString = '2010-01-20 12:30:00';
        $terminationDate = Date::FromDatabase($terminationDateString);
        $repeatOptions = new RepeatDaily($interval, $terminationDate);

        $instance1Invitees = [1, 2, 3];
        $instance1Participants = [4, 5];
        $instance1InvitedGuests = ['g1@email.com', 'g2@email.com'];
        $instance1ParticipatingGuests = ['g3@email.com', 'g4@email.com'];
        $instance2Invitees = [6];
        $instance2Participants = [7, 8, 9];
        $instance2ParticipatingGuests = ['g2@email.com', 'g3@email.com'];
        $instance2InvitedGuests = ['g4@email.com', 'g5@email.com'];

        $resourceName = 'primary resource';
        $location = 'l';
        $contact = 'c';
        $notes = 'notes';
        $minLength = '3:00';
        $maxLength = null;
        $autoAssign = true;
        $requiresApproval = false;
        $allowMultiDay = true;
        $maxParticipants = 100;
        $minNotice = '2:10';
        $maxNotice = null;
        $statusId = ReservationStatus::Pending;
        $accessoryId1 = 8111;
        $accessoryId2 = 8222;
        $quantity1 = 11;
        $quantity2 = 22;

        $attributeId1 = 908;
        $attributeValue1 = 'custom1';
        $attributeId2 = 999;
        $attributeValue2 = 'custom2';

        $fileId = 100;

        $startReminderMinutes = 25;
        $endReminderMinutes = 120;

        $allowParticipation = true;

        $expected = new ExistingReservationSeries();
        $expected->WithId($seriesId);
        $expected->WithOwner($ownerId);
        $expected->WithPrimaryResource(new BookableResource(
            $resourceId,
            $resourceName,
            $location,
            $contact,
            $notes,
            $minLength,
            $maxLength,
            $autoAssign,
            $requiresApproval,
            $allowMultiDay,
            $maxParticipants,
            $minNotice,
            $maxNotice,
            null,
            $scheduleId
        ));
        $expected->WithTitle($title);
        $expected->WithDescription($description);
        $expected->WithResource(new BookableResource(
            $resourceId1,
            $resourceName,
            $location,
            $contact,
            $notes,
            $minLength,
            $maxLength,
            $autoAssign,
            $requiresApproval,
            $allowMultiDay,
            $maxParticipants,
            $minNotice,
            $maxNotice,
            null,
            $scheduleId
        ));
        $expected->WithResource(new BookableResource(
            $resourceId2,
            $resourceName,
            $location,
            $contact,
            $notes,
            $minLength,
            $maxLength,
            $autoAssign,
            $requiresApproval,
            $allowMultiDay,
            $maxParticipants,
            $minNotice,
            $maxNotice,
            null,
            $scheduleId
        ));
        $expected->WithRepeatOptions($repeatOptions);
        $expected->WithStatus($statusId);
        $expected->WithAccessory(new ReservationAccessory($accessoryId1, $quantity1));
        $expected->WithAccessory(new ReservationAccessory($accessoryId2, $quantity2));
        $expected->WithAttribute(new AttributeValue($attributeId1, $attributeValue1));
        $expected->WithAttribute(new AttributeValue($attributeId2, $attributeValue2));
        $expected->WithAttachment($fileId, 'doc');
        $expected->AllowParticipation($allowParticipation);

        $instance1 = new Reservation($expected, $duration->AddDays(10));
        $instance1->SetReferenceNumber('instance1');
        $instance1->SetReservationId(909);
        $instance1->WithInvitees($instance1Invitees);
        $instance1->WithParticipants($instance1Participants);
        foreach ($instance1InvitedGuests as $guest) {
            $instance1->WithInvitedGuest($guest);
        }
        foreach ($instance1ParticipatingGuests as $guest) {
            $instance1->WithParticipatingGuest($guest);
        }

        $instance2 = new Reservation($expected, $duration->AddDays(20));
        $instance2->SetReferenceNumber('instance2');
        $instance2->SetReservationId(1909);
        $instance2->WithInvitees($instance2Invitees);
        $instance2->WithParticipants($instance2Participants);

        foreach ($instance2InvitedGuests as $guest) {
            $instance2->WithInvitedGuest($guest);
        }
        foreach ($instance2ParticipatingGuests as $guest) {
            $instance2->WithParticipatingGuest($guest);
        }

        $expected->WithInstance($instance1);
        $expected->WithInstance($instance2);

        $expectedInstance = new Reservation($expected, $duration);
        $expectedInstance->SetReferenceNumber($referenceNumber);
        $expectedInstance->SetReservationId($reservationId);
        $expected->WithCurrentInstance($expectedInstance);

        $expected->WithStartReminder(new ReservationReminder($startReminderMinutes, ReservationReminderInterval::Minutes));
        $expected->WithEndReminder(new ReservationReminder($endReminderMinutes / 60, ReservationReminderInterval::Hours));

        $reservationRow = new ReservationRow(
            $reservationId,
            $begin,
            $end,
            $title,
            $description,
            $repeatType,
            $repeatOptions->ConfigurationString(),
            $referenceNumber,
            $seriesId,
            $ownerId,
            $statusId,
            $allowParticipation
        );

        $reservationInstanceRow = new ReservationInstanceRow($seriesId);
        $reservationInstanceRow
            ->WithInstance($instance1->ReservationId(), $instance1->ReferenceNumber(), $instance1->Duration())
            ->WithInstance($instance2->ReservationId(), $instance2->ReferenceNumber(), $instance2->Duration())
            ->WithInstance($reservationId, $expectedInstance->ReferenceNumber(), $expectedInstance->Duration());

        $reservationResourceRow = new ReservationResourceRow(
            $reservationId,
            $resourceName,
            $location,
            $contact,
            $notes,
            $minLength,
            $maxLength,
            $autoAssign,
            $requiresApproval,
            $allowMultiDay,
            $maxParticipants,
            $minNotice,
            $maxNotice,
            $scheduleId
        );
        $reservationResourceRow
            ->WithPrimary($resourceId)
            ->WithAdditional($resourceId1)
            ->WithAdditional($resourceId2);

        $reservationUserRow = new ReservationUserRow();
        $reservationUserRow
            ->WithParticipants($instance1, $instance1Participants)
            ->WithParticipants($instance2, $instance2Participants)
            ->WithInvitees($instance1, $instance1Invitees)
            ->WithInvitees($instance2, $instance2Invitees);

        $reservationAccessoryRow = new ReservationAccessoryRow();
        $reservationAccessoryRow->WithAccessory($accessoryId1, $quantity1)
            ->WithAccessory($accessoryId2, $quantity2);

        $attributeValueRow = new CustomAttributeValueRow();
        $attributeValueRow->With($attributeId1, $attributeValue1)
            ->With($attributeId2, $attributeValue2);

        $attachmentRow = new ReservationAttachmentItemRow();
        $attachmentRow->With($fileId, $seriesId, null, 'doc');

        $reminderRow = new ReservationReminderRow();
        $reminderRow->With(1, $seriesId, $startReminderMinutes, ReservationReminderType::Start)
            ->With(2, $seriesId, $endReminderMinutes, ReservationReminderType::End);

        $guestRow = new ReservationGuestRow();
        $guestRow
            ->WithParticipants($instance1, $instance1ParticipatingGuests)
            ->WithParticipants($instance2, $instance2ParticipatingGuests)
            ->WithInvitees($instance1, $instance1InvitedGuests)
            ->WithInvitees($instance2, $instance2InvitedGuests);

        $this->db->SetRow(0, $reservationRow->Rows());
        $this->db->SetRow(1, $reservationInstanceRow->Rows());
        $this->db->SetRow(2, $reservationResourceRow->Rows());
        $this->db->SetRow(3, $reservationUserRow->Rows());
        $this->db->SetRow(4, $reservationAccessoryRow->Rows());
        $this->db->SetRow(5, $attributeValueRow->Rows());
        $this->db->SetRow(6, $attachmentRow->Rows());
        $this->db->SetRow(7, $reminderRow->Rows());
        $this->db->SetRow(8, $guestRow->Rows());

        $actualReservation = $this->repository->LoadById($reservationId);

        $this->assertEquals($expected, $actualReservation);

        $getReservation = new GetReservationByIdCommand($reservationId);
        $getInstances = new GetReservationSeriesInstances($seriesId);
        $getResources = new GetReservationResourcesCommand($seriesId);
        $getParticipants = new GetReservationSeriesParticipantsCommand($seriesId);
        $getAccessories = new GetReservationAccessoriesCommand($seriesId);
        $getAttributeValues = new GetAttributeValuesCommand($seriesId, CustomAttributeCategory::RESERVATION);
        $getAttachments = new GetReservationAttachmentsCommand($seriesId);
        $getReminders = new GetReservationReminders($seriesId);
        $getGuests = new GetReservationSeriesGuestsCommand($seriesId);

        $this->assertTrue($this->db->ContainsCommand($getReservation));
        $this->assertTrue($this->db->ContainsCommand($getInstances));
        $this->assertTrue($this->db->ContainsCommand($getResources));
        $this->assertTrue($this->db->ContainsCommand($getParticipants));
        $this->assertTrue($this->db->ContainsCommand($getAccessories));
        $this->assertTrue($this->db->ContainsCommand($getAttributeValues));
        $this->assertTrue($this->db->ContainsCommand($getAttachments));
        $this->assertTrue($this->db->ContainsCommand($getReminders));
        $this->assertTrue($this->db->ContainsCommand($getGuests));
    }

    public function testChangingOnlySharedInformationForFullSeriesJustUpdatesSeriesTable()
    {
        $userId = 10;
        $title = 'new title';
        $description = 'new description';
        $allowParticipation = true;

        $builder = new ExistingReservationSeriesBuilder();
        $existingReservation = $builder->Build();

        $updatedBy = new FakeUserSession();
        $existingReservation->Update($userId, $existingReservation->Resource(), $title, $description, $updatedBy);
        $existingReservation->AllowParticipation($allowParticipation);

        $repeatOptions = $existingReservation->RepeatOptions();
        $repeatType = $repeatOptions->RepeatType();
        $repeatConfiguration = $repeatOptions->ConfigurationString();

        $this->repository->Update($existingReservation);

        $updateSeriesCommand = new UpdateReservationSeriesCommand(
            $existingReservation->SeriesId(),
            $title,
            $description,
            $repeatType,
            $repeatConfiguration,
            Date::Now(),
            $existingReservation->StatusId(),
            $userId,
            $allowParticipation,
            $updatedBy->UserId
        );
        $this->assertEquals(1, count($this->db->_Commands));
        $this->assertEquals($updateSeriesCommand, $this->db->_Commands[0]);
    }

    public function testWhenCreditsConsumedIncreases()
    {
        Date::_SetNow(new Date('2018-01-20 3:00', 'UTC'));
        $layout = new FakeScheduleLayout();
        $layout->_SlotCount = new SlotCount(2, 2);
        $userId = 10;
        $title = 'new title';
        $description = 'new description';

        $resource = new FakeBookableResource(1);
        $resource->SetCreditsPerSlot(2);
        $resource->SetPeakCreditsPerSlot(4);

        $instance = new TestReservation('ref', new DateRange(Date::Now()->AddDays(1), Date::Now()->AddDays(1)->AddHours(1)));
        $instance->WithCreditsConsumed(10);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($instance);
        $builder->WithPrimaryResource($resource);
        $existingReservation = $builder->Build();

        $existingReservation->CalculateCredits($layout);
        $existingReservation->Update($userId, $existingReservation->Resource(), $title, $description, new FakeUserSession());

        $this->repository->Update($existingReservation);

        $this->assertEquals(new AdjustUserCreditsCommand($userId, 2, 'ReservationUpdatedLog' . $existingReservation->CurrentInstance()->ReferenceNumber()), $this->db->_Commands[1], 'was taking 10, required 12');
    }

    public function testWhenCreditsConsumedDecreases()
    {
        Date::_SetNow(new Date('2018-01-20 3:00', 'UTC'));
        $layout = new FakeScheduleLayout();
        $layout->_SlotCount = new SlotCount(2, 2);
        $userId = 10;
        $title = 'new title';
        $description = 'new description';

        $resource = new FakeBookableResource(1);
        $resource->SetCreditsPerSlot(2);
        $resource->SetPeakCreditsPerSlot(4);

        $instance = new TestReservation('ref', new DateRange(Date::Now()->AddDays(1), Date::Now()->AddDays(1)->AddHours(1)));
        $instance->WithCreditsConsumed(14);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($instance);
        $builder->WithPrimaryResource($resource);
        $existingReservation = $builder->Build();

        $existingReservation->CalculateCredits($layout);
        $existingReservation->Update($userId, $existingReservation->Resource(), $title, $description, new FakeUserSession());

        $this->repository->Update($existingReservation);

        $this->assertEquals(new AdjustUserCreditsCommand($userId, -2, 'ReservationUpdatedLog' . $existingReservation->CurrentInstance()->ReferenceNumber()), $this->db->_Commands[1], 'was taking 10, required 12');
    }

    public function testDoesNotDeductCreditsForPastInstances()
    {
        $layout = new FakeScheduleLayout();
        $layout->_SlotCount = new SlotCount(2, 2);
        $userId = 10;
        $title = 'new title';
        $description = 'new description';

        $resource = new FakeBookableResource(1);
        $resource->SetCreditsPerSlot(2);
        $resource->SetPeakCreditsPerSlot(4);

        $instance = new TestReservation('ref', new DateRange(Date::Now()->AddDays(-2), Date::Now()->AddDays(-1)));
        $instance->WithCreditsConsumed(10);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($instance);
        $builder->WithPrimaryResource($resource);
        $existingReservation = $builder->Build();

        $existingReservation->CalculateCredits($layout);
        $existingReservation->Update($userId, $existingReservation->Resource(), $title, $description, new FakeUserSession());

        $this->repository->Update($existingReservation);

        $this->assertTrue(count($this->db->GetCommandsOfType('AdjustUserCreditsCommand')) == 0);
    }

    public function testDoesNotDeductCreditsForWhenNoChangeInCredits()
    {
        $layout = new FakeScheduleLayout();
        $layout->_SlotCount = new SlotCount(2, 2);
        $userId = 10;
        $title = 'new title';
        $description = 'new description';

        $resource = new FakeBookableResource(1);
        $resource->SetCreditsPerSlot(2);
        $resource->SetPeakCreditsPerSlot(4);

        $instance = new TestReservation('ref', new DateRange(Date::Now()->AddDays(-2), Date::Now()->AddDays(-1)));
        $instance->WithCreditsConsumed(12);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($instance);
        $builder->WithPrimaryResource($resource);
        $existingReservation = $builder->Build();

        $existingReservation->CalculateCredits($layout);
        $existingReservation->Update($userId, $existingReservation->Resource(), $title, $description, new FakeUserSession());

        $this->repository->Update($existingReservation);

        $this->assertTrue(count($this->db->GetCommandsOfType('AdjustUserCreditsCommand')) == 0);
    }

    public function testBranchedSingleInstance()
    {
        $seriesId = 10909;
        $userId = 10;
        $resourceId = 11;
        $title = 'new title';
        $description = 'new description';
        $expectedRepeat = new RepeatNone();
        $referenceNumber = 'ref number current';
        $allowParticipation = true;

        $currentReservation = new TestReservation($referenceNumber, new TestDateRange());

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithRequiresNewSeries(true);
        $builder->WithRepeatOptions($expectedRepeat);
        $builder->WithCurrentInstance($currentReservation);

        $existingReservation = $builder->BuildTestVersion();
        $updatedBy = new FakeUserSession();
        $existingReservation->Update($userId, new FakeBookableResource($resourceId), $title, $description, $updatedBy);
        $existingReservation->AllowParticipation($allowParticipation);

        $this->db->_ExpectedInsertId = $seriesId;

        $this->repository->Update($existingReservation);

        $addNewSeriesCommand = new AddReservationSeriesCommand(
            Date::Now(),
            $title,
            $description,
            $expectedRepeat->RepeatType(),
            $expectedRepeat->ConfigurationString(),
            ReservationTypes::Reservation,
            ReservationStatus::Created,
            $userId,
            $allowParticipation,
            null,
            $updatedBy->UserId
        );

        $updateReservationCommand = $this->GetUpdateReservationCommand($seriesId, $currentReservation);

        $this->assertTrue(in_array($addNewSeriesCommand, $this->db->_Commands));
        $this->assertTrue(in_array($updateReservationCommand, $this->db->_Commands));
    }

    public function testBranchedWithMovedAndAddedAndRemovedInstances()
    {
        $newSeriesId = 10910;
        $newInstanceId1 = 2827;
        $newInstanceId2 = 2828;
        $userId = 10;
        $resourceId = 11;
        $title = 'new title';
        $description = 'new description';
        $allowParticipation = true;

        $dateRange = DateRange::Create('2010-01-10 05:30:00', '2010-01-10 08:30:00', 'UTC');
        $existingInstance1 = new TestReservation('123', $dateRange->AddDays(1));
        $existingInstance2 = new TestReservation('223', $dateRange->AddDays(2));
        $newInstance1 = new TestReservation('323', $dateRange->AddDays(3));
        $newInstance2 = new TestReservation('423', $dateRange->AddDays(4));
        $removedInstance1 = new TestReservation('523', $dateRange->AddDays(5));
        $removedInstance2 = new TestReservation('623', $dateRange->AddDays(6));
        $currentInstance = new TestReservation('999', $dateRange);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithEvent(new InstanceAddedEvent($newInstance1, $builder->series));
        $builder->WithEvent(new InstanceAddedEvent($newInstance2, $builder->series));
        $builder->WithEvent(new InstanceRemovedEvent($removedInstance1, $builder->series));
        $builder->WithEvent(new InstanceRemovedEvent($removedInstance2, $builder->series));
        $builder->WithEvent(new SeriesBranchedEvent($builder->series));
        $builder->WithRequiresNewSeries(true);
        $builder->WithInstance($existingInstance1);
        $builder->WithInstance($existingInstance2);
        $builder->WithCurrentInstance($currentInstance);

        $existingReservation = $builder->BuildTestVersion();
        $existingReservation->WithOwner($userId);
        $updatedBy = new FakeUserSession();
        $existingReservation->Update($userId, new FakeBookableResource($resourceId), $title, $description, $updatedBy);
        $existingReservation->AllowParticipation($allowParticipation);

        $expectedRepeat = $existingReservation->RepeatOptions();

        $this->db->_ExpectedInsertIds[] = $newSeriesId;
        $this->db->_ExpectedInsertIds[] = $newInstanceId1;
        $this->db->_ExpectedInsertIds[] = $newInstanceId2;

        $this->repository->Update($existingReservation);

        $addNewSeriesCommand = new AddReservationSeriesCommand(
            Date::Now(),
            $title,
            $description,
            $expectedRepeat->RepeatType(),
            $expectedRepeat->ConfigurationString(),
            ReservationTypes::Reservation,
            ReservationStatus::Created,
            $userId,
            $allowParticipation,
            null,
            $updatedBy->UserId
        );

        $updateReservationCommand1 = $this->GetUpdateReservationCommand($newSeriesId, $existingInstance1);
        $updateReservationCommand2 = $this->GetUpdateReservationCommand($newSeriesId, $existingInstance2);
        $updateReservationCommand3 = $this->GetUpdateReservationCommand($newSeriesId, $currentInstance);

        $addReservationCommand1 = $this->GetAddReservationCommand($newSeriesId, $newInstance1);
        $addReservationCommand2 = $this->GetAddReservationCommand($newSeriesId, $newInstance2);

        $insertReservationUser1 = $this->GetAddUserCommand($newInstanceId1, $userId, ReservationUserLevel::OWNER);
        $insertReservationUser2 = $this->GetAddUserCommand($newInstanceId2, $userId, ReservationUserLevel::OWNER);

        $removeReservationCommand1 = new RemoveReservationCommand($removedInstance1->ReferenceNumber());
        $removeReservationCommand2 = new RemoveReservationCommand($removedInstance2->ReferenceNumber());

        $this->assertEquals($addNewSeriesCommand, $this->db->_Commands[0]);

        $commands = $this->db->GetCommandsOfType('UpdateReservationCommand');
        $this->assertEquals(3, count($commands));

        $addUserCommands = $this->db->GetCommandsOfType('AddReservationUserCommand');
        $this->assertEquals(2, count($addUserCommands));

        $this->assertTrue(in_array($updateReservationCommand1, $this->db->_Commands));
        $this->assertTrue(in_array($updateReservationCommand2, $this->db->_Commands));
        $this->assertTrue(in_array($updateReservationCommand3, $this->db->_Commands));

        $this->assertTrue(in_array($addReservationCommand1, $this->db->_Commands));
        $this->assertTrue(in_array($addReservationCommand2, $this->db->_Commands));

        $this->assertTrue(in_array($insertReservationUser1, $this->db->_Commands));
        $this->assertTrue(in_array($insertReservationUser2, $this->db->_Commands));

        $this->assertTrue(in_array($removeReservationCommand1, $this->db->_Commands));
        $this->assertTrue(in_array($removeReservationCommand2, $this->db->_Commands));
    }

    public function testWithUpdatedInstances()
    {
        $seriesId = 3929;
        $dateRange = new TestDateRange();

        $instance1 = new TestReservation('323', $dateRange->AddDays(3));
        $instance2 = new TestReservation('423', $dateRange->AddDays(4));

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithEvent(new InstanceUpdatedEvent($instance1, $builder->series));
        $builder->WithEvent(new InstanceUpdatedEvent($instance2, $builder->series));
        $series = $builder->BuildTestVersion();
        $series->WithId($seriesId);
        $this->repository->Update($series);

        $updateReservationCommand1 = $this->GetUpdateReservationCommand($seriesId, $instance1);
        $updateReservationCommand2 = $this->GetUpdateReservationCommand($seriesId, $instance2);

        $commands = $this->db->GetCommandsOfType('UpdateReservationCommand');

        $this->assertEquals(2, count($commands));
        $this->assertTrue(in_array($updateReservationCommand1, $this->db->_Commands));
        $this->assertTrue(in_array($updateReservationCommand2, $this->db->_Commands));
    }

    public function testDeleteSeries()
    {
        $deletedBy = new FakeUserSession(100);
        $seriesId = 981;
        $eventSeries = new ExistingReservationSeries();
        $eventSeries->WithId($seriesId);

        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->BuildTestVersion();
        $series->TestSetCreditsConsumed(10);
        $series->WithId($seriesId);
        $series->Delete($deletedBy);
        $series->WithUnusedCreditBalance(10);

        $this->repository->Delete($series);

        $deleteSeriesCommand = new DeleteSeriesCommand($series->SeriesId(), Date::Now(), $deletedBy->UserId);
        $adjustCreditsCommand = new AdjustUserCreditsCommand($series->UserId(), -10, 'ReservationDeletedLog' . $series->CurrentInstance()->ReferenceNumber());

        $this->assertEquals(2, count($this->db->_Commands));
        $this->assertTrue(in_array($deleteSeriesCommand, $this->db->_Commands));
        $this->assertTrue(in_array($adjustCreditsCommand, $this->db->_Commands));
    }

    public function testDeleteInstances()
    {
        $seriesId = 981;
        $instance1 = new TestReservation('ref1');
        $instance2 = new TestReservation('ref2');

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithEvent(new InstanceRemovedEvent($instance1, $builder->series));
        $builder->WithEvent(new InstanceRemovedEvent($instance2, $builder->series));
        $series = $builder->BuildTestVersion();
        $series->WithId($seriesId);

        $this->repository->Delete($series);

        $deleteInstance1 = new RemoveReservationCommand($instance1->ReferenceNumber());
        $deleteInstance2 = new RemoveReservationCommand($instance2->ReferenceNumber());

        $this->assertEquals(2, count($this->db->_Commands));
        $this->assertTrue(in_array($deleteInstance1, $this->db->_Commands));
        $this->assertTrue(in_array($deleteInstance2, $this->db->_Commands));
    }

    public function testChangesParticipantsForAllInstances()
    {
        $instanceId1 = 100;
        $reservation1 = new TestReservation(null, null, $instanceId1);
        $reservation1->WithParticipants([1, 2, 3]);

        $instanceId2 = 101;
        $reservation2 = new TestReservation(null, null, $instanceId2);
        $reservation2->WithParticipants([2, 3]);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithInstance($reservation1);
        $builder->WithInstance($reservation2);

        $series = $builder->BuildTestVersion();
        $series->ChangeParticipants([3, 4]);

        $this->repository->Update($series);

        $this->assertTrue($this->db->ContainsCommand($this->GetRemoveUserCommand($instanceId1, 1)));
        $this->assertTrue($this->db->ContainsCommand($this->GetRemoveUserCommand($instanceId1, 2)));
        $this->assertTrue($this->db->ContainsCommand($this->GetAddUserCommand(
            $instanceId1,
            4,
            ReservationUserLevel::PARTICIPANT
        )));

        $this->assertTrue($this->db->ContainsCommand($this->GetRemoveUserCommand($instanceId2, 2)));
        $this->assertTrue($this->db->ContainsCommand($this->GetAddUserCommand(
            $instanceId2,
            4,
            ReservationUserLevel::PARTICIPANT
        )));
    }

    public function testChangesInviteesForAllInstances()
    {
        $instanceId1 = 100;
        $reservation1 = new TestReservation(null, null, $instanceId1);
        $reservation1->WithInvitees([1, 2, 3]);

        $instanceId2 = 101;
        $reservation2 = new TestReservation(null, null, $instanceId2);
        $reservation2->WithInvitees([2, 3]);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithInstance($reservation1);
        $builder->WithInstance($reservation2);

        $series = $builder->BuildTestVersion();
        $series->ChangeInvitees([3, 4]);

        $this->repository->Update($series);

        $this->assertTrue($this->db->ContainsCommand($this->GetRemoveUserCommand($instanceId1, 1)));
        $this->assertTrue($this->db->ContainsCommand($this->GetRemoveUserCommand($instanceId1, 2)));
        $this->assertTrue($this->db->ContainsCommand($this->GetAddUserCommand(
            $instanceId1,
            4,
            ReservationUserLevel::INVITEE
        )));

        $this->assertTrue($this->db->ContainsCommand($this->GetRemoveUserCommand($instanceId2, 2)));
        $this->assertTrue($this->db->ContainsCommand($this->GetAddUserCommand(
            $instanceId2,
            4,
            ReservationUserLevel::INVITEE
        )));
    }

    public function testChangesGuestsForAllInstances()
    {
        $instanceId1 = 100;
        $reservation1 = new TestReservation(null, null, $instanceId1);
        $reservation1->WithInvitedGuest('g1@email.com');
        $reservation1->WithInvitedGuest('g2@email.com');
        $reservation1->WithInvitedGuest('g3@email.com');

        $instanceId2 = 101;
        $reservation2 = new TestReservation(null, null, $instanceId2);
        $reservation2->WithInvitedGuest('g2@email.com');
        $reservation2->WithInvitedGuest('g3@email.com');

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithInstance($reservation1);
        $builder->WithInstance($reservation2);

        $series = $builder->BuildTestVersion();
        $series->ChangeGuests(['g3@email.com', 'g4@email.com'], []);

        $this->repository->Update($series);

        $this->assertTrue($this->db->ContainsCommand($this->GetRemoveGuestCommand($instanceId1, 'g1@email.com')));
        $this->assertTrue($this->db->ContainsCommand($this->GetRemoveGuestCommand($instanceId1, 'g2@email.com')));
        $this->assertTrue($this->db->ContainsCommand($this->GetAddGuestCommand($instanceId1, 'g4@email.com', ReservationUserLevel::INVITEE)));

        $this->assertTrue($this->db->ContainsCommand($this->GetRemoveGuestCommand($instanceId2, 'g2@email.com')));
        $this->assertTrue($this->db->ContainsCommand($this->GetAddGuestCommand($instanceId2, 'g4@email.com', ReservationUserLevel::INVITEE)));
    }

    public function testAddsResources()
    {
        $addedId = 29;
        $removedId = 28;

        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->BuildTestVersion();
        $series->WithResource(new FakeBookableResource($removedId));
        $series->ChangeResources([new FakeBookableResource($addedId)]);

        $this->repository->Update($series);

        $addCommand = new AddReservationResourceCommand($series->SeriesId(), $addedId, ResourceLevel::Additional);
        $removeCommand = new RemoveReservationResourceCommand($series->SeriesId(), $removedId);

        $this->assertTrue($this->db->ContainsCommand($addCommand));
        $this->assertTrue($this->db->ContainsCommand($removeCommand));
    }

    public function testChangesAccessories()
    {
        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->BuildTestVersion();
        $series->WithAccessory(new ReservationAccessory(1, 1));
        $series->ChangeAccessories([new ReservationAccessory(2, 2)]);

        $seriesId = $series->SeriesId();
        $this->repository->Update($series);

        $addCommand = new AddReservationAccessoryCommand(2, 2, $seriesId);
        $removeCommand = new RemoveReservationAccessoryCommand($seriesId, 1);

        $this->assertTrue($this->db->ContainsCommand($addCommand));
        $this->assertTrue($this->db->ContainsCommand($removeCommand));
    }

    public function testChangesOwner()
    {
        $currentOwner = 1111;
        $newOwner = 2222;
        $instanceId1 = 100;
        $reservation1 = new TestReservation(null, null, $instanceId1);

        $instanceId2 = 101;
        $reservation2 = new TestReservation(null, null, $instanceId2);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithInstance($reservation1);
        $builder->WithInstance($reservation2);

        $series = $builder->Build();
        $series->WithOwner($currentOwner);
        $series->Update($newOwner, $series->Resource(), '', '', $this->fakeUser);
        $this->repository->Update($series);

        $this->assertTrue($this->db->ContainsCommand(new RemoveReservationUsersCommand($instanceId1, ReservationUserLevel::OWNER)));
        $this->assertTrue($this->db->ContainsCommand($this->GetAddUserCommand($instanceId1, $newOwner, ReservationUserLevel::OWNER)));
        $this->assertTrue($this->db->ContainsCommand($this->GetAddUserCommand($instanceId2, $newOwner, ReservationUserLevel::OWNER)));
    }

    public function testEventsWhichAreNotNecessaryWhenSeriesIsBranchedAreIgnored()
    {
        $oldOwnerId = 100;
        $newUserId = 200;
        $resource = new FakeBookableResource(92);
        $accessory = new ReservationAccessory(9292, 4);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithRepeatOptions(new RepeatNone());
        $series = $builder->Build();
        $instance = new Reservation($series, new TestDateRange(), 123123);
        $series->WithCurrentInstance($instance);
        $series->WithOwner($oldOwnerId);

        $series->ApplyChangesTo(SeriesUpdateScope::FullSeries);

        $series->Update($newUserId, $series->Resource(), '', '', $this->fakeUser);
        $series->ChangeResources([$resource]);
        $series->ChangeAccessories([$accessory]);

        $this->repository->Update($series);

        $addResources = $this->db->GetCommandsOfType('AddReservationResourceCommand');
        $addAccessories = $this->db->GetCommandsOfType('AddReservationAccessoryCommand');
        $deleteResources = $this->db->GetCommandsOfType('RemoveReservationResourceCommand');
        $deleteAccessories = $this->db->GetCommandsOfType('RemoveReservationAccessoryCommand');

        $this->assertTrue($this->db->ContainsCommand(new AddReservationUserCommand($instance->ReservationId(), $newUserId, ReservationUserLevel::OWNER)));
        $this->assertEquals(count($series->AdditionalResources()), count($addResources), 'dont want to double add');
        $this->assertEquals(count($series->Accessories()), count($addAccessories));
        $this->assertEquals(1, count($deleteResources));
        $this->assertEquals(0, count($deleteAccessories));
    }

    public function testChangesAttributeValues()
    {
        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->BuildTestVersion();
        $series->WithAttribute(new AttributeValue(1, '1abc'));
        $series->WithAttribute(new AttributeValue(2, '2abc'));
        $series->WithAttribute(new AttributeValue(3, '3abc'));
        $updatedAttributes = [
            new AttributeValue(2, '22abc'),
            new AttributeValue(3, '3abc'),
            new AttributeValue(4, '4abc'),
        ];
        $series->ChangeAttributes($updatedAttributes);

        $seriesId = $series->SeriesId();
        $this->repository->Update($series);

        $addNewCommand = new AddAttributeValueCommand(4, '4abc', $seriesId, CustomAttributeCategory::RESERVATION);
        $removeOldCommand = new RemoveAttributeValueCommand(1, $seriesId);
        $removeUpdated = new RemoveAttributeValueCommand(2, $seriesId);
        $addUpdated = new AddAttributeValueCommand(2, '22abc', $seriesId, CustomAttributeCategory::RESERVATION);

        $this->assertTrue($this->db->ContainsCommand($addNewCommand));
        $this->assertTrue($this->db->ContainsCommand($removeOldCommand));
        $this->assertTrue($this->db->ContainsCommand($addUpdated));
        $this->assertTrue($this->db->ContainsCommand($removeUpdated));
    }

    public function testAddAttachment()
    {
        $expectedFileId = 5346;

        $fileName = 'fn';
        $fileType = 'doc';
        $fileSize = 100;
        $fileContent = 'contents';
        $fileExtension = 'doc';
        $seriesId = 10;

        $attachment = ReservationAttachment::Create(
            $fileName,
            $fileType,
            $fileSize,
            $fileContent,
            $fileExtension,
            $seriesId
        );
        $command = new AddReservationAttachmentCommand($fileName, $fileType, $fileSize, $fileExtension, $seriesId);

        $this->db->_ExpectedInsertId = $expectedFileId;

        $fileId = $this->repository->AddReservationAttachment($attachment);

        $this->assertEquals($command, $this->db->_LastCommand);
        $this->assertEquals($expectedFileId, $fileId);
        $this->assertEquals($expectedFileId, $attachment->FileId());
        $this->assertEquals($fileContent, $this->fileSystem->_AddedFileContents);
        $this->assertEquals("$fileId.$fileExtension", $this->fileSystem->_AddedFileName);
        $this->assertEquals($this->fileSystem->GetReservationAttachmentsPath(), $this->fileSystem->_AddedFilePath);
    }

    public function testLoadsAttachment()
    {
        $attachmentId = 1;
        $fileName = 'fn';
        $fileType = 'doc';
        $fileSize = 100;
        $fileContent = 'contents';
        $fileExtension = 'doc';
        $seriesId = 10;

        $expectedAttachment = ReservationAttachment::Create(
            $fileName,
            $fileType,
            $fileSize,
            $fileContent,
            $fileExtension,
            $seriesId
        );
        $expectedAttachment->WithFileId($attachmentId);

        $this->fileSystem->_ExpectedContents = $fileContent;

        $this->db->SetRows([[
            ColumnNames::FILE_ID => $attachmentId,
            ColumnNames::FILE_EXTENSION => $fileExtension,
            ColumnNames::FILE_NAME => $fileName,
            ColumnNames::FILE_SIZE => $fileSize,
            ColumnNames::FILE_TYPE => $fileType,
            ColumnNames::SERIES_ID => $seriesId]
        ]);

        $command = new GetReservationAttachmentCommand($attachmentId);
        $actualAttachment = $this->repository->LoadReservationAttachment($attachmentId);

        $this->assertEquals($command, $this->db->_LastCommand);
        $this->assertEquals($expectedAttachment, $actualAttachment);
        $this->assertEquals(
            $this->fileSystem->GetReservationAttachmentsPath() . "$attachmentId.$fileExtension",
            $this->fileSystem->_ContentsPath
        );
    }

    public function testAddAttachmentToExistingReservation()
    {
        $seriesId = 10;

        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->WithId($seriesId)->Build();

        $expectedFileId = 5346;

        $fileName = 'fn';
        $fileType = 'doc';
        $fileSize = 100;
        $fileContent = 'contents';
        $fileExtension = 'doc';

        $attachment = ReservationAttachment::Create(
            $fileName,
            $fileType,
            $fileSize,
            $fileContent,
            $fileExtension,
            $seriesId
        );
        $series->AddAttachment($attachment);

        $command = new AddReservationAttachmentCommand($fileName, $fileType, $fileSize, $fileExtension, $seriesId);
        $this->db->_ExpectedInsertId = $expectedFileId;

        $this->repository->Update($series);

        $this->assertEquals($command, $this->db->_LastCommand);
        $this->assertEquals($expectedFileId, $attachment->FileId());
    }

    public function testRemovesAttachments()
    {
        $fileId1 = 100;
        $fileId2 = 200;
        $seriesId = 99;
        $fileExt1 = 'doc';
        $fileExt2 = 'txt';

        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->WithId($seriesId)->Build();
        $series->WithAttachment($fileId1, $fileExt1);
        $series->WithAttachment($fileId2, $fileExt2);
        $series->RemoveAttachment($fileId1);
        $series->RemoveAttachment($fileId2);

        $command1 = new RemoveReservationAttachmentCommand($fileId1);
        $command2 = new RemoveReservationAttachmentCommand($fileId2);

        $this->repository->Update($series);

        $this->assertTrue($this->db->ContainsCommand($command1));
        $this->assertTrue($this->db->ContainsCommand($command2));
        $this->assertEquals(2, count($this->fileSystem->_RemovedFiles));
        $this->assertTrue(in_array(
            $this->fileSystem->GetReservationAttachmentsPath() . "$fileId2.$fileExt2",
            $this->fileSystem->_RemovedFiles
        ));
    }

    public function testReplacesReminder()
    {
        $seriesId = 99;
        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->WithId($seriesId)->Build();

        $series->WithStartReminder(new ReservationReminder(100, ReservationReminderInterval::Days));
        $series->WithEndReminder(new ReservationReminder(15, ReservationReminderInterval::Minutes));

        $start = new ReservationReminder(1, ReservationReminderInterval::Minutes);
        $end = new ReservationReminder(1, ReservationReminderInterval::Days);
        $series->AddStartReminder($start);
        $series->AddEndReminder($end);

        $this->repository->Update($series);

        $command1 = new RemoveReservationReminderCommand($seriesId, ReservationReminderType::Start);
        $command2 = new AddReservationReminderCommand($seriesId, $start->MinutesPrior(), ReservationReminderType::Start);
        $command3 = new RemoveReservationReminderCommand($seriesId, ReservationReminderType::End);
        $command4 = new AddReservationReminderCommand($seriesId, $end->MinutesPrior(), ReservationReminderType::End);

        $this->assertTrue($this->db->ContainsCommand($command1));
        $this->assertTrue($this->db->ContainsCommand($command2));
        $this->assertTrue($this->db->ContainsCommand($command3));
        $this->assertTrue($this->db->ContainsCommand($command4));
    }

    public function testRemovesReminders()
    {
        $seriesId = 99;
        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->WithId($seriesId)->Build();

        $series->WithStartReminder(new ReservationReminder(100, ReservationReminderInterval::Days));
        $series->WithEndReminder(new ReservationReminder(15, ReservationReminderInterval::Minutes));

        $series->RemoveStartReminder();
        $series->RemoveEndReminder();

        $this->repository->Update($series);

        $command1 = new RemoveReservationReminderCommand($seriesId, ReservationReminderType::Start);
        $command2 = new RemoveReservationReminderCommand($seriesId, ReservationReminderType::End);

        $this->assertTrue($this->db->ContainsCommand($command1));
        $this->assertTrue($this->db->ContainsCommand($command2));
    }

    private function GetUpdateReservationCommand($expectedSeriesId, Reservation $expectedInstance)
    {
        return new UpdateReservationCommand(
            $expectedInstance->ReferenceNumber(),
            $expectedSeriesId,
            $expectedInstance->StartDate(),
            $expectedInstance->EndDate(),
            $expectedInstance->CheckinDate(),
            $expectedInstance->CheckoutDate(),
            $expectedInstance->PreviousEndDate(),
            $expectedInstance->GetCreditsRequired()
        );
    }

    private function GetAddReservationCommand($expectedSeriesId, Reservation $expectedInstance)
    {
        return new AddReservationCommand(
            $expectedInstance->StartDate(),
            $expectedInstance->EndDate(),
            $expectedInstance->ReferenceNumber(),
            $expectedSeriesId,
            $expectedInstance->GetCreditsRequired()
        );
    }

    private function GetAddUserCommand($reservationId, $userId, $levelId)
    {
        return new AddReservationUserCommand($reservationId, $userId, $levelId);
    }

    private function GetRemoveUserCommand($reservationId, $userId)
    {
        return new RemoveReservationUserCommand($reservationId, $userId);
    }

    private function GetRemoveGuestCommand($reservationId, $emailAddress)
    {
        return new RemoveReservationGuestCommand($reservationId, $emailAddress);
    }

    private function GetAddGuestCommand($reservationId, $emailAddress, $levelId)
    {
        return new AddReservationGuestCommand($reservationId, $emailAddress, $levelId);
    }
}
