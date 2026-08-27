<?php

use LibreBooking\Common\Templating\TemplateRenderer;
use LibreBooking\Common\Templating\TwigEnvironmentFactory;

class TwigRenderer implements TemplateRenderer
{
    private \Twig\Environment $twig;
    private array $vars = [];
    private string $rootPath;
    private Resources $resources;
    private ?SmartyRenderer $smartyFallback = null;
    public PageValidators $Validators;

    /** @var array<int|string, mixed> */
    private array $failedValidators = [];

    public function __construct(?Resources $resources = null, ?string $rootPath = null)
    {
        $this->resources = $resources ?? Resources::GetInstance();
        $this->rootPath = $rootPath ?? '';
        $base = __DIR__ . '/../../../';

        $debug = isset($_GET['debug']) ||
            !Configuration::Instance()->GetKey(ConfigKeys::CACHE_TEMPLATES, new BooleanConverter());

        $dirs = [
            $base . 'tpl',
            $base . 'lang/' . $this->resources->CurrentLanguage,
        ];

        $this->twig = TwigEnvironmentFactory::create(
            templateDirs: $dirs,
            cacheDir: $base . 'tpl_c/twig',
            debug: $debug,
        );

        $this->twig->addExtension(new LibreBookingExtension($this->resources, $this->rootPath, $this));

        // Populated fully in Task 1.x
        $this->Validators = new PageValidators($this);
    }

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
        return $this->twig->render($templateName, array_merge($this->vars, $vars));
    }

    public function display(string $templateName): void
    {
        echo $this->render($templateName);
    }

    public function fetch(string $templateName): string
    {
        return $this->render($templateName);
    }

    public function getTemplateVars(?string $name = null): mixed
    {
        if ($name === null) {
            return $this->vars;
        }
        return $this->vars[$name] ?? null;
    }

    public function fetchLocalized(
        string $templateName,
        bool $enforceCustomTemplate,
        ?string $languageCode = null
    ): string {
        // Full localized/-custom resolution implemented in Task 4.x; default path for now.
        return $this->render($templateName);
    }

    public function addTemplateDirectory(string $dir): void
    {
        /** @var \Twig\Loader\FilesystemLoader $loader */
        $loader = $this->twig->getLoader();
        if (is_dir($dir)) {
            $loader->prependPath($dir);
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
            return $this->twig->render($twigCandidate, $vars);
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
        return $this->Validators;
    }

    public function AddFailedValidation(int|string $id, mixed $validator): void
    {
        $this->failedValidators[$id] = $validator;
    }

    public function isValid(): bool
    {
        try {
            $this->Validators->Validate();
            return $this->Validators->AreAllValid();
        } catch (\Exception $ex) {
            Log::Error('Error during page validation', $ex);
            return false;
        }
    }
}
