<?php

require_once(ROOT_DIR . 'config/timezones.php');
require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'lib/Config/Configurator.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManageConfigurationPresenter.php');

interface IManageConfigurationPage extends IActionPage
{
    /**
     * @param bool $isPageEnabled
     */
    public function SetIsPageEnabled($isPageEnabled);

    /**
     * @param bool $isFileWritable
     */
    public function SetIsConfigFileWritable($isFileWritable);

    /**
     * @param ConfigSetting $configSetting
     */
    public function AddSetting(ConfigSetting $configSetting);

    /**
     * @param ConfigSetting $configSetting
     */
    public function AddSectionSetting(ConfigSetting $configSetting);

    /**
     * @return array|ConfigSetting[]
     */
    public function GetSubmittedSettings();

    /**
     * @param ConfigFileOption[] $configFiles
     */
    public function SetConfigFileOptions($configFiles);

    /**
     * @return string
     */
    public function GetConfigFileToEdit();

    /**
     * @param string $configFileName
     */
    public function SetSelectedConfigFile($configFileName);

    /**
     * @param string $scriptUrl
     * @param string $suggestedUrl
     */
    public function ShowScriptUrlWarning($scriptUrl, $suggestedUrl);

    /**
     * @param string[] $values
     */


    /**
     * @return int
     */
    public function GetHomePageId();
}

class ManageConfigurationPage extends ActionPage implements IManageConfigurationPage
{
    /**
     * @var ManageConfigurationPresenter
     */
    private $presenter;

    /**
     * @var array|ConfigSetting[]
     */
    private $settings;

    /**
     * @var array|ConfigSetting[]
     */
    private $sectionSettings;

    /**
     * @var StringBuilder
     */
    private $settingNames;

    public function __construct()
    {
        parent::__construct('ManageConfiguration', 1);
        $this->presenter = new ManageConfigurationPresenter($this, new Configurator());
        $this->settingNames = new StringBuilder();
    }

    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    public function ProcessDataRequest($dataRequest)
    {
        // no-op
    }

    public function ProcessPageLoad()
    {
        $this->Set('IsConfigFileWritable', true);

        $this->presenter->PageLoad();
        $this->Set('Settings', $this->settings);
        $this->Set('SectionSettings', $this->sectionSettings);
        $this->Set('DefaultTimezoneKey', ConfigKeys::DEFAULT_TIMEZONE['key']);
        $this->PopulateTimezones();
        $this->Set('DefaultLanguageKey', ConfigKeys::DEFAULT_LANGUAGE['key']);
        $this->Set('Languages', Resources::GetInstance()->AvailableLanguages);
        $this->Set('DefaultHomepageKey', ConfigKeys::DEFAULT_HOMEPAGE['key']);
        $this->Set('SettingNames', $this->settingNames->ToString());
        $this->Set('IsEnvPresent', Configuration::EnvFilePresent());
        $this->Display('Admin/Configuration/manage_configuration.tpl');
    }

    public function SetIsPageEnabled($isPageEnabled)
    {
        $this->Set('IsPageEnabled', $isPageEnabled);
    }

    public function SetIsConfigFileWritable($isFileWritable)
    {
        $this->Set('IsConfigFileWritable', $isFileWritable);
    }

    public function AddSetting(ConfigSetting $configSetting)
    {
        $this->settings[] = $configSetting;
        $this->settingNames->Append($configSetting->Name . ',');
    }

    public function AddSectionSetting(ConfigSetting $configSetting)
    {
        $this->sectionSettings[$configSetting->Section][] = $configSetting;
        $this->settingNames->Append($configSetting->Name . ',');
    }

    private function PopulateTimezones()
    {
        $timezoneValues = [];
        $timezoneOutput = [];

        foreach ($GLOBALS['APP_TIMEZONES'] as $timezone) {
            $timezoneValues[] = $timezone;
            $timezoneOutput[] = $timezone;
        }

        $this->Set('TimezoneValues', $timezoneValues);
        $this->Set('TimezoneOutput', $timezoneOutput);
    }

    public function GetSubmittedSettings()
    {
        $settingNames = $this->GetRawForm('setting_names');
        $settings = explode(',', $settingNames);
        $submittedSettings = [];
        foreach ($settings as $setting) {
            $setting = trim($setting);
            if (!empty($setting)) {
                //				Log::Debug("%s=%s", $setting, $this->GetForm($setting));
                $submittedSettings[] = ConfigSetting::ParseForm($setting, stripslashes($this->GetRawForm($setting)));
            }
        }

        return $submittedSettings;
    }

    public function SetConfigFileOptions($configFiles)
    {
        $this->Set('ConfigFiles', $configFiles);
    }

    public function GetConfigFileToEdit()
    {
        return $this->GetQuerystring(QueryStringKeys::CONFIG_FILE);
    }

    public function SetSelectedConfigFile($configFileName)
    {
        $this->Set('SelectedFile', $configFileName);
    }

    public function ShowScriptUrlWarning($currentScriptUrl, $suggestedScriptUrl)
    {
        $this->Set('CurrentScriptUrl', $currentScriptUrl);
        $this->Set('SuggestedScriptUrl', $suggestedScriptUrl);
        $this->Set('ShowScriptUrlWarning', true);
    }

    public function GetHomePageId()
    {
        return $this->GetForm('homepage_id');
    }
}
