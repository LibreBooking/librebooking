<?php

require_once(ROOT_DIR . 'Domain/Values/ReservationUserLevel.php');
require_once(ROOT_DIR . 'Domain/Values/ReservationStatus.php');
require_once(ROOT_DIR . 'Domain/Values/CustomAttributes.php');
require_once(ROOT_DIR . 'Domain/Values/UserPreferences.php');
require_once(ROOT_DIR . 'Domain/RepeatOptions.php');
require_once(ROOT_DIR . 'Domain/ReservationReminderView.php');
require_once(ROOT_DIR . 'Domain/ReservationResourceView.php');
require_once(ROOT_DIR . 'Domain/ReservationUserView.php');
require_once(ROOT_DIR . 'Domain/ReservationView.php');
require_once(ROOT_DIR . 'Domain/ReservationAttachmentView.php');
require_once(ROOT_DIR . 'Domain/AccessoryReservation.php');
require_once(ROOT_DIR . 'Domain/ReservationItemView.php');
require_once(ROOT_DIR . 'Domain/ReservationAccessoryView.php');

interface IReservationViewRepository
{
    /**
     * @param string $referenceNumber
     * @return ReservationView
     */
    public function GetReservationForEditing($referenceNumber);

    /**
     * @param Date $startDate
     * @param Date $endDate
     * @param int|null|int[] $userIds
     * @param int|ReservationUserLevel|null $userLevel
     * @param int|int[]|null $scheduleIds
     * @param int|int[]|null $resourceIds
     * @param int|int[]|null $participantIds
     * @param bool $consolidateByReferenceNumber
     * @return ReservationItemView[]
     */
    public function GetReservations(
        Date $startDate,
        Date $endDate,
        $userIds = ReservationViewRepository::ALL_USERS,
        $userLevel = ReservationUserLevel::OWNER,
        $scheduleIds = ReservationViewRepository::ALL_SCHEDULES,
        $resourceIds = ReservationViewRepository::ALL_RESOURCES,
        $consolidateByReferenceNumber = false,
        $participantIds = ReservationViewRepository::ALL_USERS
    );

    /**
     * @param Date $startDate
     * @param int|null|int[] $userIds
     * @param int|ReservationUserLevel|null $userLevel
     * @param int|int[]|null $scheduleIds
     * @param int|int[]|null $resourceIds
     * @param int|int[]|null $participantIds
     * @param bool $consolidateByReferenceNumber
     * @return ReservationItemView[]
     */
    public function GetReservationsPendingApproval(
        Date $startDate,
        $userIds = ReservationViewRepository::ALL_USERS,
        $userLevel = ReservationUserLevel::OWNER,
        $scheduleIds = ReservationViewRepository::ALL_SCHEDULES,
        $resourceIds = ReservationViewRepository::ALL_RESOURCES,
        $consolidateByReferenceNumber = false,
        $participantIds = ReservationViewRepository::ALL_USERS
    );

    /**
     * @param Date|null $startDate
     * @param Date $endDate
     * @param int|null|int[] $userIds
     * @param int|ReservationUserLevel|null $userLevel
     * @param int|int[]|null $scheduleIds
     * @param int|int[]|null $resourceIds
     * @param int|int[]|null $participantIds
     * @param bool $consolidateByReferenceNumber
     * @return ReservationItemView[]
     */
    public function GetReservationsMissingCheckInCheckOut(
        ?Date $startDate,
        Date $endDate,
        $userIds = ReservationViewRepository::ALL_USERS,
        $userLevel = ReservationUserLevel::OWNER,
        $scheduleIds = ReservationViewRepository::ALL_SCHEDULES,
        $resourceIds = ReservationViewRepository::ALL_RESOURCES,
        $consolidateByReferenceNumber = false,
        $participantIds = ReservationViewRepository::ALL_USERS
    );

    /**
     * @param Date $startDate
     * @param Date $endDate
     * @param string $accessoryName
     * @return ReservationItemView[]
     */
    public function GetAccessoryReservationList(Date $startDate, Date $endDate, $accessoryName);

    /**
     * @param int $pageNumber
     * @param int $pageSize
     * @param string $sortField
     * @param string $sortDirection
     * @param ISqlFilter $filter
     * @return PageableData|ReservationItemView[]
     */
    public function GetList($pageNumber, $pageSize, $sortField = null, $sortDirection = null, $filter = null);

    /**
     * @param DateRange $dateRange
     * @param int|null $scheduleId
     * @param int|int[]|null $resourceIds
     * @return BlackoutItemView[]
     */
    public function GetBlackoutsWithin(DateRange $dateRange, $scheduleId = ReservationViewRepository::ALL_SCHEDULES, $resourceIds = ReservationViewRepository::ALL_RESOURCES);

    /**
     * @param int $pageNumber
     * @param int $pageSize
     * @param null|string $sortField
     * @param null|string $sortDirection
     * @param null|ISqlFilter $filter
     * @return PageableData|BlackoutItemView[]
     */
    public function GetBlackoutList($pageNumber, $pageSize, $sortField = null, $sortDirection = null, $filter = null);

    /**
     * @param DateRange $dateRange
     * @return array|AccessoryReservation[]
     */
    public function GetAccessoriesWithin(DateRange $dateRange);
}

class ReservationViewRepository implements IReservationViewRepository
{
    public const ALL_SCHEDULES = -1;
    public const ALL_RESOURCES = -1;
    public const ALL_USERS = -1;
    public const ALL_ACCESSORIES = -1;

    public function GetReservationForEditing($referenceNumber)
    {
        $reservationView = NullReservationView::Instance();

        $getReservation = new GetReservationForEditingCommand($referenceNumber);

        $reader = ServiceLocator::GetDatabase()->Query($getReservation);

        while ($row = $reader->GetRow()) {
            $reservationView = new ReservationView();

            $reservationView->Description = $row[ColumnNames::RESERVATION_DESCRIPTION];
            $reservationView->EndDate = Date::FromDatabase($row[ColumnNames::RESERVATION_END]);
            $reservationView->OwnerId = $row[ColumnNames::USER_ID];
            $reservationView->ResourceId = $row[ColumnNames::RESOURCE_ID];
            $reservationView->ResourceName = $row[ColumnNames::RESOURCE_NAME];
            $reservationView->ReferenceNumber = $row[ColumnNames::REFERENCE_NUMBER];
            $reservationView->ReservationId = $row[ColumnNames::RESERVATION_INSTANCE_ID];
            $reservationView->ScheduleId = $row[ColumnNames::SCHEDULE_ID];
            $reservationView->StartDate = Date::FromDatabase($row[ColumnNames::RESERVATION_START]);
            $reservationView->Title = $row[ColumnNames::RESERVATION_TITLE];
            $reservationView->SeriesId = $row[ColumnNames::SERIES_ID];
            $reservationView->OwnerFirstName = $row[ColumnNames::FIRST_NAME];
            $reservationView->OwnerLastName = $row[ColumnNames::LAST_NAME];
            $reservationView->OwnerEmailAddress = $row[ColumnNames::EMAIL];
            $reservationView->OwnerPhone = $row[ColumnNames::PHONE_NUMBER];
            $reservationView->StatusId = $row[ColumnNames::RESERVATION_STATUS];
            $reservationView->DateCreated = Date::FromDatabase($row[ColumnNames::RESERVATION_CREATED]);
            $reservationView->DateModified = Date::FromDatabase($row[ColumnNames::RESERVATION_MODIFIED]);

            $repeatConfig = RepeatConfiguration::Create($row[ColumnNames::REPEAT_TYPE], $row[ColumnNames::REPEAT_OPTIONS]);

            $reservationView->RepeatType = $repeatConfig->Type;
            $reservationView->RepeatInterval = $repeatConfig->Interval;
            $reservationView->RepeatWeekdays = $repeatConfig->Weekdays;
            $reservationView->RepeatMonthlyType = $repeatConfig->MonthlyType;
            $reservationView->RepeatTerminationDate = $repeatConfig->TerminationDate;
            $reservationView->AllowParticipation = (bool)$row[ColumnNames::RESERVATION_ALLOW_PARTICIPATION];
            $reservationView->CheckinDate = Date::FromDatabase($row[ColumnNames::CHECKIN_DATE]);
            $reservationView->CheckoutDate = Date::FromDatabase($row[ColumnNames::CHECKOUT_DATE]);
            $reservationView->OriginalEndDate = Date::FromDatabase($row[ColumnNames::PREVIOUS_END_DATE]);
            $reservationView->CreditsConsumed = $row[ColumnNames::CREDIT_COUNT];
            $reservationView->TermsAcceptanceDate = Date::FromDatabase($row[ColumnNames::RESERVATION_TERMS_ACCEPTANCE_DATE]);
            $reservationView->HasAcceptedTerms = $reservationView->TermsAcceptanceDate->ToString() != '';

            $this->SetResources($reservationView);
            $this->SetParticipants($reservationView);
            $this->SetAccessories($reservationView);
            $this->SetAttributes($reservationView);
            $this->SetAttachments($reservationView);
            $this->SetReminders($reservationView);
            $this->SetGuests($reservationView);
            $this->SetCustomRepeatDates($reservationView);
        }

        $reader->Free();
        return $reservationView;
    }

    public function GetReservations(
        Date $startDate,
        Date $endDate,
        $userIds = self::ALL_USERS,
        $userLevel = ReservationUserLevel::OWNER,
        $scheduleIds = self::ALL_SCHEDULES,
        $resourceIds = self::ALL_RESOURCES,
        $consolidateByReferenceNumber = false,
        $participantIds = self::ALL_USERS
    ) {
        if (empty($userIds)) {
            $userIds = self::ALL_USERS;
        }
        if (is_null($userLevel)) {
            $userLevel = ReservationUserLevel::OWNER;
        }
        if (empty($scheduleIds)) {
            $scheduleIds = self::ALL_SCHEDULES;
        }
        if (empty($resourceIds)) {
            $resourceIds = self::ALL_RESOURCES;
        }
        if (empty($participantIds)) {
            $participantIds = self::ALL_USERS;
        }
        if ($resourceIds == self::ALL_RESOURCES) {
            $resourceIds = null;
        }
        if ($scheduleIds == self::ALL_SCHEDULES) {
            $scheduleIds = null;
        }
        if ($userIds == self::ALL_USERS) {
            $userIds = null;
        }
        if ($participantIds == self::ALL_USERS) {
            $participantIds = null;
        }

        if (!empty($resourceIds) && $resourceIds != ReservationViewRepository::ALL_RESOURCES && !is_array($resourceIds)) {
            $resourceIds = [$resourceIds];
        }
        if (!empty($scheduleIds) && $scheduleIds != ReservationViewRepository::ALL_SCHEDULES && !is_array($scheduleIds)) {
            $scheduleIds = [$scheduleIds];
        }
        if (!empty($userIds) && $userIds != ReservationViewRepository::ALL_USERS && !is_array($userIds)) {
            $userIds = [$userIds];
        }
        if (!empty($participantIds) && $participantIds != ReservationViewRepository::ALL_USERS && !is_array($participantIds)) {
            $participantIds = [$participantIds];
        }

        $getReservations = new GetReservationListCommand($startDate, $endDate, $userIds, $userLevel, $scheduleIds, $resourceIds, $participantIds);

        $reader = ServiceLocator::GetDatabase()->Query($getReservations);

        $reservations = [];

        $reservationRepository = new ReservationRepository();
        $rules = $reservationRepository->GetReservationColorRules();

        while ($row = $reader->GetRow()) {
            if ($consolidateByReferenceNumber) {
                $refNum = $row[ColumnNames::REFERENCE_NUMBER];

                if (array_key_exists($refNum, $reservations)) {
                    $reservations[$refNum]->ResourceNames[] = $row[ColumnNames::RESOURCE_NAME];
                } else {
                    $reservation = ReservationItemView::Populate($row);
                    $reservation->WithColorRules($rules);
                    $reservations[$refNum] = $reservation;
                }
            } else {
                $reservation = ReservationItemView::Populate($row);
                $reservation->WithColorRules($rules);
                $reservations[] = $reservation;
            }
        }

        $reader->Free();

        if ($consolidateByReferenceNumber) {
            return array_values($reservations);
        }
        return $reservations;
    }

    public function GetReservationsPendingApproval(
        Date $startDate,
        $userIds = self::ALL_USERS,
        $userLevel = ReservationUserLevel::OWNER,
        $scheduleIds = self::ALL_SCHEDULES,
        $resourceIds = self::ALL_RESOURCES,
        $consolidateByReferenceNumber = false,
        $participantIds = self::ALL_USERS
    ) {
        if (empty($userIds)) {
            $userIds = self::ALL_USERS;
        }
        if (is_null($userLevel)) {
            $userLevel = ReservationUserLevel::OWNER;
        }
        if (empty($scheduleIds)) {
            $scheduleIds = self::ALL_SCHEDULES;
        }
        if (empty($resourceIds)) {
            $resourceIds = self::ALL_RESOURCES;
        }
        if (empty($participantIds)) {
            $participantIds = self::ALL_USERS;
        }
        if ($resourceIds == self::ALL_RESOURCES) {
            $resourceIds = null;
        }
        if ($scheduleIds == self::ALL_SCHEDULES) {
            $scheduleIds = null;
        }
        if ($userIds == self::ALL_USERS) {
            $userIds = null;
        }
        if ($participantIds == self::ALL_USERS) {
            $participantIds = null;
        }

        if (!empty($resourceIds) && $resourceIds != ReservationViewRepository::ALL_RESOURCES && !is_array($resourceIds)) {
            $resourceIds = [$resourceIds];
        }
        if (!empty($scheduleIds) && $scheduleIds != ReservationViewRepository::ALL_SCHEDULES && !is_array($scheduleIds)) {
            $scheduleIds = [$scheduleIds];
        }
        if (!empty($userIds) && $userIds != ReservationViewRepository::ALL_USERS && !is_array($userIds)) {
            $userIds = [$userIds];
        }
        if (!empty($participantIds) && $participantIds != ReservationViewRepository::ALL_USERS && !is_array($participantIds)) {
            $participantIds = [$participantIds];
        }

        $getReservations = new GetReservationsPendingApprovalCommand($startDate, $userIds, $userLevel, $scheduleIds, $resourceIds, $participantIds);

        $reader = ServiceLocator::GetDatabase()->Query($getReservations);

        $reservations = [];

        $reservationRepository = new ReservationRepository();
        $rules = $reservationRepository->GetReservationColorRules();

        while ($row = $reader->GetRow()) {
            if ($consolidateByReferenceNumber) {
                $refNum = $row[ColumnNames::REFERENCE_NUMBER];

                if (array_key_exists($refNum, $reservations)) {
                    $reservations[$refNum]->ResourceNames[] = $row[ColumnNames::RESOURCE_NAME];
                } else {
                    $reservation = ReservationItemView::Populate($row);
                    $reservation->WithColorRules($rules);
                    $reservations[$refNum] = $reservation;
                }
            } else {
                $reservation = ReservationItemView::Populate($row);
                $reservation->WithColorRules($rules);
                $reservations[] = $reservation;
            }
        }

        $reader->Free();

        if ($consolidateByReferenceNumber) {
            return array_values($reservations);
        }
        return $reservations;
    }

    public function GetReservationsMissingCheckInCheckOut(
        ?Date $startDate,
        Date $endDate,
        $userIds = self::ALL_USERS,
        $userLevel = ReservationUserLevel::OWNER,
        $scheduleIds = self::ALL_SCHEDULES,
        $resourceIds = self::ALL_RESOURCES,
        $consolidateByReferenceNumber = false,
        $participantIds = self::ALL_USERS
    ) {
        if (empty($userIds)) {
            $userIds = self::ALL_USERS;
        }
        if (is_null($userLevel)) {
            $userLevel = ReservationUserLevel::OWNER;
        }
        if (empty($scheduleIds)) {
            $scheduleIds = self::ALL_SCHEDULES;
        }
        if (empty($resourceIds)) {
            $resourceIds = self::ALL_RESOURCES;
        }
        if (empty($participantIds)) {
            $participantIds = self::ALL_USERS;
        }
        if ($resourceIds == self::ALL_RESOURCES) {
            $resourceIds = null;
        }
        if ($scheduleIds == self::ALL_SCHEDULES) {
            $scheduleIds = null;
        }
        if ($userIds == self::ALL_USERS) {
            $userIds = null;
        }
        if ($participantIds == self::ALL_USERS) {
            $participantIds = null;
        }

        if (!empty($resourceIds) && $resourceIds != ReservationViewRepository::ALL_RESOURCES && !is_array($resourceIds)) {
            $resourceIds = [$resourceIds];
        }
        if (!empty($scheduleIds) && $scheduleIds != ReservationViewRepository::ALL_SCHEDULES && !is_array($scheduleIds)) {
            $scheduleIds = [$scheduleIds];
        }
        if (!empty($userIds) && $userIds != ReservationViewRepository::ALL_USERS && !is_array($userIds)) {
            $userIds = [$userIds];
        }
        if (!empty($participantIds) && $participantIds != ReservationViewRepository::ALL_USERS && !is_array($participantIds)) {
            $participantIds = [$participantIds];
        }

        $getReservations = new GetReservationsMissingCheckInCheckOutCommand($startDate, $endDate, $userIds, $userLevel, $scheduleIds, $resourceIds, $participantIds);

        $reader = ServiceLocator::GetDatabase()->Query($getReservations);

        $reservations = [];

        $reservationRepository = new ReservationRepository();
        $rules = $reservationRepository->GetReservationColorRules();

        while ($row = $reader->GetRow()) {
            if ($consolidateByReferenceNumber) {
                $refNum = $row[ColumnNames::REFERENCE_NUMBER];

                if (array_key_exists($refNum, $reservations)) {
                    $reservations[$refNum]->ResourceNames[] = $row[ColumnNames::RESOURCE_NAME];
                } else {
                    $reservation = ReservationItemView::Populate($row);
                    $reservation->WithColorRules($rules);
                    $reservations[$refNum] = $reservation;
                }
            } else {
                $reservation = ReservationItemView::Populate($row);
                $reservation->WithColorRules($rules);
                $reservations[] = $reservation;
            }
        }

        $reader->Free();

        if ($consolidateByReferenceNumber) {
            return array_values($reservations);
        }
        return $reservations;
    }

    public function GetAccessoryReservationList(Date $startDate, Date $endDate, $accessoryName)
    {
        $getReservations = new GetReservationsByAccessoryNameCommand($startDate, $endDate, $accessoryName);

        $result = ServiceLocator::GetDatabase()->Query($getReservations);

        $reservations = [];

        while ($row = $result->GetRow()) {
            $reservations[] = ReservationItemView::Populate($row);
        }

        $result->Free();

        return $reservations;
    }

    public function GetList($pageNumber, $pageSize, $sortField = null, $sortDirection = null, $filter = null)
    {
        $command = new GetFullReservationListCommand();

        if ($filter != null) {
            $command = new FilterCommand($command, $filter);
        }

        $builder = ['ReservationItemView', 'Populate'];
        return PageableDataStore::GetList($command, $builder, $pageNumber, $pageSize, $sortField, $sortDirection);
    }

    private function SetResources(ReservationView $reservationView)
    {
        $getResources = new GetReservationResourcesCommand($reservationView->SeriesId);

        $reader = ServiceLocator::GetDatabase()->Query($getResources);

        while ($row = $reader->GetRow()) {
            if ($row[ColumnNames::RESOURCE_LEVEL_ID] == ResourceLevel::Additional) {
                $reservationView->AdditionalResourceIds[] = $row[ColumnNames::RESOURCE_ID];
            }
            $rrv = new ReservationResourceView(
                $row[ColumnNames::RESOURCE_ID],
                $row[ColumnNames::RESOURCE_NAME],
                $row[ColumnNames::RESOURCE_ADMIN_GROUP_ID],
                $row[ColumnNames::SCHEDULE_ID],
                $row[ColumnNames::SCHEDULE_ADMIN_GROUP_ID_ALIAS],
                $row[ColumnNames::ENABLE_CHECK_IN],
                $row[ColumnNames::AUTO_RELEASE_MINUTES],
                $row[ColumnNames::RESOURCE_STATUS_ID]
            );
            $rrv->SetColor(ColumnNames::RESERVATION_COLOR);

            $reservationView->Resources[] = $rrv;
        }

        $reader->Free();
    }

    private function SetParticipants(ReservationView $reservationView)
    {
        $getParticipants = new GetReservationParticipantsCommand($reservationView->ReservationId);

        $reader = ServiceLocator::GetDatabase()->Query($getParticipants);

        while ($row = $reader->GetRow()) {
            $levelId = $row[ColumnNames::RESERVATION_USER_LEVEL];
            $reservationUserView = new ReservationUserView(
                $row[ColumnNames::USER_ID],
                $row[ColumnNames::FIRST_NAME],
                $row[ColumnNames::LAST_NAME],
                $row[ColumnNames::EMAIL],
                $levelId
            );

            if ($levelId == ReservationUserLevel::PARTICIPANT) {
                $reservationView->Participants[] = $reservationUserView;
            }

            if ($levelId == ReservationUserLevel::INVITEE) {
                $reservationView->Invitees[] = $reservationUserView;
            }
        }

        $reader->Free();
    }

    private function SetAccessories(ReservationView $reservationView)
    {
        $getAccessories = new GetReservationAccessoriesCommand($reservationView->SeriesId);

        $reader = ServiceLocator::GetDatabase()->Query($getAccessories);

        while ($row = $reader->GetRow()) {
            $reservationView->Accessories[] = new ReservationAccessoryView(
                $row[ColumnNames::ACCESSORY_ID],
                $row[ColumnNames::QUANTITY],
                $row[ColumnNames::ACCESSORY_NAME],
                $row[ColumnNames::ACCESSORY_QUANTITY]
            );
        }

        $reader->Free();
    }

    private function SetAttributes(ReservationView $reservationView)
    {
        $getAttributes = new GetAttributeValuesCommand(
            $reservationView->SeriesId,
            CustomAttributeCategory::RESERVATION
        );

        $reader = ServiceLocator::GetDatabase()->Query($getAttributes);

        while ($row = $reader->GetRow()) {
            $reservationView->AddAttribute(new AttributeValue(
                $row[ColumnNames::ATTRIBUTE_ID],
                $row[ColumnNames::ATTRIBUTE_VALUE],
                $row[ColumnNames::ATTRIBUTE_LABEL]
            ));
        }

        $reader->Free();
    }

    private function SetAttachments(ReservationView $reservationView)
    {
        $getAttachments = new GetReservationAttachmentsCommand($reservationView->SeriesId);

        $reader = ServiceLocator::GetDatabase()->Query($getAttachments);

        while ($row = $reader->GetRow()) {
            $reservationView->AddAttachment(new ReservationAttachmentView(
                $row[ColumnNames::FILE_ID],
                $row[ColumnNames::SERIES_ID],
                $row[ColumnNames::FILE_NAME]
            ));
        }

        $reader->Free();
    }

    private function SetReminders(ReservationView $reservationView)
    {
        $getReminders = new GetReservationReminders($reservationView->SeriesId);
        $reader = ServiceLocator::GetDatabase()->Query($getReminders);
        while ($row = $reader->GetRow()) {
            if ($row[ColumnNames::REMINDER_TYPE] == ReservationReminderType::Start) {
                $reservationView->StartReminder = new ReservationReminderView($row[ColumnNames::REMINDER_MINUTES_PRIOR]);
            } else {
                $reservationView->EndReminder = new ReservationReminderView($row[ColumnNames::REMINDER_MINUTES_PRIOR]);
            }
        }

        $reader->Free();
    }

    private function SetGuests(ReservationView $reservationView)
    {
        $getGuests = new GetReservationGuestsCommand($reservationView->ReservationId);

        $reader = ServiceLocator::GetDatabase()->Query($getGuests);

        while ($row = $reader->GetRow()) {
            $levelId = $row[ColumnNames::RESERVATION_USER_LEVEL];
            $email = $row[ColumnNames::EMAIL];

            if ($levelId == ReservationUserLevel::PARTICIPANT) {
                $reservationView->ParticipatingGuests[] = $email;
            }

            if ($levelId == ReservationUserLevel::INVITEE) {
                $reservationView->InvitedGuests[] = $email;
            }
        }

        $reader->Free();
    }

    private function SetCustomRepeatDates(ReservationView $reservationView)
    {
        if ($reservationView->RepeatType == RepeatType::Custom) {
            $getRepeatDates = new GetReservationRepeatDatesCommand($reservationView->SeriesId);
            $reader = ServiceLocator::GetDatabase()->Query($getRepeatDates);
            while ($row = $reader->GetRow()) {
                $reservationView->CustomRepeatDates[] = Date::FromDatabase($row[ColumnNames::RESERVATION_START]);
            }
        }
    }

    public function GetAccessoriesWithin(DateRange $dateRange)
    {
        $getAccessoriesCommand = new GetAccessoryListCommand($dateRange->GetBegin(), $dateRange->GetEnd());

        $reader = ServiceLocator::GetDatabase()->Query($getAccessoriesCommand);

        $accessories = [];
        while ($row = $reader->GetRow()) {
            $accessories[] = new AccessoryReservation(
                $row[ColumnNames::REFERENCE_NUMBER],
                Date::FromDatabase($row[ColumnNames::RESERVATION_START]),
                Date::FromDatabase($row[ColumnNames::RESERVATION_END]),
                $row[ColumnNames::ACCESSORY_ID],
                $row[ColumnNames::QUANTITY]
            );
        }

        $reader->Free();

        return $accessories;
    }

    public function GetBlackoutsWithin(DateRange $dateRange, $scheduleId = ReservationViewRepository::ALL_SCHEDULES, $resourceIds = ReservationViewRepository::ALL_RESOURCES)
    {
        if (empty($scheduleId)) {
            $scheduleId = ReservationViewRepository::ALL_SCHEDULES;
        }

        if (empty($resourceIds)) {
            $resourceIds = self::ALL_RESOURCES;
        }
        if ($resourceIds == self::ALL_RESOURCES) {
            $resourceIds = null;
        }
        if (!empty($resourceIds) && $resourceIds != ReservationViewRepository::ALL_RESOURCES && !is_array($resourceIds)) {
            $resourceIds = [$resourceIds];
        }

        $getBlackoutsCommand = new GetBlackoutListCommand($dateRange->GetBegin(), $dateRange->GetEnd(), $scheduleId, $resourceIds);

        $reader = ServiceLocator::GetDatabase()->Query($getBlackoutsCommand);

        $blackouts = [];
        while ($row = $reader->GetRow()) {
            $blackouts[] = BlackoutItemView::Populate($row);
        }

        $reader->Free();

        return $blackouts;
    }

    public function GetBlackoutList($pageNumber, $pageSize, $sortField = null, $sortDirection = null, $filter = null)
    {
        $command = new GetBlackoutListFullCommand();

        if ($filter != null) {
            $command = new FilterCommand($command, $filter);
        }

        $builder = ['BlackoutItemView', 'Populate'];
        return PageableDataStore::GetList($command, $builder, $pageNumber, $pageSize, $sortField, $sortDirection);
    }
}
