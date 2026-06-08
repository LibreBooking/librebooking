<?php

require_once(ROOT_DIR . 'lib/Config/ConfigKeysMeta.php');

abstract class AbstractConfigKeys
{
    /** @var array<class-string, list<ConfigKey|array<string, mixed>>> */
    private static array $allCache = [];
    /** @var array<class-string, array<string, ConfigKey|array<string, mixed>>> */
    private static array $allWithEntryIdsCache = [];

    /**
     * Returns all configuration entries defined in the class.
     * @return list<ConfigKey|array<string, mixed>>
     */
    public static function all(): array
    {
        $class = static::class;
        if (isset(self::$allCache[$class])) {
            return self::$allCache[$class];
        }

        $entries = array_values(self::allWithEntryIds());

        self::$allCache[$class] = $entries;

        return $entries;
    }

    /**
     * Returns all configuration entries keyed by their constant name.
     *
     * The constant name (entry id) lets collision detection distinguish between
     * different schema entries that may share the same key text, e.g. SERVER1_KEY
     * and SERVER2_KEY both using 'key' => 'key' in different sections.
     *
     * @return array<string, ConfigKey|array<string, mixed>>
     */
    private static function allWithEntryIds(): array
    {
        $class = static::class;
        if (isset(self::$allWithEntryIdsCache[$class])) {
            return self::$allWithEntryIdsCache[$class];
        }

        $constants = (new \ReflectionClass($class))->getConstants();

        $configsWithIds = [];
        foreach ($constants as $name => $value) {
            if ($value instanceof ConfigKey) {
                $configsWithIds[$name] = $value;
            } elseif (is_array($value) && isset($value['key'])) {
                if (array_key_exists('allow_custom', $value) && !is_bool($value['allow_custom'])) {
                    throw new \InvalidArgumentException(sprintf(
                        'Config key "%s" in %s has an invalid "allow_custom" value: must be true or false (boolean), got %s',
                        $value['key'],
                        static::class,
                        gettype($value['allow_custom'])
                    ));
                }
                $configsWithIds[$name] = $value;
            }
        }

        self::assertNoCaseInsensitiveLookupCollisions(configsWithIds: $configsWithIds);
        self::$allWithEntryIdsCache[$class] = $configsWithIds;

        return $configsWithIds;
    }

    /**
     * Finds a configuration entry by its key.
     * @return ConfigKey|array<string, mixed>|null
     */
    public static function findByKey(string $key): ConfigKey|array|null
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
     * @return ConfigKey|array<string, mixed>|null
     */
    public static function findByLegacyKey(string $legacyKey): ConfigKey|array|null
    {
        if ($legacyKey === '') {
            return null;
        }

        $normalizedLegacyKey = strtolower($legacyKey);

        foreach (static::all() as $config) {
            $legacy = $config instanceof ConfigKey ? $config->legacy : ($config['legacy'] ?? null);
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
     * @param ConfigKey|array<string, mixed> $config
     */
    public static function isPrivate(ConfigKey|array $config): bool
    {
        if ($config instanceof ConfigKey) {
            return $config->isPrivate;
        }
        if (empty($config)) {
            return false;
        }
        return $config['is_private'] ?? false;
    }

    /** @param ConfigKey|array<string, mixed> $config */
    public static function hasEnv(ConfigKey|array $config): bool
    {
        if ($config instanceof ConfigKey) {
            $envKey = ConfigKeysMeta::envKeyForConfig(config: ['key' => $config->key, 'section' => $config->section]);
        } else {
            $envKey = ConfigKeysMeta::envKeyForConfig(config: $config);
        }
        if ($envKey === null) {
            return false;
        }

        return getenv($envKey) !== false;
    }

    /** @param ConfigKey|array<string, mixed> $config */
    protected static function getCanonicalLookupKey(ConfigKey|array $config): ?string
    {
        if ($config instanceof ConfigKey) {
            return $config->key !== '' ? $config->key : null;
        }
        $key = $config['key'] ?? null;

        return is_string($key) && $key !== '' ? $key : null;
    }

    /**
     * @param array<string, ConfigKey|array<string, mixed>> $configsWithIds
     */
    private static function assertNoCaseInsensitiveLookupCollisions(array $configsWithIds): void
    {
        $seen = [];

        foreach ($configsWithIds as $entryId => $configWithId) {
            $legacyKey = $configWithId instanceof ConfigKey
                ? $configWithId->legacy
                : ($configWithId['legacy'] ?? null);

            // Validate both the canonical lookup key and any legacy alias because
            // either one can collide once lookups become case-insensitive.
            self::assertUniqueCaseInsensitiveKey(
                seen: $seen,
                rawKey: static::getCanonicalLookupKey(config: $configWithId),
                entryId: $entryId,
                configWithId: $configWithId,
                source: 'key'
            );
            self::assertUniqueCaseInsensitiveKey(
                seen: $seen,
                rawKey: $legacyKey,
                entryId: $entryId,
                configWithId: $configWithId,
                source: 'legacy'
            );
        }
    }

    /**
     * @param array<string, mixed> $seen
     * @param ConfigKey|array<string, mixed> $configWithId
     */
    private static function assertUniqueCaseInsensitiveKey(array &$seen, ?string $rawKey, string $entryId, ConfigKey|array $configWithId, string $source): void
    {
        if (!is_string($rawKey) || $rawKey === '') {
            return;
        }

        $configKey = $configWithId instanceof ConfigKey ? $configWithId->key : $configWithId['key'];

        $normalizedKey = strtolower($rawKey);
        if (!isset($seen[$normalizedKey])) {
            // Remember the first schema entry that claims this normalized lookup key.
            $seen[$normalizedKey] = [
                'rawKey' => $rawKey,
                'entryId' => $entryId,
                'key' => $configKey,
                'source' => $source,
            ];
            return;
        }

        $existing = $seen[$normalizedKey];
        // Allow the same schema entry to contribute both a canonical key and a
        // legacy alias that normalize to the same lookup string.
        if ($existing['entryId'] === $entryId) {
            return;
        }

        throw new LogicException(sprintf(
            'Case-insensitive config key collision detected in %s for "%s" between %s "%s" (entry "%s", constant "%s") and %s "%s" (entry "%s", constant "%s")',
            static::class,
            $normalizedKey,
            $existing['source'],
            $existing['rawKey'],
            $existing['key'],
            $existing['entryId'],
            $source,
            $rawKey,
            $configKey,
            $entryId
        ));
    }
}
