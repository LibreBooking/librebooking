<?php

class CalendarSubscriptionUrl
{
    /**
     * @var Url
     */
    private $url;

    public const PAGE_TOKEN = '{page}';

    public function __construct($userPublicId, $schedulePublicId, $resourcePublicId)
    {
        $config = Configuration::Instance();
        $subscriptionKey = $config->GetKey(ConfigKeys::ICS_SUBSCRIPTION_KEY);

        if (empty($subscriptionKey)) {
            $this->url = new Url('#');
            return;
        }

        $scriptUrl = $config->GetScriptUrl();
        $scriptUrl .= '/export/' . self::PAGE_TOKEN;
        $url = new Url($scriptUrl);

        $url->AddQueryString(QueryStringKeys::USER_ID, $userPublicId);
        $url->AddQueryString(QueryStringKeys::SCHEDULE_ID, $schedulePublicId);
        $url->AddQueryString(QueryStringKeys::RESOURCE_ID, $resourcePublicId);
        $url->AddQueryString(QueryStringKeys::SUBSCRIPTION_KEY, $subscriptionKey);
        $this->url = $url;
    }

    public function GetWebcalUrl()
    {
        $scriptUrl = $this->url->ToString();
        return str_replace(self::PAGE_TOKEN, Pages::CALENDAR_SUBSCRIBE, $scriptUrl);
    }

    public function GetAtomUrl()
    {
        $scriptUrl = $this->url->ToString();
        return str_replace(self::PAGE_TOKEN, Pages::CALENDAR_SUBSCRIBE_ATOM, $scriptUrl);
    }

    public function __toString()
    {
        return $this->GetWebcalUrl();
    }
}
