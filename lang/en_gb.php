<?php

require_once('Language.php');
require_once('en_us.php');

class en_gb extends en_us
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _LoadDates()
    {
        $dates = parent::_LoadDates();

        // change defaults here
        $dates['general_date'] = 'd/m/Y';
        $dates['general_datetime'] = 'd/m/Y H:i:s';
        $dates['schedule_daily'] = 'l, d/m/Y';
        $dates['reservation_email'] = 'd/m/Y @ H:i (e)';
        $dates['res_popup'] = 'd/m/Y H:i';
        $dates['dashboard'] = 'l, d/m/Y H:i';
        $dates['period_time'] = 'H:i';
        $dates['general_date_js'] = "dd/mm/yy";
        $dates['short_datetime'] = 'j/n/y H:i';
        $dates['res_popup_time'] = 'D, d/n H:i';
        $dates['short_reservation_date'] = 'j/n/y H:i';
        $dates['mobile_reservation_date'] = 'j/n H:i';
        $dates['general_time_js'] = 'H:mm';
        $dates['momentjs_datetime'] = 'D/M/YY H:mm';
        $dates['calendar_time'] = 'H:mm';
        $dates['calendar_dates'] = 'd M';
        $dates['report_date'] = '%d/%m';

        $this->Dates = $dates;
        return $this->Dates;
    }
}
