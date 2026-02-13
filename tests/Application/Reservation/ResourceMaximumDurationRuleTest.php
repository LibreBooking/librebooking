<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class ResourceMaximumDurationRuleTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testNotValidIfTheReservationIsLongerThanTheMaximumDurationForAnyResource()
    {
        $resourceId1 = 1;
        $resourceId2 = 2;

        $resource1 = new FakeBookableResource($resourceId1, '1');
        $resource1->SetMaxLength(null);

        $resource2 = new FakeBookableResource($resourceId2, '2');
        $resource2->SetMaxLength('23h00m');

        $reservation = new TestReservationSeries();

        $duration = new DateRange(Date::Now(), Date::Now()->AddDays(1));
        $reservation->WithDuration($duration);
        $reservation->WithResource($resource1);
        $reservation->AddResource($resource2);

        $rule = new ResourceMaximumDurationRule();
        $result = $rule->Validate($reservation, null);

        $this->assertFalse($result->IsValid());
    }

    public function testOkIfReservationIsShorterThanTheMaximumDuration()
    {
        $resource = new FakeBookableResource(1, '2');
        $resource->SetMaxLength('25h00m');

        $reservation = new TestReservationSeries();
        $reservation->WithResource($resource);

        $duration = new DateRange(Date::Now(), Date::Now()->AddDays(1));
        $reservation->WithDuration($duration);

        $rule = new ResourceMaximumDurationRule();
        $result = $rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
    }
}
