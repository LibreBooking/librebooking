<?php
define('ROOT_DIR', '../');

// Load LibreBooking framework base files
require_once(ROOT_DIR . 'Pages/Page.php');

// Now load our page
require_once(ROOT_DIR . 'Pages/ResourceListPage.php');

$page = new ResourceListPage();
$page->PageLoad();