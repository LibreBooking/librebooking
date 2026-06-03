<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Reservation/ManageBlackoutsService.php');

class ReservationConflictResolutionTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function testDeletesReservation()
    {
        $id = 123;
        $reservationView = new TestReservationItemView($id, Date::Now(), Date::Now());

        $repo = $this->createMock('IReservationRepository');
        $notificationService = new FakeReservationNotificationService();
        $handler = new ReservationConflictDelete($repo, $notificationService);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithId($id);
        $reservation = $builder->Build();

        $repo->expects($this->once())
             ->method('LoadById')
             ->with($this->equalTo($id))
             ->willReturn($reservation);

        $repo->expects($this->once())
             ->method('Delete')
             ->with($this->equalTo($reservation));

        $handled = $handler->Handle($reservationView, new Blackout(DateRange::Create('2016-01-01', '2016-01-01', 'America/Chicago')));

        $this->assertTrue($handled);

        $this->assertEquals(SeriesUpdateScope::ThisInstance, $reservation->SeriesUpdateScope());
        $this->assertEquals($reservation, $notificationService->_ReservationNotified);
    }

    public function testCanBlackoutAroundReservations()
    {
        $timezone = 'America/Chicago';
        $date1 = DateRange::Create('2016-05-15 12:00', '2016-05-15 16:00', $timezone);
        $daily = new RepeatDaily(1, Date::Parse('2016-05-20', $timezone));

        $handler = new ReservationConflictBookAround();
        $blackoutSeries = BlackoutSeries::Create(1, 'title', $date1);
        $blackoutSeries->Repeats($daily);

        $blackouts = array_values($blackoutSeries->AllBlackouts());

        $reservation = new TestReservationItemView(1, Date::Parse('2016-05-16 13:00', $timezone), Date::Parse('2016-05-16 14:00', $timezone));
        $handled = $handler->Handle($reservation, $blackouts[1]);

        /** @var Blackout[] $adjustedBlackouts */
        $adjustedBlackouts = array_values($blackoutSeries->AllBlackouts());

        $this->assertTrue($handled);
        $this->assertEquals(7, count($adjustedBlackouts));
        $this->assertEquals(Date::Parse('2016-05-16 12:00', $timezone)->ToUtc(), $adjustedBlackouts[1]->StartDate()->ToUtc());
        $this->assertEquals(Date::Parse('2016-05-16 13:00', $timezone)->ToUtc(), $adjustedBlackouts[1]->EndDate()->ToUtc());

        $this->assertEquals(Date::Parse('2016-05-16 14:00', $timezone)->ToUtc(), $adjustedBlackouts[2]->StartDate()->ToUtc());
        $this->assertEquals(Date::Parse('2016-05-16 16:00', $timezone)->ToUtc(), $adjustedBlackouts[2]->EndDate()->ToUtc());
    }

    public function testIfTotallyConflictsThenDoNotHandle()
    {
        $timezone = 'America/Chicago';
        $date1 = DateRange::Create('2016-05-16 13:00', '2016-05-16 13:30', $timezone);

        $handler = new ReservationConflictBookAround();
        $blackoutSeries = BlackoutSeries::Create(1, 'title', $date1);

        $blackouts = array_values($blackoutSeries->AllBlackouts());

        $reservation = new TestReservationItemView(1, Date::Parse('2016-05-16 13:00', $timezone), Date::Parse('2016-05-16 14:00', $timezone));
        $handled = $handler->Handle($reservation, $blackouts[0]);

        $adjustedBlackouts = $blackoutSeries->AllBlackouts();

        $this->assertFalse($handled);
        $this->assertEquals(1, count($adjustedBlackouts));
    }

    public function testIfTotallyConflictsThenDeleteOccurrence()
    {
        $timezone = 'America/Chicago';
        $date1 = DateRange::Create('2016-05-15 13:00', '2016-05-15 13:30', $timezone);
        $daily = new RepeatDaily(1, Date::Parse('2016-05-20', $timezone));

        $handler = new ReservationConflictBookAround();
        $blackoutSeries = BlackoutSeries::Create(1, 'title', $date1);
        $blackoutSeries->Repeats($daily);

        $reservation = new TestReservationItemView(1, Date::Parse('2016-05-16 13:00', $timezone), Date::Parse('2016-05-16 14:00', $timezone));

        while ($blackout = $blackoutSeries->NextBlackout()) {
            $handler->Handle($reservation, $blackout);
        }

        $adjustedBlackouts = $blackoutSeries->AllBlackouts();

        $this->assertEquals(5, count($adjustedBlackouts));
    }
}
