<?php

interface ICalendarExportValidator
{
    /**
     * @abstract
     * @return bool
     */
    public function IsValid();
}

class CalendarSubscriptionValidator implements ICalendarExportValidator
{
    /**
     * @var ICalendarSubscriptionPage
     */
    private $page;

    /**
     * @var ICalendarSubscriptionService
     */
    private $subscriptionService;

    public function __construct(ICalendarSubscriptionPage $page, ICalendarSubscriptionService $subscriptionService)
    {
        $this->page = $page;
        $this->subscriptionService = $subscriptionService;
    }

    public function IsValid()
    {
        $key = Configuration::Instance()->GetKey(ConfigKeys::ICS_SUBSCRIPTION_KEY);
        $providedKey = $this->page->GetSubscriptionKey();

        if (empty($key) || $providedKey != $key) {
            Log::Debug('Empty or invalid subscription key. Key provided: %s', $providedKey);

            return false;
        }

        $resourceId = $this->page->GetResourceId();
        $scheduleId = $this->page->GetScheduleId();
        $userId = $this->page->GetUserId();

        if (!empty($resourceId)) {
            return $this->subscriptionService->GetResource($resourceId)->GetIsCalendarSubscriptionAllowed();
        }

        if (!empty($scheduleId)) {
            return $this->subscriptionService->GetSchedule($scheduleId)->GetIsCalendarSubscriptionAllowed();
        }

        if (!empty($userId)) {
            return $this->subscriptionService->GetUser($userId)->GetIsCalendarSubscriptionAllowed();
        }

        return true;
    }
}
