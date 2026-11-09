<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/Admin/ManageResourcesPresenter.php');
require_once(ROOT_DIR . 'Pages/Admin/ManageResourcesPage.php');

class ManageResourcesPresenterTest extends TestBase
{
    private IManageResourcesPage&\PHPUnit\Framework\MockObject\MockObject $page;

    private IResourceRepository&\PHPUnit\Framework\MockObject\MockObject $resourceRepository;

    private ManageResourcesPresenter $presenter;

    public function setUp(): void
    {
        parent::setup();

        $this->page = $this->createMock(IManageResourcesPage::class);
        $this->resourceRepository = $this->createMock(IResourceRepository::class);

        $scheduleRepository = $this->createMock(IScheduleRepository::class);
        $scheduleRepository->method('GetAll')->willReturn([]);
        $groupRepository = $this->createMock(IGroupViewRepository::class);
        $groupRepository->method('GetGroupsByRole')->willReturn([]);
        $attributeService = $this->createMock(IAttributeService::class);
        $attributeService->method('GetByCategory')->willReturn([]);

        $this->page->method('GetPageNumber')->willReturn(3);
        $this->page->method('GetPageSize')->willReturn(20);
        $this->page->method('GetFilterValues')->willReturn(new ResourceFilterValues());
        $this->resourceRepository->method('GetResourceTypes')->willReturn([]);
        $this->resourceRepository->method('GetStatusReasons')->willReturn([]);
        $this->resourceRepository->method('GetResourceGroups')->willReturn(new ResourceGroupTree());

        $this->presenter = new ManageResourcesPresenter(
            $this->page,
            $this->resourceRepository,
            $scheduleRepository,
            $this->createMock(IImageFactory::class),
            $groupRepository,
            $attributeService,
            $this->createMock(IUserPreferenceRepository::class),
            $this->createMock(IReservationViewRepository::class)
        );
    }

    public function testPageLoadRequestsCurrentPage(): void
    {
        $this->ExpectGetListPaging(expectedPageNumber: 3, expectedPageSize: 20);

        $this->presenter->PageLoad();
    }

    public function testExportResourcesLoadsAllResources(): void
    {
        $this->ExpectGetListPaging(expectedPageNumber: null, expectedPageSize: null);
        $this->page->expects($this->once())->method('ShowExportCsv');

        $this->presenter->ExportResources();
    }

    private function ExpectGetListPaging(?int $expectedPageNumber, ?int $expectedPageSize): void
    {
        $this->resourceRepository->expects($this->once())
            ->method('GetList')
            ->with($this->identicalTo($expectedPageNumber), $this->identicalTo($expectedPageSize))
            ->willReturn(new PageableData([]));
    }

    public function testBulkUpdateLeavesDropdownFieldsUnchangedForMinusOne(): void
    {
        $resource = $this->CreateResourceForBulkUpdate();
        $this->ConfigureBulkUpdate(resource: $resource, dropdownValue: -1);

        $this->presenter->BulkUpdate();

        $this->assertSame(5, $resource->GetScheduleId());
        $this->assertSame(7, $resource->GetResourceTypeId());
        $this->assertSame(9, $resource->GetAdminGroupId());
        $this->assertSame(ResourceStatus::AVAILABLE, $resource->GetStatusId());
    }

    public function testBulkUpdateChangesDropdownFieldsAndKeepsDecimalCredits(): void
    {
        $resource = $this->CreateResourceForBulkUpdate();
        $this->ConfigureBulkUpdate(resource: $resource, dropdownValue: 2);
        $this->page->method('GetCredits')->willReturn(1.5);

        $this->presenter->BulkUpdate();

        $this->assertSame(2, $resource->GetScheduleId());
        $this->assertSame(2, $resource->GetResourceTypeId());
        $this->assertSame(2, $resource->GetAdminGroupId());
        $this->assertSame(2, $resource->GetStatusId());
        $this->assertSame(1.5, $resource->GetCreditsPerSlot());
    }

    public function testBulkUpdateAppliesExplicitZeroCredits(): void
    {
        $resource = $this->CreateResourceForBulkUpdate();
        $this->ConfigureBulkUpdate(resource: $resource, dropdownValue: -1);
        $this->page->method('GetCredits')->willReturn(0.0);
        $this->page->method('GetPeakCredits')->willReturn(0.0);

        $this->presenter->BulkUpdate();

        $this->assertEquals(0, $resource->GetCreditsPerSlot());
        $this->assertEquals(0, $resource->GetPeakCreditsPerSlot());
    }

    public function testBulkUpdateLeavesCreditsUnchangedWhenBlank(): void
    {
        $resource = $this->CreateResourceForBulkUpdate();
        $this->ConfigureBulkUpdate(resource: $resource, dropdownValue: -1);
        $this->page->method('GetCredits')->willReturn(null);
        $this->page->method('GetPeakCredits')->willReturn(null);

        $this->presenter->BulkUpdate();

        $this->assertEquals(3, $resource->GetCreditsPerSlot());
        $this->assertEquals(4, $resource->GetPeakCreditsPerSlot());
    }

    private function CreateResourceForBulkUpdate(): BookableResource
    {
        $resource = BookableResource::CreateNew('Room', 5);
        $resource->SetResourceTypeId(7);
        $resource->SetAdminGroupId(9);
        $resource->ChangeStatus(ResourceStatus::AVAILABLE);
        $resource->SetCreditsPerSlot(3);
        $resource->SetPeakCreditsPerSlot(4);

        return $resource;
    }

    private function ConfigureBulkUpdate(BookableResource $resource, int $dropdownValue): void
    {
        $this->page->method('GetBulkUpdateResourceIds')->willReturn([1]);
        $this->page->method('GetAttributes')->willReturn([]);
        foreach (['GetScheduleId', 'GetResourceTypeId', 'GetAdminGroupId', 'GetStatusId'] as $method) {
            $this->page->method($method)->willReturn($dropdownValue);
        }
        foreach (['GetBulkAllowMultiday', 'GetBulkRequiresApproval', 'GetBulkAutoAssign', 'GetAllowSubscriptions'] as $method) {
            $this->page->method($method)->willReturn('-1');
        }
        $this->page->method('GetBulkEnableCheckin')->willReturn(-1);
        $this->page->method('GetBulkAllowConcurrentReservations')->willReturn(-1);
        $this->resourceRepository->method('LoadById')->willReturn($resource);
        $this->resourceRepository->expects($this->once())->method('Update')->with($this->identicalTo($resource));
    }
}
