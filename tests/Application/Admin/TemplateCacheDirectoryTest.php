<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Admin/namespace.php');

class TemplateCacheDirectoryTest extends TestBase
{
    /**
     * Random bytes in the name of the fixture directory, enough to keep the
     * names of concurrent test runs apart.
     */
    private const UNIQUE_NAME_BYTES = 8;

    private string $directory;

    public function setUp(): void
    {
        parent::setUp();

        $this->directory = sys_get_temp_dir() . '/template-cache-directory-test-'
            . bin2hex(random_bytes(self::UNIQUE_NAME_BYTES)) . '/';
        mkdir($this->directory);
    }

    public function tearDown(): void
    {
        // Flush() empties the directory even when an assertion failed before it ran
        $this->cacheDirectory()->Flush();
        rmdir($this->directory);

        parent::tearDown();
    }

    public function testFlushRemovesTheCompiledSmartyTemplates(): void
    {
        file_put_contents($this->directory . 'abc_0.file_login.tpl.php', 'compiled');
        file_put_contents($this->directory . 'def_0.file_schedule.tpl.php', 'compiled');

        $this->cacheDirectory()->Flush();

        $this->assertSame([], $this->remainingEntries());
    }

    /**
     * Twig keeps its compiled templates in tpl_c/twig/<hash prefix>/<hash>.php.
     */
    public function testFlushRemovesTheCompiledTwigTemplates(): void
    {
        mkdir($this->directory . 'twig/ab', recursive: true);
        mkdir($this->directory . 'twig/cd', recursive: true);
        file_put_contents($this->directory . 'twig/ab/abcdef.php', 'compiled');
        file_put_contents($this->directory . 'twig/cd/cdef01.php', 'compiled');
        file_put_contents($this->directory . 'abc_0.file_login.tpl.php', 'compiled');

        $this->cacheDirectory()->Flush();

        $this->assertSame([], $this->remainingEntries());
    }

    public function testFlushKeepsTheCacheDirectoryItself(): void
    {
        $this->cacheDirectory()->Flush();

        $this->assertDirectoryExists($this->directory);
    }

    private function cacheDirectory(): TemplateCacheDirectory
    {
        $directory = $this->directory;

        return new class ($directory) extends TemplateCacheDirectory {
            public function __construct(private string $directory)
            {
            }

            public function GetDirectory()
            {
                return $this->directory;
            }
        };
    }

    /**
     * @return string[]
     */
    private function remainingEntries(): array
    {
        return array_values(array_diff(scandir($this->directory), ['.', '..']));
    }
}
