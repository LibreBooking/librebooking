<?php

use LibreBooking\Common\Templating\TemplateRenderer;
use LibreBooking\Common\Templating\TwigEnvironmentFactory;

/**
 * Renders templates with Twig.
 *
 * Templates are looked up in tpl/ and in the directory of the language that
 * was current when the renderer was created.
 */
class TwigRenderer implements TemplateRenderer
{
    private \Twig\Environment $twig;

    /**
     * @var array<string, mixed>
     */
    private array $vars = [];

    private PageValidators $validators;
    private Resources $resources;
    private string $rootPath;

    public function __construct(?Resources $resources = null, ?string $rootPath = null)
    {
        $resources ??= Resources::GetInstance();
        $this->resources = $resources;
        $this->rootPath = $rootPath ?? '';
        $config = Configuration::Instance();

        // ?debug only counts in debug mode, otherwise any visitor could switch off the template cache
        $debugRequested = isset($_GET['debug']) &&
            $config->GetKey(configDef: ConfigKeys::APP_DEBUG, converter: new BooleanConverter());
        $cacheTemplates = $config->GetKey(configDef: ConfigKeys::CACHE_TEMPLATES, converter: new BooleanConverter());

        $this->twig = TwigEnvironmentFactory::create(
            templateDirs: [
                ROOT_DIR . 'tpl',
                ROOT_DIR . 'lang/' . $resources->CurrentLanguage,
            ],
            cacheDir: ROOT_DIR . 'tpl_c/twig',
            debug: $debugRequested || !$cacheTemplates
        );

        $this->twig->addExtension(new LibreBookingExtension($this->resources, $this->rootPath, $this));

        $this->validators = new PageValidators();
    }

    /**
     * Returns the Twig environment, for callers that need to configure it.
     */
    public function environment(): \Twig\Environment
    {
        return $this->twig;
    }

    public function assign(string $name, mixed $value): void
    {
        $this->vars[$name] = $value;
    }

    public function render(string $templateName, array $vars = []): string
    {
        return $this->twig->render(name: $templateName, context: array_merge($this->vars, $vars));
    }

    public function display(string $templateName): void
    {
        echo $this->render(templateName: $templateName);
    }

    public function fetch(string $templateName): string
    {
        return $this->render(templateName: $templateName);
    }

    public function getTemplateVars(?string $name = null): mixed
    {
        if ($name === null) {
            return $this->vars;
        }
        return $this->vars[$name] ?? null;
    }

    /**
     * Not implemented yet. The localized lookup is added together with the
     * first email templates.
     */
    public function fetchLocalized(string $templateName, bool $enforceCustomTemplate): string
    {
        throw new LogicException('TwigRenderer does not render localized templates yet');
    }

    /**
     * The directory is searched after those already known, as with Smarty, so
     * it cannot replace a template that an earlier directory has.
     */
    public function addTemplateDirectory(string $dir): void
    {
        $loader = $this->twig->getLoader();
        if ($loader instanceof \Twig\Loader\FilesystemLoader && is_dir(filename: $dir)) {
            $loader->addPath(path: $dir);
        }
    }

    public function renderControlTemplate(string $templateName, array $vars): string
    {
        return $this->twig->render(name: $templateName, context: $vars);
    }

    public function validators(): PageValidators
    {
        return $this->validators;
    }

    public function isValid(): bool
    {
        try {
            $this->validators->Validate();
            return $this->validators->AreAllValid();
        } catch (\Exception $ex) {
            Log::Error(message: 'Error during page validation: %s', args: $ex);
            return false;
        }
    }
}
