<?php

require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/ReservationEvents.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationCreatedEmail.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationUpdatedEmail.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationDeletedEmail.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationApprovedEmail.php');

abstract class OwnerEmailNotification implements IReservationNotification
{
    /**
     * @var IUserRepository
     */
    protected $_userRepo;

    /**
     * @var IAttributeRepository
     */
    protected $_attributeRepo;

    /**
     * @param IUserRepository $userRepo
     * @param IAttributeRepository $attributeRepo
     */
    public function __construct(IUserRepository $userRepo, IAttributeRepository $attributeRepo)
    {
        $this->_userRepo = $userRepo;
        $this->_attributeRepo = $attributeRepo;
    }

    /**
     * @param ReservationSeries $reservationSeries
     * @return void
     */
    public function Notify($reservationSeries)
    {
        $owner = $this->_userRepo->LoadById(userId: $reservationSeries->UserId());
        if ($this->ShouldSend($owner)) {
            $message = $this->GetMessage(
                owner: $owner,
                reservation: $reservationSeries,
                attributeRepository: $this->_attributeRepo,
                userRepository: $this->_userRepo
            );
            ServiceLocator::GetEmailService()->Send($message);
        } else {
            Log::Debug(
                'Owner does not want these types of email notifications. Email=%s, ReferenceNumber=%s',
                $owner->EmailAddress(),
                $reservationSeries->CurrentInstance()->ReferenceNumber()
            );
        }
    }

    /**
     * @abstract
     * @param $owner User
     * @return bool
     */
    abstract protected function ShouldSend(User $owner);

    /**
     * @param User $owner
     * @param ReservationSeries|ExistingReservationSeries $reservation
     * @param IAttributeRepository $attributeRepository
     * @param IUserRepository $userRepository
     * @return EmailMessage
     */
    abstract protected function GetMessage(User $owner, $reservation, IAttributeRepository $attributeRepository, IUserRepository $userRepository);
}

class OwnerEmailCreatedNotification extends OwnerEmailNotification
{
    protected function ShouldSend(User $owner)
    {
        return $owner->WantsEventEmail(new ReservationCreatedEvent());
    }

    protected function GetMessage(User $owner, $reservation, IAttributeRepository $attributeRepository, IUserRepository $userRepository)
    {
        return new ReservationCreatedEmail($owner, $reservation, null, $attributeRepository, $userRepository);
    }
}

class OwnerEmailUpdatedNotification extends OwnerEmailNotification
{
    protected function ShouldSend(User $owner)
    {
        return $owner->WantsEventEmail(new ReservationUpdatedEvent());
    }

    protected function GetMessage(User $owner, $reservation, IAttributeRepository $attributeRepository, IUserRepository $userRepository)
    {
        return new ReservationUpdatedEmail($owner, $reservation, null, $attributeRepository, $userRepository);
    }
}

class OwnerEmailApprovedNotification extends OwnerEmailNotification
{
    /**
     * @param $owner User
     * @return bool
     */
    protected function ShouldSend(User $owner)
    {
        return $owner->WantsEventEmail(new ReservationApprovedEvent());
    }

    protected function GetMessage(User $owner, $reservation, IAttributeRepository $attributeRepository, IUserRepository $userRepository)
    {
        return new ReservationApprovedEmail($owner, $reservation, null, $attributeRepository, $userRepository);
    }
}

class OwnerEmailDeletedNotification extends OwnerEmailNotification
{
    /**
     * @param $owner User
     * @return bool
     */
    protected function ShouldSend(User $owner)
    {
        return $owner->WantsEventEmail(new ReservationDeletedEvent());
    }

    protected function GetMessage(User $owner, $reservation, IAttributeRepository $attributeRepository, IUserRepository $userRepository)
    {
        return new ReservationDeletedEmail($owner, $reservation, null, $attributeRepository, $userRepository);
    }
}
