<?php

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

        $series = ReservationSeries::Create(1, $resource, null, null, $dates, new RepeatNone(), $this->fakeUser);

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

        $series = ReservationSeries::Create(1, $resource, null, null, $dates, new RepeatNone(), $this->fakeUser);

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
                    match ($matcher->numberOfInvocations()) {
                        1 => $this->assertEquals($d, $series->CurrentInstance()->StartDate()),
                        2 => $this->assertEquals($d, $series->CurrentInstance()->EndDate())
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => new SchedulePeriod($date, $date),
                        2 => new SchedulePeriod($date->AddMinutes(1), $date->AddMinutes(1))
                    };
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

        $series = ReservationSeries::Create(1, $resource, null, null, $dates, new RepeatNone(), $this->fakeUser);

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
}
