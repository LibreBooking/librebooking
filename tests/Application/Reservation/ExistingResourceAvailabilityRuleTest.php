<?php

use PHPUnit\Framework\MockObject\MockObject;

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class ExistingResourceAvailabilityRuleTest extends TestBase
{
    private $timezone = 'UTC';
    /**
     * @var IResourceAvailabilityStrategy|MockObject
     */
    private $strategy;

    public function setUp(): void
    {
        parent::setup();

        $this->strategy = $this->createMock('IResourceAvailabilityStrategy');
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testDoesNotConflictIfBeingDeletedOrUpdated()
    {
        $now = Date::Now();
        $currentDate = new DateRange($now->AddDays(10), $now->AddDays(15));
        $resourceId = 18;
        $id1 = 100;
        $id2 = 101;
        $currentId = 99;
        $deleted = new TestReservation('ref2', new TestDateRange());
        $deleted->SetReservationId($id1);
        $updated = new TestReservation('ref3', new TestDateRange());
        $updated->SetReservationId($id2);
        $current = new TestReservation('ref', $currentDate);
        $current->SetReservationId($currentId);

        $series = new ExistingReservationSeries();
        $series->WithPrimaryResource(new FakeBookableResource($resourceId));
        $series->WithCurrentInstance($current);
        $series->WithInstance($deleted);
        $series->WithInstance($updated);

        $series->RemoveInstance($deleted);
        $series->UpdateInstance($updated, new DateRange($now->AddDays(20), $now->AddDays(21)));

        $reservations = [
            new TestReservationItemView($id1, Date::Now(), Date::Now(), $resourceId),
            new TestReservationItemView($id2, Date::Now(), Date::Now(), $resourceId),
        ];

        $this->strategy->expects($this->exactly(2))
                       ->method('GetItemsBetween')
                       ->with($this->anything(), $this->anything())
                       ->willReturn($reservations);

        $rule = new ExistingResourceAvailabilityRule(new ReservationConflictIdentifier($this->strategy), $this->timezone);
        $ruleResult = $rule->Validate($series, null);

        $this->assertTrue($ruleResult->IsValid());
    }

    public function testDoesNotConflictIfCurrentInstanceBeingUpdated()
    {
        $resourceId = 1;
        $currentId = 19;
        $currentDate = new DateRange(Date::Now()->AddDays(10), Date::Now()->AddDays(15));
        $current = new TestReservation('ref', $currentDate);
        $current->SetReservationId($currentId);

        $series = new ExistingReservationSeries();
        $series->WithPrimaryResource(new FakeBookableResource($resourceId));
        $series->WithCurrentInstance($current);

        $reservations = [
            new TestReservationItemView($currentId, Date::Now(), Date::Now(), $resourceId),
        ];

        $this->strategy->expects($this->once())
                       ->method('GetItemsBetween')
                       ->with($this->anything(), $this->anything())
                       ->willReturn($reservations);

        $rule = new ExistingResourceAvailabilityRule(new ReservationConflictIdentifier($this->strategy), $this->timezone);
        $ruleResult = $rule->Validate($series, null);

        $this->assertTrue($ruleResult->IsValid());
    }

    public function testConflictsIfResourceReservationExistsAtSameTime()
    {
        $resourceId = 1;
        $currentId = 19;
        $currentDate = new DateRange(Date::Now()->AddDays(10), Date::Now()->AddDays(15));
        $current = new TestReservation('ref', $currentDate);
        $current->SetReservationId($currentId);

        $series = new ExistingReservationSeries();
        $series->WithPrimaryResource(new FakeBookableResource($resourceId));
        $series->WithResource(new FakeBookableResource($resourceId + 1));
        $series->WithCurrentInstance($current);

        $reservations = [
            new TestReservationItemView($currentId + 1, $currentDate->GetBegin(), $currentDate->GetEnd(), $resourceId),
        ];

        $this->strategy->expects($this->once())
                       ->method('GetItemsBetween')
                       ->with($this->anything(), $this->anything())
                       ->willReturn($reservations);

        $rule = new ExistingResourceAvailabilityRule(new ReservationConflictIdentifier($this->strategy), $this->timezone);
        $ruleResult = $rule->Validate($series, null);

        $this->assertFalse($ruleResult->IsValid());
    }

    public function testNoConflictsIfReservationExistsAtSameTimeForDifferentResource()
    {
        $resourceId1 = 1;
        $resourceId2 = 2;
        $resourceId3 = 3;
        $currentId = 19;
        $currentDate = new DateRange(Date::Now()->AddDays(10), Date::Now()->AddDays(15));
        $current = new TestReservation('ref', $currentDate);
        $current->SetReservationId($currentId);

        $series = new ExistingReservationSeries();
        $series->WithPrimaryResource(new FakeBookableResource($resourceId1));
        $series->WithResource(new FakeBookableResource($resourceId2));
        $series->WithCurrentInstance($current);

        $reservations = [
            new TestReservationItemView($currentId + 1, Date::Now(), Date::Now(), $resourceId3),
        ];

        $this->strategy->expects($this->once())
                       ->method('GetItemsBetween')
                       ->with($this->anything(), $this->anything())
                       ->willReturn($reservations);

        $rule = new ExistingResourceAvailabilityRule(new ReservationConflictIdentifier($this->strategy), $this->timezone);
        $ruleResult = $rule->Validate($series, null);

        $this->assertTrue($ruleResult->IsValid());
    }

    public function testRuleIsValidIfNoConflictsForTheReservationResourcesWithBufferTimes()
    {
        $startDate = Date::Parse('2010-04-04 06:00', 'UTC');
        $endDate = Date::Parse('2010-04-04 07:00', 'UTC');

        $r1Buffer = 60 * 60;
        $r2Buffer = 30 * 60;

        $reservation = new TestReservationSeries();
        $resource1 = new FakeBookableResource(100, null);
        $resource1->SetBufferTime($r1Buffer);

        $resource2 = new FakeBookableResource(101, null);
        $resource2->SetBufferTime($r2Buffer);

        $reservation->WithDuration(new DateRange($startDate, $endDate));
        $reservation->WithResource($resource1);
        $reservation->AddResource($resource2);
        $reservation->AddResource(new FakeBookableResource(102, null));

        $scheduleReservation1 = new TestReservationItemView(
            2,
            Date::Parse('2010-04-04 04:00', 'UTC'),
            Date::Parse('2010-04-04 05:00', 'UTC'),
            $resource1->GetId()
        );
        $scheduleReservation1->WithBufferTime($r1Buffer);

        $scheduleReservation2 = new TestReservationItemView(
            3,
            Date::Parse('2010-04-04 08:00', 'UTC'),
            Date::Parse('2010-04-04 09:00', 'UTC'),
            $resource1->GetId()
        );
        $scheduleReservation2->WithBufferTime($r1Buffer);

        $scheduleReservation3 = new TestReservationItemView(
            4,
            Date::Parse('2010-04-04 05:00', 'UTC'),
            Date::Parse('2010-04-04 05:30', 'UTC'),
            $resource2->GetId()
        );

        $scheduleReservation3->WithBufferTime($r2Buffer);

        $scheduleReservation4 = new TestReservationItemView(
            5,
            Date::Parse('2010-04-04 07:30', 'UTC'),
            Date::Parse('2010-04-04 08:00', 'UTC'),
            $resource2->GetId()
        );

        $scheduleReservation4->WithBufferTime($r2Buffer);

        $scheduleReservation5 = new TestReservationItemView(6, $startDate, $endDate, 999);
        $strategy = $this->createMock('IResourceAvailabilityStrategy');

        $strategy->expects($this->once())
                 ->method('GetItemsBetween')
                 ->with($this->equalTo($startDate->AddMinutes(-60)), $this->equalTo($endDate->AddMinutes(60)))
                 ->willReturn([$scheduleReservation1, $scheduleReservation2, $scheduleReservation3, $scheduleReservation4, $scheduleReservation5]);

        $rule = new ExistingResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), 'UTC');
        $result = $rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
    }

    public function testGetsConflictingReservationTimesForSingleDateSingleResourceWithBufferTimes()
    {
        $startDate = Date::Parse('2010-04-04 06:00', 'UTC');
        $endDate = Date::Parse('2010-04-04 07:00', 'UTC');

        $bufferTime = 60 * 60;

        $reservation = new TestReservationSeries();
        $resource1 = new FakeBookableResource(100, null);
        $resource1->SetBufferTime($bufferTime);

        $reservation->WithDuration(new DateRange($startDate, $endDate));
        $reservation->WithResource($resource1);

        $conflict1 = new TestReservationItemView(2, Date::Parse('2010-04-04 04:00', 'UTC'), Date::Parse('2010-04-04 05:30', 'UTC'), $resource1->GetId());
        $conflict1->WithBufferTime($bufferTime);

        $conflict2 = new TestReservationItemView(3, Date::Parse('2010-04-04 07:30', 'UTC'), Date::Parse('2010-04-04 08:00', 'UTC'), $resource1->GetId());
        $conflict2->WithBufferTime($bufferTime);

        $nonConflict1 = new TestReservationItemView(4, Date::Parse('2010-04-04 06:00', 'UTC'), Date::Parse('2010-04-04 07:30', 'UTC'), 2);

        $nonConflict1->WithBufferTime($bufferTime);

        $nonConflict2 = new TestReservationItemView(5, Date::Parse('2010-04-04 02:30', 'UTC'), Date::Parse('2010-04-04 05:00', 'UTC'), $resource1->GetId());
        $nonConflict2->WithBufferTime($bufferTime);

        $nonConflict3 = new TestReservationItemView(6, Date::Parse('2010-04-04 08:00', 'UTC'), Date::Parse('2010-04-04 09:00', 'UTC'), $resource1->GetId());
        $nonConflict3->WithBufferTime($bufferTime);

        $strategy = $this->createMock('IResourceAvailabilityStrategy');

        $strategy->expects($this->once())
                 ->method('GetItemsBetween')
                 ->with($this->equalTo($startDate->AddMinutes(-60)), $this->equalTo($endDate->AddMinutes(60)))
                 ->willReturn([$conflict1, $conflict2, $nonConflict1, $nonConflict2, $nonConflict3]);


        $rule = new ExistingResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), 'UTC');
        $result = $rule->Validate($reservation, null);

        $this->assertFalse($result->IsValid());
    }

    public function testNoConflictIfOverlappingCountIsLessThanWhatResourcesAllow()
    {
        $resourceId = 1;
        $currentId = 19;
        $currentDate = new DateRange(Date::Now()->AddDays(10), Date::Now()->AddDays(15));
        $current = new TestReservation('ref', $currentDate);
        $current->SetReservationId($currentId);

        $series = new ExistingReservationSeries();
        $primaryResource = new FakeBookableResource($resourceId);
        $primaryResource->SetMaxConcurrentReservations(2);
        $secondResource = new FakeBookableResource($resourceId + 1);
        $secondResource->SetMaxConcurrentReservations(2);
        $series->WithPrimaryResource($primaryResource);
        $series->WithResource($secondResource);
        $series->WithCurrentInstance($current);

        $reservations = [
            new TestReservationItemView($currentId + 1, $currentDate->GetBegin(), $currentDate->GetEnd(), $resourceId, 'r1'),
            new TestReservationItemView($currentId + 1, $currentDate->GetEnd(), $currentDate->GetEnd()->AddMinutes(30), $resourceId, 'r2'),
        ];

        $this->strategy->expects($this->once())
                       ->method('GetItemsBetween')
                       ->with($this->anything(), $this->anything())
                       ->willReturn($reservations);

        $rule = new ExistingResourceAvailabilityRule(new ReservationConflictIdentifier($this->strategy), $this->timezone);
        $ruleResult = $rule->Validate($series, null);

        $this->assertTrue($ruleResult->IsValid());
    }

    public function testNoConflictIfOverlappingCountIsLessThanWhatResourcesAllowRecurring()
    {
        $resourceId = 1;
        $start = Date::Parse('2010-01-01 01:00', $this->timezone);
        $end = Date::Parse('2010-01-01 02:00', $this->timezone);
        $reservationDates = new DateRange($start, $end);
        $series = new TestReservationSeries();
        $resource = new FakeBookableResource(1);
        $resource->SetMaxConcurrentReservations(2);
        $series->WithResource($resource);
        $series->WithDuration($reservationDates);
        $series->WithRepeatOptions(new RepeatDaily(1, $start->AddDays(3)));

        $reservationRepository = new FakeReservationViewRepository();
        $reservationRepository->_Reservations = [
            new TestReservationItemView(100, $start->AddDays(1), $end->AddDays(1), $resourceId, '100'),
            new TestReservationItemView(200, $start->AddDays(2), $end->AddDays(2), $resourceId, '200'),
            new TestReservationItemView(300, $start->AddDays(3), $end->AddDays(3), $resourceId, '300'),
        ];
        $strategy = new ResourceAvailability($reservationRepository);

        $rule = new ExistingResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), $this->timezone);
        $ruleResult = $rule->Validate($series, null);

        $this->assertTrue($ruleResult->IsValid());
    }

    public function testConflictIfOverlappingCountIsMoreThanWhatResourcesAllowOnSingleInstance()
    {
        $resourceId = 1;
        $currentId = 19;
        $currentDate = new DateRange(Date::Now()->AddDays(10), Date::Now()->AddDays(15));
        $current = new TestReservation('ref', $currentDate);
        $current->SetReservationId($currentId);
        $instance = new TestReservation('ref2', $currentDate->AddDays(1));
        $instance->SetReservationId(++$currentId);

        $series = new ExistingReservationSeries();
        $primaryResource = new FakeBookableResource($resourceId);
        $primaryResource->SetMaxConcurrentReservations(2);
        $secondResource = new FakeBookableResource($resourceId + 1);
        $secondResource->SetMaxConcurrentReservations(2);
        $series->WithPrimaryResource($primaryResource);
        $series->WithResource($secondResource);
        $series->WithCurrentInstance($current);
        $series->WithInstance($instance);

        $reservationsInstance1 = [
            new TestReservationItemView(++$currentId, $current->StartDate(), $current->EndDate(), $resourceId, "r$currentId"),
        ];

        $reservationsInstance2 = [
            new TestReservationItemView(++$currentId, $instance->StartDate(), $instance->EndDate(), $resourceId, "r$currentId"),
            new TestReservationItemView(++$currentId, $instance->StartDate(), $instance->EndDate(), $resourceId, "r$currentId"),
        ];

        $this->strategy->expects($this->exactly(2))
                       ->method('GetItemsBetween')
                       ->willReturn($reservationsInstance1, $reservationsInstance2);

        $rule = new ExistingResourceAvailabilityRule(new ReservationConflictIdentifier($this->strategy), $this->timezone);
        $ruleResult = $rule->Validate($series, null);

        $this->assertFalse($ruleResult->IsValid());
    }
}
