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
}
