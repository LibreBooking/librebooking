<?php

declare(strict_types=1);

namespace LibreBooking\Common\Templating;

class EngineSelector
{
    /**
     * Returns the .twig candidate name if a .twig file exists in any of the search dirs,
     * or null if no .twig file is found.
     *
     * @param string   $tplName    Template name (e.g. 'foo.tpl', 'Sub/bar.tpl', or 'foo.twig')
     * @param string[] $searchDirs Filesystem directories to search in
     */
    public static function twigNameFor(string $tplName, array $searchDirs): ?string
    {
        // Compute the .twig candidate name.
        if (str_ends_with($tplName, '.tpl')) {
            $candidate = substr($tplName, 0, -4) . '.twig';
        } else {
            $candidate = $tplName;
        }

        foreach ($searchDirs as $dir) {
            if (is_file($dir . '/' . $candidate)) {
                return $candidate;
            }
        }

        return null;
    }
}
