<?php

require_once(__DIR__ . '/../../../lib/Common/namespace.php');

use PHPUnit\Framework\TestCase;
use LibreBooking\Common\Templating\TemplateRenderer;

class SmartyRendererTest extends TestCase
{
    public function testIsATemplateRenderer(): void
    {
        $renderer = new SmartyRenderer();
        $this->assertInstanceOf(TemplateRenderer::class, $renderer);
    }

    public function testAssignAndGetRoundTrip(): void
    {
        $renderer = new SmartyRenderer();
        $renderer->assign('Foo', 'bar');
        $this->assertSame('bar', $renderer->getTemplateVars('Foo'));
    }

    public function testExposesUnderlyingSmartyPage(): void
    {
        $renderer = new SmartyRenderer();
        $this->assertInstanceOf(SmartyPage::class, $renderer->smarty());
    }
}
