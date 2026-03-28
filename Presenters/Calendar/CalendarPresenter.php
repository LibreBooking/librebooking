<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Config/namespace.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'Presenters/Calendar/CalendarFilters.php');
require_once(ROOT_DIR . 'Presenters/Calendar/CalendarCommon.php');

class CalendarPresenter extends CommonCalendarPresenter
{
    private string $reservationPage;

    public function __construct(
        ICommonCalendarPage $page,
        IReservationViewRepository $reservationRepository,
        IScheduleRepository $scheduleRepository,
        IUserRepository $userRepository,
        IResourceService $resourceService,
        ICalendarSubscriptionService $subscriptionService,
        IPrivacyFilter $privacyFilter,
        SlotLabelFactory $factory,
        string $reservationPage = Pages::RESERVATION
    ) {
        parent::__construct(
            page: $page,
            reservationRepository: $reservationRepository,
            scheduleRepository: $scheduleRepository,
            userRepository: $userRepository,
            resourceService: $resourceService,
            subscriptionService: $subscriptionService,
            privacyFilter: $privacyFilter,
            factory: $factory,
        );
        $this->reservationPage = $reservationPage;
    }

    /**
     * @param UserSession $userSession
     * @param int $selectedScheduleId
     * @param int $selectedResourceId
     */
    protected function BindEvents($userSession, $selectedScheduleId, $selectedResourceId, $selectedUserId, $selectedParticipantId)
    {
        $resources = $this->GetAllResources($userSession);

        $reservations = $this->reservationRepository->GetReservations(
            $this->GetStartDate(),
            $this->GetEndDate()->AddDays(1),
            $selectedUserId,
            null,
            $selectedScheduleId,
            $selectedResourceId,
            false,
            $selectedParticipantId
        );

        $dateRange = new DateRange($this->GetStartDate(), $this->GetEndDate()->AddDays(1));
        $blackouts = $this->reservationRepository->GetBlackoutsWithin(
            $dateRange,
            $selectedScheduleId,
            $selectedResourceId
        );

        $availableSlots = [];
        if (!empty($selectedResourceId) || !empty($selectedScheduleId)) {
            if (is_array($selectedResourceId) && !empty($selectedResourceId)) {
                $resources = [];
                foreach ($selectedResourceId as $id) {
                    $resources[] = $this->resourceService->GetResource($id);
                }
                $selectedScheduleId = $resources[0]->GetScheduleId();
            } elseif (!empty($selectedResourceId)) {
                $resources = [$this->resourceService->GetResource($selectedResourceId)];
                $selectedScheduleId = $resources[0]->GetScheduleId();
            }

            $scheduleLayout = $this->scheduleRepository->GetLayout($selectedScheduleId, new ScheduleLayoutFactory());
            if ($scheduleLayout->UsesCustomLayout()) {
                foreach ($dateRange->Dates() as $date) {
                    $slots = $scheduleLayout->GetLayout($date, true);
                    foreach ($slots as $slot) {
                        if (!$slot->EndDate()->LessThanOrEqual(Date::Now()) && !$this->OverlapsAnyReservation($slot, $reservations) && !$this->OverlapsAnyBlackout($slot, $blackouts)) {
                            $availableSlots[] = new ReservableCalendarSlot($slot, $selectedResourceId, $selectedScheduleId);
                        }
                    }
                }
            }
        }

        $this->page->BindEvents(CalendarReservation::FromScheduleReservationList(
            $reservations,
            $blackouts,
            $availableSlots,
            $resources,
            $userSession,
            $this->privacyFilter,
            $this->slotLabelFactory,
            reservationPage: $this->reservationPage
        ));
    }

    protected function BindSubscriptionDetails($userSession, $selectedResourceId, $selectedScheduleId)
    {
        if (!empty($selectedResourceId)) {
            $subscriptionDetails = $this->subscriptionService->ForResource($selectedResourceId);
        } else {
            if (!empty($selectedScheduleId)) {
                $subscriptionDetails = $this->subscriptionService->ForSchedule($selectedScheduleId);
            } else {
                $subscriptionDetails = new CalendarSubscriptionDetails(false);
            }
        }
        $this->page->BindSubscription($subscriptionDetails);
    }

    /**
     * @param SchedulePeriod $slot
     * @param ReservationItemView[] $reservations
     * @return bool
     */
    private function OverlapsAnyReservation($slot, $reservations)
    {
        foreach ($reservations as $reservation) {
            if ($reservation->Date->Overlaps(new DateRange($slot->BeginDate(), $slot->EndDate()))) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param SchedulePeriod $slot
     * @param BlackoutItemView[] $blackouts
     * @return bool
     */
    private function OverlapsAnyBlackout($slot, $blackouts)
    {
        foreach ($blackouts as $blackout) {
            if ($blackout->Date->Overlaps(new DateRange($slot->BeginDate(), $slot->EndDate()))) {
                return true;
            }
        }
        return false;
    }
}
