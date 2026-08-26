<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Page.php');

use LibreBooking\Common\Templating\TemplateRenderer;

class PageRendererTest extends TestBase
{
    public function testPageUsesATemplateRenderer(): void
    {
        // A concrete trivial Page subclass for the test.
        // TestBase::setUp() installs all the fakes (ServiceLocator, Configuration,
        // Resources, PluginManager) that Page::__construct() depends on, so the
        // anonymous subclass below can call parent::__construct('') safely.
        $page = new class () extends Page {
            public function __construct()
            {
                parent::__construct('');
            }
            public function PageLoad(): void
            {
            }
            public function exposeRenderer(): TemplateRenderer
            {
                return $this->renderer;
            }
        };
        $this->assertInstanceOf(TemplateRenderer::class, $page->exposeRenderer());
    }
}
