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
     * @param $emailAddress
     * @return bool
     */
    public function IsAdminEmail($emailAddress);

    /**
     * @return string[]
     */
    public function GetAllAdminEmails();

    /**
     * @return string
     */
    public function GetAdminEmail();

    /**
     * Enables ics subscriptionwith a random key if no key is set.
     * @return void
     */
    public function EnableSubscription();
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
    public const VERSION = '4.0.0';

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
     * @param string $emailAddress The email address to check.
     * @return bool True if the email address is an admin email, false otherwise.
     */
    public function IsAdminEmail($emailAddress)
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
     * @return void
     */
    public function EnableSubscription()
    {
        $this->File(self::DEFAULT_CONFIG_ID)->EnableSubscription();
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
    private $_configKeysClass = null;

    public function __construct($values, $configKeysClass = null)
    {
        $this->_configKeysClass = $configKeysClass ?? ConfigKeys::class;
        $rewriteLegacy = $this->RewriteLegacyKeys($values[Configuration::SETTINGS]);
        $validated = $this->ValidateConfig($rewriteLegacy);
        $this->_values = $validated;
    }

    /**
     * Rewrites legacy configuration keys to their new equivalents based on the definitions
     * in ConfigKeys. This function preserves the original structure of the config array,
     * rewriting only keys that are marked as deprecated while leaving known new keys untouched.
     *
     * - If a key is a valid key (via ConfigKeys::findByKey), it is passed through as-is.
     * - If a key is a known legacy key (via ConfigKeys::findByLegacyKey), it is replaced with its new equivalent.
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
                    $entry = call_user_func([$this->_configKeysClass, 'findByKey'], $fullKey) ??
                        call_user_func([$this->_configKeysClass, 'findByLegacyKey'], $fullKey);

                    if ($entry && !empty($entry['key'])) {
                        $section = $entry['section'] ?? null;
                        $finalKey = $entry['key'];

                        if ($section === $key) {
                            // Keep under parent key, rewrite subkey only
                            $subKeyNew = str_starts_with($finalKey, $section . '.')
                                ? substr($finalKey, strlen($section) + 1)
                                : $finalKey;

                            $rewritten[$key][$subKeyNew] = $subValue;
                        } else {
                            $rewritten[$finalKey] = $subValue;
                        }

                        if (!call_user_func([$this->_configKeysClass, 'findByKey'], $fullKey)) {
                            error_log("[CONFIG] Deprecated config key '$fullKey' used. It maps to '$finalKey'. Support for legacy keys will be removed in a future release.");
                        }
                    } else {
                        // Unknown subkey - preserve in original structure for validation
                        $rewritten[$key][$subKey] = $subValue;
                    }
                }

                continue;
            }

            $entry = call_user_func([$this->_configKeysClass, 'findByKey'], $key) ??
                call_user_func([$this->_configKeysClass, 'findByLegacyKey'], $key);

            if ($entry && !empty($entry['key'])) {
                $finalKey = $entry['key'];
                $section = $entry['section'] ?? null;

                if ($section && str_starts_with($finalKey, $section . '.')) {
                    $keyWithinSection = substr($finalKey, strlen($section) + 1);
                    $rewritten[$section][$keyWithinSection] = $value;
                } else {
                    $rewritten[$finalKey] = $value;
                }

                if (!call_user_func([$this->_configKeysClass, 'findByKey'], $key)) {
                    error_log("[CONFIG] Deprecated config key '$key' used. It maps to '$finalKey'. Support for legacy keys will be removed in a future release.");
                }

                continue;
            } else {
                // Unknown key — pass through (validate will drop)
                $rewritten[$key] = $value;
            }
        }

        return $rewritten;
    }

    private function ValidateConfig(array $data, string $path = ''): array
    {
        $validated = [];

        foreach ($data as $key => $value) {
            $fullKey = $path === '' ? $key : "$path.$key";

            if (is_array($value)) {
                $validated[$key] = $this->ValidateConfig($value, $fullKey);
                continue;
            }

            $configDef = call_user_func([$this->_configKeysClass, 'findByKey'], $fullKey);

            if (!$configDef) {
                error_log("[CONFIG] Unknown config key: '$fullKey'. Skipping.");
                $validated[$key] = $value; // Keep unknown keys as-is
                continue;
            }

            $converter = $this->GetDefaultConverter($configDef);
            if (!$converter->IsValid($value)) {
                error_log("[CONFIG] Invalid type for '{$fullKey}'. Should be '{$configDef['type']}', using default.");
                $validated[$key] = $configDef['default'];
                continue;
            }

            if (isset($configDef['choices']) && !array_key_exists($value, $configDef['choices'])) {
                error_log("[CONFIG] Invalid value '$value' for '{$fullKey}'. Should be one of the following options: [" . implode(', ', array_map(fn($key, $value) => "{$key} => {$value}", array_keys($configDef['choices']), $configDef['choices'])) . "]");
                $validated[$key] = $configDef['default'];
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
        if (!is_array($configDef) || !isset($configDef['key'])) {
            throw new InvalidArgumentException(sprintf(
                'Config definition not found for: %s',
                json_encode($configDef, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            ));
        }

        $value = null;
        $fullKey = $configDef['key'];
        $section = $configDef['section'] ?? null;
        $converter = $converter ?? $this->GetDefaultConverter($configDef);

        $envKey = strtoupper('LB_' . preg_replace('/[.\-]+/', '_', $configDef['key']));
        $envValue = env($envKey);

        if (!empty($envValue)) {
            $value = $envValue;
        } elseif ($section !== null) {
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
            if (array_key_exists('default', $configDef)) {
                $value = $configDef['default'];
            } else {
                $value = null;
            }
        }

        return $this->Convert($value, $converter);
    }

    private function GetDefaultConverter(array $config): ?IConvert
    {
        return match ($config['type'] ?? ConfigSettingType::String) {
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
     * @param string $emailAddress The email address to check.
     * @return bool True if the email address is an admin email, false otherwise.
     */
    public function IsAdminEmail($emailAddress)
    {
        $adminEmails = $this->GetAllAdminEmails();

        foreach ($adminEmails as $email) {
            if (strtolower($emailAddress) == strtolower($email)) {
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
     * @return void
     */
    public function EnableSubscription()
    {
        $icsKey = $this->GetKey(ConfigKeys::ICS_SUBSCRIPTION_KEY);
        if (!empty($icsKey)) {
            return;
        }

        $configFile = ROOT_DIR . 'config/config.php';

        if (file_exists($configFile)) {
            $newKey = '$conf[\'settings\'][\'ics\'][\'subscription.key\'] = \'' . BookedStringHelper::Random(20) . '\';';
            $str = file_get_contents($configFile);
            $str = str_replace('$conf[\'settings\'][\'ics\'][\'subscription.key\'] = \'\';', $newKey, $str);
            file_put_contents($configFile, $str);
            Configuration::SetInstance(null);
        }
    }
}
