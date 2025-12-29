<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

class ScheduleReservationListTest extends TestBase
{
    private $utc;
    private $userTz;

    /**
     * @var Date
     */
    private $date;

    /**
     * @var ScheduleLayout
     */
    private $testDbLayout;

    public function setUp(): void
    {
        parent::setup();

        $utc = 'UTC';
        $userTz = 'America/Chicago';

        $this->utc = $utc;
        $this->userTz = $userTz;

        $date = Date::Parse('2008-11-11', $userTz);
        $this->date = $date;

        $layout = new ScheduleLayout($userTz);

        $layout->AppendBlockedPeriod(new Time(0, 0, 0, $userTz), new Time(2, 0, 0, $userTz));

        for ($hour = 2; $hour <= 14; $hour++) {
            $layout->AppendPeriod(new Time($hour, 0, 0, $userTz), new Time($hour, 30, 0, $userTz));
            $layout->AppendPeriod(new Time($hour, 30, 0, $userTz), new Time($hour + 1, 0, 0, $userTz));
        }

        $layout->AppendBlockedPeriod(new Time(15, 0, 0, $userTz), new Time(0, 0, 0, $userTz));

        $this->testDbLayout = $layout;
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testLayoutIsConvertedToUserTimezoneBeforeSlotsAreCreated()
    {
        $hideBlocked = true;
        $userTz = 'America/Chicago';
        $date = Date::Parse('2010-01-02', $userTz);

        $s1 = Date::Parse('2010-01-01 23:00:00', $userTz);
        $e1 = Date::Parse('2010-01-02 02:00:00', $userTz);
        $s2 = Date::Parse('2010-01-02 02:00:00', $userTz);
        $e2 = Date::Parse('2010-01-02 23:00:00', $userTz);
        $s3 = Date::Parse('2010-01-02 23:00:00', $userTz);
        $e3 = Date::Parse('2010-01-03 02:00:00', $userTz);

        $item = new ReservationItemView(1, $s2->ToUtc(), $e2->ToUtc());
        $r1 = new ReservationListItem($item);
        $p1 = new NonSchedulePeriod($s1, $e1);
        $p2 = new SchedulePeriod($s2, $e2);
        $p3 = new SchedulePeriod($s3, $e3);
        $layoutPeriods = [$p1, $p2, $p3];

        $layout = $this->createMock('IScheduleLayout');
        $layout->expects($this->once())
            ->method('Timezone')
            ->willReturn($userTz);

        $layout->expects($this->once())
            ->method('GetLayout')
            ->with($this->equalTo($date), $this->equalTo($hideBlocked))
            ->willReturn($layoutPeriods);

        $scheduleList = new ScheduleReservationList([$r1], $layout, $date, $hideBlocked);
        $slots = $scheduleList->BuildSlots();

        $slot1 = new EmptyReservationSlot($p1, $p1, $date, false);
        $slot2 = new ReservationSlot($p2, $p2, $date, 1, $item);
        $slot3 = new EmptyReservationSlot($p3, $p3, $date, true);

        $this->assertEquals(3, count($slots));
        $this->assertEquals($slot1, $slots[0]);
        $this->assertEquals($slot2, $slots[1]);
        $this->assertEquals($slot3, $slots[2]);
    }

    public function testCanGetReservationSlotsForSingleDay()
    {
        $date = $this->date;

        $s1 = $date->SetTimeString('03:00');
        $e1 = $date->SetTimeString('04:30');

        $s2 = $date->SetTimeString('05:00');
        $e2 = $date->SetTimeString('05:30');

        $s3 = $date->SetTimeString('08:00');
        $e3 = $date->SetTimeString('12:00');

        $item1 = new TestReservationItemView(1, $s1->ToUtc(), $e1->ToUtc());
        $item2 = new TestReservationItemView(2, $s2->ToUtc(), $e2->ToUtc());
        $item3 = new TestReservationItemView(3, $s3->ToUtc(), $e3->ToUtc());
        $r1 = new ReservationListItem($item1);
        $r2 = new ReservationListItem($item2);
        $r3 = new ReservationListItem($item3);

        $reservations = [$r1, $r2, $r3];

        //		printf("%s\n", Date::Now()->ToString());

        $list = new ScheduleReservationList($reservations, $this->testDbLayout, $this->date);
        $slots = $list->BuildSlots();

        //		printf("%s\n", Date::Now()->ToString());

        $slotDate = $this->date;
        $expectedSlots = $this->testDbLayout->GetLayout($this->date);

        $slot1 = new EmptyReservationSlot($expectedSlots[0], $expectedSlots[0], $slotDate, $expectedSlots[0]->IsReservable());
        $slot2 = new EmptyReservationSlot($expectedSlots[1], $expectedSlots[1], $slotDate, $expectedSlots[1]->IsReservable());
        $slot3 = new EmptyReservationSlot($expectedSlots[2], $expectedSlots[2], $slotDate, $expectedSlots[2]->IsReservable());
        $slot4 = new ReservationSlot($expectedSlots[3], $expectedSlots[5], $slotDate, 3, $item1);
        $slot5 = new EmptyReservationSlot($expectedSlots[6], $expectedSlots[6], $slotDate, $expectedSlots[6]->IsReservable());
        $slot6 = new ReservationSlot($expectedSlots[7], $expectedSlots[7], $slotDate, 1, $item2);
        $slot7 = new EmptyReservationSlot($expectedSlots[8], $expectedSlots[8], $slotDate, $expectedSlots[8]->IsReservable());
        $slot8 = new EmptyReservationSlot($expectedSlots[9], $expectedSlots[9], $slotDate, $expectedSlots[9]->IsReservable());
        $slot9 = new EmptyReservationSlot($expectedSlots[10], $expectedSlots[10], $slotDate, $expectedSlots[10]->IsReservable());
        $slot10 = new EmptyReservationSlot($expectedSlots[11], $expectedSlots[11], $slotDate, $expectedSlots[11]->IsReservable());
        $slot11 = new EmptyReservationSlot($expectedSlots[12], $expectedSlots[12], $slotDate, $expectedSlots[12]->IsReservable());
        $slot12 = new ReservationSlot($expectedSlots[13], $expectedSlots[20], $slotDate, 8, $item3);
        $slot13 = new EmptyReservationSlot($expectedSlots[21], $expectedSlots[21], $slotDate, $expectedSlots[21]->IsReservable());
        $slot14 = new EmptyReservationSlot($expectedSlots[22], $expectedSlots[22], $slotDate, $expectedSlots[22]->IsReservable());
        $slot15 = new EmptyReservationSlot($expectedSlots[23], $expectedSlots[23], $slotDate, $expectedSlots[23]->IsReservable());
        $slot16 = new EmptyReservationSlot($expectedSlots[24], $expectedSlots[24], $slotDate, $expectedSlots[24]->IsReservable());
        $slot17 = new EmptyReservationSlot($expectedSlots[25], $expectedSlots[25], $slotDate, $expectedSlots[25]->IsReservable());
        $slot18 = new EmptyReservationSlot($expectedSlots[26], $expectedSlots[26], $slotDate, $expectedSlots[26]->IsReservable());
        $slot19 = new EmptyReservationSlot($expectedSlots[27], $expectedSlots[27], $slotDate, $expectedSlots[27]->IsReservable());

        $expectedNumberOfSlots = 19;

        $this->assertEquals($expectedNumberOfSlots, count($slots));

        $this->assertEquals($slot1, $slots[0]);
        $this->assertEquals($slot2, $slots[1]);
        $this->assertEquals($slot3, $slots[2]);
        $this->assertEquals($slot4, $slots[3]);
        $this->assertEquals($slot5, $slots[4]);
        $this->assertEquals($slot6, $slots[5]);
        $this->assertEquals($slot7, $slots[6]);
        $this->assertEquals($slot8, $slots[7]);
        $this->assertEquals($slot9, $slots[8]);
        $this->assertEquals($slot10, $slots[9]);
        $this->assertEquals($slot11, $slots[10]);
        $this->assertEquals($slot12, $slots[11]);
        $this->assertEquals($slot13, $slots[12]);
        $this->assertEquals($slot14, $slots[13]);
        $this->assertEquals($slot15, $slots[14]);
        $this->assertEquals($slot16, $slots[15]);
        $this->assertEquals($slot17, $slots[16]);
        $this->assertEquals($slot18, $slots[17]);
        $this->assertEquals($slot19, $slots[18]);
    }

    public function testFitsReservationToLayoutIfReservationEndingTimeIsNotAtSlotBreak()
    {
        $date = $this->date;

        $s1 = $date->SetTimeString('03:00');
        $e1 = $date->SetTimeString('04:10');

        $s2 = $date->SetTimeString('05:00');
        $e2 = $date->SetTimeString('05:20');

        $s3 = $date->SetTimeString('08:00');
        $e3 = $date->SetTimeString('11:55');

        $item1 = new TestReservationItemView(1, $s1->ToUtc(), $e1->ToUtc());
        $item2 = new TestReservationItemView(2, $s2->ToUtc(), $e2->ToUtc());
        $item3 = new TestReservationItemView(3, $s3->ToUtc(), $e3->ToUtc());
        $r1 = new ReservationListItem($item1);
        $r2 = new ReservationListItem($item2);
        $r3 = new ReservationListItem($item3);

        $reservations = [$r1, $r2, $r3];

        $list = new ScheduleReservationList($reservations, $this->testDbLayout, $date);
        $slots = $list->BuildSlots();

        $slotDate = $date;
        $expectedSlots = $this->testDbLayout->GetLayout($date);

        $slot1 = new EmptyReservationSlot($expectedSlots[0], $expectedSlots[0], $slotDate, $expectedSlots[0]->IsReservable());
        $slot2 = new EmptyReservationSlot($expectedSlots[1], $expectedSlots[1], $slotDate, $expectedSlots[1]->IsReservable());
        $slot3 = new EmptyReservationSlot($expectedSlots[2], $expectedSlots[2], $slotDate, $expectedSlots[2]->IsReservable());
        $slot4 = new ReservationSlot($expectedSlots[3], $expectedSlots[5], $slotDate, 3, $item1);
        $slot5 = new EmptyReservationSlot($expectedSlots[6], $expectedSlots[6], $slotDate, $expectedSlots[6]->IsReservable());
        $slot6 = new ReservationSlot($expectedSlots[7], $expectedSlots[7], $slotDate, 1, $item2);
        $slot7 = new EmptyReservationSlot($expectedSlots[8], $expectedSlots[8], $slotDate, $expectedSlots[8]->IsReservable());
        $slot8 = new EmptyReservationSlot($expectedSlots[9], $expectedSlots[9], $slotDate, $expectedSlots[9]->IsReservable());
        $slot9 = new EmptyReservationSlot($expectedSlots[10], $expectedSlots[10], $slotDate, $expectedSlots[10]->IsReservable());
        $slot10 = new EmptyReservationSlot($expectedSlots[11], $expectedSlots[11], $slotDate, $expectedSlots[11]->IsReservable());
        $slot11 = new EmptyReservationSlot($expectedSlots[12], $expectedSlots[12], $slotDate, $expectedSlots[12]->IsReservable());
        $slot12 = new ReservationSlot($expectedSlots[13], $expectedSlots[20], $slotDate, 8, $item3);
        $slot13 = new EmptyReservationSlot($expectedSlots[21], $expectedSlots[21], $slotDate, $expectedSlots[21]->IsReservable());
        $slot14 = new EmptyReservationSlot($expectedSlots[22], $expectedSlots[22], $slotDate, $expectedSlots[22]->IsReservable());
        $slot15 = new EmptyReservationSlot($expectedSlots[23], $expectedSlots[23], $slotDate, $expectedSlots[23]->IsReservable());
        $slot16 = new EmptyReservationSlot($expectedSlots[24], $expectedSlots[24], $slotDate, $expectedSlots[24]->IsReservable());
        $slot17 = new EmptyReservationSlot($expectedSlots[25], $expectedSlots[25], $slotDate, $expectedSlots[25]->IsReservable());
        $slot18 = new EmptyReservationSlot($expectedSlots[26], $expectedSlots[26], $slotDate, $expectedSlots[26]->IsReservable());
        $slot19 = new EmptyReservationSlot($expectedSlots[27], $expectedSlots[27], $slotDate, $expectedSlots[27]->IsReservable());

        $expectedNumberOfSlots = 19;

        $this->assertEquals($expectedNumberOfSlots, count($slots));

        $this->assertEquals($slot1, $slots[0]);
        $this->assertEquals($slot2, $slots[1]);
        $this->assertEquals($slot3, $slots[2]);
        $this->assertEquals(
            $slot4,
            $slots[3],
            'reservation does not end on a slot break, so it should scale back to the closest ending slot time'
        );
        $this->assertEquals($slot5, $slots[4]);
        $this->assertEquals(
            $slot6,
            $slots[5],
            'reservation does not end on a slot break, but it needs to span at least 1 cell'
        );
        $this->assertEquals($slot7, $slots[6]);
        $this->assertEquals($slot8, $slots[7]);
        $this->assertEquals($slot9, $slots[8]);
        $this->assertEquals($slot10, $slots[9]);
        $this->assertEquals($slot11, $slots[10]);
        $this->assertEquals(
            $slot12,
            $slots[11],
            'reservation does not end on a slot break, so it should scale back to the closest ending slot time'
        );
        $this->assertEquals($slot13, $slots[12]);
        $this->assertEquals($slot14, $slots[13]);
        $this->assertEquals($slot15, $slots[14]);
        $this->assertEquals($slot16, $slots[15]);
        $this->assertEquals($slot17, $slots[16]);
        $this->assertEquals($slot18, $slots[17]);
        $this->assertEquals($slot19, $slots[18]);
    }

    public function testReservationEndingAfterLayoutPeriodAndStartingWithinIsCreatedProperly()
    {
        $date = $this->date;

        $s1 = $date->SetTimeString('03:00');
        $e1 = $date->SetTimeString('04:10');

        $s2 = $date->SetTimeString('05:00');
        $e2 = $date->SetTimeString('05:20');

        $s3 = $date->SetTimeString('08:00');
        $e3 = $date->AddDays(2)->SetTimeString('11:55');

        $item1 = new TestReservationItemView(1, $s1->ToUtc(), $e1->ToUtc());
        $item2 = new TestReservationItemView(2, $s2->ToUtc(), $e2->ToUtc());
        $item3 = new TestReservationItemView(3, $s3->ToUtc(), $e3->ToUtc());
        $r1 = new ReservationListItem($item1);
        $r2 = new ReservationListItem($item2);
        $r3 = new ReservationListItem($item3);

        $reservations = [$r1, $r2, $r3];

        $list = new ScheduleReservationList($reservations, $this->testDbLayout, $date);
        $slots = $list->BuildSlots();
        $expectedSlots = $this->testDbLayout->GetLayout($date);

        $slotDate = $date;

        $slot1 = new EmptyReservationSlot($expectedSlots[0], $expectedSlots[0], $slotDate, $expectedSlots[0]->IsReservable());
        $slot2 = new EmptyReservationSlot($expectedSlots[1], $expectedSlots[1], $slotDate, $expectedSlots[1]->IsReservable());
        $slot3 = new EmptyReservationSlot($expectedSlots[2], $expectedSlots[2], $slotDate, $expectedSlots[2]->IsReservable());
        $slot4 = new ReservationSlot($expectedSlots[3], $expectedSlots[5], $slotDate, 3, $item1);
        $slot5 = new EmptyReservationSlot($expectedSlots[6], $expectedSlots[6], $slotDate, $expectedSlots[6]->IsReservable());
        $slot6 = new ReservationSlot($expectedSlots[7], $expectedSlots[7], $slotDate, 1, $item2);
        $slot7 = new EmptyReservationSlot($expectedSlots[8], $expectedSlots[8], $slotDate, $expectedSlots[8]->IsReservable());
        $slot8 = new EmptyReservationSlot($expectedSlots[9], $expectedSlots[9], $slotDate, $expectedSlots[9]->IsReservable());
        $slot9 = new EmptyReservationSlot($expectedSlots[10], $expectedSlots[10], $slotDate, $expectedSlots[10]->IsReservable());
        $slot10 = new EmptyReservationSlot($expectedSlots[11], $expectedSlots[11], $slotDate, $expectedSlots[11]->IsReservable());
        $slot11 = new EmptyReservationSlot($expectedSlots[12], $expectedSlots[12], $slotDate, $expectedSlots[12]->IsReservable());
        $slot12 = new ReservationSlot($expectedSlots[13], $expectedSlots[count($expectedSlots) - 1], $slotDate, 15, $item3);

        $this->assertEquals(12, count($slots));

        $this->assertEquals($slot1, $slots[0]);
        $this->assertEquals($slot2, $slots[1]);
        $this->assertEquals($slot3, $slots[2]);
        $this->assertEquals($slot4, $slots[3]);
        $this->assertEquals($slot5, $slots[4]);
        $this->assertEquals($slot6, $slots[5]);
        $this->assertEquals($slot7, $slots[6]);
        $this->assertEquals($slot8, $slots[7]);
        $this->assertEquals($slot9, $slots[8]);
        $this->assertEquals($slot10, $slots[9]);
        $this->assertEquals($slot11, $slots[10]);
        $this->assertEquals($slot12, $slots[11], $slot12 . ' ' . $slots[11]);
    }

    public function testReservationStartingBeforeLayoutPeriodAndEndingAfterLayoutPeriodIsCreatedProperly()
    {
        $userTz = 'America/Chicago';
        $date = Date::Parse('2008-11-12', $userTz);

        $item = new TestReservationItemView(1, $date->AddDays(-1)->ToUtc(), $date->AddDays(1)->ToUtc());
        $r1 = new ReservationListItem($item);

        $reservations = [$r1];

        $list = new ScheduleReservationList($reservations, $this->testDbLayout, $date);
        $slots = $list->BuildSlots();
        $expectedSlots = $this->testDbLayout->GetLayout($date);

        $totalSlotsInDay = count($expectedSlots);
        $slot1 = new ReservationSlot($expectedSlots[0], $expectedSlots[$totalSlotsInDay - 1], $date, $totalSlotsInDay, $item);

        $this->assertEquals(1, count($slots));

        $this->assertEquals($slot1, $slots[0]);
    }

    public function testReservationStartingBeforeAndEndingOnDateStartsAtFirstSlot()
    {
        $userTz = 'America/Chicago';
        $layoutTz = 'America/New_York';
        $date = Date::Parse('2008-11-12', $userTz);

        $layout = new ScheduleLayout($userTz);
        $layout->AppendPeriod(new Time(0, 0, 0, $layoutTz), new Time(6, 0, 0, $layoutTz));
        $layout->AppendPeriod(new Time(6, 0, 0, $layoutTz), new Time(8, 0, 0, $layoutTz));
        $layout->AppendPeriod(new Time(8, 0, 0, $layoutTz), new Time(12, 0, 0, $layoutTz));
        $layout->AppendPeriod(new Time(12, 0, 0, $layoutTz), new Time(18, 0, 0, $layoutTz));
        $layout->AppendPeriod(new Time(18, 0, 0, $layoutTz), new Time(0, 0, 0, $layoutTz));

        $periods = $layout->GetLayout($date);

        $item = new TestReservationItemView(1, $date->AddDays(-1)->ToUtc(), Date::Parse(
            '2008-11-12 6:0:0',
            $layoutTz
        )->ToUtc());
        $r1 = new ReservationListItem($item);

        $list = new ScheduleReservationList([$r1], $layout, $date);
        $slots = $list->BuildSlots();

        $slot1 = new ReservationSlot($periods[0], $periods[0], $date, 1, $item);
        $slot6 = new EmptyReservationSlot($periods[5], $periods[5], $date, true);

        $this->assertEquals($slot1, $slots[0]);
        $this->assertEquals($slot6, $slots[5]);
    }

    public function testReservationStartingBeforeFirstPeriodAndEndingOnDateStartsAtFirstSlot()
    {
        $userTz = 'America/Chicago';
        $layoutTz = 'America/New_York';
        $date = Date::Parse('2008-11-12', $userTz);

        $layout = new ScheduleLayout($userTz);
        $layout->AppendBlockedPeriod(new Time(0, 0, 0, $layoutTz), new Time(6, 0, 0, $layoutTz));
        $layout->AppendPeriod(new Time(6, 0, 0, $layoutTz), new Time(8, 0, 0, $layoutTz));
        $layout->AppendPeriod(new Time(8, 0, 0, $layoutTz), new Time(12, 0, 0, $layoutTz));
        $layout->AppendPeriod(new Time(12, 0, 0, $layoutTz), new Time(18, 0, 0, $layoutTz));
        $layout->AppendPeriod(new Time(18, 0, 0, $layoutTz), new Time(0, 0, 0, $layoutTz));

        $periods = $layout->GetLayout($date);

        $item = new TestReservationItemView(1, Date::Parse('2008-11-12 4:0:0', $layoutTz)->ToUtc(), Date::Parse('2008-11-12 12:0:0', $layoutTz)->ToUtc());
        $r1 = new ReservationListItem($item);

        $list = new ScheduleReservationList([$r1], $layout, $date, true);
        $slots = $list->BuildSlots();

        $slot1 = new ReservationSlot($periods[0], $periods[2], $date, 3, $item);

        $this->assertEquals($slot1, $slots[0]);
    }

    public function testCanTellIfReservationOccursOnSpecifiedDates()
    {
        $startDate = Date::Parse('2009-11-1 0:0:0', 'UTC');
        $endDate = Date::Parse('2009-11-10 1:0:0', 'UTC');

        $reservation = new ReservationItemView(1, $startDate, $endDate);

        $this->assertTrue($reservation->OccursOn($startDate));
        $this->assertTrue($reservation->OccursOn($startDate->AddDays(2)));
        $this->assertTrue($reservation->OccursOn($endDate));

        $this->assertFalse($reservation->OccursOn($startDate->AddDays(-2)));
        $this->assertFalse($reservation->OccursOn($endDate->AddDays(2)));

        $this->assertFalse($reservation->OccursOn($startDate->AddDays(-2)));
        $this->assertFalse($reservation->OccursOn($endDate->AddDays(2)));

        $this->assertTrue($reservation->OccursOn($startDate->ToTimezone('America/Chicago')));
        $this->assertTrue($reservation->OccursOn($endDate->ToTimezone('America/Chicago')));
    }

    public function testReservationDoesNotOccurOnDateIfNoneOfTheReservationOccursAtAnyTimeOnThatDate()
    {
        $d1 = new Date('2009-10-09 22:00:00', 'UTC'); // 2009-10-09 17:00:00 CST
        $d2 = new Date('2009-10-09 23:00:00', 'UTC'); // 2009-10-09 18:00:00 CST

        $res1 = new ReservationItemView(1, $d1, $d2);

        $this->assertFalse($res1->OccursOn(Date::Parse('2009-10-10', 'CST')));
    }

    public function testDoesNotOccurOnDateIfEndsAtMidnightOfThatDate()
    {
        $date = new Date('2010-01-01 00:00:00', 'UTC');

        $builder = new ReservationItemViewBuilder();
        $builder
            ->WithStartDate($date->AddDays(-1))
            ->WithEndDate($date);

        $res = $builder->Build();

        $this->assertTrue($res->OccursOn($date->AddDays(-1)));
        $this->assertFalse($res->OccursOn($date));
    }

    public function testTwentyFourHourReservationsCrossDay()
    {
        $timezone = 'America/Chicago';
        $layout = new ScheduleLayout($timezone);
        $layout->AppendPeriod(Time::Parse('0:00', $timezone), Time::Parse('4:00', $timezone));
        $layout->AppendPeriod(Time::Parse('4:00', $timezone), Time::Parse('12:00', $timezone));
        $layout->AppendPeriod(Time::Parse('12:00', $timezone), Time::Parse('18:00', $timezone));
        $layout->AppendPeriod(Time::Parse('18:00', $timezone), Time::Parse('20:00', $timezone));
        $layout->AppendPeriod(Time::Parse('20:00', $timezone), Time::Parse('0:00', $timezone));

        $date = new Date('2010-01-02', $timezone);

        $r1 = new ReservationListItem(new TestReservationItemView(
            1,
            Date::Parse('2010-01-01 00:00:00', 'UTC'),
            Date::Parse('2010-01-02 00:00:00', 'UTC'),
            1
        ));
        $r2 = new ReservationListItem(new TestReservationItemView(
            2,
            Date::Parse('2010-01-02 00:00:00', 'UTC'),
            Date::Parse('2010-01-03 00:00:00', 'UTC'),
            1
        ));
        $r3 = new ReservationListItem(new TestReservationItemView(
            3,
            Date::Parse('2010-01-03 00:00:00', 'UTC'),
            Date::Parse('2010-01-04 00:00:00', 'UTC'),
            1
        ));

        $list = new ScheduleReservationList([$r1, $r2, $r3], $layout, $date);

        $slots = $list->BuildSlots();

        $this->assertEquals(2, count($slots));
        $this->assertEquals($slots[0]->Begin(), new Time(0, 0, 0, $timezone));
        $this->assertEquals($slots[1]->Begin(), new Time(18, 0, 0, $timezone));
    }

    public function testBindsSingleReservation()
    {
        $tz = 'America/Chicago';
        $listDate = Date::Parse('2011-02-06', $tz);

        $layout = new ScheduleLayout($tz);
        $layout->AppendPeriod(Time::Parse('0:00', $tz), Time::Parse('2:00', $tz));
        $layout->AppendPeriod(Time::Parse('2:00', $tz), Time::Parse('6:00', $tz));
        $layout->AppendPeriod(Time::Parse('6:00', $tz), Time::Parse('12:00', $tz));
        $layout->AppendPeriod(Time::Parse('12:00', $tz), Time::Parse('18:00', $tz));
        $layout->AppendPeriod(Time::Parse('18:00', $tz), Time::Parse('0:00', $tz));
        $periods = $layout->GetLayout($listDate);

        $item = new TestReservationItemView(
            1,
            Date::Parse('2011-02-06 12:00:00', $tz)->ToUtc(),
            Date::Parse('2011-02-06 18:00:00', $tz)->ToUtc(),
            1
        );
        $r1 = new ReservationListItem($item);

        $list = new ScheduleReservationList([$r1], $layout, $listDate);

        $slots = $list->BuildSlots();

        $this->assertEquals(5, count($slots));
        $this->assertEquals(new ReservationSlot($periods[3], $periods[3], $listDate, 1, $item), $slots[3]);
    }

    public function testReservationStartingOffPeriodBoundaryAdjusts()
    {
        $tz = 'America/Chicago';
        $listDate = Date::Parse('2011-02-06', $tz);

        $layout = new ScheduleLayout($tz);
        $layout->AppendPeriod(Time::Parse('0:00', $tz), Time::Parse('2:00', $tz));
        $layout->AppendPeriod(Time::Parse('2:00', $tz), Time::Parse('6:00', $tz));
        $layout->AppendPeriod(Time::Parse('6:00', $tz), Time::Parse('12:00', $tz));

        $periods = $layout->GetLayout($listDate);

        $item1 = new TestReservationItemView(
            1,
            Date::Parse('2011-02-06 1:00:00', $tz)->ToUtc(),
            Date::Parse('2011-02-06 5:00:00', $tz)->ToUtc(),
            1
        );
        $item2 = new TestReservationItemView(
            2,
            Date::Parse('2011-02-06 7:00:00', $tz)->ToUtc(),
            Date::Parse('2011-02-06 11:00:00', $tz)->ToUtc(),
            1
        );
        $r1 = new ReservationListItem($item1);
        $r2 = new ReservationListItem($item2);

        $list = new ScheduleReservationList([$r1, $r2], $layout, $listDate);

        $slots = $list->BuildSlots();

        $this->assertEquals(2, count($slots));
        $this->assertEquals(new ReservationSlot($periods[0], $periods[1], $listDate, 2, $item1), $slots[0]);
        $this->assertEquals(new ReservationSlot($periods[2], $periods[2], $listDate, 1, $item2), $slots[1]);
    }

    public function testReservationEndingAtBeginningOfFirstPeriodDoesNotExistOnThatDay()
    {
        $tz = 'America/Chicago';
        $listDate = Date::Parse('2011-02-07', $tz);

        $layout = new ScheduleLayout($tz);
        $layout->AppendPeriod(Time::Parse('0:00', $tz), Time::Parse('2:00', $tz));
        $layout->AppendPeriod(Time::Parse('2:00', $tz), Time::Parse('6:00', $tz));
        $layout->AppendPeriod(Time::Parse('6:00', $tz), Time::Parse('0:00', $tz));
        $periods = $layout->GetLayout($listDate);

        $item = new TestReservationItemView(
            1,
            Date::Parse('2011-02-06 6:00:00', $tz)->ToUtc(),
            Date::Parse('2011-02-07 0:00:00', $tz)->ToUtc(),
            1
        );
        $r1 = new ReservationListItem($item);

        $list = new ScheduleReservationList([$r1], $layout, $listDate);

        $slots = $list->BuildSlots();

        $this->assertEquals(new EmptyReservationSlot($periods[0], $periods[0], $listDate, true), $slots[0]);
    }

    public function testFullDayReservationOnWithHiddenBlockedPeriodsBlocksFullDay()
    {
        $tz = 'America/Chicago';
        $listDate = Date::Parse('2011-02-07', $tz);

        $layout = new ScheduleLayout($tz);
        $layout->AppendBlockedPeriod(Time::Parse('0:00', $tz), Time::Parse('2:00', $tz));
        $layout->AppendPeriod(Time::Parse('2:00', $tz), Time::Parse('6:00', $tz));
        $layout->AppendBlockedPeriod(Time::Parse('6:00', $tz), Time::Parse('0:00', $tz));

        $item = new TestBlackoutItemView(
            1,
            Date::Parse('2011-02-07 0:00:00', $tz)->ToUtc(),
            Date::Parse('2011-02-08 0:00:00', $tz)->ToUtc(),
            1
        );
        $r1 = new BlackoutListItem($item);

        $list = new ScheduleReservationList([$r1], $layout, $listDate, true);

        $slots = $list->BuildSlots();

        $periods = $layout->GetLayout($listDate, true);
        $expectedPeriod = $periods[0];
        $this->assertEquals(new BlackoutSlot($expectedPeriod, $expectedPeriod, $listDate, 1, $item), $slots[0]);
    }

    public function testItemIsNotIncludedIfItEndsBeforeFirstDisplayedPeriod()
    {
        $tz = 'America/Chicago';
        $listDate = Date::Parse('2011-02-08', $tz);

        $layout = new ScheduleLayout($tz);
        $layout->AppendBlockedPeriod(Time::Parse('0:00', $tz), Time::Parse('2:00', $tz));
        $layout->AppendPeriod(Time::Parse('2:00', $tz), Time::Parse('6:00', $tz));
        $layout->AppendBlockedPeriod(Time::Parse('6:00', $tz), Time::Parse('0:00', $tz));

        $item = new TestBlackoutItemView(
            1,
            Date::Parse('2011-02-07 0:00', $tz)->ToUtc(),
            Date::Parse('2011-02-08 1:00', $tz)->ToUtc(),
            1
        );
        $r1 = new BlackoutListItem($item);

        $list = new ScheduleReservationList([$r1], $layout, $listDate, true);

        $slots = $list->BuildSlots();

        $periods = $layout->GetLayout($listDate, true);
        $expectedPeriod = $periods[0];
        $this->assertEquals(new EmptyReservationSlot($expectedPeriod, $expectedPeriod, $listDate, true), $slots[0]);
    }

    public function testItemIsNotIncludedIfItStartsAfterLastDisplayedPeriod()
    {
        $tz = 'America/Chicago';
        $listDate = Date::Parse('2011-02-08', $tz);

        $layout = new ScheduleLayout($tz);
        $layout->AppendBlockedPeriod(Time::Parse('0:00', $tz), Time::Parse('2:00', $tz));
        $layout->AppendPeriod(Time::Parse('2:00', $tz), Time::Parse('6:00', $tz));
        $layout->AppendBlockedPeriod(Time::Parse('6:00', $tz), Time::Parse('0:00', $tz));

        $item = new TestBlackoutItemView(
            1,
            Date::Parse('2011-02-08 6:00', $tz)->ToUtc(),
            Date::Parse('2011-02-09 1:00', $tz)->ToUtc(),
            1
        );
        $r1 = new BlackoutListItem($item);

        $list = new ScheduleReservationList([$r1], $layout, $listDate, true);

        $slots = $list->BuildSlots();

        $periods = $layout->GetLayout($listDate, true);
        $expectedPeriod = $periods[0];
        $this->assertEquals(new EmptyReservationSlot($expectedPeriod, $expectedPeriod, $listDate, true), $slots[0]);
    }

    public function testAddsSetupAndTearDownItemsIfTheReservationHasThem()
    {
        $tz = 'America/Chicago';
        $listDate = Date::Parse('2011-02-08', $tz);

        $layout = new ScheduleLayout($tz);
        $layout->AppendPeriod(Time::Parse('0:00', $tz), Time::Parse('0:30', $tz));
        $layout->AppendPeriod(Time::Parse('0:30', $tz), Time::Parse('1:30', $tz));
        $layout->AppendPeriod(Time::Parse('1:30', $tz), Time::Parse('2:00', $tz));
        $layout->AppendPeriod(Time::Parse('2:00', $tz), Time::Parse('6:00', $tz));
        $layout->AppendPeriod(Time::Parse('6:00', $tz), Time::Parse('0:00', $tz));

        $item = new TestReservationItemView(
            id: 1,
            startDate: Date::Parse('2011-02-08 2:00', $tz)->ToUtc(),
            endDate: Date::Parse('2011-02-08 6:00', $tz)->ToUtc(),
            resourceId: 1,
            referenceNumber: 30
        );
        $item->WithBufferTime(60 * 60);
        $r1 = new ReservationListItem($item);

        $list = new ScheduleReservationList([$r1], $layout, $listDate, false);

        /** @var IReservationSlot[] $slots */
        $slots = $list->BuildSlots();

        $this->assertEquals(4, count($slots));

        $this->assertTrue($slots[1]->Begin()->Equals(Time::Parse('0:30', $tz)));
        $this->assertTrue($slots[1]->End()->Equals(Time::Parse('2:00', $tz)));
        $this->assertInstanceOf('BufferSlot', $slots[1]);

        $this->assertTrue($slots[2]->Begin()->Equals(Time::Parse('2:00', $tz)));
        $this->assertTrue($slots[2]->End()->Equals(Time::Parse('6:00', $tz)));
        $this->assertInstanceOf('ReservationSlot', $slots[2]);

        $this->assertTrue($slots[3]->Begin()->Equals(Time::Parse('6:00', $tz)));
        $this->assertTrue($slots[3]->End()->Equals(Time::Parse('0:00', $tz)));
        $this->assertInstanceOf('BufferSlot', $slots[3]);
    }

    public function testSkipsAddingBufferSlotsIfThereIsAnotherItemAtThatTime()
    {
        $tz = 'America/Chicago';
        $listDate = Date::Parse('2011-02-08', $tz);

        $layout = new ScheduleLayout($tz);
        $layout->AppendPeriod(Time::Parse('0:00', $tz), Time::Parse('0:30', $tz));
        $layout->AppendPeriod(Time::Parse('0:30', $tz), Time::Parse('1:00', $tz));
        $layout->AppendPeriod(Time::Parse('1:00', $tz), Time::Parse('1:30', $tz));
        $layout->AppendPeriod(Time::Parse('1:30', $tz), Time::Parse('2:00', $tz));
        $layout->AppendPeriod(Time::Parse('2:00', $tz), Time::Parse('6:00', $tz));
        $layout->AppendPeriod(Time::Parse('6:00', $tz), Time::Parse('0:00', $tz));

        $item1 = new TestReservationItemView(
            1,
            Date::Parse('2011-02-08 0:30', $tz)->ToUtc(),
            Date::Parse('2011-02-08 1:00', $tz)->ToUtc(),
            1
        );
        $item2 = new TestReservationItemView(
            2,
            Date::Parse('2011-02-08 1:30', $tz)->ToUtc(),
            Date::Parse('2011-02-08 2:00', $tz)->ToUtc(),
            1
        );
        $item1->WithBufferTime(60 * 60);
        $item2->WithBufferTime(60 * 60);
        $r1 = new ReservationListItem($item1);
        $r2 = new ReservationListItem($item2);

        $list = new ScheduleReservationList([$r1, $r2], $layout, $listDate, false);

        /** @var IReservationSlot[] $slots */
        $slots = $list->BuildSlots();

        $this->assertTrue($slots[0]->Begin()->Equals(Time::Parse('0:00', $tz)));
        $this->assertTrue($slots[0]->End()->Equals(Time::Parse('0:30', $tz)));
        $this->assertInstanceOf('BufferSlot', $slots[0]);

        $this->assertTrue($slots[1]->Begin()->Equals(Time::Parse('0:30', $tz)));
        $this->assertTrue($slots[1]->End()->Equals(Time::Parse('1:00', $tz)));
        $this->assertInstanceOf('ReservationSlot', $slots[1]);

        $this->assertTrue($slots[2]->Begin()->Equals(Time::Parse('1:00', $tz)));
        $this->assertTrue($slots[2]->End()->Equals(Time::Parse('1:30', $tz)));
        $this->assertInstanceOf('EmptyReservationSlot', $slots[2]);

        $this->assertTrue($slots[3]->Begin()->Equals(Time::Parse('1:30', $tz)));
        $this->assertTrue($slots[3]->End()->Equals(Time::Parse('2:00', $tz)));
        $this->assertInstanceOf('ReservationSlot', $slots[3]);

        $this->assertTrue($slots[4]->Begin()->Equals(Time::Parse('2:00', $tz)));
        $this->assertTrue($slots[4]->End()->Equals(Time::Parse('6:00', $tz)));
        $this->assertInstanceOf('BufferSlot', $slots[0]);
    }
}
