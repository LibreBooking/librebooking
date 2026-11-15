<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Runs Web/Services/index.php in a separate PHP process to pin down how the
 * API entry point routes requests: which URLs match a route, what an unknown
 * URL or a wrong HTTP method gets back, and how the path of the API inside
 * the web root is worked out.
 *
 * Unless a test says otherwise the API is disabled through LB_API_ENABLED, so
 * a request that matches a route halts with 503 in the access check before
 * any handler, session or database access. A 503 therefore means "this URL
 * matched a route". The subprocess is also pointed at an unreachable database
 * so it never touches the database configured in config/config.php.
 */
class ApiEntryPointTest extends TestCase
{
    private const NOT_FOUND_BODY = '{"links":[],"message":"The requested resource was not found"}';

    private const SERVER_ERROR_BODY = '{"links":[],"message":"Exception was logged."}';

    private const SCRIPT_NAME = '/Web/Services/index.php';

    /**
     * PHP code, run when the subprocess shuts down, that evaluates to the
     * status code and the headers of the response index.php produced. The
     * CLI SAPI does not record the status line or the headers that were
     * sent, so they are read from the objects index.php leaves in the global
     * scope.
     */
    private const RESPONSE_META_CODE = '['
        . '"status" => $GLOBALS["response"]->getStatusCode(),'
        . ' "allow" => $GLOBALS["response"]->getHeaderLine("Allow") ?: null,'
        . ' "contentType" => $GLOBALS["response"]->getHeaderLine("Content-Type") ?: null,'
        . ']';

    private const META_MARKER = 'RESPONSE-META:';

    private const API_GROUP_ENV_VARS = [
        'LB_API_AUTHENTICATION_GROUP',
        'LB_API_ACCESSORIES_RO_GROUP',
        'LB_API_ACCOUNTS_RO_GROUP',
        'LB_API_ACCOUNTS_RW_GROUP',
        'LB_API_ATTRIBUTES_RO_GROUP',
        'LB_API_GROUPS_RO_GROUP',
        'LB_API_RESERVATIONS_RO_GROUP',
        'LB_API_RESERVATIONS_RW_GROUP',
        'LB_API_RESOURCES_RO_GROUP',
        'LB_API_SCHEDULES_RO_GROUP',
        'LB_API_USERS_RO_GROUP',
    ];

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

    public function testUnknownPathReturnsJsonNotFound(): void
    {
        [$status, $output, $meta] = $this->runEntryPoint(method: 'GET', path: '/NoSuchService/');

        $this->assertSame(RestResponse::NOT_FOUND_CODE, $status, $output);
        $this->assertSame(self::NOT_FOUND_BODY, $output);
        $this->assertSame('application/json', $meta['contentType']);
    }

    public function testWrongMethodForKnownPathIsNotAllowed(): void
    {
        [$status, $output, $meta] = $this->runEntryPoint(method: 'PUT', path: '/Users/5');

        $this->assertSame(RestResponse::METHOD_NOT_ALLOWED_CODE, $status, $output);
        $allowed = preg_split('/[\s,]+/', (string) $meta['allow'], -1, PREG_SPLIT_NO_EMPTY);
        foreach (['GET', 'POST', 'DELETE'] as $method) {
            $this->assertContains($method, $allowed, 'Allow header: ' . $meta['allow']);
        }
        $this->assertNotContains('PUT', $allowed);
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function matchingPaths(): array
    {
        return [
            'collection with trailing slash' => ['GET', '/Users/'],
            'collection without trailing slash' => ['GET', '/Users'],
            'create on collection' => ['POST', '/Users/'],
            'nested route' => ['POST', '/Users/5/Password'],
            'route without leading slash in its definition' => ['GET', '/Attributes/Category/1'],
            'static route next to an ID route' => ['GET', '/Resources/Status/Reasons'],
            'ID route with a sub path' => ['GET', '/Resources/5/Availability'],
            'unsecured route' => ['GET', '/Resources/Status'],
            'authentication' => ['POST', '/Authentication/Authenticate'],
            'help' => ['GET', '/Help'],
            'root' => ['GET', '/'],
            'root without slash' => ['GET', ''],
            'query string' => ['GET', '/Users/5?next=/Users/abc'],
            'query string on a collection' => ['GET', '/Reservations/?userId=5'],
        ];
    }

    #[DataProvider('matchingPaths')]
    public function testPathMatchesRoute(string $method, string $path): void
    {
        [$status, $output] = $this->runEntryPoint(method: $method, path: $path);

        $this->assertSame(RestResponse::SERVICE_UNAVAILABLE, $status, $output);
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function notMatchingPaths(): array
    {
        return [
            'trailing slash on an ID route' => ['GET', '/Users/5/'],
            'trailing slash on a nested route' => ['POST', '/Users/5/Password/'],
            'extra segment' => ['GET', '/Users/5/NoSuchThing'],
            'different case' => ['GET', '/users/5'],
            'ID longer than ten digits' => ['GET', '/Users/12345678901'],
            'negative ID' => ['GET', '/Users/-5'],
        ];
    }

    #[DataProvider('notMatchingPaths')]
    public function testPathDoesNotMatchRoute(string $method, string $path): void
    {
        [$status, $output] = $this->runEntryPoint(method: $method, path: $path);

        $this->assertSame(RestResponse::NOT_FOUND_CODE, $status, $output);
        $this->assertSame(self::NOT_FOUND_BODY, $output);
    }

    /**
     * The API is reachable both as .../Services/index.php/Users/5 and, with
     * the rewrite rule in Web/Services/.htaccess, as .../Services/Users/5.
     * In both cases SCRIPT_NAME is the path of index.php.
     *
     * @return array<string, array{0: string, 1: string}>
     */
    public static function installLocations(): array
    {
        return [
            'web root, script in URL' => ['/Web/Services/index.php', '/Web/Services/index.php'],
            'web root, rewritten URL' => ['/Web/Services/index.php', '/Web/Services'],
            'subdirectory, script in URL' => ['/booking/Web/Services/index.php', '/booking/Web/Services/index.php'],
            'subdirectory, rewritten URL' => ['/booking/Web/Services/index.php', '/booking/Web/Services'],
            'Web as document root, script in URL' => ['/Services/index.php', '/Services/index.php'],
            'Web as document root, rewritten URL' => ['/Services/index.php', '/Services'],
        ];
    }

    #[DataProvider('installLocations')]
    public function testRoutesAreFoundWhereverTheApiIsInstalled(string $scriptName, string $uriPrefix): void
    {
        [$status, $output] = $this->runEntryPoint(
            method: 'GET',
            path: '/Users/5',
            scriptName: $scriptName,
            uriPrefix: $uriPrefix,
        );
        $this->assertSame(RestResponse::SERVICE_UNAVAILABLE, $status, $output);

        [$status, $output] = $this->runEntryPoint(
            method: 'GET',
            path: '/Users/abc',
            scriptName: $scriptName,
            uriPrefix: $uriPrefix,
        );
        $this->assertSame(RestResponse::NOT_FOUND_CODE, $status, $output);
        $this->assertSame(self::NOT_FOUND_BODY, $output);
    }

    #[DataProvider('installLocations')]
    public function testHelpPageListsRoutesWithTheirFullPath(string $scriptName, string $uriPrefix): void
    {
        [$status, $output] = $this->runEntryPoint(
            method: 'GET',
            path: '/Help',
            scriptName: $scriptName,
            uriPrefix: $uriPrefix,
            apiEnabled: true,
        );

        $this->assertSame(RestResponse::OK_CODE, $status, $output);
        $this->assertStringContainsString('<code>' . $uriPrefix . '/Users/</code>', $output);
        $this->assertStringContainsString('<code>' . $uriPrefix . '/Users/:userId</code>', $output);
        $this->assertStringContainsString('<code>' . $uriPrefix . '/Users/:userId/Password</code>', $output);
        $this->assertStringContainsString('<code>' . $uriPrefix . '/Attributes/Category/:categoryId</code>', $output);
        $this->assertStringContainsString('<code>' . $uriPrefix . '/Authentication/Authenticate</code>', $output);
    }

    public function testSecureRouteWithoutCredentialsIsUnauthorized(): void
    {
        [$status, $output] = $this->runEntryPoint(method: 'GET', path: '/Users/5', apiEnabled: true);

        $this->assertSame(RestResponse::UNAUTHORIZED_CODE, $status, $output);
        $this->assertSame(
            'You must be authenticated in order to access this service.<br/>'
            . 'http://localhost/Web/Services/index.php/Authentication/Authenticate',
            $output
        );
    }

    /**
     * A PHP warning raised while a service runs fails the request, as it did
     * with Slim 2, instead of letting the service carry on with a null. The
     * login service reads the fields of the JSON body, which is missing
     * here, before it does anything else.
     */
    public function testPhpWarningInAServiceIsAServerError(): void
    {
        [$status, $output, $meta] = $this->runEntryPoint(
            method: 'POST',
            path: '/Authentication/Authenticate',
            apiEnabled: true,
        );

        $this->assertSame(RestResponse::SERVER_ERROR, $status, $output);
        $this->assertSame(self::SERVER_ERROR_BODY, $output);
        $this->assertSame('application/json', $meta['contentType']);
    }

    public function testServiceUrlsKeepThePortOfTheRequest(): void
    {
        [$status, $output] = $this->runEntryPoint(
            method: 'GET',
            path: '/Users/5',
            apiEnabled: true,
            host: 'booking.example.com:8443',
        );

        $this->assertSame(RestResponse::UNAUTHORIZED_CODE, $status, $output);
        $this->assertStringEndsWith(
            '<br/>http://booking.example.com:8443/Web/Services/index.php/Authentication/Authenticate',
            $output
        );
    }

    /**
     * @return array{0: int, 1: string, 2: array{status: int, allow: ?string, contentType: ?string}}
     *     HTTP status code, response body, and response details
     */
    private function runEntryPoint(
        string $method,
        string $path,
        string $scriptName = self::SCRIPT_NAME,
        string $uriPrefix = self::SCRIPT_NAME,
        bool $apiEnabled = false,
        string $host = 'localhost',
    ): array {
        $requestUri = $uriPrefix . $path;
        $server = [
            'REQUEST_METHOD' => $method,
            'SCRIPT_NAME' => $scriptName,
            'REQUEST_URI' => $requestUri,
            'QUERY_STRING' => (string) parse_url($requestUri, PHP_URL_QUERY),
            'SERVER_NAME' => 'localhost',
            'SERVER_PORT' => '80',
            'HTTP_HOST' => $host,
            'REMOTE_ADDR' => '127.0.0.1',
        ];
        $code = '$_SERVER = array_merge($_SERVER, ' . var_export($server, true) . ');'
            . ' register_shutdown_function(function () {'
            . ' echo "\n' . self::META_MARKER . '", json_encode(' . self::RESPONSE_META_CODE . '); });'
            . ' include "index.php";';

        // The API group settings are emptied so that registering the routes
        // never looks a group up in the database.
        $env = array_merge(
            getenv(),
            array_fill_keys(self::API_GROUP_ENV_VARS, ''),
            [
                'LB_API_ENABLED' => $apiEnabled ? 'true' : 'false',
                'LB_DATABASE_HOSTSPEC' => '127.0.0.1:1',
            ]
        );

        $process = proc_open(
            command: [PHP_BINARY, '-d', 'display_errors=1', '-d', 'error_reporting=E_ALL', '-r', $code],
            descriptor_spec: [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            pipes: $pipes,
            cwd: __DIR__ . '/../../Web/Services',
            env_vars: $env,
        );
        $this->assertIsResource($process, 'Failed to start PHP subprocess');

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        // Only stdout is parsed: stderr may carry warnings about the local
        // config/config.php that have nothing to do with the response.
        $output = $stdout . $stderr;
        $markerPos = strrpos($stdout, "\n" . self::META_MARKER);
        $this->assertNotFalse($markerPos, "No response marker in output:\n" . $output);

        $meta = json_decode(substr($stdout, $markerPos + strlen("\n" . self::META_MARKER)), true);
        $this->assertIsArray($meta, "Unreadable response marker in output:\n" . $output);
        $body = substr($stdout, 0, $markerPos);

        return [$meta['status'], $body, $meta];
    }
}
