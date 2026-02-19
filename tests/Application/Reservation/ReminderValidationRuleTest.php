<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class ReminderValidationRuleTest extends TestBase
{
    public function testWhenEnabledAndValid()
    {
        $series = new TestReservationSeries();
        $series->AddStartReminder(new ReservationReminder(2, ReservationReminderInterval::Days));
        $series->AddEndReminder(new ReservationReminder(1, ReservationReminderInterval::Days));
        $rule = new ReminderValidationRule();
        $result = $rule->Validate($series, null);

        $this->assertTrue($result->IsValid());
    }

    public function testWhenEnabledAnInvalidValue()
    {
        $series = new TestReservationSeries();
        $series->AddStartReminder(new ReservationReminder('abc', ReservationReminderInterval::Days));
        $rule = new ReminderValidationRule();
        $result = $rule->Validate($series, null);

        $this->assertFalse($result->IsValid());
    }

    public function testWhenEnabledAnInvalidInterval()
    {
        $series = new TestReservationSeries();
        $series->AddEndReminder(new ReservationReminder('abc', ReservationReminderInterval::Days));
        $rule = new ReminderValidationRule();
        $result = $rule->Validate($series, null);

        $this->assertFalse($result->IsValid());
    }
}
