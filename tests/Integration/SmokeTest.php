<?php

declare(strict_types=1);

namespace Tests\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use PHPUnit\Framework\TestCase;

/**
 * HTTP-level smoke tests that start a real PHP web server,
 * log in, and visit pages to verify they load without errors.
 *
 * Prerequisites:
 *   - MySQL database with schema, upgrades, and sample data loaded
 *   - config/config.php pointing to the test database
 *   - tpl_c/ directory exists and is writable
 *
 * Run with: composer test:integration
 */
class SmokeTest extends TestCase
{
    /** @var resource|null */
    private static $serverProcess = null;
    /** @var array<int, resource> */
    private static array $serverPipes = [];
    private static ?string $serverStderrLogFile = null;
    private static string $baseUrl;
    private static int $port;
    private static Client $client;
    private static CookieJar $cookieJar;
    private static bool $loggedIn = false;

    public static function setUpBeforeClass(): void
    {
        self::assertLinuxCiEnvironment();

        $rootDir = realpath(__DIR__ . '/../../');
        if ($rootDir === false) {
            self::fail('Failed to resolve project root directory for integration tests.');
        }

        $routerScript = realpath(__DIR__ . '/router.php');
        if ($routerScript === false) {
            self::fail('Failed to resolve router script path for integration tests.');
        }

        self::$port = (int) (getenv('LB_TEST_PORT') ?: 8080);
        self::$baseUrl = getenv('LB_TEST_BASE_URL') ?: 'http://127.0.0.1:' . self::$port;

        // Ensure tpl_c exists for Smarty template compilation
        $tplCDir = $rootDir . '/tpl_c';
        if (!is_dir($tplCDir) && !mkdir(directory: $tplCDir, permissions: 0777, recursive: true) && !is_dir($tplCDir)) {
            self::fail(sprintf(
                'Failed to create template cache directory at %s. Check filesystem permissions.',
                $tplCDir
            ));
        }

        if (!is_callable('proc_open')) {
            self::fail('Cannot start integration test web server: proc_open() is not available.');
        }

        $command = [
            'php',
            '-S',
            sprintf('127.0.0.1:%d', self::$port),
            '-t',
            $rootDir,
            $routerScript,
        ];
        $descriptorSpec = [
            0 => ['pipe', 'r'],
            1 => ['file', '/dev/null', 'a'],
            2 => ['file', self::createServerStderrLogFile(), 'a'],
        ];
        $pipes = [];
        $process = proc_open($command, $descriptorSpec, $pipes, $rootDir);
        if (!is_resource($process)) {
            self::fail('Failed to start integration test web server: proc_open() did not return a process resource.');
        }
        self::$serverProcess = $process;
        self::$serverPipes = $pipes;

        $status = proc_get_status($process);
        $pid = $status['pid'] ?? null;
        if (!is_int($pid) || $pid <= 0) {
            self::stopServerProcess();
            self::fail('Failed to start integration test web server: proc_get_status() returned an invalid PID.');
        }

        // Wait for server to be ready (up to 5 seconds)
        $ready = false;
        for ($i = 0; $i < 50; $i++) {
            $status = proc_get_status(self::$serverProcess);
            if (!($status['running'] ?? false)) {
                self::stopServerProcess();
                self::fail('PHP built-in server exited before becoming ready.');
            }

            $connection = @fsockopen('127.0.0.1', self::$port);
            if ($connection) {
                fclose($connection);
                $ready = true;
                break;
            }
            usleep(100_000); // 100ms
        }

        if (!$ready) {
            self::stopServerProcess();
            self::fail('PHP built-in server did not start within 5 seconds on port ' . self::$port);
        }

        self::$cookieJar = new CookieJar();
        self::$client = self::createClient(self::$cookieJar);
    }

    private static function assertLinuxCiEnvironment(): void
    {
        $isCi = getenv('CI');
        if ($isCi === false || strtolower($isCi) !== 'true') {
            self::fail(
                'Integration smoke tests are CI-only because setup is destructive and not safe for local runs ' .
                '(for example it can overwrite config/config.php).'
            );
        }

        if (PHP_OS_FAMILY !== 'Linux') {
            self::fail('Integration smoke tests must run on Linux.');
        }
    }

    public static function tearDownAfterClass(): void
    {
        self::stopServerProcess();
    }

    private static function stopServerProcess(): void
    {
        if (is_resource(self::$serverProcess)) {
            $status = proc_get_status(self::$serverProcess);
            if ($status['running'] ?? false) {
                @proc_terminate(self::$serverProcess);
                usleep(200_000);
                $status = proc_get_status(self::$serverProcess);
                if ($status['running'] ?? false) {
                    @proc_terminate(self::$serverProcess, 9);
                }
            }

            foreach (self::$serverPipes as $pipe) {
                if (is_resource($pipe)) {
                    fclose($pipe);
                }
            }

            @proc_close(self::$serverProcess);
        }

        self::dumpServerStderrIfAvailable();
        self::cleanupServerStderrLogFile();

        self::$serverPipes = [];
        self::$serverProcess = null;
    }

    private static function createServerStderrLogFile(): string
    {
        if (self::$serverStderrLogFile !== null) {
            return self::$serverStderrLogFile;
        }

        $logFile = tempnam(sys_get_temp_dir(), 'lb-integration-php-');
        if ($logFile === false) {
            self::fail('Failed to create temp file for integration server stderr logging.');
        }

        self::$serverStderrLogFile = $logFile;

        return self::$serverStderrLogFile;
    }

    private static function dumpServerStderrIfAvailable(): void
    {
        if (self::$serverStderrLogFile === null || !is_file(self::$serverStderrLogFile)) {
            return;
        }

        $content = file_get_contents(self::$serverStderrLogFile);
        if ($content === false || trim($content) === '') {
            return;
        }

        fwrite(STDERR, "\n--- php -S stderr (integration smoke test) ---\n");
        fwrite(STDERR, $content);
        if (!str_ends_with($content, "\n")) {
            fwrite(STDERR, "\n");
        }
        fwrite(STDERR, "--- end php -S stderr ---\n");
    }

    private static function cleanupServerStderrLogFile(): void
    {
        if (self::$serverStderrLogFile !== null && is_file(self::$serverStderrLogFile)) {
            @unlink(self::$serverStderrLogFile);
        }

        self::$serverStderrLogFile = null;
    }

    public function testLoginPageLoads(): void
    {
        $response = self::$client->get('/Web/index.php');

        $this->assertSame(
            200,
            $response->getStatusCode(),
            'Login page did not return HTTP 200'
        );

        $body = (string) $response->getBody();
        $this->assertStringContainsString('login', strtolower($body), 'Login page does not contain "login"');
        $this->assertPhpErrorFree(body: $body);
    }

    #[\PHPUnit\Framework\Attributes\Depends('testLoginPageLoads')]
    public function testInvalidLoginIsRejected(): void
    {
        $cookieJar = new CookieJar();
        $client = self::createClient($cookieJar);

        $response = $client->post('/Web/index.php', [
            'form_params' => [
                'email' => 'invalid-user@example.com',
                'password' => 'wrong-password',
                'login' => 'submit',
                'resume' => '',
            ],
        ]);

        $this->assertSame(200, $response->getStatusCode(), 'Invalid login request did not return HTTP 200');
        $body = (string) $response->getBody();
        $this->assertPhpErrorFree(body: $body);
        $this->assertStringContainsString(
            'name="login"',
            $body,
            'Invalid login should return the login form'
        );

        $dashboardResponse = $client->get('/Web/dashboard.php');
        $this->assertSame(
            200,
            $dashboardResponse->getStatusCode(),
            'Dashboard request after invalid login did not return HTTP 200'
        );
        $dashboardBody = (string) $dashboardResponse->getBody();
        $this->assertPhpErrorFree(body: $dashboardBody);
        $this->assertStringContainsString(
            'name="login"',
            $dashboardBody,
            'Invalid login appears to have authenticated the user unexpectedly'
        );
    }

    public function testLoginAndVisitDashboard(): void
    {
        // POST the login form
        $response = self::$client->post('/Web/index.php', [
            'form_params' => [
                'email' => 'admin@example.com',
                'password' => 'password',
                'login' => 'submit',
                'resume' => '',
            ],
        ]);

        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();

        // After successful login, the app redirects to dashboard.
        // Guzzle follows redirects, so we should end up at 200.
        $this->assertSame(
            200,
            $statusCode,
            "Login POST returned unexpected status $statusCode. Body: " . substr($body, 0, 500)
        );
        $this->assertPhpErrorFree(body: $body);

        self::$loggedIn = true;

        // Verify we're on the dashboard (not redirected back to login)
        $dashboardResponse = self::$client->get('/Web/dashboard.php');
        $this->assertSame(200, $dashboardResponse->getStatusCode(), 'Dashboard did not return HTTP 200');
        $dashboardBody = (string) $dashboardResponse->getBody();
        $this->assertPhpErrorFree(body: $dashboardBody);
        // Dashboard should not contain the login form
        $this->assertStringNotContainsString(
            'name="login"',
            $dashboardBody,
            'Dashboard appears to show the login form — login may have failed'
        );
    }

    #[\PHPUnit\Framework\Attributes\Depends('testLoginAndVisitDashboard')]
    #[\PHPUnit\Framework\Attributes\DataProvider('authenticatedPagesProvider')]
    public function testAuthenticatedPageReturns200(string $path, string $label): void
    {
        if (!self::$loggedIn) {
            $this->markTestSkipped('Login did not succeed — skipping authenticated page tests');
        }

        $response = self::$client->get($path);

        $this->assertSame(
            200,
            $response->getStatusCode(),
            "Page $label ($path) did not return HTTP 200"
        );

        $body = (string) $response->getBody();
        $this->assertPhpErrorFree(body: $body);
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function authenticatedPagesProvider(): array
    {
        return [
            'schedule' => ['/Web/schedule.php', 'Schedule'],
            'calendar' => ['/Web/calendar.php', 'Calendar'],
            'profile' => ['/Web/profile.php', 'Profile'],
            'my-calendar' => ['/Web/my-calendar.php', 'My Calendar'],
        ];
    }

    /**
     * Assert that the response body does not contain PHP error indicators.
     */
    private function assertPhpErrorFree(string $body): void
    {
        $this->assertStringNotContainsString('Fatal error', $body, 'Response contains a PHP Fatal error');
        $this->assertStringNotContainsString('Parse error', $body, 'Response contains a PHP Parse error');
        $this->assertStringNotContainsString('Stack trace:', $body, 'Response contains a PHP stack trace');
    }

    private static function createClient(CookieJar $cookieJar): Client
    {
        return new Client([
            'base_uri' => self::$baseUrl,
            'cookies' => $cookieJar,
            'http_errors' => false,
            'allow_redirects' => [
                'max' => 5,
                'track_redirects' => true,
            ],
        ]);
    }
}
