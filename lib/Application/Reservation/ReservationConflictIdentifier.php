<?php

interface IReservationConflictIdentifier
{
    /**
     * @param $reservationSeries ReservationSeries
     * @return ReservationConflictResult
     */
    public function GetConflicts($reservationSeries);
}

class IdentifiedConflict
{
    /**
     * @var Reservation
     */
    public $Reservation;
    /**
     * @var IReservedItemView
     */
    public $Conflict;

    /**
     * @param Reservation $reservation
     * @param IReservedItemView $conflict
     */
    public function __construct(Reservation $reservation, IReservedItemView $conflict)
    {
        $this->Reservation = $reservation;
        $this->Conflict = $conflict;
    }
}

class ReservationConflictIdentifier implements IReservationConflictIdentifier
{
    /**
     * @var IResourceAvailabilityStrategy
     */
    private $strategy;

    public function __construct(IResourceAvailabilityStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param $reservationSeries ReservationSeries
     * @return ReservationConflictResult
     */
    public function GetConflicts($reservationSeries)
    {
        /** @var IdentifiedConflict[] $conflicts */
        $conflicts = [];

        $reservations = $reservationSeries->Instances();

        $bufferTime = $reservationSeries->MaxBufferTime();

        $keyedResources = [];
        $maxConcurrentReservations = 1;
        $maxConcurrentConflicts = 0;
        $anyConflictsAreBlackouts = false;

        foreach ($reservationSeries->AllResources() as $resource) {
            $keyedResources[$resource->GetId()] = $resource;
            if ($resource->GetMaxConcurrentReservations() > $maxConcurrentReservations) {
                $maxConcurrentReservations = $resource->GetMaxConcurrentReservations();
            }
        }

        /** @var Reservation $reservation */
        foreach ($reservations as $reservation) {
            $instanceConflicts = [];
            Log::Debug('Checking for reservation conflicts, reference number %s on %s', $reservation->ReferenceNumber(), $reservation->StartDate());

            $startDate = $reservation->StartDate();
            $endDate = $reservation->EndDate();

            if ($bufferTime != null && !$reservationSeries->BookedBy()->IsAdmin) {
                $startDate = $startDate->SubtractInterval($bufferTime);
                $endDate = $endDate->AddInterval($bufferTime);
            }

            $existingItems = $this->strategy->GetItemsBetween($startDate, $endDate, array_keys($keyedResources));

            /** @var IReservedItemView $existingItem */
            foreach ($existingItems as $existingItem) {
                if (
                    ($bufferTime == null || $reservationSeries->BookedBy()->IsAdmin) &&
                    ($existingItem->GetStartDate()->Equals($reservation->EndDate()) || $existingItem->GetEndDate()->Equals($reservation->StartDate()))
                ) {
                    continue;
                }

                if ($this->IsInConflict($reservation, $reservationSeries, $existingItem, $keyedResources)) {
                    Log::Debug(
                        'Reference number %s conflicts with existing %s with id %s, referenceNumber %s on %s',
                        $reservation->ReferenceNumber(),
                        get_class($existingItem),
                        $existingItem->GetId(),
                        $existingItem->GetReferenceNumber(),
                        $reservation->StartDate()
                    );

                    $instanceConflicts[] = new IdentifiedConflict($reservation, $existingItem);
                }
                $anyConflictsAreBlackouts = $anyConflictsAreBlackouts || $existingItem->GetReferenceNumber() == '';
            }

            $totalConflicts = $this->GetMaxConcurrentConflicts($instanceConflicts);
            if ($totalConflicts > $maxConcurrentConflicts) {
                $maxConcurrentConflicts = $totalConflicts;
            }

            $conflicts = array_merge($conflicts, $instanceConflicts);
        }

        return new ReservationConflictResult($conflicts, $maxConcurrentConflicts, $anyConflictsAreBlackouts, $maxConcurrentReservations);
    }

    protected function IsInConflict(Reservation $instance, ReservationSeries $series, IReservedItemView $existingItem, $keyedResources)
    {
        if ($existingItem->GetId() == $instance->ReservationId() ||
                $series->IsMarkedForDelete($existingItem->GetId()) ||
                $series->IsMarkedForUpdate($existingItem->GetId())
        ) {
            return false;
        }

        if (array_key_exists($existingItem->GetResourceId(), $keyedResources)) {
            return $existingItem->BufferedTimes()->Overlaps($instance->Duration());
        }

        return false;
    }

    /**
     * @param IdentifiedConflict[] $instanceConflicts
     * @return int
     */
    private function GetMaxConcurrentConflicts($instanceConflicts)
    {
        if (count($instanceConflicts) <= 1) {
            return count($instanceConflicts);
        }

        if (count($instanceConflicts) == 2) {
            $c1 = $instanceConflicts[0];
            $c2 = $instanceConflicts[1];
            if ($c1->Conflict->GetReferenceNumber() != $c2->Conflict->GetReferenceNumber() && ($c1->Conflict->BufferedTimes()->Overlaps($c2->Conflict->BufferedTimes()))) {
                return 2;
            }
            return 1;
        }

        $conflicts = 0;

        $conflictsReference = [];
        foreach ($instanceConflicts as $c1) {
            $conflictsReference[$c1->Conflict->GetReferenceNumber()] = [$c1->Conflict->GetReferenceNumber()];
            foreach ($instanceConflicts as $c2) {
                if ($c1->Conflict->GetReferenceNumber() == $c2->Conflict->GetReferenceNumber()) {
                    continue;
                }
                if ($c1->Conflict->BufferedTimes()->Overlaps($c2->Conflict->BufferedTimes())) {
                    $conflictsReference[$c1->Conflict->GetReferenceNumber()][] = $c2->Conflict->GetReferenceNumber();
                }
            }
        }

        foreach ($conflictsReference as $ref => $conflictList) {
            $maxConflicts = 0;
            foreach ($conflictList as $otherRef) {
                $maxConflicts = count(array_intersect($conflictsReference[$ref], $conflictsReference[$otherRef]));
            }

            if ($maxConflicts > $conflicts) {
                $conflicts = $maxConflicts;
            }
        }

        return $conflicts;
    }
}

class ReservationConflictResult
{
    /**
     * @var IdentifiedConflict[]
     */
    private $conflicts;
    /**
     * @var int
     */
    private $maxConcurrentConflicts;
    /**
     * @var bool
     */
    private $areAnyConflictsBlackouts;
    /**
     * @var int
     */
    private $maxConcurrentReservations;

    /**
     * @param IdentifiedConflict[] $conflicts
     * @param int $maxConcurrentConflicts
     * @param bool $areAnyConflictsBlackouts
     * @param int $maxConcurrentReservations
     */
    public function __construct($conflicts, $maxConcurrentConflicts, $areAnyConflictsBlackouts, $maxConcurrentReservations)
    {
        $this->conflicts = $conflicts;
        $this->maxConcurrentConflicts = $maxConcurrentConflicts;
        $this->areAnyConflictsBlackouts = $areAnyConflictsBlackouts;
        $this->maxConcurrentReservations = $maxConcurrentReservations;
    }

    /**
     * @return IdentifiedConflict[]
     */
    public function Conflicts()
    {
        return $this->conflicts;
    }

    /**
     * @return int
     */
    public function MaxConcurrentConflicts()
    {
        return $this->maxConcurrentConflicts;
    }

    /**
     * @return bool
     */
    public function AreAnyConflictsBlackouts()
    {
        return $this->areAnyConflictsBlackouts;
    }

    /**
     * @return bool
     */
    public function AllowReservation($numberOfConflictsSkipped = 0)
    {
        return !$this->areAnyConflictsBlackouts && (($this->maxConcurrentConflicts - $numberOfConflictsSkipped) < $this->maxConcurrentReservations);
    }
}
