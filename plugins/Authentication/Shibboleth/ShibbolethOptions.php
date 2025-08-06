<?php

/**
 * @file ShibbolethOptions.php
 */

require_once ROOT_DIR . '/lib/Config/namespace.php';
require_once ROOT_DIR . 'lib/Common/Converters/namespace.php';

/**
 * Plugin configuration object.
 *
 * @class ShibbolethOptions
 */
class ShibbolethOptions
{
    /**
     * Options map.
     * @var array
     */
    protected $_options;

    /**
     * Constructor.
     */
    public function __construct()
    {
        require_once dirname(__FILE__) . '/Shibboleth.config.php';
        // load the plugin configuration from file.
        Configuration::Instance()->Register(
            dirname(__FILE__) . '/Shibboleth.config.php',
            dirname(__FILE__) . '/.env',
            ShibbolethConfigKeys::CONFIG_ID,
            false,
            ShibbolethConfigKeys::class
        );
    }

    /**
     * Returns a map of plugin configurations.
     *
     * @return array A map of configuration options.
     */
    public function GetShibbolethOptions()
    {
        if (!isset($this->_options)) {
            $this->InitShibbolethOptions();
        }
        return $this->_options;
    }

    /**
     * Initializes and populates the internal options map.
     */
    protected function InitShibbolethOptions()
    {
        $this->_options = [];
        $this->SetOption("shibboleth.username", $this->GetConfig(ShibbolethConfigKeys::USERNAME));
        $this->SetOption("shibboleth.firstname", $this->GetConfig(ShibbolethConfigKeys::FIRSTNAME));
        $this->SetOption("shibboleth.lastname", $this->GetConfig(ShibbolethConfigKeys::LASTNAME));
        $this->SetOption("shibboleth.email", $this->GetConfig(ShibbolethConfigKeys::EMAIL));
        $this->SetOption("shibboleth.phone", $this->GetConfig(ShibbolethConfigKeys::PHONE));
        $this->SetOption("shibboleth.organization", $this->GetConfig(ShibbolethConfigKeys::ORGANIZATION));
    }

    /**
     * Sets a configuration option.
     *
     * @param string $key The config key.
     * @param mixed $value The config value.
     */
    private function SetOption($key, $value)
    {
        if (empty($value)) {
            $value = null;
        }

        $this->_options[$key] = $value;
    }

    /**
     * Retrieves a configuration option value by its key.
     *
     * @param array $configDef The config key.
     * @param IConvert $converter A value converter.
     * @return mixed The config value.
     */
    protected function GetConfig($configDef, $converter = null)
    {
        return Configuration::Instance()->File(ShibbolethConfigKeys::CONFIG_ID)->GetKey($configDef, $converter);
    }
}
