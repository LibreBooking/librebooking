<?php

require_once(ROOT_DIR . 'Presenters/Admin/ManageBlackoutsPresenter.php');

class ManageBlackoutsPresenterTest extends TestBase
{
    /**
     * @var ManageBlackoutsPresenter
     */
    private $presenter;

    /**
     * @var IManageBlackoutsPage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var IManageBlackoutsService|PHPUnit\Framework\MockObject\MockObject
     */
    private $blackoutsService;

    /**
     * @var IScheduleRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $scheduleRepository;

    /**
     * @var IResourceRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $resourceRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->page = $this->createMock('IManageBlackoutsPage');
        $this->blackoutsService = $this->createMock('IManageBlackoutsService');
        $this->scheduleRepository = $this->createMock('IScheduleRepository');
        $this->resourceRepository = $this->createMock('IResourceRepository');

        $this->presenter = new ManageBlackoutsPresenter(
            $this->page,
            $this->blackoutsService,
            $this->scheduleRepository,
            $this->resourceRepository
        );
    }

    public function testUsesTwoWeekSpanWhenNoDateFilterProvided()
    {
        $userTimezone = $this->fakeUser->Timezone;
        $defaultStart = Date::Now()->AddDays(-7)->ToTimezone($userTimezone)->GetDate();
        $defaultEnd = Date::Now()->AddDays(7)->ToTimezone($userTimezone)->GetDate();
        $searchedScheduleId = 15;
        $searchedResourceId = 105;

        $this->page->expects($this->atLeastOnce())
                ->method('GetStartDate')
                ->willReturn(null);

        $this->page->expects($this->atLeastOnce())
                ->method('GetEndDate')
                ->willReturn(null);

        $this->page->expects($this->once())
            ->method('GetScheduleId')
            ->willReturn($searchedScheduleId);

        $this->page->expects($this->once())
            ->method('GetResourceId')
            ->willReturn($searchedResourceId);

        $filter = $this->GetExpectedFilter($defaultStart, $defaultEnd, $searchedScheduleId, $searchedResourceId);
        $data = new PageableData();
        $this->blackoutsService->expects($this->once())
                ->method('LoadFiltered')
                ->with($this->anything(), $this->anything(), $this->anything(), $this->anything(), $this->equalTo($filter), $this->equalTo($this->fakeUser))
                ->willReturn($data);

        $this->page->expects($this->once())
                ->method('SetStartDate')
                ->with($this->equalTo($defaultStart));

        $this->page->expects($this->once())
                ->method('SetEndDate')
                ->with($this->equalTo($defaultEnd));

        $this->page->expects($this->once())
            ->method('SetScheduleId')
            ->with($this->equalTo($searchedScheduleId));

        $this->page->expects($this->once())
            ->method('SetResourceId')
            ->with($this->equalTo($searchedResourceId));

        $this->presenter->PageLoad($userTimezone);
    }

    public function testAddsNewBlackoutTimeForSingleResource()
    {
        $startDate = '1/1/2011';
        $endDate = '1/2/2011';
        $startTime = '01:30 PM';
        $endTime = '12:15 AM';
        $timezone = $this->fakeUser->Timezone;
        $dr = DateRange::Create($startDate . ' ' . $startTime, $endDate . ' ' . $endTime, $timezone);
        $title = 'out of service';
        $conflictAction = ReservationConflictResolution::Delete;
        $conflictResolution = ReservationConflictResolution::Create($conflictAction);
        $endDateString = '2012-01-01';
        $repeatType = RepeatType::Daily;
        $repeatInterval = 1;
        $repeatDays = [1, 2];
        $repeatMonthlyType = RepeatMonthlyType::DayOfMonth;

        $roFactory = new RepeatOptionsFactory();
        $repeatEndDate = Date::Parse($endDateString, $timezone);
        $repeatOptions = $roFactory->Create($repeatType, $repeatInterval, $repeatEndDate, $repeatDays, $repeatMonthlyType, []);

        $this->ExpectPageToReturnCommonBlackoutInfo($startDate, $startTime, $endDate, $endTime, $title, $conflictAction);
        $this->ExpectPageToReturnRepeatInfo($repeatType, $repeatInterval, $endDateString, $repeatDays, $repeatMonthlyType);

        $resourceId = 123;
        $this->page->expects($this->once())
            ->method('GetBlackoutResourceId')
            ->willReturn($resourceId);

        $this->page->expects($this->once())
            ->method('GetApplyBlackoutToAllResources')
            ->willReturn(false);

        $result = $this->createMock('IBlackoutValidationResult');
        $this->blackoutsService->expects($this->once())
            ->method('Add')
            ->with(
                $this->equalTo($dr),
                $this->equalTo([$resourceId]),
                $this->equalTo($title),
                $this->equalTo($conflictResolution),
                $this->equalTo($repeatOptions)
            )
            ->willReturn($result);

        $this->presenter->AddBlackout();
    }

    public function testAddsNewBlackoutTimeForSchedule()
    {
        $startDate = '1/1/2011';
        $endDate = '1/2/2011';
        $startTime = '01:30 PM';
        $endTime = '12:15 AM';
        $timezone = $this->fakeUser->Timezone;
        $dr = DateRange::Create($startDate . ' ' . $startTime, $endDate . ' ' . $endTime, $timezone);
        $title = 'out of service';
        $conflictAction = ReservationConflictResolution::Delete;
        $conflictResolution = ReservationConflictResolution::Create($conflictAction);

        $endDateString = '2012-01-01';
        $repeatType = RepeatType::Daily;
        $repeatInterval = 1;
        $repeatDays = [1, 2];
        $repeatMonthlyType = RepeatMonthlyType::DayOfMonth;

        $roFactory = new RepeatOptionsFactory();
        $repeatEndDate = Date::Parse($endDateString, $timezone);
        $repeatOptions = $roFactory->Create($repeatType, $repeatInterval, $repeatEndDate, $repeatDays, $repeatMonthlyType, []);

        $this->ExpectPageToReturnCommonBlackoutInfo($startDate, $startTime, $endDate, $endTime, $title, $conflictAction);
        $this->ExpectPageToReturnRepeatInfo($repeatType, $repeatInterval, $endDateString, $repeatDays, $repeatMonthlyType);

        $scheduleId = 123;
        $this->page->expects($this->once())
            ->method('GetBlackoutScheduleId')
            ->willReturn($scheduleId);

        $this->page->expects($this->once())
            ->method('GetApplyBlackoutToAllResources')
            ->willReturn(true);

        $resources = [new FakeBookableResource(1), new FakeBookableResource(2), new FakeBookableResource(3)];
        $this->resourceRepository->expects($this->once())
            ->method('GetScheduleResources')
            ->with($this->equalTo($scheduleId))
            ->willReturn($resources);

        $result = $this->createMock('IBlackoutValidationResult');
        $this->blackoutsService->expects($this->once())
            ->method('Add')
            ->with(
                $this->equalTo($dr),
                $this->equalTo([1, 2, 3]),
                $this->equalTo($title),
                $this->equalTo($conflictResolution),
                $this->equalTo($repeatOptions)
            )
            ->willReturn($result);

        $this->presenter->AddBlackout();
    }

    public function testDeletesBlackoutById()
    {
        $id = 123;
        $scope = SeriesUpdateScope::ThisInstance;

        $this->page->expects($this->once())
                    ->method('GetBlackoutId')
                    ->willReturn($id);

        $this->page->expects($this->once())
                            ->method('GetSeriesUpdateScope')
                            ->willReturn(SeriesUpdateScope::ThisInstance);

        $this->blackoutsService->expects($this->once())
                    ->method('Delete')
                    ->with($this->equalTo($id), $this->equalTo($scope));

        $this->presenter->DeleteBlackout();
    }

    public function testLoadsBlackoutSeriesByBlackoutId()
    {
        $series = BlackoutSeries::Create(1, 'title', new TestDateRange());
        $repeatOptions = new RepeatDaily(1, Date::Now());
        $series->WithRepeatOptions($repeatOptions);
        $series->AddResource(new BlackoutResource(1, 'r1', 1));
        $series->AddResource(new BlackoutResource(2, 'r2', 1));
        $config = $series->RepeatConfiguration();

        $userTz = $this->fakeUser->Timezone;

        $id = 123;

        $this->page->expects($this->once())
                   ->method('GetBlackoutId')
                   ->willReturn($id);

        $this->page->expects($this->once())
                    ->method('SetBlackoutResources')
                    ->with($this->equalTo($series->ResourceIds()));

        $this->page->expects($this->once())
                    ->method('SetTitle')
                    ->with($this->equalTo('title'));

        $this->page->expects($this->once())
                    ->method('SetRepeatType')
                    ->with($this->equalTo($config->Type));

        $this->page->expects($this->once())
                    ->method('SetRepeatInterval')
                    ->with($this->equalTo($config->Interval));

        $this->page->expects($this->once())
                    ->method('SetRepeatMonthlyType')
                    ->with($this->equalTo($config->MonthlyType));

        $this->page->expects($this->once())
                    ->method('SetRepeatWeekdays')
                    ->with($this->equalTo($config->Weekdays));

        $this->page->expects($this->once())
                    ->method('SetRepeatTerminationDate')
                    ->with($this->equalTo($config->TerminationDate->ToTimezone($userTz)));

        $this->page->expects($this->once())
                    ->method('SetBlackoutId')
                    ->with($this->equalTo($id));

        $this->page->expects($this->once())
                    ->method('SetIsRecurring')
                    ->with($this->equalTo(true));

        $this->page->expects($this->once())
                    ->method('SetBlackoutStartDate')
                    ->with($this->equalTo($series->CurrentBlackout()->StartDate()->ToTimezone($userTz)));

        $this->page->expects($this->once())
                    ->method('SetBlackoutEndDate')
                    ->with($this->equalTo($series->CurrentBlackout()->EndDate()->ToTimezone($userTz)));

        $this->page->expects($this->once())
                            ->method('SetWasBlackoutFound')
                            ->with($this->equalTo(true));

        $this->page->expects($this->once())
                            ->method('ShowBlackout');

        $this->blackoutsService->expects($this->once())
                               ->method('LoadBlackout')
                               ->with($this->equalTo($id), $this->equalTo($this->fakeUser->UserId))
                               ->willReturn($series);

        $this->presenter->LoadBlackout();
    }

    public function testUpdatesBlackout()
    {
        $startDate = '1/1/2011';
        $endDate = '1/2/2011';
        $startTime = '01:30 PM';
        $endTime = '12:15 AM';
        $timezone = $this->fakeUser->Timezone;
        $dr = DateRange::Create($startDate . ' ' . $startTime, $endDate . ' ' . $endTime, $timezone);
        $title = 'out of service';
        $conflictAction = ReservationConflictResolution::Delete;
        $conflictResolution = ReservationConflictResolution::Create($conflictAction);
        $endDateString = '2012-01-01';
        $repeatType = RepeatType::Daily;
        $repeatInterval = 1;
        $repeatDays = [1, 2];
        $repeatMonthlyType = RepeatMonthlyType::DayOfMonth;
        $blackoutInstanceId = 1111;
        $scope = SeriesUpdateScope::ThisInstance;

        $roFactory = new RepeatOptionsFactory();
        $repeatEndDate = Date::Parse($endDateString, $timezone);
        $repeatOptions = $roFactory->Create($repeatType, $repeatInterval, $repeatEndDate, $repeatDays, $repeatMonthlyType, []);

        $this->ExpectPageToReturnCommonBlackoutInfo($startDate, $startTime, $endDate, $endTime, $title, $conflictAction);
        $this->ExpectPageToReturnRepeatInfo($repeatType, $repeatInterval, $endDateString, $repeatDays, $repeatMonthlyType);

        $resourceIds = [123, 456];
        $this->page->expects($this->once())
            ->method('GetBlackoutResourceIds')
            ->willReturn($resourceIds);

        $this->page->expects($this->once())
            ->method('GetUpdateBlackoutId')
            ->willReturn($blackoutInstanceId);

        $this->page->expects($this->once())
            ->method('GetSeriesUpdateScope')
            ->willReturn($scope);

        $result = $this->createMock('IBlackoutValidationResult');

        $this->blackoutsService->expects($this->once())
            ->method('Update')
            ->with(
                $this->equalTo($blackoutInstanceId),
                $this->equalTo($dr),
                $this->equalTo($resourceIds),
                $this->equalTo($title),
                $this->equalTo($conflictResolution),
                $this->equalTo($repeatOptions),
                $this->equalTo($scope)
            )
            ->willReturn($result);

        $this->presenter->UpdateBlackout();
    }

    /**
     * @param Date $startDate
     * @param Date $endDate
     * @param int $scheduleId
     * @param int $resourceId
     * @return BlackoutFilter
     */
    private function GetExpectedFilter($startDate = null, $endDate = null, $scheduleId = null, $resourceId = null)
    {
        return new BlackoutFilter($startDate, $endDate, $scheduleId, $resourceId);
    }

    private function ExpectPageToReturnCommonBlackoutInfo($startDate, $startTime, $endDate, $endTime, $title, $conflictAction)
    {
        $this->page->expects($this->once())
            ->method('GetBlackoutStartDate')
            ->willReturn($startDate);

        $this->page->expects($this->once())
            ->method('GetBlackoutStartTime')
            ->willReturn($startTime);

        $this->page->expects($this->once())
            ->method('GetBlackoutEndDate')
            ->willReturn($endDate);

        $this->page->expects($this->once())
            ->method('GetBlackoutEndTime')
            ->willReturn($endTime);

        $this->page->expects($this->once())
            ->method('GetBlackoutTitle')
            ->willReturn($title);

        $this->page->expects($this->once())
            ->method('GetBlackoutConflictAction')
            ->willReturn($conflictAction);
    }

    private function ExpectPageToReturnRepeatInfo($repeatType = RepeatType::None, $repeatInterval = null, $endDateString = null, $repeatDays = null, $repeatMonthlyType = null)
    {
        $this->page->expects($this->any())
                    ->method('GetRepeatType')
                    ->willReturn($repeatType);

        $this->page->expects($this->any())
                    ->method('GetRepeatInterval')
                    ->willReturn($repeatInterval);

        $this->page->expects($this->any())
                    ->method('GetRepeatTerminationDate')
                    ->willReturn($endDateString);

        $this->page->expects($this->any())
                    ->method('GetRepeatWeekdays')
                    ->willReturn($repeatDays);

        $this->page->expects($this->any())
                    ->method('GetRepeatMonthlyType')
                    ->willReturn($repeatMonthlyType);

        $this->page->expects($this->once())
                    ->method('GetRepeatCustomDates')
                    ->willReturn([]);
    }
}
