<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'Presenters/Calendar/CalendarPresenter.php');

class CalendarPage extends CommonCalendarPage implements ICommonCalendarPage
{
    protected $presenter;

    public function __construct()
    {
        parent::__construct('ResourceCalendar');
        $resourceRepository = new ResourceRepository();
        $scheduleRepository = new ScheduleRepository();
        $userRepository = new UserRepository();
        $resourceService = new ResourceService($resourceRepository, PluginManager::Instance()->LoadPermission(), new AttributeService(new AttributeRepository()), $userRepository, new AccessoryRepository());
        $subscriptionService = new CalendarSubscriptionService($userRepository, $resourceRepository, $scheduleRepository);
        $privacyFilter = new PrivacyFilter(new ReservationAuthorization(PluginManager::Instance()->LoadAuthorization()));

        $this->presenter = new CalendarPresenter(
            $this,
            new ReservationViewRepository(),
            $scheduleRepository,
            new UserRepository(),
            $resourceService,
            $subscriptionService,
            $privacyFilter,
            new SlotLabelFactory()
        );
    }

    public function ProcessPageLoad()
    {
        URIScriptValidator::validateOrRedirect($_SERVER['REQUEST_URI'], '/calendar.php');
        ParamsValidator::validateOrRedirect(RouteParamsKeys::VIEW_SCHEDULE, $_SERVER['REQUEST_URI'], '/calendar.php', true);

        $user = ServiceLocator::GetServer()->GetUserSession();
        $this->presenter->PageLoad($user);

        $this->Set('HeaderLabels', Resources::GetInstance()->GetDays('full'));
        $this->Set('Today', Date::Now()->ToTimezone($user->Timezone));
        $this->Set('TimeFormat', Resources::GetInstance()->GetDateFormat('calendar_time'));
        $this->Set('DateFormat', Resources::GetInstance()->GetDateFormat('calendar_dates'));
        $this->Set('CreateReservationPage', Pages::RESERVATION);
        $this->Set('CanViewUsers', !Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, new BooleanConverter()));
        $this->Set('AllowParticipation', !Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_PREVENT_PARTICIPATION, new BooleanConverter()));

        $this->DisplayPage();
    }

    protected function DisplayPage()
    {
        $this->Display('Calendar/calendar.tpl');
    }

    public function RenderSubscriptionDetails()
    {
        $this->Display('Calendar/calendar.subscription.tpl');
    }

    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    public function ProcessDataRequest($dataRequest)
    {
        $this->presenter->ProcessDataRequest($dataRequest);
    }
}

class CalendarUrl
{
    private $url;

    private function __construct($year, $month, $day, $type)
    {
        // TODO: figure out how to get these values without coupling to the query string
        $resourceId = ServiceLocator::GetServer()->GetQuerystring(QueryStringKeys::RESOURCE_ID);
        $scheduleId = ServiceLocator::GetServer()->GetQuerystring(QueryStringKeys::SCHEDULE_ID);

        $format = Pages::CALENDAR . '?'
            . QueryStringKeys::DAY . '=%d&'
            . QueryStringKeys::MONTH . '=%d&'
            . QueryStringKeys::YEAR . '=%d&'
            . QueryStringKeys::CALENDAR_TYPE . '=%s&'
            . QueryStringKeys::RESOURCE_ID . '=%s&'
            . QueryStringKeys::SCHEDULE_ID . '=%s';

        $this->url = sprintf($format, $day, $month, $year, $type, $resourceId, $scheduleId);
    }

    /**
     * @static
     * @param $date Date
     * @param $type string
     * @return PersonalCalendarUrl
     */
    public static function Create($date, $type)
    {
        return new CalendarUrl($date->Year(), $date->Month(), $date->Day(), $type);
    }

    public function __toString()
    {
        return $this->url;
    }
}
