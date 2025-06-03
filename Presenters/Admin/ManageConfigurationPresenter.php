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
     * @var string[]|array[]
     */
    private $deletedSettings = [
        'password.pattern',
        'use.local.jquery'];

    private $deletedSectionSettings = [
        ConfigSection::AUTHENTICATION => ['allow.social.login'],
        ConfigSection::ICS => ['require.login', 'import', 'import.key'],
        ConfigSection::RESERVATION => ['maximum.resources']
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
        $shouldShowConfig = Configuration::Instance()->GetSectionKey(
            ConfigSection::PAGES,
            ConfigKeys::PAGES_ENABLE_CONFIGURATION,
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

        Log::Debug(
            'Loading and displaying config file for editing by %s',
            ServiceLocator::GetServer()->GetUserSession()->Email
        );

        $this->BringConfigFileUpToDate();

        $settings = $this->configSettings->GetSettings($this->configFilePath);

        foreach ($settings as $key => $value) {
            if (is_array($value)) {
                $section = $key;
                foreach ($value as $sectionkey => $sectionvalue) {
                    if (!$this->ShouldBeSkipped($sectionkey, $section)) {
                        $this->page->AddSectionSetting(new ConfigSetting($sectionkey, $section, $sectionvalue));
                    }
                }
            } else {
                if (!$this->ShouldBeSkipped($key)) {
                    $this->page->AddSetting(new ConfigSetting($key, null, $value));
                }
            }
        }

        $this->PopulateHomepages();
        $this->PopulatePlugins();
    }

    private function PopulateHomepages()
    {
        $homepageValues = [];
        $homepageOutput = [];

        $pages = Pages::GetAvailablePages();
        foreach ($pages as $pageid => $page) {
            $homepageValues[] = $pageid;
            $homepageOutput[] = Resources::GetInstance()->GetString($page['name']);
        }

        $this->page->SetHomepages($homepageValues, $homepageOutput);
    }

    private function PopulatePlugins()
    {
        $plugins = [];
        $dit = new RecursiveDirectoryIterator(ROOT_DIR . 'plugins');

        /** @var $path SplFileInfo */
        foreach ($dit as $path) {
            if ($path->isDir() && basename($path->getPathname()) != '.' && basename($path->getPathname()) != '..') {
                $plugins[basename($path->getPathname())] = [];
                /** @var $plugin SplFileInfo */
                foreach (new RecursiveDirectoryIterator($path) as $plugin) {
                    if ($plugin->isDir() && basename($plugin->getPathname()) != '.' && basename($plugin->getPathname()) != '..') {
                        $pluginCategory = basename($path->getPathname());
                        if (!isset($plugins[$pluginCategory]) || empty($plugins[$pluginCategory])) {
                            $plugins[$pluginCategory][] = '';
                        }
                        $plugins[$pluginCategory][] = basename($plugin->getPathname());
                    }
                }
            }
        }

        $this->page->SetAuthenticationPluginValues($plugins['Authentication']);
        $this->page->SetAuthorizationPluginValues($plugins['Authorization']);
        $this->page->SetPermissionPluginValues($plugins['Permission']);
        $this->page->SetPostRegistrationPluginValues($plugins['PostRegistration']);
        $this->page->SetPreReservationPluginValues($plugins['PreReservation']);
        $this->page->SetPostReservationPluginValues($plugins['PostReservation']);
        $this->page->SetStylingPluginValues($plugins['Styling']);
        $this->page->SetExportPluginValues($plugins['Export']);
    }

    public function Update()
    {
        $shouldShowConfig = Configuration::Instance()->GetSectionKey(
            ConfigSection::PAGES,
            ConfigKeys::PAGES_ENABLE_CONFIGURATION,
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

        foreach ($configSettings as $setting) {
            if (!empty($setting->Section)) {
                $newSettings[$setting->Section][$setting->Key] = $setting->Value;
            } else {
                $newSettings[$setting->Key] = $setting->Value;
            }
        }

        $existingSettings = $this->configSettings->GetSettings($this->configFilePath);
        $mergedSettings = array_merge($existingSettings, $newSettings);

        foreach ($this->deletedSettings as $deletedSetting) {
            if (array_key_exists($deletedSetting, $mergedSettings)) {
                unset($mergedSettings[$deletedSetting]);
            }
        }

        foreach ($this->deletedSectionSettings as $section => $setting) {
            if (array_key_exists($section, $mergedSettings) && in_array($setting, $mergedSettings[$section])) {
                unset($mergedSettings[$section][$setting]);
                if (count($mergedSettings[$section]) == 0) {
                    unset($mergedSettings[$section]);
                }
            }
        }

        Log::Debug("Saving %s settings", count($configSettings));

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

    private function ShouldBeSkipped($key, $section = null)
    {
        if ($section == ConfigSection::DATABASE) {
            return true;
        }
        if (in_array($key, $this->deletedSettings)) {
            return true;
        }
        if (array_key_exists($section, $this->deletedSectionSettings) && in_array($key, $this->deletedSectionSettings[$section])) {
            return true;
        }

        switch ($key) {
            case ConfigKeys::INSTALLATION_PASSWORD:
            case ConfigKeys::PAGES_ENABLE_CONFIGURATION && $section == ConfigSection::PAGES:
                return true;
            default:
                return false;
        }
    }

    private function GetConfigFiles()
    {
        $files = [new ConfigFileOption('config.php', '')];

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
                                $files[] = new ConfigFileOption("$entry-$plugin", "$entry/$plugin");
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
        if (!empty($requestedConfigFile)) {
            /** @var $file ConfigFileOption */
            foreach ($configFiles as $file) {
                if ($file->Location == $requestedConfigFile) {
                    $this->page->SetSelectedConfigFile($requestedConfigFile);

                    $rootDir = ROOT_DIR . 'plugins/' . $requestedConfigFile;

                    $distFile = glob("$rootDir/*config.dist.php");
                    $configFile = glob("$rootDir/*config.php");
                    if (count($distFile) == 1 && count($configFile) == 0) {
                        copy($distFile[0], str_replace('.dist', '', $distFile[0]));
                    }
                    $configFile = glob("$rootDir/*config.php");
                    $this->configFilePath = $configFile[0];
                    $this->configFilePathDist = str_replace('.php', '.dist.php', $configFile[0]);
                }
            }
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
}

class ConfigFileOption
{
    public $Name;
    public $Location;
    public function __construct($name, $location)
    {
        $this->Name = $name;
        $this->Location = $location;
    }
}

class ConfigSetting
{
    public $Key;
    public $Section;
    public $Value;
    public $Type;
    public $Name;

    public function __construct($key, $section, $value)
    {
        $key = trim($key ?? '');
        $section = trim($section ?? '');
        $value = trim($value ?? '');

        $this->Name = $this->encode($key) . '|' . $this->encode($section);
        $this->Key = $key;
        $this->Section = $section;
        $this->Value = $value . '';

        $type = strtolower($value) == 'true' || strtolower($value) == 'false' ? ConfigSettingType::Boolean : ConfigSettingType::String;

        if ($key == ConfigKeys::PRIVACY_HIDE_RESERVATION_DETAILS && $section == ConfigSection::PRIVACY) {
            $type = ConfigSettingType::String;
        }

        $this->Type = $type;

        if ($type == ConfigSettingType::Boolean) {
            $this->Value = strtolower($this->Value);
        }
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

class ConfigSettingType
{
    public const String = 'string';
    public const Boolean = 'boolean';
}
