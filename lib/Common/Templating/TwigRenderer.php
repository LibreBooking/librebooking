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
    /**
     * Twig namespace that holds the directory fetchLocalized() resolved.
     */
    private const LOCALIZED_NAMESPACE = 'localized';

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
        // Keep the Smarty fallback in sync so that when fetch()/fetchLocalized()
        // delegate to it, the assigned variables are available in the template.
        $this->smartyFallback?->assign($name, $value);
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
        if (!TemplateEngineOverride::TwigAllowed()) {
            return $this->smartyFallback()->fetch($templateName);
        }

        // Compute the .twig candidate: replace trailing .tpl with .twig, or use as-is.
        $twigCandidate = str_ends_with($templateName, '.tpl')
            ? substr($templateName, 0, -4) . '.twig'
            : $templateName;

        /** @var \Twig\Loader\FilesystemLoader $loader */
        $loader = $this->twig->getLoader();
        if ($loader->exists($twigCandidate)) {
            return $this->twig->render($twigCandidate, $this->vars);
        }

        // No .twig counterpart — fall back to Smarty.
        return $this->smartyFallback()->fetch($templateName);
    }

    public function getTemplateVars(?string $name = null): mixed
    {
        if ($name === null) {
            return $this->vars;
        }
        return $this->vars[$name] ?? null;
    }

    /**
     * Mirrors SmartyPage::FetchLocalized resolution exactly.
     *
     * 1. Resolve the language code from the argument or the CurrentLanguage var;
     *    without one, step 4 falls back to en_us.
     * 2. Check for a localized template (and -custom override) at lang/<lang>/.
     * 3. If enforceCustomTemplate is true and no custom exists, check the default language.
     * 4. Fall back to lang/en_us/ when the localized path has no match.
     * 5. Engine-select: if a .twig counterpart of the resolved name exists under the
     *    resolved directory, render via Twig; otherwise delegate to the Smarty fallback.
     *
     * TODO(twig-migration): the interface has no $languageCode. Record the
     * language at construction, as SmartyRenderer does, and drop the parameter.
     */
    public function fetchLocalized(
        string $templateName,
        bool $enforceCustomTemplate,
        ?string $languageCode = null
    ): string {
        if (!TemplateEngineOverride::TwigAllowed()) {
            return $this->smartyFallback()->fetchLocalized($templateName, $enforceCustomTemplate);
        }

        if ($languageCode === null || $languageCode === '') {
            // Like SmartyPage::FetchLocalized: without a language the en_us fallback below applies,
            // not the language of whoever triggered the email
            $languageCode = $this->vars['CurrentLanguage'] ?? '';
        }

        $langPath = ROOT_DIR . 'lang/';
        $localizedPath = $langPath . $languageCode;
        $customTemplateName = str_ends_with($templateName, '.tpl')
            ? substr($templateName, 0, -4) . '-custom.tpl'
            : $templateName . '-custom';
        $hasCustomTemplate = file_exists($localizedPath . '/' . $customTemplateName);

        if ($enforceCustomTemplate && !$hasCustomTemplate) {
            $defaultLanguageCode = \Configuration::Instance()->GetKey(\ConfigKeys::DEFAULT_LANGUAGE);
            $defaultLocalizedPath = $langPath . $defaultLanguageCode;
            $hasCustomDefaultTemplate = file_exists($defaultLocalizedPath . '/' . $customTemplateName);
            if ($languageCode !== $defaultLanguageCode && $hasCustomDefaultTemplate) {
                $hasCustomTemplate = true;
                $localizedPath = $defaultLocalizedPath;
            }
        }

        if (file_exists($localizedPath . '/' . $templateName) || $hasCustomTemplate) {
            $resolvedDir = $localizedPath;
        } else {
            // Fall back to en_us
            $resolvedDir = ROOT_DIR . 'lang/en_us';
        }

        $resolvedName = file_exists($resolvedDir . '/' . $customTemplateName)
            ? $customTemplateName
            : $templateName;

        // Engine-select on the resolved template path.
        $twigCandidate = str_ends_with($resolvedName, '.tpl')
            ? substr($resolvedName, 0, -4) . '.twig'
            : $resolvedName . '.twig';

        if (file_exists($resolvedDir . '/' . $twigCandidate)) {
            // The resolved directory gets a namespace of its own. addTemplateDirectory()
            // searches an added directory last, so a template of the same name in the
            // renderer's own language directory would win otherwise.
            $loader = $this->twig->getLoader();
            if ($loader instanceof \Twig\Loader\FilesystemLoader) {
                $loader->setPaths($resolvedDir, self::LOCALIZED_NAMESPACE);
                $twigCandidate = '@' . self::LOCALIZED_NAMESPACE . '/' . $twigCandidate;
            }

            return $this->twig->render($twigCandidate, $this->vars);
        }

        // No .twig counterpart — delegate to Smarty fallback.
        return $this->smartyFallback()->fetchLocalized($templateName, $enforceCustomTemplate);
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
        if (!TemplateEngineOverride::TwigAllowed()) {
            return $this->smartyFallback()->renderControlTemplate($templateName, $vars);
        }

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
        if (!TemplateEngineOverride::TwigAllowed()) {
            return $this->smartyFallback()->render($templateName, $vars);
        }

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
