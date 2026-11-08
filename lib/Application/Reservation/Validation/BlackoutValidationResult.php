<?php

class BlackoutValidationResult implements IBlackoutValidationResult
{
    /**
     * @var array|BlackoutItemView[]
     */
    private $conflictingBlackouts;

    /**
     * @var array|ReservationItemView[]
     */
    private $conflictingReservations;

    /**
     * @param array|BlackoutItemView[] $conflictingBlackouts
     * @param array|ReservationItemView[] $conflictingReservations
     */
    public function __construct($conflictingBlackouts, $conflictingReservations)
    {
        $this->conflictingBlackouts = $conflictingBlackouts;
        $this->conflictingReservations = $conflictingReservations;
    }

    public function WasSuccessful()
    {
        return $this->CanBeSaved();
    }

    /**
     * @return bool
     */
    public function CanBeSaved()
    {
        return empty($this->conflictingBlackouts) && empty($this->conflictingReservations);
    }

    public function Message(): string
    {
        return '';
    }

    /**
     * @return array|ReservationItemView[]
     */
    public function ConflictingReservations()
    {
        return $this->conflictingReservations;
    }

    /**
     * @return array|BlackoutItemView[]
     */
    public function ConflictingBlackouts()
    {
        return $this->conflictingBlackouts;
    }
}
