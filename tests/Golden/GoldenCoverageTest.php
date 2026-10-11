<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Guard: every Smarty template that has a Twig counterpart must be exercised by a
 * golden parity test. Adding a .twig next to a .tpl without covering it fails here.
 *
 * Coverage is judged by the template path (for example "Admin/Users/users_csv") or its
 * file name appearing in a tests/Golden/*Test.php file.
 */
class GoldenCoverageTest extends TestCase
{
    /** Templates covered indirectly, with the reason. */
    private const INDIRECT = [
        'Export/embedded-calendar-container' => 'rendered through the embedded-calendar-{month,week,agenda} templates that extend it',
    ];

    /**
     * @return array<string, array{string}>
     */
    public static function templates(): array
    {
        $root = dirname(__DIR__, 2) . '/tpl/';
        $cases = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
        foreach ($iterator as $file) {
            $path = $file->getPathname();
            if (!str_ends_with($path, '.tpl') || !file_exists(substr($path, 0, -4) . '.twig')) {
                continue;
            }
            $relative = substr($path, strlen($root), -4);
            $cases[$relative] = [$relative];
        }
        ksort($cases);

        return $cases;
    }

    #[DataProvider('templates')]
    public function testTemplateIsCoveredByAGoldenTest(string $template): void
    {
        if (isset(self::INDIRECT[$template])) {
            $this->addToAssertionCount(1);

            return;
        }

        $name = basename($template);
        foreach (glob(__DIR__ . '/*Test.php') as $test) {
            if ($test === __FILE__) {
                continue;
            }
            $source = file_get_contents($test);
            if (str_contains($source, $template) || str_contains($source, $name . '.tpl') || str_contains($source, $name . '.twig')) {
                $this->addToAssertionCount(1);

                return;
            }
        }

        $this->fail("tpl/$template.tpl has a .twig counterpart but no golden test in tests/Golden mentions it");
    }
}
