<?php

require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

interface IReservationApprovalPresenter
{
    public function PageLoad();
}

class ReservationApprovalPresenter implements IReservationApprovalPresenter
{
    /**
     * @var IReservationApprovalPage
     */
    private $page;

    /**
     * @var IUpdateReservationPersistenceService
     */
    private $persistenceService;

    /**
     * @var IReservationHandler
     */
    private $handler;

    /**
     * @var IReservationAuthorization
     */
    private $authorization;

    /**
     * @var UserSession
     */
    private $userSession;

    public function __construct(
        IReservationApprovalPage $page,
        IUpdateReservationPersistenceService $persistenceService,
        IReservationHandler $handler,
        IReservationAuthorization $authorizationService,
        UserSession $userSession
    ) {
        $this->page = $page;
        $this->persistenceService = $persistenceService;
        $this->handler = $handler;
        $this->authorization = $authorizationService;
        $this->userSession = $userSession;
    }

    public function PageLoad()
    {
        $referenceNumber = $this->page->GetReferenceNumber();

        Log::Debug('User: %s, Approving reservation with reference number %s', $this->userSession->UserId, $referenceNumber);

        $series = $this->persistenceService->LoadByReferenceNumber($referenceNumber);
        if ($this->authorization->CanApprove(new ReservationViewAdapter($series), $this->userSession)) {
            $series->Approve($this->userSession);
            $this->handler->Handle($series, $this->page);
        } else {
            $this->page->SetErrors(errors: [Resources::GetInstance()->GetString('NoReservationAccess')]);
        }
    }
}

class ReservationViewAdapter extends ReservationView
{
    public function __construct(ExistingReservationSeries $series)
    {
        foreach ($series->Accessories() as $accessory) {
            $this->Accessories[] = new ReservationAccessoryView($accessory->AccessoryId, $accessory->QuantityReserved, $accessory->Name, null);
        }

        foreach ($series->AdditionalResources() as $resource) {
            $this->AdditionalResourceIds[] = $resource->GetId();
        }

        foreach ($series->AddedAttachments() as $attachment) {
            $this->Attachments[] = new ReservationAttachmentView($attachment->FileId(), $series->SeriesId(), $attachment->FileName());
        }

        foreach ($series->AttributeValues() as $av) {
            $this->Attributes[] = $av;
        }

        $this->Description = $series->Description();
        $this->EndDate = $series->CurrentInstance()->EndDate();
        $this->OwnerId = $series->UserId();
        $this->ReferenceNumber = $series->CurrentInstance()->ReferenceNumber();
        $this->ReservationId = $series->CurrentInstance()->ReservationId();
        $this->ResourceId = $series->ResourceId();

        foreach ($series->AllResources() as $resource) {
            $this->Resources[] = new ReservationResourceView(
                $resource->GetId(),
                $resource->GetName(),
                $resource->GetAdminGroupId(),
                $resource->GetScheduleId(),
                $resource->GetScheduleAdminGroupId(),
                $resource->IsCheckInEnabled(),
                $resource->GetAutoReleaseMinutes(),
                $resource->GetStatusId()
            );
        }

        $this->ScheduleId = $series->ScheduleId();
        $this->SeriesId = $series->SeriesId();
        $this->StartDate = $series->CurrentInstance()->StartDate();
        $this->StatusId = $series->StatusId();
    }
}
