<?php

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

        $rule = new ResourceCrossDayRule($this->scheduleRepository);
        $result = $rule->Validate($reservation, null);

        $this->assertFalse($result->IsValid());
    }
}
