<?php

declare(strict_types=1);

class ReservationInstancesRuleTest extends TestBase
{
    public function testInvalidIfSelectedScopeHasNoReservationInstances()
    {
        Date::_SetNow(Date::Parse('2026-06-14 12:00:00', 'UTC'));
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_START_TIME_CONSTRAINT, ReservationStartTimeConstraint::FUTURE);

        $series = $this->BuildExistingSeries(
            Date::Parse('2026-06-14 11:00:00', 'UTC'),
            Date::Parse('2026-06-14 11:30:00', 'UTC')
        );
        $series->ApplyChangesTo(SeriesUpdateScope::FullSeries);

        $result = (new ReservationInstancesRule())->Validate($series, null);

        $this->assertFalse($result->IsValid());
        $this->assertEquals('StartIsInPast', $result->ErrorMessage());
    }

    public function testValidIfSelectedScopeHasReservationInstances()
    {
        Date::_SetNow(Date::Parse('2026-06-14 12:00:00', 'UTC'));
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_START_TIME_CONSTRAINT, ReservationStartTimeConstraint::FUTURE);

        $series = $this->BuildExistingSeries(
            Date::Parse('2026-06-14 13:00:00', 'UTC'),
            Date::Parse('2026-06-14 13:30:00', 'UTC')
        );
        $series->ApplyChangesTo(SeriesUpdateScope::FullSeries);

        $result = (new ReservationInstancesRule())->Validate($series, null);

        $this->assertTrue($result->IsValid());
    }

    private function BuildExistingSeries(Date $start, Date $end): ExistingReservationSeries
    {
        $series = new ExistingReservationSeries();
        $reservation = new Reservation($series, new DateRange($start, $end));

        $series->WithCurrentInstance($reservation);
        $series->WithRepeatOptions(new RepeatNone());
        $series->WithPrimaryResource(new FakeBookableResource(1));
        $series->UpdateBookedBy($this->fakeUser);

        return $series;
    }
}
