<?php

require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

class DailyLayoutTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testGetLayoutReturnsBuiltSlotsFromScheduleReservationList()
    {
        $date = Date::Parse('2009-09-02', 'UTC');
        $resourceId = 1;
        $targetTimezone = 'America/Chicago';

        $scheduleLayout = new ScheduleLayout($targetTimezone);
        $scheduleLayout->AppendPeriod(new Time(5, 0, 0, $targetTimezone), new Time(6, 0, 0, $targetTimezone));

        $listing = $this->createMock('IReservationListing');

        $startDate = Date::Parse('2009-09-02 17:00:00', 'UTC');
        $endDate = Date::Parse('2009-09-02 18:00:00', 'UTC');
        $reservation = new TestReservationListItem($startDate, $endDate, $resourceId);
        $reservations = [$reservation];

        $listing->expects($this->once())
            ->method('OnDateForResource')
            ->with($this->equalTo($date), $this->equalTo($resourceId))
            ->willReturn($reservations);

        $layout = new DailyLayout($listing, $scheduleLayout);
        $layoutSlots = $layout->GetLayout($date, $resourceId);

        $reservationList = new ScheduleReservationList($reservations, $scheduleLayout, $date);
        $expectedSlots = $reservationList->BuildSlots();

        $this->assertEquals($expectedSlots, $layoutSlots);
    }

    public function testCanGetDisplayLabelsForDate()
    {
        $this->fakeResources->SetDateFormat('period_time', 'h:i');
        $displayDate = Date::Parse('2010-03-17', 'America/Chicago');

        $periods[] = new SchedulePeriod(Date::Parse('2010-03-16 20:30'), Date::Parse('2010-03-17 12:30'));
        $periods[] = new SchedulePeriod(Date::Parse('2010-03-17 12:30'), Date::Parse('2010-03-17 20:30'), "start");
        $periods[] = new SchedulePeriod(Date::Parse('2010-03-17 20:30'), Date::Parse('2010-03-18 12:30'));

        $scheduleLayout = $this->createMock('IScheduleLayout');
        $scheduleLayout->expects($this->once())
            ->method('GetLayout')
            ->with($this->equalTo($displayDate))
            ->willReturn($periods);

        $layout = new DailyLayout(new ReservationListing("America/Chicago"), $scheduleLayout);
        $labels = $layout->GetLabels($displayDate);

        $this->assertEquals('12:00', $labels[0]);
        $this->assertEquals('start', $labels[1]);
        $this->assertEquals('08:30', $labels[2]);
    }

    public function testGetsLayoutWithHourIndications()
    {
        $this->fakeResources->SetDateFormat('period_time', 'h:i');
        $displayDate = Date::Parse('2010-03-17', 'America/Chicago');

        $periods[] = new SchedulePeriod(Date::Parse('2010-03-16 20:30'), Date::Parse('2010-03-17 6:30'));
        $periods[] = new SchedulePeriod(Date::Parse('2010-03-17 6:30'), Date::Parse('2010-03-17 7:30'));
        $periods[] = new SchedulePeriod(Date::Parse('2010-03-17 7:30'), Date::Parse('2010-03-17 8:00'));
        $periods[] = new SchedulePeriod(Date::Parse('2010-03-17 8:00'), Date::Parse('2010-03-17 8:15'));
        $periods[] = new SchedulePeriod(Date::Parse('2010-03-17 8:15'), Date::Parse('2010-03-17 8:30'));
        $periods[] = new SchedulePeriod(Date::Parse('2010-03-17 8:30'), Date::Parse('2010-03-17 8:45'));
        $periods[] = new SchedulePeriod(Date::Parse('2010-03-17 8:45'), Date::Parse('2010-03-17 9:00'));
        $periods[] = new SchedulePeriod(Date::Parse('2010-03-17 9:00'), Date::Parse('2010-03-17 9:30'));
        $periods[] = new SchedulePeriod(Date::Parse('2010-03-17 9:30'), Date::Parse('2010-03-17 10:00'));
        $periods[] = new SchedulePeriod(Date::Parse('2010-03-17 10:00'), Date::Parse('2010-03-17 11:00'));
        $periods[] = new SchedulePeriod(Date::Parse('2010-03-17 11:00'), Date::Parse('2010-03-17 11:30'));
        $periods[] = new SchedulePeriod(Date::Parse('2010-03-17 11:30'), Date::Parse('2010-03-17 14:00'));
        $periods[] = new SchedulePeriod(Date::Parse('2010-03-17 14:00'), Date::Parse('2010-03-18 17:30'));
        $periods[] = new SchedulePeriod(Date::Parse('2010-03-17 17:30'), Date::Parse('2010-03-18 8:30'));

        $scheduleLayout = $this->createMock('IScheduleLayout');
        $scheduleLayout->expects($this->once())
            ->method('GetLayout')
            ->with($this->equalTo($displayDate))
            ->willReturn($periods);

        $scheduleLayout->expects($this->once())
            ->method('FitsToHours')
            ->willReturn(true);

        $layout = new DailyLayout(new ReservationListing("America/Chicago"), $scheduleLayout);
        $labels = $layout->GetPeriods($displayDate, true);

        $i = 0;
        $this->assertEquals('12:00', $labels[$i]->Label($displayDate));
        $this->assertEquals(1, $labels[$i]->Span());
        $i++;
        $this->assertEquals('06:30', $labels[$i]->Label($displayDate));
        $this->assertEquals(1, $labels[$i]->Span());
        $i++;
        $this->assertEquals('07:30', $labels[$i]->Label($displayDate));
        $this->assertEquals(1, $labels[$i]->Span());
        $i++;
        $this->assertEquals('08:00', $labels[$i]->Label($displayDate));
        $this->assertEquals(4, $labels[$i]->Span());
        $i++;
        $this->assertEquals('09:00', $labels[$i]->Label($displayDate));
        $this->assertEquals(2, $labels[$i]->Span());
        $i++;
        $this->assertEquals('10:00', $labels[$i]->Label($displayDate));
        $this->assertEquals(1, $labels[$i]->Span());
        $i++;
        $this->assertEquals('11:00', $labels[$i]->Label($displayDate));
        $this->assertEquals(2, $labels[$i]->Span());
        $i++;
        $this->assertEquals('02:00', $labels[$i]->Label($displayDate));
        $this->assertEquals(1, $labels[$i]->Span());
        $i++;
        $this->assertEquals('05:30', $labels[$i]->Label($displayDate));
        $this->assertEquals(1, $labels[$i]->Span());
    }

    public function testGetsDailySummaryForResource()
    {
        $targetTimezone = 'America/Chicago';
        $date = Date::Parse('2009-09-02', $targetTimezone);
        $start = $date->SetTime(Time::Parse('04:00'));
        $end = $date->SetTime(Time::Parse('05:00'));
        $resourceId = 1;

        $scheduleLayout = new ScheduleLayout($targetTimezone);
        $scheduleLayout->AppendPeriod(new Time(4, 0, 0, $targetTimezone), new Time(5, 0, 0, $targetTimezone));

        $listing = $this->createMock('IReservationListing');

        $firstReservation = new TestReservationListItem($start, $end, $resourceId);
        $reservations = [
            $firstReservation,
            new TestReservationListItem($start, $end, $resourceId),
            new TestBlackoutListItem($start, $end, $resourceId),
        ];

        $listing->expects($this->once())
            ->method('OnDateForResource')
            ->with($this->equalTo($date), $this->equalTo($resourceId))
            ->willReturn($reservations);

        $layout = new DailyLayout($listing, $scheduleLayout);
        $summary = $layout->GetSummary($date, $resourceId);

        $this->assertEquals(2, $summary->NumberOfReservations());
        $this->assertEquals($firstReservation, $summary->FirstReservation());
    }
}

class TestReservationListItem extends ReservationListItem
{
    /**
     * @var \Date
     */
    private $start;

    /**
     * @var \Date
     */
    private $end;

    /**
     * @var int
     */
    private $resourceId;

    public function __construct(Date $start, Date $end, $resourceId)
    {
        $this->start = $start;
        $this->end = $end;
        $this->resourceId = $resourceId;

        parent::__construct(new TestReservationItemView(1, $start, $end, $resourceId));
    }

    public function StartDate()
    {
        return $this->start;
    }

    public function EndDate()
    {
        return $this->end;
    }

    public function ResourceId()
    {
        return $this->resourceId;
    }
}

class TestBlackoutListItem extends BlackoutListItem
{
    /**
     * @var \Date
     */
    private $start;

    /**
     * @var \Date
     */
    private $end;

    /**
     * @var int
     */
    private $resourceId;

    public function __construct(Date $start, Date $end, $resourceId)
    {
        $this->start = $start;
        $this->end = $end;
        $this->resourceId = $resourceId;

        parent::__construct(new TestBlackoutItemView(1, $start, $end, $resourceId));
    }

    public function StartDate()
    {
        return $this->start;
    }

    public function EndDate()
    {
        return $this->end;
    }

    public function ResourceId()
    {
        return $this->resourceId;
    }
}
