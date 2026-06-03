<?php

function EnsureComposerDependenciesInstalledForRequest(): void
{
    $autoloadPath = ROOT_DIR . 'vendor/autoload.php';
    if (file_exists($autoloadPath)) {
        require_once $autoloadPath;
        return;
    }

    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $phpSelf = $_SERVER['PHP_SELF'] ?? '';

    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';

    $isApiRequest = str_contains($scriptName, '/Web/Services/')
        || str_contains($phpSelf, '/Web/Services/')
        || str_contains($requestUri, '/Web/Services/')
        || stripos($accept, 'application/json') !== false;

    if ($isApiRequest) {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8', true, 503);
        }
        echo json_encode(
            [
                'error' => 'dependencies_missing',
                'message' => 'Application dependencies are not installed. Run "composer install" in the project root.',
            ],
            JSON_UNESCAPED_SLASHES
        );
        exit;
    }

    if (!headers_sent()) {
        header('Content-Type: text/html; charset=utf-8', true, 503);
    }

    echo <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>LibreBooking Setup Required</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
            background: #f7f7f7;
            color: #1f2937;
            margin: 0;
            padding: 2rem;
        }

        .container {
            max-width: 780px;
            margin: 3rem auto;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        }

        h1 {
            margin-top: 0;
            color: #991b1b;
            font-size: 1.6rem;
        }

        code {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 0.25rem 0.4rem;
            font-size: 0.95rem;
        }

        p {
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Application setup is incomplete</h1>
        <p>Composer dependencies are missing, so LibreBooking cannot start yet.</p>
        <p>From the project root, run <code>composer install</code>, then reload this page.</p>
    </div>
</body>
</html>
HTML;

    exit;
}
