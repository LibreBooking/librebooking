<?php

abstract class AbstractConfigKeys
{
    /** @var array<class-string, array> */
    private static array $allCache = [];

    /**
     * Returns all configuration entries defined in the class.
     * @return array
     */
    public static function all(): array
    {
        $class = static::class;
        if (isset(self::$allCache[$class])) {
            return self::$allCache[$class];
        }

        $constants = (new \ReflectionClass($class))->getConstants();

        $all = [];
        foreach ($constants as $value) {
            if (is_array($value) && isset($value['key'])) {
                $all[] = $value;
            }
        }

        self::$allCache[$class] = $all;

        return $all;
    }

    /**
     * Finds a configuration entry by its key.
     * @param string $key
     * @return array|null
     */
    public static function findByKey(string $key): ?array
    {
        $normalizedKey = strtolower($key);

        foreach (static::all() as $config) {
            $lookupKey = static::getCanonicalLookupKey(config: $config);
            if (is_string($lookupKey) && strtolower($lookupKey) === $normalizedKey) {
                return $config;
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
        if ($legacyKey === '') {
            return null;
        }

        $normalizedLegacyKey = strtolower($legacyKey);

        foreach (static::all() as $config) {
            $legacy = $config['legacy'] ?? null;
            if (!is_string($legacy) || $legacy === '') {
                continue;
            }

            if (strtolower($legacy) === $normalizedLegacyKey) {
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
        $key = $config['key'] ?? null;
        if (!is_string($key) || $key === '') {
            return false;
        }

        $envKey = strtoupper('LB_' . preg_replace('/[.\-]+/', '_', $key));

        return getenv($envKey) !== false;
    }

    protected static function getCanonicalLookupKey(array $config): ?string
    {
        $key = $config['key'] ?? null;

        return is_string($key) && $key !== '' ? $key : null;
    }
}
