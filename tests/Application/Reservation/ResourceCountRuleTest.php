<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class ResourceCountRuleTest extends TestBase
{
    /**
     * @var FakeScheduleRepository
     */
    private $scheduleRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->scheduleRepository = new FakeScheduleRepository();
        $this->scheduleRepository->_Schedule = new FakeSchedule();
    }

    public function testFailsIfReservingMoreResourcesThanAllowed()
    {
        $this->scheduleRepository->_Schedule->SetMaxResourcesPerReservation(1);

        $resource1 = new FakeBookableResource(1, '1');
        $resource2 = new FakeBookableResource(2, '2');

        $reservation = new TestReservationSeries();
        $reservation->WithResource($resource1);
        $reservation->AddResource($resource2);

        $rule = new ResourceCountRule($this->scheduleRepository);
        $result = $rule->Validate($reservation, null);

        $this->assertFalse($result->IsValid());
    }

    public function testOkIfLessThanAllowed()
    {
        $this->scheduleRepository->_Schedule->SetMaxResourcesPerReservation(2);

        $resource1 = new FakeBookableResource(1, '1');
        $resource2 = new FakeBookableResource(2, '2');

        $reservation = new TestReservationSeries();
        $reservation->WithResource($resource1);
        $reservation->AddResource($resource2);

        $rule = new ResourceCountRule($this->scheduleRepository);
        $result = $rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
    }

    public function testOkIfAllowedNotSet()
    {
        $this->scheduleRepository->_Schedule->SetMaxResourcesPerReservation(0);

        $resource1 = new FakeBookableResource(1, '1');
        $resource2 = new FakeBookableResource(2, '2');

        $reservation = new TestReservationSeries();
        $reservation->WithResource($resource1);
        $reservation->AddResource($resource2);

        $rule = new ResourceCountRule($this->scheduleRepository);
        $result = $rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
    }
}
