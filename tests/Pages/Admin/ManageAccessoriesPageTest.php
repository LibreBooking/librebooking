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
     * @return array<string, array{mixed, string[]}>
     */
    public static function accessoryResourcesProvider(): array
    {
        return [
            'single value' => ['5', ['5']],
            'blank' => ['', []],
            'nested list is dropped' => [['5', ['7']], ['5']],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('accessoryResourcesProvider')]
    public function testAccessoryResourcesAreStrings(mixed $submitted, array $expected): void
    {
        $this->fakeServer->SetForm(FormKeys::ACCESSORY_RESOURCE, $submitted);

        $this->assertSame($expected, $this->CreatePage()->GetAccessoryResources());
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
