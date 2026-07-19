<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Runs the Google, Keycloak and OAuth2 callback entry points
 * (Web/{google,keycloak,oauth2}-auth.php) under php-cgi to verify that they
 * forward the authorization code and OAuth state to external-auth.php.
 *
 * The scripts read their input with filter_input(INPUT_GET, ...), which only
 * sees a real request, and the CLI SAPI does not expose sent headers. php-cgi
 * provides both: QUERY_STRING populates INPUT_GET and the response headers,
 * including Location, are written to stdout.
 */
class OAuthCallbackEntryPointTest extends TestCase
{
    private const RESUME_URL = '/Web/reservation.php?rn=123&sd=2026-01-01 10:00';
    private const FALLBACK_LOCATION = '../Web';

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function callbackScripts(): array
    {
        return [
            'google' => ['google-auth.php', 'google'],
            'keycloak' => ['keycloak-auth.php', 'keycloak'],
            'oauth2' => ['oauth2-auth.php', 'oauth2'],
        ];
    }

    #[DataProvider('callbackScripts')]
    public function testForwardsCodeAndStateAsRfc3986EncodedRedirect(string $script, string $type): void
    {
        $location = $this->runCallback(
            script: $script,
            queryString: http_build_query(['code' => 'abc/123', 'state' => self::RESUME_URL]),
        );

        $this->assertSame(
            '../Web/external-auth.php?type=' . $type
                . '&code=' . rawurlencode('abc/123')
                . '&redirect=' . rawurlencode(self::RESUME_URL),
            $location
        );
    }

    #[DataProvider('callbackScripts')]
    public function testOmitsRedirectWhenStateIsMissing(string $script, string $type): void
    {
        $location = $this->runCallback(script: $script, queryString: 'code=abc');

        $this->assertSame('../Web/external-auth.php?type=' . $type . '&code=abc', $location);
    }

    #[DataProvider('callbackScripts')]
    public function testOmitsRedirectWhenStateIsAnArray(string $script, string $type): void
    {
        $location = $this->runCallback(script: $script, queryString: 'code=abc&state[]=x');

        $this->assertSame('../Web/external-auth.php?type=' . $type . '&code=abc', $location);
    }

    #[DataProvider('callbackScripts')]
    public function testFallsBackWhenCodeIsMissing(string $script, string $type): void
    {
        $location = $this->runCallback(script: $script, queryString: 'state=x');

        $this->assertSame(self::FALLBACK_LOCATION, $location);
    }

    #[DataProvider('callbackScripts')]
    public function testFallsBackWhenCodeIsAnArray(string $script, string $type): void
    {
        $location = $this->runCallback(script: $script, queryString: 'code[]=abc&state=x');

        $this->assertSame(self::FALLBACK_LOCATION, $location);
    }

    /**
     * Runs the script under php-cgi and returns its Location header value.
     */
    private function runCallback(string $script, string $queryString): string
    {
        $webDir = realpath(__DIR__ . '/../../Web');
        $this->assertIsString($webDir);

        // JIT is disabled because, when an extension such as Xdebug hooks
        // zend_execute_ex(), PHP prints a startup warning ("JIT is
        // incompatible with third party extensions ...") that is unrelated
        // to the script under test but would fail the no-output assertion.
        $process = proc_open(
            command: [
                $this->findPhpCgi(),
                '-d', 'display_errors=1',
                '-d', 'error_reporting=E_ALL',
                '-d', 'opcache.jit=disable',
                '-d', 'opcache.jit_buffer_size=0',
            ],
            descriptor_spec: [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            pipes: $pipes,
            cwd: $webDir,
            env_vars: [
                'REQUEST_METHOD' => 'GET',
                'QUERY_STRING' => $queryString,
                'SCRIPT_FILENAME' => $webDir . '/' . $script,
                'REDIRECT_STATUS' => '1',
            ],
        );
        $this->assertIsResource($process, 'Failed to start php-cgi subprocess');

        $stdout = (string)stream_get_contents($pipes[1]);
        $stderr = (string)stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        $output = $stdout . $stderr;
        $this->assertSame(0, $exitCode, "php-cgi exited non-zero. Output:\n" . $output);

        [$headers, $body] = array_pad(explode("\r\n\r\n", $stdout, 2), 2, '');
        $this->assertSame('', trim($body . $stderr), "Unexpected output:\n" . $output);

        $this->assertMatchesRegularExpression('/^Location:\s*(.*)$/mi', $headers, "No Location header:\n" . $output);
        preg_match('/^Location:\s*(.*)$/mi', $headers, $matches);

        return trim($matches[1]);
    }

    /**
     * Locates the php-cgi binary that belongs to the PHP running the tests.
     */
    private function findPhpCgi(): string
    {
        $binDir = dirname(PHP_BINARY);
        $version = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
        foreach (["$binDir/php-cgi$version", "$binDir/php-cgi"] as $candidate) {
            if (is_executable($candidate)) {
                return $candidate;
            }
        }

        $this->markTestSkipped("php-cgi not found next to $binDir; it is required to run callback entry points");
    }
}
