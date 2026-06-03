<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

class ReservationServiceTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testGetReservationsPullsReservationFromTheRepositoryAndAddsThemToTheCoordinator()
    {
        $timezone = 'UTC';
        $startDate = Date::Now();
        $endDate = Date::Now();
        $scheduleId = 100;

        $range = new DateRange($startDate, $endDate);

        $repository = $this->createMock('IReservationViewRepository');
        $reservationListing = new TestReservationListing();
        $listingFactory = $this->createMock('IReservationListingFactory');

        $rows = FakeReservationRepository::GetReservationRows();
        $res1 = ReservationItemView::Populate($rows[0]);
        $res2 = ReservationItemView::Populate($rows[1]);
        $res3 = ReservationItemView::Populate($rows[2]);

        $date = Date::Now();
        $blackout1 = new TestBlackoutItemView(1, $date, $date, 1);
        $blackout2 = new TestBlackoutItemView(2, $date, $date, 2);
        $blackout3 = new TestBlackoutItemView(3, $date, $date, 3);

        $repository
            ->expects($this->once())
            ->method('GetReservations')
            ->with($this->equalTo($startDate), $this->equalTo($endDate), $this->isNull(), $this->isNull(), $this->equalTo($scheduleId), $this->isNull())
            ->willReturn([$res1, $res2, $res3]);

        $repository
            ->expects($this->once())
            ->method('GetBlackoutsWithin')
            ->with($this->equalTo(new DateRange($startDate, $endDate)), $this->equalTo($scheduleId))
            ->willReturn([$blackout1, $blackout2, $blackout3]);

        $listingFactory
            ->expects($this->once())
            ->method('CreateReservationListing')
            ->with($this->equalTo($timezone))
            ->willReturn($reservationListing);

        $service = new ReservationService($repository, $listingFactory);

        $listing = $service->GetReservations($range, $scheduleId, $timezone);

        $this->assertEquals($reservationListing, $listing);
        $this->assertEquals([$res1, $res2, $res3], array_map(fn (ReservationListItem $i) => $i->ReservedItem(), $reservationListing->reservations));
        $this->assertTrue(in_array($blackout1, $reservationListing->blackouts));
        $this->assertTrue(in_array($blackout2, $reservationListing->blackouts));
        $this->assertTrue(in_array($blackout3, $reservationListing->blackouts));
    }
}

class TestReservationListing implements IMutableReservationListing
{
    /**
     * @var array|ReservationListItem[]
     */
    public $reservations = [];

    /**
     * @var array|BlackoutItemView[]
     */
    public $blackouts = [];

    /**
     * @return int
     */
    public function Count()
    {
        return count($this->Reservations());
    }

    /**
     * @return array|ReservationListItem[]
     */
    public function Reservations()
    {
        return $this->reservations;
    }

    /**
     * @param Date $date
     * @return IReservationListing
     */
    public function OnDate($date)
    {
        return new EmptyReservationListing();
    }

    /**
     * @param ReservationItemView $reservation
     * @return void
     */
    public function Add($reservation)
    {
        $this->reservations[] = new ReservationListItem($reservation);
    }

    /**
     * @param BlackoutItemView $blackout
     * @return void
     */
    public function AddBlackout($blackout)
    {
        $this->blackouts[] = $blackout;
    }

    /**
     * @param int $resourceId
     * @return IReservationListing
     */
    public function ForResource($resourceId)
    {
        return new EmptyReservationListing();
    }

    /**
     * @param Date $date
     * @param int $resourceId
     * @return array|ReservationListItem[]
     */
    public function OnDateForResource(Date $date, $resourceId)
    {
        return [];
    }
}
