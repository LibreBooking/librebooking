<?php

require_once(ROOT_DIR . 'Pages/Page.php');
require_once(ROOT_DIR . 'Pages/Export/ICalendarSubscriptionPage.php');
require_once(ROOT_DIR . 'Presenters/CalendarSubscriptionPresenter.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/CalendarSubscriptionService.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');

/**
 * Abstract base class for calendar subscription feed pages (ICS, Atom, etc.).
 *
 * Provides:
 *  - Shared presenter/service constructor wiring
 *  - Common QueryString accessors used by CalendarSubscriptionValidator and the presenter
 *
 * Subclasses must implement PageLoad() to render the specific feed format.
 */
abstract class SubscriptionPage extends Page implements ICalendarSubscriptionPage
{
    protected CalendarSubscriptionPresenter $presenter;

    /** @var iCalendarReservationView[] */
    protected array $reservations = [];

    protected function __construct()
    {
        $authorization = new ReservationAuthorization(PluginManager::Instance()->LoadAuthorization());
        $service = new CalendarSubscriptionService(new UserRepository(), new ResourceRepository(), new ScheduleRepository());
        $validator = new CalendarSubscriptionValidator($this, $service);
        $this->presenter = new CalendarSubscriptionPresenter(
            $this,
            new ReservationViewRepository(),
            $validator,
            $service,
            new PrivacyFilter($authorization)
        );
        parent::__construct('', 1);
    }

    public function SetReservations($reservations): void
    {
        $this->reservations = $reservations;
    }

    public function GetSubscriptionKey()
    {
        return $this->GetQuerystring(QueryStringKeys::SUBSCRIPTION_KEY);
    }

    public function GetUserId()
    {
        return $this->GetQuerystring(QueryStringKeys::USER_ID);
    }

    public function GetScheduleId()
    {
        return $this->GetQuerystring(QueryStringKeys::SCHEDULE_ID);
    }

    public function GetResourceId()
    {
        return $this->GetQuerystring(QueryStringKeys::RESOURCE_ID);
    }

    public function GetResourceGroupId()
    {
        return $this->GetQuerystring(QueryStringKeys::RESOURCE_GROUP_ID);
    }

    public function GetAccessoryIds()
    {
        return intval($this->GetQuerystring(QueryStringKeys::ACCESSORY_ID));
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
