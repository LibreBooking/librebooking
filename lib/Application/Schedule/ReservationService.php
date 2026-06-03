<?php

class ReservationService implements IReservationService
{
    /**
     * @var IReservationViewRepository
     */
    private $_repository;

    /**
     * @var IReservationListingFactory
     */
    private $_coordinatorFactory;

    public function __construct(IReservationViewRepository $repository, IReservationListingFactory $listingFactory)
    {
        $this->_repository = $repository;
        $this->_coordinatorFactory = $listingFactory;
    }

    public function GetReservations(DateRange $dateRangeUtc, $scheduleId, $targetTimezone, $resourceIds = null)
    {
        $filterResourcesInCode = false;
        if (is_array($resourceIds)) {
            $filterResourcesInCode = count($resourceIds) > 100;
        }
        $resourceKeys = [];
        if ($filterResourcesInCode) {
            $resourceKeys = array_combine($resourceIds, $resourceIds);
        }
        $reservationListing = $this->_coordinatorFactory->CreateReservationListing($targetTimezone, $dateRangeUtc);

        $reservations = $this->_repository->GetReservations(
            $dateRangeUtc->GetBegin(),
            $dateRangeUtc->GetEnd(),
            null,
            null,
            $scheduleId,
            ($filterResourcesInCode ? [] : $resourceIds)
        );
        Log::Debug(
            'Found %s reservations for schedule %s between %s and %s',
            count($reservations),
            $scheduleId,
            $dateRangeUtc->GetBegin(),
            $dateRangeUtc->GetEnd()
        );

        foreach ($reservations as $reservation) {
            if (!$filterResourcesInCode || array_key_exists($reservation->ResourceId, $resourceKeys)) {
                $reservationListing->Add($reservation);
            }
        }

        $blackouts = $this->_repository->GetBlackoutsWithin($dateRangeUtc, $scheduleId);
        Log::Debug('Found %s blackouts for schedule %s between %s and %s', count($blackouts), $scheduleId, $dateRangeUtc->GetBegin(), $dateRangeUtc->GetEnd());

        foreach ($blackouts as $blackout) {
            $reservationListing->AddBlackout($blackout);
        }

        return $reservationListing;
    }

    public function Search(DateRange $dateRange, $scheduleId, $resourceIds = null, $ownerId = null, $participantId = null)
    {
        $reservations = $this->_repository->GetReservations($dateRange->GetBegin(), $dateRange->GetEnd(), $ownerId, null, $scheduleId, $resourceIds, false, $participantId);
        $blackouts = $this->_repository->GetBlackoutsWithin($dateRange, $scheduleId, $resourceIds);

        /** @var ReservationListItem[] $items */
        $items = [];
        foreach ($reservations as $i) {
            $items[] = new ReservationListItem($i);
        }
        foreach ($blackouts as $i) {
            $items[] = new BlackoutListItem($i);
        }

        return $items;
    }
}

interface IReservationService
{
    /**
     * @param DateRange $dateRangeUtc range of dates to search against in UTC
     * @param int $scheduleId
     * @param string $targetTimezone timezone to convert the results to
     * @param null|int[] $resourceIds
     * @return IReservationListing
     */
    public function GetReservations(DateRange $dateRangeUtc, $scheduleId, $targetTimezone, $resourceIds = null);

    /**
     * @param DateRange $dateRange
     * @param int $scheduleId
     * @param null|int[] $resourceIds
     * @param null|int $ownerId
     * @param null|int $participantId
     * @return ReservationListItem[]
     */
    public function Search(DateRange $dateRange, $scheduleId, $resourceIds = null, $ownerId = null, $participantId = null);
}
