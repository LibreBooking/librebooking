<?php

require_once(ROOT_DIR . 'lib/Email/namespace.php');
require_once(ROOT_DIR . 'Pages/Pages.php');
require_once(ROOT_DIR . 'Pages/Export/CalendarExportDisplay.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationEmailTemplateContext.php');

abstract class ReservationEmailMessage extends EmailMessage
{
    /**
     * @var User
     */
    protected $reservationOwner;

    /**
     * @var ReservationSeries
     */
    protected $reservationSeries;

    /**
     * @var BookableResource
     */
    protected $primaryResource;

    protected ?string $timezone;

    /**
     * @var IAttributeRepository
     */
    protected $attributeRepository;

    /**
     * @var IUserRepository
     */
    protected $userRepository;

    /** @var array<int, UserDto|null> Populated by PopulateIcsAttachment(); reused by PopulateTemplate() to avoid N+1 GetById() calls. */
    private array $resolvedParticipants = [];
    /** @var array<int, UserDto|null> */
    private array $resolvedInvitees = [];

    public function __construct(
        User $reservationOwner,
        ReservationSeries $reservationSeries,
        $language,
        IAttributeRepository $attributeRepository,
        IUserRepository $userRepository
    ) {
        if (empty($language)) {
            $language = $reservationOwner->Language();
        }
        parent::__construct($language);

        $this->reservationOwner = $reservationOwner;
        $this->reservationSeries = $reservationSeries;
        $ownerTimezone = $reservationOwner->Timezone();
        $this->timezone = !empty($ownerTimezone) ? $ownerTimezone : Configuration::Instance()->GetDefaultTimezone();
        $this->attributeRepository = $attributeRepository;
        $this->primaryResource = $reservationSeries->Resource();
        $this->userRepository = $userRepository;
    }

    /**
     * @abstract
     * @return string
     */
    abstract protected function GetTemplateName();

    public function To()
    {
        $address = $this->reservationOwner->EmailAddress();
        $name = $this->reservationOwner->FullName();

        return [new EmailAddress($address, $name)];
    }

    public function Body()
    {
        $this->PopulateTemplate();
        return $this->FetchTemplate($this->GetTemplateName());
    }

    public function From()
    {
        $bookedBy = $this->reservationSeries->BookedBy();
        if ($bookedBy != null) {
            $name = new FullName($bookedBy->FirstName, $bookedBy->LastName);
            return new EmailAddress($bookedBy->Email, $name->__toString());
        }
        return new EmailAddress($this->reservationOwner->EmailAddress(), $this->reservationOwner->FullName());
    }

    protected function PopulateTemplate()
    {
        $currentInstance = $this->reservationSeries->CurrentInstance();
        $context = new ReservationEmailTemplateContext(
            reservationSeries: $this->reservationSeries,
            reservationOwner: $this->reservationOwner,
            primaryResource: $this->primaryResource,
            attributeRepository: $this->attributeRepository
        );

        $this->Set('UserName', $this->reservationOwner->FullName());
        $this->Set('StartDate', $currentInstance->StartDate()->ToTimezone($this->timezone));
        $this->Set('EndDate', $currentInstance->EndDate()->ToTimezone($this->timezone));
        $this->Set('ScheduleId', $this->reservationSeries->ScheduleId());
        $this->Set('ResourceName', $this->reservationSeries->Resource()->GetName());
        $resourceImage = ReservationEmailTemplateContext::BuildResourceImageUrl($this->reservationSeries->Resource()->GetImage());
        if ($resourceImage != null) {
            $this->Set('ResourceImage', $resourceImage);
        }

        $this->Set('Title', $this->reservationSeries->Title());
        $this->Set('Description', $this->reservationSeries->Description());

        $repeatVariables = $context->GetRecurrenceInstances($this->timezone);
        $this->Set('RepeatDates', $repeatVariables['RepeatDates']);
        $this->Set('RepeatRanges', $repeatVariables['RepeatRanges']);
        $this->Set('RequiresApproval', $this->reservationSeries->RequiresApproval());

        $this->Set('ReservationUrl', sprintf('%s?%s=%s', Pages::RESERVATION, QueryStringKeys::REFERENCE_NUMBER, $currentInstance->ReferenceNumber()));

        $icalUrl = sprintf('export/%s?%s=%s', Pages::CALENDAR_EXPORT, QueryStringKeys::REFERENCE_NUMBER, $currentInstance->ReferenceNumber());
        $this->Set('ICalUrl', $icalUrl);

        $googleDateFormat = Resources::GetInstance()->GetDateFormat('google');
        $googleCalendarUrl = sprintf(
            'https://www.google.com/calendar/event?action=TEMPLATE&text=%s&dates=%s/%s&ctz=%s&details=%s&location=%s&trp=false&sprop=&sprop=name:',
            urlencode($this->reservationSeries->Title()),
            $currentInstance->StartDate()->ToUtc()->Format($googleDateFormat),
            $currentInstance->EndDate()->ToUtc()->Format($googleDateFormat),
            $currentInstance->StartDate()->Timezone(),
            urlencode($this->reservationSeries->Description()),
            urlencode($this->reservationSeries->Resource()->GetName())
        );
        $this->Set('GoogleCalendarUrl', $googleCalendarUrl);

        $this->Set('ResourceNames', $context->ResourceNames());
        $this->Set('Resources', $context->Resources());
        $this->Set('Accessories', $this->reservationSeries->Accessories());

        $attributeValues = $context->ReservationAttributes();
        $this->Set('Attributes', $attributeValues);

        $createdBy = $context->CreatedBy();
        if ($createdBy != null) {
            $this->Set('CreatedBy', $createdBy);
        }

        $minimumAutoRelease = null;
        foreach ($this->reservationSeries->AllResources() as $resource) {
            if ($resource->IsCheckInEnabled()) {
                $this->Set('CheckInEnabled', true);
            }

            if ($resource->IsAutoReleased()) {
                if ($minimumAutoRelease == null || $resource->GetAutoReleaseMinutes() < $minimumAutoRelease) {
                    $minimumAutoRelease = $resource->GetAutoReleaseMinutes();
                }
            }
        }

        $this->PopulateIcsAttachment($currentInstance, $attributeValues);

        $this->Set('AutoReleaseMinutes', $minimumAutoRelease);
        $this->Set('ReferenceNumber', $currentInstance->ReferenceNumber());

        // resolvedParticipants/resolvedInvitees were populated in PopulateIcsAttachment() above.
        $this->Set('Participants', array_values($this->resolvedParticipants));
        $this->Set('ParticipatingGuests', $currentInstance->ParticipatingGuests());
        $this->Set('Invitees', array_values($this->resolvedInvitees));
        $this->Set('InvitedGuests', $currentInstance->InvitedGuests());

        $this->Set('CreditsCurrent', $currentInstance->GetCreditsRequired());
        $this->Set('CreditsTotal', $this->reservationSeries->GetCreditsRequired());
    }

    /**
     * @param Reservation $currentInstance
     * @param Attribute[] $attributeValues
     */
    protected function PopulateIcsAttachment($currentInstance, $attributeValues)
    {
        $rv = new ReservationItemView(
            $currentInstance->ReferenceNumber(),
            $currentInstance->StartDate()->ToUTC(),
            $currentInstance->EndDate()->ToUTC(),
            $this->reservationSeries->Resource()->GetName(),
            $this->reservationSeries->Resource()->GetResourceId(),
            $currentInstance->ReservationId(),
            null,
            $this->reservationSeries->Title(),
            $this->reservationSeries->Description(),
            $this->reservationSeries->ScheduleId(),
            $this->reservationOwner->FirstName(),
            $this->reservationOwner->LastName(),
            $this->reservationOwner->Id(),
            $this->reservationOwner->GetAttribute(UserAttribute::Phone),
            $this->reservationOwner->GetAttribute(UserAttribute::Organization),
            $this->reservationOwner->GetAttribute(UserAttribute::Position)
        );

        $ca = new CustomAttributes();
        /** @var LBAttribute $attribute */
        foreach ($attributeValues as $attribute) {
            $ca->Add($attribute->Id(), $attribute->Value());
        }
        $rv->Attributes = $ca;
        $rv->UserPreferences = $this->reservationOwner->GetPreferences();
        $rv->OwnerEmailAddress = $this->reservationOwner->EmailAddress();

        $rv->ParticipantIds = $currentInstance->Participants();
        foreach ($rv->ParticipantIds as $id) {
            $participant = $this->userRepository->GetById($id);
            $this->resolvedParticipants[$id] = $participant;
            if ($participant !== null) {
                $rv->ParticipantNames[$id] = (new FullName($participant->FirstName, $participant->LastName))->__toString();
                $rv->ParticipantEmails[$id] = $participant->EmailAddress;
            }
        }

        $rv->InviteeIds = $currentInstance->Invitees();
        foreach ($rv->InviteeIds as $id) {
            $invitee = $this->userRepository->GetById($id);
            $this->resolvedInvitees[$id] = $invitee;
            if ($invitee !== null) {
                $rv->InviteeNames[$id] = (new FullName($invitee->FirstName, $invitee->LastName))->__toString();
                $rv->InviteeEmails[$id] = $invitee->EmailAddress;
            }
        }

        $rv->ParticipatingGuests = $currentInstance->ParticipatingGuests();
        $rv->InvitedGuests = $currentInstance->InvitedGuests();

        // BookedBy() is null for a series loaded without an explicit UpdateBookedBy() call
        // (e.g. ReservationRepository::BuildSeries() never sets it); fall back to a session
        // wrapping the owner so the non-nullable UserSession parameter below never gets null.
        $currentUser = $this->reservationSeries->BookedBy() ?? new UserSession($this->reservationOwner->Id());
        $icsView = new iCalendarReservationView($rv, $currentUser, new NullPrivacyFilter());

        $method = $this->GetIcsMethod();
        if ($method === 'CANCEL') {
            $icsView->IsCancelled = true;

            $cancelledAttendee = $this->GetCancelledAttendee();
            if ($cancelledAttendee !== null && !in_array($cancelledAttendee['Email'], array_column($icsView->Attendees, 'Email'), true)) {
                $icsView->Attendees[] = $cancelledAttendee;
            }
        }

        $display = new CalendarExportDisplay();
        $icsContents = $display->Render([$icsView], null, $method);
        $this->AddStringAttachment($icsContents, 'reservation.ics', "text/calendar; charset=UTF-8; method={$method}");
    }

    /**
     * The iTip (RFC 5546) method this email's ICS attachment represents. PUBLISH by default,
     * since this recipient is the reservation owner or an arbitrary share recipient, never
     * one of the reservation's own attendees — a METHOD:REQUEST only makes sense addressed
     * to an ATTENDEE. Overridden to CANCEL (ReservationDeletedEmail) or REQUEST
     * (InviteeAddedEmail/GuestAddedEmail, whose recipient is the attendee being invited).
     *
     * @return string 'PUBLISH', 'CANCEL', or 'REQUEST'
     */
    protected function GetIcsMethod(): string
    {
        return 'PUBLISH';
    }

    /**
     * The attendee a CANCEL email's ICS attachment must list even though the reservation's
     * current (post-removal) attendee data no longer includes them — e.g. an invitee, guest,
     * or participant who was just removed. Without this, PopulateIcsAttachment() above builds
     * the ATTENDEE list from $currentInstance, which by definition already excludes anyone
     * removed in the change that triggered this CANCEL, leaving a RFC 5546 CANCEL with no
     * ATTENDEE identifying who it applies to. Null when there's no specific removed recipient
     * to re-add (e.g. the base ReservationDeletedEmail, sent to the owner).
     *
     * @return array{Email: string, Name: string}|null
     */
    protected function GetCancelledAttendee(): ?array
    {
        return null;
    }
}
