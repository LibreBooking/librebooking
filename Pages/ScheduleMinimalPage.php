<?php
require_once(dirname(__FILE__) . '/SchedulePage.php');

class ScheduleMinimalPage extends SchedulePage {
    
    public function PageLoad() {
        parent::PageLoad();
    }
    
    protected function Display($template = null) {
        // Get week offset from URL
        $weekOffset = isset($_GET['week']) ? intval($_GET['week']) : 0;
        
        // Use LibreBooking's Date class correctly
        $today = Date::Now();
        
        // Calculate target date based on offset (weeks * 7 days)
        $daysToAdd = $weekOffset * 7;
        $targetDate = $today->AddDays($daysToAdd);
        
        // Get the Sunday of target week
        // LibreBooking Date->Weekday() returns 0-6 (0 = Sunday)
        $dayOfWeek = $targetDate->Weekday();
        $startOfWeek = $targetDate->AddDays(-$dayOfWeek);
        
        // Build 7 days
        $weekDates = array();
        for ($i = 0; $i < 7; $i++) {
            $weekDates[] = $startOfWeek->AddDays($i);
        }
        
        // Build availability array
        $weekAvailability = array();
        foreach($weekDates as $date) {
            $weekAvailability[] = array(
                'date' => $date->Format('M j'),
                'dayName' => $date->Format('D'),
                'dateKey' => $date->Format('Y-m-d'),
                'isAvailable' => true,
                'status' => 'available'
            );
        }
        
        // Week range text
        $weekStart = $weekDates[0]->Format('M j');
        $weekEnd = $weekDates[6]->Format('M j, Y');
        
        // Assign to template
        $this->smarty->assign('WeekAvailability', $weekAvailability);
        $this->smarty->assign('ResourceId', $_GET['rid'] ?? 1);
        $this->smarty->assign('WeekOffset', $weekOffset);
        $this->smarty->assign('WeekRangeText', "$weekStart - $weekEnd");
        $this->smarty->assign('PreviousWeekOffset', $weekOffset - 1);
        $this->smarty->assign('NextWeekOffset', $weekOffset + 1);
        
        parent::Display('Schedule/schedule-minimal.tpl');
    }
}