<?php

require_once(ROOT_DIR . 'Presenters/Admin/ManageSchedulesPresenter.php');
require_once(ROOT_DIR . 'Pages/Admin/ManageSchedulesPage.php');

class ManageSchedulesPresenterTest extends TestBase
{
    /**
     * @var IUpdateSchedulePage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var IScheduleRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $scheduleRepo;

    /**
     * @var IResourceRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $resourceRepo;

    /**
     * @var IGroupViewRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $groupRepo;

    /**
     * @var ManageScheduleService
     */
    private $service;

    public function setUp(): void
    {
        parent::setup();

        $this->page = $this->createMock('IManageSchedulesPage');
        $this->scheduleRepo = $this->createMock('IScheduleRepository');
        $this->resourceRepo = $this->createMock('IResourceRepository');
        $this->groupRepo = $this->createMock('IGroupViewRepository');

        $this->service = new ManageScheduleService($this->scheduleRepo, $this->resourceRepo);
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testLayoutIsParsedFromPage()
    {
        $scheduleId = 98;
        $timezone = 'America/Chicago';
        $reservableSlots = '00:00 - 01:00 Label 1 A\n1:00- 2:00\r\n02:00 -3:30\n03:30-12:00\r\n';
        $blockedSlots = '00:00 - 01:00 Label 1 A\n1:00- 2:00\r\n02:00 -3:30\n03:30-12:00\r\n';

        $expectedLayout = ScheduleLayout::Parse($timezone, $reservableSlots, $blockedSlots);

        $this->page->expects($this->once())
                ->method('GetScheduleId')
                ->willReturn($scheduleId);

        $this->page->expects($this->once())
                ->method('GetLayoutTimezone')
                ->willReturn($timezone);

        $this->page->expects($this->once())
                ->method('GetUsingSingleLayout')
                ->willReturn(true);

        $this->page->expects($this->once())
                ->method('GetReservableSlots')
                ->willReturn($reservableSlots);

        $this->page->expects($this->once())
                ->method('GetBlockedSlots')
                ->willReturn($blockedSlots);

        $this->scheduleRepo->expects($this->once())
                ->method('AddScheduleLayout')
                ->with($this->equalTo($scheduleId), $this->equalTo($expectedLayout));

        $presenter = new ManageSchedulesPresenter($this->page, $this->service, $this->groupRepo);
        $presenter->ChangeLayout();
    }

    public function testDailyLayoutIsParsedFromPage()
    {
        $scheduleId = 98;
        $timezone = 'America/Chicago';
        $reservableSlots = [];
        $blockedSlots = [];

        for ($i = 0; $i < 7; $i++) {
            $reservableSlots[$i] = '00:00 - 01:00 Label 1 A\n1:00- 2:00\r\n02:00 -3:30\n03:30-12:00\r\n';
            $blockedSlots[$i] = '00:00 - 01:00 Label 1 A\n1:00- 2:00\r\n02:00 -3:30\n03:30-12:00\r\n';
        }

        $expectedLayout = ScheduleLayout::ParseDaily($timezone, $reservableSlots, $blockedSlots);

        $this->page->expects($this->once())
                ->method('GetScheduleId')
                ->willReturn($scheduleId);

        $this->page->expects($this->once())
                ->method('GetLayoutTimezone')
                ->willReturn($timezone);

        $this->page->expects($this->once())
                ->method('GetUsingSingleLayout')
                ->willReturn(false);

        $this->page->expects($this->once())
                ->method('GetDailyReservableSlots')
                ->willReturn($reservableSlots);

        $this->page->expects($this->once())
                ->method('GetDailyBlockedSlots')
                ->willReturn($blockedSlots);

        $this->scheduleRepo->expects($this->once())
                ->method('AddScheduleLayout')
                ->with($this->equalTo($scheduleId), $this->equalTo($expectedLayout));

        $presenter = new ManageSchedulesPresenter($this->page, $this->service, $this->groupRepo);
        $presenter->ChangeLayout();
    }

    public function testNewScheduleIsAdded()
    {
        $sourceScheduleId = 198;
        $name = 'new name';
        $startDay = '3';
        $daysVisible = '7';

        $expectedSchedule = new Schedule(null, $name, false, $startDay, $daysVisible);

        $this->page->expects($this->once())
                ->method('GetSourceScheduleId')
                ->willReturn($sourceScheduleId);

        $this->page->expects($this->once())
                ->method('GetScheduleName')
                ->willReturn($name);

        $this->page->expects($this->once())
                ->method('GetStartDay')
                ->willReturn($startDay);

        $this->page->expects($this->once())
                ->method('GetDaysVisible')
                ->willReturn($daysVisible);

        $this->scheduleRepo->expects($this->once())
                ->method('Add')
                ->with($this->equalTo($expectedSchedule), $this->equalTo($sourceScheduleId));

        $presenter = new ManageSchedulesPresenter($this->page, $this->service, $this->groupRepo);
        $presenter->Add();
    }

    public function testDeletesSchedule()
    {
        $scheduleId = 1;
        $targetId = 2;

        $schedule = new Schedule(null, 'name', false, 0, 4);

        $resource1 = new FakeBookableResource(1, 'name1');
        $resource2 = new FakeBookableResource(2, 'name2');
        $resource1->SetScheduleId($targetId);
        $resource2->SetScheduleId($targetId);

        $resources = [$resource1, $resource2];

        $this->page->expects($this->once())
                ->method('GetScheduleId')
                ->willReturn($scheduleId);

        $this->page->expects($this->once())
                ->method('GetTargetScheduleId')
                ->willReturn($targetId);

        $this->scheduleRepo->expects($this->once())
                ->method('LoadById')
                ->with($this->equalTo($scheduleId))
                ->willReturn($schedule);

        $this->scheduleRepo->expects($this->once())
                ->method('Delete')
                ->with($this->equalTo($schedule));

        $this->resourceRepo->expects($this->once())
                ->method('GetScheduleResources')
                ->with($this->equalTo($scheduleId))
                ->willReturn($resources);

        $matcher = $this->exactly(2);
        $this->resourceRepo->expects($matcher)
                ->method('Update')
                ->willReturnCallback(function ($resource) use ($matcher, $resource1, $resource2)
                {
                    match ($matcher->numberOfInvocations())
                    {
                        1 => $this->assertEquals($resource, $resource1),
                        2 => $this->assertEquals($resource, $resource2)
                    };
                });

        $presenter = new ManageSchedulesPresenter($this->page, $this->service, $this->groupRepo);

        $presenter->Delete();
    }
}
