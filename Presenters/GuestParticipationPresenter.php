<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Notification/namespace.php');

class GuestParticipationPresenter
{
    /**
     * @var IReservationRepository
     */
    private $reservationRepository;
    /**
     * @var IGuestParticipationPage
     */
    private $page;
    /**
     * @var IUserRepository
     */
    private $userRepository;
    /**
     * @var IParticipationNotification
     */
    private $participationNotification;

    public function __construct(
        IGuestParticipationPage $page,
        IReservationRepository $reservationRepository,
        IUserRepository $userRepository,
        IParticipationNotification $participationNotification
    ) {
        $this->page = $page;
        $this->reservationRepository = $reservationRepository;
        $this->userRepository = $userRepository;
        $this->participationNotification = $participationNotification;
    }

    public function PageLoad()
    {
        if ($this->MissingRequired()) {
            $this->page->SetMissingInformation();
        } else {
            $result = $this->HandleInvitationAction($this->page->GetInvitationAction());
            $result->Populate($this->page);
        }

        $this->page->DisplayParticipation();
    }

    /**
     * @param $invitationAction
     * @return InvitationResult
     */
    private function HandleInvitationAction($invitationAction)
    {
        $email = $this->page->GetEmail();
        $user = $this->userRepository->LoadByUsername($email);
        $referenceNumber = $this->page->GetInvitationReferenceNumber();

        Log::Debug('Invitation action %s for user %s and reference number %s', $invitationAction, $email, $referenceNumber);

        $series = $this->reservationRepository->LoadByReferenceNumber($referenceNumber);
        $result = InvitationResult::None();

        if ($invitationAction == InvitationAction::Accept) {
            if ($user->IsRegistered()) {
                // if email is already registered, then add user as participant and delete guest
                $series->AcceptGuestAsUserInvitation($email, $user);
                $result = InvitationResult::AcceptedAsUser($series, $user);
            } else {
                // if not registered
                $series->AcceptGuestInvitation($email);
                $result = InvitationResult::AcceptedAsGuest($series);
            }

            foreach ($series->AllResources() as $resource) {
                if (!$resource->HasMaxParticipants()) {
                    continue;
                }

                /** @var Reservation $instance */
                foreach ($series->Instances() as $instance) {
                    $numberOfParticipants = count($instance->Participants()) + count($instance->ParticipatingGuests());

                    if ($numberOfParticipants > $resource->GetMaxParticipants()) {
                        $result = InvitationResult::MaxCapacity($resource->GetName(), $resource->GetMaxParticipants());
                        return $result;
                    }
                }
            }
        }
        if ($invitationAction == InvitationAction::Decline) {
            $series->DeclineGuestInvitation($email);
            $result = InvitationResult::Declined($series);
        }

        $this->reservationRepository->Update($series);
        $this->participationNotification->NotifyGuest($series, $email, $invitationAction);

        return $result;
    }

    private function MissingRequired()
    {
        $referenceNumber = $this->page->GetInvitationReferenceNumber();
        $email = $this->page->GetEmail();
        $invitationAction = $this->page->GetInvitationAction();

        return empty($referenceNumber) || empty($email) || empty($invitationAction);
    }
}

class InvitationResult
{
    private $acceptedAsGuest = false;
    private $acceptedAsUser = false;
    private $declined = false;
    private $reachedMaxCapacity = false;
    private $error = false;

    /**
     * @var ExistingReservationSeries|null
     */
    private $series;

    /**
     * @var User|null
     */
    private $user;

    /**
     * @var string|null
     */
    private $maxCapacityMessage;

    public function Populate(IGuestParticipationPage $page)
    {
        if ($this->acceptedAsUser) {
            $page->SetAccepted();
        }
        if ($this->acceptedAsGuest) {
            $page->SetAccepted();
            $page->SetIsGuest();
        }

        if ($this->reachedMaxCapacity) {
            $page->SetMaxCapacityReached(true, $this->maxCapacityMessage);
        }

        if ($this->declined) {
            $page->SetDeclined();
        }

        if ($this->error) {
            $page->SetMissingInformation();
        }
    }

    /**
     * @param ExistingReservationSeries $series
     * @param User $user
     * @return InvitationResult
     */
    public static function AcceptedAsUser(ExistingReservationSeries $series, User $user)
    {
        $result = new InvitationResult();
        $result->acceptedAsUser = true;
        $result->series = $series;
        $result->user = $user;

        return $result;
    }

    /**
     * @param ExistingReservationSeries $series
     * @return InvitationResult
     */
    public static function AcceptedAsGuest(ExistingReservationSeries $series)
    {
        $result = new InvitationResult();
        $result->acceptedAsGuest = true;
        $result->series = $series;

        return $result;
    }


    /**
     * @param string $resourceName
     * @param int $maxParticipants
     * @return InvitationResult
     */
    public static function MaxCapacity($resourceName, $maxParticipants)
    {
        $result = new InvitationResult();
        $result->reachedMaxCapacity = true;
        $result->maxCapacityMessage = Resources::GetInstance()->GetString('MaxParticipantsError', [$resourceName, $maxParticipants]);

        return $result;
    }

    /**
     * @param ExistingReservationSeries $series
     * @return InvitationResult
     */
    public static function Declined(ExistingReservationSeries $series)
    {
        $result = new InvitationResult();
        $result->declined = true;
        $result->series = $series;

        return $result;
    }

    /**
     * @return InvitationResult
     */
    public static function None()
    {
        $result = new InvitationResult();
        $result->error = true;

        return $result;
    }
}
