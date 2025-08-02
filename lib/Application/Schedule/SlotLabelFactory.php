<?php

class SlotLabelFactory
{
    /**
     * @var null|UserSession
     */
    private $user = null;

    /**
     * @var IAuthorizationService
     */
    private $authorizationService;

    /**
     * @var IAttributeRepository
     */
    private $attributeRepository;

    public function __construct($user = null, $authorizationService = null, $attributeRepository = null)
    {
        $this->user = $user;
        if ($this->user == null) {
            $this->user = ServiceLocator::GetServer()->GetUserSession();
        }

        $this->authorizationService = $authorizationService;
        if ($this->authorizationService == null) {
            $this->authorizationService = new AuthorizationService(new UserRepository());
        }

        $this->attributeRepository = $attributeRepository;
        if ($this->attributeRepository == null) {
            $this->attributeRepository = new AttributeRepository();
        }
    }

    /**
     * @static
     * @param ReservationItemView $reservation
     * @return string
     */
    public static function Create(ReservationItemView $reservation)
    {
        $f = new SlotLabelFactory();
        return $f->Format($reservation);
    }

    /**
     * @param ReservationItemView $reservation
     * @param string $format
     * @return string
     */
    public function Format(ReservationItemView $reservation, $format = null)
    {
        $shouldHideUser = Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, new BooleanConverter());
        $shouldHideDetails = ReservationDetailsFilter::HideReservationDetails($reservation->StartDate, $reservation->EndDate);
        $shouldHideReservations = Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_VIEW_RESERVATIONS, new BooleanConverter());

        if ($shouldHideUser || $shouldHideDetails) {
            $canSeeUserDetails = $reservation->OwnerId == $this->user->UserId || $this->user->IsAdmin || $this->user->IsAdminForGroup($reservation->OwnerGroupIds());
            $canEditResource = $this->authorizationService->CanEditForResource($this->user, new SlotLabelResource($reservation));
            $shouldHideUser = $shouldHideUser && !$canSeeUserDetails && !$canEditResource;
            $shouldHideDetails = $shouldHideDetails && !$canEditResource && !$canSeeUserDetails;
        }

        if ($shouldHideDetails) {
            return '';
        }

        if (!$shouldHideReservations && !$this->user->IsLoggedIn()) {
            return '';
        }

        if(!in_array($reservation->ResourceId,$this->UserResourcePermissions($this->user->UserId)) && !$reservation->IsUserOwner($this->user->UserId) && !$reservation->IsUserInvited($this->user->UserId) && !$reservation->IsUserParticipating($this->user->UserId)){
            return '';
        }

        if (empty($format)) {
            $format = Configuration::Instance()->GetKey(ConfigKeys::SCHEDULE_RESERVATION_LABEL);
        }

        if ($format == 'none' || empty($format)) {
            return '';
        }

        $name = $shouldHideUser ? Resources::GetInstance()->GetString('Private') : $this->GetFullName($reservation);

        $timezone = 'UTC';
        $dateFormat = Resources::GetInstance()->GetDateFormat('res_popup');
        if (!is_null($this->user)) {
            $timezone = $this->user->Timezone;
        }
        $label = $format;
        $label = str_replace('{name}', $name ?? '', $label);
        $label = str_replace('{title}', $reservation->Title ?? '', $label);
        $label = str_replace('{description}', $reservation->Description ?? '', $label);
        $label = str_replace('{email}', $reservation->OwnerEmailAddress, $label);
        $label = str_replace('{organization}', $reservation->OwnerOrganization ?? '', $label);
        $label = str_replace('{phone}', $reservation->OwnerPhone ?? '', $label);
        $label = str_replace('{position}', $reservation->OwnerPosition ?? '', $label);
        $label = str_replace('{startdate}', $reservation->StartDate->ToTimezone($timezone)->Format($dateFormat), $label);
        $label = str_replace('{enddate}', $reservation->EndDate->ToTimezone($timezone)->Format($dateFormat), $label);
        $label = str_replace('{resourcename}', implode(', ', $reservation->ResourceNames), $label);
        if (!$shouldHideUser) {
            $label = str_replace('{participants}', trim(implode(', ', $reservation->ParticipantNames)), $label);
            $label = str_replace('{invitees}', trim(implode(', ', $reservation->InviteeNames)), $label);
        }

        $matches = [];
        preg_match_all('/\{(att\d+?)\}/', $format, $matches);

        $matches = $matches[0];
        if (count($matches) > 0) {
            for ($m = 0; $m < count($matches); $m++) {
                $id = filter_var($matches[$m], FILTER_SANITIZE_NUMBER_INT);
                $value = $reservation->GetAttributeValue($id);

                $label = str_replace($matches[$m], $value, $label);
            }
        }

        if (BookedStringHelper::Contains($label, '{reservationAttributes}')) {
            $attributesLabel = new StringBuilder();
            $attributes = $this->attributeRepository->GetByCategory(CustomAttributeCategory::RESERVATION);
            foreach ($attributes as $attribute) {
                $entityIds = [$this->user->UserId];
                // check if this is a unique custom attribute
                if ((!$attribute->UniquePerEntity() && !$attribute->HasSecondaryEntities()) ||
                    (($attribute->UniquePerEntity() && count(array_intersect($entityIds, $attribute->EntityIds()))) ||
                        ($attribute->HasSecondaryEntities() && count(array_intersect($entityIds, $attribute->SecondaryEntityIds()))))) {
                    $attributesLabel->Append($attribute->Label() . ': ' . $reservation->GetAttributeValue($attribute->Id()) . ', ');
                }
            }
            $label = str_replace('{reservationAttributes}', rtrim($attributesLabel->ToString(), ', '), $label);
        }

        return $label;
    }

    protected function GetFullName(ReservationItemView $reservation)
    {
        $name = new FullName($reservation->FirstName, $reservation->LastName);
        return $name->__toString();
    }

     /**
     * Gets the resources the user has permissions (full access and view only permissions)
     * This is used to block a user from seeing reservation details if he has no permissions to it's resources
     */
    private function UserResourcePermissions($userId)
    {
        $resourceRepo = new ResourceRepository();
        $resourceIds = [];

        $resourceIds = $resourceRepo->GetUserResourcePermissions($userId);

        $resourceIds = $resourceRepo->GetUserGroupResourcePermissions($userId,$resourceIds);

        if (ServiceLocator::GetServer()->GetUserSession()->IsResourceAdmin){    
            $resourceIds = $resourceRepo->GetResourceAdminResourceIds($userId, $resourceIds);
        }

        if (ServiceLocator::GetServer()->GetUserSession()->IsScheduleAdmin){
            $resourceIds = $resourceRepo->GetScheduleAdminResourceIds($userId, $resourceIds);
        }

        return $resourceIds;
    }
}

class NullSlotLabelFactory extends SlotLabelFactory
{
    public function Format(ReservationItemView $reservation, $format = null)
    {
        return '';
    }
}

class AdminSlotLabelFactory extends SlotLabelFactory
{
    protected function GetFullName(ReservationItemView $reservation)
    {
        $name = new FullName($reservation->FirstName, $reservation->LastName);
        return $name->__toString();
    }
}

class SlotLabelResource implements IResource
{
    /**
     * @var int|null
     */
    private $id;
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var int|null
     */
    private $adminGroupId;
    /**
     * @var int|null
     */
    private $scheduleId;
    /**
     * @var int|null $scheduleAdminGroupId
     */
    private $scheduleAdminGroupId;
    /**
     * @var int
     */
    private $statusId;

    public function __construct(ReservationItemView $reservation)
    {
        $this->id = $reservation->ResourceId;
        $this->name = $reservation->ResourceName;
        $this->adminGroupId = $reservation->ResourceAdminGroupId;
        $this->scheduleId = $reservation->ScheduleId;
        $this->scheduleAdminGroupId = $reservation->ScheduleAdminGroupId;
        $this->statusId = $reservation->ResourceStatusId;
    }

    public function GetId()
    {
        return $this->id;
    }

    public function GetName()
    {
        return $this->name;
    }

    public function GetAdminGroupId()
    {
        return $this->adminGroupId;
    }

    public function GetScheduleId()
    {
        return $this->scheduleId;
    }

    public function GetScheduleAdminGroupId()
    {
        return $this->scheduleAdminGroupId;
    }

    public function GetStatusId()
    {
        return $this->statusId;
    }

    public function GetResourceId()
    {
        return $this->id;
    }
}
