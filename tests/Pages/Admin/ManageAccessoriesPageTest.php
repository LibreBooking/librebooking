<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/ManageAccessoriesPage.php');

class ManageAccessoriesPageTest extends TestBase
{
    public function testAccessoryResourceListsAreEmptyWhenNotSubmitted(): void
    {
        $page = $this->CreatePage();

        $this->assertSame([], $page->GetAccessoryResources());
        $this->assertSame([], $page->GetAccessoryResourcesMinimums());
        $this->assertSame([], $page->GetAccessoryResourcesMaximums());
    }

    public function testAccessoryResourceListsKeepTheirKeys(): void
    {
        // The form submits the quantities as name[resourceId]; a blank
        // quantity means no limit.
        $this->fakeServer->SetForm(FormKeys::ACCESSORY_RESOURCE, ['5', '7']);
        $this->fakeServer->SetForm(FormKeys::ACCESSORY_MIN_QUANTITY, [5 => '1', 7 => '']);
        $this->fakeServer->SetForm(FormKeys::ACCESSORY_MAX_QUANTITY, [5 => '', 7 => '3']);
        $page = $this->CreatePage();

        $this->assertSame(['5', '7'], $page->GetAccessoryResources());
        $this->assertSame([5 => '1', 7 => ''], $page->GetAccessoryResourcesMinimums());
        $this->assertSame([5 => '', 7 => '3'], $page->GetAccessoryResourcesMaximums());
    }

    /**
     * @return array<string, array{mixed, array<array-key, mixed>}>
     */
    public static function accessoryResourcesProvider(): array
    {
        return [
            'single value' => ['5', ['5']],
            'blank' => ['', []],
            // The presenter has to see a nested entry to reject the request.
            'nested entry is kept' => [['5', ['7']], ['5', ['7']]],
            'only a nested entry' => [[['7']], [['7']]],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('accessoryResourcesProvider')]
    public function testAccessoryResourcesAreReturnedAsSubmitted(mixed $submitted, array $expected): void
    {
        $this->fakeServer->SetForm(FormKeys::ACCESSORY_RESOURCE, $submitted);

        $this->assertSame($expected, $this->CreatePage()->GetAccessoryResources());
    }

    public function testNestedQuantitiesAreDropped(): void
    {
        // A dropped quantity leaves no entry for its resource, which the
        // presenter rejects.
        $this->fakeServer->SetForm(name: FormKeys::ACCESSORY_MIN_QUANTITY, value: [5 => ['1'], 7 => '2']);
        $this->fakeServer->SetForm(name: FormKeys::ACCESSORY_MAX_QUANTITY, value: [5 => '3', 7 => ['4']]);
        $page = $this->CreatePage();

        $this->assertSame([7 => '2'], $page->GetAccessoryResourcesMinimums());
        $this->assertSame([5 => '3'], $page->GetAccessoryResourcesMaximums());
    }

    /**
     * @return array<string, array{mixed, mixed, mixed}>
     */
    public static function malformedSubmissionProvider(): array
    {
        // The form submits each selected resource as resource[id]=id.
        return [
            'only a nested resource' => [[5 => ['5']], [5 => '1'], [5 => '3']],
            'a nested resource after a valid one' => [
                [5 => '5', 7 => ['7']],
                [5 => '1', 7 => ''],
                [5 => '3', 7 => ''],
            ],
            'nested minimum' => [[5 => '5'], [5 => ['1']], [5 => '3']],
            'nested maximum' => [[5 => '5'], [5 => '1'], [5 => ['3']]],
            'quantities sent as single values' => [[5 => '5'], '1', '3'],
        ];
    }

    /**
     * The presenter tests mock the page, so they cannot show what the
     * presenter receives from a real request.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('malformedSubmissionProvider')]
    public function testMalformedSubmissionIsNotSaved(mixed $resources, mixed $min, mixed $max): void
    {
        $existing = [new ResourceAccessory(resourceId: '9', minQuantity: '2', maxQuantity: '4')];
        $accessory = new Accessory(id: 1, name: 'accessory', quantityAvailable: 10);
        $accessory->ChangeResources(resources: $existing);

        $this->fakeServer->SetForm(name: FormKeys::ACCESSORY_RESOURCE, value: $resources);
        $this->fakeServer->SetForm(name: FormKeys::ACCESSORY_MIN_QUANTITY, value: $min);
        $this->fakeServer->SetForm(name: FormKeys::ACCESSORY_MAX_QUANTITY, value: $max);

        $accessoryRepository = $this->createMock(IAccessoryRepository::class);
        $accessoryRepository->method('LoadById')->willReturn($accessory);
        $accessoryRepository->expects($this->never())->method('Update');

        $this->CreatePresenter(accessoryRepository: $accessoryRepository)->ChangeAccessoryResources();

        $this->assertSame($existing, $accessory->Resources());
    }

    public function testSubmittedResourcesAreSaved(): void
    {
        $accessory = new Accessory(id: 1, name: 'accessory', quantityAvailable: 10);

        // The form submits each selected resource as resource[id]=id, and
        // both quantities for every resource.
        $this->fakeServer->SetForm(name: FormKeys::ACCESSORY_RESOURCE, value: [5 => '5']);
        $this->fakeServer->SetForm(name: FormKeys::ACCESSORY_MIN_QUANTITY, value: [5 => '1', 7 => '']);
        $this->fakeServer->SetForm(name: FormKeys::ACCESSORY_MAX_QUANTITY, value: [5 => '3', 7 => '']);

        $accessoryRepository = $this->createMock(IAccessoryRepository::class);
        $accessoryRepository->method('LoadById')->willReturn($accessory);
        $accessoryRepository->expects($this->once())->method('Update');

        $this->CreatePresenter(accessoryRepository: $accessoryRepository)->ChangeAccessoryResources();

        $this->assertEquals(
            [new ResourceAccessory(resourceId: '5', minQuantity: '1', maxQuantity: '3')],
            $accessory->Resources()
        );
    }

    public function testNoSelectionRemovesEveryResource(): void
    {
        $accessory = new Accessory(id: 1, name: 'accessory', quantityAvailable: 10);
        $accessory->ChangeResources(
            resources: [new ResourceAccessory(resourceId: '5', minQuantity: '1', maxQuantity: '3')],
        );

        // Unticked checkboxes are not submitted; the quantities still are.
        $this->fakeServer->SetForm(name: FormKeys::ACCESSORY_MIN_QUANTITY, value: [5 => '1']);
        $this->fakeServer->SetForm(name: FormKeys::ACCESSORY_MAX_QUANTITY, value: [5 => '3']);

        $accessoryRepository = $this->createMock(IAccessoryRepository::class);
        $accessoryRepository->method('LoadById')->willReturn($accessory);
        $accessoryRepository->expects($this->once())->method('Update');

        $this->CreatePresenter(accessoryRepository: $accessoryRepository)->ChangeAccessoryResources();

        $this->assertSame([], $accessory->Resources());
    }

    private function CreatePresenter(IAccessoryRepository $accessoryRepository): ManageAccessoriesPresenter
    {
        $this->fakeServer->SetQuerystring(name: QueryStringKeys::ACCESSORY_ID, value: '1');

        return new ManageAccessoriesPresenter(
            page: $this->CreatePage(),
            resourceRepository: $this->createMock(IResourceRepository::class),
            accessoryRepository: $accessoryRepository,
        );
    }

    private function CreatePage(): ManageAccessoriesPage
    {
        return new class () extends ManageAccessoriesPage {
            public function __construct()
            {
                $this->server = ServiceLocator::GetServer();
            }
        };
    }
}
