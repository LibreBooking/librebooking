<?php

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

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
