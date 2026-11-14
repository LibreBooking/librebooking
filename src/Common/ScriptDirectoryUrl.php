<?php

declare(strict_types=1);

namespace LibreBooking\Common;

/**
 * Builds root-relative URLs for pages that live in the same directory as the
 * script handling the current request.
 *
 * The result is derived from the request, not from configuration, so it is
 * correct whether the web server's document root is the project root (pages
 * under /Web/) or the Web directory itself (pages at /).
 */
final class ScriptDirectoryUrl
{
    /**
     * @param string $scriptName URL path of the running script, usually $_SERVER['SCRIPT_NAME']
     * @param string $page       Page in the same directory, with or without a leading slash
     *
     * @return string Root-relative URL that always starts with exactly one slash, so it can
     *                never be read as a protocol-relative URL (//host/path)
     */
    public static function forPage(string $scriptName, string $page): string
    {
        $directory = trim(str_replace('\\', '/', dirname($scriptName)), '/');
        if ($directory === '.') {
            $directory = '';
        }

        $page = ltrim($page, '/');

        return $directory === '' ? '/' . $page : '/' . $directory . '/' . $page;
    }
}
