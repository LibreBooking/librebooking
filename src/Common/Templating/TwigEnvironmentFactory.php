<?php

declare(strict_types=1);

namespace LibreBooking\Common\Templating;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Creates the Twig environment the application renders with.
 */
final class TwigEnvironmentFactory
{
    /**
     * Output is escaped for HTML unless a template says otherwise, and an
     * undefined variable renders as empty, as it does with Smarty.
     *
     * @param string[] $templateDirs directories to look templates up in;
     *                               those that do not exist are left out
     * @param string   $cacheDir     where compiled templates are kept
     * @param bool     $debug        compile templates on every request and
     *                               keep no cache
     */
    public static function create(array $templateDirs, string $cacheDir, bool $debug): Environment
    {
        $existingDirs = array_values(array: array_filter(array: $templateDirs, callback: is_dir(...)));

        return new Environment(
            loader: new FilesystemLoader(paths: $existingDirs),
            options: [
                'autoescape' => 'html',
                'cache' => $debug ? false : $cacheDir,
                'debug' => $debug,
                'strict_variables' => false,
                'auto_reload' => $debug,
            ]
        );
    }
}
