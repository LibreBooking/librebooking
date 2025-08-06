<?php

class ReservationViewRepositoryTest extends TestBase
{
    /**
     * @var ReservationViewRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setup();

        $this->repository = new ReservationViewRepository();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testBuildsViewObjectFromDatabase()
    {
        $referenceNumber = "12345";
        $reservationId = 187;
        $resourceId = 12;
        $scheduleId = 73;
        $startDate = new Date('2010-01-01 05:00', 'UTC');
        $endDate = new Date('2010-01-01 12:00', 'UTC');
        $dateCreated = new Date('2010-01-01', 'UTC');
        $ownerId = 92;
        $title = 'ti';
        $description = 'de';
        $repeatType = RepeatType::Yearly;
        $repeatOptions = 'interval=5';
        $seriesId = 1000;
        $ownerFirst = 'f';
        $ownerLast = 'l';
        $statusId = ReservationStatus::Pending;
        $resourceName = 'resource';

        $resourceId1 = 88;
        $resourceName1 = 'r1';
        $adminGroupId1 = 1239;
        $scheduleAdminGroupId = 992323;
        $resourceCheckIn1 = true;
        $resourceAutoRelease1 = 10;

        $resourceId2 = 99;
        $resourceName2 = 'r2';

        $userId1 = 87;
        $fname1 = 'f1';
        $lname1 = 'l1';
        $email1 = 'e1';

        $userId2 = 97;
        $fname2 = 'f2';
        $lname2 = 'l2';
        $email2 = 'e2';

        $userId3 = 98;
        $fname3 = 'f3';
        $lname3 = 'l3';
        $email3 = 'e3';

        $email = 'owner@email.com';
        $phone = '123-123-1234';

        $allowParticipation = 1;
        $checkin = Date::Now()->AddMinutes(1)->ToUtc();
        $checkout = Date::Now()->AddMinutes(2)->ToUtc();
        $previousEnd = Date::Now()->AddMinutes(3)->ToUtc();

        $ownerLevel = ReservationUserLevel::OWNER;
        $participantLevel = ReservationUserLevel::PARTICIPANT;
        $inviteeLevel = ReservationUserLevel::INVITEE;

        $getReservationForEditingCommand = new GetReservationForEditingCommand($referenceNumber);
        $getReservationResources = new GetReservationResourcesCommand($seriesId);
        $getParticipants = new GetReservationParticipantsCommand($reservationId);
        $getAccessories = new GetReservationAccessoriesCommand($seriesId);
        $getAttributes = new GetAttributeValuesCommand($seriesId, CustomAttributeCategory::RESERVATION);
        $getAttachments = new GetReservationAttachmentsCommand($seriesId);
        $getReminders = new GetReservationReminders($seriesId);
        $getGuests = new GetReservationGuestsCommand($reservationId);

        $reservationRow = [
            ColumnNames::RESERVATION_INSTANCE_ID => $reservationId,
            ColumnNames::REFERENCE_NUMBER => $referenceNumber,
            ColumnNames::RESOURCE_ID => $resourceId,
            ColumnNames::SCHEDULE_ID => $scheduleId,
            ColumnNames::RESERVATION_START => $startDate->ToDatabase(),
            ColumnNames::RESERVATION_END => $endDate->ToDatabase(),
            ColumnNames::USER_ID => $ownerId,
            ColumnNames::RESERVATION_TITLE => $title,
            ColumnNames::RESERVATION_DESCRIPTION => $description,
            ColumnNames::REPEAT_TYPE => $repeatType,
            ColumnNames::REPEAT_OPTIONS => $repeatOptions,
            ColumnNames::SERIES_ID => $seriesId,
            ColumnNames::FIRST_NAME => $ownerFirst,
            ColumnNames::LAST_NAME => $ownerLast,
            ColumnNames::RESERVATION_STATUS => $statusId,
            ColumnNames::RESERVATION_CREATED => $dateCreated->ToDatabase(),
            ColumnNames::RESOURCE_NAME => $resourceName,
            ColumnNames::EMAIL => $email,
            ColumnNames::RESERVATION_MODIFIED => Date::Now()->ToDatabase(),
            ColumnNames::RESERVATION_ALLOW_PARTICIPATION => $allowParticipation,
            ColumnNames::CHECKIN_DATE => $checkin->ToDatabase(),
            ColumnNames::CHECKOUT_DATE => $checkout->ToDatabase(),
            ColumnNames::PREVIOUS_END_DATE => $previousEnd->ToDatabase(),
            ColumnNames::CREDIT_COUNT => 20,
            ColumnNames::PHONE_NUMBER => $phone,
            ColumnNames::RESERVATION_TERMS_ACCEPTANCE_DATE => null
        ];

        $resourceRows = [
            $this->GetResourceRow(
                $reservationId,
                $resourceId1,
                $resourceName1,
                $adminGroupId1,
                $scheduleId,
                $scheduleAdminGroupId,
                ResourceStatus::AVAILABLE,
                $resourceCheckIn1,
                $resourceAutoRelease1
            ),
            $this->GetResourceRow(
                $reservationId,
                $resourceId2,
                $resourceName2,
                null,
                $scheduleId,
                $scheduleAdminGroupId
            ),
        ];

        $participantRows = [
            $this->GetParticipantRow($reservationId, $userId1, $fname1, $lname1, $email1, $ownerLevel),
            $this->GetParticipantRow($reservationId, $userId2, $fname2, $lname2, $email2, $participantLevel),
            $this->GetParticipantRow($reservationId, $userId3, $fname3, $lname3, $email3, $inviteeLevel),
        ];

        $guestRows = [
            [ColumnNames::RESERVATION_USER_LEVEL => ReservationUserLevel::INVITEE, ColumnNames::EMAIL => 'i1@email.com'],
            [ColumnNames::RESERVATION_USER_LEVEL => ReservationUserLevel::PARTICIPANT, ColumnNames::EMAIL => 'p1@email.com']
        ];

        $accessory1 = 123;
        $accessory2 = 1232;
        $quantity1 = 123;
        $quantity2 = 1232;
        $accessoryName1 = 'a1';
        $accessoryName2 = 'a2';
        $accessoryQuantity1 = 111;
        $accessoryQuantity2 = 222;

        $attributeId1 = 9292;
        $attributeId2 = 884;
        $attributeValue1 = 'v1';
        $attributeValue2 = 'v1';
        $attributeLabel1 = 'al1';
        $attributeLabel2 = 'al2';

        $startReminderMinutes = 25;
        $endReminderMinutes = 120;

        $accessoryRows = new ReservationAccessoryRow();
        $accessoryRows->WithAccessory($accessory1, $quantity1, $accessoryName1, $accessoryQuantity1)
            ->WithAccessory($accessory2, $quantity2, $accessoryName2, $accessoryQuantity2);

        $attributeRows = new CustomAttributeValueRow();
        $attributeRows->With($attributeId1, $attributeValue1, $attributeLabel1)
            ->With($attributeId2, $attributeValue2, $attributeLabel2);

        $reminderRows = new ReservationReminderRow();
        $reminderRows->With(1, $seriesId, $startReminderMinutes, ReservationReminderType::Start)
            ->With(2, $seriesId, $endReminderMinutes, ReservationReminderType::End);

        $fileId1 = 1;
        $fileName1 = 'fn1';
        $fileId2 = 2;
        $fileName2 = 'fn2';

        $attachmentRows = new ReservationAttachmentItemRow();
        $attachmentRows->With($fileId1, $seriesId, $fileName1)
            ->With($fileId2, $seriesId, $fileName2);

        $this->db->SetRow(0, [$reservationRow]);
        $this->db->SetRow(1, $resourceRows);
        $this->db->SetRow(2, $participantRows);
        $this->db->SetRow(3, $accessoryRows->Rows());
        $this->db->SetRow(4, $attributeRows->Rows());
        $this->db->SetRow(5, $attachmentRows->Rows());
        $this->db->SetRow(6, $reminderRows->Rows());
        $this->db->SetRow(7, $guestRows);

        $reservationView = $this->repository->GetReservationForEditing($referenceNumber);

        $commands = $this->db->_Commands;

        $this->assertEquals(8, count($commands));
        $this->assertEquals($getReservationForEditingCommand, $commands[0]);
        $this->assertEquals($getReservationResources, $commands[1]);
        $this->assertEquals($getParticipants, $commands[2]);
        $this->assertEquals($getAccessories, $commands[3]);
        $this->assertEquals($getAttributes, $commands[4]);
        $this->assertEquals($getAttachments, $commands[5]);
        $this->assertEquals($getReminders, $commands[6]);
        $this->assertEquals($getGuests, $commands[7]);

        $expectedView = new ReservationView();
        $expectedView->AdditionalResourceIds = [$resourceId1, $resourceId2];
        $expectedView->Description = $description;
        $expectedView->EndDate = $endDate;
        $expectedView->OwnerId = $ownerId;
        $expectedView->ReferenceNumber = $referenceNumber;
        $expectedView->ReservationId = $reservationId;
        $expectedView->ResourceId = $resourceId;
        $expectedView->ResourceName = $resourceName;
        $expectedView->DateCreated = $dateCreated;
        $expectedView->ScheduleId = $scheduleId;
        $expectedView->StartDate = $startDate;
        $expectedView->Title = $title;
        $expectedView->RepeatType = $repeatType;
        $expectedView->RepeatInterval = '5';
        $expectedView->SeriesId = $seriesId;
        $expectedView->OwnerFirstName = $ownerFirst;
        $expectedView->OwnerLastName = $ownerLast;
        $expectedView->StatusId = $statusId;
        $expectedView->OwnerEmailAddress = $email;
        $expectedView->OwnerPhone = $phone;
        $expectedView->DateModified = Date::Now()->ToUtc();
        $expectedView->CheckinDate = $checkin;
        $expectedView->CheckoutDate = $checkout;
        $expectedView->OriginalEndDate = $previousEnd;

        $expectedView->Participants = [
            new ReservationUserView($userId2, $fname2, $lname2, $email2, $participantLevel),
        ];

        $expectedView->Invitees = [
            new ReservationUserView($userId3, $fname3, $lname3, $email3, $inviteeLevel),
        ];

        $expectedView->Resources = [
            new ReservationResourceView(
                $resourceId1,
                $resourceName1,
                $adminGroupId1,
                $scheduleId,
                $scheduleAdminGroupId,
                $resourceCheckIn1,
                $resourceAutoRelease1,
                ResourceStatus::AVAILABLE
            ),
            new ReservationResourceView($resourceId2, $resourceName2, null, $scheduleId, $scheduleAdminGroupId, false, null, ResourceStatus::AVAILABLE),
        ];
        foreach ($expectedView->Resources as $r) {
            $r->SetColor('color');
        }

        $expectedView->Accessories = [
            new ReservationAccessoryView($accessory1, $quantity1, $accessoryName1, $accessoryQuantity1),
            new ReservationAccessoryView($accessory2, $quantity2, $accessoryName2, $accessoryQuantity2),
        ];

        $expectedView->AddAttribute(new AttributeValue($attributeId1, $attributeValue1, $attributeLabel1));
        $expectedView->AddAttribute(new AttributeValue($attributeId2, $attributeValue2, $attributeLabel2));

        $expectedView->AddAttachment(new ReservationAttachmentView($fileId1, $seriesId, $fileName1));
        $expectedView->AddAttachment(new ReservationAttachmentView($fileId2, $seriesId, $fileName2));

        $expectedView->StartReminder = new ReservationReminderView($startReminderMinutes);
        $expectedView->EndReminder = new ReservationReminderView($endReminderMinutes);

        $expectedView->AllowParticipation = true;

        $expectedView->ParticipatingGuests = ['p1@email.com'];
        $expectedView->InvitedGuests = ['i1@email.com'];
        $expectedView->CreditsConsumed = 20;
        $expectedView->TermsAcceptanceDate = NullDate::Instance();
        $expectedView->RepeatTerminationDate = NullDate::Instance();

        $this->assertEquals($expectedView, $reservationView);
    }

    public function testIsCheckinEnabled()
    {
        $view = new ReservationView();
        $view->Resources = [new ReservationResourceView(1, 1, 1, 1, 1, true, 10, 1), new ReservationResourceView(1, 1, 1, 1, 1, false, null, 1)];

        $this->assertTrue($view->IsCheckinEnabled());
    }

    public function testIsCheckinAvailableWithin5MinutesOfStart()
    {
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_CHECKIN_MINUTES_PRIOR, 5);

        $view = new ReservationView();
        $view->StartDate = Date::Now()->AddMinutes(4);
        $view->Resources = [new ReservationResourceView(1, 1, 1, 1, 1, true, 10, 1, ), new ReservationResourceView(1, 1, 1, 1, 1, false, null, 1)];

        $this->assertTrue($view->IsCheckinAvailable());
    }

    public function testIsCheckOutAvailableIfNotCheckedOut()
    {
        $view = new ReservationView();
        $view->StartDate = Date::Now()->AddMinutes(-20);
        $view->CheckinDate = Date::Now();
        $view->Resources = [new ReservationResourceView(1, 1, 1, 1, 1, true, 10, 1), new ReservationResourceView(1, 1, 1, 1, 1, false, null, 1)];

        $this->assertTrue($view->IsCheckoutAvailable());
    }

    public function testGetsReservationListForDateRangeAndUser()
    {
        $startDate = Date::Parse('2011-01-01');
        $endDate = Date::Parse('2011-01-01');

        $referenceNumber1 = "ref1";
        $resource1 = "resource1";
        $start1 = Date::Parse('2011-08-09', 'UTC');
        $end1 = Date::Parse('2011-08-10', 'UTC');
        $resourceId = 929;
        $instanceId = 1000;
        $userLevelId = 2;
        $title = 'title';
        $description = 'description';
        $scheduleId = 213;
        $fname = 'fn';
        $lname = 'ln';
        $userId = 111;
        $phone = 'phone';
        $organization = 'organization';
        $position = 'position';
        $participant_list = '100=p 1!sep!200=p 2!sep!500=p 3';
        $invitee_list = '700=i 1!sep!800=1 2';
        $attributes = 'a1=av1,a2=av2';
        $preferences = 'p1=pv1,p2=pv2';
        $bufferTime = 3600;
        $enableCheckIn = true;
        $autoReleaseMin = 20;

        $rows[] = $this->GetReservationListRow(
            $referenceNumber1,
            $resource1,
            $start1,
            $end1,
            $resourceId,
            $instanceId,
            $userLevelId,
            $title,
            $description,
            $scheduleId,
            $fname,
            $lname,
            $userId,
            $phone,
            $organization,
            $position,
            $participant_list,
            $invitee_list,
            $attributes,
            $preferences,
            $bufferTime,
            Date::Now(),
            Date::Now(),
            Date::Now(),
            $enableCheckIn,
            $autoReleaseMin
        );
        $rows[] = $this->GetReservationListRow(
            "2",
            "resource",
            Date::Now(),
            Date::Now(),
            1,
            1,
            1,
            null,
            null,
            1,
            null,
            null,
            null,
            null,
            null,
            null
        );

        $this->db->SetRows($rows);

        $reservationCommand = new GetReservationListCommand($startDate, $endDate, [$userId], ReservationUserLevel::OWNER, [$scheduleId], [$resourceId], null);
        $reservationColorsCommand = new GetReservationColorRulesCommand();
        $reservations = $this->repository->GetReservations($startDate, $endDate, $userId, null, $scheduleId, $resourceId);

        $this->assertEquals($reservationCommand, $this->db->_Commands[0]);
        $this->assertEquals($reservationColorsCommand, $this->db->_Commands[1]);

        $this->assertEquals(count($rows), count($reservations));

        $expectedItem1 = ReservationItemView::Populate($rows[0]);

        $this->assertEquals($expectedItem1, $reservations[0]);
    }

    public function testConsolidatesReservationsByReferenceNumber()
    {
        $refNum = "ref1";

        $rows[] = $this->GetReservationListRow($refNum, "1", Date::Now(), Date::Now(), 1, 1, 1, null, null, 1, null, null, null);
        $rows[] = $this->GetReservationListRow($refNum, "2", Date::Now(), Date::Now(), 2, 1, 1, null, null, 1, null, null, null);

        $this->db->SetRows($rows);

        $reservations = $this->repository->GetReservations(Date::Now(), Date::Now(), -1, -1, -1, -1, true);

        $this->assertEquals(1, count($reservations));

        $expected = ReservationItemView::Populate($rows[0]);
        $expected->ResourceNames = ["1", "2"];
        $this->assertEquals($expected, $reservations[0]);
    }

    public function testReturnsNullObjectIfNothingFound()
    {
        $referenceNumber = "12345";
        $reservationView = $this->repository->GetReservationForEditing($referenceNumber);

        $this->assertEquals(NullReservationView::Instance(), $reservationView);
    }

    public function testReturnsAllAccessoriesReservedWithinDateRange()
    {
        $dateRange = new TestDateRange();

        $refNum = "213213213";
        $start = Date::Now();
        $end = Date::Now();
        $accessoryName = 'accessory';
        $accessoryId = 123;
        $quantity = 898;

        $rows[] = $this->GetAccessoryRow($refNum, $start, $end, $accessoryName, $accessoryId, $quantity);
        $rows[] = $this->GetAccessoryRow("1", Date::Now(), Date::Now(), "", 1, 1);

        $this->db->SetRows($rows);

        $getAccessoriesCommand = new GetAccessoryListCommand($dateRange->GetBegin(), $dateRange->GetEnd());

        $accessories = $this->repository->GetAccessoriesWithin($dateRange);

        $a = new AccessoryReservation($refNum, $start->ToUtc(), $end->ToUtc(), $accessoryId, $quantity);

        $this->assertEquals($getAccessoriesCommand, $this->db->_LastCommand);
        $this->assertEquals(2, count($accessories));
        $this->assertEquals($a, $accessories[0]);
    }

    public function testReturnsAllBlackoutInstancesWithinDateRange()
    {
        $dateRange = new TestDateRange();

        $start = Date::Now();
        $end = Date::Now();
        $instanceId = 12;
        $seriesId = 222;
        $resourceId = 333;
        $userid = 444;
        $scheduleId = 555;
        $resourceName = 'resource 123';
        $firstName = 'f';
        $lastName = 'l';
        $title = 'title';
        $description = 'description';
        $repeatType = RepeatType::Daily;
        $repeat = new RepeatDaily(1, $end->AddDays(2));
        $repeatOptions = $repeat->ConfigurationString();

        $rows[] = $this->GetBlackoutRow(
            $instanceId,
            $start,
            $end,
            $resourceId,
            $userid,
            $scheduleId,
            $title,
            $description,
            $firstName,
            $lastName,
            $resourceName,
            $seriesId,
            $repeatType,
            $repeatOptions
        );
        $rows[] = $this->GetBlackoutRow("1", Date::Now(), Date::Now());

        $this->db->SetRows($rows);

        $getBlackoutsCommand = new GetBlackoutListCommand($dateRange->GetBegin(), $dateRange->GetEnd(), ReservationViewRepository::ALL_SCHEDULES, null);

        $blackouts = $this->repository->GetBlackoutsWithin($dateRange);

        $b = new BlackoutItemView(
            $instanceId,
            $start->ToUtc(),
            $end->ToUtc(),
            $resourceId,
            $userid,
            $scheduleId,
            $title,
            $description,
            $firstName,
            $lastName,
            $resourceName,
            $seriesId,
            $repeatOptions,
            $repeatType
        );

        $this->assertEquals($getBlackoutsCommand, $this->db->_LastCommand);
        $this->assertEquals(2, count($blackouts));
        $this->assertEquals($b, $blackouts[0]);
    }

    public function testKnowsIfParticipatingOrInvited()
    {
        $participant_list = '2=name name!sep!3=name name';
        $invitee_list = '4=name name!sep!5=name name';
        $reservationView = new ReservationItemView(
            'ref',
            Date::Now(),
            Date::Now(),
            'resource',
            1,
            1,
            ReservationUserLevel::OWNER,
            'title',
            'desc',
            1,
            'f',
            'l',
            1,
            null,
            null,
            null,
            $participant_list,
            $invitee_list
        );
        $this->assertTrue($reservationView->IsUserParticipating(2));
        $this->assertTrue($reservationView->IsUserParticipating(3));
        $this->assertFalse($reservationView->IsUserParticipating(4));
        $this->assertFalse($reservationView->IsUserParticipating(5));

        $this->assertFalse($reservationView->IsUserInvited(2));
        $this->assertFalse($reservationView->IsUserInvited(3));
        $this->assertTrue($reservationView->IsUserInvited(4));
        $this->assertTrue($reservationView->IsUserInvited(5));
    }

    public function testGetsCorrectReservationColor()
    {
        $this->fakeConfig->SetKey(ConfigKeys::SCHEDULE_USE_PER_USER_COLORS, true);
        $preferences = UserPreferences::Parse(UserPreferences::RESERVATION_COLOR . '=000000');
        $reservationItemView = new ReservationItemView();
        $reservationItemView->ResourceColor = 'ffffff';

        $this->assertEquals('#ffffff', $reservationItemView->GetColor(), 'uses reservation color if no user color defined');

        $reservationItemView = new ReservationItemView();
        $reservationItemView->UserPreferences = $preferences;

        $this->assertEquals('#000000', $reservationItemView->GetColor(), 'uses user color if defined');
    }

    private function GetParticipantRow($reservationId, $userId, $fname, $lname, $email, $levelId)
    {
        return [
            ColumnNames::RESERVATION_INSTANCE_ID => $reservationId,
            ColumnNames::USER_ID => $userId,
            ColumnNames::FIRST_NAME => $fname,
            ColumnNames::LAST_NAME => $lname,
            ColumnNames::EMAIL => $email,
            ColumnNames::RESERVATION_USER_LEVEL => $levelId,
        ];
    }

    private function GetResourceRow(
        $reservationId,
        $resourceId,
        $resourceName,
        $adminGroupId,
        $scheduleId,
        $scheduleAdminGroupId,
        $statusId = ResourceStatus::AVAILABLE,
        $checkinEnabled = false,
        $autoReleaseMinutes = null
    ) {
        return [
            ColumnNames::RESERVATION_INSTANCE_ID => $reservationId,
            ColumnNames::RESOURCE_ID => $resourceId,
            ColumnNames::RESOURCE_NAME => $resourceName,
            ColumnNames::RESOURCE_LEVEL_ID => ResourceLevel::Additional,
            ColumnNames::RESOURCE_ADMIN_GROUP_ID => $adminGroupId,
            ColumnNames::SCHEDULE_ID => $scheduleId,
            ColumnNames::SCHEDULE_ADMIN_GROUP_ID_ALIAS => $scheduleAdminGroupId,
            ColumnNames::RESOURCE_STATUS_ID => $statusId,
            ColumnNames::ENABLE_CHECK_IN => $checkinEnabled,
            ColumnNames::AUTO_RELEASE_MINUTES => $autoReleaseMinutes,
        ];
    }

    private function GetReservationListRow(
        $referenceNumber,
        $resourceName,
        Date $startDate,
        Date $endDate,
        $resourceId,
        $instanceId,
        $userLevelId,
        $title,
        $description,
        $scheduleId,
        $fname,
        $lname,
        $userId,
        $phone = 'phone',
        $organization = 'organization',
        $position = 'position',
        $participant_list = '',
        $invitee_list = null,
        $attributes = null,
        $preferences = null,
        $bufferTime = 0,
        $checkinDate = null,
        $checkoutDate = null,
        $previousEndDate = null,
        $requireCheckin = false,
        $autoReleaseMinutes = null
    ) {
        return [
            ColumnNames::REFERENCE_NUMBER => $referenceNumber,
            ColumnNames::RESOURCE_NAME => $resourceName,
            ColumnNames::RESERVATION_START => $startDate->ToDatabase(),
            ColumnNames::RESERVATION_END => $endDate->ToDatabase(),
            ColumnNames::RESOURCE_ID => $resourceId,
            ColumnNames::RESERVATION_INSTANCE_ID => $instanceId,
            ColumnNames::RESERVATION_USER_LEVEL => $userLevelId,
            ColumnNames::RESERVATION_TITLE => $title,
            ColumnNames::RESERVATION_DESCRIPTION => $description,
            ColumnNames::SCHEDULE_ID => $scheduleId,
            ColumnNames::FIRST_NAME => $fname,
            ColumnNames::LAST_NAME => $lname,
            ColumnNames::USER_ID => $userId,
            ColumnNames::OWNER_FIRST_NAME => $fname,
            ColumnNames::OWNER_LAST_NAME => $lname,
            ColumnNames::OWNER_USER_ID => $userId,
            ColumnNames::OWNER_PHONE => $phone,
            ColumnNames::OWNER_ORGANIZATION => $organization,
            ColumnNames::OWNER_POSITION => $position,
            ColumnNames::PARTICIPANT_LIST => $participant_list,
            ColumnNames::INVITEE_LIST => $invitee_list,
            ColumnNames::ATTRIBUTE_LIST => $attributes,
            ColumnNames::USER_PREFERENCES => $preferences,
            ColumnNames::RESOURCE_BUFFER_TIME => $bufferTime,
            ColumnNames::CHECKIN_DATE => $checkinDate == null ? null : $checkinDate->ToDatabase(),
            ColumnNames::CHECKOUT_DATE => $checkoutDate == null ? null : $checkoutDate->ToDatabase(),
            ColumnNames::PREVIOUS_END_DATE => $previousEndDate == null ? null : $previousEndDate->ToDatabase(),
            ColumnNames::ENABLE_CHECK_IN => $requireCheckin,
            ColumnNames::AUTO_RELEASE_MINUTES => $autoReleaseMinutes,
            ColumnNames::CREDIT_COUNT => null,
            ColumnNames::RESOURCE_ADMIN_GROUP_ID_RESERVATIONS => null,
            ColumnNames::SCHEDULE_ADMIN_GROUP_ID_RESERVATIONS => null,
        ];
    }

    private function GetAccessoryRow(
        $referenceNumber,
        Date $startDate,
        Date $endDate,
        $accessoryName,
        $accessoryId,
        $quantityReserved
    ) {
        return [
            ColumnNames::REFERENCE_NUMBER => $referenceNumber,
            ColumnNames::RESERVATION_START => $startDate->ToDatabase(),
            ColumnNames::RESERVATION_END => $endDate->ToDatabase(),
            ColumnNames::ACCESSORY_NAME => $accessoryName,
            ColumnNames::ACCESSORY_ID => $accessoryId,
            ColumnNames::QUANTITY => $quantityReserved,
        ];
    }

    private function GetBlackoutRow(
        $instanceId,
        Date $start,
        Date $end,
        $resourceId = 1,
        $userid = 2,
        $scheduleId = 3,
        $title = 'title',
        $description = 'description',
        $firstName = 'fname',
        $lastName = 'lname',
        $resourceName = 'resource name',
        $seriesId = 999,
        $repeatType = RepeatType::None,
        $repeatOptions = ''
    ) {
        return [
            ColumnNames::BLACKOUT_INSTANCE_ID => $instanceId,
            ColumnNames::BLACKOUT_START => $start->ToDatabase(),
            ColumnNames::BLACKOUT_END => $end->ToDatabase(),
            ColumnNames::RESOURCE_ID => $resourceId,
            ColumnNames::USER_ID => $userid,
            ColumnNames::SCHEDULE_ID => $scheduleId,
            ColumnNames::BLACKOUT_TITLE => $title,
            ColumnNames::BLACKOUT_DESCRIPTION => $description,
            ColumnNames::FIRST_NAME => $firstName,
            ColumnNames::LAST_NAME => $lastName,
            ColumnNames::RESOURCE_NAME => $resourceName,
            ColumnNames::BLACKOUT_SERIES_ID => $seriesId,
            ColumnNames::REPEAT_TYPE => $repeatType,
            ColumnNames::REPEAT_OPTIONS => $repeatOptions,
        ];
    }
}
