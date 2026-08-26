<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../../lib/Email/namespace.php');

use LibreBooking\Common\Templating\TemplateRenderer;

class EmailMessageRendererTest extends TestBase
{
    public function testEmailUsesRenderer(): void
    {
        $msg = new class () extends EmailMessage {
            public function __construct()
            {
                parent::__construct();
            }
            public function To()
            {
                return [];
            }
            public function Subject()
            {
                return '';
            }
            public function Body()
            {
                return '';
            }
            public function exposeRenderer(): TemplateRenderer
            {
                return $this->renderer;
            }
        };
        $this->assertInstanceOf(TemplateRenderer::class, $msg->exposeRenderer());
    }
}
