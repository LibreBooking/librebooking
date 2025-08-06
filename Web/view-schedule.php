<?php

define('ROOT_DIR', '../');

require_once(ROOT_DIR . 'Pages/ViewSchedulePage.php');

$page = new ViewSchedulePage();
$allowAnonymousSchedule = Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_VIEW_SCHEDULES, new BooleanConverter());
$allowGuestBookings = Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_ALLOW_GUEST_RESERVATIONS, new BooleanConverter());
if (!$allowAnonymousSchedule && !$allowGuestBookings) {
    $page = new SecurePageDecorator($page);
}

$page->PageLoad();
