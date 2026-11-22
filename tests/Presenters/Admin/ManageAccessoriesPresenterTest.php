<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/ManageAccessoriesPage.php');

class ManageAccessoriesPresenterTest extends TestBase
{
    /**
     * @var IManageAccessoriesPage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var IResourceRepository|PHPUnit\Framework\MockObject\MockObject
     */
    public $resourceRepository;

    /**
     * @var IAccessoryRepository|PHPUnit\Framework\MockObject\MockObject
     */
    public $accessoryRepository;

    /**
     * @var ManageAccessoriesPresenter
     */
    private $presenter;

    public function setUp(): void
    {
        parent::setup();

        $this->page = $this->createMock('IManageAccessoriesPage');
        $this->resourceRepository = $this->createMock('IResourceRepository');
        $this->accessoryRepository = $this->createMock('IAccessoryRepository');

        $this->presenter = new ManageAccessoriesPresenter(
            $this->page,
            $this->resourceRepository,
            $this->accessoryRepository
        );
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testWhenInitializing()
    {
        $accessories = [];
        $resources = [];

        $this->resourceRepository->expects($this->once())
            ->method('GetAccessoryList')
            ->willReturn($accessories);

        $this->resourceRepository->expects($this->once())
            ->method('GetResourceList')
            ->willReturn($resources);

        $this->page->expects($this->once())
            ->method('BindAccessories')
            ->with($this->equalTo($accessories));

        $this->page->expects($this->once())
            ->method('BindResources')
            ->with($this->equalTo($resources));

        $this->presenter->PageLoad();
    }

    public function testWhenAdding()
    {
        $name = 'accessory';
        $quantity = 2;

        $expectedAccessory = Accessory::Create($name, $quantity);

        $this->page->expects($this->once())
                ->method('GetAccessoryName')
                ->willReturn($name);

        $this->page->expects($this->once())
                ->method('GetQuantityAvailable')
                ->willReturn($quantity);

        $this->accessoryRepository->expects($this->once())
            ->method('Add')
            ->with($this->equalTo($expectedAccessory));

        $this->presenter->AddAccessory();
    }

    public function testWhenEditing()
    {
        $id = 1982;
        $name = 'accessory';
        $quantity = 2;

        $currentAccessory = new Accessory($id, 'lskdjfl', 18181);
        $expectedAccessory = new Accessory($id, $name, $quantity);

        $this->page->expects($this->once())
                ->method('GetAccessoryId')
                ->willReturn($id);

        $this->page->expects($this->once())
                ->method('GetAccessoryName')
                ->willReturn($name);

        $this->page->expects($this->once())
                ->method('GetQuantityAvailable')
                ->willReturn($quantity);

        $this->accessoryRepository->expects($this->once())
            ->method('LoadById')
            ->with($this->equalTo($id))
            ->willReturn($currentAccessory);

        $this->accessoryRepository->expects($this->once())
            ->method('Update')
            ->with($this->equalTo($expectedAccessory));

        $this->presenter->ChangeAccessory();
    }

    public function testWhenDeleting()
    {
        $id = 1982;

        $this->page->expects($this->once())
                ->method('GetAccessoryId')
                ->willReturn($id);

        $this->accessoryRepository->expects($this->once())
            ->method('Delete')
            ->with($this->equalTo($id));

        $this->presenter->DeleteAccessory();
    }

    public function testEditingUnknownAccessoryChangesNothing(): void
    {
        $this->page->method('GetAccessoryId')->willReturn(999);
        $this->page->method('GetAccessoryName')->willReturn('name');
        $this->page->method('GetQuantityAvailable')->willReturn(1);
        $this->accessoryRepository->method('LoadById')->willReturn(null);
        $this->accessoryRepository->expects($this->never())->method('Update');

        $this->presenter->ChangeAccessory();
    }

    public function testLoadingResourcesOfUnknownAccessoryBindsNoResources(): void
    {
        $this->page->method('GetAccessoryId')->willReturn(999);
        $this->accessoryRepository->method('LoadById')->willReturn(null);
        $this->page->expects($this->once())
            ->method('SetAccessoryResources')
            ->with($this->equalTo([]));

        $this->presenter->ProcessDataRequest('accessoryResources');
    }

    public function testChangingResourcesOfUnknownAccessoryChangesNothing(): void
    {
        $this->page->method('GetAccessoryId')->willReturn(999);
        $this->page->method('GetAccessoryResources')->willReturn([]);
        $this->page->method('GetAccessoryResourcesMinimums')->willReturn([]);
        $this->page->method('GetAccessoryResourcesMaximums')->willReturn([]);
        $this->accessoryRepository->method('LoadById')->willReturn(null);
        $this->accessoryRepository->expects($this->never())->method('Update');

        $this->presenter->ChangeAccessoryResources();
    }

    public function testChangesAccessoryResources(): void
    {
        $accessory = new Accessory(1, 'accessory', 10);

        // The form submits both quantities for every resource, keyed by
        // resource ID, whether or not the resource is selected.
        $this->page->method('GetAccessoryId')->willReturn(1);
        $this->page->method('GetAccessoryResources')->willReturn([5 => '5', 7 => '7']);
        $this->page->method('GetAccessoryResourcesMinimums')->willReturn([5 => '1', 6 => '', 7 => '']);
        $this->page->method('GetAccessoryResourcesMaximums')->willReturn([5 => '3', 6 => '', 7 => '']);
        $this->accessoryRepository->method('LoadById')->willReturn($accessory);
        $this->accessoryRepository->expects($this->once())
            ->method('Update')
            ->with($this->identicalTo($accessory));

        $this->presenter->ChangeAccessoryResources();

        // A blank quantity means no limit.
        $this->assertEquals(
            [new ResourceAccessory('5', '1', '3'), new ResourceAccessory('7', '', '')],
            $accessory->Resources()
        );
        $this->assertNull($accessory->Resources()[1]->MinQuantity);
        $this->assertNull($accessory->Resources()[1]->MaxQuantity);
    }

    public function testRemovesAllAccessoryResourcesWhenNoneAreSelected(): void
    {
        $accessory = new Accessory(1, 'accessory', 10);
        $accessory->ChangeResources([new ResourceAccessory('5', '1', '3')]);

        $this->page->method('GetAccessoryId')->willReturn(1);
        $this->page->method('GetAccessoryResources')->willReturn([]);
        $this->page->method('GetAccessoryResourcesMinimums')->willReturn([5 => '1']);
        $this->page->method('GetAccessoryResourcesMaximums')->willReturn([5 => '3']);
        $this->accessoryRepository->method('LoadById')->willReturn($accessory);
        $this->accessoryRepository->expects($this->once())->method('Update');

        $this->presenter->ChangeAccessoryResources();

        $this->assertSame([], $accessory->Resources());
    }

    /**
     * @return array<string, array{mixed, mixed, mixed}>
     */
    public static function malformedAccessoryResourcesProvider(): array
    {
        return [
            // resource=5&min=1&max=3
            'everything sent as a single value' => ['5', '1', '3'],
            // The quantities must be keyed by resource ID.
            'quantities sent as single values' => [['5'], '1', '3'],
            'quantities not keyed by resource' => [['5'], ['1'], ['3']],
            'no minimum for the resource' => [['5'], [6 => '1'], [5 => '3']],
            'no maximum for the resource' => [['5'], [5 => '1'], [6 => '3']],
            'no quantities at all' => [['5'], [], []],
            'nested minimum' => [['5'], [5 => ['1']], [5 => '3']],
            'nested maximum' => [['5'], [5 => '1'], [5 => ['3']]],
            'nested resource' => [[['5']], [5 => '1'], [5 => '3']],
            'second resource has no quantities' => [['5', '7'], [5 => '1'], [5 => '3']],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('malformedAccessoryResourcesProvider')]
    public function testMalformedAccessoryResourcesChangeNothing(mixed $resources, mixed $min, mixed $max): void
    {
        $existing = [new ResourceAccessory('9', '2', '4')];
        $accessory = new Accessory(1, 'accessory', 10);
        $accessory->ChangeResources($existing);

        $this->page->method('GetAccessoryId')->willReturn(1);
        $this->page->method('GetAccessoryResources')->willReturn($resources);
        $this->page->method('GetAccessoryResourcesMinimums')->willReturn($min);
        $this->page->method('GetAccessoryResourcesMaximums')->willReturn($max);
        $this->accessoryRepository->method('LoadById')->willReturn($accessory);
        $this->accessoryRepository->expects($this->never())->method('Update');

        $this->presenter->ChangeAccessoryResources();

        $this->assertSame($existing, $accessory->Resources());
    }
}
