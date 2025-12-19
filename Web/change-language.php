<?php
define('ROOT_DIR', '../');

require_once(ROOT_DIR . 'lib/Server/namespace.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');

// Get requested language
$languageCode = $_GET['lang'] ?? 'en_us';

// Set language using LibreBooking's system
$resources = Resources::GetInstance();
if ($resources->SetLanguage($languageCode)) {
    // Update cookie properly using LibreBooking's Cookie class
    ServiceLocator::GetServer()->SetCookie(
        new Cookie(CookieKeys::LANGUAGE, $languageCode, secure: false)
    );
}

// Redirect back to previous page
$referer = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';
header('Location: ' . $referer);
exit;