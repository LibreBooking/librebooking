<?php

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationEmailMessage.php');

class GuestDeletedEmail extends ReservationDeletedEmail
{
    /**
     * @var string
     */
    private $guestEmail;

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
        return new EmailAddress($this->guestEmail);
    }

    public function Subject()
    {
        return $this->Translate('ParticipantDeletedSubjectWithResource', [$this->reservationOwner->FullName(), $this->primaryResource->GetName()]);
    }

    public function From()
    {
        return new EmailAddress($this->reservationOwner->EmailAddress(), $this->reservationOwner->FullName());
    }

    public function GetTemplateName()
    {
        return 'ReservationInvitation.tpl';
    }

    protected function GetCancelledAttendee(): ?array
    {
        return ['Email' => $this->guestEmail, 'Name' => $this->guestEmail];
    }
}
