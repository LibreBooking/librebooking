<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/namespace.php');

class ResourceParticipationRuleTest extends TestBase
{
    /**
     * @var ResourceParticipationRule
     */
    private $rule;

    /**
     * @var FakeBookableResource
     */
    private $resourceLimit10;

    /**
     * @var FakeBookableResource
     */
    private $resourceLimit20;

    public function setUp(): void
    {
        parent::setup();

        $this->rule = new ResourceParticipationRule();

        $this->resourceLimit10 = new FakeBookableResource(1, 'name1');
        $this->resourceLimit10->SetMaxParticipants(10);
        $this->resourceLimit20 = new FakeBookableResource(2, 'name2');
        $this->resourceLimit20->SetMaxParticipants(20);
    }

    public function testWhenNeitherResourceIsOverLimit()
    {
        $series = new TestReservationSeries();
        $series->WithCurrentInstance(new TestReservation());
        $series->WithResource($this->resourceLimit10);
        $series->AddResource($this->resourceLimit20);
        $series->AddResource(new FakeBookableResource(3));
        $series->ChangeParticipants(range(1, 5));

        $result = $this->rule->Validate($series, null);

        $this->assertTrue($result->IsValid());
    }

    public function testWhenOneResourceIsOverLimit()
    {
        $series = new TestReservationSeries();
        $series->WithCurrentInstance(new TestReservation());
        $series->WithResource($this->resourceLimit10);
        $series->AddResource($this->resourceLimit20);
        $series->ChangeParticipants(range(1, 12));

        $result = $this->rule->Validate($series, null);
        $this->assertFalse($result->IsValid());
    }

    public function testWhenBothResourcesAreOverLimit()
    {
        $series = new TestReservationSeries();
        $series->WithCurrentInstance(new TestReservation());
        $series->WithResource($this->resourceLimit10);
        $series->AddResource($this->resourceLimit20);
        $series->ChangeParticipants(range(1, 22));

        $result = $this->rule->Validate($series, null);
        $this->assertFalse($result->IsValid());
    }
}
