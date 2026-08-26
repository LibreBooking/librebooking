<?php

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class LibreBookingExtension extends AbstractExtension
{
    public function __construct(
        private Resources $resources,
        private string $rootPath
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('translate', function (string $key, string|array $args = []): string {
                if (empty($args)) {
                    return $this->resources->GetString($key, '');
                }
                $args = is_array($args) ? $args : explode(',', $args);
                return $this->resources->GetString($key, $args);
            }, ['is_safe' => ['html']]),
        ];
    }

    public function getFilters(): array
    {
        return [];
    }
}
