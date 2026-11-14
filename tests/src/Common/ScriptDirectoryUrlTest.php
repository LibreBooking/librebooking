<?php

declare(strict_types=1);

namespace LibreBooking\Tests\Common;

use LibreBooking\Common\ScriptDirectoryUrl;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ScriptDirectoryUrlTest extends TestCase
{
    #[DataProvider('provideScriptNames')]
    public function testBuildsUrlInTheDirectoryOfTheScript(string $scriptName, string $page, string $expected): void
    {
        $url = ScriptDirectoryUrl::forPage(scriptName: $scriptName, page: $page);

        $this->assertSame($expected, $url);
        // A leading "//" would make the browser read the next segment as a host name.
        $this->assertStringStartsWith('/', $url);
        $this->assertStringStartsNotWith('//', $url);
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    public static function provideScriptNames(): array
    {
        return [
            'Web is the document root' => ['/schedule.php', '/schedule.php', '/schedule.php'],
            'Web is the document root, other page' => ['/guest-reservation.php', '/view-schedule.php', '/view-schedule.php'],
            'project root is the document root' => ['/Web/schedule.php', '/schedule.php', '/Web/schedule.php'],
            'Web directory under a subpath' => ['/booking/schedule.php', '/schedule.php', '/booking/schedule.php'],
            'project root under a subpath' => ['/booking/Web/calendar.php', '/calendar.php', '/booking/Web/calendar.php'],
            'page without leading slash' => ['/Web/schedule.php', 'schedule.php', '/Web/schedule.php'],
            'page with repeated leading slashes' => ['/schedule.php', '//schedule.php', '/schedule.php'],
            'script name with repeated leading slashes' => ['//schedule.php', '/schedule.php', '/schedule.php'],
            'script name without directory' => ['schedule.php', '/schedule.php', '/schedule.php'],
            'empty script name' => ['', '/schedule.php', '/schedule.php'],
            'Windows directory separator' => ['\\schedule.php', '/schedule.php', '/schedule.php'],
        ];
    }
}
