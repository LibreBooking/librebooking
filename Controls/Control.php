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

    /**
     * Renders with the given renderer from now on, e.g. the page's Twig renderer for controls
     * that were created before the page chose its template engine.
     */
    public function UseRenderer(TemplateRenderer $renderer): void
    {
        $this->renderer = $renderer;
    }

    protected function Get($var)
    {
        // Like the Smarty data object this replaces, fall back to the page's variables
        if (array_key_exists($var, $this->data)) {
            return $this->data[$var];
        }
        return $this->renderer->getTemplateVars($var);
    }

    protected function Display($templateName)
    {
        echo $this->renderer->renderControlTemplate($templateName, $this->data);
    }

    abstract public function PageLoad();
}
