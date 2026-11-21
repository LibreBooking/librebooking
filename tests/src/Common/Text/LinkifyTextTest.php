<?php

declare(strict_types=1);

namespace LibreBooking\Tests\Common\Text;

use LibreBooking\Common\Text\LinkifyText;
use PHPUnit\Framework\TestCase;

class LinkifyTextTest extends TestCase
{
    public function testLinkifiesHttpUrl(): void
    {
        $result = LinkifyText::linkify('visit http://example.com today');

        $this->assertStringContainsString('<a href="http://example.com"', $result);
        $this->assertStringContainsString('rel="noopener noreferrer nofollow"', $result);
        $this->assertStringContainsString('target="_blank"', $result);
    }

    public function testLinkifiesHttpsUrl(): void
    {
        $result = LinkifyText::linkify('visit https://example.com/path today');

        $this->assertStringContainsString('<a href="https://example.com/path"', $result);
    }

    public function testDoesNotLinkifyJavascriptScheme(): void
    {
        $result = LinkifyText::linkify('click javascript://%0Aalert%281%29 now');

        $this->assertStringNotContainsString('<a', $result);
        $this->assertStringNotContainsString('href=', $result);
    }

    public function testDoesNotLinkifyFtpScheme(): void
    {
        $result = LinkifyText::linkify('x ftp://host/file y');

        $this->assertStringNotContainsString('<a', $result);
    }

    public function testDoesNotLinkifyDataScheme(): void
    {
        $result = LinkifyText::linkify('x data://text/html,x y');

        $this->assertStringNotContainsString('<a', $result);
    }

    public function testLinkifiesValidEmail(): void
    {
        $result = LinkifyText::linkify('mail user@example.com please');

        $this->assertStringContainsString('mailto:user@example.com', $result);
        $this->assertStringContainsString('<a href="mailto:user@example.com"', $result);
    }

    public function testTruncatesLongUrlsInLinkText(): void
    {
        $longUrl = 'https://example.com/some/very/long/path/here';
        $result = LinkifyText::linkify("see $longUrl now");

        $this->assertStringContainsString('<a href="' . $longUrl . '"', $result);
        $this->assertStringContainsString('...', $result);
    }

    public function testRemovesTrailingPeriodFromUrl(): void
    {
        $result = LinkifyText::linkify('see https://example.com. end');

        $this->assertStringContainsString('href="https://example.com"', $result);
        $this->assertStringNotContainsString('href="https://example.com."', $result);
    }

    public function testPlainTextWithNoUrlsIsUnchanged(): void
    {
        $input = 'no links here at all';
        $result = LinkifyText::linkify($input);

        $this->assertSame($input, $result);
    }
}
