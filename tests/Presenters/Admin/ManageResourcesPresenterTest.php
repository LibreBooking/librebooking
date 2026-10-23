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
        $this->ExpectGetListPaging(expectedPageNumber: 3);

        $this->presenter->PageLoad();
    }

    public function testExportResourcesLoadsAllResources(): void
    {
        $this->ExpectGetListPaging(expectedPageNumber: null);
        $this->page->expects($this->once())->method('ShowExportCsv');

        $this->presenter->ExportResources();
    }

    private function ExpectGetListPaging(?int $expectedPageNumber): void
    {
        $this->resourceRepository->expects($this->once())
            ->method('GetList')
            ->with($this->identicalTo($expectedPageNumber), $this->isNull())
            ->willReturn(new PageableData([]));
    }
}
