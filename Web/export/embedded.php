<?php

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: "origin, x-requested-with, content-type"');
header('Access-Control-Allow-Methods: "PUT, GET, POST, DELETE, OPTIONS"');

define('ROOT_DIR', '../../');

require_once(ROOT_DIR . 'Pages/Export/EmbeddedCalendarPage.php');


//if (Configuration::Instance()->GetKey('require.login', new BooleanConverter()))
{
    $page = new EmbeddedCalendarPage();
    $page->PageLoad();
}
