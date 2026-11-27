<?php

/**
 * Calculates how many units of each accessory are already reserved during a
 * requested time range.
 *
 * The reserved quantity is the peak number of units in use at the same time
 * within the range, not the sum of every overlapping reservation. Two
 * reservations that touch the range but never overlap each other do not use
 * the accessory concurrently.
 */
class AccessoryAggregation
{
    /**
     * Sort a reservation's end before another's start at the same instant,
     * so back-to-back reservations are not counted as concurrent.
     */
    private const int END_BEFORE_START = -1;
    private const int START_AFTER_END = 1;

    private $knownAccessoryIds = [];

    /**
     * @var \DateRange
     */
    private $duration;

    /**
     * @var array<int|string, array<string, true>> accessory id => reference numbers already added
     */
    private $addedReservations = [];

    /**
     * @var array<int|string, list<array{int, int, int}>> accessory id => [timestamp, order, quantity delta]
     */
    private $accessoryEvents = [];

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
        $referenceNumber = (string)$accessoryReservation->GetReferenceNumber();

        if (isset($this->addedReservations[$accessoryId][$referenceNumber])) {
            return;
        }

        $this->addedReservations[$accessoryId][$referenceNumber] = true;

        // Every reservation added here overlaps the requested range, so any
        // two that overlap each other also overlap inside the range (intervals
        // on a line that pairwise intersect share a common point). No clipping
        // to the range is needed to find the peak within it.
        $quantity = (int)$accessoryReservation->QuantityReserved();
        $this->accessoryEvents[$accessoryId][] = [$accessoryReservation->GetStartDate()->Timestamp(), self::START_AFTER_END, $quantity];
        $this->accessoryEvents[$accessoryId][] = [$accessoryReservation->GetEndDate()->Timestamp(), self::END_BEFORE_START, -$quantity];
    }

    /**
     * @param int $accessoryId
     * @return int the peak quantity in use at any moment within the range
     */
    public function GetQuantity($accessoryId)
    {
        if (!array_key_exists($accessoryId, $this->accessoryEvents)) {
            return 0;
        }

        $events = $this->accessoryEvents[$accessoryId];
        usort(
            array: $events,
            callback: static fn (array $a, array $b): int => [$a[0], $a[1]] <=> [$b[0], $b[1]]
        );

        $inUse = 0;
        $peak = 0;
        foreach ($events as [, , $delta]) {
            $inUse += $delta;
            $peak = max($peak, $inUse);
        }

        return $peak;
    }
}
