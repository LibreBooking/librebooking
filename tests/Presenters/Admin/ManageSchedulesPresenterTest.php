<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/Admin/ManageSchedulesPresenter.php');
require_once(ROOT_DIR . 'Pages/Admin/ManageSchedulesPage.php');

class ManageSchedulesPresenterTest extends TestBase
{
    /**
     * @var IManageSchedulesPage|PHPUnit\Framework\MockObject\MockObject
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
        $startDay = 3;
        $daysVisible = 7;

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
                ->willReturnCallback(function ($resource) use ($matcher, $resource1, $resource2) {
                    match ($matcher->numberOfInvocations()) {
                        1 => $this->assertEquals($resource, $resource1),
                        2 => $this->assertEquals($resource, $resource2),
                        default => $this->fail('Unexpected invocation ' . $matcher->numberOfInvocations()),
                    };
                });

        $presenter = new ManageSchedulesPresenter($this->page, $this->service, $this->groupRepo);

        $presenter->Delete();
    }

    public function testChangeAvailabilitySetsAvailabilityWindow(): void
    {
        $schedule = new FakeSchedule();
        $schedule->SetAvailableAllYear();

        $this->page->method('GetScheduleId')->willReturn(1);
        $this->page->method('GetAvailableAllYear')->willReturn(false);
        $this->page->method('GetAvailabilityBegin')->willReturn('2026-03-01');
        $this->page->method('GetAvailabilityEnd')->willReturn('2026-04-01');

        $this->scheduleRepo->method('LoadById')->willReturn($schedule);
        $this->scheduleRepo->expects($this->once())
            ->method('Update')
            ->with($this->identicalTo($schedule));

        $presenter = new ManageSchedulesPresenter($this->page, $this->service, $this->groupRepo);
        $presenter->ChangeAvailability();

        $this->assertTrue($schedule->HasAvailability());
    }

    /**
     * @return array<string, array{bool, mixed, mixed}>
     */
    public static function clearedAvailabilityProvider(): array
    {
        return [
            'all year checked' => [true, '2026-03-01', '2026-04-01'],
            'all year checked without dates' => [true, null, null],
            'blank start' => [false, '', '2026-04-01'],
            'blank end' => [false, '2026-03-01', ''],
            'blank start with malformed end' => [false, '', ['2026-04-01']],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('clearedAvailabilityProvider')]
    public function testChangeAvailabilityClearsAvailabilityWindow(bool $allYear, mixed $start, mixed $end): void
    {
        $schedule = new FakeSchedule();

        $this->page->method('GetScheduleId')->willReturn(1);
        $this->page->method('GetAvailableAllYear')->willReturn($allYear);
        $this->page->method('GetAvailabilityBegin')->willReturn($start);
        $this->page->method('GetAvailabilityEnd')->willReturn($end);

        $this->scheduleRepo->method('LoadById')->willReturn($schedule);
        $this->scheduleRepo->expects($this->once())
            ->method('Update')
            ->with($this->identicalTo($schedule));

        $presenter = new ManageSchedulesPresenter($this->page, $this->service, $this->groupRepo);
        $presenter->ChangeAvailability();

        $this->assertFalse($schedule->HasAvailability());
    }

    /**
     * @return array<string, array{mixed, mixed}>
     */
    public static function malformedAvailabilityProvider(): array
    {
        return [
            // Sent as name[]=x.
            'start as a list' => [['2026-03-01'], '2026-04-01'],
            'end as a list' => ['2026-03-01', ['2026-04-01']],
            'missing start' => [null, '2026-04-01'],
            'missing end' => ['2026-03-01', null],
            // Date::Parse() reads '0' as no date.
            'zero start' => ['0', '2026-04-01'],
            'zero end' => ['2026-03-01', '0'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('malformedAvailabilityProvider')]
    public function testChangeAvailabilityKeepsAvailabilityWindowWhenDateIsMalformed(mixed $start, mixed $end): void
    {
        $schedule = new FakeSchedule();

        $this->page->method('GetScheduleId')->willReturn(1);
        $this->page->method('GetAvailableAllYear')->willReturn(false);
        $this->page->method('GetAvailabilityBegin')->willReturn($start);
        $this->page->method('GetAvailabilityEnd')->willReturn($end);

        $this->scheduleRepo->method('LoadById')->willReturn($schedule);
        $this->scheduleRepo->expects($this->never())->method('Update');

        // The form replaces the displayed availability with the response, so
        // the stored availability must still be shown.
        $this->page->expects($this->once())
            ->method('DisplayAvailability')
            ->with($this->identicalTo($schedule));

        $presenter = new ManageSchedulesPresenter($this->page, $this->service, $this->groupRepo);
        $presenter->ChangeAvailability();

        $this->assertTrue($schedule->HasAvailability());
    }

    public function testUpdateLayoutSlotReplacesSlot(): void
    {
        $this->page->method('GetScheduleId')->willReturn(1);
        $this->page->method('GetSlotStart')->willReturn('2026-03-01 09:00');
        $this->page->method('GetSlotEnd')->willReturn('2026-03-01 10:00');
        $this->page->method('GetSlotId')->willReturn('2026-03-01 08:00');

        $this->scheduleRepo->expects($this->once())->method('DeleteCustomLayoutPeriod');
        $this->scheduleRepo->expects($this->once())->method('AddCustomLayoutPeriod');

        $presenter = new ManageSchedulesPresenter($this->page, $this->service, $this->groupRepo);
        $presenter->UpdateLayoutSlot();
    }

    /**
     * @return array<string, array{mixed, mixed, mixed}>
     */
    public static function malformedLayoutSlotProvider(): array
    {
        $start = '2026-03-01 09:00';
        $end = '2026-03-01 10:00';
        $slotId = '2026-03-01 08:00';

        return [
            // Sent as name[]=x.
            'start as a list' => [[$start], $end, $slotId],
            'end as a list' => [$start, [$end], $slotId],
            'slot id as a list' => [$start, $end, [$slotId]],
            'missing start' => [null, $end, $slotId],
            'missing end' => [$start, null, $slotId],
            'missing slot id' => [$start, $end, null],
            'blank start' => ['', $end, $slotId],
            'blank end' => [$start, '', $slotId],
            'blank slot id' => [$start, $end, ''],
            // Date::Parse() reads '0' as no date.
            'zero start' => ['0', $end, $slotId],
            'zero end' => [$start, '0', $slotId],
            'zero slot id' => [$start, $end, '0'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('malformedLayoutSlotProvider')]
    public function testUpdateLayoutSlotKeepsSlotWhenDateIsMalformed(mixed $start, mixed $end, mixed $slotId): void
    {
        $this->page->method('GetScheduleId')->willReturn(1);
        $this->page->method('GetSlotStart')->willReturn($start);
        $this->page->method('GetSlotEnd')->willReturn($end);
        $this->page->method('GetSlotId')->willReturn($slotId);

        $this->scheduleRepo->expects($this->never())->method('DeleteCustomLayoutPeriod');
        $this->scheduleRepo->expects($this->never())->method('AddCustomLayoutPeriod');

        $presenter = new ManageSchedulesPresenter($this->page, $this->service, $this->groupRepo);
        $presenter->UpdateLayoutSlot();
    }
}
