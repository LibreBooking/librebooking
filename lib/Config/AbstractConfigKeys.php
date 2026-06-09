<?php

require_once(ROOT_DIR . 'lib/Config/ConfigKey.php');
require_once(ROOT_DIR . 'lib/Config/ConfigKeysMeta.php');

abstract class AbstractConfigKeys
{
    /** @var array<class-string, list<ConfigKey>> */
    private static array $allCache = [];
    /** @var array<class-string, array<string, ConfigKey>> */
    private static array $allWithEntryIdsCache = [];

    /**
     * Returns all configuration entries defined in the class.
     * @return list<ConfigKey>
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
     * @return array<string, ConfigKey>
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
            } elseif (is_array($value) && array_key_exists('key', $value)) {
                // Build the typed ConfigKey at this single read boundary. fromArray()
                // validates the definition (unknown keys, missing required fields,
                // non-boolean flags, mistyped scalars) and fails with the offending key.
                $configsWithIds[$name] = ConfigKey::fromArray($value);
            }
        }

        self::assertNoCaseInsensitiveLookupCollisions(configsWithIds: $configsWithIds);
        self::$allWithEntryIdsCache[$class] = $configsWithIds;

        return $configsWithIds;
    }

    /**
     * Finds a configuration entry by its key.
     */
    public static function findByKey(string $key): ?ConfigKey
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
     */
    public static function findByLegacyKey(string $legacyKey): ?ConfigKey
    {
        if ($legacyKey === '') {
            return null;
        }

        $normalizedLegacyKey = strtolower($legacyKey);

        foreach (static::all() as $config) {
            $legacy = $config->legacy;
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
        $envKey = ConfigKeysMeta::envKeyForConfig(config: $config);
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
