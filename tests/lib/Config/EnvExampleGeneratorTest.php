<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Config/namespace.php');
require_once(ROOT_DIR . 'lib/Config/ConfigDistGenerator.php');
require_once(ROOT_DIR . 'lib/Config/EnvExampleGenerator.php');

class EnvExampleGeneratorTest extends TestBase
{
    public function testRenderContainsSectionHeaders(): void
    {
        $content = EnvExampleGenerator::render();

        $this->assertStringContainsString("# Application configuration\n", $content);
        $this->assertStringContainsString("# Language and Timezone\n", $content);
        $this->assertStringContainsString("# Database\n", $content);
        $this->assertStringContainsString("# Authentication Settings\n", $content);
    }

    public function testRenderContainsAllConfigKeys(): void
    {
        $content = EnvExampleGenerator::render();

        foreach (ConfigKeys::all() as $entry) {
            $envKey = ConfigKeysMeta::envKeyForConfig(config: $entry);
            $this->assertStringContainsString(
                $envKey . '=',
                $content,
                "Missing env key: {$envKey} (from config key: {$entry->key})"
            );
        }
    }

    public function testEnvKeyFormatMatchesAbstractConfigKeys(): void
    {
        // Verify canonical env keys follow the expected LB_* naming convention
        foreach (ConfigKeys::all() as $entry) {
            $key = $entry->key;
            $expected = strtoupper('LB_' . preg_replace('/[.\-]+/', '_', $key));
            $actual = ConfigKeysMeta::envKeyForConfig(config: $entry);

            $this->assertSame(
                $expected,
                $actual,
                "envKey mismatch for config key: {$key}"
            );
        }
    }

    public function testRenderOutputHasCorrectTypes(): void
    {
        $content = EnvExampleGenerator::render();

        // Boolean defaults render as true/false, not quoted
        $this->assertMatchesRegularExpression(
            '/^LB_APP_DEBUG=false$/m',
            $content
        );

        // Integer defaults render as integers, not quoted
        $this->assertMatchesRegularExpression(
            '/^LB_INACTIVITY_TIMEOUT=30$/m',
            $content
        );

        // String defaults render as single-quoted
        $this->assertMatchesRegularExpression(
            "/^LB_APP_TITLE='LibreBooking'$/m",
            $content
        );

        // Empty string defaults render as bare (no quotes)
        $this->assertMatchesRegularExpression(
            '/^LB_COMPANY_NAME=$/m',
            $content
        );
    }

    public function testRenderContainsComments(): void
    {
        $content = EnvExampleGenerator::render();

        // config_file_comment is preferred
        $this->assertStringContainsString('# The public name of the application', $content);

        // Boolean hint is appended
        $this->assertStringContainsString('(true/false)', $content);
    }

    public function testRenderContainsChoices(): void
    {
        $content = EnvExampleGenerator::render();

        // Choices from config_file_comment (already has Options:) should not duplicate
        $this->assertStringContainsString('# Options:', $content);
    }

    public function testHiddenKeysAreIncluded(): void
    {
        $content = EnvExampleGenerator::render();

        $this->assertStringContainsString('LB_INSTALL_PASSWORD=', $content);
    }

    public function testCheckReturnsTrueForMatchingFile(): void
    {
        $tmpFile = tempnam(directory: sys_get_temp_dir(), prefix: 'env_example_test_');
        EnvExampleGenerator::writeToFile(path: $tmpFile);

        try {
            $this->assertTrue(EnvExampleGenerator::check(path: $tmpFile));
        } finally {
            unlink(filename: $tmpFile);
        }
    }

    public function testCheckReturnsFalseForMismatchedFile(): void
    {
        $tmpFile = tempnam(directory: sys_get_temp_dir(), prefix: 'env_example_test_');
        file_put_contents(filename: $tmpFile, data: 'LB_FOO=bar');

        try {
            $this->assertFalse(EnvExampleGenerator::check(path: $tmpFile));
        } finally {
            unlink(filename: $tmpFile);
        }
    }

    public function testCheckReturnsFalseForMissingFile(): void
    {
        $path = sys_get_temp_dir() . '/nonexistent_env_example_test_' . uniqid() . '.env';
        $this->assertFalse(EnvExampleGenerator::check(path: $path));
    }

    public function testRenderProducesValidEnvSyntax(): void
    {
        $content = EnvExampleGenerator::render();

        foreach (explode(separator: "\n", string: $content) as $lineNum => $line) {
            if ($line === '' || str_starts_with(haystack: $line, needle: '#')) {
                continue;
            }

            $this->assertMatchesRegularExpression(
                '/^[A-Z][A-Z0-9_]+=/',
                $line,
                'Line ' . ($lineNum + 1) . " is not valid .env syntax: {$line}"
            );
        }
    }
}
