<?php

interface IReservationAuthorization
{
    /**
     * @param UserSession $currentUser
     * @return bool
     */
    public function CanChangeUsers(UserSession $currentUser);

    /**
     * @param ReservationView $reservationView
     * @param UserSession $currentUser
     * @return bool
     */
    public function CanEdit(ReservationView $reservationView, UserSession $currentUser);

    /**
     * @param ReservationView $reservationView
     * @param UserSession $currentUser
     * @return bool
     */
    public function CanApprove(ReservationView $reservationView, UserSession $currentUser);

    /**
     * @param ReservationView $reservationView
     * @param UserSession $currentUser
     * @return bool
     */
    public function CanViewDetails(ReservationView $reservationView, UserSession $currentUser);

    public function CanViewAttachments(ReservationView $reservationView, UserSession $currentUser): bool;

    public function CanViewSeriesAttachments(ExistingReservationSeries $series, UserSession $currentUser): bool;
}

class ReservationAuthorization implements IReservationAuthorization
{
    /**
     * @var \IAuthorizationService
     */
    private $authorizationService;

    public function __construct(IAuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    public function CanEdit(ReservationView $reservationView, UserSession $currentUser)
    {
        if ($currentUser->IsAdmin) {
            return true;
        }

        $startTimeConstraint = Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_START_TIME_CONSTRAINT);
        $allowedForAdmin = $reservationView->EndDate->GreaterThanOrEqual(Date::Now());

        $adminForUser = $this->authorizationService->IsAdminFor($currentUser, $reservationView->OwnerId);
        $adminForResource = false;
        foreach ($reservationView->Resources as $resource) {
            if ($this->authorizationService->CanEditForResource($currentUser, $resource)) {
                $adminForResource = true;
            }
        }

        if ($allowedForAdmin && ($adminForUser || $adminForResource) && $startTimeConstraint !== ReservationStartTimeConstraint::NONE) {
            return $adminForUser || $adminForResource;
        }

        $ongoingReservation = true;

        if ($startTimeConstraint == ReservationStartTimeConstraint::CURRENT) {
            $ongoingReservation = Date::Now()->LessThan($reservationView->EndDate);
        }

        if ($startTimeConstraint == ReservationStartTimeConstraint::FUTURE) {
            $ongoingReservation = Date::Now()->LessThan($reservationView->StartDate);
        }

        if ($ongoingReservation) {
            if ($this->IsAccessibleTo($reservationView, $currentUser)) {
                return true;
            }
        }

        return $currentUser->IsAdmin;    // only admins can edit reservations that have ended
    }

    public function CanChangeUsers(UserSession $currentUser)
    {
        return $currentUser->IsAdmin || $this->authorizationService->CanReserveForOthers($currentUser);
    }

    public function CanApprove(ReservationView $reservationView, UserSession $currentUser)
    {
        if (!$reservationView->RequiresApproval()) {
            return false;
        }

        if ($currentUser->IsAdmin) {
            return true;
        }

        $canReserveForUser = $this->authorizationService->CanApproveFor($currentUser, $reservationView->OwnerId);
        if ($canReserveForUser) {
            return true;
        }

        foreach ($reservationView->Resources as $resource) {
            if ($this->authorizationService->CanApproveForResource($currentUser, $resource)) {
                return true;
            }
        }

        return false;
    }

    public function CanViewDetails(ReservationView $reservationView, UserSession $currentUser)
    {
        return $this->IsAccessibleTo($reservationView, $currentUser);
    }

    public function CanViewAttachments(ReservationView $reservationView, UserSession $currentUser): bool
    {
        $userIds = array_map(fn (ReservationUserView $user) => $user->UserId, array_merge($reservationView->Participants, $reservationView->Invitees));

        return $this->CanViewAttachmentsFor($reservationView->OwnerId, $reservationView->Resources, $userIds, $currentUser);
    }

    public function CanViewSeriesAttachments(ExistingReservationSeries $series, UserSession $currentUser): bool
    {
        $instance = $series->CurrentInstance();
        $userIds = array_merge($instance->Participants(), $instance->Invitees());

        return $this->CanViewAttachmentsFor($series->UserId(), $series->AllResources(), $userIds, $currentUser);
    }

    /**
     * @param IResource[] $resources
     * @param int[] $userIds participant and invitee ids
     */
    private function CanViewAttachmentsFor($ownerId, array $resources, array $userIds, UserSession $currentUser): bool
    {
        return $this->IsAccessibleToOwnerOrResourceAdmin($ownerId, $resources, $currentUser)
            || in_array($currentUser->UserId, $userIds);
    }

    /**
     * @param ReservationView $reservationView
     * @param UserSession $currentUser
     * @return bool
     */
    private function IsAccessibleTo(ReservationView $reservationView, UserSession $currentUser)
    {
        return $this->IsAccessibleToOwnerOrResourceAdmin($reservationView->OwnerId, $reservationView->Resources, $currentUser);
    }

    /**
     * @param IResource[] $resources
     */
    private function IsAccessibleToOwnerOrResourceAdmin($ownerId, array $resources, UserSession $currentUser): bool
    {
        if ($ownerId == $currentUser->UserId || $currentUser->IsAdmin) {
            return true;
        }

        if ($this->authorizationService->CanReserveFor($currentUser, $ownerId)) {
            return true;
        }

        foreach ($resources as $resource) {
            if ($this->authorizationService->CanEditForResource($currentUser, $resource)) {
                return true;
            }
        }

        return false;
    }
}
