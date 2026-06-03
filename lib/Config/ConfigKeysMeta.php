<?php

class ConfigKeysMeta
{
    /**
     * Return the canonical config key name for an entry.
     *
     * Plugin entries with a section use the fully qualified section.key name.
     */
    public static function canonicalKeyName(array $config): ?string
    {
        $key = $config['key'] ?? null;
        if (!is_string($key) || $key === '') {
            return null;
        }

        $section = $config['section'] ?? null;
        if (is_string($section) && $section !== '') {
            // Main config entries already store fully qualified keys like
            // "reservation.start.time.constraint" and use "section" only for grouping.
            if (str_starts_with($key, $section . '.')) {
                return $key;
            }

            // Plugin config entries store the leaf key plus a separate section, so
            // their canonical name needs to be reconstructed as "section.key".
            return "{$section}.{$key}";
        }

        return $key;
    }

    /**
     * Get the comment text for a config entry.
     *
     * Prefers 'config_file_comment' field, falls back to 'description'.
     */
    public static function getComment(array $entry): string
    {
        $comment = $entry['config_file_comment'] ?? null;
        if (is_string($comment) && $comment !== '') {
            return $comment;
        }

        return $entry['description'] ?? '';
    }

    /**
     * Get the human-readable title for a section.
     */
    public static function sectionTitle(string $section): string
    {
        return self::SECTION_TITLES[$section]
            ?? ucwords(string: str_replace(search: ['.', '-'], replace: ' ', subject: $section));
    }

    /**
     * Group flat config entries according to TOP_LEVEL_GROUPS ordering.
     *
     * @param array<string, array> $flatEntries
     * @return array<string, array<string, array>>
     *
     * @throws \LogicException if a group references an unknown key, a key is in multiple groups,
     *                         or flat keys are not covered by any group
     */
    public static function groupFlatEntries(array $flatEntries): array
    {
        $groups = [];
        $assignedKeys = [];

        foreach (self::TOP_LEVEL_GROUPS as $groupTitle => $groupKeys) {
            $groupEntries = [];

            foreach ($groupKeys as $key) {
                if (!array_key_exists($key, $flatEntries)) {
                    throw new \LogicException("Top-level group '{$groupTitle}' references unknown flat key '{$key}'");
                }
                if (isset($assignedKeys[$key])) {
                    throw new \LogicException("Flat key '{$key}' is assigned to multiple top-level groups");
                }

                $groupEntries[$key] = $flatEntries[$key];
                $assignedKeys[$key] = true;
            }

            $groups[$groupTitle] = $groupEntries;
        }

        $unassignedKeys = array_diff(array_keys($flatEntries), array_keys($assignedKeys));
        if ($unassignedKeys !== []) {
            throw new \LogicException(
                'Top-level groups are missing flat keys: ' . implode(', ', $unassignedKeys)
            );
        }

        return $groups;
    }

    /**
     * Derive the environment variable name for a config entry definition.
     */
    public static function envKeyForConfig(array $config): ?string
    {
        $canonicalKey = self::canonicalKeyName(config: $config);
        if ($canonicalKey === null) {
            return null;
        }

        return strtoupper('LB_' . preg_replace('/[.\-]+/', '_', $canonicalKey));
    }

    public const SECTION_TITLES = [
        'api' => 'API Configuration',
        'authentication' => 'Authentication Settings',
        'cleanup' => 'Data Retention and Deletion',
        'credits' => 'Credit System Settings',
        'database' => 'Database',
        'email' => 'Email',
        'ics' => 'ICS Settings',
        'logging' => 'Logging',
        'pages' => 'Pages',
        'password' => 'Password Policy',
        'phpmailer' => 'Email Sending (PHPMailer)',
        'plugins' => 'Plugin Configuration',
        'privacy' => 'Privacy',
        'recaptcha' => 'reCAPTCHA',
        'registration' => 'Registration',
        'reports' => 'Reporting and Registration',
        'reservation' => 'Reservations',
        'reservation.labels' => 'Reservation Label Templates',
        'reservation.notify' => 'Notification Settings for Reservations',
        'resource' => 'Resource Options',
        'schedule' => 'Schedule Display and Behavior',
        'security' => 'Security Headers',
        'tablet.view' => 'Tablet View Options',
        'uploads' => 'Uploads',
    ];

    public const TOP_LEVEL_GROUPS = [
        'Application configuration' => [
            'app.title',
            'app.debug',
            'admin.email',
            'admin.email.name',
            'company.name',
            'company.url',
        ],
        'Language and Timezone' => [
            'default.timezone',
            'default.language',
            'enabled.languages',
        ],
        'Frontend' => [
            'script.url',
            'install.password',
            'cache.templates',
            'use.local.js.libs',
            'inactivity.timeout',
            'home.url',
            'logout.url',
            'default.homepage',
            'default.page.size',
            'css.extension.file',
            'css.theme',
            'name.format',
        ],
        'Analytics Integration' => [
            'google.analytics.tracking.id',
        ],
        'Slack Integration' => [
            'slack.token',
        ],
    ];
}
