<?php

require_once(ROOT_DIR . 'lib/Email/namespace.php');

class ReservationRequiresApprovalEmailAdmin extends ReservationCreatedEmailAdmin
{
    public function __construct(UserDto $adminDto, User $reservationOwner, ReservationSeries $reservationSeries, BookableResource $primaryResource, IAttributeRepository $attributeRepository, IUserRepository $userRepository)
    {
        parent::__construct(
            adminDto: $adminDto,
            reservationOwner: $reservationOwner,
            reservationSeries: $reservationSeries,
            primaryResource: $primaryResource,
            attributeRepository: $attributeRepository,
            userRepository: $userRepository
        );
    }

    public function Subject()
    {
        return $this->Translate('ReservationApprovalAdminSubjectWithResource', [$this->resource->GetName()]);
    }
}
