<?php

@define('PEAR_ROOT', ROOT_DIR . 'lib/external/pear/');

set_include_path(PEAR_ROOT . PATH_SEPARATOR . get_include_path());

require_once(ROOT_DIR . 'plugins/Authentication/Ldap/Ldap.php');
require_once(ROOT_DIR . 'plugins/Authentication/Ldap/LdapOptions.php');
require_once(ROOT_DIR . 'plugins/Authentication/Ldap/LdapConfigKeys.php');
require_once(ROOT_DIR . 'plugins/Authentication/Ldap/LdapUser.php');
require_once(ROOT_DIR . 'plugins/Authentication/Ldap/Ldap2Wrapper.php');
require_once(PEAR_ROOT . 'PEAR.php');
