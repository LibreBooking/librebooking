<?php

declare(strict_types=1);

class FakeConfig extends Configuration implements IConfiguration
{
    public $_RegisteredFiles = [];
    public $_ScriptUrl = '';

    public function Register($configFile, $envFile, $configId, $overwrite = false, $configKeysClass = null)
    {
        $this->_RegisteredFiles[$configId] = $configFile;
    }

    public function __construct()
    {
        $this->SetFile(self::DEFAULT_CONFIG_ID, new FakeConfigFile());
    }

    public function SetFile($configId, $file)
    {
        $this->_configs[$configId] = $file;
    }

    public function SetKey($configDef, $value)
    {
        /** @var FakeConfigFile $file */
        $file = $this->_configs[self::DEFAULT_CONFIG_ID];
        $file->SetKey($configDef, $value);
    }

    public function SetTimezone($timezone)
    {
        $this->SetKey(ConfigKeys::DEFAULT_TIMEZONE, $timezone);
    }

    public function GetScriptUrl()
    {
        return $this->_ScriptUrl;
    }

    public function EnableSubscription(?string $configFilePath = null)
    {
    }

}

class FakeConfigFile extends ConfigurationFile implements IConfigurationFile
{
    public $_values = [];
    public $_sections = [];
    public $_ScriptUrl = '';

    public function __construct()
    {
        parent::__construct([Configuration::SETTINGS => []]);
    }

    public function GetKey($configDef, $converter = null)
    {
        $configKey = $this->NormalizeConfigDef($configDef);

        $value = null;
        $fullKey = $configKey->key;
        $section = $configKey->section;
        $converter = $converter ?? $this->GetDefaultConverter($configKey);

        if ($section !== null && $section !== '') {
            $sectionKey = str_starts_with($fullKey, $section . '.') ? substr($fullKey, strlen($section) + 1) : $fullKey;
            if (isset($this->_values[$section][$sectionKey])) {
                $value = $this->_values[$section][$sectionKey];
            }
        } else {
            if (array_key_exists($fullKey, $this->_values)) {
                $value = $this->_values[$fullKey];
            }
        }

        return $this->Convert($value, $converter);
    }

    public function GetSectionKey($section, $keyName, $converter = null)
    {
        return $this->GetKey($keyName, $converter);
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

    public function GetScriptUrl()
    {
        return $this->_ScriptUrl;
    }

    /**
     * @return string
     */
    public function GetDefaultTimezone()
    {
        return 'UTC';
    }

    public function SetKey($configDef, $value)
    {
        if ($configDef instanceof ConfigKey) {
            $fullKey = $configDef->key;
            $section = $configDef->section;
        } elseif (is_array($configDef) && array_key_exists('key', $configDef)) {
            $fullKey = $configDef['key'];
            $section = $configDef['section'] ?? null;
        } else {
            $this->_values[$configDef] = $value;
            return;
        }

        if ($section !== null && $section !== '') {
            $sectionKey = str_starts_with($fullKey, $section . '.') ?
                substr($fullKey, strlen($section) + 1) :
                $fullKey;

            if (!isset($this->_values[$section])) {
                $this->_values[$section] = [];
            }
            $this->_values[$section][$sectionKey] = $value;
        } else {
            $this->_values[$fullKey] = $value;
        }
    }

    public function GetValues(): array
    {
        return $this->_values;
    }

    public function EnableSubscription(?string $configFilePath = null)
    {
    }
}
