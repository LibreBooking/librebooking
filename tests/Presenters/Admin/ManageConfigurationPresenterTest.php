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
     * @var IConfigurationSettings|PHPUnit\Framework\MockObject\MockObject
     * @var IConfigurationSettings|PHPUnit\Framework\MockObject\MockObject
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
        $this->fakeConfig->SetKey(ConfigKeys::PAGES_CONFIGURATION_ENABLED, 'true');
    }

    public function testDoesNothingIfPageIsNotEnabled()
    {
        $this->fakeConfig->SetKey(ConfigKeys::PAGES_CONFIGURATION_ENABLED, 'false');

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
        $this->assertSettingExists($configValues, ConfigKeys::PRIVACY_HIDE_RESERVATION_DETAILS);

        $this->assertSettingMissing(ConfigKeys::INSTALL_PASSWORD);
        $this->assertSettingMissing(ConfigKeys::PAGES_CONFIGURATION_ENABLED);
        $this->assertSettingMissing(ConfigKeys::DATABASE_PASSWORD);
        $this->assertSettingMissing(ConfigKeys::DATABASE_USER);
        $this->assertSettingMissing(ConfigKeys::DATABASE_HOSTSPEC);
        $this->assertSettingMissing(ConfigKeys::DATABASE_NAME);
        $this->assertSettingMissing(ConfigKeys::DATABASE_TYPE);
    }

    public function testUpdatesConfigFileWithSettings()
    {
        $setting1 = ConfigSetting::ParseForm('key1|', 'true');
        $setting2 = ConfigSetting::ParseForm('key2|section1', '10');
        $setting3 = ConfigSetting::ParseForm('key3|section1', 'some string');

        $newSettings['key1'] = 'true';
        $newSettings['section1']['key2'] = '10';
        $newSettings['section1']['key3'] = 'some string';

        $existingValues['oldKey1'] = 'old1';
        $existingValues['section2']['oldKey2'] = 'old2';

        $expectedMergedSettings['key1'] = 'true';
        $expectedMergedSettings['section1']['key2'] = '10';
        $expectedMergedSettings['section1']['key3'] = 'some string';
        $expectedMergedSettings['oldKey1'] = 'old1';
        $expectedMergedSettings['section2']['oldKey2'] = 'old2';

        $this->page->_SubmittedSettings = [$setting1, $setting2, $setting3];

        $this->configSettings->expects($this->once())
                ->method('GetSettings')
                ->with($this->equalTo($this->configFilePath))
                ->willReturn($existingValues);

        // Expect BuildConfig to be called with the right parameters
        $this->configSettings->expects($this->once())
                ->method('BuildConfig')
                ->with(
                    $this->equalTo($existingValues),
                    $this->equalTo($newSettings),
                    $this->equalTo(true)
                )
                ->willReturn($expectedMergedSettings);

        $this->configSettings->expects($this->once())
                ->method('WriteSettings')
                ->with($this->equalTo($this->configFilePath), $this->equalTo($expectedMergedSettings));

        $this->presenter->Update();
    }
    private function getDefaultConfigValues()
    {
        $configFile = realpath(ROOT_DIR . 'config/config.dist.php');
        $config = @require $configFile;

        if (isset($config['settings'])) {
            return $config['settings'];
        }
        return $config[Configuration::SETTINGS] ?? $config;
    }

    private function assertSettingExists($configValues, $configKey, $type = null)
    {
        $section = $configKey['section'] ?? null;
        $type = $type ?: ($configKey['type'] ?? ConfigSettingType::String);
        $key = $section ? str_replace("$section.", '', $configKey['key']) : $configKey['key'];

        if ($section) {
            $expectedValue = $configValues[$section][$key];
        } else {
            $expectedValue = $configValues[$key];
        }

        $expectedConfig = new ConfigSetting($key, $configKey['section'], $expectedValue, $type, $configKey['choices'] ?? '', $configKey['label'], $configKey['description'], $configKey['is_private']);

        if ($section) {
            $this->assertTrue(
                in_array($expectedConfig, $this->page->_SectionSettings[$section] ?? []),
                "Missing $key in section $section"
            );
        } else {
            $this->assertTrue(
                in_array($expectedConfig, $this->page->_Settings),
                "Missing $key"
            );
        }
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
        return null;
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
        return null;
    }
}
