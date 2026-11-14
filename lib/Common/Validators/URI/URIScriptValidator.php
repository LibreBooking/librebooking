<?php

use LibreBooking\Common\ScriptDirectoryUrl;

class URIScriptValidator implements IURIScriptValidator
{
    /**
     * Redirects if the URI is not safe.
     *
     * @param string $requestURI
     * @param string $redirectURL
     */
    public static function validateOrRedirect(string $requestURI, string $redirectURL): void
    {
        if (!self::validate($requestURI)) {
            $location = ScriptDirectoryUrl::forPage(
                scriptName: $_SERVER['SCRIPT_NAME'] ?? '',
                page: $redirectURL,
            );
            Log::Debug(message: 'Invalid URI detected. Redirecting to: ' . $location);
            header('Location: ' . $location);
            exit;
        }
    }

    /**
     * Validates the request URI for safe path and query parameters.
     *
     * @param string $requestURI Full URI of the request (e.g., $_SERVER['REQUEST_URI']).
     * @return bool True if safe, false if potentially malicious.
     */
    public static function validate($requestURI): bool
    {

        $path = parse_url($requestURI, PHP_URL_PATH);
        $query = parse_url($requestURI, PHP_URL_QUERY) ?? '';

        $isPathSafe = self::isSafePath($path);
        $isQuerySafe = self::isSafeQuery($query);

        Log::Debug(
            'Validating URI. Path: %s, Query: %s, PathSafe: %s, QuerySafe: %s',
            $path,
            $query,
            $isPathSafe ? 'yes' : 'no',
            $isQuerySafe ? 'yes' : 'no'
        );

        return $isPathSafe && $isQuerySafe;
    }



    private static function isSafePath(string $path): bool
    {
        // The path must end with a PHP file. The directory is deliberately not
        // checked: pages are served from /Web/ when the project root is the
        // document root and from / when Web is.
        // See https://github.com/LibreBooking/librebooking/issues/1590
        if (!preg_match('#/[^/]+\.php$#', $path)) {
            return false;
        }

        // Only the last segment may be a PHP file. A web server that accepts
        // path info runs /schedule.php for /schedule.php/anything, so a ".php"
        // segment followed by more path means caller-controlled content was
        // appended after the real script name, even if it also ends in ".php".
        if (preg_match('#\.php/#i', rawurldecode($path))) {
            return false;
        }

        // Basic XSS prevention on path (e.g., no <script> or encoded variants)
        return !preg_match('/<script|%3Cscript/i', $path);
    }

    private static function isSafeQuery(string $query): bool
    {
        if (empty($query)) {
            return true;
        }

        // Decode and check for script-related payloads
        $decoded = urldecode($query);

        // Common XSS vectors to look for
        $xssPatterns = [
            '/<script.*?>.*?<\/script>/is',
            '/on\w+=".*?"/i',
            '/javascript:/i',
            '/data:text\/html/i',
            '/<.*?>/i',
        ];

        foreach ($xssPatterns as $pattern) {
            if (preg_match($pattern, $decoded)) {
                return false;
            }
        }

        return true;
    }
}
