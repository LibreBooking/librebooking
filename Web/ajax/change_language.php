<?php

define('ROOT_DIR', '../../');

require_once(ROOT_DIR . 'Pages/Ajax/ChangeLanguagePage.php');

$page = new ChangeLanguagePage();
$page->PageLoad();
