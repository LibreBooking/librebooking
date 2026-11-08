<?php

interface IDateReservationListing extends IResourceReservationListing
{
    /**
     * @param int $resourceId
     * @return IResourceReservationListing
     */
    public function ForResource($resourceId);
}

interface IResourceReservationListing
{
    /**
     * @return int
     */
    public function Count();

    /**
     * @return array|ReservationListItem[]
     */
    public function Reservations();
}

interface IReservationListing extends IResourceReservationListing
{
    /**
     * @param Date $date
     * @return IReservationListing
     */
    public function OnDate($date);

    /**
     * @param int $resourceId
     * @return IReservationListing
     */
    public function ForResource($resourceId);

    /**
     * @param Date $date
     * @param int $resourceId
     * @return array|ReservationListItem[]
     */
    public function OnDateForResource(Date $date, $resourceId);
}

interface IMutableReservationListing extends IReservationListing
{
    /**
     * @param ReservationItemView $reservation
     * @return void
     */
    public function Add($reservation);

    /**
     * @param BlackoutItemView $blackout
     * @return void
     */
    public function AddBlackout($blackout);
}

class EmptyReservationListing implements IReservationListing
{
    /**
     * @inheritDoc
     */
    public function Count()
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    public function Reservations()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function OnDate($date)
    {
        return new EmptyReservationListing();
    }

    /**
     * @inheritDoc
     */
    public function ForResource($resourceId)
    {
        return new EmptyReservationListing();
    }

    /**
     * @inheritDoc
     */
    public function OnDateForResource(Date $date, $resourceId): array
    {
        return [];
    }
}
