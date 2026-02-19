<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class ResourceMinimumDurationRuleTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testNotValidIfTheReservationIsShorterThanTheMinDurationForAnyResource()
    {
        $resource1 = new FakeBookableResource(1, '1');
        $resource1->SetMinLength(null);

        $resource2 = new FakeBookableResource(2, '2');
        $resource2->SetMinLength('25h00m');

        $reservation = new TestReservationSeries();

        $duration = new DateRange(Date::Now(), Date::Now()->AddDays(1));
        $reservation->WithDuration($duration);
        $reservation->WithResource($resource1);
        $reservation->AddResource($resource2);

        $rule = new ResourceMinimumDurationRule();
        $result = $rule->Validate($reservation, null);

        $this->assertFalse($result->IsValid());
    }

    public function testOkIfReservationIsLongerThanTheMinDuration()
    {
        $resource = new FakeBookableResource(1, '2');
        $resource->SetMinLength('23h00m');

        $reservation = new TestReservationSeries();
        $reservation->WithResource($resource);

        $duration = new DateRange(Date::Now(), Date::Now()->AddDays(1));
        $reservation->WithDuration($duration);

        $rule = new ResourceMinimumDurationRule();
        $result = $rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
    }
}
