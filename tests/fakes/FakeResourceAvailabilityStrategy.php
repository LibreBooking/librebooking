<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Reservation/ResourceAvailability.php');

class FakeResourceAvailabilityStrategy implements IResourceAvailabilityStrategy
{
    /**
     * @var IReservedItemView[]
     */
    public $_ReservedItems = [];
    /**
     * @var Date|null
     */
    public $_Start;
    /**
     * @var Date|null
     */
    public $_End;

    /**
     * @param Date $startDate
     * @param Date $endDate
     * @param int[] $resourceIds
     * @return array|IReservedItemView[]
     */
    public function GetItemsBetween(Date $startDate, Date $endDate, $resourceIds)
    {
        $this->_Start = $startDate;
        $this->_End = $endDate;
        return $this->_ReservedItems;
    }
}
