<?php

// The classes loaded below depend on Composer packages and on the PSR-4
// classes in src/, so the autoloader has to be registered first.
if (file_exists(filename: ROOT_DIR . 'vendor/autoload.php')) {
    require_once ROOT_DIR . 'vendor/autoload.php';
}

require_once(ROOT_DIR . 'lib/Common/Helpers/namespace.php');
require_once(ROOT_DIR . 'lib/Common/LoginTime.php');
require_once(ROOT_DIR . 'lib/Common/SmartyPage.php');
require_once(ROOT_DIR . 'lib/Common/Templating/SmartyRenderer.php');
require_once(ROOT_DIR . 'lib/Common/Templating/LibreBookingExtension.php');
require_once(ROOT_DIR . 'lib/Common/Templating/TwigRenderer.php');
require_once(ROOT_DIR . 'lib/Common/Resources.php');
require_once(ROOT_DIR . 'lib/Common/ServiceLocator.php');
require_once(ROOT_DIR . 'lib/Common/Date.php');
require_once(ROOT_DIR . 'lib/Common/Time.php');
require_once(ROOT_DIR . 'lib/Common/TimeInterval.php');
require_once(ROOT_DIR . 'lib/Common/DateRange.php');
require_once(ROOT_DIR . 'lib/Common/GlobalKeys.php');
require_once(ROOT_DIR . 'lib/Common/PluginManager.php');
require_once(ROOT_DIR . 'lib/Common/ErrorMessages.php');
require_once(ROOT_DIR . 'lib/Common/Logging/Log.php');
require_once(ROOT_DIR . 'lib/Common/Logging/ExceptionHandler.php');
require_once(ROOT_DIR . 'lib/Common/ContrastingColor.php');
require_once(ROOT_DIR . 'lib/Common/Security/RichTextHtmlSanitizer.php');
require_once(ROOT_DIR . 'lib/Common/RedirectUrlSanitizer.php');
require_once(ROOT_DIR . 'lib/Common/VersionDisplayResolver.php');
require_once(ROOT_DIR . 'lib/Common/ScriptUrlSuggestion.php');
