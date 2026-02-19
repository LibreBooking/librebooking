<?php

declare(strict_types=1);

class ExistingReservationDeleteTest extends TestBase
{
    /**
     * @var FakeUserSession
     */
    private $user;

    /**
     * @var FakeUserSession
     */
    private $admin;

    public function setUp(): void
    {
        parent::setup();

        $this->user = new FakeUserSession();
        $this->admin = new FakeUserSession(true);
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testDeleteCurrentInstanceWithOnlyOneInstance()
    {
        $reservation = new TestReservation();
        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($reservation);

        $series = $builder->Build();

        $series->ApplyChangesTo(SeriesUpdateScope::ThisInstance);
        $series->Delete($this->user);

        $events = $series->GetEvents();

        $this->assertTrue(in_array(new SeriesDeletedEvent($series), $events));
    }

    public function testDeleteCurrentInstanceWithManyInstances()
    {
        $current = new TestReservation();
        $current->SetReservationDate(TestDateRange::CreateWithDays(1));

        $reservation = new TestReservation();
        $reservation->SetReservationDate(TestDateRange::CreateWithDays(2));

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($current);
        $builder->WithInstance($reservation);

        $series = $builder->Build();

        $series->ApplyChangesTo(SeriesUpdateScope::ThisInstance);
        $series->Delete($this->user);

        $events = $series->GetEvents();

        $this->assertTrue(in_array(new InstanceRemovedEvent($current, $series), $events));
    }

    public function testDeleteAllInstancesDeletesInstancesAfterTodaysDate()
    {
        $current = new TestReservation();
        $current->SetReservationDate(TestDateRange::CreateWithDays(1));

        $past = new TestReservation();
        $past->SetReservationDate(TestDateRange::CreateWithDays(-1));

        $future1 = new TestReservation();
        $future1->SetReservationDate(TestDateRange::CreateWithDays(2));

        $future2 = new TestReservation();
        $future2->SetReservationDate(TestDateRange::CreateWithDays(20));

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($current);
        $builder->WithInstance($past);
        $builder->WithInstance($future1);
        $builder->WithInstance($future2);

        $series = $builder->Build();

        $series->ApplyChangesTo(SeriesUpdateScope::FullSeries);
        $series->Delete($this->user);

        $events = $series->GetEvents();

        $this->assertEquals(3, count($events));
        $this->assertTrue(in_array(new InstanceRemovedEvent($current, $series), $events));
        $this->assertTrue(in_array(new InstanceRemovedEvent($future1, $series), $events));
        $this->assertTrue(in_array(new InstanceRemovedEvent($future2, $series), $events));
    }

    public function testDeleteFutureInstancesDeletesCurrentAndFutureInstances()
    {
        $current = new TestReservation();
        $current->SetReservationDate(TestDateRange::CreateWithDays(2));

        $past = new TestReservation();
        $past->SetReservationDate(TestDateRange::CreateWithDays(1));

        $future1 = new TestReservation();
        $future1->SetReservationDate(TestDateRange::CreateWithDays(3));

        $future2 = new TestReservation();
        $future2->SetReservationDate(TestDateRange::CreateWithDays(20));

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($current);
        $builder->WithInstance($past);
        $builder->WithInstance($future1);
        $builder->WithInstance($future2);

        $series = $builder->Build();

        $series->ApplyChangesTo(SeriesUpdateScope::FutureInstances);
        $series->Delete($this->user);

        $events = $series->GetEvents();

        $this->assertTrue(in_array(new InstanceRemovedEvent($current, $series), $events));
        $this->assertTrue(in_array(new InstanceRemovedEvent($future1, $series), $events));
        $this->assertTrue(in_array(new InstanceRemovedEvent($future2, $series), $events));
        $this->assertTrue(!in_array(new InstanceRemovedEvent($past, $series), $events));
    }

    public function testDeleteFullSeriesWithAllInstancesInFuture()
    {
        $current = new TestReservation();
        $current->SetReservationDate(TestDateRange::CreateWithDays(1));

        $future1 = new TestReservation();
        $future1->SetReservationDate(TestDateRange::CreateWithDays(2));

        $future2 = new TestReservation();
        $future2->SetReservationDate(TestDateRange::CreateWithDays(20));

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($current);
        $builder->WithInstance($future1);
        $builder->WithInstance($future2);

        $series = $builder->Build();

        $series->ApplyChangesTo(SeriesUpdateScope::FullSeries);
        $series->Delete($this->user);

        $events = $series->GetEvents();

        $this->assertEquals(1, count($events));
        $this->assertTrue(in_array(new SeriesDeletedEvent($series), $events));
    }

    public function testDeletesWholeSeriesWhenAdminRegardlessOfDate()
    {
        $r1 = new TestReservation();
        $r1->SetReservationDate(TestDateRange::CreateWithDays(-1));

        $r2 = new TestReservation();
        $r2->SetReservationDate(TestDateRange::CreateWithDays(-2));

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($r1);
        $builder->WithInstance($r2);

        $series = $builder->Build();

        $series->ApplyChangesTo(SeriesUpdateScope::FullSeries);
        $series->Delete($this->admin);

        $events = $series->GetEvents();

        $this->assertEquals(1, count($events));
        $this->assertTrue(in_array(new SeriesDeletedEvent($series), $events));
    }

    public function testReturnsCreditsForFutureInstanceDelete()
    {
        $current = new TestReservation();
        $current->SetReservationDate(TestDateRange::CreateWithDays(1));
        $current->WithCreditsConsumed(1);

        $past = new TestReservation();
        $past->SetReservationDate(TestDateRange::CreateWithDays(-1));
        $past->WithCreditsConsumed(1);

        $future1 = new TestReservation();
        $future1->SetReservationDate(TestDateRange::CreateWithDays(2));
        $future1->WithCreditsConsumed(1);

        $future2 = new TestReservation();
        $future2->SetReservationDate(TestDateRange::CreateWithDays(20));
        $future2->WithCreditsConsumed(1);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($current);
        $builder->WithInstance($past);
        $builder->WithInstance($future1);
        $builder->WithInstance($future2);

        $series = $builder->Build();

        $series->ApplyChangesTo(SeriesUpdateScope::FutureInstances);
        $series->Delete($this->user);

        $balance = $series->GetUnusedCreditBalance();

        $this->assertEquals(3, $balance);
    }
}
