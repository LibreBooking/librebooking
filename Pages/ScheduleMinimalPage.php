<?php
require_once(dirname(__FILE__) . '/SchedulePage.php');

class ScheduleMinimalPage extends SchedulePage {
    
    public function PageLoad() {
        parent::PageLoad();
    }
    
    protected function Display($template = null) {
        // Set variables BEFORE displaying template
        $smartyVars = $this->smarty->getTemplateVars();
        $boundDates = $smartyVars['BoundDates'];
        
        // Build availability array
        $weekAvailability = array();
        foreach($boundDates as $date) {
            $weekAvailability[] = array(
                'date' => $date->Format('M j'),
                'dayName' => $date->Format('D'),
                'dateKey' => $date->Format('Y-m-d'),
                'isAvailable' => true,
                'status' => 'available'
            );
        }
        
        // Assign directly to Smarty
        $this->smarty->assign('WeekAvailability', $weekAvailability);
        $this->smarty->assign('ResourceId', $_GET['rid'] ?? 1);
        
        // NOW display the template
        parent::Display('Schedule/schedule-minimal.tpl');
    }
}