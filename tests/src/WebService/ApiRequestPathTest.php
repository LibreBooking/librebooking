<?php

declare(strict_types=1);

namespace LibreBooking\Tests\WebService;

use LibreBooking\WebService\ApiRequestPath;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ApiRequestPathTest extends TestCase
{
    #[DataProvider('provideRequests')]
    public function testSplitsTheRequestUri(
        string $scriptName,
        string $requestUri,
        string $expectedBasePath,
        string $expectedPathInfo,
    ): void {
        $path = ApiRequestPath::fromServer(server: ['SCRIPT_NAME' => $scriptName, 'REQUEST_URI' => $requestUri]);

        $this->assertSame($expectedBasePath, $path->basePath);
        $this->assertSame($expectedPathInfo, $path->pathInfo);
    }

    /**
     * @return array<string, array{string, string, string, string}>
     */
    public static function provideRequests(): array
    {
        return [
            'script in URL' => [
                '/Web/Services/index.php',
                '/Web/Services/index.php/Users/5',
                '/Web/Services/index.php',
                '/Users/5',
            ],
            'rewritten URL' => [
                '/Web/Services/index.php',
                '/Web/Services/Users/5',
                '/Web/Services',
                '/Users/5',
            ],
            'subdirectory, script in URL' => [
                '/booking/Web/Services/index.php',
                '/booking/Web/Services/index.php/Users/',
                '/booking/Web/Services/index.php',
                '/Users/',
            ],
            'subdirectory, rewritten URL' => [
                '/booking/Web/Services/index.php',
                '/booking/Web/Services/Users/',
                '/booking/Web/Services',
                '/Users/',
            ],
            'script in the web root, script in URL' => [
                '/index.php',
                '/index.php/Users/5',
                '/index.php',
                '/Users/5',
            ],
            'script in the web root, rewritten URL' => [
                '/index.php',
                '/Users/5',
                '',
                '/Users/5',
            ],
            'script only' => [
                '/Web/Services/index.php',
                '/Web/Services/index.php',
                '/Web/Services/index.php',
                '/',
            ],
            'directory only' => [
                '/Web/Services/index.php',
                '/Web/Services/',
                '/Web/Services',
                '/',
            ],
            'directory without trailing slash' => [
                '/Web/Services/index.php',
                '/Web/Services',
                '/Web/Services',
                '/',
            ],
            'query string, script in URL' => [
                '/Web/Services/index.php',
                '/Web/Services/index.php/Reservations/?userId=5&next=/Users/abc',
                '/Web/Services/index.php',
                '/Reservations/',
            ],
            'query string, rewritten URL' => [
                '/Web/Services/index.php',
                '/Web/Services/Reservations/?userId=5',
                '/Web/Services',
                '/Reservations/',
            ],
            'query string directly after the script' => [
                '/Web/Services/index.php',
                '/Web/Services/index.php?x=1',
                '/Web/Services/index.php',
                '/',
            ],
        ];
    }

    public function testMissingServerValuesGiveTheRootPath(): void
    {
        $path = ApiRequestPath::fromServer(server: []);

        $this->assertSame('', $path->basePath);
        $this->assertSame('/', $path->pathInfo);
    }
}
