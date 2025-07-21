<?php

define('ROOT_DIR', '../');

require_once(ROOT_DIR . 'Pages/ResourceViewerViewResourcesPage.php');

$page = new ResourceViewerViewResourcesPage();
$page->PageLoad();