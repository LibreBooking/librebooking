<?php

declare(strict_types=1);

namespace LibreBooking\Tests\Common\Templating;

use LibreBooking\Common\Templating\TemplateRenderer;
use PHPUnit\Framework\TestCase;

class TemplateRendererTest extends TestCase
{
    public function testInterfaceDefinesRenderingSurface(): void
    {
        $reflection = new \ReflectionClass(TemplateRenderer::class);
        $this->assertTrue($reflection->isInterface());
        foreach (['assign', 'render', 'display', 'fetch', 'getTemplateVars',
            'fetchLocalized', 'addTemplateDirectory', 'renderControlTemplate',
            'validators', 'isValid'] as $method) {
            $this->assertTrue($reflection->hasMethod($method), "missing $method");
        }
    }
}
