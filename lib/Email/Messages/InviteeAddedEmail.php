<?php

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationEmailMessage.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationDeletedEmail.php');
require_once(ROOT_DIR . 'Domain/Values/InvitationAction.php');

class InviteeAddedEmail extends ReservationEmailMessage
{
    /**
     * @var User
     */
    private $invitee;

    public function __construct(
        User $reservationOwner,
        User $invitee,
        ReservationSeries $reservationSeries,
        IAttributeRepository $attributeRepository,
        IUserRepository $userRepository
    ) {
        parent::__construct($reservationOwner, $reservationSeries, $invitee->Language(), $attributeRepository, $userRepository);

        $this->reservationOwner = $reservationOwner;
        $this->reservationSeries = $reservationSeries;
        $this->timezone = $invitee->Timezone();
        $this->invitee = $invitee;
    }

    public function To()
    {
        $address = $this->invitee->EmailAddress();
        $name = $this->invitee->FullName();

        return new EmailAddress($address, $name);
    }

    public function Subject()
    {
        return $this->Translate('InviteeAddedSubjectWithResource', [$this->reservationOwner->FullName(), $this->primaryResource->GetName()]);
    }

    public function From()
    {
        return new EmailAddress($this->reservationOwner->EmailAddress(), $this->reservationOwner->FullName());
    }

    public function GetTemplateName()
    {
        return 'ReservationInvitation.tpl';
    }

    protected function PopulateTemplate()
    {
        $currentInstance = $this->reservationSeries->CurrentInstance();
        parent::PopulateTemplate();

        $this->Set('AcceptUrl', $this->GetAcceptUrl($currentInstance, InvitationAction::Accept));
        $this->Set('DeclineUrl', $this->GetAcceptUrl($currentInstance, InvitationAction::Decline));
    }

    /**
     * @param Reservation $currentInstance
     * @param string $action
     * @return string
     */
    protected function GetAcceptUrl(Reservation $currentInstance, $action): string
    {
        return sprintf(
            '%s?%s=%s&%s=%s',
            Pages::INVITATION_RESPONSES,
            QueryStringKeys::REFERENCE_NUMBER,
            $currentInstance->ReferenceNumber(),
            QueryStringKeys::INVITATION_ACTION,
            $action
        );
    }

    /**
     * Unlike the other ReservationEmailMessage subclasses, this one is addressed to the
     * invitee themselves — a genuine ATTENDEE being asked to Accept/Decline (see
     * GetAcceptUrl() above) — so REQUEST is the correct method, not the PUBLISH default.
     */
    protected function GetIcsMethod(): string
    {
        return 'REQUEST';
    }
}

class InviteeUpdatedEmail extends InviteeAddedEmail
{
    public function Subject()
    {
        return $this->Translate('ParticipantUpdatedSubjectWithResource', [$this->reservationOwner->FullName(), $this->primaryResource->GetName()]);
    }

    public function GetTemplateName()
    {
        return 'ReservationInvitation.tpl';
    }
}

class InviteeRemovedEmail extends ReservationDeletedEmail
{
    /**
     * @var User
     */
    private $invitee;

    public function __construct(
        User $reservationOwner,
        User $invitee,
        ReservationSeries $reservationSeries,
        IAttributeRepository $attributeRepository,
        IUserRepository $userRepository
    ) {
        parent::__construct($reservationOwner, $reservationSeries, $invitee->Language(), $attributeRepository, $userRepository);

        $this->reservationOwner = $reservationOwner;
        $this->reservationSeries = $reservationSeries;
        $this->timezone = $invitee->Timezone();
        $this->invitee = $invitee;
    }

    public function To()
    {
        $address = $this->invitee->EmailAddress();
        $name = $this->invitee->FullName();

        return new EmailAddress($address, $name);
    }

    public function From()
    {
        return new EmailAddress($this->reservationOwner->EmailAddress(), $this->reservationOwner->FullName());
    }

    public function Subject()
    {
        return $this->Translate('ParticipantDeletedSubjectWithResource', [$this->reservationOwner->FullName(), $this->primaryResource->GetName()]);
    }

    public function GetTemplateName()
    {
        return 'ReservationDeleted.tpl';
    }

    protected function GetCancelledAttendee(): ?array
    {
        return ['Email' => $this->invitee->EmailAddress(), 'Name' => $this->invitee->FullName()];
    }
}
