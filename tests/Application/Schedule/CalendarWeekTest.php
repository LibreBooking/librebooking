<?php

require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class CalendarWeekTest extends TestBase
{
    public function testAddsReservationsToCalendar()
    {
        $startsBeforeWeek = new ReservationItemView();
        $startsBeforeWeek->StartDate = Date::Parse('2011-07-30 12:00', 'UTC');
        $startsBeforeWeek->EndDate = Date::Parse('2011-07-31 12:00', 'UTC');

        $endsAfterWeek = new ReservationItemView();
        ;
        $endsAfterWeek->StartDate = Date::Parse('2011-08-06 12:00', 'UTC');
        $endsAfterWeek->EndDate = Date::Parse('2012-08-08 12:00', 'UTC');

        $firstDayOnly = new ReservationItemView();
        $firstDayOnly->StartDate = Date::Parse('2011-07-31 14:00', 'UTC');
        $firstDayOnly->EndDate = Date::Parse('2011-07-31 16:25', 'UTC');

        $secondAndThirdDay = new ReservationItemView();
        $secondAndThirdDay->StartDate = Date::Parse('2011-08-01 14:00', 'UTC');
        $secondAndThirdDay->EndDate = Date::Parse('2011-08-02 16:25', 'UTC');

        $notInWeek = new ReservationItemView();
        $notInWeek->StartDate = Date::Parse('2011-11-02 14:00', 'UTC');
        $notInWeek->EndDate = Date::Parse('2011-11-03 16:25', 'UTC');

        $reservations = [$startsBeforeWeek, $endsAfterWeek, $firstDayOnly, $secondAndThirdDay, $notInWeek];

        $timezone = 'America/Chicago';

        $week = CalendarWeek::FromDate(2011, 8, 3, $timezone);
        $calendarReservations = CalendarReservation::FromViewList($reservations, $timezone, $this->fakeUser);

        $week->AddReservations($calendarReservations);

        $expectedFirstDay = Date::Parse('2011-07-31', $timezone);
        $expectedLastDay = Date::Parse('2011-08-06', $timezone);

        $this->assertEquals($expectedFirstDay, $week->FirstDay());
        $this->assertEquals($expectedLastDay, $week->LastDay());

        $day1 = new CalendarDay($expectedFirstDay);
        $day1->AddReservation(CalendarReservation::FromView($startsBeforeWeek, $timezone, $this->fakeUser));
        $day1->AddReservation(CalendarReservation::FromView($firstDayOnly, $timezone, $this->fakeUser));

        $day2 = new CalendarDay($expectedFirstDay->AddDays(1));
        $day2->AddReservation(CalendarReservation::FromView($secondAndThirdDay, $timezone, $this->fakeUser));

        $day3 = new CalendarDay($expectedFirstDay->AddDays(2));
        $day3->AddReservation(CalendarReservation::FromView($secondAndThirdDay, $timezone, $this->fakeUser));

        $day4 = new CalendarDay($expectedFirstDay->AddDays(3));
        $day5 = new CalendarDay($expectedFirstDay->AddDays(4));
        $day6 = new CalendarDay($expectedFirstDay->AddDays(5));
        $day7 = new CalendarDay($expectedFirstDay->AddDays(6));
        $day7->AddReservation(CalendarReservation::FromView($endsAfterWeek, $timezone, $this->fakeUser));

        /** @var array|CalendarDay[] $actualDays */
        $actualDays = $week->Days();

        $this->assertEquals(7, count($actualDays));
        $this->assertEquals($day1->Date(), $actualDays[0]->Date());
        $this->assertEquals($day2, $actualDays[1]);
        $this->assertEquals($day3, $actualDays[2]);
        $this->assertEquals($day4, $actualDays[3]);
        $this->assertEquals($day5, $actualDays[4]);
        $this->assertEquals($day6, $actualDays[5]);
        $this->assertEquals($day7, $actualDays[6]);

        $next = Date::Parse('2011-08-07', $timezone);
        $prev = Date::Parse('2011-07-24', $timezone);

        $this->assertEquals($next, $week->GetNextDate());
        $this->assertEquals($prev, $week->GetPreviousDate());
    }

    public function testWhenFirstDayOfTheWeekIsNotSunday()
    {
        $timezone = 'America/Chicago';

        $next = Date::Parse('2014-07-14', $timezone);
        $prev = Date::Parse('2014-06-30', $timezone);

        $expectedFirstDay = Date::Parse('2014-07-07', $timezone);
        $expectedLastDay = Date::Parse('2014-07-13', $timezone);

        $week = CalendarWeek::FromDate(2014, 7, 12, $timezone, 1);

        $this->assertEquals($expectedFirstDay, $week->FirstDay(), $week->FirstDay()->__toString());
        $this->assertEquals($expectedLastDay, $week->LastDay(), $week->LastDay()->__toString());

        $this->assertEquals($next, $week->GetNextDate(), $week->GetNextDate()->__toString());
        $this->assertEquals($prev, $week->GetPreviousDate(), $week->GetPreviousDate()->__toString());
    }
}
