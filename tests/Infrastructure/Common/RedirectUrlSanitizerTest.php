<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');

class RedirectUrlSanitizerTest extends TestBase
{
    private const SCRIPT_URL = 'https://booked.example/Web';
    private const FALLBACK = 'dashboard.php';

    public function setUp(): void
    {
        parent::setUp();
        $this->fakeConfig->_ScriptUrl = self::SCRIPT_URL;
    }

    /**
     * @dataProvider safeRedirectProvider
     */
    public function testAllowsSafeRedirects(string $url, string $path, string $expected): void
    {
        $actual = RedirectUrlSanitizer::Sanitize($url, $path, self::SCRIPT_URL, self::FALLBACK);

        $this->assertSame($expected, $actual);
    }

    /**
     * @dataProvider blockedRedirectProvider
     */
    public function testFallsBackForUnsafeRedirects(string $url): void
    {
        $actual = RedirectUrlSanitizer::Sanitize($url, '../', self::SCRIPT_URL, self::FALLBACK);

        $this->assertSame(self::FALLBACK, $actual);
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    public static function safeRedirectProvider(): array
    {
        return [
            'plain relative path is prefixed for nested pages' => ['schedule.php', '../', '../schedule.php'],
            'current-directory relative path is prefixed for nested pages' => ['./schedule.php', '../', '.././schedule.php'],
            'parent relative path already includes prefix' => ['../dashboard.php', '../', '../dashboard.php'],
            'application-root path remains unchanged' => ['/Web/schedule.php', '../', '/Web/schedule.php'],
            'query-only redirect remains unchanged' => ['?foo=bar', '../', '?foo=bar'],
            'same-origin absolute https remains unchanged' => ['https://booked.example/Web/schedule.php', '../', 'https://booked.example/Web/schedule.php'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function blockedRedirectProvider(): array
    {
        return [
            'absolute external url' => ['https://evil.com/steal'],
            'protocol relative external url' => ['//evil.com/steal'],
            'javascript scheme' => ['javascript:alert(1)'],
            'data scheme' => ['data:text/html,alert(1)'],
            'mailto scheme' => ['mailto:test@example.com'],
            'same host different scheme' => ['http://booked.example/Web/schedule.php'],
            'same host different port' => ['https://booked.example:8443/Web/schedule.php'],
            'malformed absolute url' => ['https://[broken'],
        ];
    }
}
