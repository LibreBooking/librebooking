<?php
define('ROOT_DIR', dirname(__FILE__) . '/../'); 
require_once(ROOT_DIR . 'Pages/SchedulePage.php');

class MinimalSchedulePage extends SchedulePage 
{
    protected function Display($template = null) 
    {
        parent::Display('Schedule/schedule-minimal.tpl');
    }
}

$page = new MinimalSchedulePage();
$page->PageLoad();