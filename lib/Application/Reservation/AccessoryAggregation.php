<?php

class AccessoryAggregation
{
    private $knownAccessoryIds = [];

    /**
     * @var \DateRange
     */
    private $duration;

    /**
     * @var string[]
     */
    private $addedReservations = [];

    private $accessoryQuantity = [];

    /**
     * @param AccessoryToCheck[]|Accessory[] $accessories only their ids are used
     * @param DateRange $duration
     */
    public function __construct($accessories, $duration)
    {
        foreach ($accessories as $a) {
            $this->knownAccessoryIds[$a->GetId()] = 1;
        }

        $this->duration = $duration;
    }

    /**
     * @param AccessoryReservation $accessoryReservation
     */
    public function Add(AccessoryReservation $accessoryReservation)
    {
        if ($accessoryReservation->GetStartDate()->GreaterThanOrEqual($this->duration->GetEnd()) || $accessoryReservation->GetEndDate()->LessThanOrEqual($this->duration->GetBegin())) {
            return;
        }

        $accessoryId = $accessoryReservation->GetAccessoryId();

        $key = $accessoryReservation->GetReferenceNumber() . $accessoryId;

        if (array_key_exists($key, $this->addedReservations)) {
            return;
        }

        $this->addedReservations[$key] = true;

        if (array_key_exists($accessoryId, $this->accessoryQuantity)) {
            $this->accessoryQuantity[$accessoryId] += $accessoryReservation->QuantityReserved();
        } else {
            $this->accessoryQuantity[$accessoryId] = $accessoryReservation->QuantityReserved();
        }
    }

    /**
     * @param int $accessoryId
     * @return int
     */
    public function GetQuantity($accessoryId)
    {
        if (array_key_exists($accessoryId, $this->accessoryQuantity)) {
            return $this->accessoryQuantity[$accessoryId];
        }
        return 0;
    }
}
