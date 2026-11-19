<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Loads lib/Common/namespace.php in a separate PHP process that has no
 * autoloader registered, the way most web entry points do.
 *
 * The classes it loads depend on Composer packages and on the PSR-4 classes
 * in src/. A test in this process cannot check that, because PHPUnit has
 * already registered the autoloader.
 */
class NamespaceBootstrapTest extends TestCase
{
    private const BOOTSTRAP = 'lib/Common/namespace.php';
    private const AUTOLOADER = 'vendor/autoload.php';

    public function testLoadsWithoutAnAutoloaderRegisteredBeforehand(): void
    {
        $result = $this->loadBootstrap();

        $this->assertTrue($result['smartyPage'], 'SmartyPage extends a Composer class');
        $this->assertTrue($result['smartyRenderer'], 'SmartyRenderer implements a PSR-4 interface');
    }

    /**
     * Otherwise the bootstrap only works while one of the files it includes
     * happens to register the autoloader before the first class needs it.
     */
    public function testRegistersTheAutoloaderBeforeIncludingAnythingElse(): void
    {
        $includedFiles = $this->loadBootstrap()['includedFiles'];
        $root = $this->rootDirectory();

        $bootstrap = array_search($root . self::BOOTSTRAP, $includedFiles, true);

        $this->assertIsInt($bootstrap, self::BOOTSTRAP . ' was not included');
        $this->assertSame($root . self::AUTOLOADER, $includedFiles[$bootstrap + 1]);
    }

    /**
     * @return array{includedFiles: string[], smartyPage: bool, smartyRenderer: bool}
     */
    private function loadBootstrap(): array
    {
        $code = 'define("ROOT_DIR", ' . var_export($this->rootDirectory(), true) . ');'
            . 'require ROOT_DIR . ' . var_export(self::BOOTSTRAP, true) . ';'
            . 'echo json_encode(['
            . '"includedFiles" => get_included_files(),'
            . '"smartyPage" => class_exists("SmartyPage", false),'
            . '"smartyRenderer" => class_exists("SmartyRenderer", false),'
            . ']);';

        $process = proc_open(
            command: [PHP_BINARY, '-d', 'display_errors=stderr', '-d', 'error_reporting=E_ALL', '-r', $code],
            descriptor_spec: [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            pipes: $pipes,
        );
        $this->assertIsResource($process, 'Failed to start PHP subprocess');

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        $this->assertSame(0, $exitCode, "The bootstrap exited non-zero. Output:\n" . $stdout . $stderr);
        $this->assertSame('', $stderr, 'The bootstrap wrote to stderr');

        return json_decode($stdout, associative: true, flags: JSON_THROW_ON_ERROR);
    }

    private function rootDirectory(): string
    {
        return dirname(__DIR__, 3) . '/';
    }
}
