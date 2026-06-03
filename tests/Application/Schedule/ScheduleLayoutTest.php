<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

class ScheduleLayoutTest extends TestBase
{
    private $date;

    public function setUp(): void
    {
        parent::setup();
        $this->date = Date::Parse('2011-03-01', 'America/Chicago');
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testConvertingEasternLayoutToCentralPreAndPostDaylightSavings()
    {
        $cst = 'America/Chicago';
        $est = 'America/New_York';

        $layout = new ScheduleLayout($cst);

        $layout->AppendPeriod(Time::Parse('00:00', $est), Time::Parse('06:00', $est));
        $layout->AppendPeriod(Time::Parse('06:00', $est), Time::Parse('08:00', $est));
        $layout->AppendPeriod(Time::Parse('08:00', $est), Time::Parse('12:00', $est));
        $layout->AppendPeriod(Time::Parse('12:00', $est), Time::Parse('18:00', $est));
        $layout->AppendPeriod(Time::Parse('18:00', $est), Time::Parse('00:00', $est));

        $preDst = new Date('2011-03-12', $cst);
        $onDst = new Date('2011-03-13', $cst);
        $postDst = new Date('2011-03-14', $cst);
        $endDst = new Date('2011-11-06', $cst);

        foreach ([$preDst, $onDst, $postDst, $endDst] as $date) {
            //echo '-----TEST-----';
            $slots = $layout->GetLayout($date);
            //echo '-----TEST-----';
            //die();
            $this->assertEquals(6, count($slots));

            $month = $date->Month();
            $day = $date->Day();
            $tomorrow = $day + 1;
            $yesterday = $day - 1;
            $firstSlot = new SchedulePeriod(new Date("2011-$month-$yesterday 23:00", $cst), new Date("2011-$month-$day 05:00", $cst));
            $lastSlot = new SchedulePeriod(new Date("2011-$month-$day 23:00", $cst), new Date("2011-$month-$tomorrow 05:00", $cst));
            $this->assertEquals($firstSlot, $slots[0], "Testing first slot on date $date");
            $this->assertEquals($lastSlot, $slots[5], "Testing last slot on date $date");
        }
    }

    public function testLayoutCanBeCreatedAsCSTFromPSTTimes()
    {
        $userTz = 'America/Chicago';
        $periodTz = 'America/Los_Angeles';

        $date = Date::Parse('2011-01-01', $periodTz);

        $t1s = $date->SetTime(new Time(0, 0, 0));
        $t1e = $date->SetTime(new Time(1, 0, 0));
        $t2e = $date->SetTime(new Time(21, 0, 0));

        $layout = new ScheduleLayout($userTz);
        $layout->AppendBlockedPeriod($t1s->GetTime(), $t1e->GetTime());
        $layout->AppendPeriod($t1e->GetTime(), $t2e->GetTime());
        $layout->AppendBlockedPeriod($t2e->GetTime(), $t1s->GetTime());

        //echo '-----TEST-----';
        $slots = $layout->GetLayout(Date::Parse('2011-01-01', $userTz));
        //echo '//-----TEST-----//';
        //die();
        $this->assertEquals(4, count($slots), '21:00 PST - 0:00 PST crosses midnight when converted to CST');
        $firstSlot = new NonSchedulePeriod(new Date('2010-12-31 23:00', $userTz), new Date('2011-01-01 02:00', $userTz));
        $slot2 = new NonSchedulePeriod(new Date('2011-01-01 02:00', $userTz), new Date('2011-01-01 03:00', $userTz));
        $slot3 = new SchedulePeriod(new Date('2011-01-01 03:00', $userTz), new Date('2011-01-01 23:00', $userTz));
        $lastSlot = new NonSchedulePeriod(new Date('2011-01-01 23:00', $userTz), new Date('2011-01-02 02:00', $userTz));

        $this->assertEquals($firstSlot, $slots[0]);
        $this->assertEquals($slot2, $slots[1]);
        $this->assertEquals($slot3, $slots[2]);
        $this->assertEquals($lastSlot, $slots[3]);
        //		$this->assertEquals(new Time(0, 0, 0, $userTz), $slots[0]->Begin());
        //		$this->assertEquals($t1s->ToTimezone($userTz)->GetTime(), $slots[0]->End());
        //
        //		$this->assertEquals($t1s->ToTimezone($userTz)->GetTime(), $slots[1]->Begin(), $slots[1]->Begin()->ToString());
        //		$this->assertEquals($t1e->ToTimezone($userTz)->GetTime(), $slots[1]->End(), $slots[1]->End()->ToString());
        //
        //		$this->assertEquals($t1e->ToTimezone($userTz)->GetTime(), $slots[2]->Begin());
        //		$this->assertEquals($t2e->ToTimezone($userTz)->GetTime(), $slots[2]->End());
        //
        //		$this->assertEquals($t2e->ToTimezone($userTz)->GetTime(), $slots[3]->Begin());
        //		$this->assertEquals(new Time(0, 0, 0, $userTz), $slots[3]->End());
    }

    public function testCreatesScheduleLayoutInProperOrder()
    {
        $utc = 'UTC';

        $time1 = Time::Parse('07:00', $utc);
        $time2 = Time::Parse('07:45', $utc);
        $time3 = Time::Parse('08:30', $utc);
        $time4 = Time::Parse('13:00', $utc);

        $layout = new ScheduleLayout($utc);

        $layout->AppendPeriod($time1, $time2);
        $layout->AppendPeriod($time3, $time4);
        $layout->AppendPeriod($time2, $time3, 'Period 1');

        $periods = $layout->GetLayout($this->date);

        $utcDate = $this->date->ToUtc();

        $this->assertEquals(3, count($periods));
        $period1 = new SchedulePeriod($utcDate->SetTime($time1), $utcDate->SetTime($time2));
        $period2 = new SchedulePeriod($utcDate->SetTime($time2), $utcDate->SetTime($time3), 'Period 1');
        $period3 = new SchedulePeriod($utcDate->SetTime($time3), $utcDate->SetTime($time4));

        $this->assertEquals($period1, $periods[0], $period1 . ' ' . $periods[0]);
        $this->assertEquals($period2, $periods[1], $period2 . ' ' . $periods[1]);
        $this->assertEquals($period3, $periods[2]);

        $dailyPeriods = $layout->GetSlots(null);
        $this->assertEquals(3, count($dailyPeriods));
        $this->assertEquals(new LayoutPeriod($time1, $time2), $dailyPeriods[0]);
        $this->assertEquals(new LayoutPeriod($time2, $time3, PeriodTypes::RESERVABLE, 'Period 1'), $dailyPeriods[1]);
    }

    public function testCreatesScheduleLayoutForSpecifiedTimezone()
    {
        $layout = new ScheduleLayout('CST');
        $startUtc = new Time(0, 0, 0, 'UTC');
        $endUtc = new Time(10, 0, 0, 'UTC');
        $layout->AppendPeriod($startUtc, $endUtc);
        $layout->AppendPeriod($endUtc, $startUtc);

        //echo '--TEST--';
        $periods = $layout->GetLayout(Date::Parse('2010-01-01', 'CST'));
        //echo '//TEST--';
        //die();
        $this->assertEquals(3, count($periods));

        $utcDate = $this->date->ToUtc();

        $firstBegin = Date::Parse('2010-01-01 0:00:00', 'UTC')->ToTimezone('CST');
        $firstEnd = Date::Parse('2010-01-01 10:00:00', 'UTC')->ToTimezone('CST');
        $secondBegin = Date::Parse('2010-01-01 10:00:00', 'UTC')->ToTimezone('CST');
        $secondEnd = Date::Parse('2010-01-02 0:00:00', 'UTC')->ToTimezone('CST');

        $this->assertTrue($firstBegin->Equals($periods[0]->BeginDate()));
        $this->assertTrue($firstEnd->Equals($periods[0]->EndDate()));
        $this->assertTrue($secondBegin->Equals($periods[1]->BeginDate()));
        $this->assertTrue($secondEnd->Equals($periods[1]->EndDate()));
    }

    public function testCanParseFromStrings()
    {
        $timezone = 'America/Chicago';
        $reservableSlots = "00:00 - 01:00 Label 1 A\n1:00- 2:00\r\n02:00 -3:30\n03:30-12:00\r\n";
        $blockedSlots = "12:00 - 15:00 Blocked 1 A\n15:00- 20:00\r\n20:00 -0:00\n";

        $layout = ScheduleLayout::Parse($timezone, $reservableSlots, $blockedSlots);

        $slots = $layout->GetSlots();

        $this->assertFalse($layout->UsesDailyLayouts());
        $this->assertEquals($timezone, $layout->Timezone());
        $this->assertEquals(7, count($slots));

        $start1 = Time::Parse('00:00', $timezone);
        $end1 = Time::Parse('01:00', $timezone);
        $label1 = 'Label 1 A';

        $start4 = Time::Parse('12:00', $timezone);
        $end4 = Time::Parse('15:00', $timezone);
        $label4 = 'Blocked 1 A';

        $this->assertEquals(new LayoutPeriod($start1, $end1, PeriodTypes::RESERVABLE, $label1), $slots[0]);
        $this->assertEquals(new LayoutPeriod($start4, $end4, PeriodTypes::NONRESERVABLE, $label4), $slots[4]);
    }

    public function testCanParseDailyFromStrings()
    {
        $timezone = 'America/Chicago';
        $days = DayOfWeek::Days();

        $reservableSlots = [];
        $blockedSlots = [];

        foreach ($days as $day) {
            $reservableSlots[$day] = "00:00 - 01:00 Label $day A\n1:00- 2:00\r\n02:00 -3:30\n03:30-12:00\r\n";
            $blockedSlots[$day] = "12:00 - 15:00 Blocked $day A\n15:00- 20:00\r\n20:00 -0:00\n";
        }

        $layout = ScheduleLayout::ParseDaily($timezone, $reservableSlots, $blockedSlots);

        $this->assertTrue($layout->UsesDailyLayouts());
        $this->assertEquals($timezone, $layout->Timezone());

        foreach ($days as $day) {
            $slots = $layout->GetSlots($day);
            $this->assertEquals(7, count($slots));
            $start1 = Time::Parse('00:00', $timezone);
            $end1 = Time::Parse('01:00', $timezone);

            $start4 = Time::Parse('12:00', $timezone);
            $end4 = Time::Parse('15:00', $timezone);

            $this->assertEquals(new LayoutPeriod($start1, $end1, PeriodTypes::RESERVABLE, "Label $day A"), $slots[0]);
            $this->assertEquals(
                new LayoutPeriod($start4, $end4, PeriodTypes::NONRESERVABLE, "Blocked $day A"),
                $slots[4]
            );
        }
    }

    public function testCanGetPeriodForTime()
    {
        $timezone = 'America/Chicago';
        $reservableSlots = "00:00 - 01:00 Label 1 A\n1:00- 2:00\r\n02:00 -3:30\n03:30-12:00\r\n";
        $blockedSlots = "12:00 - 15:00 Blocked 1 A\n15:00- 20:00\r\n20:00 -0:00\n";

        $layout = ScheduleLayout::Parse($timezone, $reservableSlots, $blockedSlots);

        $actual1 = $layout->GetPeriod(Date::Parse('2012-01-01 15:30:00', $timezone));
        $actual2 = $layout->GetPeriod(Date::Parse('2012-01-01 02:00:00', $timezone));

        $this->assertEquals(
            new NonSchedulePeriod(Date::Parse(
                '2012-01-01 15:00',
                $timezone
            ), Date::Parse('2012-01-01 20:00', $timezone)),
            $actual1
        );
        $this->assertEquals(
            new SchedulePeriod(Date::Parse(
                '2012-01-01 02:00',
                $timezone
            ), Date::Parse('2012-01-01 03:30', $timezone)),
            $actual2
        );
    }

    public function testTokyoDefect()
    {
        $tokyo = 'Asia/Tokyo';
        $layout = new ScheduleLayout('America/New_York');
        $layout->AppendBlockedPeriod(Time::Parse('00:00', $tokyo), Time::Parse('08:00', $tokyo));
        for ($i = 8; $i < 20; $i++) {
            $layout->AppendPeriod(Time::Parse("$i:00", $tokyo), Time::Parse($i + 1 . ':00', $tokyo));
        }
        $layout->AppendBlockedPeriod(Time::Parse('20:00', $tokyo), Time::Parse('00:00', $tokyo));

        $l = $layout->GetLayout(Date::Parse('2012-06-18', 'America/New_York'));

        $this->assertEquals($l[0]->BeginDate(), Date::Parse('2012-06-18', 'America/New_York'));
        $this->assertEquals($l[count($l) - 1]->EndDate(), Date::Parse('2012-06-19', 'America/New_York'));
    }

    public function testWhenFindingPeriodInCSTWithUTCDate()
    {
        $layoutTz = 'America/Chicago';
        $scheduleLayoutFactory = new ScheduleLayoutFactory('UTC');
        $layout = $scheduleLayoutFactory->CreateLayout();

        $layout->AppendPeriod(Time::Parse('00:00', $layoutTz), Time::Parse('12:00', $layoutTz));
        $layout->AppendPeriod(Time::Parse('12:00', $layoutTz), Time::Parse('12:30', $layoutTz));
        $layout->AppendPeriod(Time::Parse('12:30', $layoutTz), Time::Parse('13:00', $layoutTz));
        $layout->AppendPeriod(Time::Parse('13:00', $layoutTz), Time::Parse('13:30', $layoutTz));
        $layout->AppendPeriod(Time::Parse('13:30', $layoutTz), Time::Parse('14:00', $layoutTz));
        $layout->AppendPeriod(Time::Parse('14:00', $layoutTz), Time::Parse('14:30', $layoutTz));
        $layout->AppendPeriod(Time::Parse('14:30', $layoutTz), Time::Parse('00:00', $layoutTz));

        $date = Date::Parse('2012-11-26 19:30', 'UTC');
        $period = $layout->GetPeriod($date);

        $this->assertTrue($date->Equals($period->BeginDate()));
    }

    public function testDSTBug()
    {
        $layoutTz = 'America/Chicago';
        $scheduleLayoutFactory = new ScheduleLayoutFactory('UTC');
        $layout = $scheduleLayoutFactory->CreateLayout();

        $layout->AppendPeriod(Time::Parse('00:00', $layoutTz), Time::Parse('1:00', $layoutTz));
        $layout->AppendPeriod(Time::Parse('1:00', $layoutTz), Time::Parse('2:00', $layoutTz));
        $layout->AppendPeriod(Time::Parse('2:00', $layoutTz), Time::Parse('2:30', $layoutTz));
        $layout->AppendPeriod(Time::Parse('2:30', $layoutTz), Time::Parse('3:00', $layoutTz));
        $layout->AppendPeriod(Time::Parse('3:00', $layoutTz), Time::Parse('3:30', $layoutTz));
        $layout->AppendPeriod(Time::Parse('3:30', $layoutTz), Time::Parse('4:00', $layoutTz));
        $layout->AppendPeriod(Time::Parse('4:00', $layoutTz), Time::Parse('12:00', $layoutTz));
        $layout->AppendPeriod(Time::Parse('12:00', $layoutTz), Time::Parse('12:30', $layoutTz));
        $layout->AppendPeriod(Time::Parse('12:30', $layoutTz), Time::Parse('13:00', $layoutTz));
        $layout->AppendPeriod(Time::Parse('13:00', $layoutTz), Time::Parse('13:30', $layoutTz));
        $layout->AppendPeriod(Time::Parse('13:30', $layoutTz), Time::Parse('14:00', $layoutTz));
        $layout->AppendPeriod(Time::Parse('14:00', $layoutTz), Time::Parse('14:30', $layoutTz));
        $layout->AppendPeriod(Time::Parse('14:30', $layoutTz), Time::Parse('00:00', $layoutTz));

        $date = Date::Parse('2015-03-08 12:30', $layoutTz);
        $period = $layout->GetPeriod($date);

        $this->assertTrue($date->Equals($period->BeginDate()));
    }

    public function testGetsLayoutForEachDayOfWeek()
    {
        $utc = 'UTC';
        $sunday = Date::Parse('2013-01-06 00:00', $utc);
        $monday = Date::Parse('2013-01-07 20:00', $utc);
        $utcDate = $sunday->ToUtc();

        $midnight = Time::Parse('00:00', $utc);
        $time1 = Time::Parse('07:00', $utc);
        $time2 = Time::Parse('07:45', $utc);
        $time3 = Time::Parse('08:30', $utc);
        $time4 = Time::Parse('13:00', $utc);

        $layout = new ScheduleLayout($utc);

        $layout->AppendBlockedPeriod($time1, $time2, null, DayOfWeek::SUNDAY);
        $layout->AppendPeriod($midnight, $time1, null, DayOfWeek::SUNDAY);
        $layout->AppendPeriod($time2, $time3, 'Period 1', DayOfWeek::SUNDAY);
        $layout->AppendPeriod($time3, $time4, null, DayOfWeek::SUNDAY);
        $layout->AppendPeriod($time4, $midnight, 'Period 2', DayOfWeek::SUNDAY);

        $layout->AppendBlockedPeriod($midnight, $midnight, 'Monday Period', DayOfWeek::MONDAY);

        $sundayPeriods = $layout->GetLayout($sunday);
        $mondayPeriods = $layout->GetLayout($monday);

        $this->assertTrue($layout->UsesDailyLayouts());
        $this->assertEquals(5, count($sundayPeriods));
        $period1 = new SchedulePeriod($utcDate->SetTime($midnight), $utcDate->SetTime($time1));
        $period2 = new NonSchedulePeriod($utcDate->SetTime($time1), $utcDate->SetTime($time2));
        $period3 = new SchedulePeriod($utcDate->SetTime($time2), $utcDate->SetTime($time3), 'Period 1');
        $period4 = new SchedulePeriod($utcDate->SetTime($time3), $utcDate->SetTime($time4));
        $period5 = new SchedulePeriod($utcDate->SetTime($time4), $utcDate->SetTime($midnight, true), 'Period 2');

        $this->assertEquals($period1, $sundayPeriods[0], $period1 . ' ' . $sundayPeriods[0]);
        $this->assertEquals($period2, $sundayPeriods[1], $period2 . ' ' . $sundayPeriods[1]);
        $this->assertEquals($period3, $sundayPeriods[2]);
        $this->assertEquals($period4, $sundayPeriods[3]);
        $this->assertEquals($period5, $sundayPeriods[4]);

        $this->assertEquals(1, count($mondayPeriods));
        $utcDate = $monday->ToUtc();
        $period1 = new NonSchedulePeriod($utcDate->SetTime($midnight), $utcDate->SetTime(
            $midnight,
            true
        ), 'Monday Period');
        $this->assertEquals($period1, $mondayPeriods[0], 'Expected ' . $period1 . ' Actual ' . $mondayPeriods[0]);

        $sundayDailyPeriods = $layout->GetSlots(DayOfWeek::SUNDAY);
        $mondayDailyPeriods = $layout->GetSlots(DayOfWeek::MONDAY);

        $this->assertEquals(5, count($sundayDailyPeriods));
        $this->assertEquals(new LayoutPeriod($midnight, $time1, PeriodTypes::RESERVABLE, null), $sundayDailyPeriods[0]);
        $this->assertEquals(1, count($mondayDailyPeriods));
        $this->assertEquals(
            new LayoutPeriod($midnight, $midnight, PeriodTypes::NONRESERVABLE, 'Monday Period'),
            $mondayDailyPeriods[0]
        );
    }

    public function testFullDayPeriod()
    {
        $utc = 'UTC';
        $sunday = Date::Parse('2013-01-06 00:00', 'America/Chicago');
        $utcDate = $sunday->ToUtc();
        $midnight = Time::Parse('00:00', $utc);

        $layout = new ScheduleLayout($utc);
        $layout->AppendPeriod($midnight, $midnight);

        $periods = $layout->GetLayout($sunday);

        $this->assertEquals(1, count($periods));
        $period1 = new SchedulePeriod($utcDate->SetTime($midnight), $utcDate->SetTime($midnight, true));
        $this->assertEquals($period1, $periods[0], 'Expected ' . $period1 . ' Actual ' . $periods[0]);
    }

    public function testWhenFindingPeriodWithDailyLayoutAcrossTimezone()
    {
        $layoutTz = 'America/New_York';
        $targetTimezone = 'America/Chicago';
        $scheduleLayoutFactory = new ScheduleLayoutFactory($targetTimezone);
        $layout = $scheduleLayoutFactory->CreateLayout();

        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::SUNDAY
        );
        $layout->AppendBlockedPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('01:00', $layoutTz),
            null,
            DayOfWeek::MONDAY
        );
        $layout->AppendBlockedPeriod(
            Time::Parse('01:00', $layoutTz),
            Time::Parse('08:00', $layoutTz),
            null,
            DayOfWeek::MONDAY
        );
        $layout->AppendPeriod(
            Time::Parse('08:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::MONDAY
        );
        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::TUESDAY
        );
        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::WEDNESDAY
        );
        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::THURSDAY
        );
        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::FRIDAY
        );
        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::SATURDAY
        );

        $sunPeriod = $layout->GetPeriod(Date::Parse('2012-12-30 22:00', $targetTimezone));
        $monPeriod = $layout->GetPeriod(Date::Parse('2012-12-30 23:00', $targetTimezone));
        $monPeriod2 = $layout->GetPeriod(Date::Parse('2012-12-31 00:00', $targetTimezone));
        $monPeriod3 = $layout->GetPeriod(Date::Parse('2012-12-31 07:00', $targetTimezone));
        $tuePeriod = $layout->GetPeriod(Date::Parse('2013-01-01 12:00', $targetTimezone));

        $this->assertTrue($sunPeriod->BeginDate()->Equals(Date::Parse('2012-12-30 00:00', $layoutTz)));
        $this->assertTrue($monPeriod->BeginDate()->Equals(Date::Parse('2012-12-31 00:00', $layoutTz)));
        $this->assertTrue($monPeriod2->BeginDate()->Equals(Date::Parse('2012-12-31 01:00', $layoutTz)));
        $this->assertTrue($monPeriod3->BeginDate()->Equals(Date::Parse('2012-12-31 08:00', $layoutTz)));
        $this->assertTrue($monPeriod3->IsReservable());
        $this->assertTrue($tuePeriod->BeginDate()->Equals(Date::Parse('2013-01-01 00:00', $layoutTz)));
    }

    public function testDailyLayoutWithEarlierRequestedTimezone()
    {
        $layoutTz = 'America/New_York';
        $targetTimezone = 'America/Chicago';

        $scheduleLayoutFactory = new ScheduleLayoutFactory($targetTimezone);
        $layout = $scheduleLayoutFactory->CreateLayout();

        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::SUNDAY
        );

        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('01:00', $layoutTz),
            null,
            DayOfWeek::MONDAY
        );
        $layout->AppendPeriod(
            Time::Parse('01:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::MONDAY
        );

        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('23:00', $layoutTz),
            null,
            DayOfWeek::TUESDAY
        );
        $layout->AppendPeriod(
            Time::Parse('23:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::TUESDAY
        );

        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('00:30', $layoutTz),
            null,
            DayOfWeek::WEDNESDAY
        );
        $layout->AppendPeriod(
            Time::Parse('00:30', $layoutTz),
            Time::Parse('01:00', $layoutTz),
            null,
            DayOfWeek::WEDNESDAY
        );
        $layout->AppendPeriod(
            Time::Parse('01:00', $layoutTz),
            Time::Parse('02:30', $layoutTz),
            null,
            DayOfWeek::WEDNESDAY
        );
        $layout->AppendPeriod(
            Time::Parse('02:30', $layoutTz),
            Time::Parse('22:30', $layoutTz),
            null,
            DayOfWeek::WEDNESDAY
        );
        $layout->AppendPeriod(
            Time::Parse('22:30', $layoutTz),
            Time::Parse('23:00', $layoutTz),
            null,
            DayOfWeek::WEDNESDAY
        );
        $layout->AppendPeriod(
            Time::Parse('23:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::WEDNESDAY
        );

        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('22:00', $layoutTz),
            null,
            DayOfWeek::THURSDAY
        );
        $layout->AppendPeriod(
            Time::Parse('22:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::THURSDAY
        );

        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::FRIDAY
        );
        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::SATURDAY
        );

        $sun = Date::Parse('2013-01-06 23:30', $targetTimezone);
        $mon = Date::Parse('2013-01-07 00:00', $targetTimezone);
        $tue = Date::Parse('2013-01-08 01:00', $targetTimezone);
        $wed = Date::Parse('2013-01-09 00:30', $targetTimezone);
        $thu = Date::Parse('2013-01-10 23:00', $targetTimezone);
        $fri = Date::Parse('2013-01-11 02:30', $targetTimezone);
        $sat = Date::Parse('2013-01-12 03:30', $targetTimezone);

        $sunPeriods = $layout->GetLayout($sun);
        $monPeriods = $layout->GetLayout($mon);
        $tuePeriods = $layout->GetLayout($tue);
        $wedPeriods = $layout->GetLayout($wed);
        $thuPeriods = $layout->GetLayout($thu);
        $friPeriods = $layout->GetLayout($fri);
        $satPeriods = $layout->GetLayout($sat);

        $this->assertEquals(2, count($sunPeriods));
        $this->assertEquals(Date::Parse('2013-01-05 23:00', $targetTimezone), $sunPeriods[0]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-06 23:00', $targetTimezone), $sunPeriods[0]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-06 23:00', $targetTimezone), $sunPeriods[1]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-07 00:00', $targetTimezone), $sunPeriods[1]->EndDate());

        $this->assertEquals(2, count($monPeriods));
        $this->assertEquals(Date::Parse('2013-01-07 00:00', $targetTimezone), $monPeriods[0]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-07 23:00', $targetTimezone), $monPeriods[0]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-07 23:00', $targetTimezone), $monPeriods[1]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-08 22:00', $targetTimezone), $monPeriods[1]->EndDate());

        $this->assertEquals(4, count($tuePeriods));
        $this->assertEquals(Date::Parse('2013-01-07 23:00', $targetTimezone), $tuePeriods[0]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-08 22:00', $targetTimezone), $tuePeriods[0]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-08 22:00', $targetTimezone), $tuePeriods[1]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-08 23:00', $targetTimezone), $tuePeriods[1]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-08 23:00', $targetTimezone), $tuePeriods[2]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-08 23:30', $targetTimezone), $tuePeriods[2]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-08 23:30', $targetTimezone), $tuePeriods[3]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-09 00:00', $targetTimezone), $tuePeriods[3]->EndDate());

        $this->assertEquals(5, count($wedPeriods));
        $this->assertEquals(Date::Parse('2013-01-09 00:00', $targetTimezone), $wedPeriods[0]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-09 01:30', $targetTimezone), $wedPeriods[0]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-09 01:30', $targetTimezone), $wedPeriods[1]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-09 21:30', $targetTimezone), $wedPeriods[1]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-09 21:30', $targetTimezone), $wedPeriods[2]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-09 22:00', $targetTimezone), $wedPeriods[2]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-09 22:00', $targetTimezone), $wedPeriods[3]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-09 23:00', $targetTimezone), $wedPeriods[3]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-09 23:00', $targetTimezone), $wedPeriods[4]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-10 21:00', $targetTimezone), $wedPeriods[4]->EndDate());

        $this->assertEquals(3, count($thuPeriods));
        $this->assertEquals(Date::Parse('2013-01-09 23:00', $targetTimezone), $thuPeriods[0]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-10 21:00', $targetTimezone), $thuPeriods[0]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-10 21:00', $targetTimezone), $thuPeriods[1]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-10 23:00', $targetTimezone), $thuPeriods[1]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-10 23:00', $targetTimezone), $thuPeriods[2]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-11 23:00', $targetTimezone), $thuPeriods[2]->EndDate());

        $this->assertEquals(2, count($friPeriods));
        $this->assertEquals(Date::Parse('2013-01-10 23:00', $targetTimezone), $friPeriods[0]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-11 23:00', $targetTimezone), $friPeriods[0]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-11 23:00', $targetTimezone), $friPeriods[1]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-12 23:00', $targetTimezone), $friPeriods[1]->EndDate());

        $this->assertEquals(2, count($satPeriods));
        $this->assertEquals(Date::Parse('2013-01-11 23:00', $targetTimezone), $satPeriods[0]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-12 23:00', $targetTimezone), $satPeriods[0]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-12 23:00', $targetTimezone), $satPeriods[1]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-13 23:00', $targetTimezone), $satPeriods[1]->EndDate());
    }

    public function testDailyLayoutWithEarlierLaterRequestedTimezone()
    {
        $layoutTz = 'America/Chicago';
        $targetTimezone = 'America/New_York';

        $scheduleLayoutFactory = new ScheduleLayoutFactory($targetTimezone);
        $layout = $scheduleLayoutFactory->CreateLayout();

        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::SUNDAY
        );

        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('01:00', $layoutTz),
            null,
            DayOfWeek::MONDAY
        );
        $layout->AppendPeriod(
            Time::Parse('01:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::MONDAY
        );

        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('23:00', $layoutTz),
            null,
            DayOfWeek::TUESDAY
        );
        $layout->AppendPeriod(
            Time::Parse('23:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::TUESDAY
        );

        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('00:30', $layoutTz),
            null,
            DayOfWeek::WEDNESDAY
        );
        $layout->AppendPeriod(
            Time::Parse('00:30', $layoutTz),
            Time::Parse('01:00', $layoutTz),
            null,
            DayOfWeek::WEDNESDAY
        );
        $layout->AppendPeriod(
            Time::Parse('01:00', $layoutTz),
            Time::Parse('02:30', $layoutTz),
            null,
            DayOfWeek::WEDNESDAY
        );
        $layout->AppendPeriod(
            Time::Parse('02:30', $layoutTz),
            Time::Parse('22:30', $layoutTz),
            null,
            DayOfWeek::WEDNESDAY
        );
        $layout->AppendPeriod(
            Time::Parse('22:30', $layoutTz),
            Time::Parse('23:00', $layoutTz),
            null,
            DayOfWeek::WEDNESDAY
        );
        $layout->AppendPeriod(
            Time::Parse('23:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::WEDNESDAY
        );

        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('22:00', $layoutTz),
            null,
            DayOfWeek::THURSDAY
        );
        $layout->AppendPeriod(
            Time::Parse('22:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::THURSDAY
        );

        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::FRIDAY
        );
        $layout->AppendPeriod(
            Time::Parse('00:00', $layoutTz),
            Time::Parse('00:00', $layoutTz),
            null,
            DayOfWeek::SATURDAY
        );

        $sun = Date::Parse('2013-01-06 23:30', $targetTimezone);
        $mon = Date::Parse('2013-01-07 00:00', $targetTimezone);
        $tue = Date::Parse('2013-01-08 01:00', $targetTimezone);
        $wed = Date::Parse('2013-01-09 00:30', $targetTimezone);
        $thu = Date::Parse('2013-01-10 23:00', $targetTimezone);
        $fri = Date::Parse('2013-01-11 02:30', $targetTimezone);
        $sat = Date::Parse('2013-01-12 03:30', $targetTimezone);

        $sunPeriods = $layout->GetLayout($sun);
        $monPeriods = $layout->GetLayout($mon);
        $tuePeriods = $layout->GetLayout($tue);
        $wedPeriods = $layout->GetLayout($wed);
        $thuPeriods = $layout->GetLayout($thu);
        $friPeriods = $layout->GetLayout($fri);
        $satPeriods = $layout->GetLayout($sat);

        $this->assertEquals(2, count($sunPeriods));
        $this->assertEquals(Date::Parse('2013-01-05 01:00', $targetTimezone), $sunPeriods[0]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-06 01:00', $targetTimezone), $sunPeriods[0]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-06 01:00', $targetTimezone), $sunPeriods[1]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-07 01:00', $targetTimezone), $sunPeriods[1]->EndDate());

        $this->assertEquals(3, count($monPeriods));
        $this->assertEquals(Date::Parse('2013-01-06 01:00', $targetTimezone), $monPeriods[0]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-07 01:00', $targetTimezone), $monPeriods[0]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-07 01:00', $targetTimezone), $monPeriods[1]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-07 02:00', $targetTimezone), $monPeriods[1]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-07 02:00', $targetTimezone), $monPeriods[2]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-08 01:00', $targetTimezone), $monPeriods[2]->EndDate());

        $this->assertEquals(2, count($tuePeriods));
        $this->assertEquals(Date::Parse('2013-01-07 02:00', $targetTimezone), $tuePeriods[0]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-08 01:00', $targetTimezone), $tuePeriods[0]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-08 01:00', $targetTimezone), $tuePeriods[1]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-09 00:00', $targetTimezone), $tuePeriods[1]->EndDate());

        $this->assertEquals(6, count($wedPeriods));
        $this->assertEquals(Date::Parse('2013-01-09 00:00', $targetTimezone), $wedPeriods[0]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-09 01:00', $targetTimezone), $wedPeriods[0]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-09 01:00', $targetTimezone), $wedPeriods[1]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-09 01:30', $targetTimezone), $wedPeriods[1]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-09 01:30', $targetTimezone), $wedPeriods[2]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-09 02:00', $targetTimezone), $wedPeriods[2]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-09 02:00', $targetTimezone), $wedPeriods[3]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-09 03:30', $targetTimezone), $wedPeriods[3]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-09 03:30', $targetTimezone), $wedPeriods[4]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-09 23:30', $targetTimezone), $wedPeriods[4]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-09 23:30', $targetTimezone), $wedPeriods[5]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-10 00:00', $targetTimezone), $wedPeriods[5]->EndDate());

        $this->assertEquals(3, count($thuPeriods));
        $this->assertEquals(Date::Parse('2013-01-10 00:00', $targetTimezone), $thuPeriods[0]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-10 01:00', $targetTimezone), $thuPeriods[0]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-10 01:00', $targetTimezone), $thuPeriods[1]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-10 23:00', $targetTimezone), $thuPeriods[1]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-10 23:00', $targetTimezone), $thuPeriods[2]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-11 01:00', $targetTimezone), $thuPeriods[2]->EndDate());

        $this->assertEquals(2, count($friPeriods));
        $this->assertEquals(Date::Parse('2013-01-10 23:00', $targetTimezone), $friPeriods[0]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-11 01:00', $targetTimezone), $friPeriods[0]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-11 01:00', $targetTimezone), $friPeriods[1]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-12 01:00', $targetTimezone), $friPeriods[1]->EndDate());

        $this->assertEquals(2, count($satPeriods));
        $this->assertEquals(Date::Parse('2013-01-11 01:00', $targetTimezone), $satPeriods[0]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-12 01:00', $targetTimezone), $satPeriods[0]->EndDate());
        $this->assertEquals(Date::Parse('2013-01-12 01:00', $targetTimezone), $satPeriods[1]->BeginDate());
        $this->assertEquals(Date::Parse('2013-01-13 01:00', $targetTimezone), $satPeriods[1]->EndDate());
    }

    public function testDailyLayoutWithSameTimezone()
    {
        $tz = 'America/New_York';

        $scheduleLayoutFactory = new ScheduleLayoutFactory($tz);
        $layout = $scheduleLayoutFactory->CreateLayout();

        $layout->AppendPeriod(Time::Parse('00:00', $tz), Time::Parse('00:00', $tz), null, DayOfWeek::SUNDAY);
        $layout->AppendPeriod(Time::Parse('00:00', $tz), Time::Parse('00:00', $tz), null, DayOfWeek::MONDAY);
        $layout->AppendPeriod(Time::Parse('00:00', $tz), Time::Parse('00:00', $tz), null, DayOfWeek::TUESDAY);
        $layout->AppendPeriod(Time::Parse('00:00', $tz), Time::Parse('00:00', $tz), null, DayOfWeek::WEDNESDAY);
        $layout->AppendPeriod(Time::Parse('00:00', $tz), Time::Parse('00:00', $tz), null, DayOfWeek::THURSDAY);
        $layout->AppendPeriod(Time::Parse('00:00', $tz), Time::Parse('00:00', $tz), null, DayOfWeek::FRIDAY);
        $layout->AppendPeriod(Time::Parse('00:00', $tz), Time::Parse('00:00', $tz), null, DayOfWeek::SATURDAY);

        $sun = Date::Parse('2013-01-06 23:30', $tz);
        $mon = Date::Parse('2013-01-07 00:00', $tz);
        $tue = Date::Parse('2013-01-08 01:00', $tz);
        $wed = Date::Parse('2013-01-09 00:30', $tz);
        $thu = Date::Parse('2013-01-10 23:00', $tz);
        $fri = Date::Parse('2013-01-11 02:30', $tz);
        $sat = Date::Parse('2013-01-12 03:30', $tz);

        $sunPeriods = $layout->GetLayout($sun);
        $monPeriods = $layout->GetLayout($mon);
        $tuePeriods = $layout->GetLayout($tue);
        $wedPeriods = $layout->GetLayout($wed);
        $thuPeriods = $layout->GetLayout($thu);
        $friPeriods = $layout->GetLayout($fri);
        $satPeriods = $layout->GetLayout($sat);

        $this->assertEquals(1, count($sunPeriods));
        $this->assertEquals(1, count($monPeriods));
        $this->assertEquals(1, count($tuePeriods));
        $this->assertEquals(1, count($wedPeriods));
        $this->assertEquals(1, count($thuPeriods));
        $this->assertEquals(1, count($friPeriods));
        $this->assertEquals(1, count($satPeriods));
    }

    public function testSkipsBlockedPeriodsForLayout()
    {
        $layoutTz = 'America/New_York';
        $targetTimezone = 'America/Chicago';
        $scheduleLayoutFactory = new ScheduleLayoutFactory($targetTimezone);
        $layout = $scheduleLayoutFactory->CreateLayout();

        $layout->AppendPeriod(Time::Parse('00:00', $layoutTz), Time::Parse('01:00', $layoutTz));
        $layout->AppendBlockedPeriod(Time::Parse('01:00', $layoutTz), Time::Parse('08:00', $layoutTz));
        $layout->AppendBlockedPeriod(Time::Parse('08:00', $layoutTz), Time::Parse('18:00', $layoutTz));
        $layout->AppendPeriod(Time::Parse('18:00', $layoutTz), Time::Parse('00:00', $layoutTz));

        $periods = $layout->GetLayout(Date::Parse('2013-03-01', $layoutTz), true);
        $this->assertEquals(2, count($periods));
    }

    public function testSkipsBlockedPeriodsForDailyLayout()
    {
        $tz = 'America/New_York';

        $scheduleLayoutFactory = new ScheduleLayoutFactory($tz);
        $layout = $scheduleLayoutFactory->CreateLayout();

        $layout->AppendPeriod(Time::Parse('00:00', $tz), Time::Parse('00:00', $tz), null, DayOfWeek::SUNDAY);
        $layout->AppendBlockedPeriod(Time::Parse('00:00', $tz), Time::Parse('00:00', $tz), null, DayOfWeek::MONDAY);
        $layout->AppendPeriod(Time::Parse('00:00', $tz), Time::Parse('00:00', $tz), null, DayOfWeek::TUESDAY);
        $layout->AppendPeriod(Time::Parse('00:00', $tz), Time::Parse('00:00', $tz), null, DayOfWeek::WEDNESDAY);
        $layout->AppendPeriod(Time::Parse('00:00', $tz), Time::Parse('00:00', $tz), null, DayOfWeek::THURSDAY);
        $layout->AppendPeriod(Time::Parse('00:00', $tz), Time::Parse('00:00', $tz), null, DayOfWeek::FRIDAY);
        $layout->AppendPeriod(Time::Parse('00:00', $tz), Time::Parse('00:00', $tz), null, DayOfWeek::SATURDAY);

        $sun = Date::Parse('2013-01-06 23:30', $tz);
        $mon = Date::Parse('2013-01-07 00:00', $tz);
        $tue = Date::Parse('2013-01-08 01:00', $tz);
        $wed = Date::Parse('2013-01-09 00:30', $tz);
        $thu = Date::Parse('2013-01-10 23:00', $tz);
        $fri = Date::Parse('2013-01-11 02:30', $tz);
        $sat = Date::Parse('2013-01-12 03:30', $tz);

        $sunPeriods = $layout->GetLayout($sun, true);
        $monPeriods = $layout->GetLayout($mon, true);
        $tuePeriods = $layout->GetLayout($tue, true);
        $wedPeriods = $layout->GetLayout($wed, true);
        $thuPeriods = $layout->GetLayout($thu, true);
        $friPeriods = $layout->GetLayout($fri, true);
        $satPeriods = $layout->GetLayout($sat, true);

        $this->assertEquals(1, count($sunPeriods));
        $this->assertEquals(0, count($monPeriods));
        $this->assertEquals(1, count($tuePeriods));
        $this->assertEquals(1, count($wedPeriods));
        $this->assertEquals(1, count($thuPeriods));
        $this->assertEquals(1, count($friPeriods));
        $this->assertEquals(1, count($satPeriods));
    }

    public function testCanGetSlotCounts()
    {
        $totalAvailableSlots = 16;
        $layout = $this->GetLayoutBetween(9, 17);

        $start = Date::Parse('2016-04-11 8:00', 'UTC');
        $end = Date::Parse('2016-04-11 18:00', 'UTC');

        $slotCount = $layout->GetSlotCount($start, $end, $start);

        $this->assertEquals($totalAvailableSlots, $slotCount->OffPeak);
        $this->assertEquals(0, $slotCount->Peak);
    }

    public function testCanGetSlotCountsWithPeakTimesForWholeDay()
    {
        $totalAvailableSlots = 16;
        $layout = $this->GetLayoutBetween(9, 17);
        $layout->ChangePeakTimes(new PeakTimes(true, null, null, false, [1, 3], true, 0, 0, 0, 0));

        $start = Date::Parse('2016-04-11 8:00', 'UTC');
        $end = Date::Parse('2016-04-11 18:00', 'UTC');

        $slotCount = $layout->GetSlotCount($start, $end, $start);

        $this->assertEquals(0, $slotCount->OffPeak);
        $this->assertEquals($totalAvailableSlots, $slotCount->Peak);
    }

    public function testCanGetPeakForSpecificHours()
    {
        $layout = $this->GetLayoutBetween(9, 17);
        $layout->ChangePeakTimes(new PeakTimes(false, '00:00', '10:00', false, [1, 3], true, 0, 0, 0, 0));

        $start = Date::Parse('2016-04-11 8:00', 'UTC');
        $end = Date::Parse('2016-04-11 18:00', 'UTC');

        $slotCount = $layout->GetSlotCount($start, $end, $start);

        $this->assertEquals(14, $slotCount->OffPeak);
        $this->assertEquals(2, $slotCount->Peak);
    }

    public function testCanGetPeakForSpecificDays()
    {
        $layout = $this->GetLayoutBetween(9, 17);
        $layout->ChangePeakTimes(new PeakTimes(false, '00:00', '10:00', false, [1, 3], true, 0, 0, 0, 0));

        $start = Date::Parse('2016-04-12 8:00', 'UTC');
        $end = Date::Parse('2016-04-12 18:00', 'UTC');

        $slotCount = $layout->GetSlotCount($start, $end, $start);

        $this->assertEquals(16, $slotCount->OffPeak);
        $this->assertEquals(0, $slotCount->Peak);
    }

    public function testCanGetPeakWhenLimitedDates()
    {
        $layout = $this->GetLayoutBetween(9, 17);
        $layout->ChangePeakTimes(new PeakTimes(true, '', '', true, [], false, 13, 1, 13, 4));

        $start = Date::Parse('2016-04-13 8:00', 'UTC');
        $end = Date::Parse('2016-04-13 18:00', 'UTC');

        $slotCountWithin = $layout->GetSlotCount($start, $end, $start);
        $slotCountOutsideDates = $layout->GetSlotCount($start->AddDays(1), $end->AddDays(1), $start->AddDays(1));

        $this->assertEquals(0, $slotCountWithin->OffPeak);
        $this->assertEquals(16, $slotCountWithin->Peak);

        $this->assertEquals(16, $slotCountOutsideDates->OffPeak);
        $this->assertEquals(0, $slotCountOutsideDates->Peak);
    }

    private function GetLayoutBetween($firstAvailable, $lastAvailable)
    {
        $layout = new ScheduleLayout('UTC');

        for ($hour = 0; $hour < $firstAvailable; $hour++) {
            $layout->AppendBlockedPeriod(new Time($hour, 0, 0, 'UTC'), new Time($hour, 30, 0, 'UTC'));
            $layout->AppendBlockedPeriod(new Time($hour, 30, 0, 'UTC'), new Time($hour + 1, 0, 0, 'UTC'));
        }

        for ($hour = $firstAvailable; $hour < $lastAvailable; $hour++) {
            $layout->AppendPeriod(new Time($hour, 0, 0, 'UTC'), new Time($hour, 30, 0, 'UTC'));
            $layout->AppendPeriod(new Time($hour, 30, 0, 'UTC'), new Time($hour + 1, 0, 0, 'UTC'));
        }

        for ($hour = $lastAvailable; $hour < 24; $hour++) {
            $layout->AppendBlockedPeriod(new Time($hour, 0, 0, 'UTC'), new Time($hour, 30, 0, 'UTC'));
            $layout->AppendBlockedPeriod(new Time($hour, 30, 0, 'UTC'), new Time($hour + 1, 0, 0, 'UTC'));
        }

        return $layout;
    }

    public function testCustomScheduleLayoutLoadsPeriodsFromDatabase()
    {
        $repository = new FakeScheduleRepository();
        $scheduleId = 1;
        $layout = new CustomScheduleLayout('UTC', $scheduleId, $repository);

        $date1 = Date::Now();
        $date2 = Date::Now()->AddDays(2);

        $repository->_AddCustomLayout($date1, [
            new SchedulePeriod($date1->SetTimeString('08:00'), $date1->SetTimeString('14:00')),
            new SchedulePeriod($date1->SetTimeString('14:00'), $date1->SetTimeString('18:00')),
        ]);

        $repository->_AddCustomLayout($date2, [
            new SchedulePeriod($date2->SetTimeString('08:00'), $date2->SetTimeString('14:00')),
            new SchedulePeriod($date2->SetTimeString('18:00'), $date2->SetTimeString('22:00')),
        ]);

        $expectedLayout1 = [
            new NonSchedulePeriod($date1->SetTimeString('00:00'), $date1->SetTimeString('08:00')),
            new SchedulePeriod($date1->SetTimeString('08:00'), $date1->SetTimeString('14:00')),
            new SchedulePeriod($date1->SetTimeString('14:00'), $date1->SetTimeString('18:00')),
            new NonSchedulePeriod($date1->SetTimeString('18:00'), $date1->AddDays(1)->SetTimeString('00:00')),
        ];

        $expectedLayout2 = [
            new NonSchedulePeriod($date2->SetTimeString('00:00'), $date2->SetTimeString('08:00')),
            new SchedulePeriod($date2->SetTimeString('08:00'), $date2->SetTimeString('14:00')),
            new NonSchedulePeriod($date2->SetTimeString('14:00'), $date2->SetTimeString('18:00')),
            new SchedulePeriod($date2->SetTimeString('18:00'), $date2->SetTimeString('22:00')),
            new NonSchedulePeriod($date2->SetTimeString('22:00'), $date2->AddDays(1)->SetTimeString('00:00')),
        ];

        $date1Layout = $layout->GetLayout($date1);
        $date2Layout = $layout->GetLayout($date2);

        $date1AgainLayout = $layout->GetLayout($date1);

        $this->assertEquals($expectedLayout1, $date1Layout);
        $this->assertEquals($expectedLayout2, $date2Layout);
        $this->assertSame($date1Layout, $date1AgainLayout);
    }
}
