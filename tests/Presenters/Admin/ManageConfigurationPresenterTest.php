<?php

require_once(ROOT_DIR . 'Presenters/Admin/ManageConfigurationPresenter.php');

class ManageConfigurationPresenterTest extends TestBase
{
    /**
     * @var ManageConfigurationPresenter
     */
    private $presenter;

    /**
     * @var FakeManageConfigurationPage
     */
    private $page;

    /**
     * @var IConfigurationSettings|PHPUnit_Framework_MockObject_MockObject
     */
    private $configSettings;

    /**
     * @var string
     */
    private $configFilePath;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeManageConfigurationPage();
        $this->configSettings = $this->createMock('IConfigurationSettings');

        $this->configFilePath = ROOT_DIR . 'config/config.php';

        $this->presenter = new ManageConfigurationPresenter($this->page, $this->configSettings);
        $this->fakeConfig->SetSectionKey(ConfigSection::PAGES, ConfigKeys::PAGES_ENABLE_CONFIGURATION, 'true');
    }

    public function testDoesNothingIfPageIsNotEnabled()
    {
        $this->fakeConfig->SetSectionKey(ConfigSection::PAGES, ConfigKeys::PAGES_ENABLE_CONFIGURATION, 'false');

        $this->presenter->PageLoad();

        $this->assertFalse($this->page->_PageEnabled);
    }

    public function testDoesNothingIfCannotOverwriteFile()
    {
        $this->configSettings->expects($this->once())
                ->method('CanOverwriteFile')
                ->with($this->equalTo($this->configFilePath))
                ->willReturn(false);

        $this->presenter->PageLoad();

        $this->assertFalse($this->page->_ConfigFileWritable);
    }

    public function testPopulatesPageFromExistingValues()
    {
        $this->configSettings->expects($this->once())
                ->method('CanOverwriteFile')
                ->with($this->equalTo($this->configFilePath))
                ->willReturn(true);

        $configValues = $this->getDefaultConfigValues();

        $this->configSettings->expects($this->once())
                ->method('GetSettings')
                ->with($this->equalTo($this->configFilePath))
                ->willReturn($configValues);

        $this->presenter->PageLoad();

        $this->assertSettingExists($configValues, ConfigKeys::ADMIN_EMAIL, ConfigSettingType::String);
        $this->assertSectionSettingExists(
            $configValues,
            ConfigKeys::PRIVACY_HIDE_RESERVATION_DETAILS,
            ConfigSection::PRIVACY,
            ConfigSettingType::Boolean
        );

        $this->assertSettingMissing(ConfigKeys::INSTALLATION_PASSWORD);
        $this->assertSettingMissing(ConfigKeys::PAGES_ENABLE_CONFIGURATION);
        $this->assertSettingMissing(ConfigKeys::DATABASE_PASSWORD, ConfigSection::DATABASE);
        $this->assertSettingMissing(ConfigKeys::DATABASE_USER, ConfigSection::DATABASE);
        $this->assertSettingMissing(ConfigKeys::DATABASE_HOSTSPEC, ConfigSection::DATABASE);
        $this->assertSettingMissing(ConfigKeys::DATABASE_NAME, ConfigSection::DATABASE);
        $this->assertSettingMissing(ConfigKeys::DATABASE_TYPE, ConfigSection::DATABASE);
    }

    public function testUpdatesConfigFileWithSettings()
    {
        $setting1 = ConfigSetting::ParseForm('key1|', 'true');
        $setting2 = ConfigSetting::ParseForm('key2|section1', '10');
        $setting3 = ConfigSetting::ParseForm('key3|section1', 'some string');

        $expectedSettings['key1'] = 'true';
        $expectedSettings['section1']['key2'] = '10';
        $expectedSettings['section1']['key3'] = 'some string';

        $existingValues['oldKey1'] = 'old1';
        $existingValues['section2']['oldKey2'] = 'old2';

        $newSettings['key1'] = 'true';
        $newSettings['section1']['key2'] = '10';
        $newSettings['section1']['key3'] = 'some string';
        $newSettings['oldKey1'] = 'old1';
        $newSettings['section2']['oldKey2'] = 'old2';

        $this->page->_SubmittedSettings = [$setting1, $setting2, $setting3];

        $this->configSettings->expects($this->once())
                ->method('GetSettings')
                ->with($this->equalTo($this->configFilePath))
                ->willReturn($existingValues);

        $this->configSettings->expects($this->once())
                ->method('WriteSettings')
                ->with($this->equalTo($this->configFilePath), $this->equalTo($newSettings));

        $this->presenter->Update();
    }

    private function getDefaultConfigValues()
    {
        $config = new Config();
        $current = $config->parseConfig(ROOT_DIR . 'config/config.dist.php', 'PHPArray');
        $currentValues = $current->getItem("section", Configuration::SETTINGS)->toArray();
        return $currentValues[Configuration::SETTINGS];
    }

    private function assertSettingExists($configValues, $key, $type = ConfigSettingType::String)
    {
        $expectedValue = $configValues[$key];
        $this->assertTrue(
            in_array(new ConfigSetting($key, null, $expectedValue, $type), $this->page->_Settings),
            "Missing $key"
        );
    }

    private function assertSectionSettingExists($configValues, $key, $section)
    {
        $expectedValue = $configValues[$section][$key];
        $this->assertTrue(in_array(
            new ConfigSetting($key, $section, $expectedValue),
            $this->page->_SectionSettings[$section]
        ));
    }

    private function assertSettingMissing($key, $section = null)
    {
        foreach ($this->page->_Settings as $setting) {
            if ($setting->Key == $key && $setting->Section == $section) {
                $this->fail("Config Settings should not contain key: $key and section: $section");
            }
        }
    }
}

class FakeManageConfigurationPage extends FakeActionPageBase implements IManageConfigurationPage
{
    /**
     * @var bool
     */
    public $_PageEnabled;

    /**
     * @var bool
     */
    public $_ConfigFileWritable;

    /**
     * @var array|ConfigSetting[]
     */
    public $_Settings = [];

    /**
     * @var array|ConfigSetting[]
     */
    public $_SectionSettings = [];

    /**
     * @var array|ConfigSetting[]
     */
    public $_SubmittedSettings = [];

    public function SetIsPageEnabled($isPageEnabled)
    {
        $this->_PageEnabled = $isPageEnabled;
    }

    /**
     * @param bool $isFileWritable
     */
    public function SetIsConfigFileWritable($isFileWritable)
    {
        $this->_ConfigFileWritable = $isFileWritable;
    }

    /**
     * @param ConfigSetting $configSetting
     */
    public function AddSectionSetting(ConfigSetting $configSetting)
    {
        $this->_SectionSettings[$configSetting->Section][] = $configSetting;
    }

    /**
     * @param ConfigSetting $configSetting
     */
    public function AddSetting(ConfigSetting $configSetting)
    {
        $this->_Settings[] = $configSetting;
    }

    /**
     * @return array|ConfigSetting[]
     */
    public function GetSubmittedSettings()
    {
        return $this->_SubmittedSettings;
    }

    /**
     * @param ConfigFileOption[] $configFiles
     */
    public function SetConfigFileOptions($configFiles)
    {
        // TODO: Implement SetConfigFileOptions() method.
    }

    /**
     * @return string
     */
    public function GetConfigFileToEdit()
    {
        // TODO: Implement GetConfigFileToEdit() method.
    }

    /**
     * @param string $configFileName
     */
    public function SetSelectedConfigFile($configFileName)
    {
        // TODO: Implement SetSelectedConfigFile() method.
    }

    /**
     * @param string[] $homepageValues
     * @param string[] $homepageOutput
     */
    public function SetHomepages($homepageValues, $homepageOutput)
    {
        // TODO: Implement SetHomepages() method.
    }

    /**
     * @param string $scriptUrl
     * @param string $suggestedUrl
     */
    public function ShowScriptUrlWarning($scriptUrl, $suggestedUrl)
    {
        // TODO: Implement ShowScriptUrlWarning() method.
    }

    /**
     * @param string[] $values
     */
    public function SetAuthenticationPluginValues($values)
    {
        // TODO: Implement SetAuthenticationPluginValues() method.
    }

    /**
     * @param string[] $values
     */
    public function SetAuthorizationPluginValues($values)
    {
        // TODO: Implement SetAuthorizationPluginValues() method.
    }

    /**
     * @param string[] $values
     */
    public function SetPermissionPluginValues($values)
    {
        // TODO: Implement SetPermissionPluginValues() method.
    }

    /**
     * @param string[] $values
     */
    public function SetPostRegistrationPluginValues($values)
    {
        // TODO: Implement SetPostRegistrationPluginValues() method.
    }

    /**
     * @param string[] $values
     */
    public function SetPreReservationPluginValues($values)
    {
        // TODO: Implement SetPreReservationPluginValues() method.
    }

    /**
     * @param string[] $values
     */
    public function SetPostReservationPluginValues($values)
    {
        // TODO: Implement SetPostReservationPluginValues() method.
    }

    /**
     * @param string[] $values
     */
    public function SetStylingPluginValues($values)
    {
        // TODO: Implement SetStylingPluginValues() method.
    }

    /**
     * @param string[] $values
     */
    public function SetExportPluginValues($values)
    {
        // TODO: Implement SetExportPluginValues() method.
    }

    /**
     * @return int
     */
    public function GetHomePageId()
    {
        // TODO: Implement GetHomePageId() method.
    }
}
