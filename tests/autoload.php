<?php

if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', dirname(__FILE__) . '/../');
}

// require_once(ROOT_DIR . 'tests/UnitTestLib.php');
require_once(ROOT_DIR . 'tests/TestHelper.php');
require_once(ROOT_DIR . 'tests/TestBase.php');

require_once(ROOT_DIR . 'tests/fakes/namespace.php');
require_once(ROOT_DIR . 'lib/Common/Helpers/namespace.php');

if (Configuration::Instance()->GetDefaultTimezone() != "America/Chicago") {
  echo "\nERROR: Default timezone in 'config/config.php' must be 'America/Chicago in order to run unit tests.\n";
  exit(1);
}
