<?php

require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

interface IReservationUpdatePresenter
{
    /**
     * @return ExistingReservationSeries
     */
    public function BuildReservation();

    /**
     * @param ExistingReservationSeries $reservationSeries
     */
    public function HandleReservation($reservationSeries);
}

class ReservationUpdatePresenter implements IReservationUpdatePresenter
{
    /**
     * @var IReservationUpdatePage
     */
    private $page;

    /**
     * @var UpdateReservationPersistenceService
     */
    private $persistenceService;

    /**
     * @var IReservationHandler
     */
    private $handler;

    /**
     * @var IResourceRepository
     */
    private $resourceRepository;

    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @var UserSession
     */
    private $userSession;

    public function __construct(
        IReservationUpdatePage $page,
        IUpdateReservationPersistenceService $persistenceService,
        IReservationHandler $handler,
        IResourceRepository $resourceRepository,
        IScheduleRepository $scheduleRepository,
        UserSession $userSession
    ) {
        $this->page = $page;
        $this->persistenceService = $persistenceService;
        $this->handler = $handler;
        $this->resourceRepository = $resourceRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->userSession = $userSession;
    }

    /**
     * @return ExistingReservationSeries
     */
    public function BuildReservation()
    {
        $referenceNumber = $this->page->GetReferenceNumber();
        $existingSeries = $this->persistenceService->LoadByReferenceNumber($referenceNumber);
        $existingSeries->ApplyChangesTo($this->page->GetSeriesUpdateScope());

        $resourceId = $this->page->GetResourceId();
        $additionalResourceIds = $this->page->GetResources();

        if (empty($resourceId)) {
            // the first additional resource will become the primary if the primary is removed
            $resourceId = array_shift($additionalResourceIds);
        }

        $resource = $this->resourceRepository->LoadById($resourceId);
        $existingSeries->Update(
            $this->page->GetUserId(),
            $resource,
            htmlspecialchars_decode($this->page->GetTitle()),
            htmlspecialchars_decode($this->page->GetDescription()),
            $this->userSession
        );

        $existingSeries->UpdateDuration($this->GetReservationDuration());
        $roFactory = new RepeatOptionsFactory();

        $existingSeries->Repeats($roFactory->CreateFromComposite($this->page, $this->userSession->Timezone));

        $additionalResources = [];
        foreach ($additionalResourceIds as $additionalResourceId) {
            if ($additionalResourceId != $resourceId) {
                $additionalResources[] = $this->resourceRepository->LoadById($additionalResourceId);
            }
        }

        $existingSeries->ChangeResources($additionalResources);
        $existingSeries->ChangeParticipants($this->page->GetParticipants());
        $existingSeries->ChangeInvitees($this->page->GetInvitees());
        $existingSeries->ChangeAccessories($this->GetAccessories());
        $existingSeries->ChangeAttributes($this->GetAttributes());

        $invitedGuests = $this->page->GetInvitedGuests();
        $participatingGuests = $this->page->GetParticipatingGuests();
        $existingSeries->ChangeGuests($invitedGuests, $participatingGuests);

        $existingSeries->AllowParticipation($this->page->GetAllowParticipation());

        $attachments = $this->page->GetAttachments();
        foreach ($attachments as $attachment) {
            if ($attachment != null) {
                if ($attachment->IsError()) {
                    Log::Error('Error attaching file %s. %s', $attachment->OriginalName(), $attachment->Error());
                } else {
                    Log::Debug('Attaching file %s to series %s', $attachment->OriginalName(), $existingSeries->SeriesId());
                    $att = ReservationAttachment::Create(
                        $attachment->OriginalName(),
                        $attachment->MimeType(),
                        $attachment->Size(),
                        $attachment->Contents(),
                        $attachment->Extension(),
                        $existingSeries->SeriesId()
                    );
                    $existingSeries->AddAttachment($att);
                }
            }
        }

        foreach ($this->page->GetRemovedAttachmentIds() as $fileId) {
            $existingSeries->RemoveAttachment($fileId);
        }

        if ($this->page->HasStartReminder()) {
            $existingSeries->AddStartReminder(new ReservationReminder($this->page->GetStartReminderValue(), $this->page->GetStartReminderInterval()));
        } else {
            $existingSeries->RemoveStartReminder();
        }

        if ($this->page->HasEndReminder()) {
            $existingSeries->AddEndReminder(new ReservationReminder($this->page->GetEndReminderValue(), $this->page->GetEndReminderInterval()));
        } else {
            $existingSeries->RemoveEndReminder();
        }

        if (Configuration::Instance()->GetKey(ConfigKeys::CREDITS_ENABLED, new BooleanConverter())) {
            $layout = $this->scheduleRepository->GetLayout($existingSeries->ScheduleId(), new ScheduleLayoutFactory($this->userSession->Timezone));
            $existingSeries->CalculateCredits($layout);
        }

        return $existingSeries;
    }

    /**
     * @param ExistingReservationSeries $reservationSeries
     */
    public function HandleReservation($reservationSeries)
    {
        $successfullySaved = $this->handler->Handle($reservationSeries, $this->page);

        if ($successfullySaved) {
            $this->page->SetRequiresApproval($reservationSeries->RequiresApproval());
            $this->page->SetReferenceNumber($reservationSeries->CurrentInstance()->ReferenceNumber());
        }
    }

    /**
     * @return DateRange
     */
    private function GetReservationDuration()
    {
        $startDate = $this->page->GetStartDate();
        $startTime = $this->page->GetStartTime();
        $endDate = $this->page->GetEndDate();
        $endTime = $this->page->GetEndTime();

        $timezone = $this->userSession->Timezone;
        return DateRange::Create($startDate . ' ' . $startTime, $endDate . ' ' . $endTime, $timezone);
    }

    private function GetAccessories()
    {
        $accessories = [];
        foreach ($this->page->GetAccessories() as $accessory) {
            $accessories[] = new ReservationAccessory($accessory->Id, $accessory->Quantity, $accessory->Name);
        }

        return $accessories;
    }

    /**
     * @return AttributeValue[]
     */
    private function GetAttributes()
    {
        $attributes = [];
        foreach ($this->page->GetAttributes() as $attribute) {
            $attributes[] = new AttributeValue($attribute->Id, $attribute->Value);
        }

        return $attributes;
    }
}
