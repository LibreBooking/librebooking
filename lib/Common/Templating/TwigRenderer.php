<?php

use LibreBooking\Common\Templating\TemplateRenderer;
use LibreBooking\Common\Templating\TwigEnvironmentFactory;

class TwigRenderer implements TemplateRenderer
{
    private \Twig\Environment $twig;
    private array $vars = [];
    private string $rootPath;
    private Resources $resources;
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
        return $this->twig->render($templateName, $vars);
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
