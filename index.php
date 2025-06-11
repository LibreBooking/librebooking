<?php

// Ensure the config file exists
if (!file_exists('config/config.php')) {
    die('Missing config/config.php. Please refer to the installation instructions.');
}

require_once('config/config.php');

// Sanitize the query string
$queryString = isset($_SERVER['QUERY_STRING']) ? htmlspecialchars($_SERVER['QUERY_STRING'], ENT_QUOTES, 'UTF-8') : '';

// Redirect to the Web directory with the sanitized query string
header("refresh:0;url=Web?" . urlencode($queryString));
exit;
