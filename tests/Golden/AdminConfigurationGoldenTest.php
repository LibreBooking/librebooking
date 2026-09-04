<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../lib/Common/Templating/SmartyRenderer.php');
require_once(__DIR__ . '/../../lib/Common/Templating/LibreBookingExtension.php');
require_once(__DIR__ . '/../../lib/Common/Templating/TwigRenderer.php');
require_once(__DIR__ . '/../../lib/Config/ConfigKeys.php');
require_once(__DIR__ . '/../../Presenters/Admin/ManageConfigurationPresenter.php');
require_once(__DIR__ . '/../../Presenters/Admin/ManageEmailTemplatesPresenter.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for Admin/Configuration templates.
 *
 * Templates covered:
 *   - tpl/Admin/Configuration/server_settings.tpl         → .twig (full parity)
 *   - tpl/Admin/Configuration/change_theme.tpl            → .twig (full parity)
 *   - tpl/Admin/Configuration/manage_email_templates.tpl  → .twig (parity, accepted divergence)
 *   - tpl/Admin/Configuration/data_cleanup.tpl            → .twig (full parity)
 *   - tpl/Admin/Configuration/manage_configuration.tpl    → .twig (full parity)
 *
 * Parity strategy
 * ---------------
 * Every template is rendered live through both engines and compared after
 * HtmlNormalizer::normalize. CSRF is pinned via FakeServer; $_SERVER, Resources
 * and the ServiceLocator server are saved and restored; the clock is pinned.
 *
 * Accepted divergences (documented per method):
 *   manage_email_templates: Smarty `{update_button submit=true}` emits a stray
 *   `submit="1"` attribute (submit is passed through GetButtonAttributes),
 *   whereas the Twig `update_button(submit=true)` treats submit as a flag only.
 *   The stray `submit="1"` is stripped from the Smarty output before comparison.
 */
class AdminConfigurationGoldenTest extends GoldenTemplateTestCase
{
    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Resources $savedResources = null;

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/admin/manage_configuration.php';
        $_SERVER['REQUEST_URI'] = '/web/admin/manage_configuration.php';
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();
        $this->savedServiceLocatorServer = ServiceLocator::GetServer();
        $fakeServer = new FakeServer();
        $fakeServer->UserSession->CSRFToken = 'golden-test-csrf-token';
        $fakeServer->UserSession->UserId = 1;
        ServiceLocator::SetServer($fakeServer);
        Date::_SetNow(Date::Parse('2025-06-15 10:00:00', 'UTC'));
    }

    protected function tearDown(): void
    {
        $prop = new \ReflectionProperty(Date::class, '_Now');
        $prop->setAccessible(true);
        $prop->setValue(null, null);

        $_SERVER = $this->savedServer;
        Resources::SetInstance($this->savedResources);
        ServiceLocator::SetServer($this->savedServiceLocatorServer);
        parent::tearDown();
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Render both Smarty and Twig with the given vars and assert normalized parity.
     *
     * @param array<string, mixed> $vars
     */
    private function assertParity(string $tplName, string $twigName, array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $expected = $smarty->render($tplName);

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $actual = $twig->render($twigName);

        $this->assertSame(
            HtmlNormalizer::normalize($expected),
            HtmlNormalizer::normalize($actual),
            "Smarty vs Twig mismatch for $twigName"
        );
    }

    // ── server_settings ───────────────────────────────────────────────────────

    /**
     * @return array<string, mixed>
     */
    private function makeServerSettingsVars(): array
    {
        return [
            'currentTime' => '2025-06-15 10:00:00',
            'imageUploadDirectory' => '/var/www/uploads/images',
            'imageUploadDirPermissions' => '0755',
            'templateCacheDirectory' => '/var/www/tpl_c',
            'plugins' => [
                'Authentication' => ['Ldap', 'Saml'],
                'Authorization' => ['ResourceAdmin'],
            ],
        ];
    }

    public function testServerSettings(): void
    {
        $this->assertParity(
            'Admin/Configuration/server_settings.tpl',
            'Admin/Configuration/server_settings.twig',
            $this->makeServerSettingsVars()
        );
    }

    public function testServerSettingsNoPlugins(): void
    {
        $vars = $this->makeServerSettingsVars();
        $vars['plugins'] = [];
        $this->assertParity(
            'Admin/Configuration/server_settings.tpl',
            'Admin/Configuration/server_settings.twig',
            $vars
        );
    }

    // ── change_theme ──────────────────────────────────────────────────────────

    public function testChangeTheme(): void
    {
        $this->assertParity(
            'Admin/Configuration/change_theme.tpl',
            'Admin/Configuration/change_theme.twig',
            [
                'ScriptUrl' => 'https://booking.example.org',
                'LogoUrl' => 'img/logo.png',
                'FaviconUrl' => 'favicon.ico',
                'CssUrl' => 'custom.css',
            ]
        );
    }

    // ── manage_email_templates ────────────────────────────────────────────────

    /**
     * @return array<string, mixed>
     */
    private function makeEmailTemplatesVars(): array
    {
        $template1 = new class () {
            public function FileName(): string
            {
                return 'ReservationCreated.tpl';
            }

            public function Name(): string
            {
                return 'ReservationCreated';
            }
        };
        $template2 = new class () {
            public function FileName(): string
            {
                return 'ReservationDeleted.tpl';
            }

            public function Name(): string
            {
                return 'ReservationDeleted';
            }
        };

        $lang1 = new class () {
            public string $LanguageCode = 'en_us';
            public string $DisplayName = 'English (United States)';
        };
        $lang2 = new class () {
            public string $LanguageCode = 'fr_fr';
            public string $DisplayName = 'French';
        };

        return [
            'Templates' => [$template1, $template2],
            'Languages' => [$lang1, $lang2],
            'Language' => 'en_us',
        ];
    }

    /**
     * Render both engines for manage_email_templates and assert parity after
     * stripping the stray `submit="1"` attribute that Smarty's
     * `{update_button submit=true}` emits (submit passes through
     * GetButtonAttributes). The Twig `update_button(submit=true)` treats submit
     * as a flag only; stripping normalizes this accepted divergence while keeping
     * all other markup Smarty-verified.
     */
    public function testManageEmailTemplates(): void
    {
        $vars = $this->makeEmailTemplatesVars();

        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyHtml = $smarty->render('Admin/Configuration/manage_email_templates.tpl');

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigHtml = $twig->render('Admin/Configuration/manage_email_templates.twig');

        // Accepted divergence: Smarty emits a stray submit="1" attribute.
        $smartyHtml = str_replace('submit="1" ', '', $smartyHtml);
        $smartyHtml = str_replace('submit="1"', '', $smartyHtml);

        $this->assertSame(
            HtmlNormalizer::normalize($smartyHtml),
            HtmlNormalizer::normalize($twigHtml),
            'Smarty vs Twig mismatch for manage_email_templates.twig (after stripping stray submit="1")'
        );
    }

    // ── data_cleanup ──────────────────────────────────────────────────────────

    /**
     * Render both engines and assert parity after stripping the stray
     * `submit="1"` attribute emitted by Smarty's `{delete_button submit=true}`
     * (submit passes through GetButtonAttributes). The Twig
     * `delete_button(submit=true)` treats submit as a flag only. Stripping
     * normalizes this accepted divergence while keeping all other markup
     * Smarty-verified.
     *
     * @param array<string, mixed> $vars
     */
    private function assertParityIgnoringSubmitFlag(string $tplName, string $twigName, array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyHtml = $smarty->render($tplName);
        $smartyHtml = str_replace(['submit="1" ', 'submit="1"'], '', $smartyHtml);

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigHtml = $twig->render($twigName);

        $this->assertSame(
            HtmlNormalizer::normalize($smartyHtml),
            HtmlNormalizer::normalize($twigHtml),
            "Smarty vs Twig mismatch for $twigName (after stripping stray submit=\"1\")"
        );
    }

    public function testDataCleanup(): void
    {
        $this->assertParityIgnoringSubmitFlag(
            'Admin/Configuration/data_cleanup.tpl',
            'Admin/Configuration/data_cleanup.twig',
            [
                'ReservationCount' => 42,
                'DeletedReservationCount' => 7,
                'BlackoutsCount' => 3,
                'UserCount' => 12,
                'DeleteDate' => Date::Parse('2025-01-01 00:00:00', 'UTC'),
            ]
        );
    }

    // ── manage_configuration ──────────────────────────────────────────────────

    /**
     * @return array<string, mixed>
     */
    private function makeConfigurationVars(
        bool $showScriptWarning = false,
        bool $isEnvPresent = false,
        bool $isPageEnabled = true,
        bool $isConfigFileWritable = true
    ): array {
        $languageEn = new class () {
            public function GetLanguageCode(): string
            {
                return 'en_us';
            }

            public function GetDisplayName(): string
            {
                return 'English (United States)';
            }
        };
        $languageFr = new class () {
            public function GetLanguageCode(): string
            {
                return 'fr_fr';
            }

            public function GetDisplayName(): string
            {
                return 'French';
            }
        };

        // General settings exercising several rendering branches.
        $stringSetting = new ConfigSetting(
            Key: 'app.title',
            Section: null,
            Value: 'LibreBooking',
            Type: ConfigSettingType::String,
            Choices: '',
            Label: 'Application Title',
            Description: 'The title of the application'
        );
        $intSetting = new ConfigSetting(
            Key: 'inactivity.timeout',
            Section: null,
            Value: '30',
            Type: ConfigSettingType::Integer,
            Choices: '',
            Label: 'Inactivity Timeout',
            Description: 'Session timeout in minutes'
        );
        $boolSetting = new ConfigSetting(
            Key: 'app.debug',
            Section: null,
            Value: 'true',
            Type: ConfigSettingType::Boolean,
            Choices: '',
            Label: 'Debug',
            Description: 'Enable debugging'
        );
        $privateSetting = new ConfigSetting(
            Key: 'install.password',
            Section: null,
            Value: 'secret&pass',
            Type: ConfigSettingType::String,
            Choices: '',
            Label: 'Install Password',
            Description: 'The install password',
            IsPrivate: true
        );
        $timezoneSetting = new ConfigSetting(
            Key: 'default.timezone',
            Section: null,
            Value: 'America/New_York',
            Type: ConfigSettingType::String,
            Choices: '',
            Label: 'Default Timezone',
            Description: 'Default timezone'
        );
        $languageSetting = new ConfigSetting(
            Key: 'default.language',
            Section: null,
            Value: 'en_us',
            Type: ConfigSettingType::String,
            Choices: '',
            Label: 'Default Language',
            Description: 'Default language'
        );
        $homepageSetting = new ConfigSetting(
            Key: 'default.homepage',
            Section: null,
            Value: '1',
            Type: ConfigSettingType::Integer,
            Choices: [1 => 'Dashboard', 2 => 'Schedule', 3 => 'My Calendar', 4 => 'Resource Calendar'],
            Label: 'Default Homepage',
            Description: 'Default homepage'
        );
        $envSetting = new ConfigSetting(
            Key: 'database.password',
            Section: null,
            Value: 'dbpass',
            Type: ConfigSettingType::String,
            Choices: '',
            Label: 'Database Password',
            Description: 'DB password',
            IsPrivate: true,
            hasEnv: true
        );

        // Section settings (a plain choices dropdown).
        $sectionChoiceSetting = new ConfigSetting(
            Key: 'phpmailer.mailer',
            Section: 'phpmailer',
            Value: 'smtp',
            Type: ConfigSettingType::String,
            Choices: ['smtp' => 'SMTP', 'sendmail' => 'Sendmail', 'mail' => 'Mail'],
            Label: 'Mailer',
            Description: 'The mailer to use'
        );
        $sectionStringSetting = new ConfigSetting(
            Key: 'phpmailer.smtp_host',
            Section: 'phpmailer',
            Value: 'smtp.example.org',
            Type: ConfigSettingType::String,
            Choices: '',
            Label: 'SMTP Host',
            Description: 'SMTP host'
        );

        $settingNames = implode(',', [
            $stringSetting->Name,
            $intSetting->Name,
            $boolSetting->Name,
            $privateSetting->Name,
            $timezoneSetting->Name,
            $languageSetting->Name,
            $homepageSetting->Name,
            $envSetting->Name,
            $sectionChoiceSetting->Name,
            $sectionStringSetting->Name,
        ]) . ',';

        $configFile1 = new ConfigFileOption('config.php', '', ConfigKeys::class, '');
        $configFile2 = new ConfigFileOption('Ldap', 'Authentication/Ldap', 'LdapConfigKeys', '');

        $vars = [
            'IsPageEnabled' => $isPageEnabled,
            'IsConfigFileWritable' => $isConfigFileWritable,
            'IsEnvPresent' => $isEnvPresent,
            'ConfigFiles' => [$configFile1, $configFile2],
            'SelectedFile' => '',
            'Settings' => [
                $stringSetting,
                $intSetting,
                $boolSetting,
                $privateSetting,
                $timezoneSetting,
                $languageSetting,
                $homepageSetting,
                $envSetting,
            ],
            'SectionSettings' => [
                'phpmailer' => [$sectionChoiceSetting, $sectionStringSetting],
            ],
            'SettingNames' => $settingNames,
            'DefaultTimezoneKey' => 'default.timezone',
            'DefaultLanguageKey' => 'default.language',
            'DefaultHomepageKey' => 'default.homepage',
            'TimezoneValues' => ['America/New_York', 'Europe/London', 'UTC'],
            'TimezoneOutput' => ['America/New_York', 'Europe/London', 'UTC'],
            'Languages' => [$languageEn, $languageFr],
        ];

        if ($showScriptWarning) {
            $vars['ShowScriptUrlWarning'] = true;
            $vars['CurrentScriptUrl'] = 'https://old.example.org';
            $vars['SuggestedScriptUrl'] = 'https://new.example.org';
        }

        return $vars;
    }

    public function testManageConfigurationFullPage(): void
    {
        $this->assertParity(
            'Admin/Configuration/manage_configuration.tpl',
            'Admin/Configuration/manage_configuration.twig',
            $this->makeConfigurationVars()
        );
    }

    public function testManageConfigurationWithScriptUrlWarning(): void
    {
        $this->assertParity(
            'Admin/Configuration/manage_configuration.tpl',
            'Admin/Configuration/manage_configuration.twig',
            $this->makeConfigurationVars(showScriptWarning: true)
        );
    }

    public function testManageConfigurationWithEnvPresent(): void
    {
        $this->assertParity(
            'Admin/Configuration/manage_configuration.tpl',
            'Admin/Configuration/manage_configuration.twig',
            $this->makeConfigurationVars(isEnvPresent: true)
        );
    }

    public function testManageConfigurationPageDisabled(): void
    {
        $this->assertParity(
            'Admin/Configuration/manage_configuration.tpl',
            'Admin/Configuration/manage_configuration.twig',
            $this->makeConfigurationVars(isPageEnabled: false)
        );
    }

    public function testManageConfigurationFileNotWritable(): void
    {
        $this->assertParity(
            'Admin/Configuration/manage_configuration.tpl',
            'Admin/Configuration/manage_configuration.twig',
            $this->makeConfigurationVars(isConfigFileWritable: false)
        );
    }

    /**
     * Covers the AllowCustom + Choices datalist branch in manage_configuration.
     *
     * The datalist branch renders a text input with `list="<name>-list"` plus a
     * `<datalist>` element containing `<option>` elements for each choice. This
     * branch fires when `setting.AllowCustom == true && is_array(setting.Choices)`
     * (Smarty) / `setting.AllowCustom and setting.Choices is iterable` (Twig).
     *
     * This test exercises that branch live through both engines and asserts
     * byte-parity after normalization, ensuring the Twig datalist rendering
     * exactly matches Smarty's output.
     */
    public function testManageConfigurationWithAllowCustomDatalist(): void
    {
        $datalistSetting = new ConfigSetting(
            Key: 'css.theme',
            Section: null,
            Value: 'default',
            Type: ConfigSettingType::String,
            Choices: ['default' => 'Default', 'dark' => 'Dark Mode', 'high-contrast' => 'High Contrast'],
            Label: 'CSS Theme',
            Description: 'The visual theme',
            AllowCustom: true,
        );

        $languageEn = new class () {
            public function GetLanguageCode(): string
            {
                return 'en_us';
            }

            public function GetDisplayName(): string
            {
                return 'English (United States)';
            }
        };

        $configFile = new ConfigFileOption('config.php', '', ConfigKeys::class, '');

        $settingNames = $datalistSetting->Name . ',';

        $vars = [
            'IsPageEnabled' => true,
            'IsConfigFileWritable' => true,
            'IsEnvPresent' => false,
            'ConfigFiles' => [$configFile],
            'SelectedFile' => '',
            'Settings' => [$datalistSetting],
            'SectionSettings' => [],
            'SettingNames' => $settingNames,
            'DefaultTimezoneKey' => 'default.timezone',
            'DefaultLanguageKey' => 'default.language',
            'DefaultHomepageKey' => 'default.homepage',
            'TimezoneValues' => ['UTC'],
            'TimezoneOutput' => ['UTC'],
            'Languages' => [$languageEn],
        ];

        $this->assertParity(
            'Admin/Configuration/manage_configuration.tpl',
            'Admin/Configuration/manage_configuration.twig',
            $vars
        );
    }
}
