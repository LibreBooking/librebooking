<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Page.php');

use PHPUnit\Framework\Attributes\DataProvider;

class PageTest extends TestBase
{
    private string $tempDir = '';

    public function setUp(): void
    {
        parent::setUp();
        $this->tempDir = sys_get_temp_dir() . '/librebooking_test_' . uniqid();
        mkdir($this->tempDir . '/new_upload', recursive: true);
        mkdir($this->tempDir . '/old_img', recursive: true);
        mkdir($this->tempDir . '/old_web', recursive: true);
    }

    public function tearDown(): void
    {
        // Clean up temp files
        $this->removeDirectory($this->tempDir);
        parent::tearDown();
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }

    public function testFindCustomFileReturnsNullWhenNoFilesExist(): void
    {
        $locations = [
            ['dir' => $this->tempDir . '/new_upload', 'url' => 'uploads/images'],
            ['dir' => $this->tempDir . '/old_img', 'url' => 'img'],
        ];

        $result = Page::findCustomFile(
            baseName: 'custom-logo',
            extensions: ['png', 'gif', 'jpg'],
            locations: $locations,
        );

        $this->assertNull($result);
    }

    public function testFindCustomFileFindsFileInFirstLocation(): void
    {
        touch($this->tempDir . '/new_upload/custom-logo.png');

        $locations = [
            ['dir' => $this->tempDir . '/new_upload', 'url' => 'uploads/images'],
            ['dir' => $this->tempDir . '/old_img', 'url' => 'img'],
        ];

        $result = Page::findCustomFile(
            baseName: 'custom-logo',
            extensions: ['png', 'gif', 'jpg'],
            locations: $locations,
        );

        $this->assertSame('uploads/images/custom-logo.png', $result);
    }

    public function testFindCustomFileFallsBackToSecondLocation(): void
    {
        touch($this->tempDir . '/old_img/custom-logo.gif');

        $locations = [
            ['dir' => $this->tempDir . '/new_upload', 'url' => 'uploads/images'],
            ['dir' => $this->tempDir . '/old_img', 'url' => 'img'],
        ];

        $result = Page::findCustomFile(
            baseName: 'custom-logo',
            extensions: ['png', 'gif', 'jpg'],
            locations: $locations,
        );

        $this->assertSame('img/custom-logo.gif', $result);
    }

    public function testFindCustomFileFirstLocationWinsWhenBothHaveFiles(): void
    {
        touch($this->tempDir . '/new_upload/custom-logo.png');
        touch($this->tempDir . '/old_img/custom-logo.jpg');

        $locations = [
            ['dir' => $this->tempDir . '/new_upload', 'url' => 'uploads/images'],
            ['dir' => $this->tempDir . '/old_img', 'url' => 'img'],
        ];

        $result = Page::findCustomFile(
            baseName: 'custom-logo',
            extensions: ['png', 'gif', 'jpg'],
            locations: $locations,
        );

        $this->assertSame('uploads/images/custom-logo.png', $result);
    }

    public function testFindCustomFileLaterExtensionWinsWithinSameLocation(): void
    {
        touch($this->tempDir . '/new_upload/custom-logo.png');
        touch($this->tempDir . '/new_upload/custom-logo.jpg');

        $locations = [
            ['dir' => $this->tempDir . '/new_upload', 'url' => 'uploads/images'],
        ];

        $result = Page::findCustomFile(
            baseName: 'custom-logo',
            extensions: ['png', 'gif', 'jpg'],
            locations: $locations,
        );

        $this->assertSame('uploads/images/custom-logo.jpg', $result);
    }

    public function testFindCustomFileHandlesEmptyUrlPrefix(): void
    {
        touch($this->tempDir . '/old_web/custom-favicon.ico');

        $locations = [
            ['dir' => $this->tempDir . '/new_upload', 'url' => 'uploads/images'],
            ['dir' => $this->tempDir . '/old_web', 'url' => ''],
        ];

        $result = Page::findCustomFile(
            baseName: 'custom-favicon',
            extensions: ['png', 'gif', 'jpg', 'ico'],
            locations: $locations,
        );

        $this->assertSame('custom-favicon.ico', $result);
    }

    #[DataProvider('faviconExtensionProvider')]
    public function testFindCustomFileFaviconExtensions(string $extension): void
    {
        touch($this->tempDir . '/new_upload/custom-favicon.' . $extension);

        $locations = [
            ['dir' => $this->tempDir . '/new_upload', 'url' => 'uploads/images'],
        ];

        $result = Page::findCustomFile(
            baseName: 'custom-favicon',
            extensions: ['png', 'gif', 'jpg', 'ico'],
            locations: $locations,
        );

        $this->assertSame('uploads/images/custom-favicon.' . $extension, $result);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function faviconExtensionProvider(): array
    {
        return [
            'png' => ['png'],
            'gif' => ['gif'],
            'jpg' => ['jpg'],
            'ico' => ['ico'],
        ];
    }
}
