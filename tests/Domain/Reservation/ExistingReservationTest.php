<?php

require_once(ROOT_DIR . 'Domain/namespace.php');

class ExistingReservationTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testWhenApplyingSimpleUpdatesToSingleInstance()
    {
        $currentSeriesDate = new DateRange(Date::Now(), Date::Now());

        $oldDates = $currentSeriesDate->AddDays(-1);
        $oldReservation = new TestReservation('old', $oldDates);

        $currentInstance = new TestReservation('current', $currentSeriesDate);

        $futureDates1 = $currentSeriesDate->AddDays(1);
        $futureReservation1 = new TestReservation('new1', $futureDates1);

        $futureDates2 = $currentSeriesDate->AddDays(10);
        $futureReservation2 = new TestReservation('new2', $futureDates2);

        $currentRepeatOptions = new RepeatDaily(1, $currentSeriesDate->AddDays(50)->GetBegin());

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($currentInstance);
        $builder->WithRepeatOptions($currentRepeatOptions);
        $builder->WithInstance($oldReservation);
        $builder->WithInstance($currentInstance);
        $builder->WithInstance($futureReservation1);
        $builder->WithInstance($futureReservation2);
        $series = $builder->Build();
        // updates
        $series->ApplyChangesTo(SeriesUpdateScope::ThisInstance);
        $series->Update($series->UserId(), $series->Resource(), 'new', 'new', new FakeUserSession());
        $series->Repeats($currentRepeatOptions);

        $instances = $series->Instances();

        $this->assertEquals(1, count($instances), "should only be existing");

        $events = $series->GetEvents();

        // remove all future events
        $seriesBranchedEvent = new SeriesBranchedEvent($series);
        $this->assertEquals(1, count($events));
        $this->assertEquals($seriesBranchedEvent, $events[0], "should have been branched");
        $this->assertEquals(
            new RepeatNone(),
            $series->RepeatOptions(),
            "repeat options should be cleared for new instance"
        );
    }

    public function testWhenApplyingRecurrenceUpdatesToFutureInstancesSeries()
    {
        $currentSeriesDate = DateRange::Create('2010-01-01 08:30:00', '2010-01-01 12:30:00', 'UTC');

        $oldDates = $currentSeriesDate->AddDays(-1);
        $oldReservation = new TestReservation('old', $oldDates);

        $currentInstance = new TestReservation('current', $currentSeriesDate);

        $futureDates1 = $currentSeriesDate->AddDays(1);
        $futureReservation1 = new TestReservation('new1', $futureDates1);

        $futureDates2 = $currentSeriesDate->AddDays(10);
        $futureReservation2 = new TestReservation('new2', $futureDates2);

        $currentRepeatOptions = new RepeatDaily(1, $currentSeriesDate->AddDays(50)->GetBegin());

        $repeatDaily = new RepeatDaily(1, $currentSeriesDate->AddDays(10)->GetBegin());

        $builder = new ExistingReservationSeriesBuilder();
        //		$builder->WithBookedBy($this->fakeUser);
        $builder->WithRepeatOptions($currentRepeatOptions);
        $builder->WithInstance($oldReservation);
        $builder->WithInstance($currentInstance);
        $builder->WithInstance($futureReservation1);
        $builder->WithInstance($futureReservation2);
        $builder->WithCurrentInstance($currentInstance);
        $series = $builder->Build();
        // updates
        $series->ApplyChangesTo(SeriesUpdateScope::FutureInstances);
        $series->Repeats($repeatDaily);
        $series->Update($series->UserId(), $series->Resource(), $series->Title(), $series->Description(), $this->fakeUser);

        $instances = $series->Instances();

        $this->assertEquals(11, count($instances), "1 existing, 10 repeated dates");

        $events = $series->GetEvents();

        $this->assertEquals(13, count($events), "1 branched, 10 created, 2 removed");
        // remove all future events
        $instanceRemovedEvent1 = new InstanceRemovedEvent($futureReservation1, $series);
        $instanceRemovedEvent2 = new InstanceRemovedEvent($futureReservation2, $series);
        $seriesBranchedEvent = new SeriesBranchedEvent($series);

        $this->assertTrue(
            in_array($instanceRemovedEvent1, $events),
            "missing ref {$futureReservation1->ReferenceNumber()}"
        );
        $this->assertTrue(
            in_array($instanceRemovedEvent2, $events),
            "missing ref {$futureReservation2->ReferenceNumber()}"
        );
        $this->assertTrue(in_array($seriesBranchedEvent, $events), "should have been branched");

        // recreate all future events
        foreach ($instances as $instance) {
            if ($instance == $currentInstance) {
                continue;
            }

            $instanceAddedEvent = new InstanceAddedEvent($instance, $series);
            $this->assertTrue(in_array($instanceAddedEvent, $events), "missing ref num {$instance->ReferenceNumber()}");
        }
    }

    public function testWhenApplyingSimpleUpdatesToFutureInstancesSeries()
    {
        $currentSeriesDate = new DateRange(Date::Now(), Date::Now());

        $oldDates = $currentSeriesDate->AddDays(-1);
        $oldReservation = new TestReservation('old', $oldDates);

        $currentInstance = new TestReservation('current', $currentSeriesDate);

        $futureDates1 = $currentSeriesDate->AddDays(1);
        $futureReservation1 = new TestReservation('new1', $futureDates1);

        $futureDates2 = $currentSeriesDate->AddDays(10);
        $futureReservation2 = new TestReservation('new2', $futureDates2);

        $currentRepeatOptions = new RepeatDaily(1, $currentSeriesDate->AddDays(50)->GetBegin());

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($currentInstance);
        $builder->WithRepeatOptions($currentRepeatOptions);
        $builder->WithInstance($oldReservation);
        $builder->WithInstance($currentInstance);
        $builder->WithInstance($futureReservation1);
        $builder->WithInstance($futureReservation2);
        $series = $builder->Build();
        // updates
        $series->ApplyChangesTo(SeriesUpdateScope::FutureInstances);
        $series->Repeats($currentRepeatOptions);
        $series->Update($series->UserId(), $series->Resource(), $series->Title(), $series->Description(), $this->fakeUser);

        $instances = $series->Instances();

        $this->assertEquals(3, count($instances), "should only be existing and future instances");

        $events = $series->GetEvents();

        // remove all future events
        $seriesBranchedEvent = new SeriesBranchedEvent($series);
        $this->assertEquals(1, count($events));
        $this->assertEquals($seriesBranchedEvent, $events[0], "should have been branched");
    }

    public function testWhenApplyingRecurrenceUpdatesToFullSeries()
    {
        $today = new DateRange(Date::Now(), Date::Now());

        $oldDates = $today->AddDays(-1);
        $oldReservation = new TestReservation('old', $oldDates);

        $currentSeriesDate = $today->AddDays(5);
        $currentInstance = new TestReservation('current', $currentSeriesDate);

        $futureDates1 = $today->AddDays(1);
        $afterTodayButBeforeCurrent = new TestReservation('new1', $futureDates1);

        $futureDates2 = $today->AddDays(10);
        $afterCurrent = new TestReservation('new2', $futureDates2);

        $currentRepeatOptions = new RepeatYearly(1, $currentSeriesDate->AddDays(400)->GetBegin());

        $repeatDaily = new RepeatDaily(1, $currentSeriesDate->AddDays(10)->GetBegin());

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithRepeatOptions($currentRepeatOptions);
        $builder->WithInstance($oldReservation);
        $builder->WithInstance($currentInstance);
        $builder->WithInstance($afterTodayButBeforeCurrent);
        $builder->WithInstance($afterCurrent);
        $builder->WithCurrentInstance($currentInstance);
        $series = $builder->Build();
        // updates
        $series->ApplyChangesTo(SeriesUpdateScope::FullSeries);
        $series->Repeats($repeatDaily);

        $instances = $series->Instances();

        $this->assertEquals(11, count($instances), "1 old, 1 current, 10 repeated dates");
        $this->assertTrue(in_array($currentInstance, $instances));

        $events = $series->GetEvents();

        $this->assertEquals(12, count($events), "2 removals, 10 adds");
        // remove all future events
        $instanceRemovedEvent1 = new InstanceRemovedEvent($afterTodayButBeforeCurrent, $series);
        $instanceRemovedEvent2 = new InstanceRemovedEvent($afterCurrent, $series);

        $this->assertTrue(
            in_array($instanceRemovedEvent1, $events),
            "missing ref {$afterTodayButBeforeCurrent->ReferenceNumber()}"
        );
        $this->assertTrue(in_array($instanceRemovedEvent2, $events), "missing ref {$afterCurrent->ReferenceNumber()}");

        // recreate all future events
        foreach ($instances as $instance) {
            if ($instance == $currentInstance) {
                continue;
            }

            $instanceAddedEvent = new InstanceAddedEvent($instance, $series);
            $this->assertTrue(in_array($instanceAddedEvent, $events), "missing ref num {$instance->ReferenceNumber()}");
        }
    }

    public function testWhenExtendingEndDateOfRepeatOptionsOnFullSeries()
    {
        $currentSeriesDate = new DateRange(Date::Now()->AddDays(1), Date::Now()->AddDays(1));
        $currentInstance = new TestReservation('current', $currentSeriesDate);
        $futureInstance = new TestReservation('future', $currentSeriesDate->AddDays(11));
        $repeatDaily = new RepeatDaily(1, $currentSeriesDate->AddDays(10)->GetBegin());

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithRepeatOptions($repeatDaily);
        $builder->WithInstance(new TestReservation('past', $currentSeriesDate->AddDays(-1)));
        $builder->WithInstance($currentInstance);
        $builder->WithInstance($futureInstance);
        $builder->WithCurrentInstance($currentInstance);

        $series = $builder->Build();
        $series->ApplyChangesTo(SeriesUpdateScope::FullSeries);
        $series->Repeats(new RepeatDaily(1, $currentSeriesDate->AddDays(20)->GetBegin()));

        $instances = $series->Instances();
        $this->assertEquals(22, count($instances), "1 past, 1 current, 20 future (including existing instance)");
        $this->assertTrue(in_array($currentInstance, $instances), "current should not have been altered");
        $this->assertTrue(in_array($futureInstance, $instances), "existing future should not have been altered");

        $events = $series->GetEvents();
        $this->assertEquals(19, count($events), "should have nothing other than new instance created events");
    }

    public function testWhenReducingEndDateOfRepeatOptionsOnFullSeries()
    {
        $currentSeriesDate = new DateRange(Date::Now()->AddDays(1), Date::Now()->AddDays(1));
        $currentInstance = new TestReservation('current', $currentSeriesDate);
        $futureInstance = new TestReservation('future', $currentSeriesDate->AddDays(20));
        $repeatDaily = new RepeatDaily(1, $currentSeriesDate->AddDays(10)->GetBegin());

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithRepeatOptions($repeatDaily);
        $builder->WithInstance(new TestReservation('past', $currentSeriesDate->AddDays(-1)));
        $builder->WithInstance($currentInstance);
        $builder->WithInstance($futureInstance);
        $builder->WithCurrentInstance($currentInstance);

        $series = $builder->Build();
        $series->ApplyChangesTo(SeriesUpdateScope::FullSeries);
        $series->Repeats(new RepeatDaily(1, $currentSeriesDate->AddDays(19)->GetBegin()));

        $instances = $series->Instances();
        $this->assertEquals(21, count($instances), "1 past, 1 current, 19 future (including existing instance)");
        $this->assertTrue(in_array($currentInstance, $instances), "current should not have been altered");
        $this->assertFalse(in_array($futureInstance, $instances), "existing future should not have been altered");

        $events = $series->GetEvents();
        $this->assertEquals(20, count($events), "19 created, 1 deleted");
        $this->assertTrue(in_array(new InstanceRemovedEvent($futureInstance, $series), $events));
    }

    public function testWhenApplyingSimpleUpdatesToFullSeries()
    {
        $currentResource = new FakeBookableResource(8);
        $newResource = new FakeBookableResource(10);

        $repeatOptions = new RepeatDaily(1, Date::Now());
        $dateRange = new TestDateRange();
        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithPrimaryResource($currentResource);
        $builder->WithRepeatOptions($repeatOptions);
        $builder->WithInstance(new TestReservation('123', $dateRange));
        $builder->WithCurrentInstance(new TestReservation('1', $dateRange->AddDays(5)));

        $series = $builder->Build();
        $series->ApplyChangesTo(SeriesUpdateScope::FullSeries);
        $series->Repeats($repeatOptions);
        $series->Update($series->UserId(), $newResource, 'new', 'new', new FakeUserSession());

        $events = $series->GetEvents();

        $this->assertEquals($newResource, $series->Resource());
        $this->assertEquals(2, count($series->Instances()));
        $this->assertEquals(2, count($events));
        $this->assertTrue(in_array(new ResourceRemovedEvent($currentResource, $series), $events));
        $this->assertTrue(in_array(new ResourceAddedEvent($newResource, ResourceLevel::Primary, $series), $events));
    }

    public function testWhenApplyingSimpleUpdatesToFullSeriesCurrentTimesCanBeEdited()
    {
        $this->fakeConfig->SetKey(
            ConfigKeys::RESERVATION_START_TIME_CONSTRAINT,
            ReservationStartTimeConstraint::CURRENT
        );
        $timezone = 'UTC';
        $currentResource = new FakeBookableResource(8);
        $newResource = new FakeBookableResource(10);

        $dateRange = DateRange::Create('2010-01-01 00:00', '2010-01-01 5:00', $timezone);
        Date::_SetNow(Date::Parse('2010-01-01 2:00', $timezone));

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithPrimaryResource($currentResource);
        $builder->WithCurrentInstance(new TestReservation('1', $dateRange));

        $series = $builder->Build();
        $series->ApplyChangesTo(SeriesUpdateScope::FullSeries);

        $series->Update($series->UserId(), $newResource, 'new', 'new', new FakeUserSession());

        $this->assertEquals($newResource, $series->Resource());
        $this->assertEquals(1, count($series->Instances()));
    }

    public function testWhenApplyingSimpleUpdatesToFullSeriesAndThereIsNoStartTimeConstraint()
    {
        $this->fakeConfig->SetKey(
            ConfigKeys::RESERVATION_START_TIME_CONSTRAINT,
            ReservationStartTimeConstraint::NONE
        );
        $timezone = 'UTC';
        $currentResource = new FakeBookableResource(8);
        $newResource = new FakeBookableResource(10);

        $dateRange = DateRange::Create('2010-01-01 00:00', '2010-01-01 5:00', $timezone);
        Date::_SetNow(Date::Parse('2010-01-05 2:00', $timezone));

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithPrimaryResource($currentResource);
        $builder->WithCurrentInstance(new TestReservation('1', $dateRange));

        $series = $builder->Build();
        $series->ApplyChangesTo(SeriesUpdateScope::FullSeries);

        $series->Update($series->UserId(), $newResource, 'new', 'new', new FakeUserSession());

        $this->assertEquals($newResource, $series->Resource());
        $this->assertEquals(1, count($series->Instances()));
    }

    public function testChangingTimeForFullSeriesUpdatesAllInstanceTimes()
    {
        $repeatOptions = new RepeatDaily(1, Date::Now());
        $dateRange = DateRange::Create('2015-01-01 08:30', '2015-01-01 09:30', 'UTC');
        $instance1Date = $dateRange->AddDays(5);
        $instance2Date = $dateRange->AddDays(8);

        $instance1 = new TestReservation('123', $instance1Date, 100);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithBookedBy(new FakeUserSession(true));
        $builder->WithRepeatOptions($repeatOptions);
        $builder->WithInstance($instance1);
        $builder->WithInstance(new TestReservation('223', $instance2Date, 101));
        $builder->WithCurrentInstance(new TestReservation('1', $dateRange, 102));

        $series = $builder->Build();
        $series->ApplyChangesTo(SeriesUpdateScope::FullSeries);
        $newDuration = DateRange::Create('2015-01-01 09:30:00', '2015-01-02 00:00:00', 'UTC');
        $series->UpdateDuration($newDuration);

        $this->assertEquals($newDuration, $series->CurrentInstance()->Duration());

        $newInstance1Start = Date::Parse('2015-01-06 09:30:00', 'UTC');
        $newInstance1End = Date::Parse('2015-01-07 00:00:00', 'UTC');

        $newInstance2Start = Date::Parse('2015-01-09 09:30:00', 'UTC');
        $newInstance2End = Date::Parse('2015-01-10 00:00:00', 'UTC');

        //		$this->assertEquals(new DateRange($newInstance1Start, $newInstance1End), $series->GetInstance($newInstance1Start)->Duration());
        //		$this->assertEquals(new DateRange($newInstance2Start, $newInstance2End), $series->GetInstance($newInstance2Start)->Duration());

        $events = $series->GetEvents();
        $this->assertTrue(in_array(new InstanceUpdatedEvent($instance1, $series), $events));
    }

    public function testUpdateSingleInstanceToAnEarlierTime()
    {
        $now = Date::Now();

        $newStart = Date::Parse('2010-01-01 04:00:00', 'UTC');
        $start = Date::Parse('2010-01-01 05:00:00', 'UTC');
        $end = Date::Parse('2010-01-01 07:00:00', 'UTC');

        $future1 = new TestReservation('1', new DateRange($now->AddDays(5), $now->AddDays(5)));
        $future2 = new TestReservation('2', new DateRange($now->AddDays(6), $now->AddDays(6)));
        $current = new TestReservation('3', new DateRange($start, $end));

        $existing = new ExistingReservationSeries();
        $existing->WithInstance($future1);
        $existing->WithInstance($future2);
        $existing->WithCurrentInstance($current);

        $existing->ApplyChangesTo(SeriesUpdateScope::ThisInstance);
        $existing->UpdateDuration(new DateRange($newStart, $end));

        $newCurrent = $existing->CurrentInstance();

        $this->assertTrue($newCurrent->StartDate()->Equals($newStart));
        $this->assertTrue($newCurrent->EndDate()->Equals($end));
    }

    public function testWhenChangingTimeToFutureInstances()
    {
        $currentSeriesDate = new DateRange(Date::Now()->AddDays(1), Date::Now()->AddDays(2));

        $oldDates = $currentSeriesDate->AddDays(-4);
        $oldReservation = new TestReservation('old', $oldDates, 1);

        $currentInstance = new TestReservation('current', $currentSeriesDate, 2);

        $futureDates1 = $currentSeriesDate->AddDays(1);
        $futureReservation1 = new TestReservation('new1', $futureDates1, 3);

        $futureDates2 = $currentSeriesDate->AddDays(10);
        $futureReservation2 = new TestReservation('new2', $futureDates2, 4);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($currentInstance);
        $builder->WithInstance($oldReservation);
        $builder->WithInstance($futureReservation1);
        $builder->WithInstance($futureReservation2);
        $series = $builder->Build();
        // updates
        $series->ApplyChangesTo(SeriesUpdateScope::FutureInstances);
        $series->UpdateDuration($currentSeriesDate->AddDays(1));

        //$instances = $series->Instances();

        $events = $series->GetEvents();

        //$this->assertEquals(1, count($events));
        $this->assertTrue(in_array(new InstanceUpdatedEvent($currentInstance, $series), $events));
        $this->assertTrue(in_array(new InstanceUpdatedEvent($futureReservation1, $series), $events));
        $this->assertTrue(in_array(new InstanceUpdatedEvent($futureReservation2, $series), $events));
    }

    public function testChangingParticipantsAddsNewRemovesOldAndKeepsOverlap()
    {
        $existingParticipants = [1, 2, 3, 4];
        $newParticipants = [1, 5, 4, 6];

        $reservation = new TestReservation();
        $reservation->WithParticipants($existingParticipants);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithInstance($reservation);
        $series = $builder->Build();

        $series->ChangeParticipants($newParticipants);

        $this->assertEquals(2, count($reservation->AddedParticipants()));
        $this->assertEquals([5, 6], $reservation->AddedParticipants());
        $this->assertEquals([2, 3], $reservation->RemovedParticipants());
        $this->assertEquals([1, 4], $reservation->UnchangedParticipants());
    }

    public function testChangingInviteesAddsNewRemovesOldAndKeepsOverlap()
    {
        $existingInvitees = [1, 2, 3, 4];
        $newInvitees = [1, 5, 4, 6];

        $reservation = new TestReservation();
        $reservation->WithInvitees($existingInvitees);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithInstance($reservation);
        $series = $builder->Build();

        $series->ChangeInvitees($newInvitees);

        $this->assertEquals(2, count($reservation->AddedInvitees()));
        $this->assertEquals([5, 6], $reservation->AddedInvitees());
        $this->assertEquals([2, 3], $reservation->RemovedInvitees());
        $this->assertEquals([1, 4], $reservation->UnchangedInvitees());
    }

    public function testDuplicateEventsAreIgnored()
    {
        $reservation = new TestReservation();
        $reservation->WithInvitees([1]);
        $reservation->SetReservationId(100);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithInstance($reservation);
        $series = $builder->Build();

        $series->ChangeInvitees([2]);
        $series->ChangeInvitees([3]);

        $events = $series->GetEvents();
        $unique = array_unique($events);

        $this->assertEquals(count($unique), count($events));
    }

    public function testJoinsForEachInstance()
    {
        $userId = 1;

        $r1 = new TestReservation();
        $r1->WithInvitees([$userId]);
        $r1->SetReservationId(100);

        $r2 = new TestReservation();
        $r2->WithInvitees([$userId]);
        $r2->SetReservationId(100);

        $r3 = new TestReservation();
        $r3->WithParticipants([$userId]);
        $r3->SetReservationId(100);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($r1);
        $builder->WithInstance($r2);
        $builder->WithInstance($r3);

        $series = $builder->Build();
        $series->AllowParticipation(true);

        $series->JoinReservationSeries($userId);

        $events = $series->GetEvents();

        $this->assertContains($userId, $r1->AddedParticipants());
        $this->assertContains($userId, $r1->RemovedInvitees());

        $this->assertContains($userId, $r2->AddedParticipants());
        $this->assertContains($userId, $r2->RemovedInvitees());

        $this->assertTrue(in_array(new InstanceUpdatedEvent($r1, $series), $events));
        $this->assertTrue(in_array(new InstanceUpdatedEvent($r2, $series), $events));
        $this->assertFalse(in_array(new InstanceUpdatedEvent($r3, $series), $events), 'already participating');
    }

    public function testJoinsCurrentInstance()
    {
        $userId = 1;

        $r1 = new TestReservation();
        $r1->WithInvitees([$userId]);
        $r1->SetReservationId(100);

        $r2 = new TestReservation();
        $r2->WithInvitees([$userId]);
        $r2->SetReservationId(100);

        $r3 = new TestReservation();
        $r3->WithParticipants([$userId]);
        $r3->SetReservationId(100);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($r1);
        $builder->WithInstance($r2);
        $builder->WithInstance($r3);

        $series = $builder->Build();
        $series->AllowParticipation(true);

        $series->JoinReservation($userId);

        $events = $series->GetEvents();

        $this->assertContains($userId, $r1->AddedParticipants());
        $this->assertContains($userId, $r1->RemovedInvitees());

        $this->assertTrue(in_array(new InstanceUpdatedEvent($r1, $series), $events));
        $this->assertFalse(in_array(new InstanceUpdatedEvent($r2, $series), $events));
        $this->assertFalse(in_array(new InstanceUpdatedEvent($r3, $series), $events));
    }

    public function testAcceptsInvitesForEachInstance()
    {
        $userId = 1;

        $r1 = new TestReservation();
        $r1->WithInvitees([$userId]);
        $r1->SetReservationId(100);

        $r2 = new TestReservation();
        $r2->WithInvitees([$userId]);
        $r2->SetReservationId(100);

        $r3 = new TestReservation();
        $r3->WithInvitees([10]);
        $r3->SetReservationId(100);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithInstance($r1);
        $builder->WithInstance($r2);
        $builder->WithInstance($r3);

        $series = $builder->Build();

        $series->AcceptInvitation($userId);

        $events = $series->GetEvents();

        $this->assertContains($userId, $r1->AddedParticipants());
        $this->assertContains($userId, $r1->RemovedInvitees());

        $this->assertContains($userId, $r2->AddedParticipants());
        $this->assertContains($userId, $r2->RemovedInvitees());

        $this->assertTrue(in_array(new InstanceUpdatedEvent($r1, $series), $events));
        $this->assertTrue(in_array(new InstanceUpdatedEvent($r2, $series), $events));
        $this->assertFalse(in_array(new InstanceUpdatedEvent($r3, $series), $events));
    }

    public function testDeclinesInvitesForEachInstance()
    {
        $userId = 1;

        $r1 = new TestReservation();
        $r1->WithInvitees([$userId]);
        $r1->SetReservationId(100);

        $r2 = new TestReservation();
        $r2->WithInvitees([$userId]);
        $r2->SetReservationId(100);

        $r3 = new TestReservation();
        $r3->WithInvitees([10]);
        $r3->SetReservationId(100);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithInstance($r1);
        $builder->WithInstance($r2);
        $builder->WithInstance($r3);

        $series = $builder->Build();

        $series->DeclineInvitation($userId);

        $events = $series->GetEvents();

        $this->assertContains($userId, $r1->RemovedInvitees());
        $this->assertContains($userId, $r2->RemovedInvitees());

        $this->assertTrue(in_array(new InstanceUpdatedEvent($r1, $series), $events));
        $this->assertTrue(in_array(new InstanceUpdatedEvent($r2, $series), $events));
        $this->assertFalse(in_array(new InstanceUpdatedEvent($r3, $series), $events));
    }

    public function testAcceptsAsGuestInvitesForEachInstance()
    {
        $invitedEmail = 'invited@email.com';

        $r1 = new TestReservation();
        $r1->WithInvitedGuest($invitedEmail);
        $r1->SetReservationId(100);

        $r2 = new TestReservation();
        $r2->WithInvitedGuest($invitedEmail);
        $r2->SetReservationId(100);

        $r3 = new TestReservation();
        $r3->WithInvitedGuest('someone@else.com');
        $r3->SetReservationId(100);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithInstance($r1);
        $builder->WithInstance($r2);
        $builder->WithInstance($r3);

        $series = $builder->Build();

        $series->AcceptGuestInvitation($invitedEmail);

        $events = $series->GetEvents();

        $this->assertContains($invitedEmail, $r1->ParticipatingGuests());
        $this->assertContains($invitedEmail, $r2->ParticipatingGuests());

        $this->assertTrue(in_array(new InstanceUpdatedEvent($r1, $series), $events));
        $this->assertTrue(in_array(new InstanceUpdatedEvent($r2, $series), $events));
        $this->assertFalse(in_array(new InstanceUpdatedEvent($r3, $series), $events));
    }

    public function testAcceptsAsUserInvitesForEachInstance()
    {
        $invitedEmail = 'invited@email.com';
        $user = new FakeUser(1);

        $r1 = new TestReservation();
        $r1->WithInvitedGuest($invitedEmail);
        $r1->SetReservationId(100);

        $r2 = new TestReservation();
        $r2->WithInvitedGuest($invitedEmail);
        $r2->SetReservationId(100);

        $r3 = new TestReservation();
        $r3->WithInvitedGuest('someone@else.com');
        $r3->SetReservationId(100);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithInstance($r1);
        $builder->WithInstance($r2);
        $builder->WithInstance($r3);

        $series = $builder->Build();

        $series->AcceptGuestAsUserInvitation($invitedEmail, $user);

        $events = $series->GetEvents();

        $this->assertContains($invitedEmail, $r1->RemovedInvitedGuests());
        $this->assertContains($invitedEmail, $r2->RemovedInvitedGuests());
        $this->assertContains($user->Id(), $r1->AddedParticipants());
        $this->assertContains($user->Id(), $r2->AddedParticipants());

        $this->assertTrue(in_array(new InstanceUpdatedEvent($r1, $series), $events));
        $this->assertTrue(in_array(new InstanceUpdatedEvent($r2, $series), $events));
        $this->assertFalse(!in_array(new InstanceUpdatedEvent($r3, $series), $events));
    }

    public function testDeclinesAsGuestInvitesForEachInstance()
    {
        $invitedEmail = 'invited@email.com';

        $r1 = new TestReservation();
        $r1->WithInvitedGuest($invitedEmail);
        $r1->SetReservationId(100);

        $r2 = new TestReservation();
        $r2->WithInvitedGuest($invitedEmail);
        $r2->SetReservationId(100);

        $r3 = new TestReservation();
        $r2->WithInvitedGuest('someone@else.com');
        $r3->SetReservationId(100);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithInstance($r1);
        $builder->WithInstance($r2);
        $builder->WithInstance($r3);

        $series = $builder->Build();

        $series->DeclineGuestInvitation($invitedEmail);

        $events = $series->GetEvents();

        $this->assertContains($invitedEmail, $r1->RemovedInvitedGuests());
        $this->assertContains($invitedEmail, $r2->RemovedInvitedGuests());

        $this->assertTrue(in_array(new InstanceUpdatedEvent($r1, $series), $events));
        $this->assertTrue(in_array(new InstanceUpdatedEvent($r2, $series), $events));
        $this->assertFalse(in_array(new InstanceUpdatedEvent($r3, $series), $events));
    }

    public function testRemovesParticipationFromCurrentInstance()
    {
        $userId = 1;

        $r1 = new TestReservation();
        $r1->WithParticipant($userId);
        $r1->SetReservationId(100);

        $r2 = new TestReservation();
        $r2->WithParticipant($userId);
        $r2->SetReservationId(100);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithCurrentInstance($r1);
        $builder->WithInstance($r2);

        $series = $builder->Build();

        $series->CancelInstanceParticipation($userId);

        $events = $series->GetEvents();

        $this->assertContains($userId, $r1->RemovedParticipants());
        $this->assertEmpty($r2->RemovedInvitees());

        $this->assertTrue(in_array(new InstanceUpdatedEvent($r1, $series), $events));
        $this->assertFalse(in_array(new InstanceUpdatedEvent($r2, $series), $events));
    }

    public function testRemovesParticipationFromAllInstances()
    {
        $userId = 1;

        $r1 = new TestReservation();
        $r1->WithParticipant($userId);
        $r1->SetReservationId(100);

        $r2 = new TestReservation();
        $r2->WithParticipant($userId);
        $r2->SetReservationId(101);

        $r3 = new TestReservation();
        $r3->WithParticipant(89);
        $r3->SetReservationId(102);

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithInstance($r1);
        $builder->WithInstance($r2);
        $builder->WithInstance($r3);

        $series = $builder->Build();

        $series->CancelAllParticipation($userId);

        $events = $series->GetEvents();

        $this->assertContains($userId, $r1->RemovedParticipants());
        $this->assertContains($userId, $r2->RemovedParticipants());
        $this->assertEmpty($r3->RemovedInvitees());

        $this->assertTrue(in_array(new InstanceUpdatedEvent($r1, $series), $events));
        $this->assertTrue(in_array(new InstanceUpdatedEvent($r2, $series), $events));
        $this->assertFalse(in_array(new InstanceUpdatedEvent($r3, $series), $events));
    }

    public function testChangesResources()
    {
        $builder = new ExistingReservationSeriesBuilder();

        $unchanged = new FakeBookableResource(1);
        $added = new FakeBookableResource(2);
        $removed = new FakeBookableResource(3);
        $primaryResource = new FakeBookableResource(4);
        $becomesPrimary = new FakeBookableResource(5);

        $series = $builder->Build();
        $series->WithPrimaryResource($primaryResource);
        $series->WithResource($unchanged);
        $series->WithResource($removed);
        $series->WithResource($becomesPrimary);

        $series->Update($series->UserId(), $becomesPrimary, $series->Title(), $series->Description(), $this->fakeUser);
        $series->ChangeResources([$unchanged, $added]);

        $events = $series->GetEvents();

        $removeAdditionalEvent = new ResourceRemovedEvent($becomesPrimary, $series);
        $addAdditionalEvent = new ResourceAddedEvent($becomesPrimary, ResourceLevel::Primary, $series);

        $this->assertTrue(in_array(new ResourceRemovedEvent($removed, $series), $events));
        $this->assertTrue(in_array(new ResourceAddedEvent($added, ResourceLevel::Additional, $series), $events));
        $this->assertTrue(in_array(new ResourceRemovedEvent($primaryResource, $series), $events));
        $this->assertTrue(in_array($removeAdditionalEvent, $events));
        $this->assertTrue(in_array($addAdditionalEvent, $events));

        $removeAdditionalIndex = array_search($removeAdditionalEvent, $events);
        $addAdditionalIndex = array_search($addAdditionalEvent, $events);

        $this->assertTrue(
            $removeAdditionalIndex < $addAdditionalIndex,
            "should remove existing relationship before adding new one"
        );
    }

    public function testApproveUpdatesStateAndFiresEvent()
    {
        $series = new ExistingReservationSeries();
        $series->WithStatus(ReservationStatus::Pending);

        $series->Approve($this->fakeUser);
        $events = $series->GetEvents();

        $this->assertEquals(ReservationStatus::Created, $series->StatusId());
        $this->assertTrue(in_array(new SeriesApprovedEvent($series), $events));
    }

    public function testChangeAccessories()
    {
        $accessory1 = new ReservationAccessory(1, 100);
        $accessory2 = new ReservationAccessory(2, 22);
        $accessory3 = new ReservationAccessory(3, 3);
        $accessory4 = new ReservationAccessory(4, 444);

        $accessory1WithDifferentQuantity = new ReservationAccessory(1, 99);

        $series = new ExistingReservationSeries();

        $series->WithAccessory($accessory1);
        $series->WithAccessory($accessory2);
        $series->WithAccessory($accessory3);

        $accessories = [$accessory1WithDifferentQuantity, $accessory2, $accessory4];
        $series->ChangeAccessories($accessories);

        $this->assertEquals($accessories, $series->Accessories());

        $events = $series->GetEvents();

        $remove1 = new AccessoryRemovedEvent($accessory1, $series);
        $remove3 = new AccessoryRemovedEvent($accessory3, $series);
        $add1 = new AccessoryAddedEvent($accessory1WithDifferentQuantity, $series);
        $add4 = new AccessoryAddedEvent($accessory4, $series);

        $this->assertTrue(in_array($remove1, $events));
        $this->assertTrue(in_array($add1, $events));
        $this->assertTrue(in_array($add4, $events));
        $this->assertTrue(in_array($remove3, $events));

        $removeIndex = array_search($remove1, $events);
        $addIndex = array_search($add1, $events);

        $this->assertTrue($removeIndex < $addIndex, "need to remove before adding to avoid key conflicts");
    }

    public function testChangeOwner()
    {
        $oldOwnerId = 100;
        $newUserId = 200;

        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->Build();
        $series->WithOwner($oldOwnerId);

        $series->Update($newUserId, $series->Resource(), '', '', $this->fakeUser);
        $events = $series->GetEvents();

        $this->assertTrue(in_array(new OwnerChangedEvent($series, $oldOwnerId, $newUserId), $events));
    }

    public function testReplacesReminders()
    {
        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->Build();
        $series->WithStartReminder(new ReservationReminder(100, ReservationReminderInterval::Days));
        $series->WithEndReminder(new ReservationReminder(15, ReservationReminderInterval::Minutes));

        $start = new ReservationReminder(1, ReservationReminderInterval::Minutes);
        $end = new ReservationReminder(1, ReservationReminderInterval::Days);
        $series->AddStartReminder($start);
        $series->AddEndReminder($end);

        $events = $series->GetEvents();

        $this->assertTrue(in_array(
            new ReminderAddedEvent($series, $start->MinutesPrior(), ReservationReminderType::Start),
            $events
        ));
        $this->assertTrue(in_array(
            new ReminderAddedEvent($series, $end->MinutesPrior(), ReservationReminderType::End),
            $events
        ));
    }

    public function testDoesNothingIfReminderDoesNotChange()
    {
        $start = new ReservationReminder(1, ReservationReminderInterval::Minutes);
        $end = new ReservationReminder(1, ReservationReminderInterval::Days);
        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->Build();
        $series->WithStartReminder($start);
        $series->WithEndReminder($end);

        $series->AddStartReminder($start);
        $series->AddEndReminder($end);

        $events = $series->GetEvents();

        $this->assertEquals(0, count($events));
    }

    public function testRemovesReminders()
    {
        $start = new ReservationReminder(1, ReservationReminderInterval::Minutes);
        $end = new ReservationReminder(1, ReservationReminderInterval::Days);
        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->Build();
        $series->WithStartReminder($start);
        $series->WithEndReminder($end);

        $series->RemoveStartReminder();
        $series->RemoveEndReminder();

        $events = $series->GetEvents();

        $this->assertTrue(in_array(
            new ReminderRemovedEvent($series, ReservationReminderType::Start),
            $events
        ));
        $this->assertTrue(in_array(
            new ReminderRemovedEvent($series, ReservationReminderType::End),
            $events
        ));
    }

    public function testWhenUpdatingReservationDatesPastCheckedInTime_RequireCheckInAgain()
    {
        $reservation = new TestReservation(null, new DateRange(Date::Now(), Date::Now()));
        $reservation->WithCheckin(Date::Now(), new NullDate());

        $series = new ExistingReservationSeries();
        $series->WithCurrentInstance($reservation);

        $series->UpdateDuration(new DateRange(Date::Now()->AddDays(1), Date::Now()->AddDays(2)));

        $this->assertFalse($series->CurrentInstance()->IsCheckedIn());
    }

    public function testWhenCheckingOut_EndDateIsUpdated_AndPreviousValueCaptured()
    {
        $oldEnd = Date::Now()->AddDays(1);
        $reservation = new TestReservation(null, new DateRange(Date::Now(), $oldEnd));
        $reservation->WithCheckin(Date::Now(), new NullDate());

        $series = new ExistingReservationSeries();
        $series->WithCurrentInstance($reservation);

        $series->Checkout($this->fakeUser);

        $this->assertEquals($oldEnd, $reservation->PreviousEndDate());
        $this->assertEquals(Date::Now(), $reservation->EndDate());
    }

    public function testWhenRemovingInstanceOfUpdatedRepeatingSeries()
    {
        $tz = 'UTC';
        $this->fakeUser->Timezone = $tz;
        Date::_SetNow(Date::Parse('2017-08-01', $tz));
        $reservations = [];
        $now = Date::Parse('2017-08-10', $tz);
        for ($i = 0; $i < 5; $i++) {
            $date = new DateRange($now->AddDays($i)->SetTime(Time::Parse('13:00')), $now->AddDays($i)->SetTime(Time::Parse('14:00')));
            $reservations[] = new TestReservation($i, $date, $i);
        }
        $series = new ExistingReservationSeries();
        for ($i = 1; $i < 5; $i++) {
            $series->WithInstance($reservations[$i]);
        }

        $series->WithCurrentInstance($reservations[0]);

        $series->UpdateBookedBy($this->fakeUser);
        $series->ApplyChangesTo(SeriesUpdateScope::FullSeries);
        $series->Repeats(new RepeatDaily(1, Date::Parse('2017-08-16', $tz)));

        $series->RemoveInstance($reservations[2]);

        $instances = $series->Instances();

        $this->assertEquals(6, count($instances));
    }

    public function testChangingRepeatOfAPastEvent()
    {
        $admin = $this->fakeUser;
        $admin->IsAdmin = true;
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_START_TIME_CONSTRAINT, 'future');
        $tz = 'America/Chicago';
        Date::_SetNow(Date::Parse('2018-10-01 22:00', $tz));

        $builder = new ExistingReservationSeriesBuilder();
        $originalRange = DateRange::Create('2018-10-01 12:00', '2018-10-01 13:00', $tz);
        $reservation = $builder
            ->WithCurrentInstance(new TestReservation(1, $originalRange))
            ->WithRepeatOptions(new RepeatWeekly(1, Date::Parse('2018-10-29', $tz), [DayOfWeek::MONDAY]))
            ->WithInstance(new TestReservation(2, $originalRange->AddDays(7)))
            ->WithInstance(new TestReservation(3, $originalRange->AddDays(14)))
            ->WithInstance(new TestReservation(4, $originalRange->AddDays(21)))
            ->WithInstance(new TestReservation(5, $originalRange->AddDays(28)))
            ->WithPrimaryResource(new FakeBookableResource(1))
            ->Build();

        $reservation->UpdateBookedBy($admin);
        $reservation->UpdateDuration($originalRange->AddDays(1));
        $reservation->Repeats(new RepeatWeekly(1, Date::Parse('2018-10-30', $tz), [DayOfWeek::TUESDAY]));

        $instances = $reservation->Instances();

        $events = $reservation->GetEvents();

        $removedReferenceNumbers = [];
        foreach ($events as $e) {
            if (is_a($e, 'InstanceRemovedEvent')) {
                /** @var InstanceRemovedEvent $e */
                $removedReferenceNumbers[] = $e->Instance()->ReferenceNumber();
            }
        }

        $this->assertFalse(in_array(1, $removedReferenceNumbers));
    }

    public function testChangingTimeOfExistingCustomRepeat()
    {
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_START_TIME_CONSTRAINT, 'none');

        $admin = $this->fakeUser;
        $admin->IsAdmin = false;
        $tz = 'America/Chicago';
        Date::_SetNow(Date::Parse('2018-10-01 22:00', $tz));

        $builder = new ExistingReservationSeriesBuilder();
        $originalRange = DateRange::Create('2018-10-01 12:00', '2018-10-01 13:00', $tz);
        $date1 = $originalRange->AddDays(1);
        $date2 = $originalRange->AddDays(5);
        $date3 = $originalRange->AddDays(15);
        $repeatOptions = new RepeatCustom([$date1->GetBegin(), $date2->GetBegin(), $date3->GetBegin()]);
        $reservation = $builder
            ->WithCurrentInstance(new TestReservation(1, $originalRange))
            ->WithRepeatOptions($repeatOptions)
            ->WithInstance(new TestReservation(2, $date1))
            ->WithInstance(new TestReservation(3, $date2))
            ->WithInstance(new TestReservation(4, $date3))
            ->WithPrimaryResource(new FakeBookableResource(1))
            ->Build();

        $reservation->UpdateDuration($originalRange->AddDays(1));
        $reservation->UpdateBookedBy($admin);
        $reservation->Repeats($repeatOptions);

        $instances = $reservation->SortedInstances();

        $events = $reservation->GetEvents();

        $removedReferenceNumbers = [];
        $addedReferenceNumbers = [];
        $updatedReferenceNumbers = [];
        foreach ($events as $e) {
            if (is_a($e, 'InstanceRemovedEvent')) {
                /** @var InstanceRemovedEvent $e */
                $removedReferenceNumbers[] = $e->Instance()->ReferenceNumber();
            }

            if (is_a($e, 'InstanceAddedEvent')) {
                /** @var InstanceAddedEvent $e */
                $addedReferenceNumbers[] = $e->Instance()->ReferenceNumber();
            }

            if (is_a($e, 'InstanceUpdatedEvent')) {
                /** @var InstanceAddedEvent $e */
                $updatedReferenceNumbers[] = $e->Instance()->ReferenceNumber();
            }
        }

        $this->assertEquals(4, count($instances));
        $this->assertEquals(0, count($removedReferenceNumbers));
        $this->assertEquals(0, count($addedReferenceNumbers));
        sort($updatedReferenceNumbers);
        $this->assertEquals([1, 2, 3, 4], $updatedReferenceNumbers);
        $this->assertEquals($instances[1]->StartDate(), $date1->GetBegin()->AddDays(1));
        $this->assertEquals($instances[1]->EndDate(), $date1->GetBegin()->AddDays(1)->SetTimeString("13:00"));
    }

    public function testAddingDateToExistingCustomRepeat()
    {
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_START_TIME_CONSTRAINT, 'none');

        $admin = $this->fakeUser;
        $admin->IsAdmin = false;
        $tz = 'America/Chicago';
        Date::_SetNow(Date::Parse('2018-10-01 22:00', $tz));

        $builder = new ExistingReservationSeriesBuilder();
        $originalRange = DateRange::Create('2018-10-01 12:00', '2018-10-01 13:00', $tz);
        $date1 = $originalRange->AddDays(1);
        $date2 = $originalRange->AddDays(5);
        $date3 = $originalRange->AddDays(15);
        $newDate = $originalRange->AddDays(10);
        $reservation = $builder
            ->WithCurrentInstance(new TestReservation(1, $originalRange))
            ->WithRepeatOptions(new RepeatCustom([$date1->GetBegin(), $date2->GetBegin(), $date3->GetBegin()]))
            ->WithInstance(new TestReservation(2, $date1))
            ->WithInstance(new TestReservation(3, $date2))
            ->WithInstance(new TestReservation(4, $date3))
            ->WithPrimaryResource(new FakeBookableResource(1))
            ->Build();

        $reservation->UpdateBookedBy($admin);
        $reservation->Repeats(new RepeatCustom([$date1->GetBegin(), $date2->GetBegin(), $date3->GetBegin(), $newDate->GetBegin()]));

        $instances = $reservation->Instances();

        $events = $reservation->GetEvents();

        $removedReferenceNumbers = [];
        $addedReferenceNumbers = [];
        foreach ($events as $e) {
            if (is_a($e, 'InstanceRemovedEvent')) {
                /** @var InstanceRemovedEvent $e */
                $removedReferenceNumbers[] = $e->Instance()->ReferenceNumber();
            }

            if (is_a($e, 'InstanceAddedEvent')) {
                /** @var InstanceAddedEvent $e */
                $addedReferenceNumbers[] = $e->Instance()->ReferenceNumber();
            }
        }

        $this->assertEquals(5, count($instances));
        $this->assertEquals(3, count($removedReferenceNumbers));
        $this->assertEquals(4, count($addedReferenceNumbers));
    }

    public function testRemovingDateFromExistingCustomRepeat()
    {
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_START_TIME_CONSTRAINT, 'future');

        $admin = $this->fakeUser;
        $admin->IsAdmin = false;
        $tz = 'America/Chicago';
        Date::_SetNow(Date::Parse('2018-10-01 22:00', $tz));

        $builder = new ExistingReservationSeriesBuilder();
        $originalRange = DateRange::Create('2018-10-01 12:00', '2018-10-01 13:00', $tz);
        $date1 = $originalRange->AddDays(1);
        $date2 = $originalRange->AddDays(5);
        $date3 = $originalRange->AddDays(15);
        $reservation = $builder
            ->WithCurrentInstance(new TestReservation(1, $originalRange))
            ->WithRepeatOptions(new RepeatCustom([$date1->GetBegin(), $date2->GetBegin(), $date3->GetBegin()]))
            ->WithInstance(new TestReservation(2, $date1))
            ->WithInstance(new TestReservation(3, $date2))
            ->WithInstance(new TestReservation(4, $date3))
            ->WithPrimaryResource(new FakeBookableResource(1))
            ->Build();

        $reservation->UpdateBookedBy($admin);
        $reservation->Repeats(new RepeatCustom([$date1->GetBegin(), $date3->GetBegin()]));

        $instances = $reservation->Instances();

        $events = $reservation->GetEvents();

        $removedReferenceNumbers = [];
        $addedReferenceNumbers = [];
        foreach ($events as $e) {
            if (is_a($e, 'InstanceRemovedEvent')) {
                /** @var InstanceRemovedEvent $e */
                $removedReferenceNumbers[] = $e->Instance()->ReferenceNumber();
            }

            if (is_a($e, 'InstanceAddedEvent')) {
                /** @var InstanceAddedEvent $e */
                $addedReferenceNumbers[] = $e->Instance()->ReferenceNumber();
            }
        }

        $this->assertEquals(2, count($instances), "the original/current is in the past");
        $this->assertEquals(3, count($removedReferenceNumbers));
        $this->assertEquals(2, count($addedReferenceNumbers));
        sort($removedReferenceNumbers);
        $this->assertEquals([2, 3, 4], $removedReferenceNumbers);
    }
}
