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

require_once(ROOT_DIR . 'plugins/Authentication/Saml/Saml.php');
require_once(ROOT_DIR . 'plugins/Authentication/Saml/SamlOptions.php');
require_once(ROOT_DIR . 'plugins/Authentication/Saml/SamlConfigKeys.php');
require_once(ROOT_DIR . 'plugins/Authentication/Saml/ISaml.php');
require_once(ROOT_DIR . 'plugins/Authentication/Saml/SamlUser.php');
require_once(ROOT_DIR . 'plugins/Authentication/Saml/AdSamlWrapper.php');
