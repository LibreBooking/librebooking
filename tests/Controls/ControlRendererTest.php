<?php

declare(strict_types=1);

require_once ROOT_DIR . 'vendor/autoload.php';
require_once ROOT_DIR . 'lib/Common/namespace.php';
require_once ROOT_DIR . 'Controls/Control.php';

use PHPUnit\Framework\TestCase;

class ControlRendererTest extends TestCase
{
    public function testAcceptsSmartyRenderer(): void
    {
        $control = new class (new SmartyRenderer()) extends Control {
            public function PageLoad(): void
            {
            }
        };
        $this->assertInstanceOf(Control::class, $control);
    }

    public function testStillAcceptsRawSmartyPageForBackCompat(): void
    {
        $control = new class (new SmartyPage()) extends Control {
            public function PageLoad(): void
            {
            }
        };
        $this->assertInstanceOf(Control::class, $control);
    }
}
