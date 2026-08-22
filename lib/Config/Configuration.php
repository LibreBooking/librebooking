<?php

require_once(ROOT_DIR . 'lib/Common/Helpers/namespace.php');

use Dotenv\Dotenv;

/** * Interface IConfiguration
 *
 * This interface defines the methods for accessing configuration settings in LibreBooking.
 * It provides methods to retrieve configuration values, manage admin emails, and handle script URLs.
 */
interface IConfiguration extends IConfigurationFile
{
    /**
     * @param string $configFile
     * @param string $envFile
     * @param string $configId
     * @param bool $overwrite
     * @param null|string $configKeysClass
     */
    public function Register($configFile, $envFile, $configId, $overwrite = false, $configKeysClass = null);

    /**
     * @param string $configId
     * @return Configuration
     */
    public function File($configId);
}

interface IConfigurationFile
{
    /**
     * @param array $name
     * @param null|IConvert $converter
     * @return mixed|string
     */
    public function GetKey($name, $converter = null);

    /**
     * @return string the full url to the root of this LibreBooking instance WITHOUT the trailing /
     */
    public function GetScriptUrl();

    /**
     * @return string
     */
    public function GetDefaultTimezone();

    /**
     * @param string|null $emailAddress
     * @return bool
     */
    public function IsAdminEmail(?string $emailAddress): bool;

    /**
     * @return string[]
     */
    public function GetAllAdminEmails();

    /**
     * @return string
     */
    public function GetAdminEmail();

    /**
     * Enables ics subscription with a random key if no key is set.
     * @param string|null $configFilePath Path to rewrite; defaults to the live config file.
     * @return void
     */
    public function EnableSubscription(?string $configFilePath = null);
}

/**
 * Class Configuration
 *
 * This class implements the IConfiguration interface and provides methods to manage configuration settings.
 * It allows registering configuration files, retrieving configuration values, and managing admin emails.
 */
class Configuration implements IConfiguration
{
    /**
     * @var array|Configuration[]
     */
    protected $_configs = [];

    /**
     * @var Configuration
     */
    private static $_instance = null;

    public const SETTINGS = 'settings';
    public const DEFAULT_CONFIG_ID = 'librebooking';
    public const CONFIG_FILE_PATH = ROOT_DIR . 'config/config.php';
    public const ENV_FILE_PATH = ROOT_DIR . '.env';
    public const VERSION = '5.3.0';

    protected function __construct()
    {
    }

    /**
     * @return Configuration&IConfiguration
     */
    public static function Instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new Configuration();
            self::$_instance->Register(
                self::CONFIG_FILE_PATH,
                self::ENV_FILE_PATH,
                self::DEFAULT_CONFIG_ID
            );
        }

        return self::$_instance;
    }

    public static function SetInstance($value)
    {
        self::$_instance = $value;
    }

    /**
     * Registers a configuration file and loads the environment variables if the .env file exists.
     * @param string $configFile Path to the configuration file.
     * @param string $envFile Path to the .env file.
     * @param string $configId Identifier for the configuration.
     * @param bool $overwrite Whether to overwrite existing configurations.
     * @throws Exception If the config file is missing or invalid.
     */
    public function Register($configFile, $envFile, $configId, $overwrite = false, $configKeysClass = null)
    {
        $configFile = realpath($configFile);
        if (!file_exists($configFile)) {
            echo "Missing config file: $configFile. If there is a config.dist.php file in this location, please copy it as $configFile";
            throw new Exception("Missing config file: $configFile");
        }

        //Load .env if exists
        if (file_exists($envFile)) {
            $envPath = realpath(dirname($envFile));
            $envFilename = basename($envFile);
            $dotenv = Dotenv::createMutable($envPath, $envFilename);
            $dotenv->load();
        }

        // Load Legacy-style config, mutated $conf during require
        $conf = [];
        $loadedConfig = @require $configFile;

        if (is_array($loadedConfig) && isset($loadedConfig['settings'])) {
            // directly load new config format
            $conf = $loadedConfig;
        } elseif (isset($conf['settings'])) {
            error_log("[CONFIG] Legacy config format detected in $configFile. Please consider updating to the new format.");
        } else {
            throw new Exception("[CONFIG] Invalid config file: 'settings' section missing");
        }

        $this->AddConfig($configId, $conf, $overwrite, $configKeysClass);
        $this->CheckEnableDebug();
    }

    /**
     * Returns the configuration file for the given configId.
     * @param string $configId
     * @return ConfigurationFile
     */
    public function File($configId)
    {
        return $this->_configs[$configId];
    }

    protected function AddConfig($configId, $config, $overwrite, $configKeysClass = null)
    {
        if (!$overwrite) {
            if (array_key_exists($configId, $this->_configs)) {
                throw new Exception('Configuration already exists');
            }
        }

        $this->_configs[$configId] = new ConfigurationFile($config, $configKeysClass);
    }

    protected function CheckEnableDebug()
    {
        mysqli_report(MYSQLI_REPORT_OFF);
        error_reporting(E_ALL & ~E_NOTICE);

        $debug = $this->GetKey(ConfigKeys::APP_DEBUG, new BooleanConverter());
        if ($debug) {
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', '0');
            ini_set('display_startup_errors', '0');
        }
    }

    /**
     * Returns the value of a configuration key.
     * @param array|string $configDef The key name or configuration definition.
     * @param null|IConvert $converter Optional converter for the value.
     * @return mixed|string The configuration value.
     * @throws InvalidArgumentException If the config definition is not found.
     */
    public function GetKey($configDef, $converter = null)
    {
        return $this->File(self::DEFAULT_CONFIG_ID)->GetKey($configDef, $converter);
    }

    /**
     * Returns the full URL to the root of this LibreBooking instance without the trailing slash.
     * @return string
     */
    public function GetScriptUrl()
    {
        return $this->File(self::DEFAULT_CONFIG_ID)->GetScriptUrl();
    }

    /**
     * Returns the default timezone configured in the system or the server's default timezone if not set.
     * @return string The default timezone identifier.
     */
    public function GetDefaultTimezone()
    {
        $tz = $this->GetKey(ConfigKeys::DEFAULT_TIMEZONE);
        if (empty($tz)) {
            $tz = date_default_timezone_get();
        }

        return $tz;
    }

    /**
     * Checks if the given email address is an admin email.
     * @param string|null $emailAddress The email address to check.
     * @return bool True if the email address is an admin email, false otherwise.
     */
    public function IsAdminEmail(?string $emailAddress): bool
    {
        return $this->File(self::DEFAULT_CONFIG_ID)->IsAdminEmail($emailAddress);
    }

    /**
     * Returns all admin emails configured in the system.
     * @return string[] An array of admin email addresses.
     */
    public function GetAllAdminEmails()
    {
        return $this->File(self::DEFAULT_CONFIG_ID)->GetAllAdminEmails();
    }

    /**
     * Returns the first admin email address configured in the system.
     * @return string The first admin email address or null if not set.
     */
    public function GetAdminEmail()
    {
        return $this->File(self::DEFAULT_CONFIG_ID)->GetAdminEmail();
    }

    /**
     * Enables ICS subscription with a random key if no key is set.
     * @param string|null $configFilePath Path to rewrite; defaults to the live config file.
     * @return void
     */
    public function EnableSubscription(?string $configFilePath = null)
    {
        $this->File(self::DEFAULT_CONFIG_ID)->EnableSubscription($configFilePath);
    }

    /**
     * Checks if the .env file is present or if environment variables starting with 'LB_' are loaded.
     * @return bool True if the .env file exists or LB_ environment variables are loaded, false otherwise.
     */
    public static function EnvFilePresent(): bool
    {
        $envFileExists = file_exists(self::ENV_FILE_PATH);

        $envVarsLoaded = false;
        $loadedEnvVars = getenv();
        foreach ($loadedEnvVars as $key => $value) {
            if (str_starts_with($key, 'LB_')) {
                $envVarsLoaded = true;
                break;
            }
        }

        return $envFileExists || $envVarsLoaded;
    }
}

/**
 * Class ConfigurationFile
 *
 * This class implements the IConfigurationFile interface and provides methods to manage configuration settings.
 * It rewrites legacy keys, validates configuration data, and provides access to configuration values.
 */
class ConfigurationFile implements IConfigurationFile
{
    private $_values = [];

    /**
     * @var class-string
     */
    // Stores the schema class name used for config key lookups, such as
    // `ConfigKeys::class` for the main config or a `PluginConfigKeys` subclass
    // for plugin-specific config files.
    private string $_configKeysClass;

    /**
     * @param array{settings: array<string, mixed>, ...} $values Full config payload containing the
     *        `settings` array that will be rewritten, validated, and stored.
     * @param class-string|null $configKeysClass Schema class name to use for key lookups.
     *        Must be `ConfigKeys::class` or a `PluginConfigKeys` subclass. Defaults to
     *        `ConfigKeys::class` for the main application config.
     */
    public function __construct(array $values, ?string $configKeysClass = null)
    {
        $configKeysClass = $configKeysClass ?? ConfigKeys::class;

        if ($configKeysClass !== ConfigKeys::class && !is_subclass_of($configKeysClass, PluginConfigKeys::class)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid config keys class: %s',
                $configKeysClass
            ));
        }

        $this->_configKeysClass = $configKeysClass;
        $rewriteLegacy = $this->RewriteLegacyKeys($values[Configuration::SETTINGS]);
        $validated = $this->ValidateConfig($rewriteLegacy);
        $this->_values = $validated;
    }

    /**
     * Returns the normalized configuration values after legacy-key rewriting and validation.
     *
     * @return array<string, mixed>
     */
    public function GetValues(): array
    {
        return $this->_values;
    }

    /**
     * Rewrites legacy configuration keys to their new equivalents based on the active
     * config schema class. This function preserves the original structure of the config
     * array, rewriting only keys that are marked as deprecated while leaving known new
     * keys untouched.
     *
     * - If a key is a valid key in the configured schema, it is passed through as-is.
     * - If a key is a known legacy key in the configured schema, it is replaced with its
     *   new equivalent.
     * - If a sectioned array (e.g. 'upload' => [...]) is encountered, each subkey is resolved as 'section.key'.
     *
     * @param array $config The raw configuration array from config.php (usually $conf['settings']).
     * @return array The rewritten configuration array with legacy keys replaced by new keys.
     */
    private function RewriteLegacyKeys(array $config): array
    {
        $rewritten = [];

        foreach ($config as $key => $value) {
            if (is_array($value)) {
                // Section group, check subkeys as "$key.$subKey"
                foreach ($value as $subKey => $subValue) {
                    $fullKey = "$key.$subKey";
                    $match = $this->FindConfigEntry($fullKey);
                    $entry = $match['entry'];
                    $isLegacyKey = $match['isLegacy'];

                    if ($entry !== null) {
                        $section = $entry->section;
                        $finalKey = $entry->key;

                        if ($section !== null && $section !== '') {
                            // Always rewrite sectioned keys into the canonical section bucket,
                            // e.g. legacy 'delete.old.data.years.old.data' -> canonical 'cleanup'.
                            $subKeyNew = str_starts_with($finalKey, $section . '.')
                                ? substr($finalKey, strlen($section) + 1)
                                : $finalKey;

                            $rewritten[$section][$subKeyNew] = $subValue;
                        } else {
                            $rewritten[$finalKey] = $subValue;
                        }

                        if ($isLegacyKey) {
                            error_log("[CONFIG] Deprecated config key '$fullKey' used. It maps to '$finalKey'. Support for legacy keys will be removed in a future release.");
                        }
                    } else {
                        $removedReason = DeprecatedConfigKeys::findReason($fullKey);
                        if ($removedReason !== null) {
                            error_log("[CONFIG] Config key '$fullKey' has been deprecated and removed. Please remove it from your config file. Reason: $removedReason");
                        } else {
                            // Unknown subkey - preserve in original structure for validation
                            $rewritten[$key][$subKey] = $subValue;
                        }
                    }
                }

                continue;
            }

            $match = $this->FindConfigEntry($key);
            $entry = $match['entry'];
            $isLegacyKey = $match['isLegacy'];

            if ($entry !== null) {
                $finalKey = $entry->key;
                $section = $entry->section;

                if ($section !== null && $section !== '' && str_starts_with($finalKey, $section . '.')) {
                    $keyWithinSection = substr($finalKey, strlen($section) + 1);
                    $rewritten[$section][$keyWithinSection] = $value;
                } else {
                    $rewritten[$finalKey] = $value;
                }

                if ($isLegacyKey) {
                    error_log("[CONFIG] Deprecated config key '$key' used. It maps to '$finalKey'. Support for legacy keys will be removed in a future release.");
                }

                continue;
            } else {
                $removedReason = DeprecatedConfigKeys::findReason($key);
                if ($removedReason !== null) {
                    error_log("[CONFIG] Config key '$key' has been deprecated and removed. Please remove it from your config file. Reason: $removedReason");
                } else {
                    // Unknown key — pass through (validate will log a warning but keep it)
                    $rewritten[$key] = $value;
                }
            }
        }

        return $rewritten;
    }

    /**
     * @return array{entry: ?ConfigKey, isLegacy: bool}
     */
    private function FindConfigEntry(string $key): array
    {
        // `_configKeysClass` stores a class name string such as `ConfigKeys::class`.
        // Using `$configKeysClass::...` lets the same parser work with either the
        // main config schema or a plugin-specific schema class selected at runtime.
        $configKeysClass = $this->_configKeysClass;

        $entry = $configKeysClass::findByKey($key);
        if ($entry !== null) {
            return ['entry' => $entry, 'isLegacy' => false];
        }

        $entry = $configKeysClass::findByLegacyKey($key);

        return ['entry' => $entry, 'isLegacy' => $entry !== null];
    }

    private function ValidateConfig(array $data, string $path = ''): array
    {
        $validated = [];
        $configKeysClass = $this->_configKeysClass;

        foreach ($data as $key => $value) {
            $fullKey = $path === '' ? $key : "$path.$key";

            if (is_array($value)) {
                $validated[$key] = $this->ValidateConfig($value, $fullKey);
                continue;
            }

            $configDef = $configKeysClass::findByKey($fullKey);

            if (!$configDef) {
                $removedReason = DeprecatedConfigKeys::findReason($fullKey);
                if ($removedReason !== null) {
                    // Intentionally not added to $validated — no code reads this key anymore
                    error_log("[CONFIG] Config key '$fullKey' has been deprecated and removed. Please remove it from your config file. Reason: $removedReason");
                } else {
                    error_log("[CONFIG] Unknown config key: '$fullKey'. Skipping.");
                    $validated[$key] = $value; // Keep unknown keys as-is
                }
                continue;
            }

            $converter = $this->GetDefaultConverter($configDef);
            if (!$converter->IsValid($value)) {
                error_log("[CONFIG] Invalid type for '{$fullKey}'. Should be '{$configDef->type}', using default.");
                $validated[$key] = $configDef->default;
                continue;
            }

            if ($configDef->choices !== null
                && !$configDef->allowCustom
                && !array_key_exists($value, $configDef->choices)) {
                error_log("[CONFIG] Invalid value '$value' for '{$fullKey}'. Should be one of the following options: [" . implode(', ', array_map(fn ($key, $value) => "{$key} => {$value}", array_keys($configDef->choices), $configDef->choices)) . ']');
                $validated[$key] = $configDef->default;
                continue;
            }

            $validated[$key] = $converter->Convert($value);
        }

        return $validated;
    }
    /**
     * Returns the value of a configuration key based on the provided configuration definition.
     * If the section is specified, it retrieves the value from that section.
     * If no section is specified, it retrieves the value from the global configuration.
     *
     * @param array $configDef The configuration definition containing 'key' and optional 'section'.
     * @param null|IConvert $converter Optional converter for the value.
     * @return mixed|string The converted configuration value.
     * @throws InvalidArgumentException If the config definition is not found.
     */
    public function GetKey($configDef, $converter = null)
    {
        $configKey = $this->NormalizeConfigDef($configDef);

        $value = null;
        $fullKey = $configKey->key;
        $section = $configKey->section;
        $converter = $converter ?? $this->GetDefaultConverter($configKey);

        $envKey = ConfigKeysMeta::envKeyForConfig(config: $configKey);
        $envValue = $envKey !== null ? env($envKey) : null;

        if (!empty($envValue)) {
            $value = $envValue;
        } elseif ($section !== null && $section !== '') {
            $sectionKey = str_starts_with($fullKey, $section . '.') ? substr($fullKey, strlen($section) + 1) : $fullKey;
            if (isset($this->_values[$section][$sectionKey])) {
                $value = $this->_values[$section][$sectionKey];
            }
        } else {
            if (array_key_exists($fullKey, $this->_values)) {
                $value = $this->_values[$fullKey];
            }
        }

        if ($value === null || $value === '') {
            $value = $configKey->default;
        }

        return $this->Convert($value, $converter);
    }

    /**
     * Normalizes a config definition into a typed ConfigKey.
     *
     * Accepts either a ConfigKey or a legacy associative-array definition such as
     * ConfigKeys::APP_TITLE. Left untyped so anything else falls through to the
     * descriptive InvalidArgumentException rather than a raw TypeError.
     *
     * @param ConfigKey|array<string, mixed>|mixed $configDef
     */
    protected function NormalizeConfigDef($configDef): ConfigKey
    {
        if ($configDef instanceof ConfigKey) {
            return $configDef;
        }

        if (is_array($configDef) && array_key_exists('key', $configDef)) {
            return ConfigKey::fromArray($configDef);
        }

        throw new InvalidArgumentException(sprintf(
            'Config definition not found for: %s',
            json_encode($configDef, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        ));
    }

    private function GetDefaultConverter(ConfigKey $config): ?IConvert
    {
        return match ($config->type) {
            ConfigSettingType::Integer => new IntConverter(),
            ConfigSettingType::Boolean => new BooleanConverter(),
            ConfigSettingType::String => new StringConverter(),
            default => null
        };
    }

    protected function Convert($value, $converter)
    {
        if (!is_null($converter)) {
            return $converter->Convert($value);
        }

        return $value != null ? trim($value) : $value;
    }

    /**
     * Returns the full URL to the root of this LibreBooking instance without the trailing slash.
     * If the URL starts with '//' it will be prefixed with 'http:' or 'https:' based on the server's protocol.
     *
     * @return string The script URL without trailing slash.
     */
    public function GetScriptUrl()
    {
        $url = $this->GetKey(ConfigKeys::SCRIPT_URL);

        if (BookedStringHelper::StartsWith($url, '//')) {
            $isHttps = ServiceLocator::GetServer()->GetIsHttps();

            if ($isHttps) {
                $url = "https:$url";
            } else {
                $url = "http:$url";
            }
        }

        return rtrim($url, '/');
    }

    /**
     * Returns the default timezone configured in the system or the server's default timezone if not set.
     * If no timezone is set, it uses the server's default timezone.
     *
     * @return string The default timezone identifier.
     */
    public function GetDefaultTimezone()
    {
        $tz = $this->GetKey(ConfigKeys::DEFAULT_TIMEZONE);
        if (empty($tz)) {
            $tz = date_default_timezone_get();
        }

        return $tz;
    }

    /**
     * Returns all admin emails configured in the system.
     * The emails are split by whitespace, comma, or semicolon and trimmed of whitespace.
     *
     * @return string[] An array of admin email addresses.
     */
    public function GetAllAdminEmails()
    {
        $adminEmail = Configuration::Instance()->GetKey(ConfigKeys::ADMIN_EMAIL);

        return array_map('trim', preg_split('/[\s,;]+/', $adminEmail));
    }

    /**
     * Checks if the given email address is an admin email.
     * @param string|null $emailAddress The email address to check.
     * @return bool True if the email address is an admin email, false otherwise.
     */
    public function IsAdminEmail(?string $emailAddress): bool
    {
        if ($emailAddress === null || $emailAddress === '') {
            return false;
        }

        $adminEmails = $this->GetAllAdminEmails();

        foreach ($adminEmails as $email) {
            if (strtolower((string) $emailAddress) == strtolower((string) $email)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the first admin email address configured in the system.
     * @return string The first admin email address or null if not set.
     */
    public function GetAdminEmail()
    {
        $adminEmails = $this->GetAllAdminEmails();

        return $adminEmails[0] ?? null;
    }

    /**
     * Enables ICS subscription with a random key if no key is set.
     * This method updates the configuration file to include a new ICS subscription key.
     * If the key already exists, it does nothing.
     *
     * @param string|null $configFilePath Path to rewrite; defaults to the live config file.
     * @return void
     */
    public function EnableSubscription(?string $configFilePath = null)
    {
        $newKey = self::GenerateSubscriptionKeyIfNeeded(
            $this->GetKey(ConfigKeys::ICS_ENABLED, new BooleanConverter()),
            $this->GetKey(ConfigKeys::ICS_SUBSCRIPTION_KEY)
        );

        if ($newKey === null) {
            return;
        }

        $configFilePath ??= ROOT_DIR . 'config/config.php';

        if (!is_readable($configFilePath)) {
            return;
        }

        $contents = file_get_contents($configFilePath);

        if ($contents === false) {
            return;
        }

        // Matches the current `'subscription.key' => '',` nested-array entry.
        $updated = preg_replace(
            '/([\'"]subscription\.key[\'"]\s*=>\s*)([\'"])\2/',
            '${1}${2}' . $newKey . '${2}',
            $contents,
            1,
            $count
        );

        if ($updated === null || $count === 0) {
            return;
        }

        // Checking is_writable() first keeps the common read-only-config case from
        // emitting a warning, but the return value still has to be checked because
        // the write can fail for reasons the check cannot see.
        if (!is_writable($configFilePath) || file_put_contents($configFilePath, $updated) === false) {
            Log::Error('Could not write the generated ICS subscription key to %s', $configFilePath);
            return;
        }

        Configuration::SetInstance(null);
    }

    /**
     * Shared gate for ICS subscription key generation: returns a freshly generated
     * key when ICS is enabled and no key is set yet, or null when nothing should change.
     * Used both here (patching the live config file) and by ManageConfigurationPresenter
     * (patching the in-memory settings array before it writes the config file).
     *
     * @param bool $icsEnabled
     * @param string|null $existingKey
     * @return string|null
     */
    public static function GenerateSubscriptionKeyIfNeeded(bool $icsEnabled, ?string $existingKey): ?string
    {
        if (!$icsEnabled || !empty($existingKey)) {
            return null;
        }

        return BookedStringHelper::Random(32);
    }
}
