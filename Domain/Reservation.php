<?php

require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'Domain/Values/FullName.php');

class Reservation
{
    /**
     * @var string
     */
    protected $referenceNumber;

    /**
     * @return string
     */
    public function ReferenceNumber()
    {
        return $this->referenceNumber;
    }

    /**
     * @var Date
     */
    protected $startDate;

    /**
     * @return Date
     */
    public function StartDate()
    {
        return $this->startDate;
    }

    /**
     * @var Date
     */
    protected $endDate;

    /**
     * @return Date
     */
    public function EndDate()
    {
        return $this->endDate;
    }

    /**
     * @return DateRange
     */
    public function Duration()
    {
        return new DateRange($this->StartDate(), $this->EndDate());
    }

    /**
     * @var Date
     */
    protected $previousStart;

    /**
     * @return Date
     */
    public function PreviousStartDate()
    {
        return $this->previousStart;
    }

    /**
     * @var Date
     */
    protected $previousEnd;

    /**
     * @var int
     */
    protected $creditsRequired;


    /**
     * @return Date
     */
    public function PreviousEndDate()
    {
        return $this->previousEnd == null ? new NullDate() : $this->previousEnd;
    }

    protected $reservationId;

    public function ReservationId()
    {
        return $this->reservationId;
    }

    /**
     * @var array|int[]
     */
    private $_participantIds = [];

    /**
     * @var array|int[]
     */
    protected $addedParticipants = [];

    /**
     * @var array|int[]
     */
    protected $removedParticipants = [];

    /**
     * @var array|int[]
     */
    protected $unchangedParticipants = [];

    /**
     * @var int[]
     */
    private $_inviteeIds = [];

    /**
     * @var int[]
     */
    protected $addedInvitees = [];

    /**
     * @var int[]
     */
    protected $removedInvitees = [];

    /**
     * @var int[]
     */
    protected $unchangedInvitees = [];

    /**
     * @var string[]
     */
    private $_invitedGuests = [];

    /**
     * @var string[]
     */
    protected $addedInvitedGuests = [];

    /**
     * @var string[]
     */
    protected $removedInvitedGuests = [];

    /**
     * @var string[]
     */
    protected $unchangedInvitedGuests = [];

    /**
     * @var string[]
     */
    private $_participatingGuests = [];

    /**
     * @var string[]
     */
    protected $addedParticipatingGuests = [];

    /**
     * @var string[]
     */
    protected $removedParticipatingGuests = [];

    /**
     * @var string[]
     */
    protected $unchangedParticipatingGuests = [];

    /**
     * @var Date|null
     */
    protected $checkinDate;

    /**
     * @var Date|null
     */
    protected $checkoutDate;

    /**
     * @var bool
     */
    protected $reservationDatesChanged = false;

    /**
     * @var ReservationSeries
     */
    public $series;

    public function __construct(ReservationSeries $reservationSeries, DateRange $reservationDate, $reservationId = null, $referenceNumber = null)
    {
        $this->series = $reservationSeries;

        $this->startDate = $reservationDate->GetBegin();
        $this->endDate = $reservationDate->GetEnd();

        $this->SetReferenceNumber($referenceNumber);

        if (!empty($reservationId)) {
            $this->SetReservationId($reservationId);
        }

        if (empty($referenceNumber)) {
            $this->SetReferenceNumber(ReferenceNumberGenerator::Generate());
        }

        $this->checkinDate = new NullDate();
        $this->checkoutDate = new NullDate();
        $this->previousStart = new NullDate();
        $this->previousEnd = new NullDate();
    }

    public function SetReservationId($reservationId)
    {
        $this->reservationId = $reservationId;
    }

    public function SetReferenceNumber($referenceNumber)
    {
        $this->referenceNumber = $referenceNumber;
    }

    public function SetReservationDate(DateRange $reservationDate)
    {
        $this->previousStart = $this->StartDate();
        $this->previousEnd = $this->EndDate();

        if (!$this->startDate->Equals($reservationDate->GetBegin()) || !$this->endDate->Equals($reservationDate->GetEnd())) {
            $this->reservationDatesChanged = true;
        }

        $this->startDate = $reservationDate->GetBegin();
        $this->endDate = $reservationDate->GetEnd();

        if ($this->previousStart != null && !($this->previousStart->Equals($reservationDate->GetBegin())) && $this->CheckinDate()->LessThan($this->startDate)) {
            $this->WithCheckin(new NullDate(), $this->CheckoutDate());
        }
    }

    /**
     * @internal
     * @param array|int[] $participantIds
     * @return void
     */
    public function WithParticipants($participantIds)
    {
        $this->_participantIds = $participantIds;
        $this->unchangedParticipants = $participantIds;
    }

    /**
     * @param int $participantId
     */
    public function WithParticipant($participantId)
    {
        $this->_participantIds[] = $participantId;
        $this->unchangedParticipants[] = $participantId;
    }

    /**
     * @internal
     * @param array|int[] $inviteeIds
     * @return void
     */
    public function WithInvitees($inviteeIds)
    {
        $this->_inviteeIds = $inviteeIds;
        $this->unchangedInvitees = $inviteeIds;
    }

    /**
     * @param int $inviteeId
     */
    public function WithInvitee($inviteeId)
    {
        $this->_inviteeIds[] = $inviteeId;
        $this->unchangedInvitees[] = $inviteeId;
    }

    /**
     * @param array|int[] $participantIds
     * @return int
     */
    public function ChangeParticipants($participantIds)
    {
        $diff = new ArrayDiff($this->_participantIds, $participantIds);

        $this->addedParticipants = $diff->GetAddedToArray1();
        $this->removedParticipants = $diff->GetRemovedFromArray1();
        $this->unchangedParticipants = $diff->GetUnchangedInArray1();

        $this->_participantIds = $participantIds;

        return count($this->addedParticipants) + count($this->removedParticipants);
    }

    /**
     * @return array|int[]
     */
    public function Participants()
    {
        return $this->_participantIds;
    }

    /**
     * @return array|int[]
     */
    public function AddedParticipants()
    {
        return $this->addedParticipants;
    }

    /**
     * @return array|int[]
     */
    public function RemovedParticipants()
    {
        return $this->removedParticipants;
    }

    /**
     * @return array|int[]
     */
    public function UnchangedParticipants()
    {
        return $this->unchangedParticipants;
    }

    /**
     * @param string $guest
     */
    public function WithInvitedGuest($guest)
    {
        $this->_invitedGuests[] = $guest;
        $this->unchangedInvitedGuests[] = $guest;
    }

    /**
     * @param string $guest
     */
    public function WithParticipatingGuest($guest)
    {
        $this->_participatingGuests[] = $guest;
        $this->unchangedParticipatingGuests[] = $guest;
    }

    /**
     * @return array|int[]
     */
    public function Invitees()
    {
        return $this->_inviteeIds;
    }

    /**
     * @return array|int[]
     */
    public function AddedInvitees()
    {
        return $this->addedInvitees;
    }

    /**
     * @return array|int[]
     */
    public function RemovedInvitees()
    {
        return $this->removedInvitees;
    }

    /**
     * @return array|int[]
     */
    public function UnchangedInvitees()
    {
        return $this->unchangedInvitees;
    }

    /**
     * @param array|int[] $inviteeIds
     * @return int
     */
    public function ChangeInvitees($inviteeIds)
    {
        $diff = new ArrayDiff($this->_inviteeIds, $inviteeIds);

        $this->addedInvitees = $diff->GetAddedToArray1();
        $this->removedInvitees = $diff->GetRemovedFromArray1();
        $this->unchangedInvitees = $diff->GetUnchangedInArray1();

        $this->_inviteeIds = $inviteeIds;

        return count($this->addedInvitees) + count($this->removedInvitees);
    }

    /**
     * @param string[] $invitedGuests
     * @return int
     */
    public function ChangeInvitedGuests($invitedGuests)
    {
        $inviteeDiff = new ArrayDiff($this->_invitedGuests, $invitedGuests);

        $this->addedInvitedGuests = $inviteeDiff->GetAddedToArray1();
        $this->removedInvitedGuests = $inviteeDiff->GetRemovedFromArray1();
        $this->unchangedInvitedGuests = $inviteeDiff->GetUnchangedInArray1();

        $this->_invitedGuests = $invitedGuests;

        return count($this->addedInvitedGuests) + count($this->removedInvitedGuests);
    }

    /**
     * @param string $email
     */
    public function RemoveInvitedGuest($email)
    {
        $newInvitees = [];

        foreach ($this->_invitedGuests as $invitee) {
            if ($invitee != $email) {
                $newInvitees[] = $invitee;
            }
        }

        $this->ChangeInvitedGuests($newInvitees);
    }

    /**
     * @param string[] $participatingGuests
     * @return int
     */
    public function ChangeParticipatingGuests($participatingGuests)
    {
        $participantDiff = new ArrayDiff($this->_participatingGuests, $participatingGuests);

        $this->addedParticipatingGuests = $participantDiff->GetAddedToArray1();
        $this->removedParticipatingGuests = $participantDiff->GetRemovedFromArray1();
        $this->unchangedParticipatingGuests = $participantDiff->GetUnchangedInArray1();

        $this->_participatingGuests = $participatingGuests;

        return count($this->addedParticipatingGuests) + count($this->removedParticipatingGuests);
    }

    /**
     * @return string[]
     */
    public function AddedInvitedGuests()
    {
        return $this->addedInvitedGuests;
    }

    /**
     * @return string[]
     */
    public function RemovedInvitedGuests()
    {
        return $this->removedInvitedGuests;
    }

    /**
     * @return string[]
     */
    public function UnchangedInvitedGuests()
    {
        return $this->unchangedInvitedGuests;
    }

    /**
     * @return string[]
     */
    public function AddedParticipatingGuests()
    {
        return $this->addedParticipatingGuests;
    }

    /**
     * @return string[]
     */
    public function RemovedParticipatingGuests()
    {
        return $this->removedParticipatingGuests;
    }

    /**
     * @return string[]
     */
    public function UnchangedParticipatingGuests()
    {
        return $this->unchangedParticipatingGuests;
    }

    /**
     * @return string[]
     */
    public function ParticipatingGuests()
    {
        return $this->_participatingGuests;
    }

    /**
     * @return string[]
     */
    public function InvitedGuests()
    {
        return $this->_invitedGuests;
    }

    /**
     * @return bool
     */
    public function IsNew()
    {
        return $this->ReservationId() == null;
    }

    /**
     * @param int $inviteeId
     * @return bool whether the invitation was accepted
     */
    public function AcceptInvitation($inviteeId)
    {
        if (in_array($inviteeId, $this->_inviteeIds)) {
            $this->addedParticipants[] = $inviteeId;
            $this->_participantIds[] = $inviteeId;
            $this->removedInvitees[] = $inviteeId;

            return true;
        }

        return false;
    }

    /**
     * @param int $userId
     * @return bool whether the user joined
     */
    public function JoinReservation($userId)
    {
        if (in_array($userId, $this->_participantIds)) {
            // already participating
            return false;
        }

        if (in_array($userId, $this->_inviteeIds)) {
            $this->removedInvitees[] = $userId;
        }

        $this->addedParticipants[] = $userId;
        $this->_participantIds[] = $userId;

        return true;
    }

    /**
     * @param int $inviteeId
     * @return bool whether the invitation was declined
     */
    public function DeclineInvitation($inviteeId)
    {
        if (in_array($inviteeId, $this->_inviteeIds)) {
            $this->removedInvitees[] = $inviteeId;
            return true;
        }

        return false;
    }

    /**
     * @param string $email
     * @return bool whether the invitation was accepted
     */
    public function AcceptGuestInvitation($email)
    {
        if (in_array($email, $this->_invitedGuests)) {
            $this->addedParticipatingGuests[] = $email;
            $this->_participatingGuests[] = $email;
            $this->removedInvitedGuests[] = $email;

            return true;
        }

        return false;
    }

    /**
     * @param string $email
     * @return bool whether the invitation was declined
     */
    public function DeclineGuestInvitation($email)
    {
        if (in_array($email, $this->_invitedGuests)) {
            $this->removedInvitedGuests[] = $email;
            return true;
        }

        return false;
    }

    /**
     * @param int $participantId
     * @return bool whether the participant was removed
     */
    public function CancelParticipation($participantId)
    {
        if (in_array($participantId, $this->_participantIds)) {
            $this->removedParticipants[] = $participantId;
            $index = array_search($participantId, $this->_participantIds);
            if ($index !== false) {
                array_splice($this->_participantIds, $index, 1);
            }
            return true;
        }

        return false;
    }

    /**
     * @return Date|null
     */
    public function CheckinDate()
    {
        return $this->checkinDate == null ? new NullDate() : $this->checkinDate;
    }

    /**
     * @return bool
     */
    public function IsCheckedIn()
    {
        return $this->checkinDate != null && $this->checkinDate->ToString() != '';
    }

    public function Checkin()
    {
        $this->checkinDate = Date::Now();
    }

    /**
     * @return Date|null
     */
    public function CheckoutDate()
    {
        return $this->checkoutDate == null ? new NullDate() : $this->checkoutDate;
    }

    /**
     * @return bool
     */
    public function IsCheckedOut()
    {
        return $this->checkoutDate != null && $this->checkoutDate->ToString() != '';
    }

    public function Checkout()
    {
        $this->previousEnd = $this->endDate;
        $this->endDate = Date::Now();
        $this->checkoutDate = Date::Now();
    }

    public static function Compare(Reservation $res1, Reservation $res2)
    {
        return $res1->StartDate()->Compare($res2->StartDate());
    }

    /**
     * @param Date $checkinDate
     * @param Date $checkoutDate
     */
    public function WithCheckin(Date $checkinDate, Date $checkoutDate)
    {
        $this->checkinDate = $checkinDate;
        $this->checkoutDate = $checkoutDate;
    }

    /**
     * @return bool
     */
    public function WereDatesChanged()
    {
        return $this->reservationDatesChanged || empty($this->reservationId);
    }

    public function GetCreditsRequired()
    {
        if ($this->EndDate()->GreaterThan(Date::Now())) {
            return $this->creditsRequired;
        }
        return 0;
    }

    public function SetCreditsRequired($creditsRequired)
    {
        $this->creditsRequired = $creditsRequired;
    }

    private $creditsConsumed;

    public function WithCreditsConsumed($credits)
    {
        $this->creditsConsumed = $credits;
    }

    public function GetCreditsConsumed()
    {
        if ($this->EndDate()->GreaterThan(Date::Now())) {
            return empty($this->creditsConsumed) ? 0 : $this->creditsConsumed;
        }
        return 0;
    }
}

class ReferenceNumberGenerator
{
    /**
     * Just for testing
     * @var string|null
     */
    public static $__referenceNumber = null;

    public static function Generate()
    {
        if (self::$__referenceNumber == null) {
            return str_replace('.', '', uniqid('', true));
        }

        return self::$__referenceNumber;
    }
}
