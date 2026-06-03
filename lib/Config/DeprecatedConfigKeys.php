<?php

/**
 * Registry of configuration keys that have been deprecated and removed.
 *
 * When a config key is removed from ConfigKeys, add it here so that
 * existing config files using the old key produce a helpful deprecation
 * message instead of an "Unknown config key" warning.
 */
class DeprecatedConfigKeys
{
    /**
     * Maps removed canonical config keys to the reason they were removed.
     *
     * Format: 'key' => 'reason for removal'
     *
     * Keys MUST be lowercase — findReason() normalizes lookups via
     * strtolower(), so mixed-case entries here will never match.
     *
     * @var array<string, string>
     */
    public const DEPRECATED_KEYS = [
        'security.x-xss' => 'X-XSS-Protection header is obsolete and has been removed from all modern browsers',
    ];

    /**
     * Maps removed legacy config keys to the reason they were removed.
     *
     * These are the legacy (pre-rewrite) forms of removed keys
     * (e.g. 'security.security.x-xss' for canonical 'security.x-xss').
     * Separated so that when legacy key support is removed entirely,
     * this constant can simply be deleted.
     *
     * Keys MUST be lowercase.
     *
     * @var array<string, string>
     */
    public const DEPRECATED_LEGACY_KEYS = [
        'security.security.x-xss' => 'X-XSS-Protection header is obsolete and has been removed from all modern browsers',
    ];

    /**
     * Checks if a key has been deprecated and removed.
     * Returns the removal reason if found, or null if the key is not
     * a known deprecated key.
     */
    public static function findReason(string $key): ?string
    {
        $normalizedKey = strtolower($key);

        return self::DEPRECATED_KEYS[$normalizedKey]
            ?? self::DEPRECATED_LEGACY_KEYS[$normalizedKey]
            ?? null;
    }
}
