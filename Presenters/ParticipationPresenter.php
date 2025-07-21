<?php

require_once(ROOT_DIR . 'Pages/ParticipationPage.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Notification/namespace.php');

class ParticipationPresenter
{
    /**
     * @var IParticipationPage
     */
    private $page;

    /**
     * @var IReservationRepository
     */
    private $reservationRepository;

    /**
     * @var IReservationViewRepository
     */
    private $reservationViewRepository;
    /**
     * @var IParticipationNotification
     */
    private $participationNotification;

    public function __construct(
        IParticipationPage $page,
        IReservationRepository $reservationRepository,
        IReservationViewRepository $reservationViewRepository,
        IParticipationNotification $participationNotification
    ) {
        $this->page = $page;
        $this->reservationRepository = $reservationRepository;
        $this->reservationViewRepository = $reservationViewRepository;
        $this->participationNotification = $participationNotification;
    }

    public function PageLoad()
    {
        $invitationAction = $this->page->GetInvitationAction();

        if (!empty($invitationAction)) {
            $resultString = $this->HandleInvitationAction($invitationAction);

            if ($this->page->GetResponseType() == 'json') {
                $this->page->DisplayResult($resultString);
                return;
            }

            $this->page->SetResult($resultString);
        }

        $startDate = Date::Now();
        $endDate = $startDate->AddDays(30);
        $user = ServiceLocator::GetServer()->GetUserSession();
        $userId = $user->UserId;

        $reservations = $this->reservationViewRepository->GetReservations($startDate, $endDate, $userId, ReservationUserLevel::INVITEE);

        $this->page->SetTimezone($user->Timezone);
        $this->page->BindReservations($reservations);
        $this->page->DisplayParticipation();
    }

    /**
     * @param $invitationAction
     * @return string|null
     */
    private function HandleInvitationAction($invitationAction)
    {
        $user = ServiceLocator::GetServer()->GetUserSession();

        $referenceNumber = $this->page->GetInvitationReferenceNumber();
        $userId = $this->page->GetUserId();

        Log::Debug('Invitation action %s for user %s and reference number %s', $invitationAction, $userId, $referenceNumber);

        $series = $this->reservationRepository->LoadByReferenceNumber($referenceNumber);

        if ($invitationAction == InvitationAction::Join || $invitationAction == InvitationAction::CancelInstance) {
            $rules = [new ReservationStartTimeRule(new ScheduleRepository()), new ResourceMinimumNoticeCurrentInstanceRuleUpdate($user), new ResourceMaximumNoticeCurrentInstanceRule($user)];
        } else {
            $rules = [new ReservationStartTimeRule(new ScheduleRepository()), new ResourceMinimumNoticeRuleAdd($user), new ResourceMaximumNoticeRule($user)];
        }

        /** @var IReservationValidationRule $rule */
        foreach ($rules as $rule) {
            $ruleResult = $rule->Validate($series, null);

            if (!$ruleResult->IsValid()) {
                return $ruleResult->ErrorMessage();
            }
        }

        $error = null;
        if ($invitationAction == InvitationAction::Accept) {
            $series->AcceptInvitation($userId);

            $error = $this->CheckCapacityAndReturnAnyError($series);
        }
        if ($invitationAction == InvitationAction::Decline) {
            $series->DeclineInvitation($userId);
        }
        if ($invitationAction == InvitationAction::CancelInstance) {
            $series->CancelInstanceParticipation($userId);
        }
        if ($invitationAction == InvitationAction::CancelAll) {
            $series->CancelAllParticipation($userId);
        }
        if ($invitationAction == InvitationAction::Join) {
            if (!$series->GetAllowParticipation()) {
                $error = Resources::GetInstance()->GetString('ParticipationNotAllowed');
            } else {
                $series->JoinReservation($userId);
                $error = $this->CheckCapacityAndReturnAnyError($series);
            }
        }
        if ($invitationAction == InvitationAction::JoinAll) {
            if (!$series->GetAllowParticipation()) {
                $error = Resources::GetInstance()->GetString('ParticipationNotAllowed');
            } else {
                $series->JoinReservationSeries($userId);
                $error = $this->CheckCapacityAndReturnAnyError($series);
            }
        }

        if (empty($error)) {
            $this->reservationRepository->Update($series);
            $this->participationNotification->Notify($series, $userId, $invitationAction);
        }

        return $error;
    }

    /**
     * @param ExistingReservationSeries $series
     * @return mixed|null|string
     */
    private function CheckCapacityAndReturnAnyError($series)
    {
        foreach ($series->AllResources() as $resource) {
            if (!$resource->HasMaxParticipants()) {
                continue;
            }

            /** @var Reservation $instance */
            foreach ($series->Instances() as $instance) {
                $numberOfParticipants = count($instance->Participants()) + count($instance->ParticipatingGuests());
                if ($numberOfParticipants > $resource->GetMaxParticipants()) {
                    return Resources::GetInstance()->GetString('MaxParticipantsError', [$resource->GetName(), $resource->GetMaxParticipants()]);
                }
            }
        }

        return null;
    }
}
