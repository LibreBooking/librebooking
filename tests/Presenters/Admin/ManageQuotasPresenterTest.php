<?php

require_once(ROOT_DIR . 'Pages/Admin/ManageQuotasPage.php');

class ManageQuotasPresenterTest extends TestBase
{
    /**
     * @var IManageQuotasPage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var ManageQuotasPresenter
     */
    private $presenter;

    /**
     * @var IResourceRepository|PHPUnit\Framework\MockObject\MockObject
     */
    public $resourceRepository;

    /**
     * @var IGroupViewRepository|PHPUnit\Framework\MockObject\MockObject
     */
    public $groupRepository;

    /**
     * @var IScheduleRepository|PHPUnit\Framework\MockObject\MockObject
     */
    public $scheduleRepository;

    /**
     * @var QuotaRepository|PHPUnit\Framework\MockObject\MockObject
     */
    public $quotaRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->page = $this->createMock('IManageQuotasPage');
        $this->resourceRepository = $this->createMock('IResourceRepository');
        $this->groupRepository = $this->createMock('IGroupViewRepository');
        $this->scheduleRepository = $this->createMock('IScheduleRepository');
        $this->quotaRepository = $this->createMock('QuotaRepository');

        $this->presenter = new ManageQuotasPresenter(
            $this->page,
            $this->resourceRepository,
            $this->groupRepository,
            $this->scheduleRepository,
            $this->quotaRepository
        );
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testWhenInitializing()
    {
        $groups = [];
        $bookableResources = [];
        $schedules = [];

        $groupResult = new PageableData($groups);

        $quotaList = [];

        $this->resourceRepository->expects($this->once())
                                 ->method('GetResourceList')
                                 ->willReturn($bookableResources);

        $this->page->expects($this->once())
                   ->method('BindResources')
                   ->with($this->equalTo($bookableResources));

        $this->groupRepository->expects($this->once())
                              ->method('GetList')
                              ->willReturn($groupResult);

        $this->page->expects($this->once())
                   ->method('BindGroups')
                   ->with($this->equalTo($groups));

        $this->scheduleRepository->expects($this->once())
                                 ->method('GetAll')
                                 ->willReturn($schedules);

        $this->page->expects($this->once())
                   ->method('BindSchedules')
                   ->with($this->equalTo($schedules));

        $this->quotaRepository->expects($this->once())
                              ->method('GetAll')
                              ->willReturn($quotaList);

        $this->presenter->PageLoad();
    }

    public function testWhenAdding()
    {
        $duration = QuotaDuration::Day;
        $limit = 2;
        $unit = QuotaUnit::Hours;
        $resourceId = 987;
        $groupId = 8287;
        $scheduleId = 400;
        $enforcedStartTime = '10:00am';
        $enforcedEndTime = '4:30pm';
        $enforcedDays = [1, 3, 5];
        $scope = QuotaScope::ExcludeCompleted;

        $this->page->expects($this->atLeastOnce())
                   ->method('GetDuration')
                   ->willReturn($duration);

        $this->page->expects($this->atLeastOnce())
                   ->method('GetLimit')
                   ->willReturn($limit);

        $this->page->expects($this->atLeastOnce())
                   ->method('GetUnit')
                   ->willReturn($unit);

        $this->page->expects($this->atLeastOnce())
                   ->method('GetResourceId')
                   ->willReturn($resourceId);

        $this->page->expects($this->atLeastOnce())
                   ->method('GetGroupId')
                   ->willReturn($groupId);

        $this->page->expects($this->atLeastOnce())
                   ->method('GetScheduleId')
                   ->willReturn($scheduleId);

        $this->page->expects($this->atLeastOnce())
                   ->method('GetEnforcedAllDay')
                   ->willReturn(false);

        $this->page->expects($this->atLeastOnce())
                   ->method('GetEnforcedStartTime')
                   ->willReturn($enforcedStartTime);

        $this->page->expects($this->atLeastOnce())
                   ->method('GetEnforcedEndTime')
                   ->willReturn($enforcedEndTime);

        $this->page->expects($this->atLeastOnce())
                   ->method('GetEnforcedEveryDay')
                   ->willReturn(false);

        $this->page->expects($this->atLeastOnce())
                   ->method('GetEnforcedDays')
                   ->willReturn($enforcedDays);

        $this->page->expects($this->atLeastOnce())
                        ->method('GetScope')
                        ->willReturn($scope);

        $expectedQuota = Quota::Create($duration, $limit, $unit, $resourceId, $groupId, $scheduleId, $enforcedStartTime, $enforcedEndTime, $enforcedDays, $scope);

        $this->quotaRepository->expects($this->once())
                              ->method('Add')
                              ->with($this->equalTo($expectedQuota));

        $this->presenter->AddQuota();
    }
}
