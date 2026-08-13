<?php

define('ROOT_DIR', '../');

require_once(ROOT_DIR . 'Pages/Reservation/DetailReservationPage.php');

$page = new DetailReservationPage();

if (!Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_VIEW_RESERVATIONS, new BooleanConverter())) {
    $page = new SecurePageDecorator($page);
}

$page->PageLoad();

