<?php

use LibreBooking\Common\Templating\TemplateRenderer;

abstract class Control
{
    protected TemplateRenderer $renderer;

    /**
     * @var string
     */
    protected $id = null;

    /**
     * @var array<string,mixed>
     */
    protected array $data = [];

    /**
     * @param TemplateRenderer|SmartyPage $renderer
     */
    public function __construct(TemplateRenderer|SmartyPage $renderer)
    {
        // BC: callers (e.g. SmartyPage::DisplayControl) still pass a raw SmartyPage.
        $this->renderer = $renderer instanceof SmartyPage
            ? SmartyRenderer::wrap($renderer)
            : $renderer;
        $this->id = uniqid();
    }

    public function Set($var, $value)
    {
        $this->data[$var] = $value;
    }

    protected function Get($var)
    {
        return $this->data[$var] ?? null;
    }

    protected function Display($templateName)
    {
        echo $this->renderer->renderControlTemplate($templateName, $this->data);
    }

    abstract public function PageLoad();
}
