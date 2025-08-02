<?php

require_once(ROOT_DIR . 'Presenters/CalendarSubscriptionPresenter.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Pages/Export/CalendarSubscriptionPage.php');

use \FeedWriter\ATOM;

class AtomSubscriptionPage extends Page implements ICalendarSubscriptionPage
{
    /**
     * @var CalendarSubscriptionPresenter
     */
    private $presenter;

    /**
     * @var iCalendarReservationView[]
     */
    private $reservations = [];

    public function __construct()
    {
        $authorization = new ReservationAuthorization(PluginManager::Instance()->LoadAuthorization());
        $service = new CalendarSubscriptionService(new UserRepository(), new ResourceRepository(), new ScheduleRepository());
        $subscriptionValidator = new CalendarSubscriptionValidator($this, $service);
        $this->presenter = new CalendarSubscriptionPresenter(
            $this,
            new ReservationViewRepository(),
            $subscriptionValidator,
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

    public function PageLoad()
    {
        ob_clean();
        $this->presenter->PageLoad();

        $config = Configuration::Instance();

        $feed = new ATOM();
        $title = $config->GetKey(ConfigKeys::APP_TITLE);
        $feed->setTitle(Resources::GetInstance()->GetString('AtomFeedTitle', [$title]));
        $url = $config->GetScriptUrl();
        $feed->setLink($url);

        $lastUpdated = Date::Min();
        $feed->setChannelElement('author', ['name' => $title]);

        foreach ($this->reservations as $reservation) {
            $item = $feed->createNewItem();
            $item->setTitle($reservation->Summary);
            $item->setLink($reservation->ReservationUrl);
            $item->setDate($reservation->DateCreated->Timestamp());
            $item->setDescription($this->FormatReservationDescription($reservation, ServiceLocator::GetServer()->GetUserSession()));

            $feed->addItem($item);

            if ($reservation->DateCreated->GreaterThan($lastUpdated)) {
                $lastUpdated = $reservation->DateCreated;
            }
            if ($reservation->LastModified != null && $reservation->LastModified->GreaterThan($lastUpdated)) {
                $lastUpdated = $reservation->LastModified;
            }
        }

        $feed->setChannelElement('updated', $lastUpdated->Format(DATE_ATOM));
        $feed->printFeed();
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
        // no op
        return 0;
    }

    public function GetResourceGroupId()
    {
        return $this->GetQuerystring(QueryStringKeys::RESOURCE_GROUP_ID);
    }

    public function FormatReservationDescription(iCalendarReservationView $reservation, UserSession $user)
    {
        $factory = new SlotLabelFactory($user);
        return $factory->Format($reservation->ReservationItemView, Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_LABELS_RSS_DESCRIPTION));
    }

    public function GetPastNumberOfDays()
    {
        return $this->GetQuerystring(QueryStringKeys::SUBSCRIPTION_DAYS_PAST);
    }

    public function GetFutureNumberOfDays()
    {
        return $this->GetQuerystring(QueryStringKeys::SUBSCRIPTION_DAYS_FUTURE);
    }
}
