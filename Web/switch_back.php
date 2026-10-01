<?php

define('ROOT_DIR', '../');

require_once(ROOT_DIR . 'Pages/SwitchBackPage.php');

$page = new SwitchBackPage();
$page->PageLoad();
