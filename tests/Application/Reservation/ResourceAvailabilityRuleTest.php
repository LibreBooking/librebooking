<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class ResourceAvailabilityRuleTest extends TestBase
{
    /**
     * @var FakeScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @var FakeSchedule
     */
    private $schedule;

    public function setUp(): void
    {
        $this->scheduleRepository = new FakeScheduleRepository();
        $this->schedule = new FakeSchedule();
        $this->scheduleRepository->_Schedule = $this->schedule;
        parent::setup();
    }


    public function testRuleIsValidIfNoConflictsForTheReservationResources()
    {
        $startDate = Date::Parse('2010-04-04', 'UTC');
        $endDate = Date::Parse('2010-04-05', 'UTC');

        $reservation = new TestReservationSeries();
        $reservation->WithResource(new FakeBookableResource(100, null));
        $reservation->WithDuration(new DateRange($startDate, $endDate));
        $reservation->AddResource(new FakeBookableResource(101, null));
        $reservation->AddResource(new FakeBookableResource(102, null));

        $scheduleReservation = new TestReservationItemView(2, $startDate, $endDate, 1);

        $strategy = $this->createMock('IResourceAvailabilityStrategy');

        $strategy->expects($this->once())
                 ->method('GetItemsBetween')
                 ->with($this->equalTo($startDate), $this->equalTo($endDate))
                 ->willReturn([$scheduleReservation]);

        $rule = new ResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), 'UTC');
        $result = $rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
        $this->assertFalse($result->CanBeRetried());
    }

    public function testGetsConflictingReservationTimesForSingleDateSingleResource()
    {
        $startDate = Date::Parse('2010-04-04', 'UTC');
        $endDate = Date::Parse('2010-04-06', 'UTC');
        $resourceId = 100;

        $reservation = new TestReservationSeries();
        $reservation->WithResource(new FakeBookableResource($resourceId));
        $reservation->WithDuration(new DateRange($startDate, $endDate));

        $startConflict1 = Date::Parse('2010-04-04', 'UTC');
        $endConflict1 = Date::Parse('2010-04-08', 'UTC');

        $startConflict2 = Date::Parse('2010-04-05', 'UTC');
        $endConflict2 = Date::Parse('2010-04-08', 'UTC');

        $startNonConflict1 = Date::Parse('2010-04-06', 'UTC');
        $endNonConflict1 = Date::Parse('2010-04-08', 'UTC');

        $startNonConflict2 = Date::Parse('2010-04-02', 'UTC');
        $endNonConflict2 = Date::Parse('2010-04-04', 'UTC');

        $reservations = [
                new TestReservationItemView(2, $startConflict1, $endConflict1, $resourceId),
                new TestReservationItemView(3, $startConflict2, $endConflict2, 2),
                new TestReservationItemView(4, $startNonConflict1, $startNonConflict2, $resourceId),
                new TestReservationItemView(5, $startNonConflict2, $endNonConflict2, $resourceId),
        ];

        $strategy = $this->createMock('IResourceAvailabilityStrategy');

        $strategy->expects($this->once())
                 ->method('GetItemsBetween')
                 ->with($this->equalTo($startDate), $this->equalTo($endDate))
                 ->willReturn($reservations);

        $rule = new ResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), 'UTC');
        $result = $rule->Validate($reservation, null);

        $this->assertFalse($result->IsValid());
        $this->assertFalse($result->CanBeRetried());
        $this->assertTrue($result->CanJoinWaitlist());
    }

    public function testGetsConflictingReservationTimesForSingleDateMultipleResources()
    {
        $startDate = Date::Parse('2010-04-04', 'UTC');
        $endDate = Date::Parse('2010-04-06', 'UTC');
        $additionalResourceId = 1;

        $reservation = new TestReservationSeries();
        $reservation->WithResource(new FakeBookableResource(100));
        $reservation->WithDuration(new DateRange($startDate, $endDate));
        $reservation->AddResource(new FakeBookableResource($additionalResourceId));

        $startConflict1 = Date::Parse('2010-04-04', 'UTC');
        $endConflict1 = Date::Parse('2010-04-08', 'UTC');

        $startConflict2 = Date::Parse('2010-04-05', 'UTC');
        $endConflict2 = Date::Parse('2010-04-08', 'UTC');

        $reservations = [
                new TestReservationItemView(2, $startConflict1, $endConflict1, 2),
                new TestReservationItemView(3, $startConflict2, $endConflict2, $additionalResourceId),
        ];

        $strategy = $this->createMock('IResourceAvailabilityStrategy');

        $strategy->expects($this->once())
                 ->method('GetItemsBetween')
                 ->with($this->equalTo($startDate), $this->equalTo($endDate))
                 ->willReturn($reservations);

        $rule = new ResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), 'UTC');
        $result = $rule->Validate($reservation, null);

        $this->assertFalse($result->IsValid());
        $this->assertTrue(!is_null($result->ErrorMessage()));
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

        $rule = new ResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), 'UTC');
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

        $conflict1 = new TestReservationItemView(
            2,
            Date::Parse('2010-04-04 04:00', 'UTC'),
            Date::Parse('2010-04-04 06:00', 'UTC'),
            $resource1->GetId()
        );
        $conflict1->WithBufferTime($bufferTime);

        $conflict2 = new TestReservationItemView(
            3,
            Date::Parse('2010-04-04 07:00', 'UTC'),
            Date::Parse('2010-04-04 08:00', 'UTC'),
            $resource1->GetId()
        );
        $conflict2->WithBufferTime($bufferTime);

        $nonConflict1 = new TestReservationItemView(
            4,
            Date::Parse('2010-04-04 06:00', 'UTC'),
            Date::Parse('2010-04-04 07:30', 'UTC'),
            2
        );

        $nonConflict1->WithBufferTime($bufferTime);

        $nonConflict2 = new TestReservationItemView(
            5,
            Date::Parse('2010-04-04 02:30', 'UTC'),
            Date::Parse('2010-04-04 05:00', 'UTC'),
            $resource1->GetId()
        );
        $nonConflict2->WithBufferTime($bufferTime);

        $nonConflict3 = new TestReservationItemView(
            6,
            Date::Parse('2010-04-04 08:00', 'UTC'),
            Date::Parse('2010-04-04 09:00', 'UTC'),
            $resource1->GetId()
        );
        $nonConflict3->WithBufferTime($bufferTime);

        $strategy = $this->createMock('IResourceAvailabilityStrategy');

        $strategy->expects($this->once())
                 ->method('GetItemsBetween')
                 ->with($this->equalTo($startDate->AddMinutes(-60)), $this->equalTo($endDate->AddMinutes(60)))
                 ->willReturn([$conflict1, $conflict2, $nonConflict1, $nonConflict2, $nonConflict3]);


        $rule = new ResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), 'UTC');
        $result = $rule->Validate($reservation, null);

        $this->assertFalse($result->IsValid());
    }

    public function testApplicationAdminsAreExcludedFromBufferConstraints()
    {
        $startDate = Date::Parse('2010-04-04 06:00', 'UTC');
        $endDate = Date::Parse('2010-04-04 07:00', 'UTC');

        $bufferTime = 60 * 60;

        $reservation = new TestReservationSeries();
        $resource1 = new FakeBookableResource(100, null);
        $resource1->SetBufferTime($bufferTime);


        $reservation->WithDuration(new DateRange($startDate, $endDate));
        $reservation->WithResource($resource1);
        $reservation->WithBookedBy(new FakeUserSession(true));

        $conflict1 = new TestReservationItemView(
            2,
            Date::Parse('2010-04-04 04:00', 'UTC'),
            Date::Parse('2010-04-04 06:00', 'UTC'),
            $resource1->GetId()
        );
        $conflict1->WithBufferTime($bufferTime);

        $strategy = $this->createMock('IResourceAvailabilityStrategy');

        $strategy->expects($this->once())
                 ->method('GetItemsBetween')
                 ->with($this->equalTo($startDate), $this->equalTo($endDate))
                 ->willReturn([$conflict1]);


        $rule = new ResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), "UTC");
        $result = $rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
    }

    public function testValidatesEachDateThatAReservationRepeatsOn()
    {
        $start = Date::Parse('2010-01-01');
        $end = Date::Parse('2010-01-02');
        $reservationDates = new DateRange($start, $end);
        $twoRepetitions = new RepeatWeekly(1, $start->AddDays(14), [$start->Weekday()]);

        $repeatDates = $twoRepetitions->GetDates($reservationDates);

        $reservation = new TestReservationSeries();
        $reservation->WithResource(new FakeBookableResource(1));
        $reservation->WithDuration($reservationDates);
        $reservation->WithRepeatOptions($twoRepetitions);

        $strategy = $this->createMock('IResourceAvailabilityStrategy');

        $strategy->expects($this->exactly(1 + count($repeatDates)))
                 ->method('GetItemsBetween')
                 ->with($this->anything(), $this->anything())
                 ->willReturn([]);

        $rule = new ResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), 'UTC');
        $result = $rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
    }

    public function testReservationStrategyChecksReservations()
    {
        $startDate = Date::Now();
        $endDate = Date::Now();
        $resourceIds = [1, 2];

        $repository = $this->createMock('IReservationViewRepository');

        $strategy = new ResourceAvailability($repository);

        $reservations = ['reservation'];
        $repository->expects($this->once())
                   ->method('GetReservations')
                   ->with($this->equalTo($startDate), $this->equalTo($endDate), $this->isNull(), $this->isNull(), $this->isNull(), $this->equalTo($resourceIds))
                   ->willReturn($reservations);

        $blackouts = ['blackout'];
        $repository->expects($this->once())
                   ->method('GetBlackoutsWithin')
                   ->with($this->equalTo(new DateRange($startDate, $endDate)))
                   ->willReturn($blackouts);

        $items = $strategy->GetItemsBetween($startDate, $endDate, $resourceIds);

        $this->assertEquals(array_merge($reservations, $blackouts), $items);
    }

    public function testCanRetryIfThereAreConflictsThatCanBeSkipped()
    {
        $startDate = Date::Parse('2010-04-04', 'UTC');
        $endDate = Date::Parse('2010-04-06', 'UTC');

        $reservation = new TestReservationSeries();
        $reservation->WithResource(new FakeBookableResource(100));
        $reservation->WithDuration(new DateRange($startDate, $endDate));
        $instance = DateRange::Create('2010-04-10', '2010-04-12', 'UTC');
        $reservation->WithInstanceOn($instance);

        $startConflict1 = Date::Parse('2010-04-04', 'UTC');
        $endConflict1 = Date::Parse('2010-04-08', 'UTC');

        $reservations = [
                new TestReservationItemView(2, $startConflict1, $endConflict1, 100),
        ];

        $strategy = $this->createMock('IResourceAvailabilityStrategy');

        $strategy->expects($this->exactly(2))
                 ->method('GetItemsBetween')
                 ->willReturnCallback(function(Date $start, Date $end) use ($startDate, $endDate, $reservations, $instance)
                 {
                    if ($start->Equals($startDate) && $end->Equals($endDate))
                        return $reservations;

                    if ($start->Equals($instance->GetBegin()) && $end->Equals($instance->GetEnd()))
                        return [];

                    throw new Exception("Unexpected arguments");
                 });

        $rule = new ResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), 'UTC');
        $result = $rule->Validate($reservation, null);

        $this->assertTrue(
            $result->CanBeRetried(),
            'should only be able to retry if there are less conflicts than dates reserved'
        );
        $this->assertEquals([new ReservationRetryParameter(ReservationRetryParameter::$SKIP_CONFLICTS, true)], $result->RetryParameters());
    }

    public function testSkipsConflictsIfRequested()
    {
        $startDate = Date::Parse('2010-04-04', 'UTC');
        $endDate = Date::Parse('2010-04-06', 'UTC');

        $reservation = new TestReservationSeries();
        $reservation->WithResource(new FakeBookableResource(100));
        $reservation->WithDuration(new DateRange($startDate, $endDate));
        $instance = DateRange::Create('2010-04-10', '2010-04-12', 'UTC');
        $reservation->WithInstanceOn($instance);

        $startConflict1 = Date::Parse('2010-04-10', 'UTC');
        $endConflict1 = Date::Parse('2010-04-11', 'UTC');

        $reservations = [
                new TestReservationItemView(2, $startConflict1, $endConflict1, 100),
        ];

        $strategy = $this->createMock('IResourceAvailabilityStrategy');

        $strategy->expects($this->exactly(2))
                 ->method('GetItemsBetween')
                 ->willReturnCallback(function(Date $start, Date $end) use ($startDate, $endDate, $reservations, $instance)
                 {
                    if ($start->Equals($startDate) && $end->Equals($endDate))
                        return [];

                    if ($start->Equals($instance->GetBegin()) && $end->Equals($instance->GetEnd()))
                        return $reservations;

                    throw new Exception("Unexpected arguments");
                 });

        $rule = new ResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), 'UTC');
        $result = $rule->Validate($reservation, [new ReservationRetryParameter(ReservationRetryParameter::$SKIP_CONFLICTS, true)]);

        $this->assertTrue($result->IsValid(), 'should have skipped conflicts');
        $this->assertEquals(1, count($reservation->Instances()));
    }

    public function testSkipsCorrectDatesWhenUpdatingExisting()
    {
        $this->fakeUser->Timezone = 'UTC';
        Date::_SetNow(Date::Parse('2010-04-01', 'UTC'));
        $startDate = Date::Parse('2010-04-09 06:00', 'UTC');
        $endDate = Date::Parse('2010-04-09 08:00', 'UTC');

        $reservation = new ExistingReservationSeries();
        $reservation->UpdateBookedBy($this->fakeUser);
        $reservation->ApplyChangesTo(SeriesUpdateScope::FullSeries);
        $reservation->WithCurrentInstance(new TestReservation('not conflict', new DateRange($startDate, $endDate)));
        $reservation->WithPrimaryResource(new FakeBookableResource(100));
        $reservation->WithRepeatOptions(new RepeatDaily(1, Date::Parse('2010-04-10', 'UTC')));
        $reservation->UpdateDuration(new DateRange($startDate, $endDate));
        $reservation->Repeats(new RepeatDaily(1, Date::Parse('2010-04-12', 'UTC')));

        $startConflict1 = Date::Parse('2010-04-10 06:00', 'UTC');
        $endConflict1 = Date::Parse('2010-04-10 08:00', 'UTC');

        $reservations = [
                new TestReservationItemView(2, $startConflict1, $endConflict1, 100),
        ];

        $strategy = $this->createMock('IResourceAvailabilityStrategy');

        $strategy->expects($this->atLeastOnce())
                 ->method('GetItemsBetween')
                 ->willReturn($reservations);

        $rule = new ResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), 'UTC');
        $result = $rule->Validate($reservation, [new ReservationRetryParameter(ReservationRetryParameter::$SKIP_CONFLICTS, true)]);

        $this->assertTrue($result->IsValid(), 'should have skipped conflicts');
        $this->assertEquals(3, count($reservation->Instances()));
        $events = $reservation->GetEvents();
        $this->assertEquals(3, count($events), 'we shouldnt have the insert for the conflict');
    }

    public function testBackToBackReservationsAndAnotherConcurrentCoveringBoth()
    {
        $reservation = new TestReservationSeries();
        $resource = new FakeBookableResource(1);
        $resource->SetMaxConcurrentReservations(3);
        $reservation->WithResource($resource);
        $reservation->WithDuration(DateRange::Create('2020-09-17 09:00', '2020-09-17 12:00', 'UTC'));

        $reservationRepository = new FakeReservationViewRepository();
        $reservationRepository->_Reservations = [
                new TestReservationItemView(100, Date::Parse('2020-09-17 10:00', 'UTC'), Date::Parse('2020-09-17 11:00', 'UTC'), 1, 'r1'),
                new TestReservationItemView(200, Date::Parse('2020-09-17 11:00', 'UTC'), Date::Parse('2020-09-17 12:00', 'UTC'), 1, 'r2'),
                new TestReservationItemView(300, Date::Parse('2020-09-17 9:30', 'UTC'), Date::Parse('2020-09-17 11:00', 'UTC'), 1, 'r3'),
                ];
        $strategy = new ResourceAvailability($reservationRepository);
        $rule = new ResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), 'UTC');

        $result = $rule->Validate($reservation, null);
        $this->assertTrue($result->IsValid());
    }

    public function testExistingStartingAtTheSameTime()
    {
        $reservation = new TestReservationSeries();
        $resource = new FakeBookableResource(1);
        $resource->SetMaxConcurrentReservations(3);
        $reservation->WithResource($resource);
        $reservation->WithDuration(DateRange::Create('2020-09-17 09:00', '2020-09-17 9:30', 'UTC'));

        $reservationRepository = new FakeReservationViewRepository();
        $reservationRepository->_Reservations = [
            new TestReservationItemView(100, Date::Parse('2020-09-17 9:00', 'UTC'), Date::Parse('2020-09-17 10:30', 'UTC'), 1, 'r1'),
            new TestReservationItemView(200, Date::Parse('2020-09-17 9:00', 'UTC'), Date::Parse('2020-09-17 9:30', 'UTC'), 1, 'r2'),
            new TestReservationItemView(300, Date::Parse('2020-09-17 9:00', 'UTC'), Date::Parse('2020-09-17 9:30', 'UTC'), 1, 'r3'),
        ];
        $strategy = new ResourceAvailability($reservationRepository);
        $rule = new ResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), 'UTC');

        $result = $rule->Validate($reservation, null);
        $this->assertFalse($result->IsValid());
    }

    public function testBackToBackReservationsAndAnotherConcurrentCoveringBothWithAllowingOnlyTwo()
    {
        $reservation = new TestReservationSeries();
        $resource = new FakeBookableResource(1);
        $resource->SetMaxConcurrentReservations(2);
        $reservation->WithResource($resource);
        $reservation->WithDuration(DateRange::Create('2020-09-17 09:00', '2020-09-17 12:00', 'UTC'));

        $reservationRepository = new FakeReservationViewRepository();
        $reservationRepository->_Reservations = [
            new TestReservationItemView(100, Date::Parse('2020-09-17 10:00', 'UTC'), Date::Parse('2020-09-17 12:00', 'UTC'), 1, 'r1'),
            new TestReservationItemView(200, Date::Parse('2020-09-17 10:00', 'UTC'), Date::Parse('2020-09-17 11:00', 'UTC'), 1, 'r2'),
            new TestReservationItemView(300, Date::Parse('2020-09-17 11:00', 'UTC'), Date::Parse('2020-09-17 12:00', 'UTC'), 1, 'r3'),
        ];
        $strategy = new ResourceAvailability($reservationRepository);
        $rule = new ResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), 'UTC');

        $result = $rule->Validate($reservation, null);
        $this->assertFalse($result->IsValid());
    }

    public function testConcurrentWithGap()
    {
        $reservation = new TestReservationSeries();
        $resource = new FakeBookableResource(1);
        $resource->SetMaxConcurrentReservations(3);
        $reservation->WithResource($resource);
        $reservation->WithDuration(DateRange::Create('2020-09-17 14:30', '2020-09-17 16:00', 'UTC'));

        $reservationRepository = new FakeReservationViewRepository();
        $reservationRepository->_Reservations = [
            new TestReservationItemView(100, Date::Parse('2020-09-17 13:30', 'UTC'), Date::Parse('2020-09-17 14:30', 'UTC'), 1, 'r1'),
            new TestReservationItemView(200, Date::Parse('2020-09-17 13:30', 'UTC'), Date::Parse('2020-09-17 15:00', 'UTC'), 1, 'r2'),
            new TestReservationItemView(300, Date::Parse('2020-09-17 14:00', 'UTC'), Date::Parse('2020-09-17 16:00', 'UTC'), 1, 'r3'),
            new TestReservationItemView(400, Date::Parse('2020-09-17 15:00', 'UTC'), Date::Parse('2020-09-17 15:30', 'UTC'), 1, 'r4'),
        ];
        $strategy = new ResourceAvailability($reservationRepository);
        $rule = new ResourceAvailabilityRule(new ReservationConflictIdentifier($strategy), 'UTC');

        $result = $rule->Validate($reservation, null);
        $this->assertTrue($result->IsValid());
    }
}
