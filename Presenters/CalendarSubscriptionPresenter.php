<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class CalendarSubscriptionPresenter
{
    public function __construct(
        private readonly ICalendarSubscriptionPage $page,
        private readonly IReservationViewRepository $reservationViewRepository,
        private readonly ICalendarExportValidator $validator,
        private readonly ICalendarSubscriptionService $subscriptionService,
        private readonly IPrivacyFilter $privacyFilter
    ) {
    }

    public function PageLoad(): bool
    {
        if (!$this->validator->IsValid()) {
            return false;
        }

        $this->SetCalendarName();
        $this->page->SetReservations($this->GetReservationViews());

        return true;
    }

    private function SetCalendarName(): void
    {
        $calendarName = $this->subscriptionService->ResolveCalendarName(
            $this->page->GetScheduleId(),
            $this->page->GetResourceId(),
            $this->page->GetResourceGroupId(),
            $this->page->GetUserId()
        );

        if ($calendarName !== null && $calendarName !== '') {
            $this->page->SetCalendarName($calendarName);
        }
    }

    /**
     * @return iCalendarReservationView[]
     */
    private function GetReservationViews(): array
    {
        $userId = $this->page->GetUserId();
        $resourceGroupId = $this->page->GetResourceGroupId();
        $resourceIds = empty($resourceGroupId) ? [] : $this->subscriptionService->GetResourcesInGroup($resourceGroupId);

        $summaryKey = empty($userId) ? ConfigKeys::RESERVATION_LABELS_ICS_SUMMARY : ConfigKeys::RESERVATION_LABELS_ICS_MY_SUMMARY;
        $summaryFormat = Configuration::Instance()->GetKey($summaryKey);
        $session = ServiceLocator::GetServer()->GetUserSession();

        $views = [];
        foreach ($this->FetchReservations($resourceIds) as $reservation) {
            // A requested group restricts the feed, even when it resolved to no resources
            if (!empty($resourceGroupId) && !in_array($reservation->ResourceId, $resourceIds)) {
                continue;
            }
            $views[] = new iCalendarReservationView($reservation, $session, $this->privacyFilter, $summaryFormat);
        }

        return $views;
    }

    /**
     * Resolve the public ids from the query string to internal ids; null when not requested.
     *
     * @return array{0: ?int, 1: ?int, 2: ?int} [userId, scheduleId, resourceId]
     */
    private function ResolveFilters(): array
    {
        $userId = $this->page->GetUserId();
        $scheduleId = $this->page->GetScheduleId();
        $resourceId = $this->page->GetResourceId();

        return [
            empty($userId) ? null : $this->subscriptionService->GetUser($userId)->Id(),
            empty($scheduleId) ? null : $this->subscriptionService->GetSchedule($scheduleId)->GetId(),
            empty($resourceId) ? null : $this->subscriptionService->GetResource($resourceId)->GetId(),
        ];
    }

    /**
     * @param int[] $groupResourceIds resources of the requested group, if any
     * @return ReservationItemView[]
     */
    private function FetchReservations(array $groupResourceIds): array
    {
        [$uid, $sid, $rid] = $this->ResolveFilters();
        // keyed on the requested public id: an unknown user resolves to a null id but must still use the ALL level
        $userLevel = empty($this->page->GetUserId()) ? ReservationUserLevel::OWNER : ReservationUserLevel::ALL;

        $reservations = [];
        if (!empty($uid) || !empty($sid) || !empty($rid) || !empty($groupResourceIds)) {
            [$start, $end] = $this->GetDateRange();
            $reservations = $this->reservationViewRepository->GetReservations($start, $end, $uid, $userLevel, $sid, $rid, true);
        } elseif (!empty($this->page->GetAccessoryIds())) {
            // Accessory subscriptions are not supported yet
            throw new Exception('need to give an accessory a public id, allow subscriptions');
        }

        Log::Debug(
            'Loading calendar subscription for userId %s, scheduleId %s, resourceId %s. Found %s reservations.',
            $this->page->GetUserId(),
            $this->page->GetScheduleId(),
            $this->page->GetResourceId(),
            count($reservations)
        );

        return $reservations;
    }

    /**
     * Requested window, falling back to the configured ICS defaults.
     *
     * @return Date[] [start, end]
     */
    private function GetDateRange(): array
    {
        $config = Configuration::Instance();
        $pastDays = $config->GetKey(ConfigKeys::ICS_PAST_DAYS, new IntConverter());
        $futureDays = $config->GetKey(ConfigKeys::ICS_FUTURE_DAYS, new IntConverter());
        if ($futureDays == 0) {
            $futureDays = 30;
        }

        $daysAgo = $this->page->GetPastNumberOfDays();
        $daysAhead = $this->page->GetFutureNumberOfDays();
        $daysAgo = empty($daysAgo) ? $pastDays : intval($daysAgo);
        $daysAhead = empty($daysAhead) ? $futureDays : intval($daysAhead);

        return [Date::Now()->AddDays(-$daysAgo), Date::Now()->AddDays($daysAhead)];
    }
}
