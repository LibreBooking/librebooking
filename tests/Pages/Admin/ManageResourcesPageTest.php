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
}
