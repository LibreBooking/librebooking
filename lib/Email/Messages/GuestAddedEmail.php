<?php

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationEmailMessage.php');

class GuestAddedEmail extends ReservationEmailMessage
{
    /**
     * @var string
     */
    protected $guestEmail;

    public function __construct(User $reservationOwner, $guestEmail, ReservationSeries $reservationSeries, IAttributeRepository $attributeRepository, IUserRepository $userRepository)
    {
        parent::__construct($reservationOwner, $reservationSeries, $reservationOwner->Language(), $attributeRepository, $userRepository);

        $this->reservationOwner = $reservationOwner;
        $this->reservationSeries = $reservationSeries;
        $this->timezone = $reservationOwner->Timezone();
        $this->guestEmail = $guestEmail;
    }

    public function To()
    {
        return [new EmailAddress($this->guestEmail)];
    }

    public function Subject()
    {
        return $this->Translate('ParticipantAddedSubjectWithResource', [$this->reservationOwner->FullName(), $this->primaryResource->GetName()]);
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

        $this->Set('AcceptUrl', sprintf('%s?%s=%s&%s=%s&%s=%s', Pages::GUEST_INVITATION_RESPONSES, QueryStringKeys::REFERENCE_NUMBER, $currentInstance->ReferenceNumber(), QueryStringKeys::EMAIL, $this->guestEmail, QueryStringKeys::INVITATION_ACTION, InvitationAction::Accept));
        $this->Set('DeclineUrl', sprintf('%s?%s=%s&%s=%s&%s=%s', Pages::GUEST_INVITATION_RESPONSES, QueryStringKeys::REFERENCE_NUMBER, $currentInstance->ReferenceNumber(), QueryStringKeys::EMAIL, $this->guestEmail, QueryStringKeys::INVITATION_ACTION, InvitationAction::Decline));
    }

    /**
     * Unlike the other ReservationEmailMessage subclasses, this one is addressed to the
     * guest themselves — a genuine ATTENDEE being asked to Accept/Decline (see
     * PopulateTemplate() above) — so REQUEST is the correct method, not the PUBLISH default.
     */
    protected function GetIcsMethod(): string
    {
        return 'REQUEST';
    }
}

class GuestUpdatedEmail extends GuestAddedEmail
{
    public function Subject()
    {
        return $this->Translate('ParticipantUpdatedSubjectWithResource', [$this->reservationOwner->FullName(), $this->primaryResource->GetName()]);
    }

    public function PopulateTemplate()
    {
        parent::PopulateTemplate();
        $this->Set('Deleted', false);
        $this->Set('Updated', true);
    }
}
