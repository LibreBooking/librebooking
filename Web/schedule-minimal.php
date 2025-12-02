<?php
define('ROOT_DIR', dirname(__FILE__) . '/../'); 
require_once(ROOT_DIR . 'Pages/SchedulePage.php');
require_once(ROOT_DIR . "Pages/ScheduleMinimalPage.php");

$page = new ScheduleMinimalPage();
$page->PageLoad();