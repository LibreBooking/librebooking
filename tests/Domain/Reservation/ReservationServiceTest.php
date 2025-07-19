<?php

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
        $this->assertTrue(in_array($res1, $reservationListing->reservations));
        $this->assertTrue(in_array($res2, $reservationListing->reservations));
        $this->assertTrue(in_array($res3, $reservationListing->reservations));
        $this->assertTrue(in_array($blackout1, $reservationListing->blackouts));
        $this->assertTrue(in_array($blackout2, $reservationListing->blackouts));
        $this->assertTrue(in_array($blackout3, $reservationListing->blackouts));
    }
}

class TestReservationListing implements IMutableReservationListing
{
    /**
     * @var array|ReservationItemView[]
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
        // TODO: Implement Count() method.
        return null;
    }

    /**
     * @return array|ReservationItemView[]
     */
    public function Reservations()
    {
        // TODO: Implement Reservations() method.
        return null;
    }

    /**
     * @param Date $date
     * @return IDateReservationListing
     */
    public function OnDate($date)
    {
        // TODO: Implement OnDate() method.
        return null;
    }

    /**
     * @param ReservationItemView $reservation
     * @return void
     */
    public function Add($reservation)
    {
        $this->reservations[] = $reservation;
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
        // TODO: Implement ForResource() method.
        return null;
    }

    /**
     * @param Date $date
     * @param int $resourceId
     * @return array|ReservationListItem[]
     */
    public function OnDateForResource(Date $date, $resourceId)
    {
        // TODO: Implement OnDateForResource() method.
        return null;
    }
}
