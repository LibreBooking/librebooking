<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Runs Web/microsoft-auth.php in a separate PHP process to verify the
 * entry-point bootstrap: the Composer autoloader must resolve the callback
 * handler while the legacy include bootstrap supplies the global classes that
 * Log depends on. The handler unit test cannot catch a missing-class fatal in
 * this file because it never executes the entry point.
 */
class MicrosoftAuthEntryPointTest extends TestCase
{
    public function testOAuthErrorCallbackRunsWithoutFatalError(): void
    {
        [$exitCode, $output] = $this->runEntryPoint(
            get: ['error' => 'access_denied', 'error_description' => 'the user canceled the authentication'],
        );

        $this->assertSame(0, $exitCode, "Entry point exited non-zero. Output:\n" . $output);
        $this->assertStringNotContainsString('Fatal error', $output);
        $this->assertStringNotContainsString('not found', $output);
    }

    public function testSuccessCallbackRunsWithoutFatalError(): void
    {
        [$exitCode, $output] = $this->runEntryPoint(get: ['code' => 'abc123', 'state' => '/Web/']);

        $this->assertSame(0, $exitCode, "Entry point exited non-zero. Output:\n" . $output);
        $this->assertStringNotContainsString('Fatal error', $output);
        $this->assertStringNotContainsString('not found', $output);
    }

    /**
     * @param array<string, string> $get
     * @return array{0: int, 1: string} exit code and combined stdout/stderr
     */
    private function runEntryPoint(array $get): array
    {
        $code = '$_GET = ' . var_export($get, true) . '; include "microsoft-auth.php";';

        $process = proc_open(
            command: [PHP_BINARY, '-d', 'display_errors=1', '-d', 'error_reporting=E_ALL', '-r', $code],
            descriptor_spec: [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            pipes: $pipes,
            cwd: __DIR__ . '/../../Web',
        );
        $this->assertIsResource($process, 'Failed to start PHP subprocess');

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        return [$exitCode, $stdout . $stderr];
    }
}
