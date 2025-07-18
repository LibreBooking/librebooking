<?php

require_once(ROOT_DIR . 'lib/Application/Reservation/Notification/InviteeAddedEmailNotification.php');

class InviteeUpdatedEmailNotification extends InviteeAddedEmailNotification
{
    /**
     * @var IUserRepository
     */
    private $userRepository;

    /**
     * @var IAttributeRepository
     */
    private $attributeRepository;

    public function __construct(IUserRepository $userRepository, IAttributeRepository $attributeRepository)
    {
        $this->userRepository = $userRepository;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param ReservationSeries $reservationSeries
     */
    public function Notify($reservationSeries)
    {
        $owner = null;

        $instance = $reservationSeries->CurrentInstance();
        foreach ($instance->UnchangedInvitees() as $userId) {
            if ($owner == null) {
                $owner = $this->userRepository->LoadById($reservationSeries->UserId());
            }

            $invitee = $this->userRepository->LoadById($userId);

            $message = new InviteeAddedEmail($owner, $invitee, $reservationSeries, $this->attributeRepository, $this->userRepository);
            ServiceLocator::GetEmailService()->Send($message);
        }

        foreach ($instance->RemovedInvitees() as $userId) {
            if ($owner == null) {
                $owner = $this->userRepository->LoadById($reservationSeries->UserId());
            }

            $invitee = $this->userRepository->LoadById($userId);

            $message = new InviteeRemovedEmail($owner, $invitee, $reservationSeries, $this->attributeRepository, $this->userRepository);
            ServiceLocator::GetEmailService()->Send($message);
        }
    }
}
