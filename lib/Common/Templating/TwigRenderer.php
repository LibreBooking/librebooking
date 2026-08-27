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
    private ?SmartyRenderer $smartyFallback = null;

    /**
     * @param PageValidators|null $validators the validators of the page being
     *                                        rendered, when it already has some
     */
    public function __construct(
        ?Resources $resources = null,
        ?string $rootPath = null,
        ?PageValidators $validators = null
    ) {
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

        $this->validators = $validators ?? new PageValidators();
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

    /**
     * Twig is the primary engine; Smarty is only built the first time a template
     * has no .twig counterpart. The variables assigned so far are replayed into it.
     */
    private function smartyFallback(): SmartyRenderer
    {
        if ($this->smartyFallback === null) {
            $this->smartyFallback = new SmartyRenderer($this->resources, $this->rootPath);
            foreach ($this->vars as $name => $value) {
                $this->smartyFallback->assign($name, $value);
            }
        }

        return $this->smartyFallback;
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
        // Compute the .twig candidate: replace trailing .tpl with .twig, or use as-is.
        $twigCandidate = str_ends_with($templateName, '.tpl')
            ? substr($templateName, 0, -4) . '.twig'
            : $templateName;

        /** @var \Twig\Loader\FilesystemLoader $loader */
        $loader = $this->twig->getLoader();
        if ($loader->exists($twigCandidate)) {
            // Page variables stay visible to control templates, as with Smarty's parent data
            return $this->twig->render($twigCandidate, array_merge($this->vars, $vars));
        }

        // No .twig template found — fall back to Smarty rendering the original .tpl.
        return $this->smartyFallback()->renderControlTemplate($templateName, $vars);
    }

    /**
     * Renders a partial template by name using engine-selecting fallback,
     * with full-page Smarty context (equivalent to Smarty {include}).
     *
     * Used by render_partial() in LibreBookingExtension.  Unlike renderControlTemplate()
     * (which creates an isolated data scope for Controls), this method renders via the
     * shared Smarty page context so that {function} definitions compiled into the
     * Smarty page are available to the sub-template — matching the behaviour of
     * Smarty's {include file="..."} in the parent template.
     *
     * If a .twig counterpart exists, it is rendered via the Twig environment instead.
     */
    public function renderPartial(string $templateName, array $vars): string
    {
        // Compute the .twig candidate: replace trailing .tpl with .twig, or use as-is.
        $twigCandidate = str_ends_with($templateName, '.tpl')
            ? substr($templateName, 0, -4) . '.twig'
            : $templateName;

        /** @var \Twig\Loader\FilesystemLoader $loader */
        $loader = $this->twig->getLoader();
        if ($loader->exists($twigCandidate)) {
            return $this->twig->render($twigCandidate, $vars);
        }

        // No .twig counterpart yet — fall back to Smarty using full-page context.
        // SmartyRenderer::render() assigns vars to the main SmartyPage and calls
        // fetch(), which replicates Smarty {include} shared-context semantics.
        return $this->smartyFallback()->render($templateName, $vars);
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
