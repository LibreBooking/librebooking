<?php

require_once(ROOT_DIR . 'Pages/Admin/ManageConfigurationPage.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'lib/Database/namespace.php');
require_once(ROOT_DIR . 'lib/Database/Commands/namespace.php');

class ConfigActions
{
    public const Update = 'update';
    public const SetHomepage = 'setHomepage';
}

class ManageConfigurationPresenter extends ActionPresenter
{
    /**
     * @var IManageConfigurationPage
     */
    private $page;

    /**
     * @var IConfigurationSettings
     */
    private $configSettings;

    /**
     * @var string
     */
    private $configFilePath;

    /**
     * @var string
     */
    private $configKeyClass;

    /**
     * @var string
     */
    private $configKeyClassPath;
    /**
     * @var string[]|array[]
     */
    private $deletedSettings = [
        'password.pattern',
        'use.local.jquery',
        'authentification.allow.social.login',
        'ics.require.login',
        'ics.import',
        'ics.import.key',
        'reservation.maximum.resources'
    ];

    /**
     * @var string
     */
    private $configFilePathDist;

    public function __construct(IManageConfigurationPage $page, IConfigurationSettings $settings)
    {
        parent::__construct($page);
        $this->page = $page;
        $this->configSettings = $settings;
        $this->configFilePath = ROOT_DIR . 'config/config.php';
        $this->configFilePathDist = ROOT_DIR . 'config/config.dist.php';

        $this->AddAction(ConfigActions::Update, 'Update');
        $this->AddAction(ConfigActions::SetHomepage, 'SetHomepage');
    }

    public function PageLoad()
    {
        $shouldShowConfig = Configuration::Instance()->GetKey(
            ConfigKeys::PAGES_CONFIGURATION_ENABLED,
            new BooleanConverter()
        );
        $this->page->SetIsPageEnabled($shouldShowConfig);

        if (!$shouldShowConfig) {
            Log::Debug('Show configuration UI is turned off. Not displaying the config values');
            return;
        }

        $this->CheckIfScriptUrlMayBeWrong();

        $configFiles = $this->GetConfigFiles();
        $this->page->SetConfigFileOptions($configFiles);

        $this->HandleSelectedConfigFile($configFiles);

        $isFileWritable = $this->configSettings->CanOverwriteFile($this->configFilePath);
        $this->page->SetIsConfigFileWritable($isFileWritable);

        if (!$isFileWritable) {
            Log::Debug('Config file is not writable');
            return;
        }

        if (empty($this->configKeyClassPath) || !file_exists($this->configKeyClassPath)) {
            $this->configKeyClass = ConfigKeys::class;
        } else {
            include_once($this->configKeyClassPath);
        }

        Log::Debug(
            'Loading and displaying config file for editing by %s',
            ServiceLocator::GetServer()->GetUserSession()->Email
        );

        $this->BringConfigFileUpToDate();

        $settings = $this->configSettings->GetSettings($this->configFilePath);
        
        // Check if this is a plugin config file
        $isPluginConfig = $this->IsPluginConfigFile($this->configFilePath);
        $pluginId = $isPluginConfig ? $this->GetPluginIdFromPath($this->configFilePath) : null;

        foreach ($settings as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    $fullKey = "$key.$subKey";
                    $this->AddSettingFromMeta($fullKey, $key, $subValue);
                }
            } else {
                // For plugin configs, add settings under the plugin section
                if ($isPluginConfig && $pluginId) {
                    $fullKey = "$pluginId.$key";
                    $this->AddSettingFromMeta($fullKey, $pluginId, $value);
                } else {
                    $this->AddSettingFromMeta($key, null, $value);
                }
            }
        }
    }

    private function AddSettingFromMeta($key, $section, $value)
    {
        // For plugin configs, the key might not include the section prefix
        // Check both the full key and the section.key format
        $meta = call_user_func([$this->configKeyClass, 'findByKey'], $key);
        
        // If not found and we have a section, try without section prefix
        if (!isset($meta) && $section) {
            $keyWithoutSection = str_replace("$section.", '', $key);
            $meta = call_user_func([$this->configKeyClass, 'findByKey'], $keyWithoutSection);
        }
        
        if (!isset($meta) || $this->ShouldBeSkipped($key, $meta)) {
            return;
        }

        $isPrivate = call_user_func([$this->configKeyClass, 'isPrivate'], $meta) ?? false;
        $hasEnv = call_user_func([$this->configKeyClass, 'hasEnv'], $meta) ?? false;

        // Use the key without section prefix for display
        $displayKey = $section ? str_replace("$section.", '', $key) : $key;
        
        $setting = new ConfigSetting(
            $displayKey,
            $section ?: null,
            $value,
            $meta['type'] ?? 'string',
            $meta['choices'] ?? '',
            $meta['label'] ?? '',
            $meta['description'] ?? '',
            $isPrivate,
            $hasEnv
        );

        if ($section) {
            $this->page->AddSectionSetting($setting);
        } else {
            $this->page->AddSetting($setting);
        }
    }

    public function Update()
    {
        $shouldShowConfig = Configuration::Instance()->GetKey(
            ConfigKeys::PAGES_CONFIGURATION_ENABLED,
            new BooleanConverter()
        );

        if (!$shouldShowConfig) {
            Log::Debug('Show configuration UI is turned off. No updates are allowed');
            return;
        }

        $configSettings = $this->page->GetSubmittedSettings();

        $configFiles = $this->GetConfigFiles();
        $this->HandleSelectedConfigFile($configFiles);

        $newSettings = [];

        // Check if this is a plugin config
        $isPluginConfig = $this->IsPluginConfigFile($this->configFilePath);
        $pluginId = $isPluginConfig ? $this->GetPluginIdFromPath($this->configFilePath) : null;

        foreach ($configSettings as $setting) {
            if (!empty($setting->Section)) {
                // For plugin configs, strip the plugin section wrapper if it matches
                if ($isPluginConfig && $pluginId && $setting->Section === $pluginId) {
                    // Flat structure: just use the key
                    $newSettings[$setting->Key] = $setting->Value;
                } else {
                    $newSettings[$setting->Section][$setting->Key] = $setting->Value;
                }
            } else {
                $newSettings[$setting->Key] = $setting->Value;
            }
        }

        $existingSettings = $this->configSettings->GetSettings($this->configFilePath);

        // Use the Configurator's BuildConfig method which handles env vars and private fields
        // Both existingSettings and newSettings are now flat structures for plugins
        $mergedSettings = $this->configSettings->BuildConfig($existingSettings, $newSettings, true);

        foreach ($this->deletedSettings as $deletedSetting) {
            if (array_key_exists($deletedSetting, $mergedSettings)) {
                unset($mergedSettings[$deletedSetting]);
            }
        }

        Log::Debug("Saving %s settings", count($configSettings));

        // WriteSettings will handle the proper nesting based on file type
        $this->configSettings->WriteSettings($this->configFilePath, $mergedSettings);

        Log::Debug('Config file saved by %s', ServiceLocator::GetServer()->GetUserSession()->Email);
    }

    public function SetHomepage()
    {
        $homepageId = $this->page->GetHomePageId();

        Log::Debug('Applying homepage to all users. HomepageId=%s', $homepageId);
        $command = new AdHocCommand('update users set homepageid = @homepageid');
        $command->AddParameter(new Parameter(ParameterNames::HOMEPAGE_ID, $homepageId));
        ServiceLocator::GetDatabase()->Execute($command);
    }

    private function ShouldBeSkipped(string $key, ?array $meta): bool
    {
        if ($meta === null) {
            Log::Debug("[CONFIG] No metadata found for key '%s'. Not skipped.", $key);
            return false;
        }

        if ($meta['is_hidden'] ?? false) {
            Log::Debug("[CONFIG] Skipping hidden config key '%s'.", $key);
            return true;
        }

        if (in_array($key, $this->deletedSettings)) {
            Log::Debug("[CONFIG] Skipping deleted config key '%s'.", $key);
            return true;
        }

        return false;
    }

    private function GetConfigFiles()
    {
        $files = [new ConfigFileOption('config.php', '', ConfigKeys::class, ROOT_DIR . 'lib/Config/ConfigKeys.php')];

        $pluginBaseDir = ROOT_DIR . 'plugins/';
        if ($h = opendir($pluginBaseDir)) {
            while (false !== ($entry = readdir($h))) {
                $pluginDir = $pluginBaseDir . $entry;
                if (is_dir($pluginDir) && $entry != "." && $entry != "..") {
                    $plugins = scandir($pluginDir);
                    foreach ($plugins as $plugin) {
                        if (is_dir("$pluginDir/$plugin") && $plugin != "." && $plugin != ".." && strpos($plugin, 'Example') === false) {
                            $configFiles = array_merge(glob("$pluginDir/$plugin/*.config.php"), glob("$pluginDir/$plugin/*.config.dist.php"));
                            if (count($configFiles) > 0) {
                                $configKeysFile = "/" . $plugin . "ConfigKeys.php";
                                $configKeysClass = $plugin . "ConfigKeys";
                                $files[] = new ConfigFileOption("$entry-$plugin", "$entry/$plugin", $configKeysClass, $configKeysFile);
                            }
                        }
                    }
                }
            }

            closedir($h);
        }

        return $files;
    }

    private function HandleSelectedConfigFile($configFiles)
    {
        $requestedConfigFile = $this->page->GetConfigFileToEdit();
        if (empty($requestedConfigFile)) {
            return;
        }

        $found = false;
        foreach ($configFiles as $file) {
            if ($file->Location == $requestedConfigFile) {
                $found = true;
                $this->page->SetSelectedConfigFile($requestedConfigFile);
                $this->configKeyClass = $file->ConfigKeysClass;
                $rootDir = ROOT_DIR . 'plugins/' . $requestedConfigFile;

                // Check if directory exists
                if (!is_dir($rootDir)) {
                    Log::Error("Plugin directory not found: $rootDir");
                    return;
                }

                // Handle config file creation if needed
                $distFile = glob("$rootDir/*config.dist.php");
                $configFile = glob("$rootDir/*config.php");

                if (count($distFile) == 1 && count($configFile) == 0) {
                    try {
                        copy($distFile[0], str_replace('.dist', '', $distFile[0]));
                        Log::Debug("Created new config file from dist template: {$distFile[0]}");
                    } catch (Exception $e) {
                        Log::Error("Failed to create config file: " . $e->getMessage());
                        return;
                    }
                    $configFile = glob("$rootDir/*config.php");
                }

                if (count($configFile) == 0) {
                    Log::Error("No config file found for plugin: $requestedConfigFile");
                    return;
                }

                $this->configFilePath = $configFile[0];
                $this->configFilePathDist = str_replace('.php', '.dist.php', $configFile[0]);
                $this->configKeyClassPath = $rootDir . $file->ConfigKeysClassLocation;

                break;
            }
        }

        if (!$found) {
            Log::Error("Requested config file not found: $requestedConfigFile");
        }
    }

    private function BringConfigFileUpToDate()
    {
        if (!file_exists($this->configFilePathDist)) {
            return;
        }

        $configurator = new Configurator();
        $configurator->Merge($this->configFilePath, $this->configFilePathDist);
    }

    private function CheckIfScriptUrlMayBeWrong()
    {
        $scriptUrl = Configuration::Instance()->GetScriptUrl();
        $server = ServiceLocator::GetServer();
        $currentUrl = $server->GetUrl();

        $maybeWrong = !BookedStringHelper::Contains($scriptUrl, '/Web') && BookedStringHelper::Contains($currentUrl, '/Web');
        if ($maybeWrong) {
            $parts = explode('/Web', $currentUrl);
            $port = $server->GetHeader('SERVER_PORT');
            $suggestedUrl = ($server->GetIsHttps() ? 'https://' : 'http://')
                . $server->GetHeader('SERVER_NAME')
                . ($port == '80' ? '' : $port)
                . $parts[0]
                . '/Web';
            $this->page->ShowScriptUrlWarning($scriptUrl, $suggestedUrl);
        }
    }

    private function IsPluginConfigFile($filePath)
    {
        $basename = basename($filePath);
        return $basename !== 'config.php' && preg_match('/\.config\.php$/', $basename);
    }

    private function GetPluginIdFromPath($filePath)
    {
        $basename = basename($filePath);
        
        if ($basename === 'config.php') {
            return null;
        }
        
        if (preg_match('/^([A-Z][a-z]+)\.config\.php$/', $basename, $matches)) {
            return strtolower($matches[1]);
        }
        
        return null;
    }
}

class ConfigFileOption
{
    public $Name;
    public $Location;
    public $ConfigKeysClass;
    public $ConfigKeysClassLocation;

    public function __construct($name, $location, $configKeysClass, $configKeysClassLocation)
    {
        $this->Name = $name;
        $this->Location = $location;
        $this->ConfigKeysClass = $configKeysClass;
        $this->ConfigKeysClassLocation = $configKeysClassLocation;
    }
}

class ConfigSetting
{
    public $Key;
    public $Section;
    public $Value;
    public $Type;
    public $Choices;
    public $Name;
    public $Label;
    public $Description;
    public $IsPrivate;
    public $hasEnv;


    public function __construct($key, $section, $value, $type = 'string', $choices = '', $label = '', $description = '', $isPrivate = false, $hasEnv = false)
    {
        $key = trim($key ?? '');
        $section = trim($section ?? '');
        $value = trim($value ?? '');

        $this->Name = $this->encode($key) . '|' . $this->encode($section);
        $this->Key = $key;
        $this->Section = $section;
        $this->Value = $value . '';
        $this->Type = $type;
        $this->Choices = $choices;
        $this->Label = $label;
        $this->Description = $description;
        $this->IsPrivate = $isPrivate;
        $this->hasEnv = $hasEnv;
    }

    public static function ParseForm($key, $value)
    {
        $k = self::decode($key);
        $keyAndSection = explode('|', $k);
        return new ConfigSetting($keyAndSection[0], $keyAndSection[1], $value);
    }

    private static function encode($value)
    {
        return str_replace('.', '__', $value);
    }

    private static function decode($value)
    {
        return str_replace('__', '.', $value);
    }
}


