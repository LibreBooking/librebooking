<?php

require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class CalendarMonthTest extends TestBase
{
    public function testAddsReservationsToCalendar()
    {
        $startsBeforeMonth = new ReservationItemView();
        $startsBeforeMonth->StartDate = Date::Parse('2011-11-25', 'UTC');
        $startsBeforeMonth->EndDate = Date::Parse('2011-12-01 12:25', 'UTC');
        $startsBeforeMonth->ResourceName = 'Something Fun';
        $startsBeforeMonth->UserLevelId = ReservationUserLevel::OWNER;

        $endsAfterMonth = new ReservationItemView();
        ;
        $endsAfterMonth->StartDate = Date::Parse('2011-12-25', 'UTC');
        $endsAfterMonth->EndDate = Date::Parse('2012-01-25', 'UTC');
        $endsAfterMonth->ResourceName = 'Something Fun';
        $endsAfterMonth->UserLevelId = ReservationUserLevel::PARTICIPANT;

        $firstDayOnly = new ReservationItemView();
        $firstDayOnly->StartDate = Date::Parse('2011-12-01 14:00', 'UTC');
        $firstDayOnly->EndDate = Date::Parse('2011-12-01 16:25', 'UTC');
        $firstDayOnly->ResourceName = 'Something Fun';
        $firstDayOnly->UserLevelId = ReservationUserLevel::OWNER;

        $secondAndThirdDay = new ReservationItemView();
        $secondAndThirdDay->StartDate = Date::Parse('2011-12-02 14:00', 'UTC');
        $secondAndThirdDay->EndDate = Date::Parse('2011-12-03 16:25', 'UTC');
        $secondAndThirdDay->ResourceName = 'Something Fun';
        $secondAndThirdDay->UserLevelId = ReservationUserLevel::INVITEE;

        $notInMonth = new ReservationItemView();
        $notInMonth->StartDate = Date::Parse('2011-11-02 14:00', 'UTC');
        $notInMonth->EndDate = Date::Parse('2011-11-03 16:25', 'UTC');
        $notInMonth->ResourceName = 'Something Fun';
        $notInMonth->UserLevelId = ReservationUserLevel::OWNER;

        $reservations = [$startsBeforeMonth, $endsAfterMonth, $firstDayOnly, $secondAndThirdDay, $notInMonth];

        $timezone = 'America/Chicago';
        $calendarReservations = CalendarReservation::FromViewList($reservations, $timezone, $this->fakeUser);

        $month = new CalendarMonth(12, 2011, $timezone);

        $month->AddReservations($calendarReservations);

        $expectedFirstDay = Date::Parse('2011-12-01', $timezone);
        $expectedLastDay = Date::Parse('2012-01-01', $timezone);

        $this->assertEquals($expectedFirstDay, $month->FirstDay());
        $this->assertEquals($expectedLastDay, $month->LastDay());

        $nullDay = CalendarDay::Null();
        $day1 = new CalendarDay($expectedFirstDay);
        $day1->AddReservation(CalendarReservation::FromView($startsBeforeMonth, $timezone, $this->fakeUser));
        $day1->AddReservation(CalendarReservation::FromView($firstDayOnly, $timezone, $this->fakeUser));
        $day2 = new CalendarDay($expectedFirstDay->AddDays(1));
        $day2->AddReservation(CalendarReservation::FromView($secondAndThirdDay, $timezone, $this->fakeUser));
        $day3 = new CalendarDay($expectedFirstDay->AddDays(2));
        $day3->AddReservation(CalendarReservation::FromView($secondAndThirdDay, $timezone, $this->fakeUser));

        $weeks = $month->Weeks();
        /** @var CalendarWeek $actualWeek1 */
        $actualWeek1 = $weeks[0];
        /** @var array|CalendarDay[] $actualDays */
        $actualDays = $actualWeek1->Days();

        $this->assertEquals(5, count($weeks));
        $this->assertEquals(7, count($actualDays));
        $this->assertEquals($nullDay, $actualDays[0]);
        $this->assertEquals($nullDay, $actualDays[1]);
        $this->assertEquals($nullDay, $actualDays[2]);
        $this->assertEquals($nullDay, $actualDays[3]);

        $this->assertEquals(2, count($actualDays[4]->Reservations()));
        $this->assertEquals($day1, $actualDays[4]);
        $this->assertEquals($day2, $actualDays[5]);
        $this->assertEquals($day3, $actualDays[6]);

        $lastWeekDays = $weeks[4]->Days();
        $lastDayReservations = $lastWeekDays[6]->Reservations();
        $this->assertEquals(CalendarReservation::FromView($endsAfterMonth, $timezone, $this->fakeUser), $lastDayReservations[0]);

        $next = Date::Parse('2012-01-01', $timezone);
        $prev = Date::Parse('2011-11-01', $timezone);

        $this->assertEquals($next, $month->GetNextDate());
        $this->assertEquals($prev, $month->GetPreviousDate());
    }

    public function testGroupsReservationsByResource()
    {
        $start = Date::Now();
        $end = Date::Now()->AddDays(1);

        $r1 = new ReservationItemView();
        $r1->SeriesId = 1;
        $r1->ResourceId = 1;
        $r1->StartDate = $start;
        $r1->EndDate = $end;
        $r1->ReferenceNumber = 1;

        $r2 = new ReservationItemView();
        $r2->SeriesId = 1;
        $r2->ResourceId = 2;
        $r2->StartDate = $start;
        $r2->EndDate = $end;
        $r2->ReferenceNumber = 1;

        $r3 = new ReservationItemView();
        $r3->SeriesId = 2;
        $r3->ResourceId = 1;
        $r3->StartDate = $start;
        $r3->EndDate = $end;
        $r3->ReferenceNumber = 2;

        $reservations = [$r1, $r2, $r3];

        $timezone = 'America/Chicago';
        $calendarReservations = CalendarReservation::FromViewList($reservations, $timezone, $this->fakeUser, true);

        $this->assertEquals(2, count($calendarReservations));
    }
}
