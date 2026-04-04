<?php

require_once(ROOT_DIR . 'Pages/Page.php');

class CalendarExportDisplay extends Page
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $reservations iCalendarReservationView[]
     * @return string
     */
    public function Render($reservations)
    {
        $config = Configuration::Instance();

        $this->Set('bookedVersion', Configuration::VERSION);
        $this->Set('DateStamp', Date::Now());

        /**
         * ScriptUrl is used to generate iCal UID's. As a workaround to this bug
         * https://bugzilla.mozilla.org/show_bug.cgi?id=465853
         * we need to avoid using any slashes "/"
         */
        $url = $config->GetScriptUrl();
        $this->Set('UID', parse_url($url, PHP_URL_HOST));
        $this->Set('Reservations', $reservations);

        return preg_replace('~\R~u', "\r\n", $this->smarty->fetch('Export/ical.tpl'));
    }

    public function PageLoad()
    {
        // no-op
    }
}
