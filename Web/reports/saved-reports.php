<?php

define('ROOT_DIR', '../../');
require_once(ROOT_DIR . 'Pages/Reports/SavedReportsPage.php');

$roles = [RoleLevel::APPLICATION_ADMIN, RoleLevel::GROUP_ADMIN, RoleLevel::RESOURCE_ADMIN, RoleLevel::SCHEDULE_ADMIN];
if (Configuration::Instance()->GetKey(ConfigKeys::REPORTS_ALLOW_ALL_USERS, new BooleanConverter())) {
    $roles = [];
}

$page = new RoleRestrictedPageDecorator(new SavedReportsPage(), $roles);
$page->PageLoad();
