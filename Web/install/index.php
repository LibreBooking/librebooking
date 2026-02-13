<?php

define('ROOT_DIR', '../../');
$smartyTemplateCacheDir = ROOT_DIR . 'tpl_c';

/**
 * Checking directory permission
 */
if (SmartyPermissionsAreOk($smartyTemplateCacheDir)) {
    require_once(ROOT_DIR . 'Pages/Install/InstallPage.php');
    $page = new InstallPage();
    $page->PageLoad();
} else {
    echo "The web server (such as _www on Mac or apache on Linux) must have write access to $smartyTemplateCacheDir. ";
    echo '<br/>The permissions are currently set as ' . substr(sprintf('%o', fileperms($smartyTemplateCacheDir)), -4);
    echo "<br/>You can either change $smartyTemplateCacheDir to group for example: _www/apache accordingly";
    echo "<br/>Or change $smartyTemplateCacheDir to have permission 777 which is only for testing due to security vulnerability.";
}

/**
 * Determine the permission of given directory
 * @param string $smartyTemplateCacheDir location of tpl_c directory
 * @return bool|string bool when writable, and string otherwise
 */
function SmartyPermissionsAreOk($smartyTemplateCacheDir)
{
    if (!is_writable($smartyTemplateCacheDir)) {
        // Attempt to change permission of directory to 0770 - 1st section 0 for all storage type.
        return chmod($smartyTemplateCacheDir, 0770); // often the attempt will fail
    }

    return true;
}
