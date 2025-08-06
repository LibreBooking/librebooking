<?php

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationParticipationActivityEmail.php');

interface IParticipationNotification
{
    /**
     * @param ExistingReservationSeries $series
     * @param int $participantId
     * @param InvitationAction|string $invitationAction
     */
    public function Notify(ExistingReservationSeries $series, $participantId, $invitationAction);

    /**
     * @param ExistingReservationSeries $series
     * @param string $guestEmail
     * @param InvitationAction|string $invitationAction
     */
    public function NotifyGuest(ExistingReservationSeries $series, $guestEmail, $invitationAction);
}

class ParticipationNotification implements IParticipationNotification
{
    /**
     * @var IUserRepository
     */
    private $userRepository;

    /**
     * @var bool
     */
    private $disabled;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->disabled = Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_PREVENT_PARTICIPATION, new BooleanConverter());
    }

    public function Notify(ExistingReservationSeries $series, $participantId, $invitationAction)
    {
        if ($this->disabled) {
            return;
        }

        $owner = $this->userRepository->LoadById($series->UserId());
        if ($owner->WantsEventEmail(new ParticipationChangedEvent())) {
            $participant = $this->userRepository->LoadById($participantId);
            ServiceLocator::GetEmailService()->Send(new ReservationParticipationActivityEmail($series, $invitationAction, $owner, $participant->FullName()));
        }
    }

    public function NotifyGuest(ExistingReservationSeries $series, $guestEmail, $invitationAction)
    {
        if ($this->disabled) {
            return;
        }

        $owner = $this->userRepository->LoadById($series->UserId());
        if ($owner->WantsEventEmail(new ParticipationChangedEvent())) {
            ServiceLocator::GetEmailService()->Send(new ReservationParticipationActivityEmail($series, $invitationAction, $owner, $guestEmail));
        }
    }
}
