<?php

require_once(ROOT_DIR . 'lib/external/pear/Config.php');

interface IConfigurationSettings
{
    /**
     * @param string $file
     * @return array
     */
    public function GetSettings($file);

    /**
     * @param array $currentSettings
     * @param array $newSettings
     * @param bool $removeMissingKeys
     * @return array
     */
    public function BuildConfig($currentSettings, $newSettings, $removeMissingKeys = false);

    /**
     * @param string $configFilePath
     * @param array $mergedSettings
     */
    public function WriteSettings($configFilePath, $mergedSettings);

    /**
     * @param string $configFilePath
     * @return bool
     */
    public function CanOverwriteFile($configFilePath);
}

class Configurator implements IConfigurationSettings
{
    /**
     * @param string $configPhp
     * @param string $distPhp
     */
    public function Merge($configPhp, $distPhp)
    {
        if ($this->IsConfigOutOfDate($configPhp, $distPhp)) {
            $mergedSettings = $this->GetMerged($configPhp, $distPhp);

            $this->WriteSettings($configPhp, $mergedSettings);
        }
    }

    public function WriteSettings($configFilePath, $mergedSettings)
    {
        $this->CreateBackup($configFilePath);
        if (!array_key_exists(Configuration::SETTINGS, $mergedSettings)) {
            $mergedSettings = [Configuration::SETTINGS => $mergedSettings];
        }
        $config = new Config();
        $config->parseConfig($mergedSettings, 'PHPArray');
        $config->writeConfig($configFilePath, 'PHPArray', $mergedSettings);

        $this->AddErrorReporting($configFilePath);
    }

    /**
     * @param string $configPhp
     * @param string $distPhp
     * @return string
     */
    private function GetMerged($configPhp, $distPhp)
    {
        $currentSettings = $this->GetSettings($configPhp);
        $newSettings = $this->GetSettings($distPhp);

        $settings = $this->BuildConfig($currentSettings, $newSettings, true);
        return [Configuration::SETTINGS => $settings];
    }

    public function GetMergedString($configPhp, $distPhp)
    {
        $settings = $this->GetMerged($configPhp, $distPhp);
        $config = new Config();
        $parsed = $config->parseConfig($settings, 'PHPArray');

        return $parsed->toString('PHPArray');
    }

    public function GetSettings($file)
    {
        $config = new Config();
        /** @var $current Config_Container */
        $current = $config->parseConfig($file, 'PHPArray');

        $currentValues = $current->getItem("section", Configuration::SETTINGS)->toArray();

        return $currentValues[Configuration::SETTINGS];
    }

    public function BuildConfig($currentSettings, $newSettings, $keepMissingKeys = false)
    {
        foreach ($currentSettings as $key => $value) {
            if (array_key_exists($key, $newSettings)) {
                if (is_array($value)) {
                    $newSettings[$key] = array_merge($newSettings[$key], $value);
                } else {
                    $newSettings[$key] = $value;
                }
            } else {
                Log::Debug("$key not found");
                if ($keepMissingKeys) {
                    $newSettings[$key] = $value;
                }
            }
        }

        return $newSettings;
    }

    private function AddErrorReporting($file)
    {
        $pathinfo = pathinfo($file);
        if ($pathinfo['dirname'] != ROOT_DIR . 'config') {
            return;
        }
        $contents = file_get_contents($file);
        $new = str_replace("<?php", "<?php\r\nmysqli_report(MYSQLI_REPORT_OFF);\r\nerror_reporting(E_ALL & ~E_NOTICE);\r\n", $contents);

        file_put_contents($file, $new);
    }

    public function CanOverwriteFile($configFile)
    {
        if (!is_writable($configFile)) {
            return chmod($configFile, 0770);
        }

        return true;
    }

    private function CreateBackup($configFilePath)
    {
        $backupPath = str_replace('.php', time() . '.php', $configFilePath);
        copy($configFilePath, $backupPath);
    }

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
