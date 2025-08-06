<?php

/**
File in Authentication plugin package for ver 2.1.4 LibreBooking
to implement Single Sign On Capability.  Based on code from the
LibreBooking Authentication Ldap plugin as well as a SAML
Authentication plugin for Moodle 1.9+.
See http://moodle.org/mod/data/view.php?d=13&rid=2574
This plugin uses the SimpleSAMLPHP version 1.8.2 libraries.
http://simplesamlphp.org/
 */

require_once(ROOT_DIR . '/lib/Config/namespace.php');

class SamlOptions
{
    private $_options = [];

    public function __construct()
    {
        require_once(dirname(__FILE__) . '/Saml.config.php');

        Configuration::Instance()->Register(
            dirname(__FILE__) . '/Saml.config.php',
            dirname(__FILE__) . '/.env',
            SamlConfigKeys::CONFIG_ID,
            false,
            SamlConfigKeys::class
        );
    }

    public function AdSamlOptions()
    {
        $this->SetOption('ssphp_lib', $this->GetConfig(SamlConfigKeys::SIMPLESAMLPHP_LIB));
        $this->SetOption('ssphp_config', $this->GetConfig(SamlConfigKeys::SIMPLESAMLPHP_CONFIG));
        $this->SetOption('ssphp_sp', $this->GetConfig(SamlConfigKeys::SIMPLESAMLPHP_SP));
        $this->SetOption('ssphp_username', $this->GetConfig(SamlConfigKeys::USERNAME));
        $this->SetOption('ssphp_firstname', $this->GetConfig(SamlConfigKeys::FIRSTNAME));
        $this->SetOption('ssphp_lastname', $this->GetConfig(SamlConfigKeys::LASTNAME));
        $this->SetOption('ssphp_email', $this->GetConfig(SamlConfigKeys::EMAIL));
        $this->SetOption('ssphp_phone', $this->GetConfig(SamlConfigKeys::PHONE));
        $this->SetOption('ssphp_organization', $this->GetConfig(SamlConfigKeys::ORGANIZATION));
        $this->SetOption('ssphp_position', $this->GetConfig(SamlConfigKeys::POSITION));
        $this->SetOption('ssphp_groups', $this->GetConfig(SamlConfigKeys::GROUPS));

        return $this->_options;
    }

    /**
     * @return bool
     */
    public function SyncGroups()
    {
        return $this->GetConfig(SamlConfigKeys::SYNC_GROUPS, new BooleanConverter());
    }

    private function SetOption($key, $value)
    {
        if (empty($value)) {
            $value = null;
        }

        $this->_options[$key] = $value;
    }

    private function GetConfig($configDef, $converter = null)
    {
        return Configuration::Instance()->File(SamlConfigKeys::CONFIG_ID)->GetKey($configDef, $converter);
    }
}
