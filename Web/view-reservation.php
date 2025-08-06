<?php

define('ROOT_DIR', '../');

require_once(ROOT_DIR . 'Pages/Reservation/ReadOnlyReservationPage.php');

$page = new ReadOnlyReservationPage();

if (!Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_VIEW_RESERVATIONS, new BooleanConverter())) {
    $page = new SecurePageDecorator($page);
}
$page->PageLoad();
