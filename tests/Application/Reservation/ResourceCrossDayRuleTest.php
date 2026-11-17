<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class ResourceCrossDayRuleTest extends TestBase
{
    private IScheduleRepository&\PHPUnit\Framework\MockObject\MockObject $scheduleRepository;

    /**
     * @var Schedule
     */
    private $schedule;

    public function setUp(): void
    {
        parent::setup();
        $this->scheduleRepository = $this->createMock('IScheduleRepository');
        $this->schedule = new FakeSchedule();
        $this->schedule->SetTimezone('America/Chicago');
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testRuleIsValidIfBeginsAndEndsOnSameDayInScheduleTimezone()
    {
        $start = Date::Parse('2013-01-01 12:00', 'UTC');
        $end = $start->AddHours(2);

        $reservation = new TestReservationSeries();
        $reservation->WithCurrentInstance(new TestReservation('1', new DateRange($start, $end)));
        $resource = new FakeBookableResource(1);
        $resource->SetAllowMultiday(false);

        $reservation->WithResource($resource);

        $this->scheduleRepository->expects($this->once())
        ->method('LoadById')
        ->with($this->equalTo($reservation->ScheduleId()))
        ->willReturn($this->schedule);

        $rule = new ResourceCrossDayRule($this->scheduleRepository);
        $result = $rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
    }

    public function testRuleIsValidIfAllResourcesAllowMultiDay()
    {
        $start = Date::Now();
        $end = Date::Now()->AddDays(1);

        $reservation = new TestReservationSeries();
        $reservation->WithCurrentInstance(new TestReservation('1', new DateRange($start, $end)));
        $resource = new FakeBookableResource(1);
        $resource->SetAllowMultiday(true);

        $resource2 = new FakeBookableResource(2);
        $resource2->SetAllowMultiday(true);

        $reservation->WithResource($resource);
        $reservation->AddResource($resource2);

        $rule = new ResourceCrossDayRule($this->scheduleRepository);
        $result = $rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
    }

    public function testRuleIsInValidIfReservationCrossesDayInScheduleTimezone()
    {
        $start = Date::Now();
        $end = Date::Now()->AddDays(1);

        $reservation = new TestReservationSeries();
        $reservation->WithCurrentInstance(new TestReservation('1', new DateRange($start, $end)));
        $resource = new FakeBookableResource(1);
        $resource->SetAllowMultiday(true);

        $resource2 = new FakeBookableResource(2);
        $resource2->SetAllowMultiday(false);

        $reservation->WithResource($resource);
        $reservation->AddResource($resource2);

        $this->scheduleRepository->expects($this->once())
        ->method('LoadById')
        ->with($this->equalTo($reservation->ScheduleId()))
        ->willReturn($this->schedule);

        $this->scheduleRepository->method('GetLayout')
            ->willReturn(ScheduleLayout::Parse($this->schedule->GetTimezone(), '00:00-00:00', ''));

        $rule = new ResourceCrossDayRule($this->scheduleRepository);
        $result = $rule->Validate($reservation, null);

        $this->assertFalse($result->IsValid());
    }

    public function testRuleIsValidIfReservationOnlyCrossesMidnightWithinAnOvernightPeriod(): void
    {
        $start = Date::Parse('2026-11-16 22:00', 'America/Chicago');
        $end = Date::Parse('2026-11-17 06:00', 'America/Chicago');

        $result = $this->ValidateWithOvernightLayout($start, $end);

        $this->assertTrue($result->IsValid());
    }

    public function testRuleIsInvalidIfReservationContinuesAfterTheOvernightPeriod(): void
    {
        $start = Date::Parse('2026-11-16 22:00', 'America/Chicago');
        $end = Date::Parse('2026-11-17 14:00', 'America/Chicago');

        $result = $this->ValidateWithOvernightLayout($start, $end);

        $this->assertFalse($result->IsValid());
    }

    public function testRuleIsInvalidIfPeriodCrossingMidnightIsNotReservable(): void
    {
        $start = Date::Parse('2026-11-16 20:00', 'America/Chicago');
        $end = Date::Parse('2026-11-17 06:00', 'America/Chicago');

        $result = $this->ValidateWithOvernightLayout($start, $end, reservableSlots: '06:00-22:00', blockedSlots: '22:00-06:00');

        $this->assertFalse($result->IsValid());
    }

    private function ValidateWithOvernightLayout(
        Date $start,
        Date $end,
        string $reservableSlots = "22:00-06:00\n06:00-14:00",
        string $blockedSlots = '14:00-22:00'
    ): ReservationRuleResult {
        $reservation = new TestReservationSeries();
        $reservation->WithCurrentInstance(new TestReservation('1', new DateRange($start, $end)));
        $resource = new FakeBookableResource(1);
        $resource->SetAllowMultiday(false);
        $reservation->WithResource($resource);

        $this->scheduleRepository->method('LoadById')->willReturn($this->schedule);
        $this->scheduleRepository->method('GetLayout')
            ->with($this->equalTo($reservation->ScheduleId()))
            ->willReturn(ScheduleLayout::Parse($this->schedule->GetTimezone(), $reservableSlots, $blockedSlots));

        $rule = new ResourceCrossDayRule($this->scheduleRepository);

        return $rule->Validate($reservation, null);
    }
}
