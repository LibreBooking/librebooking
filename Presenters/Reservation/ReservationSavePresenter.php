<?php

require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

interface IReservationSavePresenter
{
    /**
     * @return ReservationSeries
     */
    public function BuildReservation();

    /**
     * @param ReservationSeries $reservationSeries
     */
    public function HandleReservation($reservationSeries);
}

class ReservationSavePresenter implements IReservationSavePresenter
{
    /**
     * @var IReservationSavePage
     */
    private $page;

    /**
     * @var IReservationPersistenceService
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
     * @var UserSession
     */
    private $userSession;
    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;

    public function __construct(
        IReservationSavePage $page,
        IReservationPersistenceService $persistenceService,
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

    public function BuildReservation()
    {
        $userId = $this->page->GetUserId();
        $primaryResourceId = $this->page->GetResourceId();
        $resource = $this->resourceRepository->LoadById($primaryResourceId);
        $title = $this->page->GetTitle();
        $description = $this->page->GetDescription();
        $roFactory = new RepeatOptionsFactory();
        $repeatOptions = $roFactory->CreateFromComposite($this->page, $this->userSession->Timezone);
        $duration = $this->GetReservationDuration();

        $reservationSeries = ReservationSeries::Create($userId, $resource, htmlspecialchars_decode($title), htmlspecialchars_decode($description), $duration, $repeatOptions, $this->userSession);

        $resourceIds = $this->page->GetResources();
        foreach ($resourceIds as $resourceId) {
            if ($primaryResourceId != $resourceId) {
                $reservationSeries->AddResource($this->resourceRepository->LoadById($resourceId));
            }
        }

        $accessories = $this->page->GetAccessories();
        foreach ($accessories as $accessory) {
            $reservationSeries->AddAccessory(new ReservationAccessory($accessory->Id, $accessory->Quantity, $accessory->Name));
        }

        $attributes = $this->page->GetAttributes();
        foreach ($attributes as $attribute) {
            $reservationSeries->AddAttributeValue(new AttributeValue($attribute->Id, $attribute->Value));
        }

        $participantIds = $this->page->GetParticipants();
        $reservationSeries->ChangeParticipants($participantIds);

        $inviteeIds = $this->page->GetInvitees();
        $reservationSeries->ChangeInvitees($inviteeIds);

        $invitedGuests = $this->page->GetInvitedGuests();
        $participatingGuests = $this->page->GetParticipatingGuests();
        $reservationSeries->ChangeGuests($invitedGuests, $participatingGuests);

        $reservationSeries->AllowParticipation($this->page->GetAllowParticipation());

        $attachments = $this->page->GetAttachments();

        foreach ($attachments as $attachment) {
            if ($attachment != null) {
                if ($attachment->IsError()) {
                    Log::Error('Error attaching file %s. %s', $attachment->OriginalName(), $attachment->Error());
                } else {
                    $att = ReservationAttachment::Create($attachment->OriginalName(), $attachment->MimeType(), $attachment->Size(), $attachment->Contents(), $attachment->Extension(), 0);
                    $reservationSeries->AddAttachment($att);
                }
            }
        }

        if ($this->page->HasStartReminder()) {
            $reservationSeries->AddStartReminder(new ReservationReminder($this->page->GetStartReminderValue(), $this->page->GetStartReminderInterval()));
        }

        if ($this->page->HasEndReminder()) {
            $reservationSeries->AddEndReminder(new ReservationReminder($this->page->GetEndReminderValue(), $this->page->GetEndReminderInterval()));
        }

        if (Configuration::Instance()->GetKey(ConfigKeys::CREDITS_ENABLED, new BooleanConverter())) {
            $layout = $this->scheduleRepository->GetLayout($reservationSeries->ScheduleId(), new ScheduleLayoutFactory($this->userSession->Timezone));
            $reservationSeries->CalculateCredits($layout);
        }

        $reservationSeries->AcceptTerms($this->page->GetTermsOfServiceAcknowledgement());

        return $reservationSeries;
    }

    /**
     * @param ReservationSeries $reservationSeries
     * @throws Exception
     */
    public function HandleReservation($reservationSeries)
    {
        $successfullySaved = $this->handler->Handle(
            $reservationSeries,
            $this->page
        );


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
}
