<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;

require_once(ROOT_DIR . 'lib/Common/namespace.php');

class URIValidatorTest extends TestBase
{
    public function testValidPathIsAccepted()
    {
        $uri = '/Web/view-schedule.php';
        $this->assertTrue(URIScriptValidator::validate($uri));
    }

    public function testInvalidScriptInQueryTriggersRedirect()
    {
        $uri = '/Web/view-schedule.php?<script>alert(1)</script>';
        $this->assertFalse(URIScriptValidator::validate($uri));
    }

    #[DataProvider('provideAcceptedUris')]
    public function testPageIsAcceptedInEveryDeploymentLayout(string $uri): void
    {
        $this->assertTrue(URIScriptValidator::validate($uri));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function provideAcceptedUris(): array
    {
        return [
            'Web is the document root' => ['/schedule.php'],
            'Web is the document root, with query' => ['/schedule.php?sid=1&sd=2026-01-05'],
            'project root is the document root' => ['/Web/schedule.php'],
            'project root is the document root, with query' => ['/Web/calendar.php?sid=1'],
            'Web directory under a subpath' => ['/booking/schedule.php'],
            'project root under a subpath' => ['/booking/Web/schedule.php?sid=1'],
        ];
    }

    #[DataProvider('provideRejectedUris')]
    public function testUnsafeUriIsRejected(string $uri): void
    {
        $this->assertFalse(URIScriptValidator::validate($uri));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function provideRejectedUris(): array
    {
        return [
            'extra path after script' => ['/schedule.php/extra'],
            'extra path after script under Web' => ['/Web/schedule.php/extra'],
            'PHP file appended after script' => ['/schedule.php/extra.php'],
            'PHP file appended after script under Web' => ['/Web/schedule.php/extra.php'],
            'Web directory and PHP file appended after script' => ['/Web/schedule.php/Web/extra.php'],
            'markup ending in .php appended after script' => ['/Web/guest-reservation.php/"><img src=x onerror=alert(1)>.php'],
            'markup ending in .php appended after Web segment' => ['/Web/guest-reservation.php/Web/"><img src=x onerror=alert(1)>.php'],
            'encoded markup ending in .php appended after script' => ['/guest-reservation.php/%22%3E%3Cimg%20src=x%20onerror=alert(1)%3E.php'],
            'appended path with upper-case extension' => ['/schedule.PHP/extra.php'],
            'appended path with encoded dot' => ['/schedule%2Ephp/extra.php'],
            'appended path with encoded slash' => ['/schedule.php%2Fextra.php'],
            'script tag after script name' => ['/schedule.php/<script>alert(1)</script>'],
            'script tag in file name' => ['/<script>alert(1).php'],
            'encoded script tag in file name' => ['/Web/%3Cscript%3Ealert(1).php'],
            'directory instead of script' => ['/Web/'],
            'document root' => ['/'],
            'not a PHP file' => ['/Web/schedule.html'],
            'script tag in query' => ['/schedule.php?sid=<script>alert(1)</script>'],
            'event handler in query' => ['/schedule.php?sid=1" onload="alert(1)"'],
            'javascript scheme in query' => ['/Web/schedule.php?redirect=javascript:alert(1)'],
        ];
    }
}
