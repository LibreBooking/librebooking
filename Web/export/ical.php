<?php

define('ROOT_DIR', '../../');

require_once(ROOT_DIR . 'Pages/Export/CalendarExportPage.php');

$page = new CalendarExportPage();
// if (Configuration::Instance()->GetKey('require.login', new BooleanConverter())) {
//     $page = new SecurePageDecorator($page);
// }
$page->PageLoad();
