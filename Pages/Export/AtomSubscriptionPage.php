<?php

require_once(ROOT_DIR . 'Pages/Export/SubscriptionPage.php');

use FeedWriter\ATOM;

class AtomSubscriptionPage extends SubscriptionPage
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function renderFeed(): void
    {
        ob_clean();

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

    public function GetAccessoryIds()
    {
        // Atom feed does not support accessory filtering
        return 0;
    }

    public function FormatReservationDescription(iCalendarReservationView $reservation, UserSession $user)
    {
        $factory = new SlotLabelFactory($user);
        return $factory->Format($reservation->ReservationItemView, Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_LABELS_RSS_DESCRIPTION));
    }
}
