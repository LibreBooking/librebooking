<?php

declare(strict_types=1);

namespace LibreBooking\Tests\Common\Security;

use LibreBooking\Common\Security\SvgSanitizer;
use PHPUnit\Framework\TestCase;

class SvgSanitizerTest extends TestCase
{
    public function testKeepsPlainShapes(): void
    {
        $clean = SvgSanitizer::sanitize(svg: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><rect width="10" height="10" fill="#2B6B6B"/></svg>');

        $this->assertNotNull($clean);
        $this->assertStringContainsString('<rect', $clean);
        $this->assertStringContainsString('fill="#2B6B6B"', $clean);
    }

    public function testRemovesScriptsAndEventHandlers(): void
    {
        $clean = SvgSanitizer::sanitize(svg: '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"><script>alert(2)</script>'
            . '<a href="javascript:alert(3)"><rect width="1" height="1"/></a>'
            . '<foreignObject><iframe src="https://example.com"></iframe></foreignObject></svg>');

        $this->assertNotNull($clean);
        $this->assertStringNotContainsStringIgnoringCase('script', $clean);
        $this->assertStringNotContainsStringIgnoringCase('onload', $clean);
        $this->assertStringNotContainsStringIgnoringCase('foreignObject', $clean);
        $this->assertStringNotContainsStringIgnoringCase('iframe', $clean);
    }

    public function testRemovesRemoteReferences(): void
    {
        $clean = SvgSanitizer::sanitize(svg: '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">'
            . '<image xlink:href="https://example.com/track.png" width="1" height="1"/></svg>');

        $this->assertNotNull($clean);
        $this->assertStringNotContainsString('example.com', $clean);
    }

    public function testRejectsInputThatIsNotAnSvg(): void
    {
        $this->assertNull(SvgSanitizer::sanitize(svg: 'not xml at all'));
        $this->assertNull(SvgSanitizer::sanitize(svg: '<html><body>hi</body></html>'));
    }
}
