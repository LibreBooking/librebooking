<?php

require_once(ROOT_DIR . 'lib/Application/Reservation/Notification/IReservationNotificationService.php');

class AddReservationNotificationService extends ReservationNotificationService
{
    public function __construct(IUserRepository $userRepo, IAttributeRepository $attributeRepo)
    {
        $notifications = [];
        $notifications[] = new OwnerEmailCreatedNotification($userRepo, $attributeRepo);
        $notifications[] = new AdminEmailCreatedNotification($userRepo, $userRepo, $attributeRepo);
        $notifications[] = new AdminEmailApprovalNotification($userRepo, $userRepo, $attributeRepo);
        $notifications[] = new ParticipantAddedEmailNotification($userRepo, $attributeRepo);
        $notifications[] = new InviteeAddedEmailNotification($userRepo, $attributeRepo);
        $notifications[] = new GuestAddedEmailNotification($userRepo, $attributeRepo);

        parent::__construct($notifications);
    }
}
