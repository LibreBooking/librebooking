<?php

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationEmailMessage.php');

class ReservationSeriesEndingEmail extends EmailMessage
{
    /**
     * @var string
     */
    private $to;

    /**
     * @var ExistingReservationSeries
     */
    private $reservationSeries;

    /**
     * @var string
     */
    private $timezone;

    /**
     * @var Reservation
     */
    private $currentInstance;

    public function __construct(ExistingReservationSeries $reservationSeries, $language, $timezone, $to)
    {
        parent::__construct($language);

        $this->reservationSeries = $reservationSeries;
        $this->timezone = $timezone;
        $this->to = $to;
        $this->currentInstance = $this->reservationSeries->CurrentInstance();
    }

    public function To()
    {
        return [new EmailAddress($this->to)];
    }

    public function Subject()
    {
        return $this->Translate('ReservationSeriesEndingSubject', [
            $this->reservationSeries->Resource()->GetName(),
            $this->currentInstance->StartDate()->ToTimezone($this->timezone)->Format(Resources::GetInstance()->GetDateFormat('general_date'))]);
    }

    public function Body()
    {
        $this->Set('ResourceName', $this->reservationSeries->Resource()->GetName());
        $this->Set('Title', $this->reservationSeries->Title());
        $this->Set('Description', $this->reservationSeries->Description());
        $this->Set('StartDate', $this->currentInstance->StartDate()->ToTimezone($this->timezone));
        $this->Set('EndDate', $this->currentInstance->EndDate()->ToTimezone($this->timezone));
        $this->Set('ReservationUrl', sprintf('%s?%s=%s', Pages::RESERVATION, QueryStringKeys::REFERENCE_NUMBER, $this->currentInstance->ReferenceNumber()));

        return $this->FetchTemplate('ReservationSeriesEnding.tpl');
    }
}
