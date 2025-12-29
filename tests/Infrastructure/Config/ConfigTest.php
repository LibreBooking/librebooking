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

        $this->assertEquals('America/Chicago', $config->GetDefaultTimezone());
        $this->assertEquals(true, $config->GetKey(ConfigKeys::REGISTRATION_ALLOW_SELF, new BooleanConverter()));
        $this->assertEquals('mysql', $config->GetKey(ConfigKeys::DATABASE_TYPE));
        $this->assertEquals('ActiveDirectory', $config->GetKey(ConfigKeys::PLUGIN_AUTHENTICATION));
    }

    public function testLegacyConfigLoadsAllValues()
    {
        $errorLogs = $this->captureErrorLog(function () {
            Configuration::Instance()->Register(ROOT_DIR . 'tests/data/test_legacy_config.php', '', self::CONFIG_ID, true);
            $config = Configuration::Instance()->File(self::CONFIG_ID);

            $this->assertEquals('America/Chicago', $config->GetDefaultTimezone());
            $this->assertEquals(true, $config->GetKey(ConfigKeys::REGISTRATION_ALLOW_SELF, new BooleanConverter()));
            $this->assertEquals('mysql', $config->GetKey(ConfigKeys::DATABASE_TYPE));
            $this->assertEquals('ActiveDirectory', $config->GetKey(ConfigKeys::PLUGIN_AUTHENTICATION));
        });

        $this->assertLogMessage($errorLogs, 'Legacy config format detected', 'legacy config format detection');
        $this->assertLogMessage($errorLogs, "Deprecated config key 'allow.self.registration'", 'deprecated allow.self.registration key');
        $this->assertLogMessage($errorLogs, "Deprecated config key 'plugins.Authentication'", 'deprecated plugins.Authentication key');
    }

    public function testMainConfigValidation()
    {
        $errorLogs = $this->captureErrorLog(function () {
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
            $this->assertEquals('error', $loggingLevel, "Invalid choice should be replaced with default");

            $minimumLetters = $config->GetKey(ConfigKeys::PASSWORD_MINIMUM_LETTERS, new IntConverter());
            $this->assertEquals(6, $minimumLetters, "Type conversion should return integer");
        });

        $this->assertLogMessage($errorLogs, "Invalid type for 'app.debug'. Should be 'boolean'", 'app.debug type validation error');
        $this->assertLogMessage($errorLogs, "Invalid type for 'app.title'. Should be 'string'", 'app.title type validation error');
        $this->assertLogMessage($errorLogs, "Invalid value 'super-verbose' for 'logging.level'", 'logging.level value validation error');
        $this->assertLogMessage($errorLogs, "Invalid type for 'inactivity.timeout'. Should be 'integer'", 'inactivity.timeout type validation error');
        $this->assertLogMessage($errorLogs, "Invalid type for 'password.minimum.letters'. Should be 'integer'", 'password.minimum.letters type validation error');
    }

    public function testRegistersPluginConfigFiles()
    {
        Configuration::Instance()->Register(ROOT_DIR . 'tests/data/test_config.php', '', self::CONFIG_ID, true);
        Configuration::Instance()->Register(ROOT_DIR . 'tests/data/test_plugin_config.php', '', TestPluginConfigKeys::CONFIG_ID, false, TestPluginConfigKeys::class);
        $config = Configuration::Instance()->File(self::CONFIG_ID);;
        $pluginConfig = Configuration::Instance()->File(TestPluginConfigKeys::CONFIG_ID);

        $this->assertEquals('America/Chicago', $config->GetDefaultTimezone());
        $this->assertEquals('value1', $pluginConfig->GetKey(TestPluginConfigKeys::KEY1));
        $this->assertEquals('value2', $pluginConfig->GetKey(TestPluginConfigKeys::SERVER1_KEY));
        $this->assertEquals('value3', $pluginConfig->GetKey(TestPluginConfigKeys::SERVER2_KEY));
    }


    public function testPluginConfigValidation()
    {
        $errorLogs = $this->captureErrorLog(function () {
            Configuration::Instance()->Register(ROOT_DIR . 'tests/data/test_invalid_plugin_config.php', '', TestPluginConfigKeys::CONFIG_ID, false, TestPluginConfigKeys::class);
            $pluginConfig = Configuration::Instance()->File(TestPluginConfigKeys::CONFIG_ID);

            $this->assertEquals('default1', $pluginConfig->GetKey(TestPluginConfigKeys::KEY1));
            $this->assertEquals('default2', $pluginConfig->GetKey(TestPluginConfigKeys::SERVER1_KEY));
            $this->assertEquals('option1', $pluginConfig->GetKey(TestPluginConfigKeys::SERVER2_KEY));
        });

        $this->assertLogMessage($errorLogs, "Invalid type for 'key1'. Should be 'string', using default.", 'key1 type validation error');
        $this->assertLogMessage($errorLogs, "Invalid type for 'server1.key'. Should be 'string', using default.", 'server1.key type validation error');
        $this->assertLogMessage($errorLogs, "Invalid value 'invalid' for 'server2.key'. Should be one of the following options: [value1 => Option 1, value2 => Option 2, value3 => Option 3]", 'server2.key value validation error');
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

    // /**
    //  * Test that the actual config.dist.php loads correctly
    //  * and all its nested values are accessible
    //  */
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
