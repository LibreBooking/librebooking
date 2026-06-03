<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class ReservationDateTimeRuleTest extends TestBase
{
    public function testEnsuresThatStartMustBeBeforeEnd()
    {
        $start = Date::Parse('2010-01-01');

        $reservationDate = new DateRange($start, $start->AddDays(-1));

        $reservationSeries = new TestReservationSeries();
        $reservationSeries->WithDuration($reservationDate);

        $rule = new ReservationDateTimeRule();

        $result = $rule->Validate($reservationSeries, null);

        $this->assertFalse($result->IsValid());
    }
}
