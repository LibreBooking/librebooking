<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/namespace.php');

class ScheduleAvailabilityRuleTest extends TestBase
{
    /**
     * @var FakeScheduleRepository
     */
    public $scheduleRepository;

    /**
     * @var ScheduleAvailabilityRule
     */
    public $rule;

    public function setUp(): void
    {
        parent::setup();

        $this->scheduleRepository = new FakeScheduleRepository();

        $this->rule = new ScheduleAvailabilityRule($this->scheduleRepository, 'UTC');
    }

    public function testRuleIsValidIfScheduleDoesNotHaveAvailability()
    {
        $schedule = new FakeSchedule();
        $schedule->SetAvailableAllYear();
        $this->scheduleRepository->_Schedule = $schedule;

        $startDate = Date::Parse('2010-04-04', 'UTC');
        $endDate = Date::Parse('2010-04-05', 'UTC');

        $reservation = new TestReservationSeries();

        $dr1 = new DateRange($startDate, $endDate);
        $reservation->WithDuration($dr1);

        $result = $this->rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
    }

    public function testRuleIsValidIfFirstAndLastReservationDatesAreWithinAvailabilityBounds()
    {
        $begin = Date::Parse('2018-01-01', 'UTC');
        $end = Date::Parse('2018-02-01', 'UTC');

        $schedule = new FakeSchedule();
        $schedule->SetAvailability($begin, $end);
        $this->scheduleRepository->_Schedule = $schedule;

        $startDate = $begin;
        $endDate = $end->AddDays(-1);

        $startDate1 = $begin->AddDays(1);
        $endDate1 = $end;

        $reservation = new TestReservationSeries();

        $dr1 = new DateRange($startDate, $endDate);
        $dr2 = new DateRange($startDate1, $endDate1);
        $reservation->WithDuration($dr1);
        $reservation->WithInstanceOn($dr2);

        $result = $this->rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
    }

    public function testRuleIsInvalidIfFirstDateOutsideBounds()
    {
        $begin = Date::Parse('2018-01-01', 'UTC');
        $end = Date::Parse('2018-02-01', 'UTC');

        $schedule = new FakeSchedule();
        $schedule->SetAvailability($begin, $end);
        $this->scheduleRepository->_Schedule = $schedule;

        $startDate = $begin->AddDays(-1);
        $endDate = $end->AddDays(-1);

        $startDate1 = $begin->AddDays(1);
        $endDate1 = $end;

        $reservation = new TestReservationSeries();

        $dr1 = new DateRange($startDate, $endDate);
        $dr2 = new DateRange($startDate1, $endDate1);
        $reservation->WithDuration($dr1);
        $reservation->WithInstanceOn($dr2);

        $result = $this->rule->Validate($reservation, null);

        $this->assertFalse($result->IsValid());
    }

    public function testRuleIsInvalidIfLastDateOutsideBounds()
    {
        $begin = Date::Parse('2018-01-01', 'UTC');
        $end = Date::Parse('2018-02-01', 'UTC');

        $schedule = new FakeSchedule();
        $schedule->SetAvailability($begin, $end);
        $this->scheduleRepository->_Schedule = $schedule;

        $startDate = $begin;
        $endDate = $end->AddDays(-1);

        $startDate1 = $begin->AddDays(1);
        $endDate1 = $end->AddDays(1);

        $reservation = new TestReservationSeries();

        $dr1 = new DateRange($startDate, $endDate);
        $dr2 = new DateRange($startDate1, $endDate1);
        $reservation->WithDuration($dr1);
        $reservation->WithInstanceOn($dr2);

        $result = $this->rule->Validate($reservation, null);

        $this->assertFalse($result->IsValid());
    }
}
