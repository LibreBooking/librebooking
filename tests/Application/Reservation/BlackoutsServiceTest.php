<?php

use PHPUnit\Framework\MockObject\MockObject;

require_once(ROOT_DIR . 'lib/Application/Reservation/ManageBlackoutsService.php');

class BlackoutsServiceTest extends TestBase
{
    /**
     * @var ManageBlackoutsService
     */
    private $service;

    /**
     * @var IReservationViewRepository|MockObject
     */
    private $reservationViewRepository;

    /**
     * @var IUserRepository|MockObject
     */
    private $userRepository;

    /**
     * @var IReservationConflictResolution|MockObject
     */
    private $conflictHandler;

    /**
     * @var FakeBlackoutRepository
     */
    private $blackoutRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->reservationViewRepository = $this->createMock('IReservationViewRepository');
        $this->conflictHandler = $this->createMock('IReservationConflictResolution');
        $this->blackoutRepository = new FakeBlackoutRepository();
        $this->userRepository = $this->createMock('IUserRepository');

        $this->service = new ManageBlackoutsService($this->reservationViewRepository, $this->blackoutRepository, $this->userRepository);
    }

    public function testCreatesBlackoutForEachResourceWhenNoConflicts()
    {
        $userId = $this->fakeUser->UserId;
        $start = Date::Parse('2011-01-01 01:01:01');
        $end = Date::Parse('2011-02-02 02:02:02');
        $date = new DateRange($start, $end);
        $resourceIds = [1, 2, 3];
        $title = 'title';

        $blackoutBefore = new TestBlackoutItemView(1, Date::Parse('2010-01-01'), $start, 3);
        $blackoutAfter = new TestBlackoutItemView(2, $end, Date::Parse('2012-01-01'), 1);
        $blackoutDuring = new TestBlackoutItemView(3, $start, $end, 4);
        $this->reservationViewRepository->expects($this->once())
                                        ->method('GetBlackoutsWithin')
                                        ->with($this->equalTo($date))
                                        ->willReturn([$blackoutBefore, $blackoutAfter, $blackoutDuring]);

        $reservationBefore = new TestReservationItemView(1, Date::Parse('2010-01-01'), $start, 1);
        $reservationAfter = new TestReservationItemView(2, $end, Date::Parse('2012-01-01'), 2);

        $this->reservationViewRepository->expects($this->once())
                                        ->method('GetReservations')
                                        ->with($this->equalTo($start), $this->equalTo($end))
                                        ->willReturn([$reservationBefore, $reservationAfter]);

        $series = BlackoutSeries::Create($userId, $title, $date);
        $series->AddResourceId($resourceIds[0]);
        $series->AddResourceId($resourceIds[1]);
        $series->AddResourceId($resourceIds[2]);

        $result = $this->service->Add($date, $resourceIds, $title, $this->conflictHandler, new RepeatNone());

        $this->assertTrue($result->WasSuccessful());
        $series->_ResetBlackoutIteration();
        $this->blackoutRepository->_Added->_ResetBlackoutIteration();
        $this->assertEquals($series, $this->blackoutRepository->_Added);
    }

    public function testDoesNotAddAnyBlackoutsIfThereAreConflictingBlackoutTimes()
    {
        $start = Date::Parse('2011-01-01 01:01:01');
        $end = Date::Parse('2011-02-02 02:02:02');
        $date = new DateRange($start, $end);
        $resourceIds = [2, 3];
        $title = 'title';

        $blackoutDuring = new TestBlackoutItemView(1, $start, $end, 3);
        $this->reservationViewRepository->expects($this->atLeastOnce())
                                        ->method('GetBlackoutsWithin')
                                        ->with($this->anything())
                                        ->willReturn([$blackoutDuring]);

        $result = $this->service->Add($date, $resourceIds, $title, $this->conflictHandler, new RepeatNone());

        $this->assertFalse($result->WasSuccessful());
    }

    public function testConflictHandlerActsOnEachConflictingReservationAndSavesBlackout()
    {
        $userId = $this->fakeUser->UserId;
        $start = Date::Parse('2011-01-01 01:01:01');
        $end = Date::Parse('2011-02-02 02:02:02');
        $date = new DateRange($start, $end);
        $resourceId = 2;
        $resourceIds = [$resourceId];
        $title = 'title';

        $series = BlackoutSeries::Create($userId, $title, $date);
        $series->AddResourceId(2);

        $this->reservationViewRepository->expects($this->once())
                                        ->method('GetBlackoutsWithin')
                                        ->with($this->equalTo($date))
                                        ->willReturn([]);

        $reservation1 = new TestReservationItemView(1, $start, $end, 2);
        $reservation2 = new TestReservationItemView(2, $start, $end, 2);
        $this->reservationViewRepository->expects($this->once())
                                        ->method('GetReservations')
                                        ->with($this->equalTo($start), $this->equalTo($end))
                                        ->willReturn([$reservation1, $reservation2]);

        $this->conflictHandler->expects($this->exactly(2))
                              ->method('Handle')
                              ->willReturn(true);

        $result = $this->service->Add($date, $resourceIds, $title, $this->conflictHandler, new RepeatNone());

        $this->assertTrue($result->WasSuccessful());
        $series->_ResetBlackoutIteration();
        $this->blackoutRepository->_Added->_ResetBlackoutIteration();
        $this->assertEquals($series, $this->blackoutRepository->_Added);
    }

    public function testConflictHandlerReportsConflictingReservationAndDoesNotSaveBlackout()
    {
        $start = Date::Parse('2011-01-01 01:01:01');
        $end = Date::Parse('2011-02-02 02:02:02');
        $date = new DateRange($start, $end);
        $resourceId = 2;
        $resourceIds = [$resourceId];
        $title = 'title';

        $this->reservationViewRepository->expects($this->once())
                                        ->method('GetBlackoutsWithin')
                                        ->with($this->equalTo($date))
                                        ->willReturn([]);

        $reservation1 = new TestReservationItemView(1, $start, $end, 2);
        $reservation2 = new TestReservationItemView(2, $start, $end, 2);
        $this->reservationViewRepository->expects($this->once())
                                        ->method('GetReservations')
                                        ->with($this->equalTo($start), $this->equalTo($end))
                                        ->willReturn([$reservation1, $reservation2]);

        $this->conflictHandler->expects($this->exactly(2))
                              ->method('Handle')
                              ->willReturnCallback(function ($reservation) use ($reservation2) {
                                  return $this->equalTo($reservation2)->evaluate($reservation, '', true);
                              });

        $result = $this->service->Add($date, $resourceIds, $title, $this->conflictHandler, new RepeatNone());

        $this->assertFalse($result->WasSuccessful());
    }

    public function testChecksAndCreatesForEachRecurringDate()
    {
        $userId = $this->fakeUser->UserId;
        $start = Date::Parse('2011-01-01 01:01:01');
        $end = Date::Parse('2011-01-01 02:02:02');
        $range = new DateRange($start, $end);
        $resourceId = 1;
        $resourceIds = [$resourceId];
        $title = 'title';
        $repeatEnd = $start->AddDays(3);

        $repeatDaily = new RepeatDaily(1, $repeatEnd);
        $repeatDates = $repeatDaily->GetDates($range);

        /** @var DateRange[] $allDates */
        $allDates = array_merge([$range], $repeatDates);

        $series = BlackoutSeries::Create($userId, $title, $range);
        $series->Repeats($repeatDaily);
        $series->AddResourceId($resourceId);

        foreach ($repeatDates as $date) {
            $series->AddBlackout(new Blackout($date));
        }

        $this->assertEquals(4, count($allDates), 'should create 4 blackouts');

        $this->reservationViewRepository
            ->expects($this->exactly(count($allDates)))
            ->method('GetBlackoutsWithin')
            ->willReturn([]);

        $this->reservationViewRepository
            ->expects($this->exactly(count($allDates)))
            ->method('GetReservations')
            ->willReturn([]);

        $result = $this->service->Add($range, $resourceIds, $title, $this->conflictHandler, $repeatDaily);
        $this->assertTrue($result->WasSuccessful());
        $series->_ResetBlackoutIteration();
        $this->blackoutRepository->_Added->_ResetBlackoutIteration();
        $this->assertEquals($series, $this->blackoutRepository->_Added);
    }

    public function testNothingIsCheckedIfTimesAreInvalid()
    {
        $date = DateRange::Create('2011-01-01 00:00:00', '2011-01-01 00:00:00', 'UTC');
        $result = $this->service->Add($date, [1], 'title', $this->conflictHandler, new RepeatNone());

        $this->assertFalse($result->WasSuccessful());
        $this->assertNotEmpty($result->Message());
    }

    public function testDeletesBlackoutById()
    {
        $blackoutId = 123;
        $scope = SeriesUpdateScope::ThisInstance;


        $this->service->Delete($blackoutId, $scope);
        $this->assertEquals($blackoutId, $this->blackoutRepository->_DeletedId);
    }

    public function testDeletesBlackoutSeriesByInstanceId()
    {
        $blackoutId = 123;
        $scope = SeriesUpdateScope::FullSeries;

        $this->service->Delete($blackoutId, $scope);
        $this->assertEquals($blackoutId, $this->blackoutRepository->_DeletedSeriesId);
    }

    public function testGetsBlackoutsThatUserCanManageIfNotAdmin()
    {
        $userId = $this->fakeUser->UserId;
        $this->fakeUser->IsAdmin = false;
        $pageNumber = 10;
        $pageSize = 20;
        $filter = new BlackoutFilter();

        $groupIds = [1, 2, 3];
        $groups = [new UserGroup(1, null), new UserGroup(2, null), new UserGroup(3, null)];

        $expectedFilter = $filter->GetFilter();
        $adminFilter = new SqlFilterIn(new SqlFilterColumn('r', ColumnNames::RESOURCE_ADMIN_GROUP_ID), $groupIds);
        $adminFilter->_Or(new SqlFilterIn(new SqlFilterColumn(TableNames::SCHEDULES, ColumnNames::SCHEDULE_ADMIN_GROUP_ID), $groupIds));
        $expectedFilter->_And($adminFilter);

        $date = Date::Now();
        $resourceId1 = 111;
        $resourceId2 = 222;

        $b1 = new TestBlackoutItemView(1, $date, $date, $resourceId1);
        $b2 = new TestBlackoutItemView(2, $date, $date, $resourceId2);
        $blackouts = [$b1, $b2];

        $roles = [RoleLevel::RESOURCE_ADMIN, RoleLevel::SCHEDULE_ADMIN];
        $this->userRepository->expects($this->once())
                             ->method('LoadGroups')
                             ->with($this->equalTo($userId), $this->equalTo($roles))
                             ->willReturn($groups);

        $this->reservationViewRepository->expects($this->once())
                                        ->method('GetBlackoutList')
                                        ->with(
                                            $this->equalTo($pageNumber),
                                            $this->equalTo($pageSize),
                                            $this->isNull(),
                                            $this->isNull(),
                                            $this->equalTo($expectedFilter)
                                        )
                                        ->willReturn(new PageableData($blackouts));

        $this->service->LoadFiltered($pageNumber, $pageSize, null, null, $filter, $this->fakeUser);
    }

    public function testLoadsBlackoutByInstanceId()
    {
        $id = 123231;
        $userId = 89191;
        $user = $this->createMock('User');
        $resource = new BlackoutResource(1, 'name', 3);

        $series = BlackoutSeries::Create(1, 'title', new TestDateRange());
        $series->AddResource($resource);

        $this->userRepository->expects($this->once())
                             ->method('LoadById')
                             ->with($this->equalTo($userId))
                             ->willReturn($user);

        $this->blackoutRepository->_Series = $series;

        $user->expects($this->once())
             ->method('IsResourceAdminFor')
             ->with($this->equalTo($resource))
             ->willReturn(true);

        $actualSeries = $this->service->LoadBlackout($id, $userId);

        $this->assertEquals($series, $actualSeries);
        $this->assertEquals($id, $this->blackoutRepository->_LoadedBlackoutId);
    }

    public function testUpdatesBlackoutForEachResourceWhenNoConflicts()
    {
        $userId = $this->fakeUser->UserId;
        $start = Date::Parse('2011-01-01 01:01:01');
        $end = Date::Parse('2011-02-02 02:02:02');
        $date = new DateRange($start, $end);
        $resourceIds = [1, 2, 3];
        $title = 'title';
        $seriesId = 111;
        $blackoutInstanceId = 10;

        $blackoutBefore = new TestBlackoutItemView(1, Date::Parse('2010-01-01'), $start, 3);
        $blackoutAfter = new TestBlackoutItemView(2, $end, Date::Parse('2012-01-01'), 1);
        $blackoutDuringDiffResource = new TestBlackoutItemView(3, $start, $end, 4);
        $blackoutDuringSameSeries = new TestBlackoutItemView(3, $start, $end, 1, $seriesId);

        $series = BlackoutSeries::Create(1, 'old title', new TestDateRange());
        $series->WithId($seriesId);
        $user = $this->createMock('User');

        $reservationBefore = new TestReservationItemView(1, Date::Parse('2010-01-01'), $start, 1);
        $reservationAfter = new TestReservationItemView(2, $end, Date::Parse('2012-01-01'), 2);

        $this->reservationViewRepository->expects($this->once())
                                        ->method('GetBlackoutsWithin')
                                        ->with($this->equalTo($date))
                                        ->willReturn([$blackoutBefore, $blackoutAfter, $blackoutDuringDiffResource, $blackoutDuringSameSeries]);

        $this->reservationViewRepository->expects($this->once())
                                        ->method('GetReservations')
                                        ->with($this->equalTo($start), $this->equalTo($end))
                                        ->willReturn([$reservationBefore, $reservationAfter]);

        $user->expects($this->any())
             ->method('IsResourceAdminFor')
             ->with($this->anything())
             ->willReturn(true);

        $this->userRepository->expects($this->once())
                             ->method('LoadById')
                             ->with($this->equalTo($userId))
                             ->willReturn($user);

        $this->blackoutRepository->_Series = $series;

        $result = $this->service->Update(
            $blackoutInstanceId,
            $date,
            $resourceIds,
            $title,
            $this->conflictHandler,
            new RepeatNone(),
            SeriesUpdateScope::FullSeries
        );

        $this->assertTrue($result->WasSuccessful());

        $this->assertEquals($title, $series->Title());
        $this->assertEquals($series, $this->blackoutRepository->_Updated);
    }

    public function testDoesNotUpdateAnyBlackoutsIfThereAreConflictingBlackoutTimes()
    {
        $userId = $this->fakeUser->UserId;
        $start = Date::Parse('2011-01-01 01:01:01');
        $end = Date::Parse('2011-02-02 02:02:02');
        $date = new DateRange($start, $end);
        $resourceIds = [2, 3];
        $title = 'title';
        $blackoutInstanceId = 199;

        $user = $this->createMock('User');
        $series = BlackoutSeries::Create(1, 'old title', new TestDateRange());
        $series->WithId(1);

        $blackoutDuring = new TestBlackoutItemView(1, $start, $end, 3, 10);
        $this->reservationViewRepository->expects($this->atLeastOnce())
                                        ->method('GetBlackoutsWithin')
                                        ->with($this->anything())
                                        ->willReturn([$blackoutDuring]);

        $user->expects($this->any())
             ->method('IsResourceAdminFor')
             ->with($this->anything())
             ->willReturn(true);

        $this->userRepository->expects($this->once())
                             ->method('LoadById')
                             ->with($this->equalTo($userId))
                             ->willReturn($user);

        $this->blackoutRepository->_Series = $series;

        $result = $this->service->Update(
            $blackoutInstanceId,
            $date,
            $resourceIds,
            $title,
            $this->conflictHandler,
            new RepeatNone(),
            SeriesUpdateScope::FullSeries
        );

        $this->assertFalse($result->WasSuccessful());
    }

    public function testConflictHandlerActsOnEachConflictingReservationAndUpdatesBlackout()
    {
        $userId = $this->fakeUser->UserId;
        $start = Date::Parse('2011-01-01 01:01:01');
        $end = Date::Parse('2011-02-02 02:02:02');
        $date = new DateRange($start, $end);
        $resourceId = 2;
        $resourceIds = [$resourceId];
        $title = 'title';

        $seriesId = 111;
        $blackoutInstanceId = 10;

        $series = BlackoutSeries::Create(1, 'old title', new TestDateRange());
        $series->WithId($seriesId);
        $user = $this->createMock('User');

        $user->expects($this->any())
             ->method('IsResourceAdminFor')
             ->with($this->anything())
             ->willReturn(true);

        $this->userRepository->expects($this->once())
                             ->method('LoadById')
                             ->with($this->equalTo($userId))
                             ->willReturn($user);

        $this->reservationViewRepository->expects($this->once())
                                        ->method('GetBlackoutsWithin')
                                        ->with($this->equalTo($date))
                                        ->willReturn([]);

        $reservation1 = new TestReservationItemView(1, $start, $end, 2);
        $reservation2 = new TestReservationItemView(2, $start, $end, 2);
        $this->reservationViewRepository->expects($this->once())
                                        ->method('GetReservations')
                                        ->with($this->equalTo($start), $this->equalTo($end))
                                        ->willReturn([$reservation1, $reservation2]);

        $this->conflictHandler->expects($this->exactly(2))
                              ->method('Handle')
                              ->willReturn(true);

        $this->blackoutRepository->_Series = $series;

        $result = $this->service->Update(
            $blackoutInstanceId,
            $date,
            $resourceIds,
            $title,
            $this->conflictHandler,
            new RepeatNone(),
            SeriesUpdateScope::FullSeries
        );

        $this->assertTrue($result->WasSuccessful());
        $this->assertEquals($series, $this->blackoutRepository->_Updated);
    }

    public function testConflictHandlerReportsConflictingReservationAndDoesNotUpdateBlackout()
    {
        $userId = $this->fakeUser->UserId;
        $start = Date::Parse('2011-01-01 01:01:01');
        $end = Date::Parse('2011-02-02 02:02:02');
        $date = new DateRange($start, $end);
        $resourceId = 2;
        $resourceIds = [$resourceId];
        $title = 'title';

        $seriesId = 111;
        $blackoutInstanceId = 10;

        $series = BlackoutSeries::Create(1, 'old title', new TestDateRange());
        $series->WithId($seriesId);
        $user = $this->createMock('User');

        $user->expects($this->any())
             ->method('IsResourceAdminFor')
             ->with($this->anything())
             ->willReturn(true);

        $this->userRepository->expects($this->once())
                             ->method('LoadById')
                             ->with($this->equalTo($userId))
                             ->willReturn($user);

        $this->reservationViewRepository->expects($this->once())
                                        ->method('GetBlackoutsWithin')
                                        ->with($this->equalTo($date))
                                        ->willReturn([]);

        $reservation1 = new TestReservationItemView(1, $start, $end, 2);
        $reservation2 = new TestReservationItemView(2, $start, $end, 2);
        $this->reservationViewRepository->expects($this->once())
                                        ->method('GetReservations')
                                        ->with($this->equalTo($start), $this->equalTo($end))
                                        ->willReturn([$reservation1, $reservation2]);

        $this->conflictHandler->expects($this->exactly(2))
                              ->method('Handle')
                              ->willReturn(false);

        $this->blackoutRepository->_Series = $series;

        $result = $this->service->Update(
            $blackoutInstanceId,
            $date,
            $resourceIds,
            $title,
            $this->conflictHandler,
            new RepeatNone(),
            SeriesUpdateScope::FullSeries
        );

        $this->assertFalse($result->WasSuccessful());
    }
}
