<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');

class RichTextHtmlSanitizerTest extends TestBase
{
    public function testAllowsBasicRichTextFormatting(): void
    {
        $html = '<h1>Heading 1</h1><h2>Heading 2</h2><h3>Heading 3</h3>'
            . '<h4>Heading 4</h4><h5>Heading 5</h5><h6>Heading 6</h6>'
            . '<blockquote>Quote</blockquote>'
            . '<p>Text <strong>bold</strong> <b>b</b> <em>em</em> <i>i</i> <u>u</u> <del>del</del> <s>s</s></p>'
            . '<ul><li>One</li></ul><ol><li>Two</li></ol><br>';

        $actual = RichTextHtmlSanitizer::Sanitize($html);

        $this->assertSame(
            '<h1>Heading 1</h1><h2>Heading 2</h2><h3>Heading 3</h3>'
            . '<h4>Heading 4</h4><h5>Heading 5</h5><h6>Heading 6</h6>'
            . '<blockquote>Quote</blockquote>'
            . '<p>Text <strong>bold</strong> <b>b</b> <em>em</em> <i>i</i> <u>u</u> <del>del</del> <s>s</s></p>'
            . '<ul><li>One</li></ul><ol><li>Two</li></ol><br />',
            $actual
        );
    }

    public function testDecodesLegacyEntityEncodedHtmlBeforeSanitizing(): void
    {
        $html = '&lt;p&gt;Text &lt;u&gt;underlined&lt;/u&gt;&lt;/p&gt;';

        $actual = RichTextHtmlSanitizer::Sanitize($html);

        $this->assertSame('<p>Text <u>underlined</u></p>', $actual);
    }

    public function testRemovesUnsafeTagsAttributesAndUrls(): void
    {
        $html = '<p onclick="alert(1)">Text</p>'
            . '<script>alert(2)</script>'
            . '<img src=x onerror=alert(3)>'
            . '<a href="javascript:alert(4)" title="unsafe">link</a>';

        $actual = RichTextHtmlSanitizer::Sanitize($html);

        $this->assertStringContainsString('<p>Text</p>', $actual);
        $this->assertStringContainsString('<img src="x" />', $actual);
        $this->assertStringContainsString('<a title="unsafe" rel="noopener noreferrer">link</a>', $actual);
        $this->assertStringNotContainsString('<script', $actual);
        $this->assertStringNotContainsString('onclick=', $actual);
        $this->assertStringNotContainsString('onerror=', $actual);
        $this->assertStringNotContainsString('javascript:', $actual);
    }

    public function testAllowsSafeLinksAndForcesRelAttribute(): void
    {
        $html = '<a href="https://example.com" title="https" target="_blank">https</a>'
            . '<a href="http://example.com" title="http">http</a>'
            . '<a href="mailto:test@example.com" title="mail">mail</a>'
            . '<a href="/resource/1" title="relative">relative</a>';

        $actual = RichTextHtmlSanitizer::Sanitize($html);

        $this->assertStringContainsString(
            '<a href="https://example.com" title="https" target="_blank" rel="noopener noreferrer">https</a>',
            $actual
        );
        $this->assertStringContainsString(
            '<a href="http://example.com" title="http" rel="noopener noreferrer">http</a>',
            $actual
        );
        $this->assertStringContainsString(
            '<a href="mailto:test&#64;example.com" title="mail" rel="noopener noreferrer">mail</a>',
            $actual
        );
        $this->assertStringContainsString(
            '<a href="/resource/1" title="relative" rel="noopener noreferrer">relative</a>',
            $actual
        );
    }

    public function testRemovesSchemeRelativeLinkHref(): void
    {
        $html = '<a href="//evil.example/path" title="external">external</a>'
            . '<a href="/resource/1" title="relative">relative</a>'
            . '<a href="?q=1" title="query">query</a>'
            . '<a href="#section" title="anchor">anchor</a>';

        $actual = RichTextHtmlSanitizer::Sanitize($html);

        $this->assertStringContainsString(
            '<a title="external" rel="noopener noreferrer">external</a>',
            $actual
        );
        $this->assertStringContainsString(
            '<a href="/resource/1" title="relative" rel="noopener noreferrer">relative</a>',
            $actual
        );
        $this->assertStringContainsString(
            '<a href="?q&#61;1" title="query" rel="noopener noreferrer">query</a>',
            $actual
        );
        $this->assertStringContainsString(
            '<a href="#section" title="anchor" rel="noopener noreferrer">anchor</a>',
            $actual
        );
        $this->assertStringNotContainsString('//evil.example', $actual);
    }

    public function testAllowsSafeImagesAndRemovesUnsafeImageSourcesAndAttributes(): void
    {
        $html = '<img src="https://example.com/image.png" alt="Example" title="Title" width="100" height="50">'
            . '<img src="/uploads/image.png" alt="Relative">'
            . '<img src="javascript:alert(1)" alt="Bad scheme">'
            . '<img src="data:image/svg+xml;base64,PHN2ZyBvbmxvYWQ9YWxlcnQoMSk+" alt="Data">'
            . '<img src="https://example.com/bad.png" onerror="alert(2)" style="display:block">';

        $actual = RichTextHtmlSanitizer::Sanitize($html);

        $this->assertStringContainsString(
            '<img src="https://example.com/image.png" alt="Example" title="Title" width="100" height="50" />',
            $actual
        );
        $this->assertStringContainsString('<img src="/uploads/image.png" alt="Relative" />', $actual);
        $this->assertStringContainsString('<img alt="Bad scheme" />', $actual);
        $this->assertStringContainsString('<img alt="Data" />', $actual);
        $this->assertStringContainsString('<img src="https://example.com/bad.png" />', $actual);
        $this->assertStringNotContainsString('javascript:', $actual);
        $this->assertStringNotContainsString('data:', $actual);
        $this->assertStringNotContainsString('onerror=', $actual);
        $this->assertStringNotContainsString('style=', $actual);
    }

    public function testEmptyInputReturnsEmptyString(): void
    {
        $this->assertSame('', RichTextHtmlSanitizer::Sanitize(null));
        $this->assertSame('', RichTextHtmlSanitizer::Sanitize(''));
    }

    public function testZeroStringIsNotTreatedAsEmpty(): void
    {
        $this->assertSame('0', RichTextHtmlSanitizer::Sanitize('0'));
    }
}
