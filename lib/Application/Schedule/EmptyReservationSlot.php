<?php

require_once(ROOT_DIR . 'Domain/Values/ReservationStartTimeConstraint.php');

class EmptyReservationSlot implements IReservationSlot
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
    protected $_date;

    /**
     * @var bool $_isReservable
     */
    protected $_isReservable;

    /**
     * @var int
     */
    protected $_periodSpan;

    protected $_beginDisplayTime;
    protected $_endDisplayTime;

    protected $_beginSlotId;
    protected $_endSlotId;

    protected $_beginPeriod;
    protected $_endPeriod;

    public function __construct(SchedulePeriod $begin, SchedulePeriod $end, Date $displayDate, $isReservable)
    {
        $this->_begin = $begin->BeginDate();
        $this->_end = $end->EndDate();
        $this->_date = $displayDate;
        $this->_isReservable = $isReservable;

        $this->_beginDisplayTime = $this->_begin->GetTime();
        if (!$this->_begin->DateEquals($displayDate)) {
            $this->_beginDisplayTime = $displayDate->GetDate()->GetTime();
        }

        $this->_endDisplayTime = $this->_end->GetTime();
        if (!$this->_end->DateEquals($displayDate)) {
            $this->_endDisplayTime = $displayDate->GetDate()->GetTime();
        }

        $this->_beginSlotId = $begin->Id();
        $this->_endSlotId = $end->Id();

        $this->_beginPeriod = $begin;
        $this->_endPeriod = $end;
    }

    public function Begin()
    {
        return $this->_beginDisplayTime;
    }

    public function BeginDate()
    {
        return $this->_begin;
    }


    public function End()
    {
        return $this->_endDisplayTime;
    }

    public function EndDate()
    {
        return $this->_end;
    }

    public function Date()
    {
        return $this->_date;
    }

    public function PeriodSpan()
    {
        return 1;
    }

    public function Label()
    {
        return '';
    }

    public function IsReservable()
    {
        return $this->_isReservable;
    }

    public function IsReserved()
    {
        return false;
    }

    public function IsPending()
    {
        return false;
    }

    public function IsPastDate(Date $date)
    {
        return ReservationPastTimeConstraint::IsPast($this->BeginDate(), $this->EndDate());
    }

    public function ToTimezone($timezone)
    {
        return new EmptyReservationSlot(
            $this->_beginPeriod->ToTimezone($timezone),
            $this->_endPeriod->ToTimezone($timezone),
            $this->Date(),
            $this->_isReservable
        );
    }

    public function IsOwnedBy(UserSession $session)
    {
        return false;
    }

    public function IsParticipating(UserSession $session)
    {
        return false;
    }

    public function BeginSlotId()
    {
        return $this->_beginSlotId;
    }

    public function EndSlotId()
    {
        return $this->_endSlotId;
    }

    public function Color()
    {
        return null;
    }

    public function HasCustomColor()
    {
        return false;
    }

    public function TextColor()
    {
        return null;
    }

    public function CollidesWith(Date $date)
    {
        if ($this->IsReservable()) {
            return false;
        }

        $range = new DateRange($this->_begin, $this->_end);
        return $range->Contains($date, false);
    }

    public function RequiresCheckin()
    {
        return false;
    }

    public function AutoReleaseMinutes()
    {
        return null;
    }

    public function AutoReleaseMinutesRemaining()
    {
        return null;
    }

    public function Id()
    {
        return '';
    }

    public function OwnerId()
    {
        return null;
    }

    public function OwnerGroupIds()
    {
        return [];
    }

    public function IsNew()
    {
        return false;
    }

    public function IsUpdated()
    {
        return false;
    }
}
