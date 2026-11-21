<?php

declare(strict_types=1);

require_once ROOT_DIR . 'lib/Common/namespace.php';
require_once ROOT_DIR . 'Controls/Dashboard/AnnouncementsControl.php';
require_once ROOT_DIR . 'Controls/Dashboard/UpcomingReservations.php';
require_once ROOT_DIR . 'Controls/Dashboard/ResourceAvailabilityControl.php';
require_once ROOT_DIR . 'Controls/Dashboard/PastReservations.php';

use LibreBooking\Common\Templating\TemplateRenderer;
use PHPUnit\Framework\TestCase;

/**
 * Guard test: every dashboard control used by DashboardPresenter::Initialize()
 * must accept a SmartyRenderer and store it as a TemplateRenderer (not a raw SmartyPage).
 */
class DashboardPresenterRendererTest extends TestCase
{
    private function getRenderer(object $control): TemplateRenderer
    {
        $ref = new ReflectionClass($control);
        // Walk up the hierarchy to find the `renderer` property defined in Control
        while ($ref !== false) {
            if ($ref->hasProperty('renderer')) {
                $prop = $ref->getProperty('renderer');
                $prop->setAccessible(true);
                return $prop->getValue($control);
            }
            $ref = $ref->getParentClass();
        }
        $this->fail('No renderer property found on ' . get_class($control));
    }

    public function testAnnouncementsControlAcceptsSmartyRenderer(): void
    {
        $renderer = new SmartyRenderer();
        $control = new AnnouncementsControl($renderer);
        $this->assertInstanceOf(SmartyRenderer::class, $this->getRenderer($control));
    }

    public function testPastReservationsAcceptsSmartyRenderer(): void
    {
        $renderer = new SmartyRenderer();
        $control = new PastReservations($renderer);
        $this->assertInstanceOf(SmartyRenderer::class, $this->getRenderer($control));
    }

    public function testUpcomingReservationsAcceptsSmartyRenderer(): void
    {
        $renderer = new SmartyRenderer();
        $control = new UpcomingReservations($renderer);
        $this->assertInstanceOf(SmartyRenderer::class, $this->getRenderer($control));
    }

    public function testResourceAvailabilityControlAcceptsSmartyRenderer(): void
    {
        $renderer = new SmartyRenderer();
        $control = new ResourceAvailabilityControl($renderer);
        $this->assertInstanceOf(SmartyRenderer::class, $this->getRenderer($control));
    }
}
