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

    #[DataProvider('querystringIntProvider')]
    public function testGetQuerystringIntAcceptsOnlyIntegerIds(mixed $value, ?int $expected): void
    {
        $this->fakeServer->SetQuerystring('id', $value);
        $page = new class () extends Page {
            public function __construct()
            {
                $this->server = ServiceLocator::GetServer();
            }

            public function PageLoad()
            {
            }

            public function ReadInt(string $key): ?int
            {
                return $this->GetQuerystringInt(key: $key);
            }
        };

        $this->assertSame($expected, $page->ReadInt(key: 'id'));
    }

    /**
     * @return array<string, array{0: mixed, 1: int|null}>
     */
    public static function querystringIntProvider(): array
    {
        return [
            'integer ID' => ['42', 42],
            'zero' => ['0', 0],
            'leading zeros' => ['007', 7],
            'multiple zeros' => ['000', 0],
            'largest integer' => [(string)PHP_INT_MAX, PHP_INT_MAX],
            'largest integer with leading zeros' => ['00' . PHP_INT_MAX, PHP_INT_MAX],
            'one past largest integer' => ['9223372036854775808', null],
            'very long digit string' => [str_repeat('9', 40), null],
            'missing' => [null, null],
            'empty' => ['', null],
            'negative' => ['-1', null],
            'decimal' => ['1.5', null],
            'prefixed' => ['r12', null],
            'trailing text' => ['12abc', null],
            'array' => [['1'], null],
        ];
    }

    #[DataProvider('formIntProvider')]
    public function testGetFormIntKeepsNegativeIntegers(mixed $value, ?int $expected): void
    {
        $this->fakeServer->SetForm('id', $value);

        $this->assertSame($expected, $this->CreateFormReadingPage()->ReadFormInt(key: 'id'));
    }

    /**
     * @return array<string, array{0: mixed, 1: int|null}>
     */
    public static function formIntProvider(): array
    {
        return [
            'integer ID' => ['42', 42],
            'zero' => ['0', 0],
            'unchanged sentinel' => ['-1', -1],
            'negative with leading zeros' => ['-007', -7],
            'negative zero' => ['-0', 0],
            'largest integer' => [(string)PHP_INT_MAX, PHP_INT_MAX],
            'smallest integer' => [(string)PHP_INT_MIN, PHP_INT_MIN],
            'one past largest integer' => ['9223372036854775808', null],
            'one past smallest integer' => ['-9223372036854775809', null],
            'missing' => [null, null],
            'empty' => ['', null],
            'minus only' => ['-', null],
            'double minus' => ['--1', null],
            'plus sign' => ['+1', null],
            'decimal' => ['1.5', null],
            'trailing text' => ['12abc', null],
            'array' => [['1'], null],
        ];
    }

    #[DataProvider('formFloatProvider')]
    public function testGetFormFloatKeepsDecimals(mixed $value, ?float $expected): void
    {
        $this->fakeServer->SetForm('credits', $value);

        $this->assertSame($expected, $this->CreateFormReadingPage()->ReadFormFloat(key: 'credits'));
    }

    /**
     * @return array<string, array{0: mixed, 1: float|null}>
     */
    public static function formFloatProvider(): array
    {
        return [
            'integer' => ['2', 2.0],
            'decimal' => ['1.5', 1.5],
            'two decimal places' => ['0.25', 0.25],
            'negative' => ['-1', -1.0],
            'missing' => [null, null],
            'empty' => ['', null],
            'not numeric' => ['abc', null],
            'trailing text' => ['1.5abc', null],
            'infinite' => ['1e999', null],
            'array' => [['1.5'], null],
        ];
    }

    private function CreateFormReadingPage(): object
    {
        return new class () extends Page {
            public function __construct()
            {
                $this->server = ServiceLocator::GetServer();
            }

            public function PageLoad()
            {
            }

            public function ReadFormInt(string $key): ?int
            {
                return $this->GetFormInt(key: $key);
            }

            public function ReadFormFloat(string $key): ?float
            {
                return $this->GetFormFloat(key: $key);
            }
        };
    }
}
