<?php

require_once(ROOT_DIR . 'Pages/Export/SubscriptionPage.php');
require_once(ROOT_DIR . 'Pages/Export/CalendarExportDisplay.php');

class CalendarSubscriptionPage extends SubscriptionPage
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function renderFeed(): void
    {
        header('Content-Type: text/Calendar');
        header('Content-Disposition: inline; filename=calendar.ics');

        $display = new CalendarExportDisplay();
        echo $display->Render($this->reservations, $this->calendarName);
    }
}
