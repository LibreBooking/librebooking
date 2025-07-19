<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'Presenters/Calendar/CalendarFilters.php');
require_once(ROOT_DIR . 'Presenters/Calendar/CalendarCommon.php');

class PersonalCalendarPresenter extends CommonCalendarPresenter
{
    public function __construct(
        ICommonCalendarPage $page,
        IReservationViewRepository $repository,
        ICalendarSubscriptionService $subscriptionService,
        IUserRepository $userRepository,
        IResourceService $resourceService,
        IScheduleRepository $scheduleRepository
    ) {
        parent::__construct(
            $page,
            $repository,
            $scheduleRepository,
            $userRepository,
            $resourceService,
            $subscriptionService,
            new NullPrivacyFilter(),
            new SlotLabelFactory()
        );

        $this->AddAction(CalendarActions::ActionEnableSubscription, 'EnableSubscription');
        $this->AddAction(CalendarActions::ActionDisableSubscription, 'DisableSubscription');
    }

    public function EnableSubscription()
    {
        $userId = ServiceLocator::GetServer()->GetUserSession()->UserId;
        Log::Debug('Enabling calendar subscription for userId: %s', $userId);

        $user = $this->userRepository->LoadById($userId);
        $user->EnableSubscription();
        Configuration::Instance()->EnableSubscription();
        $this->userRepository->Update($user);
    }

    public function DisableSubscription()
    {
        $userId = ServiceLocator::GetServer()->GetUserSession()->UserId;
        Log::Debug('Disabling calendar subscription for userId: %s', $userId);

        $user = $this->userRepository->LoadById($userId);
        $user->DisableSubscription();
        $this->userRepository->Update($user);
    }

    protected function BindSubscriptionDetails($userSession, $resourceId, $scheduleId)
    {
        $details = $this->subscriptionService->ForUser($userSession->UserId, $resourceId, $scheduleId);
        $this->page->BindSubscription($details);
    }

    protected function BindEvents($userSession, $selectedScheduleId, $selectedResourceId, $selectedUserId, $selectedParticipantId)
    {
        $reservations = $this->reservationRepository->GetReservations(
            $this->GetStartDate(),
            $this->GetEndDate()->AddDays(1),
            $userSession->UserId,
            ReservationUserLevel::ALL,
            $selectedScheduleId,
            $selectedResourceId,
            true
        );

        $this->page->BindEvents(CalendarReservation::FromViewList($reservations, $userSession->Timezone, $userSession));
    }
}
