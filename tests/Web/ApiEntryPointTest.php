<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Runs Web/Services/index.php in a separate PHP process to verify that a
 * route ID that is not numeric gets a JSON 404 from the API entry point
 * instead of reaching a handler that expects an int.
 *
 * The API is disabled through LB_API_ENABLED, so a request that matches a
 * route halts with 503 in the dispatch hook before any handler, session or
 * database access. The subprocess is also pointed at an unreachable database
 * so it never touches the database configured in config/config.php.
 */
class ApiEntryPointTest extends TestCase
{
    private const NOT_FOUND_BODY = '{"links":[],"message":"The requested resource was not found"}';

    public function testNonNumericRouteIdReturnsJsonNotFound(): void
    {
        [$status, $output] = $this->runEntryPoint(method: 'GET', path: '/Users/abc');

        $this->assertSame(RestResponse::NOT_FOUND_CODE, $status, $output);
        $this->assertSame(self::NOT_FOUND_BODY, $output);
    }

    public function testRouteIdWithTrailingTextReturnsJsonNotFound(): void
    {
        [$status, $output] = $this->runEntryPoint(method: 'DELETE', path: '/Groups/12abc');

        $this->assertSame(RestResponse::NOT_FOUND_CODE, $status, $output);
        $this->assertSame(self::NOT_FOUND_BODY, $output);
    }

    public function testNumericRouteIdMatchesRoute(): void
    {
        [$status, $output] = $this->runEntryPoint(method: 'GET', path: '/Users/5');

        $this->assertSame(RestResponse::SERVICE_UNAVAILABLE, $status, $output);
        $this->assertStringContainsString('API is disabled', $output);
    }

    public function testReferenceNumberRouteIsNotRestricted(): void
    {
        [$status, $output] = $this->runEntryPoint(method: 'GET', path: '/Reservations/5f1a2b3c4d5e6');

        $this->assertSame(RestResponse::SERVICE_UNAVAILABLE, $status, $output);
    }

    /**
     * @return array{0: int, 1: string} HTTP status code and combined stdout/stderr
     */
    private function runEntryPoint(string $method, string $path): array
    {
        $statusMarker = 'STATUS:';
        $server = [
            'REQUEST_METHOD' => $method,
            'SCRIPT_NAME' => '/Web/Services/index.php',
            'REQUEST_URI' => '/Web/Services/index.php' . $path,
            'SERVER_NAME' => 'localhost',
            'SERVER_PORT' => '80',
            'REMOTE_ADDR' => '127.0.0.1',
        ];
        // The CLI SAPI does not record the status line Slim sends, so read
        // the status from the Slim app that index.php leaves in $app.
        $code = '$_SERVER = array_merge($_SERVER, ' . var_export($server, true) . ');'
            . ' register_shutdown_function(function () {'
            . ' echo "\n' . $statusMarker . '", $GLOBALS["app"]->response()->status(); });'
            . ' include "index.php";';

        $process = proc_open(
            command: [PHP_BINARY, '-d', 'display_errors=1', '-d', 'error_reporting=E_ALL', '-r', $code],
            descriptor_spec: [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            pipes: $pipes,
            cwd: __DIR__ . '/../../Web/Services',
            env_vars: array_merge(getenv(), [
                'LB_API_ENABLED' => 'false',
                'LB_DATABASE_HOSTSPEC' => '127.0.0.1:1',
            ]),
        );
        $this->assertIsResource($process, 'Failed to start PHP subprocess');

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        $output = $stdout . $stderr;
        $markerPos = strrpos($output, "\n" . $statusMarker);
        $this->assertNotFalse($markerPos, "No status marker in output:\n" . $output);

        $status = (int) substr($output, $markerPos + strlen("\n" . $statusMarker));
        $body = substr($output, 0, $markerPos);

        return [$status, $body];
    }
}
