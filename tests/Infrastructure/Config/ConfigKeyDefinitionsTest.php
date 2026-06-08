<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Config/namespace.php');

/**
 * Guards that every real config definition — core and plugin — is a valid
 * ConfigKey definition by running ConfigKey::fromArray() over all of them.
 *
 * This catches a malformed constant (unknown field, non-boolean flag, invalid
 * type) in CI at its source, rather than letting it surface later when the read
 * boundary (AbstractConfigKeys::all()/findByKey(), Configuration::GetKey())
 * builds the registry.
 *
 * Reflects the raw array constants rather than calling all() so it remains valid
 * regardless of what all() returns as the migration progresses.
 */
class ConfigKeyDefinitionsTest extends TestBase
{
    public function testAllCoreConfigKeysConvertToConfigKey(): void
    {
        $converted = $this->assertAllConstantsConvert(ConfigKeys::class);

        $this->assertGreaterThan(0, $converted, 'Expected ConfigKeys to define config entries');
    }

    public function testAllPluginConfigKeysConvertToConfigKey(): void
    {
        $files = glob(ROOT_DIR . 'plugins/*/*/*ConfigKeys.php');
        $this->assertNotEmpty($files, 'Expected to discover plugin *ConfigKeys.php files');

        $classesChecked = 0;
        foreach ($files as $file) {
            require_once($file);
            $class = basename($file, '.php');
            $this->assertTrue(class_exists($class), "Plugin ConfigKeys class {$class} not found after loading {$file}");

            $this->assertAllConstantsConvert($class);
            $classesChecked++;
        }

        $this->assertGreaterThan(0, $classesChecked, 'Expected to check at least one plugin ConfigKeys class');
    }

    /**
     * Convert every array-shaped config constant on the class via fromArray(),
     * failing if any definition is invalid. Returns the number converted.
     *
     * @param class-string $configKeysClass
     */
    private function assertAllConstantsConvert(string $configKeysClass): int
    {
        $constants = (new \ReflectionClass($configKeysClass))->getConstants();

        $converted = 0;
        foreach ($constants as $name => $value) {
            // array_key_exists (not isset) so a malformed 'key' => null definition is
            // still handed to fromArray() and rejected, rather than silently skipped.
            if (!is_array($value) || !array_key_exists('key', $value)) {
                continue;
            }

            try {
                $configKey = ConfigKey::fromArray($value);
            } catch (\InvalidArgumentException $e) {
                $this->fail(sprintf(
                    'Config definition %s::%s did not convert to a ConfigKey: %s',
                    $configKeysClass,
                    $name,
                    $e->getMessage()
                ));
            }

            $this->assertSame(
                $value['key'],
                $configKey->key,
                "ConfigKey built from {$configKeysClass}::{$name} should preserve the 'key'"
            );
            $converted++;
        }

        return $converted;
    }
}
