<?php

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationEmailMessage.php');

class ReservationShareEmail extends ReservationEmailMessage
{
    /**
     * @var string
     */
    private $emailToShare;

    public function __construct(User $reservationOwner, $emailToShare, ReservationSeries $reservationSeries, IAttributeRepository $attributeRepository, IUserRepository $userRepository)
    {
        parent::__construct($reservationOwner, $reservationSeries, $reservationOwner->Language(), $attributeRepository, $userRepository);

        $this->reservationOwner = $reservationOwner;
        $this->reservationSeries = $reservationSeries;
        $this->timezone = $reservationOwner->Timezone();
        $this->emailToShare = $emailToShare;
    }

    public function To()
    {
        return [new EmailAddress($this->emailToShare)];
    }

    protected function GetIcsAttendeeParams(): array
    {
        // a share only informs the recipient
        return ['ROLE' => 'NON-PARTICIPANT', 'PARTSTAT' => 'NEEDS-ACTION'];
    }

    public function Subject()
    {
        return $this->Translate('ReservationShareSubject', [$this->reservationSeries->BookedBy()->FullName(), $this->primaryResource->GetName()]);
    }

    public function From()
    {
        return new EmailAddress($this->reservationOwner->EmailAddress(), $this->reservationOwner->FullName());
    }

    public function GetTemplateName()
    {
        return 'ReservationCreated.tpl';
    }
}
