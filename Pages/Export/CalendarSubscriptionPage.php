<?php

require_once(ROOT_DIR . 'Pages/Export/CalendarExportDisplay.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/CalendarSubscriptionService.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Pages/Export/ICalendarSubscriptionPage.php');
require_once(ROOT_DIR . 'Presenters/CalendarSubscriptionPresenter.php');

class CalendarSubscriptionPage extends Page implements ICalendarSubscriptionPage
{
    /**
     * @var CalendarSubscriptionPresenter
     */
    private $presenter;

    /**
     * @var array|iCalendarReservationView[]
     */
    private $reservations = [];

    public function __construct()
    {
        $authorization = new ReservationAuthorization(PluginManager::Instance()->LoadAuthorization());
        $service = new CalendarSubscriptionService(new UserRepository(), new ResourceRepository(), new ScheduleRepository());
        $icalSubscriptionValidator = new CalendarSubscriptionValidator($this, $service);
        $this->presenter = new CalendarSubscriptionPresenter(
            $this,
            new ReservationViewRepository(),
            $icalSubscriptionValidator,
            $service,
            new PrivacyFilter($authorization)
        );
        parent::__construct('', 1);
    }

    public function GetSubscriptionKey()
    {
        return $this->GetQuerystring(QueryStringKeys::SUBSCRIPTION_KEY);
    }

    public function GetUserId()
    {
        return $this->GetQuerystring(QueryStringKeys::USER_ID);
    }

    public function PageLoad(): void
    {
        if (!$this->presenter->PageLoad()) {
            http_response_code(404);
            return;
        }

        header('Content-Type: text/Calendar');
        header('Content-Disposition: inline; filename=calendar.ics');

        $display = new CalendarExportDisplay();
        echo preg_replace('~\R~u', "\r\n", $display->Render($this->reservations));
    }

    public function SetReservations($reservations)
    {
        $this->reservations = $reservations;
    }

    public function GetScheduleId()
    {
        return $this->GetQuerystring(QueryStringKeys::SCHEDULE_ID);
    }

    public function GetResourceId()
    {
        return $this->GetQuerystring(QueryStringKeys::RESOURCE_ID);
    }

    public function GetAccessoryIds()
    {
        return intval($this->GetQuerystring(QueryStringKeys::ACCESSORY_ID));
    }

    public function GetResourceGroupId()
    {
        return $this->GetQuerystring(QueryStringKeys::RESOURCE_GROUP_ID);
    }

    public function GetPastNumberOfDays()
    {
        return intval($this->GetQuerystring(QueryStringKeys::SUBSCRIPTION_DAYS_PAST));
    }

    public function GetFutureNumberOfDays()
    {
        return intval($this->GetQuerystring(QueryStringKeys::SUBSCRIPTION_DAYS_FUTURE));
    }
}
