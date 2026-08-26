<?php

use LibreBooking\Common\Templating\TemplateRenderer;

class SmartyRenderer implements TemplateRenderer
{
    private SmartyPage $page;

    public function __construct(?Resources $resources = null, ?string $rootPath = null)
    {
        $this->page = new SmartyPage($resources, $rootPath);
    }

    public function smarty(): SmartyPage
    {
        return $this->page;
    }

    public function assign(string $name, mixed $value): void
    {
        $this->page->assign($name, $value);
    }

    public function render(string $templateName, array $vars = []): string
    {
        foreach ($vars as $k => $v) {
            $this->page->assign($k, $v);
        }
        return $this->page->fetch($templateName);
    }

    public function display(string $templateName): void
    {
        $this->page->display($templateName);
    }

    public function fetch(string $templateName): string
    {
        return $this->page->fetch($templateName);
    }

    public function getTemplateVars(?string $name = null): mixed
    {
        return $this->page->getTemplateVars($name);
    }

    public function fetchLocalized(
        string $templateName,
        bool $enforceCustomTemplate,
        ?string $languageCode = null
    ): string {
        return $this->page->FetchLocalized($templateName, $enforceCustomTemplate, $languageCode);
    }

    public function addTemplateDirectory(string $dir): void
    {
        $this->page->AddTemplateDirectory($dir);
    }

    public function renderControlTemplate(string $templateName, array $vars): string
    {
        $data = $this->page->createData();
        foreach ($vars as $k => $v) {
            $data->assign($k, $v);
        }
        return $this->page->createTemplate($templateName, $data)->fetch();
    }

    public function validators(): PageValidators
    {
        return $this->page->Validators;
    }

    public function isValid(): bool
    {
        return $this->page->IsValid();
    }
}
