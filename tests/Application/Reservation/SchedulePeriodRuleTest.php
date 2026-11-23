<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class SchedulePeriodRuleTest extends TestBase
{
    private IScheduleRepository&\PHPUnit\Framework\MockObject\MockObject $scheduleRepository;

    private IScheduleLayout&\PHPUnit\Framework\MockObject\MockObject $layout;

    /**
     * @var SchedulePeriodRule
     */
    private $rule;

    public function setUp(): void
    {
        parent::setup();

        $this->scheduleRepository = $this->createMock('IScheduleRepository');
        $this->layout = $this->createMock('IScheduleLayout');

        $this->rule = new SchedulePeriodRule($this->scheduleRepository, $this->fakeUser);
    }

    public function testFailsWhenStartTimeDoesNotMatchPeriod()
    {
        $date = Date::Now();
        $dates = new DateRange($date, $date);
        $scheduleId = 1232;
        $resource = new FakeBookableResource(1);
        $resource->SetScheduleId($scheduleId);

        $series = ReservationSeries::Create(
            userId: 1,
            resource: $resource,
            title: '',
            description: '',
            reservationDate: $dates,
            repeatOptions: new RepeatNone(),
            bookedBy: $this->fakeUser
        );

        $this->scheduleRepository
                ->expects($this->once())
                ->method('GetLayout')
                ->with(
                    $this->equalTo($scheduleId),
                    $this->equalTo(new ScheduleLayoutFactory($this->fakeUser->Timezone))
                )
                ->willReturn($this->layout);

        $this->layout
                ->expects($this->atLeastOnce())
                ->method('GetPeriod')
                ->with($this->equalTo($series->CurrentInstance()->StartDate()))
                ->willReturn(new SchedulePeriod($date->AddMinutes(1), $date));

        $result = $this->rule->Validate($series, null);

        $this->assertFalse($result->IsValid());
    }

    public function testFailsWhenEndTimeDoesNotMatchPeriod()
    {
        $date = Date::Now();
        $dates = new DateRange($date, $date);
        $scheduleId = 1232;
        $resource = new FakeBookableResource(1);
        $resource->SetScheduleId($scheduleId);

        $series = ReservationSeries::Create(
            userId: 1,
            resource: $resource,
            title: '',
            description: '',
            reservationDate: $dates,
            repeatOptions: new RepeatNone(),
            bookedBy: $this->fakeUser
        );

        $this->scheduleRepository
                ->expects($this->once())
                ->method('GetLayout')
                ->with(
                    $this->equalTo($scheduleId),
                    $this->equalTo(new ScheduleLayoutFactory($this->fakeUser->Timezone))
                )
                ->willReturn($this->layout);

        $matcher = $this->exactly(2);
        $this->layout
                ->expects($matcher)
                ->method('GetPeriod')
                ->willReturnCallback(function (Date $d) use ($matcher, $series, $date) {
                    $invocation = $matcher->numberOfInvocations();
                    match ($invocation) {
                        1 => $this->assertEquals($d, $series->CurrentInstance()->StartDate()),
                        2 => $this->assertEquals($d, $series->CurrentInstance()->EndDate()),
                        default => $this->fail('Unexpected invocation ' . $invocation),
                    };

                    return $invocation === 1
                        ? new SchedulePeriod($date, $date)
                        : new SchedulePeriod($date->AddMinutes(1), $date->AddMinutes(1));
                });

        $result = $this->rule->Validate($series, null);

        $this->assertFalse($result->IsValid());
    }

    public function testSucceedsWhenStartAndEndTimeMatchPeriods()
    {
        $date = Date::Now();
        $dates = new DateRange($date, $date);
        $scheduleId = 1232;
        $resource = new FakeBookableResource(1);
        $resource->SetScheduleId($scheduleId);

        $series = ReservationSeries::Create(
            userId: 1,
            resource: $resource,
            title: '',
            description: '',
            reservationDate: $dates,
            repeatOptions: new RepeatNone(),
            bookedBy: $this->fakeUser
        );

        $this->scheduleRepository
                ->expects($this->once())
                ->method('GetLayout')
                ->with(
                    $this->equalTo($scheduleId),
                    $this->equalTo(new ScheduleLayoutFactory($this->fakeUser->Timezone))
                )
                ->willReturn($this->layout);

        $period = new SchedulePeriod($date, $date);
        $this->layout
                ->expects($this->exactly(2))
                ->method('GetPeriod')
                ->willReturn($period);

        $result = $this->rule->Validate($series, null);

        $this->assertTrue($result->IsValid());
    }

    public function testDoesNotEvenCheckIfTheDatesHaveNotBeenChanged()
    {
        $dates = new DateRange(Date::Now(), Date::Now());

        $series = new ExistingReservationSeries();
        $current = new Reservation($series, $dates, 123);
        $current->SetReservationDate($dates);
        $series->WithCurrentInstance($current);

        $result = $this->rule->Validate($series, null);
        $this->assertTrue($result->IsValid());
    }

    public function testSucceedsWhenReservationUsesAPeriodThatRunsPastMidnight(): void
    {
        $timezone = $this->fakeUser->Timezone;
        $start = Date::Parse('2026-11-16 22:00', $timezone);
        $end = Date::Parse('2026-11-17 06:00', $timezone);
        $resource = new FakeBookableResource(1);
        $resource->SetScheduleId(1232);

        $series = ReservationSeries::Create(
            userId: 1,
            resource: $resource,
            title: '',
            description: '',
            reservationDate: new DateRange($start, $end),
            repeatOptions: new RepeatNone(),
            bookedBy: $this->fakeUser
        );

        $this->scheduleRepository
            ->method('GetLayout')
            ->willReturn(ScheduleLayout::Parse($timezone, "22:00-06:00\n06:00-14:00", '14:00-22:00'));

        $result = $this->rule->Validate($series, null);

        $this->assertTrue($result->IsValid(), $result->ErrorMessage());
    }
}
