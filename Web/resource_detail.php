    <?php
    define('ROOT_DIR', '../');

    // Debug: Show all errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Check if ResourceDetailPage.php exists
    $pageFile = ROOT_DIR . 'Pages/ResourceDetailPage.php';
    if (!file_exists($pageFile)) {
        throw new Exception("Required file not found: " . basename($pageFile));
    }

    require_once($pageFile);

    $page = new ResourceDetailPage();
    $page->PageLoad();