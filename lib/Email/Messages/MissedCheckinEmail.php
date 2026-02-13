<?php

require_once(ROOT_DIR . 'lib/Email/namespace.php');
require_once(ROOT_DIR . 'Pages/Pages.php');

class MissedCheckinEmail extends EmailMessage
{
    /**
     * @var ReservationItemView
     */
    private $reservation;

    public function __construct(ReservationItemView $reservation)
    {
        $this->reservation = $reservation;
        parent::__construct($reservation->OwnerLanguage);
    }

    /**
     * @return array|EmailAddress[]|EmailAddress
     */
    public function To()
    {
        return new EmailAddress($this->reservation->OwnerEmailAddress, new FullName($this->reservation->OwnerFirstName, $this->reservation->OwnerLastName));
    }

    public function Subject()
    {
        return $this->Translate('MissedCheckinEmailSubject', [$this->reservation->ResourceName]);
    }

    public function Body()
    {
        $this->Set('StartDate', $this->reservation->StartDate->ToTimezone($this->reservation->OwnerTimezone));
        $this->Set('EndDate', $this->reservation->EndDate->ToTimezone($this->reservation->OwnerTimezone));
        $this->Set('ResourceName', $this->reservation->ResourceName);
        $this->Set('Title', $this->reservation->Title);
        $this->Set('Description', $this->reservation->Description);
        $this->Set('IsAutoRelease', $this->reservation->AutoReleaseMinutes != null);
        $this->Set('AutoReleaseTime', $this->reservation->StartDate->AddMinutes($this->reservation->AutoReleaseMinutes));
        $this->Set('ReservationUrl', sprintf(
            '%s?%s=%s',
            Pages::RESERVATION,
            QueryStringKeys::REFERENCE_NUMBER,
            $this->reservation->ReferenceNumber
        ));

        return $this->FetchTemplate('MissedCheckinEmail.tpl');
    }
}
