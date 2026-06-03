<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Config/namespace.php');
require_once(ROOT_DIR . 'lib/Config/Configurator.php');

class ConfiguratorTest extends TestBase
{
    private Configurator $configurator;

    public function setUp(): void
    {
        parent::setUp();
        $this->configurator = new Configurator();
    }

    public function testBuildConfigReturnsNewValuesWhenPreserveExistingIsFalse(): void
    {
        $current = [
            'test.title' => 'My Custom Title',
            'test.email' => 'me@example.com',
        ];
        $new = [
            'test.title' => 'Default Title',
            'test.email' => 'default@example.com',
        ];

        $result = $this->configurator->BuildConfig(
            currentSettings: $current,
            newSettings: $new,
            preserveExisting: false
        );

        $this->assertEquals('Default Title', $result['test.title']);
        $this->assertEquals('default@example.com', $result['test.email']);
    }

    public function testBuildConfigPreservesExistingValuesWhenPreserveExistingIsTrue(): void
    {
        $current = [
            'test.title' => 'My Custom Title',
            'test.email' => 'me@example.com',
        ];
        $new = [
            'test.title' => 'Default Title',
            'test.email' => 'default@example.com',
        ];

        $result = $this->configurator->BuildConfig(
            currentSettings: $current,
            newSettings: $new,
            preserveExisting: true
        );

        $this->assertEquals('My Custom Title', $result['test.title']);
        $this->assertEquals('me@example.com', $result['test.email']);
    }

    public function testBuildConfigAddsNewKeysWhenPreserveExistingIsTrue(): void
    {
        $current = [
            'test.title' => 'My Custom Title',
        ];
        $new = [
            'test.title' => 'Default Title',
            'test.newkey' => 'new-default-value',
        ];

        $result = $this->configurator->BuildConfig(
            currentSettings: $current,
            newSettings: $new,
            preserveExisting: true
        );

        $this->assertEquals('My Custom Title', $result['test.title']);
        $this->assertEquals('new-default-value', $result['test.newkey']);
    }

    public function testBuildConfigPreservesExistingNestedValuesWhenPreserveExistingIsTrue(): void
    {
        $current = [
            'test.section' => [
                'type' => 'mysql',
                'host' => 'my-db-server',
                'name' => 'my_database',
            ],
        ];
        $new = [
            'test.section' => [
                'type' => 'mysql',
                'host' => 'localhost',
                'name' => 'librebooking',
                'port' => '3306',
            ],
        ];

        $result = $this->configurator->BuildConfig(
            currentSettings: $current,
            newSettings: $new,
            preserveExisting: true
        );

        $this->assertEquals('my-db-server', $result['test.section']['host']);
        $this->assertEquals('my_database', $result['test.section']['name']);
        $this->assertEquals('3306', $result['test.section']['port'], 'New nested key should be added');
    }

    public function testBuildConfigKeepsMissingKeysFromCurrentSettings(): void
    {
        $current = [
            'test.title' => 'My Title',
            'test.custom' => 'custom-value',
        ];
        $new = [
            'test.title' => 'Default Title',
        ];

        $result = $this->configurator->BuildConfig(
            currentSettings: $current,
            newSettings: $new,
            keepMissingKeys: true,
            preserveExisting: true
        );

        $this->assertEquals('My Title', $result['test.title']);
        $this->assertEquals('custom-value', $result['test.custom'], 'Keys only in current should be kept');
    }

    public function testBuildConfigDropsMissingKeysWhenKeepMissingIsFalse(): void
    {
        $current = [
            'test.title' => 'My Title',
            'test.custom' => 'custom-value',
        ];
        $new = [
            'test.title' => 'Default Title',
        ];

        $result = $this->configurator->BuildConfig(
            currentSettings: $current,
            newSettings: $new,
            keepMissingKeys: false,
            preserveExisting: true
        );

        $this->assertEquals('My Title', $result['test.title']);
        $this->assertArrayNotHasKey('test.custom', $result, 'Keys only in current should be dropped');
    }

    /**
     * Reproduces the bug from issue #1222: visiting manage_configuration.php
     * overwrites all config values with defaults when config.dist.php has a
     * new key not present in config.php.
     */
    public function testBuildConfigDoesNotOverwriteExistingValuesWhenNewKeyIsAdded(): void
    {
        $current = [
            'test.title' => 'My Company Booking',
            'test.email' => 'admin@mycompany.com',
            'test.section' => [
                'type' => 'mysql',
                'host' => 'db.mycompany.com',
            ],
        ];
        $dist = [
            'test.title' => 'Default Title',
            'test.email' => 'default@example.com',
            'test.section' => [
                'type' => 'mysql',
                'host' => 'localhost',
            ],
            'test.brand.new' => 'default-value',
        ];

        // This is what GetMerged() does internally
        $result = $this->configurator->BuildConfig(
            currentSettings: $current,
            newSettings: $dist,
            keepMissingKeys: true,
            preserveExisting: true
        );

        $this->assertEquals('My Company Booking', $result['test.title'], 'Existing title must not be overwritten');
        $this->assertEquals('admin@mycompany.com', $result['test.email'], 'Existing email must not be overwritten');
        $this->assertEquals('db.mycompany.com', $result['test.section']['host'], 'Existing nested value must not be overwritten');
        $this->assertEquals('default-value', $result['test.brand.new'], 'New key from dist should be added');
    }
}
