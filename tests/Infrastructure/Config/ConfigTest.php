<?php

require_once(ROOT_DIR . 'lib/Config/namespace.php');
require_once(ROOT_DIR . 'tests/data/test_plugin_configclass.php');

class ConfigTest extends TestBase
{

    private const CONFIG_ID = 'test';

    public function setup(): void
    {
        parent::setup();

        Configuration::SetInstance(null);
    }

    public function testConfigLoadsAllValues()
    {
        Configuration::Instance()->Register(ROOT_DIR . 'tests/data/test_config.php', '', self::CONFIG_ID, true);
        $config = Configuration::Instance()->File(self::CONFIG_ID);

        $this->assertEquals('US/Central', $config->GetDefaultTimezone());
        $this->assertEquals(true, $config->GetKey(ConfigKeys::REGISTRATION_ALLOW_SELF, new BooleanConverter()));
        $this->assertEquals('mysql', $config->GetKey(ConfigKeys::DATABASE_TYPE));
        $this->assertEquals('ActiveDirectory', $config->GetKey(ConfigKeys::PLUGIN_AUTHENTICATION));
    }

    public function testLegacyConfigLoadsAllValues()
    {
        Configuration::Instance()->Register(ROOT_DIR . 'tests/data/test_legacy_config.php', '', self::CONFIG_ID, true);
        $config = Configuration::Instance()->File(self::CONFIG_ID);

        $this->assertEquals('US/Central', $config->GetDefaultTimezone());
        $this->assertEquals(true, $config->GetKey(ConfigKeys::REGISTRATION_ALLOW_SELF, new BooleanConverter()));
        $this->assertEquals('mysql', $config->GetKey(ConfigKeys::DATABASE_TYPE));
        $this->assertEquals('ActiveDirectory', $config->GetKey(ConfigKeys::PLUGIN_AUTHENTICATION));
    }

    public function testMainConfigValidation()
    {
        Configuration::Instance()->Register(
            ROOT_DIR . 'tests/data/test_invalid_config.php',
            '',
            self::CONFIG_ID,
            true
        );

        $config = Configuration::Instance()->File(self::CONFIG_ID);

        $appDebug = $config->GetKey(ConfigKeys::APP_DEBUG, new BooleanConverter());
        $this->assertFalse($appDebug, "Invalid boolean should be replaced with default");

        $timeout = $config->GetKey(ConfigKeys::INACTIVITY_TIMEOUT, new IntConverter());
        $this->assertEquals(30, $timeout, "Invalid integer should be replaced with default");

        $loggingLevel = $config->GetKey(ConfigKeys::LOGGING_LEVEL);
        $this->assertEquals('none', $loggingLevel, "Invalid choice should be replaced with default");

        $minimumLetters = $config->GetKey(ConfigKeys::PASSWORD_MINIMUM_LETTERS, new IntConverter());
        $this->assertEquals(6, $minimumLetters, "Type conversion should return integer");
    }

    public function testRegistersPluginConfigFiles()
    {
        Configuration::Instance()->Register(ROOT_DIR . 'tests/data/test_config.php', '', self::CONFIG_ID, true);
        Configuration::Instance()->Register(ROOT_DIR . 'tests/data/test_plugin_config.php', '', TestPluginConfigKeys::CONFIG_ID, false, TestPluginConfigKeys::class);
        $config = Configuration::Instance()->File(self::CONFIG_ID);
        ;
        $pluginConfig = Configuration::Instance()->File(TestPluginConfigKeys::CONFIG_ID);

        $this->assertEquals('US/Central', $config->GetDefaultTimezone());
        $this->assertEquals('value1', $pluginConfig->GetKey(TestPluginConfigKeys::KEY1));
        $this->assertEquals('value2', $pluginConfig->GetKey(TestPluginConfigKeys::SERVER1_KEY));
        $this->assertEquals('value3', $pluginConfig->GetKey(TestPluginConfigKeys::SERVER2_KEY));
    }


    public function testPluginConfigValidation()
    {
        Configuration::Instance()->Register(ROOT_DIR . 'tests/data/test_invalid_plugin_config.php', '', TestPluginConfigKeys::CONFIG_ID, false, TestPluginConfigKeys::class);
        $pluginConfig = Configuration::Instance()->File(TestPluginConfigKeys::CONFIG_ID);

        // Test that invalid string is replaced with default
        $this->assertEquals('default1', $pluginConfig->GetKey(TestPluginConfigKeys::KEY1));

        // Test that invalid string is replaced with default
        $this->assertEquals('default2', $pluginConfig->GetKey(TestPluginConfigKeys::SERVER1_KEY));

        // Test that invalid choice is replaced with default
        $this->assertEquals('option1', $pluginConfig->GetKey(TestPluginConfigKeys::SERVER2_KEY));
    }

    public function testEnvOverridesConfigWithPutenv()
    {
        // Simulate environment variables
        putenv('DEFAULT_TIMEZONE=Europe/Berlin');

        Configuration::SetInstance(null);
        Configuration::Instance()->Register(ROOT_DIR . 'tests/data/test_config_env.php', 'tests/data/test.env', self::CONFIG_ID, true);
        $config = Configuration::Instance()->File(self::CONFIG_ID);

        $this->assertEquals('UCT', $config->GetDefaultTimezone());
    }

    /**
     * Test that the actual config.dist.php loads correctly
     * and all its nested values are accessible
     */
    public function testConfigDistLoadsCorrectly()
    {
        // Load the config.dist.php file
        Configuration::Instance()->Register(ROOT_DIR . 'config/config.dist.php', '', Configuration::DEFAULT_CONFIG_ID, true);
        $config = Configuration::Instance();

        // Define expected values to check (a representative subset)
        $expectedValues = [
            // Top level settings
            'app.title' => env('LB_APP_TITLE', 'LibreBooking'),
            //'default.timezone' => env('LB_DEFAULT_TIMEZONE', 'Europe/London'),
            'default.language' => env('LB_DEFAULT_LANGUAGE', 'en_us'),
            'app.debug' => env('LB_APP_DEBUG', false),  // boolean
            'inactivity.timeout' => env('LB_INACTIVITY_TIMEOUT', 30),  // integer

            // Sections with nested values
            'database' => [
                'type' => env('LB_DATABASE_TYPE', 'mysql'),
                'hostspec' => env('LB_DATABASE_HOSTSPEC', '127.0.0.1'),
                'name' => env('LB_DATABASE_NAME', 'librebooking'),
                'user' => env('LB_DATABASE_USER', 'lb_user'),
                'password' => env('LB_DATABASE_PASSWORD', 'password')
            ],
            'privacy' => [
                'view.schedules' => env('LB_PRIVACY_VIEW_SCHEDULES', true),
                'view.reservations' => env('LB_PRIVACY_VIEW_RESERVATIONS', false),
                'hide.user.details' => env('LB_PRIVACY_HIDE_USER_DETAILS', false),
                'hide.reservation.details' => env('LB_PRIVACY_HIDE_RESERVATION_DETAILS', false)
            ],
            'reservation' => [
                'start.time.constraint' => env('LB_RESERVATION_START_TIME_CONSTRAINT', 'future')
            ]
        ];

        // Verify all expected values
        $this->verifyConfigValues($expectedValues, $config);
    }

    /**
     * Helper method to verify all values in the expected array
     * match what's in the configuration
     *
     * @param array $expected Expected values to check
     * @param Configuration $config Configuration instance
     */
    private function verifyConfigValues($expected, $config)
    {
        foreach ($expected as $key => $value) {
            if (is_array($value)) {
                // This is a section with nested values
                foreach ($value as $sectionKey => $sectionValue) {
                    $fullkey = "$key.$sectionKey";
                    if (is_bool($sectionValue)) {
                        $this->assertEquals(
                            $sectionValue,
                            $config->GetKey(ConfigKeys::findByKey($fullkey), new BooleanConverter()),
                            "Boolean value mismatch for key '$fullkey'"
                        );
                    } elseif (is_int($sectionValue)) {
                        $this->assertEquals(
                            $sectionValue,
                            $config->GetKey(ConfigKeys::findByKey($fullkey), new IntConverter()),
                            "Integer value mismatch for key '$fullkey'"
                        );
                    } else {
                        $this->assertEquals(
                            $sectionValue,
                            $config->GetKey(ConfigKeys::findByKey($fullkey)),
                            "String value mismatch for key '$fullkey'"
                        );
                    }
                }
            } else {
                // This is a top-level key
                if (is_bool($value)) {
                    $this->assertEquals(
                        $value,
                        $config->GetKey(ConfigKeys::findByKey($key), new BooleanConverter()),
                        "Boolean value mismatch for key '$key'"
                    );
                } elseif (is_int($value)) {
                    $this->assertEquals(
                        $value,
                        $config->GetKey(ConfigKeys::findByKey($key), new IntConverter()),
                        "Integer value mismatch for key '$key'"
                    );
                } else {
                    $this->assertEquals(
                        $value,
                        $config->GetKey(ConfigKeys::findByKey($key)),
                        "String value mismatch for key '$key'"
                    );
                }
            }
        }
    }
}
