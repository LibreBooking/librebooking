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
        return [];
    }

    public function getFilters(): array
    {
        return [];
    }
}
