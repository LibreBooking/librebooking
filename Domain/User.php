<?php

require_once(ROOT_DIR . 'Domain/Values/RoleLevel.php');
require_once(ROOT_DIR . 'Domain/Values/EmailPreferences.php');

class User
{
    public function __construct()
    {
        $this->emailPreferences = new EmailPreferences();
        $this->preferences = new UserPreferences();
    }

    /**
     * @var IEmailPreferences
     */
    protected $emailPreferences;

    protected $id;

    public function Id()
    {
        return $this->id;
    }

    protected $firstName;

    public function FirstName()
    {
        return $this->firstName;
    }

    protected $lastName;

    public function LastName()
    {
        return $this->lastName;
    }

    public function FullName()
    {
        return $this->FirstName() . ' ' . $this->LastName();
    }

    protected $emailAddress;

    public function EmailAddress()
    {
        return $this->emailAddress;
    }

    protected $username;

    public function Username()
    {
        return $this->username;
    }

    protected $language;

    public function Language()
    {
        return $this->language;
    }

    protected $timezone;

    public function Timezone()
    {
        return $this->timezone;
    }

    protected $homepageId;

    public function Homepage()
    {
        return $this->homepageId;
    }

    protected $statusId;

    /**
     * @return int|null|AccountStatus
     */
    public function StatusId()
    {
        return $this->statusId;
    }

    /**
     * @var string
     */
    private $lastLogin;

    /**
     * @return string
     */
    public function LastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @var array|UserGroup[]
     */
    protected $groups = [];

    /**
     * @var UserGroup[]
     */
    private $addedGroups = [];

    /**
     * @var UserGroup[]
     */
    private $removedGroups = [];

    /**
     * @var array|UserGroup[]
     */
    protected $groupsICanAdminister = [];

    /**
     * @return array|UserGroup[]
     */
    public function Groups()
    {
        return $this->groups;
    }

    /**
     * @return UserGroup[]
     */
    public function GetAddedGroups()
    {
        return $this->addedGroups;
    }

    /**
     * @return UserGroup[]
     */
    public function GetRemovedGroups()
    {
        return $this->removedGroups;
    }

    /**
     * @param int $groupId
     * @return bool
     */
    public function IsInGroup($groupId)
    {
        foreach ($this->groups as $group) {
            if ($group->GroupId == $groupId) {
                return true;
            }
        }

        return false;
    }

    private $isCalendarSubscriptionAllowed = false;

    private $originalCredits = 0;

    /**
     * @return float
     */
    public function GetOriginalCredits()
    {
        return $this->originalCredits;
    }

    /**
     * @param bool $isAllowed
     */
    protected function SetIsCalendarSubscriptionAllowed($isAllowed)
    {
        $this->isCalendarSubscriptionAllowed = $isAllowed;
    }

    /**
     * @return bool
     */
    public function GetIsCalendarSubscriptionAllowed()
    {
        return $this->isCalendarSubscriptionAllowed;
    }

    private $publicId;

    /**
     * @param string $publicId
     */
    protected function SetPublicId($publicId)
    {
        $this->publicId = $publicId;
    }

    /**
     * @return string
     */
    public function GetPublicId()
    {
        return $this->publicId;
    }

    public function EnablePublicProfile()
    {
        if (empty($this->publicId)) {
            $this->SetPublicId(BookedStringHelper::Random(20));
        }
    }

    public function EnableSubscription()
    {
        $this->SetIsCalendarSubscriptionAllowed(true);
        $this->EnablePublicProfile();
    }

    public function DisableSubscription()
    {
        $this->SetIsCalendarSubscriptionAllowed(false);
    }

    public function Activate()
    {
        $this->statusId = AccountStatus::ACTIVE;
    }

    public function Deactivate()
    {
        $this->statusId = AccountStatus::INACTIVE;
    }

    protected $preferences;

    /**
     * @return UserPreferences
     */
    public function GetPreferences()
    {
        return $this->preferences;
    }

    /**
     * @return bool
     */
    public function IsRegistered()
    {
        return !empty($this->id);
    }

    public function ChangePreference($name, $value)
    {
        $this->preferences->Update($name, $value);
    }

    protected $creditsNote;

    /**
     * @return string
     */
    public function GetCreditsNote()
    {
        return $this->creditsNote;
    }

    /**
     * @var bool
     */
    private $permissionsChanged = false;
    private $removedPermissions = [];
    private $addedPermissions = [];
    private $removedViewPermissions = [];
    private $addedViewPermissions = [];

    /**
     * @var int[]
     */
    protected $allowedResourceIds = [];

    /**
     * @var int[]
     */
    protected $viewableResourceIds = [];

    /**
     * @var string
     * @internal
     */
    public $encryptedPassword;

    /**
     * @var string
     * @internal
     */
    public $passwordSalt;

    private $attributes = [];
    private $attributesChanged = false;

    private $isGroupAdmin = false;
    private $isApplicationAdmin = false;
    private $isResourceAdmin = false;
    private $isScheduleAdmin = false;

    /**
     * @param int[] $allowedResourceIds
     */
    public function WithAllowedPermissions($allowedResourceIds = [])
    {
        $this->permissionsChanged = false;
        $this->allowedResourceIds = $allowedResourceIds;
    }

    /**
     * @param int[] $viewableResourceIds
     */
    public function WithViewablePermission($viewableResourceIds = [])
    {
        $this->permissionsChanged = false;
        $this->viewableResourceIds = $viewableResourceIds;
    }

    public function WithPreferences(UserPreferences $preferences)
    {
        $this->preferences = $preferences;
    }

    /**
     * @param UserGroup[] $groups
     */
    public function WithGroups($groups = [])
    {
        foreach ($groups as $group) {
            if ($group->IsGroupAdmin) {
                $this->isGroupAdmin = true;
            }
            if ($group->IsApplicationAdmin) {
                $this->isApplicationAdmin = true;
            }
            if ($group->IsResourceAdmin) {
                $this->isResourceAdmin = true;
            }
            if ($group->IsScheduleAdmin) {
                $this->isScheduleAdmin = true;
            }
        }

        $this->groups = $groups;
    }

    /**
     * @param UserGroup[] $ownedGroups
     */
    public function WithOwnedGroups($ownedGroups = [])
    {
        $this->groupsICanAdminister = $ownedGroups;
    }

    /**
     * @param int[] $allowedResourceIds
     */
    public function ChangeAllowedPermissions($allowedResourceIds = [])
    {
        $removed = array_diff($this->allowedResourceIds, $allowedResourceIds);
        $added = array_diff($allowedResourceIds, $this->allowedResourceIds);

        if (!empty($removed) || !empty($added)) {
            $this->permissionsChanged = true;
            $this->removedPermissions = $removed;
            $this->addedPermissions = $added;

            $this->allowedResourceIds = $allowedResourceIds;
        }
    }

    /**
     * @param int[] $viewableResourceIds
     */
    public function ChangeViewPermissions($viewableResourceIds = [])
    {
        $diff = new ArrayDiff($this->viewableResourceIds, $viewableResourceIds);
        $removed = $diff->GetRemovedFromArray1();
        $added = $diff->GetAddedToArray1();

        if ($diff->AreDifferent()) {
            $this->permissionsChanged = true;
            $this->removedViewPermissions = $removed;
            $this->addedViewPermissions = $added;

            $this->viewableResourceIds = $viewableResourceIds;
        }
    }

    /**
     * @return int[]
     */
    public function GetAllowedResourceIds()
    {
        return $this->allowedResourceIds;
    }

    /**
     * @internal
     * @return int[]
     */
    public function GetAddedPermissions()
    {
        return $this->addedPermissions;
    }

    /**
     * @internal
     * @return int[]
     */
    public function GetAddedViewPermissions()
    {
        return $this->addedViewPermissions;
    }

    /**
     * @internal
     * @return int[]
     */
    public function GetRemovedPermissions()
    {
        return array_merge($this->removedPermissions, $this->removedViewPermissions);
    }

    /**
     * @return int[]
     */
    public function GetAllowedViewResourceIds()
    {
        return $this->viewableResourceIds;
    }

    /**
     * @internal
     * @param IEmailPreferences $emailPreferences
     */
    public function WithEmailPreferences(IEmailPreferences $emailPreferences)
    {
        $this->emailPreferences = $emailPreferences;
    }

    /**
     * @param IDomainEvent $event
     * @return bool
     */
    public function WantsEventEmail(IDomainEvent $event)
    {
        return $this->emailPreferences->Exists($event->EventCategory(), $event->EventType());
    }

    /**
     * @param IDomainEvent $event
     * @param bool $turnedOn
     */
    public function ChangeEmailPreference(IDomainEvent $event, $turnedOn)
    {
        if ($turnedOn) {
            $this->emailPreferences->AddPreference($event);
        } else {
            $this->emailPreferences->RemovePreference($event);
        }
    }

    /**
     * @param string $loginTime
     * @param string $language
     */
    public function Login($loginTime, $language)
    {
        $this->lastLogin = $loginTime;
        $this->language = $language;
        $this->EnablePublicProfile();
    }

    /**
     * @return array|IDomainEvent[]
     */
    public function GetAddedEmailPreferences()
    {
        return $this->emailPreferences->GetAdded();
    }

    /**
     * @return array|IDomainEvent[]
     */
    public function GetRemovedEmailPreferences()
    {
        return $this->emailPreferences->GetRemoved();
    }

    public static function FromRow($row)
    {
        $user = new User();
        $user->id = $row[ColumnNames::USER_ID];
        $user->firstName = $row[ColumnNames::FIRST_NAME];
        $user->lastName = $row[ColumnNames::LAST_NAME];
        $user->emailAddress = $row[ColumnNames::EMAIL];
        $user->username = $row[ColumnNames::USERNAME];
        $user->language = $row[ColumnNames::LANGUAGE_CODE];
        $user->timezone = $row[ColumnNames::TIMEZONE_NAME];
        $user->statusId = $row[ColumnNames::USER_STATUS_ID];
        $user->encryptedPassword = $row[ColumnNames::PASSWORD];
        $user->passwordSalt = $row[ColumnNames::SALT];
        $user->homepageId = $row[ColumnNames::HOMEPAGE_ID];
        $user->lastLogin = $row[ColumnNames::LAST_LOGIN];
        $user->isCalendarSubscriptionAllowed = $row[ColumnNames::ALLOW_CALENDAR_SUBSCRIPTION];
        $user->publicId = $row[ColumnNames::PUBLIC_ID];
        $user->defaultScheduleId = $row[ColumnNames::DEFAULT_SCHEDULE_ID];

        $user->attributes[UserAttribute::Phone] = $row[ColumnNames::PHONE_NUMBER];
        $user->attributes[UserAttribute::Position] = $row[ColumnNames::POSITION];
        $user->attributes[UserAttribute::Organization] = $row[ColumnNames::ORGANIZATION];

        $user->isApplicationAdmin = Configuration::Instance()->IsAdminEmail($row[ColumnNames::EMAIL]);

        return $user;
    }

    /**
     * @static
     * @return User
     */
    public static function Create(
        $firstName,
        $lastName,
        $emailAddress,
        $userName,
        $language,
        $timezone,
        $password,
        $passwordSalt,
        $homepageId = Pages::DEFAULT_HOMEPAGE_ID
    ) {
        $user = new User();
        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->emailAddress = $emailAddress;
        $user->username = $userName;
        $user->language = $language;
        $user->timezone = $timezone;
        $user->encryptedPassword = $password;
        $user->passwordSalt = $passwordSalt;
        $user->homepageId = $homepageId;
        $user->statusId = AccountStatus::ACTIVE;
        return $user;
    }

    /**
     * @static
     * @return User
     */
    public static function CreatePending(
        $firstName,
        $lastName,
        $emailAddress,
        $userName,
        $language,
        $timezone,
        $password,
        $passwordSalt,
        $homepageId = Pages::DEFAULT_HOMEPAGE_ID
    ) {
        $user = self::Create(
            $firstName,
            $lastName,
            $emailAddress,
            $userName,
            $language,
            $timezone,
            $password,
            $passwordSalt,
            $homepageId
        );
        $user->statusId = AccountStatus::AWAITING_ACTIVATION;
        return $user;
    }

    /**
     * @param int $userId
     */
    public function WithId($userId)
    {
        $this->id = $userId;
    }

    /**
     * @param string $loginTime
     */
    public function WithLastLogin($loginTime)
    {
        $this->lastLogin = $loginTime;
    }

    /**
     * @param string $encryptedPassword
     * @param string $salt
     */
    public function ChangePassword($encryptedPassword, $salt)
    {
        $this->encryptedPassword = $encryptedPassword;
        $this->passwordSalt = $salt;
    }

    public function ChangeName($firstName, $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function ChangeEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    public function ChangeUsername($username)
    {
        $this->username = $username;
    }

    public function ChangeDefaultHomePage($homepageId)
    {
        $this->homepageId = $homepageId;
    }

    public function ChangeTimezone($timezoneName)
    {
        $this->timezone = $timezoneName;
    }

    public function ChangeLanguage($language)
    {
        $this->language = $language;
    }

    public function ChangeAttributes($phone, $organization, $position)
    {
        $this->attributesChanged = true;

        $this->attributes[UserAttribute::Phone] = $phone;
        $this->attributes[UserAttribute::Organization] = $organization;
        $this->attributes[UserAttribute::Position] = $position;
    }

    public function HaveAttributesChanged()
    {
        return $this->attributesChanged;
    }

    /**
     * @param UserAttribute|string $attributeName
     * @return string
     */
    public function GetAttribute($attributeName)
    {
        if (array_key_exists($attributeName, $this->attributes)) {
            return $this->attributes[$attributeName];
        }
        return null;
    }

    /**
     * @return bool
     */
    public function IsGroupAdmin()
    {
        return $this->isGroupAdmin;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function IsAdminFor(User $user)
    {
        if ($this->isApplicationAdmin) {
            return true;
        }

        if (!$this->isGroupAdmin) {
            return false;
        }

        $adminIdsForUser = [];
        foreach ($user->Groups() as $userGroup) {
            if (!empty($userGroup->AdminGroupId)) {
                $adminIdsForUser[$userGroup->AdminGroupId] = true;
            }
        }

        foreach ($this->Groups() as $group) {
            if ($group->IsGroupAdmin) {
                if (array_key_exists($group->GroupId, $adminIdsForUser)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param IResource $resource
     * @return bool
     */
    public function IsResourceAdminFor(IResource $resource)
    {
        if ($this->isApplicationAdmin) {
            return true;
        }

        if ($this->isResourceAdmin || $this->isScheduleAdmin) {
            foreach ($this->groups as $group) {
                if (
                    ($group->GroupId == $resource->GetAdminGroupId()) ||
                    ($group->GroupId == $resource->GetScheduleAdminGroupId())
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param IResource[] $resources
     * @return bool
     */
    public function IsResourceAdminForOneOf($resources)
    {
        foreach ($resources as $resource) {
            if ($this->IsResourceAdminFor($resource)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ISchedule $schedule
     * @return bool
     */
    public function IsScheduleAdminFor(ISchedule $schedule)
    {
        if ($this->isApplicationAdmin) {
            return true;
        }

        if (!$this->isScheduleAdmin) {
            return false;
        }

        foreach ($this->groups as $group) {
            if ($group->GroupId == $schedule->GetAdminGroupId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int|RoleLevel $roleLevel
     * @return bool
     */
    public function IsInRole($roleLevel)
    {
        if ($roleLevel == RoleLevel::GROUP_ADMIN) {
            return $this->isGroupAdmin;
        }
        if ($roleLevel == RoleLevel::APPLICATION_ADMIN) {
            return $this->isApplicationAdmin;
        }
        if ($roleLevel == RoleLevel::RESOURCE_ADMIN) {
            return $this->isResourceAdmin;
        }
        if ($roleLevel == RoleLevel::SCHEDULE_ADMIN) {
            return $this->isScheduleAdmin;
        }

        return false;
    }

    /**
     * @static
     * @return User
     */
    public static function null()
    {
        return new NullUser();
    }

    /**
     * @return array|UserGroup[]
     */
    public function GetAdminGroups()
    {
        return $this->groupsICanAdminister;
    }

    /**
     * @param $attribute AttributeValue
     * @param $adminOnly bool
     */
    public function WithAttribute(AttributeValue $attribute, $adminOnly = false)
    {
        $this->attributeValues[$attribute->AttributeId] = $attribute;
        if ($adminOnly) {
            $this->adminAttributesIds[] = $attribute->AttributeId;
        }
    }

    /**
     * @var array|int[]
     */
    private $adminAttributesIds = [];

    /**
     * @var array|AttributeValue[]
     */
    private $attributeValues = [];

    /**
     * @var array|AttributeValue[]
     */
    private $_addedAttributeValues = [];

    /**
     * @var array|AttributeValue[]
     */
    private $_removedAttributeValues = [];

    /**
     * @var float
     */
    private $credits;

    /**
     * @param $attributes AttributeValue[]|array
     */
    public function ChangeCustomAttributes($attributes, $removeAttrOnDiff = true)
    {
        $diff = new ArrayDiff($this->attributeValues, $attributes);

        $added = $diff->GetAddedToArray1();
        $removed = $diff->GetRemovedFromArray1();

        /** @var AttributeValue $attribute */
        foreach ($added as $attribute) {
            $this->_addedAttributeValues[] = $attribute;
        }

        if ($removeAttrOnDiff) {
            /** @var AttributeValue $attribute */
            foreach ($removed as $attribute) {
                if (!in_array($attribute->AttributeId, $this->adminAttributesIds)) {
                    $this->_removedAttributeValues[] = $attribute;
                }
            }
        }

        foreach ($attributes as $attribute) {
            $this->AddAttributeValue($attribute);
        }
    }

    /**
     * @param $attributeValue AttributeValue
     */
    public function AddAttributeValue($attributeValue)
    {
        $this->attributeValues[$attributeValue->AttributeId] = $attributeValue;
    }

    /**
     * @return array|AttributeValue[]
     */
    public function GetAddedAttributes()
    {
        return $this->_addedAttributeValues;
    }

    /**
     * @return array|AttributeValue[]
     */
    public function GetRemovedAttributes()
    {
        return $this->_removedAttributeValues;
    }

    /**
     * @param $customAttributeId
     * @return mixed
     */
    public function GetAttributeValue($customAttributeId)
    {
        if (array_key_exists($customAttributeId, $this->attributeValues)) {
            return $this->attributeValues[$customAttributeId]->Value;
        }

        return null;
    }

    /**
     * @var int|null
     */
    protected $defaultScheduleId;

    /**
     * @return int|null
     */
    public function GetDefaultScheduleId()
    {
        return $this->defaultScheduleId;
    }

    /**
     * @param int $scheduleId
     */
    public function ChangeDefaultSchedule($scheduleId)
    {
        $this->defaultScheduleId = $scheduleId;
    }

    /**
     * @param int $scheduleId
     */
    public function WithDefaultSchedule($scheduleId)
    {
        $this->defaultScheduleId = $scheduleId;
    }

    /**
     * @param $groupId int|int[]
     * @return bool
     */
    public function IsGroupAdminFor($groupId)
    {
        if (!is_array($groupId)) {
            $groupId = [$groupId];
        }

        foreach ($this->groupsICanAdminister as $group) {
            if (in_array($group->GroupId, $groupId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $preferenceName string
     * @return null|string
     */
    public function GetPreference($preferenceName)
    {
        return $this->preferences->Get($preferenceName);
    }

    public function ChangeGroups($groups)
    {
        $diff = new ArrayDiff($this->groups, $groups);

        $added = $diff->GetAddedToArray1();
        $removed = $diff->GetRemovedFromArray1();

        /** @var UserGroup $group */
        foreach ($added as $group) {
            $this->addedGroups[] = $group;
        }

        /** @var UserGroup $group */
        foreach ($removed as $group) {
            $this->removedGroups[] = $group;
        }

        $this->WithGroups($groups);
    }

    /**
     * @param $attribute AttributeValue
     */
    public function ChangeCustomAttribute($attribute)
    {
        $this->_removedAttributeValues[] = $attribute;
        $this->_addedAttributeValues[] = $attribute;
        $this->AddAttributeValue($attribute);
    }

    public function GetCurrentCredits()
    {
        return empty($this->credits) ? 0 : $this->credits;
    }

    public function WithCredits($credits)
    {
        $this->originalCredits = $credits;
        $this->credits = $credits;
    }

    public function ChangeCurrentCredits($credits, $note = '')
    {
        $this->credits = $credits;
        $this->creditsNote = $note;
    }

    public function AddCredits($credits, $note = '')
    {
        $this->credits = intval($this->credits) + intval($credits);
        $this->creditsNote = $note;
    }

    public function HaveCreditsChanged()
    {
        return $this->credits != $this->originalCredits;
    }

    /**
     * @var Date|null
     */
    protected $termsAcceptanceDate;

    /**
     * @return Date|null
     */
    public function TermsAcceptanceDate()
    {
        return $this->termsAcceptanceDate;
    }

    /**
     * @param bool $accepted
     */
    public function AcceptTerms($accepted)
    {
        if ($accepted) {
            $this->termsAcceptanceDate = Date::Now();
        }
    }
}

class NullUser extends User
{
    public function Id()
    {
        return null;
    }
}

class GuestUser extends User
{
    public function __construct($email)
    {
        parent::__construct();
        $this->emailAddress = $email;
        $this->language = Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE);
        $this->timezone = Configuration::Instance()->GetDefaultTimezone();
    }
}

class UserAttribute
{
    public const Phone = 'phone';
    public const Organization = 'organization';
    public const Position = 'position';

    /**
     * @var array|string[]
     */
    private $attributeValues = [];

    public function __construct($attributeValues = [])
    {
        $this->attributeValues = $attributeValues;
    }

    /**
     * @param string|UserAttribute $attributeName
     * @return null|string
     */
    public function Get($attributeName)
    {
        if (array_key_exists($attributeName, $this->attributeValues)) {
            return $this->attributeValues[$attributeName];
        }

        return null;
    }
}

class UserGroup
{
    /**
     * @var int
     */
    public $GroupId;

    /**
     * @var string
     */
    public $GroupName;

    /**
     * @var int|null
     */
    public $AdminGroupId;

    /**
     * @var bool
     */
    public $IsGroupAdmin = false;

    /**
     * @var bool
     */
    public $IsApplicationAdmin = false;

    /**
     * @var bool
     */
    public $IsResourceAdmin = false;

    /**
     * @var bool
     */
    public $IsScheduleAdmin = false;

    /**
     * @param int $groupId
     * @param string $groupName
     * @param int|null $adminGroupId
     * @param int|RoleLevel $roleLevel defaults to none
     */
    public function __construct($groupId, $groupName, $adminGroupId = null, $roleLevel = RoleLevel::NONE)
    {
        $this->GroupId = $groupId;
        $this->GroupName = $groupName;
        $this->AdminGroupId = $adminGroupId;
        $this->AddRole($roleLevel);
    }

    /**
     * @param int|null|RoleLevel $roleLevel
     */
    public function AddRole($roleLevel = null)
    {
        if ($roleLevel == RoleLevel::GROUP_ADMIN) {
            $this->IsGroupAdmin = true;
        }
        if ($roleLevel == RoleLevel::APPLICATION_ADMIN) {
            $this->IsApplicationAdmin = true;
        }
        if ($roleLevel == RoleLevel::RESOURCE_ADMIN) {
            $this->IsResourceAdmin = true;
        }
        if ($roleLevel == RoleLevel::SCHEDULE_ADMIN) {
            $this->IsScheduleAdmin = true;
        }
    }

    public function __toString()
    {
        return $this->GroupId . '';
    }
}
