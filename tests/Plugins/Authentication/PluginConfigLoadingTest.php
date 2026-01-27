<?php

require_once(ROOT_DIR . 'lib/Config/namespace.php');

/**
 * Tests that all authentication plugin configurations load correctly
 * and that plugin configs are properly namespaced under their section keys.
 */
class PluginConfigLoadingTest extends TestBase
{
    private $pluginsDir;

    public function setUp(): void
    {
        parent::setup();
        $this->pluginsDir = ROOT_DIR . 'plugins/Authentication/';
        Configuration::SetInstance(null);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Configuration::SetInstance(null);
    }

    /**
     * Get all authentication plugins that have config files
     */
    private function getAuthenticationPlugins(): array
    {
        $plugins = [];

        if (!is_dir($this->pluginsDir)) {
            $this->fail("Plugins directory not found: {$this->pluginsDir}");
        }

        $dirs = scandir($this->pluginsDir);
        foreach ($dirs as $dir) {
            if ($dir === '.' || $dir === '..') {
                continue;
            }

            $pluginPath = $this->pluginsDir . $dir;
            if (!is_dir($pluginPath)) {
                continue;
            }

            // Check for config.dist.php file
            $configDistFile = $pluginPath . '/' . $dir . '.config.dist.php';
            $configKeysFile = $pluginPath . '/' . $dir . 'ConfigKeys.php';

            if (file_exists($configDistFile) && file_exists($configKeysFile)) {
                $plugins[] = [
                    'name' => $dir,
                    'path' => $pluginPath,
                    'configDistFile' => $configDistFile,
                    'configKeysFile' => $configKeysFile,
                ];
            }
        }

        return $plugins;
    }

    /**
     * Test that all plugin config.dist.php files have proper structure
     */
    public function testAllPluginConfigsHaveProperStructure()
    {
        $plugins = $this->getAuthenticationPlugins();
        $this->assertNotEmpty($plugins, "No authentication plugins found with config files");

        foreach ($plugins as $plugin) {
            $config = require($plugin['configDistFile']);

            // Check basic structure
            $this->assertIsArray($config, "Config for {$plugin['name']} must be an array");
            $this->assertArrayHasKey('settings', $config, "Config for {$plugin['name']} must have 'settings' key");

            $settings = $config['settings'];
            $this->assertIsArray($settings, "Settings for {$plugin['name']} must be an array");

            // Check that settings are wrapped in a plugin-specific section
            $sectionKey = strtolower($plugin['name']);
            $this->assertArrayHasKey(
                $sectionKey,
                $settings,
                "Config for {$plugin['name']} must have settings wrapped in '{$sectionKey}' section array"
            );

            $pluginSettings = $settings[$sectionKey];
            $this->assertIsArray($pluginSettings, "Plugin settings for {$plugin['name']} must be an array");
            $this->assertNotEmpty($pluginSettings, "Plugin settings for {$plugin['name']} should not be empty");
        }
    }

    /**
     * Test that all plugin ConfigKeys classes extend PluginConfigKeys
     */
    public function testAllPluginConfigKeysExtendBaseClass()
    {
        $plugins = $this->getAuthenticationPlugins();

        foreach ($plugins as $plugin) {
            require_once($plugin['configKeysFile']);

            $configKeysClass = $plugin['name'] . 'ConfigKeys';
            $this->assertTrue(class_exists($configKeysClass), "ConfigKeys class {$configKeysClass} not found");

            $reflection = new ReflectionClass($configKeysClass);
            $this->assertTrue(
                $reflection->isSubclassOf('PluginConfigKeys'),
                "{$configKeysClass} must extend PluginConfigKeys"
            );
        }
    }

    /**
     * Test that all ConfigKeys have a section defined and match the plugin name
     */
    public function testAllConfigKeysHaveProperSections()
    {
        $plugins = $this->getAuthenticationPlugins();

        foreach ($plugins as $plugin) {
            require_once($plugin['configKeysFile']);

            $configKeysClass = $plugin['name'] . 'ConfigKeys';
            $expectedSection = strtolower($plugin['name']);

            $allKeys = $configKeysClass::all();
            $this->assertNotEmpty($allKeys, "{$configKeysClass} should have config key definitions");

            foreach ($allKeys as $keyDef) {
                $this->assertArrayHasKey('section', $keyDef, "Config key in {$configKeysClass} must have 'section'");
                $this->assertEquals(
                    $expectedSection,
                    $keyDef['section'],
                    "Config key in {$configKeysClass} must have section '{$expectedSection}', got '{$keyDef['section']}'"
                );
            }
        }
    }

    /**
     * Test that plugin configs can be registered and loaded correctly
     */
    public function testPluginConfigsCanBeRegisteredAndLoaded()
    {
        $plugins = $this->getAuthenticationPlugins();

        foreach ($plugins as $plugin) {
            // Reset configuration for each plugin test
            Configuration::SetInstance(null);

            require_once($plugin['configKeysFile']);

            $configKeysClass = $plugin['name'] . 'ConfigKeys';
            $configId = defined("{$configKeysClass}::CONFIG_ID")
                ? constant("{$configKeysClass}::CONFIG_ID")
                : strtolower($plugin['name']);

            // Register the config with correct parameter order
            Configuration::Instance()->Register(
                $plugin['configDistFile'],
                '', // envFile
                $configId,
                false, // overwrite
                $configKeysClass // configKeysClass
            );

            $config = Configuration::Instance()->File($configId);
            $this->assertNotNull($config, "Config for {$plugin['name']} should be loaded");

            // Test that we can retrieve at least one config value
            $allKeys = $configKeysClass::all();
            if (!empty($allKeys)) {
                $firstKey = $allKeys[0];
                $value = $config->GetKey($firstKey);
                // Value can be anything, just check it doesn't throw an exception
                $this->assertTrue(true, "Successfully retrieved config value for {$plugin['name']}");
            }
        }
    }

    /**
     * Test that plugin configs are only accessible via section-prefixed keys
     */
    public function testPluginConfigsRequireSectionPrefix()
    {
        $plugins = $this->getAuthenticationPlugins();

        foreach ($plugins as $plugin) {
            require_once($plugin['configKeysFile']);

            $configKeysClass = $plugin['name'] . 'ConfigKeys';
            $expectedSection = strtolower($plugin['name']);

            $allKeys = $configKeysClass::all();

            foreach ($allKeys as $keyDef) {
                $bareKey = $keyDef['key'];
                $fullKey = $expectedSection . '.' . $bareKey;

                // Test that findByKey with bare key returns null (because it has a section)
                $foundWithBareKey = $configKeysClass::findByKey($bareKey);
                $this->assertNull(
                    $foundWithBareKey,
                    "Plugin config key '{$bareKey}' in {$configKeysClass} should NOT be accessible without section prefix"
                );

                // Test that findByKey with section-prefixed key returns the config
                $foundWithFullKey = $configKeysClass::findByKey($fullKey);
                $this->assertNotNull(
                    $foundWithFullKey,
                    "Plugin config key '{$fullKey}' in {$configKeysClass} should be accessible with section prefix"
                );

                // Verify it's the same config definition
                $this->assertEquals(
                    $bareKey,
                    $foundWithFullKey['key'],
                    "Found config should have bare key '{$bareKey}'"
                );
                $this->assertEquals(
                    $expectedSection,
                    $foundWithFullKey['section'],
                    "Found config should have section '{$expectedSection}'"
                );
            }
        }
    }

    /**
     * Test that config validation works for plugin configs
     */
    public function testPluginConfigValidation()
    {
        $plugins = $this->getAuthenticationPlugins();

        foreach ($plugins as $plugin) {
            Configuration::SetInstance(null);

            require_once($plugin['configKeysFile']);

            $configKeysClass = $plugin['name'] . 'ConfigKeys';
            $configId = defined("{$configKeysClass}::CONFIG_ID")
                ? constant("{$configKeysClass}::CONFIG_ID")
                : strtolower($plugin['name']);

            // Register the config (this should trigger validation)
            $errorLogs = $this->captureErrorLog(function () use ($plugin, $configKeysClass, $configId) {
                Configuration::Instance()->Register(
                    $plugin['configDistFile'],
                    '', // envFile
                    $configId,
                    false, // overwrite
                    $configKeysClass // configKeysClass
                );
            });

            // Config should load without validation errors for known keys
            // (Unknown keys would trigger warnings, but dist configs should be clean)
            $hasUnknownKeyWarnings = false;
            foreach ($errorLogs as $log) {
                if (strpos($log, 'Unknown config key') !== false) {
                    $hasUnknownKeyWarnings = true;
                    break;
                }
            }

            $this->assertFalse(
                $hasUnknownKeyWarnings,
                "Plugin {$plugin['name']} config.dist.php should not have unknown keys"
            );
        }
    }

    /**
     * Test that actual plugin config files load correctly and all config keys are accessible
     * This tests the real *.config.php files used by the application (not .dist files)
     */
    public function testActualPluginConfigsLoadAllValues()
    {
        $plugins = $this->getAuthenticationPlugins();
        $testedPlugins = 0;
        $skippedPlugins = [];

        foreach ($plugins as $plugin) {
            Configuration::SetInstance(null);

            require_once($plugin['configKeysFile']);

            $configKeysClass = $plugin['name'] . 'ConfigKeys';
            $configId = defined("{$configKeysClass}::CONFIG_ID")
                ? constant("{$configKeysClass}::CONFIG_ID")
                : strtolower($plugin['name']);

            // Try to use actual config file first (what the application uses)
            $actualConfigFile = $plugin['path'] . '/' . $plugin['name'] . '.config.php';

            if (!file_exists($actualConfigFile)) {
                $skippedPlugins[] = $plugin['name'];
                continue; // Skip plugins without actual config files
            }

            // Register the actual config file
            Configuration::Instance()->Register(
                $actualConfigFile,
                '', // envFile
                $configId,
                false, // overwrite
                $configKeysClass // configKeysClass
            );

            $config = Configuration::Instance()->File($configId);
            $this->assertNotNull($config, "Config for {$plugin['name']} should be loaded");

            // Test ALL config keys are accessible via Configuration->GetKey()
            $allKeys = $configKeysClass::all();
            $expectedSection = strtolower($plugin['name']);

            foreach ($allKeys as $keyDef) {
                $keyName = $keyDef['key'];
                $section = $keyDef['section'] ?? null;

                // Test that the value can be retrieved
                try {
                    $value = $config->GetKey($keyDef);
                    // Value retrieved successfully (can be empty string, null, or actual value)
                    $this->assertTrue(true, "Successfully retrieved {$plugin['name']}.{$keyName}");
                } catch (Exception $e) {
                    $this->fail("Failed to retrieve {$plugin['name']}.{$keyName}: " . $e->getMessage());
                }

                // Test that section-prefixed key lookup works
                $fullKey = $section ? "{$section}.{$keyName}" : $keyName;
                $foundKey = $configKeysClass::findByKey($fullKey);
                $this->assertNotNull(
                    $foundKey,
                    "Should find '{$fullKey}' in {$configKeysClass}"
                );

                // If key has a section, verify bare key is NOT accessible
                if ($section) {
                    $bareKey = $configKeysClass::findByKey($keyName);
                    $this->assertNull(
                        $bareKey,
                        "Should NOT find bare key '{$keyName}' in {$configKeysClass} (requires section prefix)"
                    );
                }
            }

            // Output per-plugin summary
            fwrite(STDERR, sprintf(
                "\n  ✓ %s: %d config keys tested",
                $plugin['name'],
                count($allKeys)
            ));

            $testedPlugins++;
        }

        // Report which plugins were tested
        fwrite(STDERR, sprintf(
            "\n\n[SUMMARY] Tested %d plugins (%d config keys total)\n",
            $testedPlugins,
            array_sum(array_map(function ($p) {
                $keysClass = $p['name'] . 'ConfigKeys';
                return count($keysClass::all());
            }, array_filter($plugins, function ($p) {
                return file_exists($p['path'] . '/' . $p['name'] . '.config.php');
            })))
        ));

        if (!empty($skippedPlugins)) {
            fwrite(STDERR, sprintf(
                "[SUMMARY] Skipped %d plugins: %s\n\n",
                count($skippedPlugins),
                implode(', ', $skippedPlugins)
            ));
        }

        if ($testedPlugins > 0) {
            $this->addToAssertionCount(1); // Count this as a successful test
        } else {
            $this->markTestIncomplete(
                "No actual plugin config files found to test. Skipped: " . implode(', ', $skippedPlugins)
            );
        }
    }

    /**
     * Test that config files match their ConfigKeys definitions
     * Validates: 1) No extra keys in config, 2) All expected keys present, 3) Structure matches
     */
    public function testConfigFilesMatchConfigKeyDefinitions()
    {
        $plugins = $this->getAuthenticationPlugins();
        $errors = [];

        foreach ($plugins as $plugin) {
            require_once($plugin['configKeysFile']);

            $configKeysClass = $plugin['name'] . 'ConfigKeys';
            $expectedSection = strtolower($plugin['name']);

            // Load actual config file
            $actualConfigFile = $plugin['path'] . '/' . $plugin['name'] . '.config.php';
            if (!file_exists($actualConfigFile)) {
                continue; // Skip plugins without actual config files
            }

            $loadedConfig = require($actualConfigFile);

            // Verify structure: settings -> section -> keys
            if (!isset($loadedConfig['settings'])) {
                $errors[] = "{$plugin['name']}: Missing 'settings' top-level key";
                continue;
            }

            if (!isset($loadedConfig['settings'][$expectedSection])) {
                $errors[] = "{$plugin['name']}: Missing section '{$expectedSection}' under settings";
                continue;
            }

            $configKeys = $loadedConfig['settings'][$expectedSection];
            $definedKeys = $configKeysClass::all();

            // Build list of expected keys from ConfigKeys
            $expectedKeys = [];
            foreach ($definedKeys as $keyDef) {
                $expectedKeys[] = $keyDef['key'];
            }

            // Check for extra keys in config file that aren't in ConfigKeys
            $actualKeys = array_keys($configKeys);
            $extraKeys = array_diff($actualKeys, $expectedKeys);
            if (!empty($extraKeys)) {
                $errors[] = sprintf(
                    "%s: Extra keys in config file not defined in %s: %s",
                    $plugin['name'],
                    $configKeysClass,
                    implode(', ', $extraKeys)
                );
            }

            // Check for missing keys that are defined in ConfigKeys but not in config
            $missingKeys = array_diff($expectedKeys, $actualKeys);
            if (!empty($missingKeys)) {
                // Filter out keys that have defaults - those are optional
                $criticalMissing = [];
                foreach ($missingKeys as $missingKey) {
                    $keyDef = array_filter($definedKeys, function ($k) use ($missingKey) {
                        return $k['key'] === $missingKey;
                    });
                    $keyDef = reset($keyDef);
                    // If no default is set, it's critical
                    if (!isset($keyDef['default']) || $keyDef['default'] === null) {
                        $criticalMissing[] = $missingKey;
                    }
                }

                if (!empty($criticalMissing)) {
                    $errors[] = sprintf(
                        "%s: Missing required keys in config file (no default value): %s",
                        $plugin['name'],
                        implode(', ', $criticalMissing)
                    );
                }
            }
        }

        // Report all errors
        if (!empty($errors)) {
            fwrite(STDERR, "\n\n[CONFIG VALIDATION ERRORS]\n");
            foreach ($errors as $error) {
                fwrite(STDERR, "  ✗ " . $error . "\n");
            }
            fwrite(STDERR, "\n");

            $this->fail(
                "Config file validation failed:\n  - " . implode("\n  - ", $errors)
            );
        } else {
            fwrite(STDERR, "\n[CONFIG VALIDATION] All plugin configs match their ConfigKeys definitions ✓\n");
            $this->assertTrue(true, "All config files match their ConfigKeys");
        }
    }
}
