<?php

define('ROOT_DIR', '../');

require_once(ROOT_DIR . 'Pages/ScheduleViewerViewSchedulesPage.php');

$page = new ScheduleViewerViewSchedulesPage();
$page->PageLoad();