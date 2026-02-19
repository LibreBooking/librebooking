<?php

declare(strict_types=1);

class TestReservation extends Reservation
{
    /**
     * @param string $referenceNumber
     * @param DateRange $reservationDate
     * @param int $reservationId
     */
    public function __construct($referenceNumber = null, $reservationDate = null, $reservationId = null)
    {
        $this->startDate = new NullDate();
        $this->endDate = new NullDate();
        $this->checkinDate = new NullDate();
        $this->checkoutDate = new NullDate();
        $this->previousStart = new NullDate();
        $this->previousEnd = new NullDate();

        if (!empty($referenceNumber)) {
            $this->SetReferenceNumber($referenceNumber);
        } else {
            $this->SetReferenceNumber(uniqid('', true));
        }

        if ($reservationDate != null) {
            $this->SetReservationDate($reservationDate);
        } else {
            $this->SetReservationDate(new TestDateRange());
        }

        if ($reservationId == null) {
            $reservationId = uniqid();
        }
        $this->SetReservationId($reservationId);
    }

    public function WithAddedInvitees($inviteeIds)
    {
        $this->addedInvitees = $inviteeIds;
    }

    public function WithAddedParticipants($participantIds)
    {
        $this->addedParticipants = $participantIds;
    }

    public function WithExistingParticipants($participantIds)
    {
        $this->unchangedParticipants = $participantIds;
    }
}
