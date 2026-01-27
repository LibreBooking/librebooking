<?php

abstract class PluginConfigKeys
{
    /**
     * Returns all configuration entries defined in the class.
     * @return array
     */
    public static function all(): array
    {
        $constants = (new \ReflectionClass(static::class))->getConstants();

        $all = [];
        foreach ($constants as $name => $value) {
            if (is_array($value) && isset($value['key'])) {
                $all[] = $value;
            }
        }

        return $all;
    }

    /**
     * Finds a configuration entry by its key.
     * @param string $key
     * @return array|null
     */
    public static function findByKey(string $key): ?array
    {
        foreach (static::all() as $config) {
            $configKey = $config['key'] ?? null;
            $section = $config['section'] ?? null;

            // If this config has a section, only match with the full key (section.key)
            if ($section) {
                $fullKey = "{$section}.{$configKey}";
                if ($fullKey === $key) {
                    return $config;
                }
            } else {
                if ($configKey === $key) {
                    return $config;
                }
            }
        }

        return null;
    }

    /**
     * Finds a configuration entry by its legacy key.
     * @param string $legacyKey
     * @return array|null
     */
    public static function findByLegacyKey(string $legacyKey): ?array
    {
        foreach (static::all() as $config) {
            if (($config['legacy'] ?? null) === $legacyKey) {
                return $config;
            }
        }

        return null;
    }

    /**
     * Checks if a configuration entry is private (should not be displayed in UI).
     * @param array $config
     * @return bool
     */
    public static function isPrivate($config): bool
    {
        if (empty($config)) {
            return false;
        }
        return $config['is_private'] ?? false;
    }

    public static function hasEnv($config): bool
    {
        if (empty($config)) {
            return false;
        }
        $loadedEnvVars = getenv();
        $envKey = strtoupper('LB_' . preg_replace('/[.\-]+/', '_', $config['key']));
        return array_key_exists($envKey, $loadedEnvVars) ?? false;
    }
}
