<?php

require_once(ROOT_DIR . 'lib/Application/Schedule/SlotLabelFactory.php');

class ReservationSlot implements IReservationSlot
{
    /**
     * @var Date
     */
    protected $_begin;

    /**
     * @var Date
     */
    protected $_end;

    /**
     * @var Date
     */
    protected $_displayDate;

    /**
     * @var int
     */
    protected $_periodSpan;

    /**
     * @var ReservationItemView
     */
    private $_reservation;

    /**
     * @var string
     */
    protected $_beginSlotId;

    /**
     * @var string
     */
    protected $_endSlotId;

    /**
     * @var SchedulePeriod
     */
    protected $_beginPeriod;

    /**
     * @var SchedulePeriod
     */
    protected $_endPeriod;

    /**
     * @param SchedulePeriod $begin
     * @param SchedulePeriod $end
     * @param Date $displayDate
     * @param int $periodSpan
     * @param IReservedItemView $reservation
     */
    public function __construct(
        SchedulePeriod $begin,
        SchedulePeriod $end,
        Date $displayDate,
        $periodSpan,
        IReservedItemView $reservation
    ) {
        $this->_reservation = $reservation;
        $this->_begin = $begin->BeginDate();
        $this->_displayDate = $displayDate;
        $this->_end = $end->EndDate();
        $this->_periodSpan = $periodSpan;

        $this->_beginSlotId = $begin->Id();
        $this->_endSlotId = $end->Id();

        $this->_beginPeriod = $begin;
        $this->_endPeriod = $end;
    }

    /**
     * @return Time
     */
    public function Begin()
    {
        return $this->_begin->GetTime();
    }

    /**
     * @return Date
     */
    public function BeginDate()
    {
        return $this->_begin;
    }

    /**
     * @return Time
     */
    public function End()
    {
        return $this->_end->GetTime();
    }

    /**
     * @return Date
     */
    public function EndDate()
    {
        return $this->_end;
    }

    /**
     * @return Date
     */
    public function Date()
    {
        return $this->_displayDate;
    }

    /**
     * @return int
     */
    public function PeriodSpan()
    {
        return $this->_periodSpan;
    }

    /**
     * @param SlotLabelFactory|null $factory
     * @return string
     */
    public function Label($factory = null)
    {
        if (empty($factory)) {
            return SlotLabelFactory::Create($this->_reservation);
        }
        return $factory->Format($this->_reservation);
    }

    public function IsReservable()
    {
        return false;
    }

    public function IsReserved()
    {
        return true;
    }

    public function IsPending()
    {
        return $this->_reservation->RequiresApproval;
    }

    public function IsPastDate(Date $date)
    {
        return $this->_displayDate->SetTime($this->Begin())->LessThan($date);
    }

    public function RequiresCheckin()
    {
        return $this->_reservation->RequiresCheckin();
    }

    public function AutoReleaseMinutes()
    {
        return empty($this->_reservation->AutoReleaseMinutes) ? 0 : $this->_reservation->AutoReleaseMinutes;
    }

    public function AutoReleaseMinutesRemaining()
    {
        $min = $this->AutoReleaseMinutes();
        if (empty($min)) {
            return 0;
        }
        $maxCheckinTime = $this->BeginDate()->AddMinutes($min);
        $d = DateDiff::BetweenDates(Date::Now(), $maxCheckinTime);
        return $d->Minutes();
    }

    public function ToTimezone($timezone)
    {
        return new ReservationSlot($this->_beginPeriod->ToTimezone($timezone), $this->_endPeriod->ToTimezone($timezone), $this->Date(), $this->PeriodSpan(), $this->_reservation);
    }

    public function Id()
    {
        return $this->_reservation->ReferenceNumber;
    }

    public function IsOwnedBy(UserSession $user)
    {
        return $this->_reservation->UserId == $user->UserId;
    }

    public function IsParticipating(UserSession $session)
    {
        return $this->_reservation->IsUserParticipating($session->UserId) || $this->_reservation->IsUserInvited($session->UserId);
    }

    public function __toString()
    {
        return sprintf("Start: %s, End: %s, Span: %s", $this->Begin(), $this->End(), $this->PeriodSpan());
    }

    public function BeginSlotId()
    {
        return $this->_beginSlotId;
    }

    public function EndSlotId()
    {
        return $this->_beginSlotId;
    }

    public function HasCustomColor()
    {
        $color = $this->Color();

        return !empty($color);
    }

    public function Color()
    {
        return $this->_reservation->GetColor();
    }

    public function TextColor()
    {
        return $this->_reservation->GetTextColor();
    }

    public function BorderColor()
    {
        return $this->_reservation->GetBorderColor();
    }

    public function GetAdditonalCSSClasses()
    {
        return $this->_reservation->GetAdditonalCSSClasses() ?? [];
    }

    /**
     * @return ReservationItemView
     */
    public function Reservation()
    {
        return $this->_reservation;
    }

    public function CollidesWith(Date $date)
    {
        return $this->_reservation->CollidesWith($date);
    }

    public function OwnerId()
    {
        return $this->_reservation->UserId;
    }

    public function OwnerGroupIds()
    {
        return $this->_reservation->OwnerGroupIds();
    }

    public function IsNew()
    {
        $newMinutes = Configuration::Instance()->GetKey(ConfigKeys::SCHEDULE_UPDATE_HIGHLIGHT_MINUTES, new IntConverter());
        $modifiedDate = $this->_reservation->ModifiedDate;
        return
            ($newMinutes > 0) &&
            (empty($modifiedDate)) &&
            ($this->_reservation->CreatedDate->AddMinutes($newMinutes)->GreaterThanOrEqual(Date::Now()));
    }

    public function IsUpdated()
    {
        $newMinutes = Configuration::Instance()->GetKey(ConfigKeys::SCHEDULE_UPDATE_HIGHLIGHT_MINUTES, new IntConverter());
        $modifiedDate = $this->_reservation->ModifiedDate;
        return
            ($newMinutes > 0) &&
            (!empty($modifiedDate)) &&
            ($this->_reservation->ModifiedDate->AddMinutes($newMinutes)->GreaterThanOrEqual(Date::Now()));
    }
}
