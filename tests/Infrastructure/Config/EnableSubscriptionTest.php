<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Config/namespace.php');

class EnableSubscriptionTest extends TestBase
{
    public function setUp(): void
    {
        parent::setUp();

        Configuration::SetInstance(null);
    }

    public function testGeneratesKeyWhenIcsEnabledAndKeyEmptyInCurrentNestedFormat(): void
    {
        $path = $this->writeTempConfig(
            <<<'PHP'
<?php
return [
    'settings' => [
        'ics' => [
            'enabled' => true,
            'subscription.key' => '',
        ],
    ],
];
PHP
        );

        try {
            $config = $this->loadConfigurationFile($path);
            $config->EnableSubscription($path);

            $reloaded = $this->loadConfigurationFile($path);
            $newKey = $reloaded->GetKey(ConfigKeys::ICS_SUBSCRIPTION_KEY);

            $this->assertNotEmpty($newKey, 'Subscription key should have been generated');
            $this->assertStringNotContainsString(
                "'subscription.key' => '',",
                file_get_contents($path),
                'Placeholder empty key should have been replaced'
            );
        } finally {
            unlink($path);
        }
    }

    public function testGeneratesKeyAgainstRealConfigDistTemplate(): void
    {
        $path = $this->copyConfigDistTemplate();

        try {
            $config = $this->loadConfigurationFile($path);
            $config->EnableSubscription($path);

            $reloaded = $this->loadConfigurationFile($path);
            $newKey = $reloaded->GetKey(ConfigKeys::ICS_SUBSCRIPTION_KEY);

            $this->assertNotEmpty(
                $newKey,
                'Subscription key should have been generated against the real config.dist.php template'
            );
            $this->assertStringNotContainsString(
                "'subscription.key' => '',",
                file_get_contents($path),
                'Placeholder empty key should have been replaced in config.dist.php'
            );
        } finally {
            unlink($path);
        }
    }

    public function testDoesNothingWhenIcsIsDisabled(): void
    {
        $path = $this->writeTempConfig(
            <<<'PHP'
<?php
return [
    'settings' => [
        'ics' => [
            'enabled' => false,
            'subscription.key' => '',
        ],
    ],
];
PHP
        );

        try {
            $before = file_get_contents($path);

            $config = $this->loadConfigurationFile($path);
            $config->EnableSubscription($path);

            $this->assertSame($before, file_get_contents($path), 'Config file should not change when ICS is disabled');
        } finally {
            unlink($path);
        }
    }

    public function testDoesNothingWhenConfigFileIsUnreadable(): void
    {
        if (!function_exists('posix_getuid') || posix_getuid() === 0) {
            $this->markTestSkipped('File permissions are not enforced when running as root.');
        }

        $path = $this->writeTempConfig(
            <<<'PHP'
<?php
return [
    'settings' => [
        'ics' => [
            'enabled' => true,
            'subscription.key' => '',
        ],
    ],
];
PHP
        );

        try {
            $config = $this->loadConfigurationFile($path);

            chmod($path, 0000);
            $config->EnableSubscription($path);
            chmod($path, 0644);

            $this->assertStringContainsString(
                "'subscription.key' => '',",
                file_get_contents($path),
                'Config file should be left untouched when it cannot be read'
            );
        } finally {
            chmod($path, 0644);
            unlink($path);
        }
    }

    public function testKeepsTheLoadedConfigurationWhenTheConfigFileIsNotWritable(): void
    {
        if (!function_exists('posix_getuid') || posix_getuid() === 0) {
            $this->markTestSkipped('File permissions are not enforced when running as root.');
        }

        $path = $this->writeTempConfig(
            <<<'PHP'
<?php
return [
    'settings' => [
        'ics' => [
            'enabled' => true,
            'subscription.key' => '',
        ],
    ],
];
PHP
        );

        try {
            $config = $this->loadConfigurationFile($path);
            $instance = Configuration::Instance();

            chmod($path, 0444);
            $config->EnableSubscription($path);
            chmod($path, 0644);

            $this->assertStringContainsString(
                "'subscription.key' => '',",
                file_get_contents($path),
                'Config file should be left untouched when it cannot be written'
            );
            $this->assertSame(
                $instance,
                Configuration::Instance(),
                'Cached configuration should be kept when the generated key could not be persisted'
            );
        } finally {
            chmod($path, 0644);
            unlink($path);
        }
    }

    public function testDoesNothingWhenKeyIsAlreadySet(): void
    {
        $path = $this->writeTempConfig(
            <<<'PHP'
<?php
return [
    'settings' => [
        'ics' => [
            'enabled' => true,
            'subscription.key' => 'already-set-key',
        ],
    ],
];
PHP
        );

        try {
            $before = file_get_contents($path);

            $config = $this->loadConfigurationFile($path);
            $config->EnableSubscription($path);

            $this->assertSame($before, file_get_contents($path), 'Config file should not change when a key is already set');
        } finally {
            unlink($path);
        }
    }

    private function copyConfigDistTemplate(): string
    {
        $path = tempnam(sys_get_temp_dir(), 'enable-subscription-test-');
        if ($path === false) {
            throw new RuntimeException('Failed to create temporary config file.');
        }

        if (!copy(ROOT_DIR . 'config/config.dist.php', $path)) {
            throw new RuntimeException('Failed to copy config.dist.php to temporary path.');
        }

        return $path;
    }

    private function writeTempConfig(string $contents): string
    {
        $path = tempnam(sys_get_temp_dir(), 'enable-subscription-test-');
        if ($path === false) {
            throw new RuntimeException('Failed to create temporary config file.');
        }

        if (file_put_contents($path, $contents) === false) {
            throw new RuntimeException('Failed to write temporary config file.');
        }

        return $path;
    }

    private function loadConfigurationFile(string $path): ConfigurationFile
    {
        $conf = [];
        $loaded = require $path;

        if (is_array($loaded) && isset($loaded['settings'])) {
            return new ConfigurationFile($loaded);
        }

        if (isset($conf['settings'])) {
            return new ConfigurationFile([Configuration::SETTINGS => $conf['settings']]);
        }

        throw new RuntimeException("Invalid config file: 'settings' section missing");
    }
}
