<?php

require_once(ROOT_DIR . 'lib/Config/ConfigKeysMeta.php');

abstract class AbstractConfigKeys
{
    /** @var array<class-string, array> */
    private static array $allCache = [];
    /** @var array<class-string, array> */
    private static array $allWithEntryIdsCache = [];

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

        $entries = [];
        foreach (self::allWithEntryIds() as $value) {
            unset($value['_entry_id']);
            $entries[] = $value;
        }

        self::$allCache[$class] = $entries;

        return $entries;
    }

    /**
     * @return array<int, array<string, mixed>>
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
            if (is_array($value) && isset($value['key'])) {
                if (array_key_exists('allow_custom', $value) && !is_bool($value['allow_custom'])) {
                    throw new \InvalidArgumentException(sprintf(
                        'Config key "%s" in %s has an invalid "allow_custom" value: must be true or false (boolean), got %s',
                        $value['key'],
                        static::class,
                        gettype($value['allow_custom'])
                    ));
                }
                // Preserve the constant name so collision detection can distinguish
                // between different schema entries that may share the same key text,
                // e.g. SERVER1_KEY and SERVER1_KEY_DUPLICATE_SECTION_CASE both using
                // 'key' => 'key'.
                $value['_entry_id'] = $name;
                $configsWithIds[] = $value;
            }
        }

        self::assertNoCaseInsensitiveLookupCollisions(configsWithIds: $configsWithIds);
        self::$allWithEntryIdsCache[$class] = $configsWithIds;

        return $configsWithIds;
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
        $envKey = ConfigKeysMeta::envKeyForConfig(config: $config);
        if ($envKey === null) {
            return false;
        }

        return getenv($envKey) !== false;
    }

    protected static function getCanonicalLookupKey(array $config): ?string
    {
        $key = $config['key'] ?? null;

        return is_string($key) && $key !== '' ? $key : null;
    }

    private static function assertNoCaseInsensitiveLookupCollisions(array $configsWithIds): void
    {
        $seen = [];

        foreach ($configsWithIds as $configWithId) {
            // Validate both the canonical lookup key and any legacy alias because
            // either one can collide once lookups become case-insensitive.
            self::assertUniqueCaseInsensitiveKey(
                seen: $seen,
                rawKey: static::getCanonicalLookupKey(config: $configWithId),
                configWithId: $configWithId,
                source: 'key'
            );
            self::assertUniqueCaseInsensitiveKey(
                seen: $seen,
                rawKey: $configWithId['legacy'] ?? null,
                configWithId: $configWithId,
                source: 'legacy'
            );
        }
    }

    private static function assertUniqueCaseInsensitiveKey(array &$seen, ?string $rawKey, array $configWithId, string $source): void
    {
        if (!is_string($rawKey) || $rawKey === '') {
            return;
        }

        $normalizedKey = strtolower($rawKey);
        if (!isset($seen[$normalizedKey])) {
            // Remember the first schema entry that claims this normalized lookup key.
            $seen[$normalizedKey] = [
                'rawKey' => $rawKey,
                'entryId' => $configWithId['_entry_id'],
                'key' => $configWithId['key'],
                'source' => $source,
            ];
            return;
        }

        $existing = $seen[$normalizedKey];
        // Allow the same schema entry to contribute both a canonical key and a
        // legacy alias that normalize to the same lookup string.
        if ($existing['entryId'] === $configWithId['_entry_id']) {
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
            $configWithId['key'],
            $configWithId['_entry_id']
        ));
    }
}
