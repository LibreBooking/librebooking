<?php

/**
 * Typed definition of a single application configuration entry.
 *
 * ConfigKey instances are declared as class constants on ConfigKeys and
 * plugin *ConfigKeys classes. Once the migration from associative arrays
 * is complete they will be passed directly to Configuration::GetKey() to
 * retrieve the live value from config.php or the matching environment variable.
 */
readonly class ConfigKey
{
    private const VALID_TYPES = ['string', 'boolean', 'integer'];

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
}
