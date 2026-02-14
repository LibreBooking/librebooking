<?php

require_once(ROOT_DIR . 'Domain/namespace.php');

class QuotaWhenModifyingTest extends TestBase
{
    /**
     * @var string
     */
    public $tz;

    /**
     * @var Schedule
     */
    public $schedule;

    public IReservationViewRepository&\PHPUnit\Framework\MockObject\MockObject $reservationViewRepository;

    /**
     * @var FakeUser
     */
    public $user;

    public function setUp(): void
    {
        $this->reservationViewRepository = $this->createMock('IReservationViewRepository');

        $this->tz = 'UTC';
        $this->schedule = new Schedule(1, null, null, null, null, $this->tz);

        $this->user = new FakeUser();

        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testWhenNotChangingExistingTimes()
    {
        $ref1 = 'ref1';
        $ref2 = 'ref2';
        $duration = new QuotaDurationDay();
        $limit = new QuotaLimitCount(1);

        $quota = new Quota(1, $duration, $limit);

        $r1start = Date::Parse('2011-04-03 1:30', $this->tz);
        $r1End = Date::Parse('2011-04-03 2:30', $this->tz);

        $r2start = Date::Parse('2011-04-04 1:30', $this->tz);
        $r2End = Date::Parse('2011-04-04 2:30', $this->tz);

        $existing1 = new TestReservation($ref1, new DateRange($r1start, $r1End));
        $existing2 = new TestReservation($ref2, new DateRange($r2start, $r2End));

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($existing1)
                ->WithInstance($existing2);
        $series = $builder->BuildTestVersion();

        $res1 = new ReservationItemView($ref1, $r1start, $r1End, '', $series->ResourceId());
        $res2 = new ReservationItemView($ref2, $r2start, $r2End, '', $series->ResourceId());
        $reservations = [$res1, $res2];

        $this->SearchReturns($reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testWhenChangingExistingTimes()
    {
        $ref1 = 'ref1';
        $ref2 = 'ref2';
        $duration = new QuotaDurationDay();
        $limit = new QuotaLimitHours(1);

        $quota = new Quota(1, $duration, $limit);

        $r1start = Date::Parse('2011-04-03 1:30', $this->tz);
        $r1End = Date::Parse('2011-04-03 2:30', $this->tz);

        $r2start = Date::Parse('2011-04-04 1:30', $this->tz);
        $r2End = Date::Parse('2011-04-04 2:30', $this->tz);

        $existing1 = new TestReservation($ref1, new DateRange($r1start, $r1End));
        $existing2 = new TestReservation($ref2, new DateRange($r2start, $r2End));

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($existing1)
                ->WithInstance($existing2);
        $series = $builder->BuildTestVersion();

        $series->UpdateDuration(new DateRange($r1start, $r1End->SetTimeString('3:00')));

        $this->SearchReturns([]);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenAddingNewReservations()
    {
        $ref1 = 'ref1';
        $ref2 = 'ref2';
        $duration = new QuotaDurationDay();
        $limit = new QuotaLimitCount(1);

        $quota = new Quota(1, $duration, $limit);

        $r1start = Date::Parse('2011-04-03 1:30', $this->tz);
        $r1End = Date::Parse('2011-04-03 2:30', $this->tz);

        $r2start = Date::Parse('2011-04-04 1:30', $this->tz);
        $r2End = Date::Parse('2011-04-04 2:30', $this->tz);

        $existing1 = new TestReservation($ref1, new DateRange($r1start, $r1End));
        $new = new TestReservation($ref2, new DateRange($r2start, $r2End));

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($existing1)
                ->WithInstance($new);

        $series = $builder->BuildTestVersion();

        $res1 = new ReservationItemView($ref1, $r1start, $r1End, '', $series->ResourceId());
        $res2 = new ReservationItemView('something else', $r2start, $r2End, '', $series->ResourceId());
        $reservations = [$res1, $res2];

        $this->SearchReturns($reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenDeletingAnInstanceItDoesNotCount()
    {
        $ref1 = 'ref1';
        $ref2 = 'ref2';
        $ref3 = 'ref3';
        $duration = new QuotaDurationDay();
        $limit = new QuotaLimitCount(1);

        $quota = new Quota(1, $duration, $limit);

        $r1start = Date::Parse('2011-04-03 1:30', $this->tz);
        $r1End = Date::Parse('2011-04-03 2:30', $this->tz);

        $r2start = Date::Parse('2011-04-04 1:30', $this->tz);
        $r2End = Date::Parse('2011-04-04 2:30', $this->tz);

        $existing1 = new TestReservation($ref1, new DateRange($r1start, $r1End), 1);
        $deleted = new TestReservation($ref2, new DateRange($r2start, $r2End), 2);
        $new = new TestReservation($ref3, new DateRange($r2start, $r2End), 3);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($existing1)
                ->WithInstance($deleted)
                ->WithInstance($new);

        $series = $builder->BuildTestVersion();
        $series->RemoveInstance($deleted);

        $res1 = new ReservationItemView($ref1, $r1start, $r1End, '', $series->ResourceId(), $existing1->ReservationId());
        $res2 = new ReservationItemView($ref2, $r1start, $r1End, '', $series->ResourceId(), $deleted->ReservationId());
        $res3 = new ReservationItemView($ref3, $r2start, $r2End, '', $series->ResourceId(), $new->ReservationId());
        $reservations = [$res1, $res2, $res3];

        $this->SearchReturns($reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    private function SearchReturns($reservations)
    {
        $this->reservationViewRepository->expects($this->once())
            ->method('GetReservations')
            ->with($this->anything(), $this->anything(), $this->anything(), $this->anything())
            ->willReturn($reservations);
    }
}
