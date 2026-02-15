<?php

/**
 * Generate a config/config.php suitable for integration testing.
 *
 * Reads database credentials from environment variables and writes
 * a config file based on config.dist.php with test-friendly overrides.
 *
 * Usage:
 *   LB_TEST_DB_USER=root LB_TEST_DB_PASSWORD=root php tests/Integration/create-test-config.php
 *
 * Environment variables:
 *   LB_TEST_DB_HOST     - Database host (default: 127.0.0.1)
 *   LB_TEST_DB_PORT     - Database port (default: 3306)
 *   LB_TEST_DB_NAME     - Database name (default: librebooking_test)
 *   LB_TEST_DB_USER     - Database user (default: root)
 *   LB_TEST_DB_PASSWORD - Database password (default: empty)
 *   LB_TEST_BASE_URL    - Base URL of the test server (default: http://127.0.0.1:8080)
 */

$dbHost = getenv('LB_TEST_DB_HOST') ?: '127.0.0.1';
$dbPort = getenv('LB_TEST_DB_PORT') ?: '3306';
$dbName = getenv('LB_TEST_DB_NAME') ?: 'librebooking_test';
$dbUser = getenv('LB_TEST_DB_USER') ?: 'root';
$dbPass = getenv('LB_TEST_DB_PASSWORD') !== false ? getenv('LB_TEST_DB_PASSWORD') : '';
$testPort = getenv('LB_TEST_PORT') ?: '8080';
$baseUrl = getenv('LB_TEST_BASE_URL') ?: "http://127.0.0.1:$testPort";

$isCi = getenv('CI');
if ($isCi === false || strtolower($isCi) !== 'true') {
    fwrite(
        STDERR,
        'ERROR: integration setup is CI-only because it is destructive and not safe to run locally ' .
        '(for example it can overwrite config/config.php).' . "\n"
    );
    exit(1);
}

$configPath = __DIR__ . '/../../config/config.php';
$distPath = __DIR__ . '/../../config/config.dist.php';

if (!file_exists($distPath)) {
    fwrite(STDERR, "ERROR: config.dist.php not found at: $distPath\n");
    exit(1);
}

// Load the dist config as a base
$config = require $distPath;

// Override database settings
$hostSpec = $dbPort !== '3306' ? "$dbHost:$dbPort" : $dbHost;
$config['settings']['database']['hostspec'] = $hostSpec;
$config['settings']['database']['name'] = $dbName;
$config['settings']['database']['user'] = $dbUser;
$config['settings']['database']['password'] = $dbPass;

// Test-friendly overrides
$config['settings']['script.url'] = "$baseUrl/Web";
$config['settings']['cache.templates'] = false;
$config['settings']['app.debug'] = false;
$config['settings']['registration']['captcha.enabled'] = false;
$config['settings']['authentication']['captcha.on.login'] = false;
$config['settings']['recaptcha']['enabled'] = false;
$config['settings']['logging']['level'] = 'none';
$config['settings']['email']['enabled'] = false;

$bytesWritten = file_put_contents($configPath, "<?php\nreturn " . var_export($config, true) . ";\n");
if ($bytesWritten === false) {
    fwrite(STDERR, "ERROR: failed to write test config to: $configPath\n");
    exit(1);
}

echo "Created test config at: $configPath\n";
echo "  Database: $dbUser@$hostSpec/$dbName\n";
echo "  Base URL: $baseUrl\n";
