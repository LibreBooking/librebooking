<?php

class FakeDailyLayout implements IDailyLayout
{
    public $_Timezone;
    private $_Layouts = [];

    /**
     * @param Date $date
     * @param int $resourceId
     * @return array|IReservationSlot[]
     */
    public function GetLayout(Date $date, $resourceId)
    {
        return $this->_Layouts[$date->GetDate()->Timestamp() . $resourceId];
    }

    /**
     * @param Date $date
     * @return bool
     */
    public function IsDateReservable(Date $date)
    {
        return true;
    }

    /**
     * @param Date $displayDate
     * @return string[]
     */
    public function GetLabels(Date $displayDate)
    {
        return [];
    }

    /**
     * @param Date $displayDate
     * @return mixed
     */
    public function GetPeriods(Date $displayDate)
    {
        // TODO: Implement GetPeriods() method.
    }

    /**
     * @param Date $date
     * @param int $resourceId
     * @return DailyReservationSummary
     */
    public function GetSummary(Date $date, $resourceId)
    {
        throw new LogicException('GetSummary() not implemented in FakeDailyLayout');
    }

    /**
     * @param Date $date
     * @param int $resourceId
     * @param IReservationSlot[] $reservationSlots
     */
    public function _SetLayout(Date $date, $resourceId, $reservationSlots)
    {
        $this->_Layouts[$date->GetDate()->Timestamp() . $resourceId] = $reservationSlots;
    }

    public function Timezone()
    {
        return $this->_Timezone;
    }
}
