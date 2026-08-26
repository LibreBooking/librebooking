<?php

declare(strict_types=1);

namespace LibreBooking\Common\Templating;

interface TemplateRenderer
{
    public function assign(string $name, mixed $value): void;

    public function render(string $templateName, array $vars = []): string;

    public function display(string $templateName): void;

    public function fetch(string $templateName): string;

    public function getTemplateVars(?string $name = null): mixed;

    public function fetchLocalized(
        string $templateName,
        bool $enforceCustomTemplate,
        ?string $languageCode = null
    ): string;

    public function addTemplateDirectory(string $dir): void;

    public function renderControlTemplate(string $templateName, array $vars): string;

    public function validators(): \PageValidators;

    public function isValid(): bool;
}
