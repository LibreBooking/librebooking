<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'Presenters/Calendar/PersonalCalendarPresenter.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/CalendarSubscriptionService.php');

class PersonalCalendarPage extends CommonCalendarPage implements ICommonCalendarPage
{
    /**
     * @var PersonalCalendarPresenter
     */
    private $presenter;

    public function __construct()
    {
        parent::__construct('MyCalendar', 0);

        $userRepository = new UserRepository();
        $subscriptionService = new CalendarSubscriptionService($userRepository, new ResourceRepository(), new ScheduleRepository());
        $resourceRepository = new ResourceRepository();
        $resourceService = new ResourceService(
            $resourceRepository,
            PluginManager::Instance()->LoadPermission(),
            new AttributeService(new AttributeRepository()),
            $userRepository,
            new AccessoryRepository()
        );

        $this->presenter = new PersonalCalendarPresenter(
            $this,
            new ReservationViewRepository(),
            $subscriptionService,
            $userRepository,
            $resourceService,
            new ScheduleRepository()
        );
    }

    public function ProcessPageLoad()
    {
        $user = ServiceLocator::GetServer()->GetUserSession();
        $this->presenter->PageLoad($user);

        $this->Set('HeaderLabels', Resources::GetInstance()->GetDays('full'));
        $this->Set('Today', Date::Now()->ToTimezone($user->Timezone));
        $this->Set('TimeFormat', Resources::GetInstance()->GetDateFormat('calendar_time'));
        $this->Set('DateFormat', Resources::GetInstance()->GetDateFormat('calendar_dates'));
        $this->Set('CreateReservationPage', Pages::RESERVATION);

        $this->Display('Calendar/mycalendar.tpl');
    }

    public function RenderSubscriptionDetails()
    {
        $this->Display('Calendar/mycalendar.subscription.tpl');
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

class PersonalCalendarUrl
{
    private $url;

    private function __construct($year, $month, $day, $type)
    {
        $resourceId = ServiceLocator::GetServer()->GetQuerystring(QueryStringKeys::RESOURCE_ID);
        $scheduleId = ServiceLocator::GetServer()->GetQuerystring(QueryStringKeys::SCHEDULE_ID);

        $format = Pages::MY_CALENDAR . '?'
                . QueryStringKeys::DAY . '=%d&'
                . QueryStringKeys::MONTH . '=%d&'
                . QueryStringKeys::YEAR
                . '=%d&'
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
        return new PersonalCalendarUrl($date->Year(), $date->Month(), $date->Day(), $type);
    }

    public function __toString()
    {
        return $this->url;
    }
}
