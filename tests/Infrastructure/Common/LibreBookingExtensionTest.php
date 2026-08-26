<?php

require_once(__DIR__ . '/../../../lib/Common/namespace.php');

use PHPUnit\Framework\TestCase;

class LibreBookingExtensionTest extends TestCase
{
    public function testIsATwigExtension(): void
    {
        $ext = new LibreBookingExtension(Resources::GetInstance(), '');
        $this->assertInstanceOf(\Twig\Extension\AbstractExtension::class, $ext);
    }

    public function testConstantFunctionResolvesClassConstant(): void
    {
        $env = new \Twig\Environment(new \Twig\Loader\ArrayLoader([
            't' => "{{ constant('CustomAttributeTypes::CHECKBOX') }}",
        ]));
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), ''));
        $this->assertSame((string) CustomAttributeTypes::CHECKBOX, $env->render('t'));
    }
}
