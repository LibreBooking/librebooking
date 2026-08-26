<?php

declare(strict_types=1);

namespace LibreBooking\Common\Templating;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TwigEnvironmentFactory
{
    /**
     * @param string[] $templateDirs
     */
    public static function create(array $templateDirs, string $cacheDir, bool $debug): Environment
    {
        $existing = array_values(array_filter($templateDirs, 'is_dir'));
        $loader = new FilesystemLoader($existing);

        $env = new Environment($loader, [
            'autoescape' => 'html',
            'cache' => $debug ? false : $cacheDir,
            'debug' => $debug,
            'strict_variables' => false,
            'auto_reload' => $debug,
        ]);

        return $env;
    }
}
