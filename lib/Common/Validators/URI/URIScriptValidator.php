<?php

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
            Log::Debug(message: "Invalid URI detected. Redirecting to: " . dirname($_SERVER['SCRIPT_NAME']) . $redirectURL);
            header("Location: " . dirname($_SERVER['SCRIPT_NAME']) . $redirectURL);
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
            "Validating URI. Path: %s, Query: %s, PathSafe: %s, QuerySafe: %s",
            $path,
            $query,
            $isPathSafe ? "yes" : "no",
            $isQuerySafe ? "yes" : "no"
        );

        return $isPathSafe && $isQuerySafe;
    }



    private static function isSafePath(string $path): bool
    {
        // Enforce the path must include /Web/ and end with a PHP file
        if (!preg_match('#/Web/[^/]+\.php$#', $path)) {
            return false;
        }

        // Basic XSS prevention on path (e.g., no <script> or encoded variants)
        return !preg_match('/<script|%3Cscript/i', $path);
    }

    private static function isSafeQuery(string $query): bool
    {
        if (empty($query)) return true;

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
