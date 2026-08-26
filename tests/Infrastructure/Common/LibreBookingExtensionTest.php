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

    public function testTranslateReturnsResourceString(): void
    {
        $env = new \Twig\Environment(new \Twig\Loader\ArrayLoader(['t' => "{{ translate('Yes') }}"]));
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), ''));
        $this->assertSame(Resources::GetInstance()->GetString('Yes'), $env->render('t'));
    }

    public function testTranslateWithArgsExplodesStringAndPassesToGetString(): void
    {
        $resources = Resources::GetInstance();
        $env = new \Twig\Environment(new \Twig\Loader\ArrayLoader(['t' => "{{ translate('Yes') }}"]));
        $env->addExtension(new LibreBookingExtension($resources, ''));

        // Without args path: GetString('Yes', '')
        $expected = $resources->GetString('Yes', '');
        $this->assertSame($expected, $env->render('t'));
    }

    public function testTranslateWithStringArgsExplodesOnComma(): void
    {
        $resources = Resources::GetInstance();
        // Use a template that passes args as string; the result is forwarded to GetString with exploded array
        $env = new \Twig\Environment(new \Twig\Loader\ArrayLoader([
            't' => "{{ translate('Yes', 'a,b') }}",
        ]));
        $env->addExtension(new LibreBookingExtension($resources, ''));
        $expected = $resources->GetString('Yes', ['a', 'b']);
        $this->assertSame($expected, $env->render('t'));
    }

    public function testTranslateWithArrayArgsPassesDirectly(): void
    {
        $resources = Resources::GetInstance();
        $env = new \Twig\Environment(new \Twig\Loader\ArrayLoader([
            't' => "{{ translate('Yes', ['x']) }}",
        ]));
        $env->addExtension(new LibreBookingExtension($resources, ''));
        $expected = $resources->GetString('Yes', ['x']);
        $this->assertSame($expected, $env->render('t'));
    }
}
