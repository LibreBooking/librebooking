<?php

use LibreBooking\Common\Templating\TemplateRenderer;

/**
 * Renders templates with Smarty by passing every call through to a SmartyPage.
 */
class SmartyRenderer implements TemplateRenderer
{
    private SmartyPage $page;

    /**
     * The language that was current when the renderer was created, which is
     * the one it serves.
     */
    private string $language;

    public function __construct(?Resources $resources = null, ?string $rootPath = null)
    {
        $resources ??= Resources::GetInstance();

        $this->language = self::languageOf(resources: $resources);
        $this->page = new SmartyPage(resources: $resources, RootPath: $rootPath);
    }

    /**
     * Wrap an existing SmartyPage instance without constructing a new one.
     * Preserves the page's template directories and already-assigned variables.
     *
     * The page does not expose the language it was created for, so the
     * renderer serves the language that is current when the page is wrapped.
     */
    public static function wrap(SmartyPage $page): self
    {
        $instance = (new \ReflectionClass(self::class))->newInstanceWithoutConstructor();
        $instance->page = $page;
        $instance->language = self::languageOf(resources: Resources::GetInstance());
        return $instance;
    }

    /**
     * No language is loaded when the configured one is not supported.
     * SmartyPage treats an empty language like a missing one.
     */
    private static function languageOf(Resources $resources): string
    {
        $language = $resources->CurrentLanguage;

        return is_string(value: $language) ? $language : '';
    }

    /**
     * Returns the wrapped page, for callers that still need methods only
     * SmartyPage offers.
     */
    public function smarty(): SmartyPage
    {
        return $this->page;
    }

    public function assign(string $name, mixed $value): void
    {
        $this->page->assign(tpl_var: $name, value: $value);
    }

    public function render(string $templateName, array $vars = []): string
    {
        foreach ($vars as $k => $v) {
            $this->page->assign(tpl_var: $k, value: $v);
        }
        return $this->page->fetch(template: $templateName);
    }

    public function display(string $templateName): void
    {
        $this->page->display(template: $templateName);
    }

    public function fetch(string $templateName): string
    {
        return $this->page->fetch(template: $templateName);
    }

    public function getTemplateVars(?string $name = null): mixed
    {
        return $this->page->getTemplateVars(varName: $name);
    }

    public function fetchLocalized(string $templateName, bool $enforceCustomTemplate): string
    {
        // Without the language, SmartyPage falls back to the CurrentLanguage
        // variable and, when that is not assigned, misses "-custom" templates
        return $this->page->FetchLocalized(
            templateName: $templateName,
            enforceCustomTemplate: $enforceCustomTemplate,
            languageCode: $this->language
        );
    }

    public function addTemplateDirectory(string $dir): void
    {
        $this->page->AddTemplateDirectory(templateDirectory: $dir);
    }

    public function renderControlTemplate(string $templateName, array $vars): string
    {
        $data = $this->page->createData();
        foreach ($vars as $k => $v) {
            $data->assign(tpl_var: $k, value: $v);
        }
        return $this->page->createTemplate(template_name: $templateName, parent: $data)->fetch();
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
