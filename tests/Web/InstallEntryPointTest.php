<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Runs Web/install/index.php in a separate PHP process to verify that the
 * destructive install and upgrade actions are not run for a session that has
 * not verified the install password.
 *
 * The subprocess is pointed at an unreachable database through LB_*
 * environment variables, so it never touches the database configured in
 * config/config.php.
 */
class InstallEntryPointTest extends TestCase
{
    private const PAGE_MARKER = 'id="page-install"';
    private const PASSWORD_PROMPT = 'name="install_password"';

    public function setUp(): void
    {
        $cacheDir = __DIR__ . '/../../tpl_c';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0770, true);
        }
        $this->assertTrue(is_writable($cacheDir), 'tpl_c must be writable for the install page to load');
    }

    public function testAnonymousRunInstallDoesNotRunInstaller(): void
    {
        $output = $this->runEntryPoint(['run_install' => '1', 'create_database' => '1']);

        $this->assertInstallPasswordPromptShown($output);
        $this->assertStringNotContainsString('create-db.sql', $output, 'The installer must not run');
    }

    public function testAnonymousRunUpgradeDoesNotRunUpgrade(): void
    {
        $output = $this->runEntryPoint(['run_upgrade' => '1']);

        $this->assertInstallPasswordPromptShown($output);
        $this->assertStringNotContainsString('upgrades', $output, 'The upgrade must not run');
    }

    private function assertInstallPasswordPromptShown(string $output): void
    {
        $this->assertStringNotContainsString('Fatal error', $output);
        $this->assertStringContainsString(self::PAGE_MARKER, $output, 'The install page must render');
        $this->assertStringContainsString(self::PASSWORD_PROMPT, $output, 'The install password prompt must be shown');
    }

    /**
     * @param array<string, string> $post
     */
    private function runEntryPoint(array $post): string
    {
        $code = '$_SERVER["REQUEST_METHOD"] = "POST"; $_POST = ' . var_export($post, true)
            . '; include "index.php";';

        $process = proc_open(
            command: [PHP_BINARY, '-d', 'display_errors=1', '-d', 'error_reporting=E_ALL', '-r', $code],
            descriptor_spec: [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            pipes: $pipes,
            cwd: __DIR__ . '/../../Web/install',
            env_vars: array_merge(getenv(), [
                'LB_DATABASE_HOSTSPEC' => '127.0.0.1:1',
                'LB_INSTALL_PASSWORD' => 'entry-point-test-password',
            ]),
        );
        $this->assertIsResource($process, 'Failed to start PHP subprocess');

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        return $stdout . $stderr;
    }
}
