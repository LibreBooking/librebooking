<?php

require_once(ROOT_DIR . 'WebServices/Responses/CustomAttributes/CustomAttributeResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/ResourceItemResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/Group/GroupItemResponse.php');

class UserResponse extends RestResponse
{
    public $id;
    public $userName;
    public $firstName;
    public $lastName;
    public $emailAddress;
    public $phoneNumber;
    public $lastLogin;
    public $statusId;
    public $timezone;
    public $organization;
    public $position;
    public $language;
    public $icsUrl;
    public $defaultScheduleId;
    public $currentCredits;
    public $reservationColor;

    /** @var array|CustomAttributeResponse[] */
    public $customAttributes = [];
    /** @var array|ResourceItemResponse[] */
    public $permissions = [];
    /** @var array|GroupItemResponse[] */
    public $groups = [];

    public function __construct(IRestServer $server, User $user, IEntityAttributeList $attributes)
    {
        $userId = $user->Id();
        $this->id = $userId;
        $this->emailAddress = $user->EmailAddress();
        $this->firstName = $user->FirstName();
        $this->lastName = $user->LastName();
        $this->language = $user->Language();
        $this->lastLogin = Date::FromDatabase($user->LastLogin())->ToIso();
        $this->organization = $user->GetAttribute(UserAttribute::Organization);
        $this->phoneNumber = $user->GetAttribute(UserAttribute::Phone);
        $this->position = $user->GetAttribute(UserAttribute::Position);
        $this->statusId = $user->StatusId();
        $this->timezone = $user->Timezone();
        $this->userName = $user->Username();
        $this->defaultScheduleId = $user->GetDefaultScheduleId();
        $this->currentCredits = $user->GetCurrentCredits();
        $this->reservationColor = $user->GetPreference(UserPreferences::RESERVATION_COLOR);

        $attributeValues = $attributes->GetAttributes($userId);

        if (!empty($attributeValues)) {
            foreach ($attributeValues as $av) {
                $this->customAttributes[] = new CustomAttributeResponse($server, $av->Id(), $av->Label(), $av->Value());
            }
        }

        foreach ($user->GetAllowedResourceIds() as $allowedResourceId) {
            $this->permissions[] = new ResourceItemResponse($server, $allowedResourceId, '');
        }

        foreach ($user->Groups() as $group) {
            $this->groups[] = new UserGroupItemResponse($server, $group->GroupId, $group->GroupName);
        }

        if ($user->GetIsCalendarSubscriptionAllowed()) {
            $url = new CalendarSubscriptionUrl($user->GetPublicId(), null, null);
            $this->icsUrl = $url->__toString();
        }
    }

    public static function Example()
    {
        return new ExampleUserResponse();
    }
}

class UserGroupItemResponse extends RestResponse
{
    /**
     * @var bool
     */
    public $isDefault;

    /**
     * @var int[]
     */
    public $roleIds;

    /**
     * @param int $id
     * @param string $name
     */
    public function __construct(IRestServer $server, public $id, public $name)
    {
        $this->AddService($server, WebServices::GetGroup, [WebServiceParams::GroupId => $this->id]);
    }

    public static function Example()
    {
        return new ExampleUserGroupItemResponse();
    }
}

class ExampleUserResponse extends UserResponse
{
    public function __construct()
    {
        $date = Date::Now()->ToIso();
        $this->id = 1;
        $this->emailAddress = 'email@address.com';
        $this->firstName = 'first';
        $this->lastName = 'last';
        $this->language = 'language_code';
        $this->lastLogin = $date;
        $this->organization = 'organization';
        $this->phoneNumber = 'phone';
        $this->statusId = 'statusId';
        $this->timezone = 'timezone';
        $this->userName = 'username';
        $this->position = 'position';
        $this->icsUrl = 'webcal://url/to/calendar';
        $this->customAttributes = [CustomAttributeResponse::Example()];
        $this->permissions = [ResourceItemResponse::Example()];
        $this->groups = [UserGroupItemResponse::Example()];
        $this->defaultScheduleId = 1;
        $this->currentCredits = '2.50';
        $this->reservationColor = '#000000';
    }
}

class ExampleUserGroupItemResponse extends UserGroupItemResponse
{
    public function __construct()
    {
        $this->id = 1;
        $this->name = 'group name';
    }
}
