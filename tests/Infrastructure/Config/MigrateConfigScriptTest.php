<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'tests/TestBase.php');
require_once(ROOT_DIR . 'lib/Config/namespace.php');
require_once(ROOT_DIR . 'lib/Config/MigrateConfig.php');

class MigrateConfigScriptTest extends TestBase
{
    /** @var array<int, string> */
    private array $tempConfigPaths = [];

    public function testLoadSettingsReadsNewFormatConfig(): void
    {
        $path = $this->writeTempConfig(
            <<<'PHP'
<?php
return [
    'settings' => [
        'app.title' => 'Example',
    ],
];
PHP
        );

        $this->assertSame(
            ['app.title' => 'Example'],
            MigrateConfig::LoadSettings($path)
        );
    }

    public function testLoadSettingsReadsLegacyConfigSideEffects(): void
    {
        $path = $this->writeTempConfig(
            <<<'PHP'
<?php
$conf['settings']['app.title'] = 'Legacy Example';
PHP
        );

        $settings = MigrateConfig::LoadSettings($path);

        $this->assertSame('Legacy Example', $settings['app.title']);
    }

    public function testLoadSettingsIncludesPathInInvalidConfigException(): void
    {
        $path = $this->writeTempConfig(
            <<<'PHP'
<?php
return ['not_settings' => []];
PHP
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Invalid config file '$path': 'settings' section missing");

        MigrateConfig::LoadSettings($path);
    }

    public function testMergeSettingsPreservesExistingValuesAndAddsTemplateDefaults(): void
    {
        $merged = MigrateConfig::MergeSettings(
            currentSettings: [
                'app' => [
                    'title' => 'Custom',
                ],
            ],
            templateSettings: [
                'app' => [
                    'title' => 'Default',
                    'debug' => false,
                ],
                'logging' => [
                    'level' => 'error',
                ],
            ]
        );

        $this->assertSame('Custom', $merged['app']['title']);
        $this->assertFalse($merged['app']['debug']);
        $this->assertSame('error', $merged['logging']['level']);
    }

    public function testExportArrayUsesSchemaTypesForBooleanIntegerAndStringValues(): void
    {
        $exported = MigrateConfig::ExportArray([
            Configuration::SETTINGS => [
                'app' => [
                    'debug' => 'true',
                ],
                'inactivity' => [
                    'timeout' => '30',
                ],
                'app.title' => 'true',
                'install.password' => '1234',
            ],
        ]);

        $this->assertStringContainsString("'debug' => true", $exported);
        $this->assertStringContainsString("'timeout' => 30", $exported);
        $this->assertStringContainsString("'app.title' => 'true'", $exported);
        $this->assertStringContainsString("'install.password' => '1234'", $exported);
    }

    private function writeTempConfig(string $contents): string
    {
        $path = tempnam(sys_get_temp_dir(), 'migrate-config-test-');
        if ($path === false) {
            throw new RuntimeException('Failed to create temporary config file.');
        }

        if (file_put_contents($path, $contents) === false) {
            throw new RuntimeException('Failed to write temporary config file.');
        }

        $this->tempConfigPaths[] = $path;

        return $path;
    }

    public function tearDown(): void
    {
        foreach ($this->tempConfigPaths as $path) {
            if (file_exists($path)) {
                unlink($path);
            }
        }

        parent::tearDown();
    }
}
