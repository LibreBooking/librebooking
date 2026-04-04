<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Config/namespace.php');
require_once(ROOT_DIR . 'lib/Config/ConfigDistGenerator.php');

class ConfigDistGeneratorTest extends TestBase
{
    public function testGenerateSettingsArrayHasFlatAndSections(): void
    {
        $result = ConfigDistGenerator::generateSettingsArray();

        $this->assertArrayHasKey('flat', $result);
        $this->assertArrayHasKey('sections', $result);
        $this->assertIsArray($result['flat']);
        $this->assertIsArray($result['sections']);
    }

    public function testFlatKeysFollowDeclarationOrder(): void
    {
        $result = ConfigDistGenerator::generateSettingsArray();
        $keys = array_keys($result['flat']);

        // First flat key in ConfigKeys.php is app.title
        $this->assertSame('app.title', $keys[0], 'Flat keys should follow ConfigKeys declaration order');
    }

    public function testSectionsFollowDeclarationOrder(): void
    {
        $result = ConfigDistGenerator::generateSettingsArray();
        $sectionNames = array_keys($result['sections']);

        // First section in ConfigKeys.php is 'pages'
        $this->assertSame('pages', $sectionNames[0], 'Sections should follow ConfigKeys declaration order');
    }

    public function testHiddenKeysAreIncluded(): void
    {
        $result = ConfigDistGenerator::generateSettingsArray();

        // is_hidden keys should still appear in the dist file
        $this->assertArrayHasKey('install.password', $result['flat']);
    }

    public function testEmptySectionTreatedAsFlat(): void
    {
        $result = ConfigDistGenerator::generateSettingsArray();

        // google.analytics.tracking.id has section => ''
        $this->assertArrayHasKey('google.analytics.tracking.id', $result['flat']);
    }

    public function testTopLevelGroupsCoverAllFlatKeys(): void
    {
        $result = ConfigDistGenerator::generateSettingsArray();
        $flatKeys = array_keys($result['flat']);

        $groupedKeys = [];
        foreach (ConfigKeysMeta::TOP_LEVEL_GROUPS as $groupKeys) {
            foreach ($groupKeys as $key) {
                $groupedKeys[] = $key;
            }
        }

        $this->assertSame($flatKeys, $groupedKeys, 'Top-level groups should cover flat keys in render order');
    }

    public function testSectionedKeysHavePrefixStripped(): void
    {
        $result = ConfigDistGenerator::generateSettingsArray();

        $this->assertArrayHasKey('database', $result['sections']);
        $this->assertArrayHasKey('type', $result['sections']['database']);
        $this->assertArrayHasKey('name', $result['sections']['database']);
    }

    public function testRenderProducesValidPhp(): void
    {
        $content = ConfigDistGenerator::render();

        // Write to temp file and include it to verify valid PHP
        $tmpFile = tempnam(directory: sys_get_temp_dir(), prefix: 'config_dist_test_');
        file_put_contents(filename: $tmpFile, data: $content);

        try {
            $result = include $tmpFile;
            $this->assertIsArray($result);
            $this->assertArrayHasKey('settings', $result);
        } finally {
            unlink(filename: $tmpFile);
        }
    }

    public function testRenderContainsFileHeader(): void
    {
        $content = ConfigDistGenerator::render();

        $this->assertStringContainsString(
            'This file contains the default configuration for LibreBooking',
            $content
        );
    }

    public function testRenderContainsSectionHeaders(): void
    {
        $content = ConfigDistGenerator::render();

        $this->assertStringContainsString("# Database\n", $content);
        $this->assertStringContainsString("# Authentication Settings\n", $content);
    }

    public function testRenderContainsTopLevelGroupHeaders(): void
    {
        $content = ConfigDistGenerator::render();

        $this->assertStringContainsString("# Application configuration\n", $content);
        $this->assertStringContainsString("# Language and Timezone\n", $content);
        $this->assertStringContainsString("# Frontend\n", $content);
    }

    public function testCheckReturnsTrueForMatchingFile(): void
    {
        $tmpFile = tempnam(directory: sys_get_temp_dir(), prefix: 'config_dist_test_');
        ConfigDistGenerator::writeToFile(path: $tmpFile);

        try {
            $this->assertTrue(ConfigDistGenerator::check(path: $tmpFile));
        } finally {
            unlink(filename: $tmpFile);
        }
    }

    public function testCheckReturnsFalseForMismatchedFile(): void
    {
        $tmpFile = tempnam(directory: sys_get_temp_dir(), prefix: 'config_dist_test_');
        file_put_contents(filename: $tmpFile, data: '<?php return [];');

        try {
            $this->assertFalse(ConfigDistGenerator::check(path: $tmpFile));
        } finally {
            unlink(filename: $tmpFile);
        }
    }

    public function testCheckReturnsFalseForMissingFile(): void
    {
        $path = sys_get_temp_dir() . '/nonexistent_config_dist_test_' . uniqid() . '.php';
        $this->assertFalse(ConfigDistGenerator::check(path: $path));
    }

    public function testRenderOutputHasCorrectTypes(): void
    {
        $content = ConfigDistGenerator::render();

        // Boolean defaults render as true/false, not strings
        $this->assertMatchesRegularExpression(
            "/'app\\.debug' => false,/",
            $content
        );

        // Integer defaults render as integers
        $this->assertMatchesRegularExpression(
            "/'inactivity\\.timeout' => 30,/",
            $content
        );

        // String defaults render as quoted strings
        $this->assertMatchesRegularExpression(
            "/'app\\.title' => 'LibreBooking',/",
            $content
        );
    }
}
