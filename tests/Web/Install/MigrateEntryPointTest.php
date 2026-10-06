<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Runs Web/install/migrate.php in a separate PHP process to verify that it
 * stays an inert stub. The phpScheduleIt 1.2 web migration it used to host ran
 * its migration steps without authentication (GHSA-3356-vjx2-5pg8). The file
 * is kept so that upgrades which overwrite an existing installation replace
 * the vulnerable copy, so it must answer HTTP 410 for every request and must
 * not load any application code.
 */
class MigrateEntryPointTest extends TestCase
{
    private const RESULT_MARKER = "\n---MIGRATE-ENTRY-POINT-RESULT---\n";

    /**
     * @return array<string, array{0: array<string, string>, 1: array<string, string>}>
     */
    public static function requestProvider(): array
    {
        $legacyDatabase = [
            'legacyUser' => 'attacker',
            'legacyPassword' => 'secret',
            'legacyHostSpec' => 'attacker.example.com',
            'legacyDatabaseName' => 'phpscheduleit',
        ];

        $requests = ['plain request' => [[], []]];

        foreach (['schedules', 'resources', 'accessories', 'groups', 'users', 'reservations'] as $runTarget) {
            $requests['migration step: ' . $runTarget] = [['start' => $runTarget], $legacyDatabase];
        }

        $requests['migration login'] = [[], $legacyDatabase + ['run' => 'true', 'installPassword' => 'password']];

        return $requests;
    }

    /**
     * @param array<string, string> $get
     * @param array<string, string> $post
     */
    #[DataProvider('requestProvider')]
    public function testAnswersGoneWithoutLoadingApplicationCode(array $get, array $post): void
    {
        [$exitCode, $output, $result] = $this->runEntryPoint(get: $get, post: $post);

        $this->assertSame(0, $exitCode, "Entry point exited non-zero. Output:\n" . $output);
        $this->assertSame(410, $result['status']);
        $this->assertStringStartsWith('Gone', $output);
        $this->assertSame(['migrate.php'], $result['includedFiles'], 'The stub must not include any other file');
        $this->assertSame([], $result['userClasses'], 'The stub must not declare or load any class');
        $this->assertSame([], $result['userFunctions'], 'The stub must not declare or load any function');
    }

    /**
     * @param array<string, string> $get
     * @param array<string, string> $post
     * @return array{0: int, 1: string, 2: array{status: int, includedFiles: list<string>, userClasses: list<string>, userFunctions: list<string>}}
     *     exit code, combined stdout/stderr of the entry point, and the state of the subprocess after it ran
     */
    private function runEntryPoint(array $get, array $post): array
    {
        $code = '$_GET = ' . var_export($get, true) . ';'
            . '$_POST = ' . var_export($post, true) . ';'
            . '$_REQUEST = $_GET + $_POST;'
            . '$_SERVER["REQUEST_METHOD"] = ' . var_export($post === [] ? 'GET' : 'POST', true) . ';'
            . 'include "migrate.php";'
            . 'echo ' . var_export(self::RESULT_MARKER, true) . ', json_encode(['
            . '"status" => http_response_code(),'
            . '"includedFiles" => array_values(array_map("basename", array_filter(get_included_files(), "is_file"))),'
            . '"userClasses" => array_values(array_filter(get_declared_classes(), fn ($c) => (new ReflectionClass($c))->isUserDefined())),'
            . '"userFunctions" => get_defined_functions()["user"],'
            . ']);';

        $process = proc_open(
            command: [PHP_BINARY, '-d', 'display_errors=1', '-d', 'error_reporting=E_ALL', '-r', $code],
            descriptor_spec: [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            pipes: $pipes,
            cwd: __DIR__ . '/../../../Web/install',
        );
        $this->assertIsResource($process, 'Failed to start PHP subprocess');

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        $parts = explode(self::RESULT_MARKER, $stdout . $stderr);
        $this->assertCount(2, $parts, "Entry point did not run to completion. Output:\n" . $stdout . $stderr);

        return [$exitCode, $parts[0], json_decode($parts[1], true, flags: JSON_THROW_ON_ERROR)];
    }
}
