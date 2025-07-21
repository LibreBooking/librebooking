<?php

require_once(ROOT_DIR . 'Domain/namespace.php');

class ReservationRepository implements IReservationRepository
{
    public function LoadById($reservationId)
    {
        Log::Debug("ReservationRepository::LoadById() - ReservationID: $reservationId");

        return $this->Load(new GetReservationByIdCommand($reservationId));
    }

    public function LoadByReferenceNumber($referenceNumber)
    {
        Log::Debug("ReservationRepository::LoadByReferenceNumber() - ReferenceNumber: $referenceNumber");

        return $this->Load(new GetReservationByReferenceNumberCommand($referenceNumber));
    }

    private function Load(SqlCommand $loadSeriesCommand)
    {
        $reader = ServiceLocator::GetDatabase()->Query($loadSeriesCommand);

        if ($reader->NumRows() != 1) {
            Log::Debug('Reservation not found');
            return null;
        }

        $series = $this->BuildSeries($reader);
        $this->PopulateInstances($series);
        $this->PopulateResources($series);
        $this->PopulateParticipants($series);
        $this->PopulateAccessories($series);
        $this->PopulateAttributeValues($series);
        $this->PopulateAttachmentIds($series);
        $this->PopulateReminders($series);
        $this->PopulateGuests($series);

        return $series;
    }

    public function Add(ReservationSeries $reservationSeries)
    {
        $database = ServiceLocator::GetDatabase();

        $seriesId = $this->InsertSeries($reservationSeries);

        $reservationSeries->SetSeriesId($seriesId);

        $instances = $reservationSeries->Instances();

        foreach ($instances as $reservation) {
            $command = new InstanceAddedEventCommand($reservation, $reservationSeries);
            $command->Execute($database);
        }
    }

    public function Update(ExistingReservationSeries $reservationSeries)
    {
        $database = ServiceLocator::GetDatabase();

        if ($reservationSeries->RequiresNewSeries()) {
            $currentId = $reservationSeries->SeriesId();
            $newSeriesId = $this->InsertSeries($reservationSeries);
            Log::Debug('Series branched from seriesId: %s to seriesId: %s', $currentId, $newSeriesId);

            $reservationSeries->SetSeriesId($newSeriesId);

            /** @var Reservation $instance */
            foreach ($reservationSeries->Instances() as $instance) {
                $updateReservationCommand = new UpdateReservationCommand(
                    $instance->ReferenceNumber(),
                    $newSeriesId,
                    $instance->StartDate(),
                    $instance->EndDate(),
                    $instance->CheckinDate(),
                    $instance->CheckoutDate(),
                    $instance->PreviousEndDate(),
                    $instance->GetCreditsRequired()
                );

                $database->Execute($updateReservationCommand);
            }
        } else {
            Log::Debug('Updating existing series (seriesId: %s)', $reservationSeries->SeriesId());

            $updateSeries = new UpdateReservationSeriesCommand(
                $reservationSeries->SeriesId(),
                $reservationSeries->Title(),
                $reservationSeries->Description(),
                $reservationSeries->RepeatOptions()->RepeatType(),
                $reservationSeries->RepeatOptions()->ConfigurationString(),
                Date::Now(),
                $reservationSeries->StatusId(),
                $reservationSeries->UserId(),
                $reservationSeries->GetAllowParticipation(),
                $reservationSeries->BookedBy()->UserId
            );

            $database->Execute($updateSeries);

            foreach ($reservationSeries->AddedAttachments() as $attachment) {
                $this->AddReservationAttachment($attachment);
            }

            $creditsToDeduct = $reservationSeries->GetCreditsRequired() - $reservationSeries->GetOriginalCreditsConsumed();

            Log::Debug(
                'CREDITS - Reservation update adjusting credits for user %s by %s. Required %s, consumed %s',
                $reservationSeries->UserId(),
                $creditsToDeduct,
                $reservationSeries->GetCreditsRequired(),
                $reservationSeries->GetCreditsConsumed()
            );

            if ($creditsToDeduct != 0) {
                try {
                    $adjustCreditsCommand = new AdjustUserCreditsCommand($reservationSeries->UserId(), $creditsToDeduct, Resources::GetInstance()->GetString('ReservationUpdatedLog', $reservationSeries->CurrentInstance()->ReferenceNumber()));

                    $database->Execute($adjustCreditsCommand);
                } catch (Exception $ex) {
                }
            }
        }

        $this->ExecuteEvents($reservationSeries);
    }

    /**
     * @param ReservationSeries $reservationSeries
     * @return int newly created series_id
     */
    private function InsertSeries(ReservationSeries $reservationSeries)
    {
        $database = ServiceLocator::GetDatabase();

        $insertReservationSeries = new AddReservationSeriesCommand(
            Date::Now(),
            $reservationSeries->Title(),
            $reservationSeries->Description(),
            $reservationSeries->RepeatOptions()->RepeatType(),
            $reservationSeries->RepeatOptions()->ConfigurationString(),
            ReservationTypes::Reservation,
            $reservationSeries->StatusId(),
            $reservationSeries->UserId(),
            $reservationSeries->GetAllowParticipation(),
            $reservationSeries->TermsAcceptanceDate(),
            $reservationSeries->BookedBy()->UserId
        );

        $reservationSeriesId = $database->ExecuteInsert($insertReservationSeries);

        $reservationSeries->WithSeriesId($reservationSeriesId);

        $insertReservationResource = new AddReservationResourceCommand($reservationSeriesId, $reservationSeries->ResourceId(), ResourceLevel::Primary);

        $database->Execute($insertReservationResource);

        foreach ($reservationSeries->AdditionalResources() as $resource) {
            $insertReservationResource = new AddReservationResourceCommand($reservationSeriesId, $resource->GetResourceId(), ResourceLevel::Additional);

            $database->Execute($insertReservationResource);
        }

        foreach ($reservationSeries->Accessories() as $accessory) {
            $insertAccessory = new AddReservationAccessoryCommand($accessory->AccessoryId, $accessory->QuantityReserved, $reservationSeriesId);
            $database->Execute($insertAccessory);
        }

        foreach ($reservationSeries->AttributeValues() as $attribute) {
            $insertAttributeValue = new AddAttributeValueCommand(
                $attribute->AttributeId,
                $attribute->Value,
                $reservationSeriesId,
                CustomAttributeCategory::RESERVATION
            );
            $database->Execute($insertAttributeValue);
        }

        foreach ($reservationSeries->AddedAttachments() as $attachment) {
            $this->AddReservationAttachment($attachment);
        }

        if ($reservationSeries->GetStartReminder()->Enabled()) {
            $reminder = $reservationSeries->GetStartReminder();
            $insertAccessory = new AddReservationReminderCommand($reservationSeriesId, $reminder->MinutesPrior(), ReservationReminderType::Start);
            $database->Execute($insertAccessory);
        }

        if ($reservationSeries->GetEndReminder()->Enabled()) {
            $reminder = $reservationSeries->GetEndReminder();
            $insertAccessory = new AddReservationReminderCommand($reservationSeriesId, $reminder->MinutesPrior(), ReservationReminderType::End);
            $database->Execute($insertAccessory);
        }

        $creditsToDeduct = $reservationSeries->GetCreditsRequired() - $reservationSeries->GetCreditsConsumed();

        Log::Debug(
            'CREDITS - Reservation update adjusting credits for user %s by %s. Required %s, consumed %s',
            $reservationSeries->UserId(),
            $creditsToDeduct,
            $reservationSeries->GetCreditsRequired(),
            $reservationSeries->GetCreditsConsumed()
        );

        if ($creditsToDeduct != 0) {
            try {
                $adjustCreditsCommand = new AdjustUserCreditsCommand($reservationSeries->UserId(), $creditsToDeduct, Resources::GetInstance()->GetString('ReservationCreatedLog', $reservationSeries->CurrentInstance()->ReferenceNumber()));
                $database->Execute($adjustCreditsCommand);
            } catch (Exception $ex) {
            }
        }

        return $reservationSeriesId;
    }

    public function Delete(ExistingReservationSeries $existingReservationSeries)
    {
        $database = ServiceLocator::GetDatabase();

//        $creditAdjustment = 0 - $existingReservationSeries->GetCreditsConsumed();
        //		$creditAdjustment = $existingReservationSeries->GetCreditsRequired() - $existingReservationSeries->GetOriginalCreditsConsumed();
        $creditAdjustment = 0 - $existingReservationSeries->GetUnusedCreditBalance();
        if ($creditAdjustment != 0) {
            Log::Debug('CREDITS - Reservation delete adjusting credits for user %s by %s', $existingReservationSeries->UserId(), $creditAdjustment);

            try {
                $adjustCreditsCommand = new AdjustUserCreditsCommand($existingReservationSeries->UserId(), $creditAdjustment, Resources::GetInstance()->GetString('ReservationDeletedLog', $existingReservationSeries->CurrentInstance()->ReferenceNumber()));
                $database->Execute($adjustCreditsCommand);
            } catch (Exception $ex) {
            }
        }

        $this->ExecuteEvents($existingReservationSeries);
    }

    private function ExecuteEvents(ExistingReservationSeries $existingReservationSeries)
    {
        $database = ServiceLocator::GetDatabase();
        $events = $existingReservationSeries->GetEvents();

        foreach ($events as $event) {
            $command = $this->GetReservationCommand($event, $existingReservationSeries);

            if ($command != null) {
                $command->Execute($database);
            }
        }
    }

    /**
     * @param SeriesEvent $event
     * @param ExistingReservationSeries $series
     * @return EventCommand
     */
    private function GetReservationCommand($event, $series)
    {
        return ReservationEventMapper::Instance()->Map($event, $series);
    }

    /// LOAD BY ID HELPER FUNCTIONS

    /**
     * @param IReader $reader
     * @return ExistingReservationSeries
     */
    private function BuildSeries($reader)
    {
        $series = new ExistingReservationSeries();
        if ($row = $reader->GetRow()) {
            $seriesId = $row[ColumnNames::SERIES_ID];
            $repeatType = $row[ColumnNames::REPEAT_TYPE];
            $configurationString = $row[ColumnNames::REPEAT_OPTIONS];

            $repeatDates = [];
            if ($repeatType == RepeatType::Custom) {
                $getRepeatDates = new GetReservationRepeatDatesCommand($seriesId);
                $repeatReader = ServiceLocator::GetDatabase()->Query($getRepeatDates);
                while ($repeatRow = $repeatReader->GetRow()) {
                    $repeatDates[] = Date::FromDatabase($repeatRow[ColumnNames::RESERVATION_START]);
                }
                $repeatReader->Free();
            }
            $repeatOptions = $this->BuildRepeatOptions($repeatType, $configurationString, $repeatDates);
            $series->WithRepeatOptions($repeatOptions);

            $title = $row[ColumnNames::RESERVATION_TITLE];
            $description = $row[ColumnNames::RESERVATION_DESCRIPTION];

            $series->WithId($seriesId);
            $series->WithTitle($title);
            $series->WithDescription($description);
            $series->WithOwner($row[ColumnNames::RESERVATION_OWNER]);
            $series->WithStatus($row[ColumnNames::RESERVATION_STATUS]);
            $series->AllowParticipation($row[ColumnNames::RESERVATION_ALLOW_PARTICIPATION]);

            $startDate = Date::FromDatabase($row[ColumnNames::RESERVATION_START]);
            $endDate = Date::FromDatabase($row[ColumnNames::RESERVATION_END]);
            $duration = new DateRange($startDate, $endDate);

            $instance = new Reservation($series, $duration, $row[ColumnNames::RESERVATION_INSTANCE_ID], $row[ColumnNames::REFERENCE_NUMBER]);
            $instance->WithCheckin(Date::FromDatabase($row[ColumnNames::CHECKIN_DATE]), Date::FromDatabase($row[ColumnNames::CHECKOUT_DATE]));
            $instance->WithCreditsConsumed($row[ColumnNames::CREDIT_COUNT]);
            $instance->SetCreditsRequired($row[ColumnNames::CREDIT_COUNT]);

            $series->WithCurrentInstance($instance);
        }
        $reader->Free();

        return $series;
    }

    private function PopulateInstances(ExistingReservationSeries $series)
    {
        // get all series instances
        $getInstancesCommand = new GetReservationSeriesInstances($series->SeriesId());
        $reader = ServiceLocator::GetDatabase()->Query($getInstancesCommand);
        while ($row = $reader->GetRow()) {
            $start = Date::FromDatabase($row[ColumnNames::RESERVATION_START]);
            $end = Date::FromDatabase($row[ColumnNames::RESERVATION_END]);

            $reservation = new Reservation(
                $series,
                new DateRange($start, $end),
                $row[ColumnNames::RESERVATION_INSTANCE_ID],
                $row[ColumnNames::REFERENCE_NUMBER]
            );
            $reservation->WithCheckin(Date::FromDatabase($row[ColumnNames::CHECKIN_DATE]), Date::FromDatabase($row[ColumnNames::CHECKOUT_DATE]));
            $reservation->WithCreditsConsumed($row[ColumnNames::CREDIT_COUNT]);
            $reservation->SetCreditsRequired($row[ColumnNames::CREDIT_COUNT]);

            $series->WithInstance($reservation);
        }
        $reader->Free();
    }

    private function PopulateResources(ExistingReservationSeries $series)
    {
        // get all reservation resources
        $getResourcesCommand = new GetReservationResourcesCommand($series->SeriesId());
        $reader = ServiceLocator::GetDatabase()->Query($getResourcesCommand);
        while ($row = $reader->GetRow()) {
            $resource = BookableResource::Create($row);
            if ($row[ColumnNames::RESOURCE_LEVEL_ID] == ResourceLevel::Primary) {
                $series->WithPrimaryResource($resource);
            } else {
                $series->WithResource($resource);
            }
        }
        $reader->Free();
    }

    private function PopulateParticipants(ExistingReservationSeries $series)
    {
        $getSeriesParticipants = new GetReservationSeriesParticipantsCommand($series->SeriesId());

        $reader = ServiceLocator::GetDatabase()->Query($getSeriesParticipants);
        while ($row = $reader->GetRow()) {
            if ($row[ColumnNames::RESERVATION_USER_LEVEL] == ReservationUserLevel::PARTICIPANT) {
                $series->GetInstance($row[ColumnNames::REFERENCE_NUMBER])->WithParticipant($row[ColumnNames::USER_ID]);
            }
            if ($row[ColumnNames::RESERVATION_USER_LEVEL] == ReservationUserLevel::INVITEE) {
                $series->GetInstance($row[ColumnNames::REFERENCE_NUMBER])->WithInvitee($row[ColumnNames::USER_ID]);
            }
        }
        $reader->Free();
    }

    private function PopulateGuests(ExistingReservationSeries $series)
    {
        $getSeriesGuests = new GetReservationSeriesGuestsCommand($series->SeriesId());

        $reader = ServiceLocator::GetDatabase()->Query($getSeriesGuests);
        while ($row = $reader->GetRow()) {
            if ($row[ColumnNames::RESERVATION_USER_LEVEL] == ReservationUserLevel::PARTICIPANT) {
                $series->GetInstance($row[ColumnNames::REFERENCE_NUMBER])->WithParticipatingGuest($row[ColumnNames::EMAIL]);
            }
            if ($row[ColumnNames::RESERVATION_USER_LEVEL] == ReservationUserLevel::INVITEE) {
                $series->GetInstance($row[ColumnNames::REFERENCE_NUMBER])->WithInvitedGuest($row[ColumnNames::EMAIL]);
            }
        }
        $reader->Free();
    }

    private function PopulateAccessories(ExistingReservationSeries $series)
    {
        $getResourcesCommand = new GetReservationAccessoriesCommand($series->SeriesId());
        $reader = ServiceLocator::GetDatabase()->Query($getResourcesCommand);
        while ($row = $reader->GetRow()) {
            $series->WithAccessory(new ReservationAccessory($row[ColumnNames::ACCESSORY_ID], $row[ColumnNames::QUANTITY]));
        }
        $reader->Free();
    }

    private function PopulateAttributeValues(ExistingReservationSeries $series)
    {
        $getAttributes = new GetAttributeValuesCommand($series->SeriesId(), CustomAttributeCategory::RESERVATION);
        $reader = ServiceLocator::GetDatabase()->Query($getAttributes);
        while ($row = $reader->GetRow()) {
            $series->WithAttribute(new AttributeValue($row[ColumnNames::ATTRIBUTE_ID], $row[ColumnNames::ATTRIBUTE_VALUE]));
        }
        $reader->Free();
    }

    private function PopulateAttachmentIds(ExistingReservationSeries $series)
    {
        $getAttachments = new GetReservationAttachmentsCommand($series->SeriesId());
        $reader = ServiceLocator::GetDatabase()->Query($getAttachments);
        while ($row = $reader->GetRow()) {
            $series->WithAttachment($row[ColumnNames::FILE_ID], $row[ColumnNames::FILE_EXTENSION]);
        }
        $reader->Free();
    }

    private function PopulateReminders(ExistingReservationSeries $series)
    {
        $getReminders = new GetReservationReminders($series->SeriesId());
        $reader = ServiceLocator::GetDatabase()->Query($getReminders);
        while ($row = $reader->GetRow()) {
            $reminder = ReservationReminder::FromMinutes($row[ColumnNames::REMINDER_MINUTES_PRIOR]);
            if ($row[ColumnNames::REMINDER_TYPE] == ReservationReminderType::Start) {
                $series->WithStartReminder($reminder);
            } else {
                $series->WithEndReminder($reminder);
            }
        }
        $reader->Free();
    }

    private function BuildRepeatOptions($repeatType, $configurationString, $repeatDates)
    {
        $configuration = RepeatConfiguration::Create($repeatType, $configurationString);
        $factory = new RepeatOptionsFactory();
        return $factory->Create(
            $repeatType,
            $configuration->Interval,
            $configuration->TerminationDate,
            $configuration->Weekdays,
            $configuration->MonthlyType,
            $repeatDates
        );
    }

    // LOAD BY ID HELPER FUNCTIONS END

    /**
     * @param $attachmentFileId int
     * @return ReservationAttachment
     */
    public function LoadReservationAttachment($attachmentFileId)
    {
        $command = new GetReservationAttachmentCommand($attachmentFileId);
        $reader = ServiceLocator::GetDatabase()->Query($command);

        if ($row = $reader->GetRow()) {
            $fileId = $row[ColumnNames::FILE_ID];
            $extension = $row[ColumnNames::FILE_EXTENSION];
            $fileSystem = ServiceLocator::GetFileSystem();
            $contents = $fileSystem->GetFileContents($fileSystem->GetReservationAttachmentsPath() . "$fileId.$extension");
            $attachment = ReservationAttachment::Create(
                $row[ColumnNames::FILE_NAME],
                $row[ColumnNames::FILE_TYPE],
                $row[ColumnNames::FILE_SIZE],
                $contents,
                $row[ColumnNames::FILE_EXTENSION],
                $row[ColumnNames::SERIES_ID]
            );
            $attachment->WithFileId($fileId);

            return $attachment;
        }

        return null;
    }

    /**
     * @param $attachmentFile ReservationAttachment
     * @return int
     */
    public function AddReservationAttachment(ReservationAttachment $attachmentFile)
    {
        $command = new AddReservationAttachmentCommand(
            $attachmentFile->FileName(),
            $attachmentFile->FileType(),
            $attachmentFile->FileSize(),
            $attachmentFile->FileExtension(),
            $attachmentFile->SeriesId()
        );
        $id = ServiceLocator::GetDatabase()->ExecuteInsert($command);
        $extension = $attachmentFile->FileExtension();
        $attachmentFile->WithFileId($id);

        $fileSystem = ServiceLocator::GetFileSystem();
        $fileSystem->Save($fileSystem->GetReservationAttachmentsPath(), "$id.$extension", $attachmentFile->FileContents());

        return $id;
    }

    /**
     * @return ReservationColorRule[]
     */
    public function GetReservationColorRules()
    {
        $command = new GetReservationColorRulesCommand();
        $reader = ServiceLocator::GetDatabase()->Query($command);

        $rules = [];
        while ($row = $reader->GetRow()) {
            $rules[] = ReservationColorRule::FromRow($row);
        }

        return $rules;
    }

    public function GetReservationColorRule($ruleId)
    {
        $command = new GetReservationColorRuleCommand($ruleId);
        $reader = ServiceLocator::GetDatabase()->Query($command);

        $rule = null;
        if ($row = $reader->GetRow()) {
            $rule = ReservationColorRule::FromRow($row);
        }

        return $rule;
    }

    public function AddReservationColorRule(ReservationColorRule $rule)
    {
        $command = new AddReservationColorRuleCommand($rule->AttributeType, $rule->Color, $rule->ComparisonType, $rule->RequiredValue, $rule->AttributeId);
        $id = ServiceLocator::GetDatabase()->ExecuteInsert($command);
        $rule->Id = $id;

        return $id;
    }

    public function DeleteReservationColorRule(ReservationColorRule $rule)
    {
        $command = new DeleteReservationColorRuleCommand($rule->Id);
        ServiceLocator::GetDatabase()->Execute($command);
    }
}

class ReservationEventMapper
{
    private $buildMethods = [];
    private static $instance;

    private function __construct()
    {
        $this->buildMethods['SeriesDeletedEvent'] = 'BuildDeleteSeriesCommand';
        $this->buildMethods['OwnerChangedEvent'] = 'OwnerChangedCommand';

        $this->buildMethods['InstanceAddedEvent'] = 'BuildAddReservationCommand';
        $this->buildMethods['InstanceRemovedEvent'] = 'BuildRemoveReservationCommand';
        $this->buildMethods['InstanceUpdatedEvent'] = 'BuildUpdateReservationCommand';

        $this->buildMethods['ResourceRemovedEvent'] = 'BuildRemoveResourceCommand';
        $this->buildMethods['ResourceAddedEvent'] = 'BuildAddResourceCommand';

        $this->buildMethods['AccessoryAddedEvent'] = 'BuildAddAccessoryCommand';
        $this->buildMethods['AccessoryRemovedEvent'] = 'BuildRemoveAccessoryCommand';

        $this->buildMethods['AttributeAddedEvent'] = 'BuildAddAttributeCommand';
        $this->buildMethods['AttributeRemovedEvent'] = 'BuildRemoveAttributeCommand';

        $this->buildMethods['AttachmentRemovedEvent'] = 'BuildAttachmentRemovedEvent';

        $this->buildMethods['ReminderAddedEvent'] = 'BuildReminderAddedEvent';
        $this->buildMethods['ReminderRemovedEvent'] = 'BuildReminderRemovedEvent';
    }

    /**
     * @static
     * @return ReservationEventMapper
     */
    public static function Instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new ReservationEventMapper();
        }

        return self::$instance;
    }

    /**
     * @param $event mixed
     * @param $series ExistingReservationSeries
     * @return EventCommand
     */
    public function Map($event, ExistingReservationSeries $series)
    {
        $eventType = get_class($event);
        if (!isset($this->buildMethods[$eventType])) {
            Log::Debug("No command event mapper found for event $eventType");
            return null;
        }

        $method = $this->buildMethods[$eventType];
        return $this->$method($event, $series);
    }

    private function BuildDeleteSeriesCommand(SeriesDeletedEvent $event)
    {
        return new DeleteSeriesEventCommand($event->Series());
    }

    private function BuildAddReservationCommand(InstanceAddedEvent $event, ExistingReservationSeries $series)
    {
        return new InstanceAddedEventCommand($event->Instance(), $series);
    }

    private function BuildRemoveReservationCommand(InstanceRemovedEvent $event, ExistingReservationSeries $series)
    {
        return new InstanceRemovedEventCommand($event->Instance(), $series);
    }

    private function BuildUpdateReservationCommand(InstanceUpdatedEvent $event, ExistingReservationSeries $series)
    {
        return new InstanceUpdatedEventCommand($event->Instance(), $series);
    }

    private function BuildRemoveResourceCommand(ResourceRemovedEvent $event, ExistingReservationSeries $series)
    {
        return new EventCommand(new RemoveReservationResourceCommand($series->SeriesId(), $event->ResourceId()), $series);
    }

    private function BuildAddResourceCommand(ResourceAddedEvent $event, ExistingReservationSeries $series)
    {
        return new EventCommand(new AddReservationResourceCommand($series->SeriesId(), $event->ResourceId(), $event->ResourceLevel()), $series);
    }

    private function BuildAddAccessoryCommand(AccessoryAddedEvent $event, ExistingReservationSeries $series)
    {
        return new EventCommand(new AddReservationAccessoryCommand($event->AccessoryId(), $event->Quantity(), $series->SeriesId()), $series);
    }

    private function BuildRemoveAccessoryCommand(AccessoryRemovedEvent $event, ExistingReservationSeries $series)
    {
        return new EventCommand(new RemoveReservationAccessoryCommand($series->SeriesId(), $event->AccessoryId()), $series);
    }

    private function BuildAddAttributeCommand(AttributeAddedEvent $event, ExistingReservationSeries $series)
    {
        return new EventCommand(
            new AddAttributeValueCommand($event->AttributeId(), $event->Value(), $series->SeriesId(), CustomAttributeCategory::RESERVATION),
            $series
        );
    }

    private function BuildRemoveAttributeCommand(AttributeRemovedEvent $event, ExistingReservationSeries $series)
    {
        return new EventCommand(new RemoveAttributeValueCommand($event->AttributeId(), $series->SeriesId()), $series);
    }

    private function OwnerChangedCommand(OwnerChangedEvent $event, ExistingReservationSeries $series)
    {
        return new OwnerChangedEventCommand($event);
    }

    private function BuildAttachmentRemovedEvent(AttachmentRemovedEvent $event, ExistingReservationSeries $series)
    {
        return new AttachmentRemovedCommand($event);
    }

    private function BuildReminderAddedEvent(ReminderAddedEvent $event, ExistingReservationSeries $series)
    {
        return new ReminderAddedCommand($event);
    }

    private function BuildReminderRemovedEvent(ReminderRemovedEvent $event, ExistingReservationSeries $series)
    {
        return new EventCommand(new RemoveReservationReminderCommand($series->SeriesId(), $event->ReminderType()), $series);
    }
}

class EventCommand
{
    /**
     * @var ISqlCommand
     */
    private $command;

    /**
     * @var ExistingReservationSeries
     */
    protected $series;

    public function __construct(ISqlCommand $command, ExistingReservationSeries $series)
    {
        $this->command = $command;
        $this->series = $series;
    }

    public function Execute(Database $database)
    {
        if (!$this->series->RequiresNewSeries()) {
            $database->Execute($this->command);
        }
    }
}

class DeleteSeriesEventCommand extends EventCommand
{
    public function __construct(ExistingReservationSeries $series)
    {
        $this->series = $series;
    }

    public function Execute(Database $database)
    {
        $deletedBy = $this->series->BookedBy();
        $database->Execute(new DeleteSeriesCommand($this->series->SeriesId(), Date::Now(), !empty($deletedBy) ? $deletedBy->UserId : null));
    }
}

class InstanceRemovedEventCommand extends EventCommand
{
    /**
     * @var Reservation
     */
    private $instance;

    public function __construct(Reservation $instance, ReservationSeries $series)
    {
        $this->instance = $instance;
        $this->series = $series;
    }

    public function Execute(Database $database)
    {
        $database->Execute(new RemoveReservationCommand($this->instance->ReferenceNumber()));
    }
}

class InstanceAddedEventCommand extends EventCommand
{
    /**
     * @var Reservation
     */
    private $instance;

    public function __construct(Reservation $instance, ReservationSeries $series)
    {
        $this->instance = $instance;
        $this->series = $series;
    }

    public function Execute(Database $database)
    {
        $insertReservation = new AddReservationCommand(
            $this->instance->StartDate(),
            $this->instance->EndDate(),
            $this->instance->ReferenceNumber(),
            $this->series->SeriesId(),
            $this->instance->GetCreditsRequired()
        );

        $reservationId = $database->ExecuteInsert($insertReservation);
//
//        if ($reservationId <= 0)
//        {
//            $database->Execute(new DeleteSeriesPermanantCommand($this->series->SeriesId()));
//            Log::Error("Could not insert reservation because there were conflicts. Command: %s", $insertReservation);
//            throw new Exception("Could not insert reservation - conflicting times");
//        }
        $insertReservationUser = new AddReservationUserCommand($reservationId, $this->series->UserId(), ReservationUserLevel::OWNER);

        $database->Execute($insertReservationUser);

        foreach ($this->instance->AddedParticipants() as $participantId) {
            $insertReservationUser = new AddReservationUserCommand($reservationId, $participantId, ReservationUserLevel::PARTICIPANT);

            $database->Execute($insertReservationUser);
        }

        foreach ($this->instance->AddedInvitees() as $inviteeId) {
            $insertReservationUser = new AddReservationUserCommand($reservationId, $inviteeId, ReservationUserLevel::INVITEE);

            $database->Execute($insertReservationUser);
        }


        foreach ($this->instance->AddedInvitedGuests() as $guest) {
            $insertReservationGuest = new AddReservationGuestCommand($reservationId, $guest, ReservationUserLevel::INVITEE);

            $database->Execute($insertReservationGuest);
        }

        foreach ($this->instance->AddedParticipatingGuests() as $guest) {
            $insertReservationGuest = new AddReservationGuestCommand($reservationId, $guest, ReservationUserLevel::PARTICIPANT);

            $database->Execute($insertReservationGuest);
        }
    }
}

class InstanceUpdatedEventCommand extends EventCommand
{
    /**
     * @var Reservation
     */
    private $instance;

    public function __construct(Reservation $instance, ExistingReservationSeries $series)
    {
        $this->instance = $instance;
        $this->series = $series;
    }

    public function Execute(Database $database)
    {
        $instanceId = $this->instance->ReservationId();
        $updateReservationCommand = new UpdateReservationCommand(
            $this->instance->ReferenceNumber(),
            $this->series->SeriesId(),
            $this->instance->StartDate(),
            $this->instance->EndDate(),
            $this->instance->CheckinDate(),
            $this->instance->CheckoutDate(),
            $this->instance->PreviousEndDate(),
            $this->instance->GetCreditsRequired()
        );

        $database->Execute($updateReservationCommand);

        foreach ($this->instance->RemovedParticipants() as $participantId) {
            $removeReservationUser = new RemoveReservationUserCommand($instanceId, $participantId);

            $database->Execute($removeReservationUser);
        }

        foreach ($this->instance->RemovedInvitees() as $inviteeId) {
            $insertReservationUser = new RemoveReservationUserCommand($instanceId, $inviteeId);

            $database->Execute($insertReservationUser);
        }

        foreach ($this->instance->RemovedInvitedGuests() as $guest) {
            $removeReservationGuest = new RemoveReservationGuestCommand($instanceId, $guest);

            $database->Execute($removeReservationGuest);
        }

        foreach ($this->instance->RemovedParticipatingGuests() as $guest) {
            $removeReservationGuest = new RemoveReservationGuestCommand($instanceId, $guest);

            $database->Execute($removeReservationGuest);
        }

        foreach ($this->instance->AddedParticipants() as $participantId) {
            $insertReservationUser = new AddReservationUserCommand($instanceId, $participantId, ReservationUserLevel::PARTICIPANT);

            $database->Execute($insertReservationUser);
        }

        foreach ($this->instance->AddedInvitees() as $inviteeId) {
            $insertReservationUser = new AddReservationUserCommand($instanceId, $inviteeId, ReservationUserLevel::INVITEE);

            $database->Execute($insertReservationUser);
        }

        foreach ($this->instance->AddedInvitedGuests() as $guest) {
            $insertReservationGuest = new AddReservationGuestCommand($instanceId, $guest, ReservationUserLevel::INVITEE);

            $database->Execute($insertReservationGuest);
        }

        foreach ($this->instance->AddedParticipatingGuests() as $guest) {
            $insertReservationGuest = new AddReservationGuestCommand($instanceId, $guest, ReservationUserLevel::PARTICIPANT);

            $database->Execute($insertReservationGuest);
        }
    }
}

class OwnerChangedEventCommand extends EventCommand
{
    /**
     * @var OwnerChangedEvent
     */
    private $event;

    public function __construct(OwnerChangedEvent $event)
    {
        $this->event = $event;
    }

    public function Execute(Database $database)
    {
        $oldOwnerId = $this->event->OldOwnerId();
        $newOwnerId = $this->event->NewOwnerId();

        $instances = $this->event->Series()->_Instances();

        /** @var Reservation $instance */
        foreach ($instances as $instance) {
            if (!$instance->IsNew()) {
                $id = $instance->ReservationId();
                $database->Execute(new RemoveReservationUsersCommand($id, ReservationUserLevel::OWNER));
                $database->Execute(new AddReservationUserCommand($id, $newOwnerId, ReservationUserLevel::OWNER));
            }
        }
    }
}

class AttachmentRemovedCommand extends EventCommand
{
    /**
     * @var AttachmentRemovedEvent
     */
    private $event;

    public function __construct(AttachmentRemovedEvent $event)
    {
        $this->event = $event;
    }

    public function Execute(Database $database)
    {
        $database->Execute(new RemoveReservationAttachmentCommand($this->event->FileId()));
        $fileSystem = ServiceLocator::GetFileSystem();
        $fileSystem->RemoveFile($fileSystem->GetReservationAttachmentsPath() . $this->event->FileName());
    }
}

class ReminderAddedCommand extends EventCommand
{
    /**
     * @var ReminderAddedEvent
     */
    private $event;

    public function __construct(ReminderAddedEvent $event)
    {
        $this->event = $event;
    }

    public function Execute(Database $database)
    {
        $database->Execute(new RemoveReservationReminderCommand($this->event->SeriesId(), $this->event->ReminderType()));
        $database->Execute(new AddReservationReminderCommand($this->event->SeriesId(), $this->event->MinutesPrior(), $this->event->ReminderType()));
    }
}

interface IReservationRepository
{
    /**
     * Insert a new reservation
     *
     * @param ReservationSeries $reservation
     * @return void
     */
    public function Add(ReservationSeries $reservation);

    /**
     * Return an existing reservation series
     *
     * @param int $reservationInstanceId
     * @return ExistingReservationSeries or null if no reservation found
     */
    public function LoadById($reservationInstanceId);

    /**
     * Return an existing reservation series
     *
     * @param string $referenceNumber
     * @return ExistingReservationSeries or null if no reservation found
     */
    public function LoadByReferenceNumber($referenceNumber);

    /**
     * Update an existing reservation
     *
     * @param ExistingReservationSeries $existingReservationSeries
     * @return void
     */
    public function Update(ExistingReservationSeries $existingReservationSeries);

    /**
     * Delete all or part of an existing reservation
     *
     * @param ExistingReservationSeries $existingReservationSeries
     * @return void
     */
    public function Delete(ExistingReservationSeries $existingReservationSeries);

    /**
     * @abstract
     * @param $attachmentFileId int
     * @return ReservationAttachment
     */
    public function LoadReservationAttachment($attachmentFileId);

    /**
     * @param $attachmentFile ReservationAttachment
     * @return int
     */
    public function AddReservationAttachment(ReservationAttachment $attachmentFile);

    /**
     * @return ReservationColorRule[]
     */
    public function GetReservationColorRules();

    /**
     * @param int $ruleId
     * @return ReservationColorRule
     */
    public function GetReservationColorRule($ruleId);

    /**
     * @param ReservationColorRule $colorRule
     * @return int
     */
    public function AddReservationColorRule(ReservationColorRule $colorRule);

    /**
     * @param ReservationColorRule $colorRule
     */
    public function DeleteReservationColorRule(ReservationColorRule $colorRule);
}
