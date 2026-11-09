<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/ManageResourcesPage.php');

use PHPUnit\Framework\Attributes\DataProvider;

class ManageResourcesPageTest extends TestBase
{
    #[DataProvider('resourceImagesProvider')]
    public function testBindsResourceImageUrls(?string $primaryImage, array $images, array $expected): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::UPLOAD_IMAGE_URL, 'http://localhost/uploads');
        $page = new class () extends ManageResourcesPage {
            public array $response = [];

            public function __construct()
            {
                $this->smarty = new SmartyPage();
            }

            public function SetJsonResponse($response): void
            {
                $this->response = $response;
            }
        };
        $resource = BookableResource::CreateNew('Room', 1);
        $resource->SetImage($primaryImage);
        $resource->SetImages($images);

        $page->BindResourceImages($resource);

        $this->assertSame($expected, $page->response);
    }

    public static function resourceImagesProvider(): array
    {
        return [
            'primary image' => ['room.png', [], [
                'image' => 'http://localhost/uploads/room.png',
                'images' => [],
            ]],
            'primary and additional images' => ['room.png', ['inside.png', 'outside.png'], [
                'image' => 'http://localhost/uploads/room.png',
                'images' => ['http://localhost/uploads/inside.png', 'http://localhost/uploads/outside.png'],
            ]],
            'no images' => [null, [], ['image' => null, 'images' => []]],
        ];
    }

    public function testBulkUpdateDropdownsKeepTheUnchangedSentinel(): void
    {
        foreach ([FormKeys::SCHEDULE_ID, FormKeys::RESOURCE_TYPE_ID, FormKeys::RESOURCE_ADMIN_GROUP_ID, FormKeys::RESOURCE_STATUS_ID, FormKeys::ENABLE_CHECK_IN, FormKeys::ALLOW_CONCURRENT_RESERVATIONS] as $key) {
            $this->fakeServer->SetForm($key, '-1');
        }
        $page = $this->CreateFormReadingPage();

        $this->assertSame(-1, $page->GetScheduleId());
        $this->assertSame(-1, $page->GetResourceTypeId());
        $this->assertSame(-1, $page->GetAdminGroupId());
        $this->assertSame(-1, $page->GetStatusId());
        $this->assertSame(-1, $page->GetBulkEnableCheckin());
        $this->assertSame(-1, $page->GetBulkAllowConcurrentReservations());
    }

    public function testCreditsKeepDecimals(): void
    {
        $this->fakeServer->SetForm(FormKeys::CREDITS, '1.5');
        $this->fakeServer->SetForm(FormKeys::PEAK_CREDITS, '0.25');
        $page = $this->CreateFormReadingPage();

        $this->assertSame(1.5, $page->GetCredits());
        $this->assertSame(0.25, $page->GetPeakCredits());
    }

    public function testCreditsDistinguishZeroFromBlank(): void
    {
        $this->fakeServer->SetForm(FormKeys::CREDITS, '0.00');
        $this->fakeServer->SetForm(FormKeys::PEAK_CREDITS, '');
        $page = $this->CreateFormReadingPage();

        $this->assertSame(0.0, $page->GetCredits());
        $this->assertNull($page->GetPeakCredits());
    }

    public function testEmptyAdminGroupAndPermissionTypeNone(): void
    {
        $this->fakeServer->SetForm(FormKeys::RESOURCE_ADMIN_GROUP_ID, '');
        $this->fakeServer->SetForm(FormKeys::PERMISSION_TYPE, (string)ResourcePermissionType::None);
        $page = $this->CreateFormReadingPage();

        $this->assertNull($page->GetAdminGroupId());
        $this->assertSame(ResourcePermissionType::None, $page->GetPermissionType());
    }

    public function testResourceIdFallsBackFromQuerystringToForm(): void
    {
        $this->fakeServer->SetForm(FormKeys::PK, '42');
        $this->assertSame(42, $this->CreateFormReadingPage()->GetResourceId());

        $this->fakeServer->SetQuerystring(QueryStringKeys::RESOURCE_ID, '7');
        $this->assertSame(7, $this->CreateFormReadingPage()->GetResourceId());
    }

    private function CreateFormReadingPage(): ManageResourcesPage
    {
        return new class () extends ManageResourcesPage {
            public function __construct()
            {
                $this->server = ServiceLocator::GetServer();
            }
        };
    }
}
