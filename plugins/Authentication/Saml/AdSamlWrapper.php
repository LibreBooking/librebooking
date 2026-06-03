<?php

/**
 * File in Authentication plugin package for ver 2.1.4 LibreBooking
 * to implement Single Sign On Capability.  Based on code from the
 * LibreBooking Authentication Ldap plugin as well as a SAML
 * Authentication plugin for Moodle 1.9+.
 * See http://moodle.org/mod/data/view.php?d=13&rid=2574
 * This plugin uses the SimpleSAMLPHP version 1.8.2 libraries.
 * http://simplesamlphp.org/
 */

require_once(ROOT_DIR . 'plugins/Authentication/Saml/adSAML.php');
require_once(ROOT_DIR . 'plugins/Authentication/Saml/SamlUser.php');

class AdSamlWrapper implements ISaml
{
    /**
     * @var SamlOptions
     */
    private $options;

    /**
     * @var adSAML|null
     */
    private $saml;

    /**
     * @param SamlOptions $samlOptions
     */
    public function __construct($samlOptions)
    {
        $this->options = $samlOptions;
    }

    public function Connect()
    {
        $options = $this->options->AdSamlOptions();

        $this->saml = new adSaml($options);
    }

    public function Authenticate()
    {
        return $this->saml->authenticate();
    }

    public function GetSamlUser()
    {
        return new SamlUser($this->saml->getAttributes(), $this->options);
    }

    public function Logout()
    {
        $this->Connect();
        $this->saml->Logout();
    }

    public function Cleanup()
    {
        $this->Connect();
        $this->saml->Cleanup();
    }
}
