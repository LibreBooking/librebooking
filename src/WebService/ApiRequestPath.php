<?php

declare(strict_types=1);

namespace LibreBooking\WebService;

/**
 * Splits the URL of an API request into the part that locates the API inside
 * the web root and the part that names the requested service.
 *
 * The API is reachable both with the entry script in the URL
 * (/Web/Services/index.php/Users/5) and, when the web server rewrites
 * requests to the entry script, without it (/Web/Services/Users/5). In both
 * cases the path of the service is /Users/5. The base path is what a
 * generated service URL has to start with to be reachable the same way.
 */
final class ApiRequestPath
{
    private function __construct(
        /** Path of the API inside the web root, without a trailing slash */
        public readonly string $basePath,
        /** Path of the requested service, always starting with a slash */
        public readonly string $pathInfo,
    ) {
    }

    /**
     * @param array<string, mixed> $server The $_SERVER array of the request
     */
    public static function fromServer(array $server): self
    {
        $requestUri = (string) ($server['REQUEST_URI'] ?? '');
        $scriptName = (string) ($server['SCRIPT_NAME'] ?? '');

        if ($scriptName !== '' && str_starts_with($requestUri, $scriptName)) {
            // The entry script is part of the URL
            $basePath = $scriptName;
        } else {
            // The web server rewrote the URL to the entry script. On Windows
            // dirname() returns a backslash for a script in the web root.
            $basePath = str_replace('\\', '/', dirname($scriptName));
        }

        $pathInfo = substr($requestUri, strlen($basePath));
        $queryStart = strpos($pathInfo, '?');
        if ($queryStart !== false) {
            $pathInfo = substr($pathInfo, 0, $queryStart);
        }

        return new self(
            basePath: rtrim($basePath, '/'),
            pathInfo: '/' . ltrim($pathInfo, '/'),
        );
    }
}
