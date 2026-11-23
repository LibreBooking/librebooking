<?php

declare(strict_types=1);

class ScheduleLayoutSerializableTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function testCreatesSerializableLayout()
    {
        $baseDate = Date::Now();

        $b1 = $baseDate->AddDays(1);
        $e1 = $baseDate->AddDays(2);
        $b2 = $baseDate->AddDays(3);
        $e2 = $baseDate->AddDays(4);
        $b3 = $baseDate->AddDays(5);
        $e3 = $baseDate->AddDays(6);
        $b4 = $baseDate->AddDays(7);
        $e4 = $baseDate->AddDays(8);

        $l1 = 'label 1';
        $l2 = 'label 2';

        $p1 = new SchedulePeriod($b1, $e1);
        $p2 = new NonSchedulePeriod($b2, $e2);
        $p3 = new SchedulePeriod($b3, $e3, $l1);
        $p4 = new NonSchedulePeriod($b4, $e4, $l2);
        $periods = [$p1, $p2, $p3, $p4,];

        $actual = new ScheduleLayoutSerializable($periods);

        $actualPeriods = $actual->periods;

        $this->assertEquals(count($periods), count($actualPeriods));

        $this->assertEquals($p1->Begin()->__toString(), $actualPeriods[0]->begin);
        $this->assertEquals($p1->End()->__toString(), $actualPeriods[0]->end);
        $this->assertEquals($p1->BeginDate()->__toString(), $actualPeriods[0]->beginDate);
        $this->assertEquals($p1->EndDate()->__toString(), $actualPeriods[0]->endDate);
        $this->assertEquals($p1->Label(), $actualPeriods[0]->label);
        $this->assertEquals($p1->LabelEnd(), $actualPeriods[0]->labelEnd);
        $this->assertEquals($p1->IsReservable(), $actualPeriods[0]->isReservable);

        $this->assertEquals($p2->Begin()->__toString(), $actualPeriods[1]->begin);
        $this->assertEquals($p2->End()->__toString(), $actualPeriods[1]->end);
        $this->assertEquals($p2->Label(), $actualPeriods[1]->label);
        $this->assertEquals($p2->LabelEnd(), $actualPeriods[1]->labelEnd);
        $this->assertEquals($p2->IsReservable(), $actualPeriods[1]->isReservable);
    }

    public function testMarksPeriodsThatRunPastMidnightForTheRequestedDate(): void
    {
        $date = Date::Parse('2026-11-17', 'UTC');
        $beganPreviousDay = new SchedulePeriod($date->AddDays(-1)->SetTimeString('22:00'), $date->SetTimeString('06:00'));
        $withinDay = new SchedulePeriod($date->SetTimeString('06:00'), $date->SetTimeString('22:00'));
        $endsNextDay = new SchedulePeriod($date->SetTimeString('22:00'), $date->AddDays(1)->SetTimeString('06:00'));
        $endsAtMidnight = new SchedulePeriod($date->SetTimeString('22:00'), $date->AddDays(1));

        $actual = new ScheduleLayoutSerializable(
            periods: [$beganPreviousDay, $withinDay, $endsNextDay, $endsAtMidnight],
            layoutDate: $date->SetTimeString('13:00')
        );

        $this->assertFalse($actual->periods[0]->beginsOnDate);
        $this->assertTrue($actual->periods[0]->endsOnDate);
        $this->assertTrue($actual->periods[1]->beginsOnDate);
        $this->assertTrue($actual->periods[1]->endsOnDate);
        $this->assertTrue($actual->periods[2]->beginsOnDate);
        $this->assertFalse($actual->periods[2]->endsOnDate);
        $this->assertTrue($actual->periods[3]->beginsOnDate);
        $this->assertTrue($actual->periods[3]->endsOnDate);
    }
}
