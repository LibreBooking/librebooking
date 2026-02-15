<?php

/**
 * Router script for PHP's built-in web server.
 *
 * Replicates the Apache .htaccess rewrite rules so the application
 * works correctly with `php -S`.
 *
 * Usage:
 *   php -S 127.0.0.1:8080 -t /path/to/project tests/Integration/router.php
 */

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
if (!is_string($path)) {
    http_response_code(400);
    echo "Bad Request\n";
    return;
}

/**
 * Execute the target script while making server vars look like direct execution.
 */
function dispatchScript(string $documentRoot, string $scriptPath, ?string $pathInfo = null): void
{
    $scriptName = substr($scriptPath, strlen($documentRoot));
    if ($scriptName === false || $scriptName === '') {
        $scriptName = '/index.php';
    }
    $scriptName = str_replace('\\', '/', $scriptName);

    $_SERVER['SCRIPT_FILENAME'] = $scriptPath;
    $_SERVER['SCRIPT_NAME'] = $scriptName;
    $_SERVER['PHP_SELF'] = $scriptName;
    $_SERVER['DOCUMENT_URI'] = $scriptName;

    if ($pathInfo !== null) {
        $_SERVER['PATH_INFO'] = $pathInfo;
    } else {
        unset($_SERVER['PATH_INFO']);
    }

    // Each Web/*.php entry point does define('ROOT_DIR', '../'),
    // so the working directory must be the script's directory.
    chdir(dirname($scriptPath));
    require $scriptPath;
}

/**
 * Return true when path contains traversal/null-byte patterns.
 */
function isInvalidRequestPath(string $path): bool
{
    $decodedPath = rawurldecode($path);

    if (str_contains($decodedPath, "\0")) {
        return true;
    }

    return preg_match('#(?:^|/)\.\.(?:/|$)#', $decodedPath) === 1;
}

/**
 * Resolve a URL path to an existing filesystem path under DOCUMENT_ROOT.
 */
function resolvePathInDocumentRoot(string $documentRoot, string $path): ?string
{
    $resolvedPath = realpath($documentRoot . $path);
    if ($resolvedPath === false) {
        return null;
    }

    if ($resolvedPath !== $documentRoot && !str_starts_with($resolvedPath, $documentRoot . '/')) {
        return null;
    }

    return $resolvedPath;
}

// Serve existing static files directly (CSS, JS, images, etc.)
$documentRoot = realpath($_SERVER['DOCUMENT_ROOT']);
if (!is_string($documentRoot) || $documentRoot === '') {
    http_response_code(500);
    echo "Server Misconfiguration\n";
    return;
}

if (isInvalidRequestPath(path: $path)) {
    http_response_code(400);
    echo "Bad Request\n";
    return;
}

$resolvedRequestedPath = resolvePathInDocumentRoot(documentRoot: $documentRoot, path: $path);
$requestedPathIsFile = is_string($resolvedRequestedPath) && is_file($resolvedRequestedPath);
if ($path !== '/' && $requestedPathIsFile) {
    // Let the built-in server handle static files natively
    return false;
}

// Replicate root .htaccess: redirect non-/Web/ requests to /Web/
if (!str_starts_with($path, '/Web/') && $path !== '/Web') {
    header('Location: /Web' . $path, true, 302);
    return;
}

// Replicate Web/Services/.htaccess: route API requests to Services/index.php
if (str_starts_with($path, '/Web/Services/') && $path !== '/Web/Services/index.php') {
    $servicesIndex = resolvePathInDocumentRoot(documentRoot: $documentRoot, path: '/Web/Services/index.php');
    if (is_string($servicesIndex) && is_file($servicesIndex) && !$requestedPathIsFile) {
        $pathInfo = substr($path, strlen('/Web/Services'));
        if ($pathInfo === false || $pathInfo === '') {
            $pathInfo = '/';
        }
        dispatchScript(documentRoot: $documentRoot, scriptPath: $servicesIndex, pathInfo: $pathInfo);
        return;
    }
}

// Serve the PHP file from the Web/ directory
$scriptPath = resolvePathInDocumentRoot(documentRoot: $documentRoot, path: $path);

// Handle directory requests (e.g., /Web/) by appending index.php
if (is_string($scriptPath) && is_dir($scriptPath)) {
    $scriptPath = resolvePathInDocumentRoot(
        documentRoot: $documentRoot,
        path: substr($path, -1) === '/' ? $path . 'index.php' : $path . '/index.php'
    );
}

if (is_string($scriptPath) && is_file($scriptPath)) {
    dispatchScript(documentRoot: $documentRoot, scriptPath: $scriptPath);
    return;
}

// 404 fallback
http_response_code(404);
echo "Not Found: $path\n";
