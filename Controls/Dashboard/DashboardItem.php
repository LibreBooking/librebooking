<?php

require_once(ROOT_DIR . 'Controls/Control.php');

abstract class DashboardItem extends Control
{
    public function __construct(\LibreBooking\Common\Templating\TemplateRenderer|SmartyPage $smarty)
    {
        parent::__construct($smarty);
    }

    protected function Display($templateName)
    {
        parent::Display("Dashboard/$templateName");
    }

    protected function Assign($name, $value)
    {
        $this->Set($name, $value);
    }
}
