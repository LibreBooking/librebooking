<?php

define('ROOT_DIR', '../../');

require_once(ROOT_DIR . 'Pages/Export/CalendarSubscriptionPage.php');

$page = new CalendarSubscriptionPage();
// if (Configuration::Instance()->GetKey('require.login', new BooleanConverter())) 
//     {
//     $page = new SecurePageDecorator($page);
// }
$page->PageLoad();
