<?php

require_once(ROOT_DIR . 'Domain/namespace.php');

class QuotaTest extends TestBase
{
    /**
     * @var string
     */
    public $tz;

    /**
     * @var Schedule
     */
    public $schedule;

    /**
     * @var IReservationViewRepository
     */
    public $reservationViewRepository;

    /**
     * @var FakeUser
     */
    public $user;

    public function setUp(): void
    {
        $this->reservationViewRepository = $this->createMock('IReservationViewRepository');

        $this->tz = 'America/Chicago';
        $this->schedule = new Schedule(1, null, null, null, null, $this->tz);

        $this->user = new FakeUser();

        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testWhenUserHasLessThanAllowedReservationsOnSameDayForSelectedResources()
    {
        $duration = new QuotaDurationDay();
        $limit = new QuotaLimitCount(2);

        $quota = new Quota(1, $duration, $limit);

        $startDate = Date::Parse('2011-04-03 1:30', 'UTC');
        $endDate = Date::Parse('2011-04-03 2:30', 'UTC');

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', $startDate, $endDate, '', 3, 98712);
        $res2 = new ReservationItemView('', $startDate, $endDate, '', 4, 98713);
        $res3 = new ReservationItemView('', $startDate->SetTimeString('3:30'), $endDate->SetTimeString('4:30'), '', $series->ResourceId(), 98713);
        // next day in America/Chicago
        $res4 = new ReservationItemView('', $startDate->SetTimeString('6:30'), $endDate->SetTimeString('20:30'), '', $series->ResourceId(), 98713);
        $reservations = [$res1, $res2, $res3, $res4];

        $startSearch = $startDate->ToTimezone($this->tz)->GetDate();
        $endSearch = $endDate->ToTimezone($this->tz)->AddDays(1)->GetDate();

        $this->ShouldSearchBy($startSearch, $endSearch, $series, $reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testWhenTotalLimitIsExceededOnSameDayForSameResource()
    {
        $duration = new QuotaDurationDay();
        $limit = new QuotaLimitCount(1);

        $quota = new Quota(1, $duration, $limit);

        $startDate = Date::Parse('2011-04-03 1:30', 'UTC');
        $endDate = Date::Parse('2011-04-03 2:30', 'UTC');

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', $startDate->SetTimeString('3:30'), $endDate->SetTimeString('8:00'), '', $series->ResourceId(), 98712);
        $reservations = [$res1];

        $startSearch = $startDate->ToTimezone($this->tz)->GetDate();
        $endSearch = $endDate->ToTimezone($this->tz)->AddDays(1)->GetDate();

        $this->ShouldSearchBy($startSearch, $endSearch, $series, $reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenHourlyLimitIsNotExceeded()
    {
        $duration = new QuotaDurationDay();
        $limit = new QuotaLimitHours(1.5);

        $quota = new Quota(1, $duration, $limit);

        $startDate = Date::Parse('2011-04-03 0:30', 'UTC');
        $endDate = Date::Parse('2011-04-03 1:30', 'UTC');

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', $startDate->SetTimeString('00:00'), $endDate->SetTimeString('00:30'), '', $series->ResourceId(), 98712);
        $reservations = [$res1];

        $this->SearchReturns($reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testWhenHourlyLimitIsExceededOnSameDayForSameResource()
    {
        $duration = new QuotaDurationDay();
        $limit = new QuotaLimitHours(1.5);

        $startDate = Date::Parse('2011-04-03 0:30', 'UTC');
        $endDate = Date::Parse('2011-04-03 1:30', 'UTC');

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $quota = new Quota(1, $duration, $limit, $series->ResourceId());

        $res1 = new ReservationItemView('', $startDate->SetTimeString('00:00'), $endDate->SetTimeString('00:31'), '', $series->ResourceId(), 98712);
        $reservations = [$res1];

        $this->SearchReturns($reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenHourlyLimitIsExceededOnSameDayForDifferentResource()
    {
        $duration = new QuotaDurationDay();
        $limit = new QuotaLimitHours(1.5);

        $startDate = Date::Parse('2011-04-03 0:30', 'UTC');
        $endDate = Date::Parse('2011-04-03 1:30', 'UTC');

        $series = $this->GetHourLongReservation($startDate, $endDate);
        $quota = new Quota(1, $duration, $limit, $series->ResourceId());

        $res1 = new ReservationItemView('ref1', $startDate->SetTimeString('00:00'), $endDate->SetTimeString('00:31'), '', 999, 98712);
        $res1->ScheduleId = $series->ScheduleId();
        $reservations = [$res1];

        $this->SearchReturns($reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testWhenTotalLimitIsExceededForWeek()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);

        $duration = new QuotaDurationWeek();
        $limit = new QuotaLimitCount(2);

        $quota = new Quota(1, $duration, $limit);

        // week 07/31/2011 - 08/05/2011
        $startDate = Date::Parse('2011-07-30 5:30', $tz);
        $endDate = Date::Parse('2011-08-03 5:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', Date::Parse('2011-08-04 1:30', $tz), Date::Parse(
            '2011-08-04 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $res2 = new ReservationItemView('', Date::Parse('2011-08-05 1:30', $tz), Date::Parse(
            '2011-08-05 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $reservations = [$res1, $res2];

        $startSearch = Date::Parse('2011-07-24 00:00', $tz);
        $endSearch = Date::Parse('2011-08-07 00:00', $tz);

        $this->ShouldSearchBy($startSearch, $endSearch, $series, $reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenTotalLimitIsExceededForWeekAndWeekStartOnMonday()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);
        $this->schedule->SetWeekdayStart(1);

        $duration = new QuotaDurationWeek();
        $limit = new QuotaLimitCount(2);

        $quota = new Quota(1, $duration, $limit);

        // week 02/20/2017 - 02/26/2017
        $startDate = Date::Parse('2017-02-23 5:30', $tz);
        $endDate = Date::Parse('2017-02-23 6:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', Date::Parse('2017-02-21 1:30', $tz), Date::Parse(
            '2017-02-21 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $res2 = new ReservationItemView('', Date::Parse('2017-02-26 1:30', $tz), Date::Parse(
            '2017-02-26 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $reservations = [$res1, $res2];

        $startSearch = Date::Parse('2017-02-20 00:00', $tz);
        $endSearch = Date::Parse('2017-02-27 00:00', $tz);

        $this->ShouldSearchBy($startSearch, $endSearch, $series, $reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenTotalLimitIsNotExceededForWeekAndWeekStartOnMonday()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);
        $this->schedule->SetWeekdayStart(1);

        $duration = new QuotaDurationWeek();
        $limit = new QuotaLimitCount(2);

        $quota = new Quota(1, $duration, $limit);

        // week 02/20/2017 - 02/26/2017
        $startDate = Date::Parse('2017-02-23 5:30', $tz);
        $endDate = Date::Parse('2017-02-23 6:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', Date::Parse('2017-02-19 1:30', $tz), Date::Parse(
            '2017-02-19 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $res2 = new ReservationItemView('', Date::Parse('2017-02-26 1:30', $tz), Date::Parse(
            '2017-02-26 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $reservations = [$res1, $res2];

        $startSearch = Date::Parse('2017-02-20 00:00', $tz);
        $endSearch = Date::Parse('2017-02-27 00:00', $tz);

        $this->ShouldSearchBy($startSearch, $endSearch, $series, $reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testWhenTotalLimitIsExceededForWeekAndWeekStartOnThursday()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);
        $this->schedule->SetWeekdayStart(4);

        $duration = new QuotaDurationWeek();
        $limit = new QuotaLimitCount(2);

        $quota = new Quota(1, $duration, $limit);

        // week 01/11/2018 - 01/18/2018
        $startDate = Date::Parse('2018-01-15 5:30', $tz);
        $endDate = Date::Parse('2018-01-15 6:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', Date::Parse('2018-01-11 1:30', $tz), Date::Parse('2018-01-11 2:30', $tz), '', $series->ResourceId(), 98712);
        $res2 = new ReservationItemView('', Date::Parse('2018-01-12 1:30', $tz), Date::Parse('2018-01-12 2:30', $tz), '', $series->ResourceId(), 98712);
        $reservations = [$res1, $res2];

        $startSearch = Date::Parse('2018-01-11 00:00', $tz);
        $endSearch = Date::Parse('2018-01-18 00:00', $tz);

        $this->ShouldSearchBy($startSearch, $endSearch, $series, $reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenTotalLimitIsExceededForWeekAndWeekStartOnToday()
    {
        $tz = 'UTC';
        Date::_SetNow(Date::Parse('2017-02-21', $tz));    // tuesday
        $this->schedule->SetTimezone($tz);
        $this->schedule->SetWeekdayStart(Schedule::Today);

        $duration = new QuotaDurationWeek();
        $limit = new QuotaLimitCount(2);

        $quota = new Quota(1, $duration, $limit);

        // week 02/21/2017 - 02/27/2017
        $startDate = Date::Parse('2017-02-23 5:30', $tz);
        $endDate = Date::Parse('2017-02-23 6:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', Date::Parse('2017-02-27 1:30', $tz), Date::Parse(
            '2017-02-27 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $res2 = new ReservationItemView('', Date::Parse('2017-02-26 1:30', $tz), Date::Parse(
            '2017-02-26 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $reservations = [$res1, $res2];

        $startSearch = Date::Parse('2017-02-21 00:00', $tz);
        $endSearch = Date::Parse('2017-02-28 00:00', $tz);

        $this->ShouldSearchBy($startSearch, $endSearch, $series, $reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenTotalLimitIsNotExceededForWeekAndWeekStartOnToday()
    {
        $tz = 'UTC';
        Date::_SetNow(Date::Parse('2017-02-21', $tz));    // tuesday
        $this->schedule->SetTimezone($tz);
        $this->schedule->SetWeekdayStart(Schedule::Today);

        $duration = new QuotaDurationWeek();
        $limit = new QuotaLimitCount(2);

        $quota = new Quota(1, $duration, $limit);

        // week 02/21/2017 - 02/27/2017
        $startDate = Date::Parse('2017-02-23 5:30', $tz);
        $endDate = Date::Parse('2017-02-23 6:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', Date::Parse('2017-02-28 1:30', $tz), Date::Parse(
            '2017-02-28 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $res2 = new ReservationItemView('', Date::Parse('2017-02-26 1:30', $tz), Date::Parse(
            '2017-02-26 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $reservations = [$res1, $res2];

        $startSearch = Date::Parse('2017-02-21 00:00', $tz);
        $endSearch = Date::Parse('2017-02-28 00:00', $tz);

        $this->ShouldSearchBy($startSearch, $endSearch, $series, $reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testWhenTotalLimitIsNotExceededForWeek()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);

        $duration = new QuotaDurationWeek();
        $limit = new QuotaLimitCount(3);

        $quota = new Quota(1, $duration, $limit);

        // week 07/31/2011 - 08/05/2011
        $startDate = Date::Parse('2011-07-30 5:30', $tz);
        $endDate = Date::Parse('2011-08-03 5:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', Date::Parse('2011-08-04 1:30', $tz), Date::Parse(
            '2011-08-04 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $res2 = new ReservationItemView('', Date::Parse('2011-08-05 1:30', $tz), Date::Parse(
            '2011-08-05 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $reservations = [$res1, $res2];

        $startSearch = Date::Parse('2011-07-24 00:00', $tz);
        $endSearch = Date::Parse('2011-08-07 00:00', $tz);

        $this->ShouldSearchBy($startSearch, $endSearch, $series, $reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testWhenTotalLimitIsExceededForAReservationLastingMultipleWeeks()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);

        $duration = new QuotaDurationWeek();
        $limit = new QuotaLimitCount(1);

        $quota = new Quota(1, $duration, $limit);

        // week 07/31/2011 - 08/05/2011
        $startDate = Date::Parse('2011-07-30 5:30', $tz);
        $endDate = Date::Parse('2011-08-07 5:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', Date::Parse('2011-08-08 1:30', $tz), Date::Parse(
            '2011-08-08 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $reservations = [$res1];

        $this->SearchReturns($reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenHourLimitIsExceededForMultipleReservationsInOneWeek()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);

        $duration = new QuotaDurationWeek();
        $limit = new QuotaLimitHours(2);

        $quota = new Quota(1, $duration, $limit);

        // week 07/31/2011 - 08/05/2011
        $startDate = Date::Parse('2011-07-31 5:30', $tz);
        $endDate = Date::Parse('2011-07-31 6:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', Date::Parse('2011-08-05 1:30', $tz), Date::Parse(
            '2011-08-05 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $res2 = new ReservationItemView('', Date::Parse('2011-08-06 11:00', $tz), Date::Parse(
            '2011-08-07 0:00',
            $tz
        ), '', $series->ResourceId(), 98712);
        $reservations = [$res1, $res2];

        $this->SearchReturns($reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenHourLimitIsExceededForReservationLastingMultipleWeeks()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);

        $duration = new QuotaDurationWeek();
        $limit = new QuotaLimitHours(39);

        $quota = new Quota(1, $duration, $limit);

        // week 07/31/2011 - 08/05/2011
        $startDate = Date::Parse('2011-07-30 5:30', $tz);
        $endDate = Date::Parse('2011-08-07 5:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $reservations = [];

        $this->SearchReturns($reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenTotalLimitIsNotExceededForMonth()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);

        $duration = new QuotaDurationMonth();
        $limit = new QuotaLimitCount(3);

        $quota = new Quota(1, $duration, $limit);

        // week 07/31/2011 - 08/05/2011
        $startDate = Date::Parse('2011-07-30 5:30', $tz);
        $endDate = Date::Parse('2011-08-03 5:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', Date::Parse('2011-08-04 1:30', $tz), Date::Parse(
            '2011-08-05 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $res2 = new ReservationItemView('', Date::Parse('2011-08-25 1:30', $tz), Date::Parse(
            '2011-08-28 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $reservations = [$res1, $res2];

        $startSearch = Date::Parse('2011-07-01 00:00', $tz);
        $endSearch = Date::Parse('2011-09-01 00:00', $tz);

        $this->ShouldSearchBy($startSearch, $endSearch, $series, $reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testWhenTotalLimitIsExceededForMonth()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);

        $duration = new QuotaDurationMonth();
        $limit = new QuotaLimitCount(2);

        $quota = new Quota(1, $duration, $limit);

        $startDate = Date::Parse('2011-08-01 5:30', $tz);
        $endDate = Date::Parse('2011-08-03 5:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', Date::Parse('2011-08-04 1:30', $tz), Date::Parse(
            '2011-08-05 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $res2 = new ReservationItemView('', Date::Parse('2011-08-25 1:30', $tz), Date::Parse(
            '2011-08-28 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $reservations = [$res1, $res2];

        $this->SearchReturns($reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenTotalLimitIsExceededForReservationThatSpansMoreThanOneMonth()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);

        $duration = new QuotaDurationMonth();
        $limit = new QuotaLimitCount(1);

        $quota = new Quota(1, $duration, $limit);

        $startDate = Date::Parse('2011-07-30 5:30', $tz);
        $endDate = Date::Parse('2011-08-03 5:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', Date::Parse('2011-07-01 1:30', $tz), Date::Parse(
            '2011-07-02 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $reservations = [$res1];

        $this->SearchReturns($reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenHourLimitIsExceededForReservationWithinAMonth()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);

        $duration = new QuotaDurationMonth();
        $limit = new QuotaLimitHours(4);

        $quota = new Quota(1, $duration, $limit);

        $startDate = Date::Parse('2011-08-01 5:30', $tz);
        $endDate = Date::Parse('2011-08-01 6:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', Date::Parse('2011-08-02 1:30', $tz), Date::Parse(
            '2011-08-02 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $res2 = new ReservationItemView('', Date::Parse('2011-08-31 21:30', $tz), Date::Parse(
            '2011-09-01 0:00',
            $tz
        ), '', $series->ResourceId(), 98712);
        $reservations = [$res1, $res2];

        $this->SearchReturns($reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenHourLimitIsExceededForReservationLastingLongerThanOneMonth()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);

        $duration = new QuotaDurationMonth();
        $limit = new QuotaLimitHours(4);

        $quota = new Quota(1, $duration, $limit);

        $startDate = Date::Parse('2011-07-31 5:30', $tz);
        $endDate = Date::Parse('2011-08-01 6:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $this->SearchReturns([]);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenMonthlyHourLimitIsNotExceededForReservation()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);

        $duration = new QuotaDurationMonth();
        $limit = new QuotaLimitHours(4);

        $quota = new Quota(1, $duration, $limit);

        $startDate = Date::Parse('2011-07-31 21:30', $tz);
        $endDate = Date::Parse('2011-08-01 2:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', Date::Parse('2011-07-02 2:00', $tz), Date::Parse(
            '2011-07-02 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $res2 = new ReservationItemView('', Date::Parse('2011-08-31 23:30', $tz), Date::Parse(
            '2011-09-01 3:00',
            $tz
        ), '', $series->ResourceId(), 98712);
        $this->SearchReturns([$res1, $res2]);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testWhenAggregatedResourceTimesForScheduleAreExceeded()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);

        $duration = new QuotaDurationMonth();
        $limit = new QuotaLimitHours(4);

        $scheduleId = 1;

        $quota = new Quota(1, $duration, $limit, null, null, $scheduleId);

        $startDate = Date::Parse('2011-08-05 1:30', $tz);
        $endDate = Date::Parse('2011-08-05 2:30', $tz);

        // 2 one hour reservations
        $series = $this->GetHourLongReservation($startDate, $endDate, 1, 2, $scheduleId);

        $res1 = new ReservationItemView('', Date::Parse('2011-08-01 00:00', $tz), Date::Parse(
            '2011-08-01 2:00',
            $tz
        ), '', 999, 98712, null, null, null, $scheduleId);
        $res2 = new ReservationItemView('', Date::Parse('2011-08-01 02:00', $tz), Date::Parse(
            '2011-08-01 3:00',
            $tz
        ), '', 888, 98712, null, null, null, $scheduleId);
        $this->SearchReturns([$res1, $res2]);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenAggregatedResourceTimesForScheduleAreNotExceeded()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);

        $duration = new QuotaDurationMonth();
        $limit = new QuotaLimitHours(4);

        $scheduleId = 1;

        $quota = new Quota(1, $duration, $limit, null, null, $scheduleId);

        $startDate = Date::Parse('2011-08-05 1:30', $tz);
        $endDate = Date::Parse('2011-08-05 2:30', $tz);

        // 2 one hour reservations
        $series = $this->GetHourLongReservation($startDate, $endDate, 1, 2, $scheduleId);

        $res1 = new ReservationItemView('', Date::Parse('2011-08-01 00:00', $tz), Date::Parse(
            '2011-08-01 1:00',
            $tz
        ), '', 999, 98712, null, null, null, $scheduleId);
        $res2 = new ReservationItemView('', Date::Parse('2011-08-01 02:00', $tz), Date::Parse(
            '2011-08-01 3:00',
            $tz
        ), '', 888, 98712, null, null, null, $scheduleId);
        $this->SearchReturns([$res1, $res2]);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testDoesNotCheckWhenNoResourcesApply()
    {
        $resourceId = 100;
        $startDate = Date::Parse('2011-07-31 21:30', $this->tz);
        $endDate = Date::Parse('2011-08-01 2:30', $this->tz);

        $quota = new Quota(1, new QuotaDurationDay(), new QuotaLimitCount(0), $resourceId, null, null);

        $series = $this->GetHourLongReservation($startDate, $endDate, 101, 102);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testChecksWhenAtLeastOneResourceApplies()
    {
        $resourceId = 100;
        $startDate = Date::Parse('2011-07-31 21:30', $this->tz);
        $endDate = Date::Parse('2011-08-01 2:30', $this->tz);

        $quota = new Quota(1, new QuotaDurationDay(), new QuotaLimitCount(0), $resourceId, null, null);

        $series = $this->GetHourLongReservation($startDate, $endDate, 101, $resourceId);
        $this->SearchReturns([]);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testDoesNotCheckWhenNoGroupsApply()
    {
        $g1 = new UserGroup(1, null);
        $g2 = new UserGroup(2, null);
        $this->user->SetGroups([$g1, $g2]);

        $groupId = 4;
        $startDate = Date::Parse('2011-07-31 21:30', $this->tz);
        $endDate = Date::Parse('2011-08-01 2:30', $this->tz);

        $quota = new Quota(1, new QuotaDurationDay(), new QuotaLimitCount(0), null, $groupId, null);

        $series = $this->GetHourLongReservation($startDate, $endDate, 101, 102);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testChecksWhenAtLeastOneGroupApplies()
    {
        $g1 = new UserGroup(1, null);
        $g2 = new UserGroup(2, null);
        $g4 = new UserGroup(4, null);
        $this->user->SetGroups([$g1, $g2, $g4]);

        $groupId = 4;
        $startDate = Date::Parse('2011-07-31 21:30', $this->tz);
        $endDate = Date::Parse('2011-08-01 2:30', $this->tz);

        $quota = new Quota(1, new QuotaDurationDay(), new QuotaLimitCount(0), null, $groupId, null);

        $series = $this->GetHourLongReservation($startDate, $endDate, 101, 102);
        $this->SearchReturns([]);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testDoesNotCheckWhenNoSchedulesApply()
    {
        $series = $this->GetHourLongReservation(Date::Now(), Date::Now(), 101, 102, 999);

        $quota = new Quota(1, new QuotaDurationDay(), new QuotaLimitCount(0), 101, 102, 888);
        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testWhenHourlyLimitIsExceededInNextYear()
    {
        $duration = new QuotaDurationYear();
        $limit = new QuotaLimitHours(1.5);

        $startDate = Date::Parse('2011-12-31 23:30', $this->schedule->GetTimezone());
        $endDate = Date::Parse('2012-01-01 00:30', $this->schedule->GetTimezone());

        $series = $this->GetHourLongReservation($startDate, $endDate);
        $quota = new Quota(1, $duration, $limit, $series->ResourceId());

        $res1 = new ReservationItemView('ref1', $startDate->SetTimeString('00:00'), $startDate->SetTimeString('00:31'), '', $series->ResourceId(), 98712);
        $res1->ScheduleId = $series->ScheduleId();
        $reservations = [$res1];

        $this->SearchReturns($reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testWhenHourlyLimitIsExceededInYear()
    {
        $duration = new QuotaDurationYear();
        $limit = new QuotaLimitHours(1.5);

        $startDate = Date::Parse('2011-04-03 0:30', $this->schedule->GetTimezone());
        $endDate = Date::Parse('2011-04-03 1:30', $this->schedule->GetTimezone());

        $series = $this->GetHourLongReservation($startDate, $endDate);
        $quota = new Quota(1, $duration, $limit, $series->ResourceId());

        $res1 = new ReservationItemView('ref1', $startDate->SetTimeString('00:00'), $endDate->SetTimeString('00:31'), '', $series->ResourceId(), 98712);
        $res1->ScheduleId = $series->ScheduleId();
        $reservations = [$res1];

        $this->SearchReturns($reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertTrue($exceeds);
    }

    public function testWhenHourlyLimitIsExceededOnSameDayForSameResourceButOnlyEnforcedCertainHours()
    {
        $duration = new QuotaDurationDay();
        $limit = new QuotaLimitHours(1.5);

        $startDate = Date::Parse('2011-04-03 0:30', 'UTC');
        $endDate = Date::Parse('2011-04-03 1:30', 'UTC');

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $quota = new Quota(1, $duration, $limit, $series->ResourceId(), null, null, "00:00", "00:30");

        $res1 = new ReservationItemView('', $startDate->SetTimeString('00:00'), $endDate->SetTimeString('00:31'), '', $series->ResourceId(), 98712);
        $reservations = [$res1];

        $this->SearchReturns($reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testWhenTotalLimitIsExceededForButOnlyEnforcedOnCertainDaysWeek()
    {
        $tz = 'UTC';
        $this->schedule->SetTimezone($tz);

        $duration = new QuotaDurationWeek();
        $limit = new QuotaLimitCount(2);

        $quota = new Quota(1, $duration, $limit, null, null, null, null, null, [1, 2, 4]);

        // week 07/31/2011 - 08/05/2011
        $startDate = Date::Parse('2011-07-30 5:30', $tz);
        $endDate = Date::Parse('2011-08-03 5:30', $tz);

        $series = $this->GetHourLongReservation($startDate, $endDate);

        $res1 = new ReservationItemView('', Date::Parse('2011-08-04 1:30', $tz), Date::Parse(
            '2011-08-04 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $res2 = new ReservationItemView('', Date::Parse('2011-08-05 1:30', $tz), Date::Parse(
            '2011-08-05 2:30',
            $tz
        ), '', $series->ResourceId(), 98712);
        $reservations = [$res1, $res2];

        $startSearch = Date::Parse('2011-07-24 00:00', $tz);
        $endSearch = Date::Parse('2011-08-07 00:00', $tz);

        $this->ShouldSearchBy($startSearch, $endSearch, $series, $reservations);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testTwoFullDayReservationWith24HoursPerDayLimitBackToBack()
    {
        $quota = new Quota(1, new QuotaDurationDay(), new QuotaLimitHours(24), null, null, null, null, null, [], new QuotaScopeExcluded());
        $reservationDate = DateRange::Create('2019-09-04 00:00', '2019-09-06 00:00', 'America/Chicago');
        $series = ReservationSeries::Create(1, new FakeBookableResource(1), '', '', $reservationDate, new RepeatNone(), $this->fakeUser);

        $res1 = new ReservationItemView('', Date::Parse('2019-09-02 05:00', 'UTC'), Date::Parse('2019-09-04 05:00', 'UTC'), '', $series->ResourceId(), 98712);

        $this->reservationViewRepository->expects($this->any())
                                        ->method('GetReservations')
                                        ->willReturn([$res1]);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testTwoDayReservationWith24HoursPerDayLimit()
    {
        $quota = new Quota(1, new QuotaDurationDay(), new QuotaLimitHours(24), null, null, null, null, null, [], new QuotaScopeExcluded());
        $reservationDate = DateRange::Create('2019-08-27 12:00', '2019-08-29 12:00', 'America/Chicago');
        $series = ReservationSeries::Create(1, new FakeBookableResource(1), '', '', $reservationDate, new RepeatNone(), $this->fakeUser);

        $this->reservationViewRepository->expects($this->any())
                                        ->method('GetReservations')
                                        ->willReturn([]);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    public function testBugOvernightBookingCausingNewReservationsToExceedQuota()
    {
        $quota = new Quota(1, new QuotaDurationDay(), new QuotaLimitHours(1), null, null, null, "08:00", "20:00", [], new QuotaScopeIncluded());
        $overnightReservation = new ReservationItemView('', Date::Parse('2019-08-27 21:00', 'America/Chicago'), Date::Parse('2019-08-28 04:00', 'America/Chicago'), null, 1);
        $reservationDate = DateRange::Create('2019-08-28 12:00', '2019-08-28 13:00', 'America/Chicago');
        $series = ReservationSeries::Create(1, new FakeBookableResource(1), '', '', $reservationDate, new RepeatNone(), $this->fakeUser);

        $this->reservationViewRepository->expects($this->any())
                                        ->method('GetReservations')
                                        ->willReturn([$overnightReservation]);

        $exceeds = $quota->ExceedsQuota($series, $this->user, $this->schedule, $this->reservationViewRepository);

        $this->assertFalse($exceeds);
    }

    private function GetHourLongReservation(
        $startDate,
        $endDate,
        $resourceId1 = null,
        $resourceId2 = null,
        $scheduleId = null
    ) {
        $userId = 12;
        $resource1 = empty($resourceId1) ? 13 : $resourceId1;
        $resource2 = empty($resourceId2) ? 14 : $resourceId2;
        $schedule = empty($scheduleId) ? 1 : $scheduleId;

        $hourLongReservation = new DateRange($startDate, $endDate, $this->tz);

        $resource = new FakeBookableResource($resource1);
        $resource->SetScheduleId($schedule);
        $series = ReservationSeries::Create(
            $userId,
            $resource,
            null,
            null,
            $hourLongReservation,
            new RepeatNone(),
            new FakeUserSession()
        );
        $series->AddResource(new FakeBookableResource($resource2));

        return $series;
    }

    private function ShouldSearchBy($startSearch, $endSearch, $series, $reservations)
    {
        $this->reservationViewRepository->expects($this->once())
                                        ->method('GetReservations')
                                        ->with(
                                            $this->equalTo($startSearch),
                                            $this->equalTo($endSearch),
                                            $this->equalTo($series->UserId()),
                                            $this->equalTo(ReservationUserLevel::OWNER)
                                        )
                                        ->willReturn($reservations);
    }

    private function SearchReturns($reservations)
    {
        $this->reservationViewRepository->expects($this->once())
                                        ->method('GetReservations')
                                        ->with($this->anything(), $this->anything(), $this->anything(), $this->anything())
                                        ->willReturn($reservations);
    }
}
