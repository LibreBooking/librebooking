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

    #[DataProvider('formIntListProvider')]
    public function testGetFormIntListParsesSingleAndMultipleValues(mixed $value, array $expected): void
    {
        $this->fakeServer->SetForm('ids', $value);

        $this->assertSame($expected, $this->CreateFormReadingPage()->ReadFormIntList(key: 'ids'));
    }

    /**
     * @return array<string, array{0: mixed, 1: int[]}>
     */
    public static function formIntListProvider(): array
    {
        return [
            'missing' => [null, []],
            'empty' => ['', []],
            'single value' => ['5', [5]],
            'list' => [['5', '6'], [5, 6]],
            'negative kept' => [['-1', '2'], [-1, 2]],
            'invalid values dropped' => [['5', 'abc', '', '1.5', '6'], [5, 6]],
            'nested array dropped' => [['5', ['6']], [5]],
            'out of range dropped' => [['9223372036854775808', '7'], [7]],
        ];
    }

    #[DataProvider('querystringIntListProvider')]
    public function testGetQuerystringIntListParsesSingleAndMultipleValues(mixed $value, array $expected): void
    {
        $this->fakeServer->SetQuerystring('ids', $value);

        $this->assertSame($expected, $this->CreateFormReadingPage()->ReadQuerystringIntList(key: 'ids'));
    }

    /**
     * @return array<string, array{0: mixed, 1: int[]}>
     */
    public static function querystringIntListProvider(): array
    {
        return [
            'missing' => [null, []],
            'empty' => ['', []],
            'single value' => ['5', [5]],
            'list' => [['5', '6'], [5, 6]],
            'keys discarded' => [[3 => '5', 9 => '6'], [5, 6]],
            'negative dropped' => [['-1', '2'], [2]],
            'invalid values dropped' => [['5', 'abc', '', '1.5', '6'], [5, 6]],
            'nested array dropped' => [['5', ['6']], [5]],
            'out of range dropped' => [['9223372036854775808', '7'], [7]],
        ];
    }

    #[DataProvider('stringProvider')]
    public function testGetFormStringReturnsOnlySingleStrings(mixed $value, ?string $expected): void
    {
        $this->fakeServer->SetForm('name', $value);

        $this->assertSame($expected, $this->CreateFormReadingPage()->ReadFormString(key: 'name'));
    }

    #[DataProvider('stringProvider')]
    public function testGetQuerystringStringReturnsOnlySingleStrings(mixed $value, ?string $expected): void
    {
        $this->fakeServer->SetQuerystring('name', $value);

        $this->assertSame($expected, $this->CreateFormReadingPage()->ReadQuerystringString(key: 'name'));
    }

    /**
     * @return array<string, array{0: mixed, 1: string|null}>
     */
    public static function stringProvider(): array
    {
        return [
            'text' => ['Room 1', 'Room 1'],
            'empty kept' => ['', ''],
            'zero kept' => ['0', '0'],
            'missing' => [null, null],
            'array' => [['Room 1'], null],
            'empty array' => [[], null],
            'nested array' => [[['Room 1']], null],
        ];
    }

    #[DataProvider('formFirstStringProvider')]
    public function testGetFormFirstStringUsesFirstItemOfList(mixed $value, ?string $expected): void
    {
        $this->fakeServer->SetForm('value', $value);

        $this->assertSame($expected, $this->CreateFormReadingPage()->ReadFormFirstString(key: 'value'));
    }

    /**
     * @return array<string, array{0: mixed, 1: string|null}>
     */
    public static function formFirstStringProvider(): array
    {
        return [
            'text' => ['yes', 'yes'],
            'empty kept' => ['', ''],
            'missing' => [null, null],
            'single item list' => [['1'], '1'],
            'first of several' => [['a', 'b'], 'a'],
            'first by position, not key' => [[5 => 'a', 2 => 'b'], 'a'],
            'empty list' => [[], null],
            'nested list' => [[['a']], null],
        ];
    }

    #[DataProvider('formStringListProvider')]
    public function testGetFormStringListKeepsStringsAndKeys(mixed $value, array $expected): void
    {
        $this->fakeServer->SetForm('names', $value);

        $this->assertSame($expected, $this->CreateFormReadingPage()->ReadFormStringList(key: 'names'));
    }

    /**
     * @return array<string, array{0: mixed, 1: array<array-key, string>}>
     */
    public static function formStringListProvider(): array
    {
        return [
            'missing' => [null, []],
            'empty' => ['', []],
            'single value' => ['a', ['a']],
            'zero kept' => ['0', ['0']],
            'list' => [['a', 'b'], ['a', 'b']],
            'empty items kept' => [['a', '', 'b'], ['a', '', 'b']],
            'keys kept' => [[7 => 'a', 'x' => 'b'], [7 => 'a', 'x' => 'b']],
            'nested array dropped' => [['a', ['b'], 'c'], [0 => 'a', 2 => 'c']],
        ];
    }

    #[DataProvider('fontProvider')]
    public function testConfiguredFontRendersLocalStylesheet(string $font, string $stylesheet, bool $useLocalAssets): void
    {
        $this->fakeConfig->SetKey(configDef: ConfigKeys::CSS_FONT, value: $font);
        $this->fakeConfig->SetKey(configDef: ConfigKeys::USE_LOCAL_JS_LIBS, value: $useLocalAssets);

        $output = $this->renderHeader();

        $this->assertStringContainsString("data-font='{$font}'", $output);
        $this->assertStringContainsString("../../{$stylesheet}?v=", $output);
        $this->assertSame(1, substr_count(haystack: $output, needle: 'assets/fonts/'));
        $this->assertStringNotContainsString('fonts.googleapis.com', $output);
        $this->assertStringNotContainsString('fonts.gstatic.com', $output);
        $this->assertFileExists(ROOT_DIR . 'Web/' . $stylesheet);

        $css = file_get_contents(filename: ROOT_DIR . 'Web/' . $stylesheet);
        $this->assertNotFalse($css);
        $this->assertGreaterThan(0, preg_match_all(
            pattern: '/url\([\'"]?\.\/([^\'")]+\.woff2)[\'"]?\)/',
            subject: $css,
            matches: $matches
        ));
        foreach (array_unique(array: $matches[1]) as $fontFile) {
            $this->assertFileExists(dirname(path: ROOT_DIR . 'Web/' . $stylesheet) . '/' . $fontFile);
        }
    }

    /**
     * @return array<string, array{0: string, 1: string, 2: bool}>
     */
    public static function fontProvider(): array
    {
        $cases = [];
        foreach (self::fontStylesheetProvider() as [$font, $stylesheet]) {
            $cases[$font . ' local'] = [$font, $stylesheet, true];
            $cases[$font . ' CDN'] = [$font, $stylesheet, false];
        }
        return $cases;
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function fontStylesheetProvider(): array
    {
        $cases = [];
        foreach (array_filter(array: Page::FONT_STYLESHEETS) as $font => $stylesheet) {
            $cases[$font] = [$font, $stylesheet];
        }
        return $cases;
    }

    public function testMissingFontUsesDefault(): void
    {
        $output = $this->renderHeader();

        $this->assertStringContainsString("data-font='inter'", $output);
        $this->assertStringContainsString('../../assets/fonts/inter/v20/inter.css?v=', $output);
    }

    #[DataProvider('localAssetsProvider')]
    public function testBootstrapFontDoesNotLoadBundledFonts(bool $useLocalAssets): void
    {
        $this->fakeConfig->SetKey(configDef: ConfigKeys::CSS_FONT, value: 'none');
        $this->fakeConfig->SetKey(configDef: ConfigKeys::USE_LOCAL_JS_LIBS, value: $useLocalAssets);

        $output = $this->renderHeader();

        $this->assertStringContainsString("data-font='none'", $output);
        $this->assertStringNotContainsString('assets/fonts/', $output);
        $this->assertStringNotContainsString('fonts.googleapis.com', $output);
        $this->assertStringNotContainsString('fonts.gstatic.com', $output);
    }

    /**
     * @return array<string, array{0: bool}>
     */
    public static function localAssetsProvider(): array
    {
        return [
            'local' => [true],
            'CDN' => [false],
        ];
    }

    public function testFontStylesheetsMatchConfigChoices(): void
    {
        $this->assertSame(
            array_keys(array: ConfigKeys::CSS_FONT['choices']),
            array_keys(array: Page::FONT_STYLESHEETS)
        );
    }

    #[DataProvider('fontStylesheetProvider')]
    public function testApplicationCssUsesTheFontFamilyOfEachStylesheet(string $font, string $stylesheet): void
    {
        $fontCss = file_get_contents(filename: ROOT_DIR . 'Web/' . $stylesheet);
        $appCss = file_get_contents(filename: ROOT_DIR . 'Web/css/librebooking.css');
        $this->assertNotFalse($fontCss);
        $this->assertNotFalse($appCss);
        $this->assertSame(1, preg_match(
            pattern: "/font-family:\\s*'([^']+)'/",
            subject: $fontCss,
            matches: $family
        ));

        $familyPattern = preg_quote(str: $family[1], delimiter: '/');
        if ($font === ConfigKeys::CSS_FONT['default']) {
            $rule = "/var\\(--app-font,\\s*'{$familyPattern}'\\)/";
        } else {
            $fontPattern = preg_quote(str: $font, delimiter: '/');
            $rule = "/body\\[data-font='{$fontPattern}'\\]\\s*\\{\\s*--app-font:\\s*'{$familyPattern}';/";
        }
        $this->assertSame(1, preg_match(pattern: $rule, subject: $appCss), "librebooking.css does not apply '{$family[1]}' for {$font}");
    }

    public function testInvalidFontUsesDefaultAndIsLogged(): void
    {
        $this->fakeConfig->SetKey(configDef: ConfigKeys::CSS_FONT, value: '../../invalid');

        $logs = $this->captureErrorLog(testFunction: function () {
            $output = $this->renderHeader();
            $this->assertStringContainsString("data-font='inter'", $output);
            $this->assertStringContainsString('../../assets/fonts/inter/v20/inter.css?v=', $output);
            $this->assertStringNotContainsString('../../invalid', $output);
        });

        $this->assertLogMessage(
            logs: $logs,
            expectedPattern: 'Invalid css.font or LB_CSS_FONT value',
            description: 'invalid font configuration'
        );
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

            /**
             * @return int[]
             */
            public function ReadFormIntList(string $key): array
            {
                return $this->GetFormIntList(key: $key);
            }

            /**
             * @return int[]
             */
            public function ReadQuerystringIntList(string $key): array
            {
                return $this->GetQuerystringIntList(key: $key);
            }

            public function ReadFormString(string $key): ?string
            {
                return $this->GetFormString(key: $key);
            }

            public function ReadFormFirstString(string $key): ?string
            {
                return $this->GetFormFirstString(key: $key);
            }

            public function ReadQuerystringString(string $key): ?string
            {
                return $this->GetQuerystringString(key: $key);
            }

            /**
             * @return array<array-key, string>
             */
            public function ReadFormStringList(string $key): array
            {
                return $this->GetFormStringList(key: $key);
            }
        };
    }

    private function renderHeader(): string
    {
        $page = new class () extends Page {
            public function __construct()
            {
                parent::__construct(pageDepth: 2);
            }

            public function PageLoad()
            {
            }

            public function renderHeader(): string
            {
                return $this->smarty->fetch(template: 'globalheader.tpl');
            }
        };

        return $page->renderHeader();
    }
}
