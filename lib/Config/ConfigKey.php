<?php

/**
 * Typed definition of a single application configuration entry.
 *
 * Config keys are defined as associative-array class constants on ConfigKeys and
 * the plugin *ConfigKeys classes. Those arrays are converted to ConfigKey
 * instances at runtime via ConfigKey::fromArray() at the read boundary
 * (AbstractConfigKeys::all()/findByKey() and Configuration::GetKey()) rather than
 * being declared as `new ConfigKey(...)` constants directly, because `new` in a
 * class constant is only supported from PHP 8.5 and the project floor is PHP 8.2.
 * Consumers receive a typed ConfigKey; the constructor and fromArray() validate the
 * definition.
 */
readonly class ConfigKey
{
    private const VALID_TYPES = ['string', 'boolean', 'integer'];

    /** Array keys that every config definition must provide. */
    private const REQUIRED_KEYS = ['key', 'type', 'default'];

    /** Array keys a config definition may provide but is not required to. */
    private const OPTIONAL_KEYS = [
        'section',
        'label',
        'description',
        'choices',
        'config_file_comment',
        'legacy',
        'is_private',
        'is_hidden',
        'allow_custom',
        'is_protected',
    ];

    /**
     * Top-level field names allowed in an array config definition (required plus
     * optional). Anything outside this set passed to fromArray() is a typo and is
     * rejected.
     */
    private const KNOWN_KEYS = [...self::REQUIRED_KEYS, ...self::OPTIONAL_KEYS];

    /** Array keys whose values must be booleans when present. */
    private const BOOLEAN_KEYS = ['is_private', 'is_hidden', 'allow_custom', 'is_protected'];

    /** Array keys whose values must be strings when present. */
    private const STRING_KEYS = ['key', 'type'];

    /** Array keys whose values must be a string or null when present. */
    private const NULLABLE_STRING_KEYS = ['section', 'label', 'description', 'config_file_comment', 'legacy'];

    public function __construct(
        /** Dot-separated config file key, e.g. 'app.title' or 'database.name'. */
        public string $key,
        /** Value type: 'string', 'boolean', or 'integer'. Controls validation and conversion. */
        public string $type,
        /** Value used when the key is absent from config.php and no env var is set. */
        public mixed $default,
        /** Config file section, e.g. 'database'. When set, the value is read from $conf['settings'][$section] using the key with the section prefix removed (e.g. section='database', key='database.name' reads $conf['settings']['database']['name']). */
        public ?string $section = null,
        /** Short human-readable label shown in the admin configuration UI. */
        public ?string $label = null,
        /** Longer explanatory text shown as a tooltip in the admin configuration UI. */
        public ?string $description = null,
        /**
         * Allowed values for select/datalist inputs, keyed by stored value with display label as value.
         * When allowCustom is false, ValidateConfig() enforces this list strictly.
         *
         * @var array<int|string, string>|null
         */
        public ?array $choices = null,
        /** Comment written above the key in generated config.dist.php. Falls back to description if null. */
        public ?string $configFileComment = null,
        /** Former key name. When set, old configs using this name are transparently migrated on load. */
        public ?string $legacy = null,
        /** When true, the admin UI treats the value as sensitive and preserves the existing value when the submitted value is empty. */
        public bool $isPrivate = false,
        /** When true, the key is not shown in the admin configuration UI at all. */
        public bool $isHidden = false,
        /** When true, choices acts as suggestions only; any valid PHP class name is accepted. Used for plugin selectors. */
        public bool $allowCustom = false,
        /** When true (plugin use only), the value is not overwritten when the admin UI saves other fields. */
        public bool $isProtected = false,
    ) {
        if (trim($this->key) === '') {
            throw new \InvalidArgumentException('Config key cannot be empty or whitespace');
        }
        if (!in_array($this->type, self::VALID_TYPES, strict: true)) {
            throw new \InvalidArgumentException(
                "Invalid config type '{$this->type}' for key '{$this->key}'"
            );
        }
    }

    /**
     * Build a ConfigKey from a legacy associative-array definition.
     *
     * Maps the snake_case array keys to the camelCase constructor parameters and
     * validates the definition at this single conversion point: unknown keys and
     * non-boolean flag values are rejected, so definition typos surface the first
     * time the config registry is built (the constructor additionally validates
     * key/type).
     *
     * @param array<string, mixed> $def
     */
    public static function fromArray(array $def): self
    {
        // Build a descriptor used only for error messages. The section is included
        // when present because plugin definitions can share the same bare 'key' in
        // different sections (e.g. SERVER1_KEY and SERVER2_KEY both 'key' => 'key'),
        // so the key alone would not identify which definition is invalid.
        $configKey = is_string($def['key'] ?? null) ? $def['key'] : '';
        $section = (isset($def['section']) && is_string($def['section']) && $def['section'] !== '')
            ? $def['section']
            : null;
        $keyDescriptor = $section !== null
            ? sprintf('"%s" (section "%s")', $configKey, $section)
            : sprintf('"%s"', $configKey);

        $unknownKeys = array_diff(array_keys($def), self::KNOWN_KEYS);
        if ($unknownKeys !== []) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown config definition key(s) [%s] for config key %s',
                implode(', ', $unknownKeys),
                $keyDescriptor
            ));
        }

        // Every definition must provide the required keys. 'default' in particular
        // is indexed without guarding by consumers such as
        // ConfigurationFile::ValidateConfig() and ConfigDistGenerator, so an omitted
        // value (an explicit null is allowed) must not pass this gate.
        foreach (self::REQUIRED_KEYS as $requiredKey) {
            if (!array_key_exists($requiredKey, $def)) {
                throw new \InvalidArgumentException(sprintf(
                    'Config key %s is missing a required "%s" value',
                    $keyDescriptor,
                    $requiredKey
                ));
            }
        }

        foreach (self::BOOLEAN_KEYS as $booleanKey) {
            if (array_key_exists($booleanKey, $def) && !is_bool($def[$booleanKey])) {
                throw new \InvalidArgumentException(sprintf(
                    'Config key %s has an invalid "%s" value: must be true or false (boolean), got %s',
                    $keyDescriptor,
                    $booleanKey,
                    gettype($def[$booleanKey])
                ));
            }
        }

        // Validate scalar field types explicitly: ConfigKey.php is not strict_types,
        // so without these checks PHP would silently coerce e.g. 'key' => 123 to "123".
        foreach (self::STRING_KEYS as $stringKey) {
            if (array_key_exists($stringKey, $def) && !is_string($def[$stringKey])) {
                throw new \InvalidArgumentException(sprintf(
                    'Config key %s has an invalid "%s" value: must be a string, got %s',
                    $keyDescriptor,
                    $stringKey,
                    gettype($def[$stringKey])
                ));
            }
        }

        foreach (self::NULLABLE_STRING_KEYS as $nullableStringKey) {
            if (array_key_exists($nullableStringKey, $def)
                && $def[$nullableStringKey] !== null
                && !is_string($def[$nullableStringKey])) {
                throw new \InvalidArgumentException(sprintf(
                    'Config key %s has an invalid "%s" value: must be a string or null, got %s',
                    $keyDescriptor,
                    $nullableStringKey,
                    gettype($def[$nullableStringKey])
                ));
            }
        }

        if (array_key_exists('choices', $def) && $def['choices'] !== null && !is_array($def['choices'])) {
            throw new \InvalidArgumentException(sprintf(
                'Config key %s has an invalid "choices" value: must be an array or null, got %s',
                $keyDescriptor,
                gettype($def['choices'])
            ));
        }

        return new self(
            key: $def['key'],
            type: $def['type'],
            default: $def['default'],
            section: $def['section'] ?? null,
            label: $def['label'] ?? null,
            description: $def['description'] ?? null,
            choices: $def['choices'] ?? null,
            configFileComment: $def['config_file_comment'] ?? null,
            legacy: $def['legacy'] ?? null,
            isPrivate: $def['is_private'] ?? false,
            isHidden: $def['is_hidden'] ?? false,
            allowCustom: $def['allow_custom'] ?? false,
            isProtected: $def['is_protected'] ?? false,
        );
    }
}
