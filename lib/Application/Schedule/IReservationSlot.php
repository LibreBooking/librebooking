<?php

interface IReservationSlot
{
    /**
     * @return Time
     */
    public function Begin();

    /**
     * @return Time
     */
    public function End();

    /**
     * @return Date
     */
    public function BeginDate();

    /**
     * @return Date
     */
    public function EndDate();

    /**
     * @return Date
     */
    public function Date();

    /**
     * @return int
     *
     */
    public function PeriodSpan();

    public function Label(): ?string;

    /**
     * @return bool
     */
    public function IsReservable();

    /**
     * @return bool
     */
    public function IsReserved();

    /**
     * @return bool
     */
    public function IsPending();

    /**
     * @param $date Date
     * @return bool
     */
    public function IsPastDate(Date $date);

    /**
     * @return bool
     */
    public function RequiresCheckin();

    public function AutoReleaseMinutes(): ?int;

    public function AutoReleaseMinutesRemaining(): ?int;

    /**
     * @param string $timezone
     * @return IReservationSlot
     */
    public function ToTimezone($timezone);

    /**
     * @param UserSession $session
     * @return bool
     */
    public function IsOwnedBy(UserSession $session);

    /**
     * @param UserSession $session
     * @return bool
     */
    public function IsParticipating(UserSession $session);

    /**
     * @return string
     */
    public function BeginSlotId();

    /**
     * @return string
     */
    public function EndSlotId();

    /**
     * @return bool
     */
    public function HasCustomColor();

    public function Color(): ?string;

    public function TextColor(): ?string;

    /**
     * @param Date $date
     * @return bool
     */
    public function CollidesWith(Date $date);

    /**
     * @return string
     */
    public function Id();

    /**
     * @return int|null
     */
    public function OwnerId();

    /**
     * @return int[]
     */
    public function OwnerGroupIds();

    /**
     * @return bool
     */
    public function IsNew();

    /**
     * @return bool
     */
    public function IsUpdated();
}
