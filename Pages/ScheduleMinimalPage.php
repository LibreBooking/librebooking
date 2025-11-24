<?php
namespace Pages;

class ScheduleMinimalPage extends SchedulePage
{
    public function PageLoad()
    {
        parent::PageLoad();
        
        // Pass ResourceId from URL
        $resourceId = $_GET['rid'] ?? null;
        if ($resourceId) {
            $this->Set('ResourceId', $resourceId);
        }
        
        // Register missing Smarty functions if needed
        if (!$this->smarty->getRegisteredPlugin('function', 'displayPastTime')) {
            $this->smarty->registerPlugin('function', 'displayPastTime', function($params) {
                return ''; // Empty for minimal view
            });
        }
    }
    
    protected function Display($template = null)
    {
        parent::Display('Schedule/schedule-minimal.tpl');
    }
}