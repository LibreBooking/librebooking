<?php

/**
 * Interface for configuration settings operations such as merging, writing, and reading config files.
 */
interface IConfigurationSettings
{
    /**
     *  Retrieves settings from a config file.
     * @param string $file The path to the config file.
     * @return array The configuration settings.
     */
    public function GetSettings($file);

    /**
     * Builds a merged config array from current and new settings.
     *
     * @param array $currentSettings
     * @param array $newSettings
     * @param bool $keepMissingKeys  If true, retains keys that exist only in current config.
     * @return array
     */
    public function BuildConfig($currentSettings, $newSettings, $keepMissingKeys = false);

    /**
     * Writes the given configuration array to a PHP config file.
     *
     * @param string $configFilePath The path to the config file.
     * @param array $mergedSettings
     */
    public function WriteSettings($configFilePath, $mergedSettings);

    /**
     * Checks if a file is writable or attempts to make it writable.
     *
     * @param string $configFilePath
     * @return bool
     */
    public function CanOverwriteFile($configFilePath);
}

class Configurator implements IConfigurationSettings
{
    /**
     * @param string $configPhp  Path to the target config.php.
     * @param string $distPhp Path to the default template config.php.
     */
    public function Merge($configPhp, $distPhp)
    {
        if ($this->IsConfigOutOfDate($configPhp, $distPhp)) {
            $mergedSettings = $this->GetMerged($configPhp, $distPhp);
            $this->WriteSettings($configPhp, $mergedSettings);
        }
    }

    /**
     * Writes the final merged settings into the config file.
     *
     * @param string $configFilePath Target file path.
     * @param array $mergedSettings Merged settings array.
     */
    public function WriteSettings($configFilePath, $mergedSettings)
    {
        $this->CreateBackup($configFilePath);

        if (!array_key_exists(Configuration::SETTINGS, $mergedSettings)) {
            $mergedSettings = [Configuration::SETTINGS => $mergedSettings];
        }
        $comment = "// LibreBooking configuration file edited at " . date('c');
        $body = $this->ExportArray($mergedSettings);
        $php = "<?php\n\n$comment\n\nreturn $body;\n";

        file_put_contents($configFilePath, $php);
    }

    /**
     * Exports an array to a string representation with proper indentation.
     *
     * @param array $array The array to export.
     * @param int $indentLevel  The current indentation level.
     * @return string The exported array as a string.
     */
    private function ExportArray(array $array, int $indentLevel = 0): string
    {
        $indent = str_repeat('    ', $indentLevel);
        $nextIndent = str_repeat('    ', $indentLevel + 1);
        $lines = ["["];

        foreach ($array as $key => $value) {
            $exportedKey = var_export($key, true);

            if (is_array($value)) {
                $exportedValue = $this->ExportArray($value, $indentLevel + 1);
            } else {
                $exportedValue = $this->ExportValue( $value);
            }

            $lines[] = "$nextIndent$exportedKey => $exportedValue,";
        }

        $lines[] = "$indent]";
        return implode("\n", $lines);
    }

    /**
     * Formats a config value using the given key.
     * Supports strings, booleans, numbers, and null.
     *
     * @param mixed $value  The default value
     * @return string       The key value pair as a string
     */
    private function ExportValue( $value): string
    {
        if (is_bool($value)) {
            $default = $value ? 'true' : 'false';
        }
        // Handle string booleans
        elseif (is_string($value) && ($value === 'true' || $value === 'false')) {
            $default = $value;
        }
        // Handle integers
        elseif (is_int($value)) {
            $default = (string) $value;
        }
        // Handle string integers
        elseif (is_string($value) && ctype_digit($value)) {
            if (strlen($value) > 1 && $value[0] === '0') {
                $default = var_export($value, true);
            } else {
                $default = (int) $value;
            }
        }
        // All other cases
        else {
            $default = var_export($value, true);
        }

        return  $default;
    }

    /**
     * Merges two configuration arrays.
     *
     * @param string $configPhp Path to the target config.php.
     * @param string $distPhp Path to the default template config.php.
     * @return array
     */
    private function GetMerged($configPhp, $distPhp)
    {
        $currentSettings = $this->GetSettings($configPhp);
        $newSettings = $this->GetSettings($distPhp);
        $settings = $this->BuildConfig($currentSettings, $newSettings, true);
        return [Configuration::SETTINGS => $settings];
    }

    /**
     * Returns the merged configuration as a PHP string.
     *
     * @param string $configPhp  Path to the target config.php.
     * @param string $distPhp ath to the default template config.php.
     * @return string PHP code representing merged config.
     */
    public function GetMergedString($configPhp, $distPhp)
    {
        $config = $this->GetMerged($configPhp, $distPhp);
        return var_export($config, true);
    }

    /**
     * Loads the settings array from a config file.
     *
     * @param string $file File path.
     * @return array Settings array.
     */
    public function GetSettings($file)
    {
        $conf = [];
        $loaded = @require $file;

        if (is_array($loaded) && isset($loaded['settings'])) {
            // New-style config using return
            return $loaded['settings'];
        } elseif (isset($conf['settings'])) {
            // Legacy-style config that populated $conf
            return $conf['settings'];
        } else {
            throw new Exception("Invalid config file: 'settings' section missing");
        }
    }

    /**
     * Merges new settings with current settings, intelligently handling special cases.
     * Preserves order, environment variables, and private field values.
     *
     * @param array $currentSettings Existing settings from config file.
     * @param array $newSettings New settings from form submission.
     * @param bool $keepMissingKeys Whether to preserve keys missing in new settings.
     * @return array Final merged config with preserved order and special handling.
     */
    public function BuildConfig($currentSettings, $newSettings, $keepMissingKeys = false)
    {
        return $this->MergeArrays($currentSettings, $newSettings, $keepMissingKeys);
    }

    /**
     * Recursively merges two arrays, preserving order and applying special logic.
     */
    private function MergeArrays($current, $new, $keepMissing = false, $keyPrefix = '')
    {
        $result = [];

        // Process all current keys first to preserve order
        foreach ($current as $key => $currentValue) {
            $fullKey = $keyPrefix ? "$keyPrefix.$key" : $key;

            if (array_key_exists($key, $new)) {
                $newValue = $new[$key];

                if (is_array($currentValue) && is_array($newValue)) {
                    // Recursively merge nested arrays
                    $result[$key] = $this->MergeArrays($currentValue, $newValue, true, $fullKey);
                } else {
                    // Decide whether to use current or new value
                    $result[$key] = $this->MergeValue($fullKey, $currentValue, $newValue);
                }
            } elseif ($keepMissing) {
                // Keep existing keys that aren't in new settings
                $result[$key] = $currentValue;
            }
        }

        // Add any completely new keys at the end
        foreach ($new as $key => $newValue) {
            if (!array_key_exists($key, $current)) {
                $result[$key] = $newValue;
            }
        }

        return $result;
    }

    /**
     * Chooses between current and new value based on environment variables and private field rules.
     */
    private function MergeValue($key, $currentValue, $newValue)
    {
        $meta = $this->GetKeyMetadata($key);

        if (!$meta) {
            return $newValue;
        }

        // Check environment variable override
        if ($this->HasEnvironmentVariable($meta)) {
            Log::Debug('Preserving config value for env-controlled field: %s', $key);
            return $currentValue;
        }

        // Check private field with empty new value (disabled form field)
        if ($this->IsPrivateWithEmptyValue($meta, $newValue)) {
            Log::Debug('Preserving config value for private field with empty new value: %s', $key);
            return $currentValue;
        }

        return $newValue;
    }

    /**
     * Gets metadata for a configuration key.
     */
    private function GetKeyMetadata($key)
    {
        if (class_exists('ConfigKeys') && method_exists('ConfigKeys', 'findByKey')) {
            return call_user_func(['ConfigKeys', 'findByKey'], $key);
        }
        return null;
    }

    /**
     * Checks if a field has an environment variable set.
     */
    private function HasEnvironmentVariable($meta)
    {
        return $meta && method_exists('ConfigKeys', 'hasEnv') &&
               call_user_func(['ConfigKeys', 'hasEnv'], $meta);
    }

    /**
     * Checks if a field is private and has an empty new value.
     */
    private function IsPrivateWithEmptyValue($meta, $newValue)
    {
        if (!$meta || !method_exists('ConfigKeys', 'isPrivate')) {
            return false;
        }

        $isPrivate = call_user_func(['ConfigKeys', 'isPrivate'], $meta);
        return $isPrivate && (empty($newValue) || $newValue === '');
    }

    /**
     * Checks if a config file is writable.
     *
     * @param string $configFile Path to the file.
     * @return bool True if writable or permission changed successfully.
     */
    public function CanOverwriteFile($configFile)
    {
        if (!is_writable($configFile)) {
            return chmod($configFile, 0770);
        }
        return true;
    }

    /**
     * Creates a timestamped backup of the config file.
     *
     * @param string $configFilePath Path to the config file.
     */
    private function CreateBackup($configFilePath)
    {
        $timestamp = date('Y-m-d\TH-i-s'); // ISO-like format: 2025-07-01T15-03-00
        $pathinfo = pathinfo($configFilePath);
        $backupPath = $pathinfo['dirname'] . '/' . $pathinfo['filename'] . "_{$timestamp}.bck.php";
        copy($configFilePath, $backupPath);
    }

    /**
     * Checks whether the current config is outdated.
     *
     * @param string $configPhp Current config file.
     * @param string $distPhp Template config.
     * @return bool True if config differs.
     */
    private function IsConfigOutOfDate($configPhp, $distPhp)
    {
        $currentSettings = $this->GetSettings($configPhp);
        $newSettings = $this->GetSettings($distPhp);

        if ($this->AreKeysTheSame($currentSettings, $newSettings)) {
            Log::Debug('Config file is already up to date. Skipping config merge.');
            return false;
        }

        Log::Debug('Config file is out of date. Merging new config options in.');
        return true;
    }

    /**
     * Recursively compares config keys between two arrays.
     *
     * @param array $current The current config.
     * @param array $new The new (template) config.
     * @return bool True if structure is identical.
     */
    private function AreKeysTheSame($current, $new)
    {
        foreach ($new as $key => $val) {
            if (!array_key_exists($key, $current) || (is_array($new[$key]) && is_array($current[$key]) && !$this->AreKeysTheSame($current[$key], $new[$key]))) {
                Log::Debug('Could not find key in config file: %s', $key);
                return false;
            }
        }

        return true;
    }
}
