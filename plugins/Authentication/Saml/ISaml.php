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

interface ISaml
{
    /**
     * @return bool If connection was successful
     */
    public function Connect();

    /**
     * @return bool If authentication was successful
     */
    public function Authenticate();

    /**
     * @return SamlUser The details for the user
     */
    public function GetSamlUser();

    /**
     * Logs the user out of the identity provider
     * @return void
     */
    public function Logout();

    /**
     * Reverts from the SimpleSAMLphp session to the application session
     * @return void
     */
    public function Cleanup();
}
