<?php
define('ROOT_DIR', '../');

// Debug: Show all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if ResourceListPage.php exists
$pageFile = ROOT_DIR . 'Pages/ResourceListPage.php';
if (!file_exists($pageFile)) {
    die("ERROR: No se encuentra " . $pageFile);
}

require_once($pageFile);

$page = new ResourceListPage();
$page->PageLoad();