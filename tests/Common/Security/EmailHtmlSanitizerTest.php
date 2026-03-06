<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/Security/EmailHtmlSanitizer.php');

class EmailHtmlSanitizerTest extends TestBase
{
    public function testAllowsBasicFormattingTags(): void
    {
        $input = '<p>Test <u>underline</u> <strong>bold</strong></p>';

        $actual = EmailHtmlSanitizer::Sanitize($input);

        $this->assertSame($input, $actual);
    }

    public function testRemovesDangerousContent(): void
    {
        $input = '<p onclick="alert(1)">Click</p><script>alert(2)</script><a href="javascript:alert(3)">x</a>';

        $actual = EmailHtmlSanitizer::Sanitize($input);

        $this->assertStringNotContainsString('<script', $actual);
        $this->assertStringNotContainsString('onclick=', $actual);
        $this->assertStringNotContainsString('javascript:', $actual);
    }

    public function testDecodesLegacyEncodedHtmlBeforeSanitizing(): void
    {
        $input = '&lt;p&gt; Test &lt;u&gt;sdfsdf&lt;/u&gt;&lt;/p&gt;';

        $actual = EmailHtmlSanitizer::Sanitize($input);

        $this->assertSame('<p> Test <u>sdfsdf</u></p>', $actual);
    }
}
