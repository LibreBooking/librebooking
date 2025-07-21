<?php

require_once(ROOT_DIR . 'Domain/User.php');
require_once(ROOT_DIR . 'Domain/Values/AccountStatus.php');
require_once(ROOT_DIR . 'Domain/Values/FullName.php');
require_once(ROOT_DIR . 'Domain/Values/UserPreferences.php');
require_once(ROOT_DIR . 'lib/Email/Messages/AccountCreationEmail.php');

interface IUserRepository extends IUserViewRepository
{
    /**
     * @param int $userId
     * @return User
     */
    public function LoadById($userId);

    /**
     * @param string $publicId
     * @return User
     */
    public function LoadByPublicId($publicId);

    /**
     * @param string $userName
     * @return User
     */
    public function LoadByUsername($userName);

    /**
     * @param User $user
     * @return void
     */
    public function Update(User $user);

    /**
     * @param User $user
     * @return int
     */
    public function Add(User $user);

    /**
     * @param $userId int
     * @return void
     */
    public function DeleteById($userId);

    /**
     * @return int
     */
    public function GetCount();
}

class UserFilter
{
    private $username;
    private $email;
    private $firstName;
    private $lastName;
    private $phone;
    private $organization;
    private $position;
    private $attributes;
    /**
     * @var array|ISqlFilter[]
     */
    private $_and = [];


    public function __construct(
        $username = null,
        $email = null,
        $firstName = null,
        $lastName = null,
        $phone = null,
        $organization = null,
        $position = null,
        $attributes = null
    ) {
        $this->username = $username;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->organization = $organization;
        $this->position = $position;
        $this->attributes = $attributes;
    }

    /**
     * @param ISqlFilter $filter
     * @return UserFilter
     */
    public function _And(ISqlFilter $filter)
    {
        $this->_and[] = $filter;
        return $this;
    }

    public function GetFilter()
    {
        $filter = new SqlFilterNull();

        if (!empty($this->username)) {
            $filter->_And(new SqlFilterEquals(ColumnNames::USERNAME, $this->username));
        }
        if (!empty($this->email)) {
            $filter->_And(new SqlFilterEquals(ColumnNames::EMAIL, $this->email));
        }
        if (!empty($this->firstName)) {
            $filter->_And(new SqlFilterEquals(ColumnNames::FIRST_NAME, $this->firstName));
        }
        if (!empty($this->lastName)) {
            $filter->_And(new SqlFilterEquals(ColumnNames::LAST_NAME, $this->lastName));
        }
        if (!empty($this->phone)) {
            $filter->_And(new SqlFilterEquals(ColumnNames::PHONE_NUMBER, $this->phone));
        }
        if (!empty($this->organization)) {
            $filter->_And(new SqlFilterEquals(ColumnNames::ORGANIZATION, $this->organization));
        }
        if (!empty($this->position)) {
            $filter->_And(new SqlFilterEquals(ColumnNames::POSITION, $this->position));
        }

        if (!empty($this->attributes)) {
            $attributeFilter = AttributeFilter::Create('`'. TableNames::USERS_ALIAS . '`.`' . ColumnNames::USER_ID . '`', $this->attributes);

            if ($attributeFilter != null) {
                $filter->_And($attributeFilter);
            }
        }

        foreach ($this->_and as $and) {
            $filter->_And($and);
        }

        return $filter;
    }
}

interface IUserViewRepository
{
    /**
     * @param int $userId
     * @return UserDto
     */
    public function GetById($userId);

    /**
     * @return UserDto[]
     */
    public function GetAll();

    /**
     * @param int $pageNumber
     * @param int $pageSize
     * @param null|string $sortField
     * @param null|string $sortDirection
     * @param null|ISqlFilter $filter
     * @param AccountStatus|int $accountStatus
     * @return PageableData|UserItemView[]
     */
    public function GetList(
        $pageNumber,
        $pageSize,
        $sortField = null,
        $sortDirection = null,
        $filter = null,
        $accountStatus = AccountStatus::ALL
    );

    /**
     * @param int $resourceId
     * @return array|UserDto[]
     */
    public function GetResourceAdmins($resourceId);

    /**
     * @return array|UserDto[]
     */
    public function GetApplicationAdmins();

    /**
     * @param int $userId
     * @return array|UserDto[]
     */
    public function GetGroupAdmins($userId);

    /**
     * @param $userId int
     * @param $roleLevels int|null|array|int[]
     * @return array|UserGroup[]
     */
    public function LoadGroups($userId, $roleLevels = null);

    /**
     * @param string $emailAddress
     * @param string $userName
     * @return int|null
     */
    public function UserExists($emailAddress, $userName);
}

interface IAccountActivationRepository
{
    /**
     * @abstract
     * @param User $user
     * @param string $activationCode
     * @return void
     */
    public function AddActivation(User $user, $activationCode);

    /**
     * @abstract
     * @param string $activationCode
     * @return int|null
     */
    public function FindUserIdByCode($activationCode);

    /**
     * @abstract
     * @param string $activationCode
     * @return void
     */
    public function DeleteActivation($activationCode);
}

class UserRepository implements IUserRepository, IAccountActivationRepository
{
    /**
     * @var DomainCache
     */
    private $_cache;

    public function __construct()
    {
        $this->_cache = new DomainCache();
    }

    public function GetAll()
    {
        $command = new GetAllUsersByStatusCommand(AccountStatus::ACTIVE);

        $reader = ServiceLocator::GetDatabase()->Query($command);
        $users = [];

        while ($row = $reader->GetRow()) {
            $preferences = isset($row[ColumnNames::USER_PREFERENCES]) ? $row[ColumnNames::USER_PREFERENCES] : '';
            $creditCount = isset($row[ColumnNames::CREDIT_COUNT]) ? $row[ColumnNames::CREDIT_COUNT] : '';

            $users[] = new UserDto(
                $row[ColumnNames::USER_ID],
                $row[ColumnNames::FIRST_NAME],
                $row[ColumnNames::LAST_NAME],
                $row[ColumnNames::EMAIL],
                $row[ColumnNames::TIMEZONE_NAME],
                $row[ColumnNames::LANGUAGE_CODE],
                $preferences,
                $creditCount
            );
        }

        $reader->Free();

        return $users;
    }

    /**
     * @param $userId
     * @return null|UserDto
     */
    public function GetById($userId)
    {
        if ($this->_cache->Exists($userId . 'dto')) {
            return $this->_cache->Get($userId . 'dto');
        }
        $command = new GetUserByIdCommand($userId);

        $reader = ServiceLocator::GetDatabase()->Query($command);

        if ($row = $reader->GetRow()) {
            $user = new UserDto(
                $row[ColumnNames::USER_ID],
                $row[ColumnNames::FIRST_NAME],
                $row[ColumnNames::LAST_NAME],
                $row[ColumnNames::EMAIL],
                $row[ColumnNames::TIMEZONE_NAME],
                $row[ColumnNames::LANGUAGE_CODE],
                null,
                $row[ColumnNames::CREDIT_COUNT]
            );

            $this->_cache->Add($userId . 'dto', $user);

            $reader->Free();
            return $user;
        }

        $reader->Free();

        return null;
    }

    public function GetList(
        $pageNumber,
        $pageSize,
        $sortField = null,
        $sortDirection = null,
        $filter = null,
        $accountStatus = AccountStatus::ALL
    ) {
        $command = new GetAllUsersByStatusCommand($accountStatus);

        if ($filter != null) {
            $command = new FilterCommand($command, $filter);
        }

        $builder = ['UserItemView', 'Create'];
        return PageableDataStore::GetList($command, $builder, $pageNumber, $pageSize, $sortField, $sortDirection);
    }

    /**
     * @param $command SqlCommand
     * @return User
     */
    private function Load($command)
    {
        $reader = ServiceLocator::GetDatabase()->Query($command);

        if ($row = $reader->GetRow()) {
            $userId = $row[ColumnNames::USER_ID];
            $emailPreferences = $this->LoadEmailPreferences($userId);
            $permissions = $this->LoadPermissions($userId);
            $groups = $this->LoadGroups($userId);

            $user = User::FromRow($row);
            $user->WithEmailPreferences($emailPreferences);
            $user->WithAllowedPermissions($permissions['full']);
            $user->WithViewablePermission($permissions['view']);
            $user->WithGroups($groups);
            $user->WithCredits($row[ColumnNames::CREDIT_COUNT]);
            $this->LoadAttributes($userId, $user);

            if ($user->IsGroupAdmin()) {
                $ownedGroups = $this->LoadOwnedGroups($userId);
                $user->WithOwnedGroups($ownedGroups);
            }

            $preferences = $this->LoadPreferences($userId);
            $user->WithPreferences($preferences);

            $user->WithDefaultSchedule($row[ColumnNames::DEFAULT_SCHEDULE_ID]);

            $this->_cache->Add($userId, $user);

            $reader->Free();
            return $user;
        } else {
            $reader->Free();
            return User::Null();
        }
    }

    /**
     * @param int $userId
     * @return User
     */
    public function LoadById($userId)
    {
        if (!$this->_cache->Exists($userId)) {
            $command = new GetUserByIdCommand($userId);
            return $this->Load($command);
        } else {
            return $this->_cache->Get($userId);
        }
    }

    /**
     * @param string $publicId
     * @return User
     */
    public function LoadByPublicId($publicId)
    {
        $command = new GetUserByPublicIdCommand($publicId);
        return $this->Load($command);
    }

    /**
     * @param string $userName
     * @return User
     */
    public function LoadByUsername($userName)
    {
        $command = new LoginCommand(strtolower($userName));
        return $this->Load($command);
    }

    /**
     * @param User $user
     * @return int
     */
    public function Add(User $user)
    {
        $db = ServiceLocator::GetDatabase();
        $id = $db->ExecuteInsert(new RegisterUserCommand(
            $user->Username(),
            $user->EmailAddress(),
            $user->FirstName(),
            $user->LastName(),
            $user->encryptedPassword,
            $user->passwordSalt,
            $user->Timezone(),
            $user->Language(),
            $user->Homepage(),
            $user->GetAttribute(UserAttribute::Phone),
            $user->GetAttribute(UserAttribute::Organization),
            $user->GetAttribute(UserAttribute::Position),
            $user->StatusId(),
            $user->GetPublicId(),
            $user->GetDefaultScheduleId(),
            $user->TermsAcceptanceDate()
        ));

        $user->WithId($id);

        foreach ($user->GetAddedAttributes() as $added) {
            $db->Execute(new AddAttributeValueCommand($added->AttributeId, $added->Value, $user->Id(), CustomAttributeCategory::USER));
        }

        $addedPreferences = $user->GetAddedEmailPreferences();
        foreach ($addedPreferences as $event) {
            $db->Execute(new AddEmailPreferenceCommand($id, $event->EventCategory(), $event->EventType()));
        }

        $userGroups = $user->Groups();
        if (!empty($userGroups)) {
            foreach ($userGroups as $group) {
                $db->Execute(new AddUserGroupCommand($id, $group->GroupId));
            }
        }

        $addedPermissions = $user->GetAddedPermissions();
        if (!empty($addedPermissions)) {
            foreach ($addedPermissions as $resourceId) {
                $db->Execute(new AddUserResourcePermission($id, $resourceId, ResourcePermissionType::Full));
            }
        }

        $addedPermissions = $user->GetAddedViewPermissions();
        if (!empty($addedPermissions)) {
            foreach ($addedPermissions as $resourceId) {
                $db->Execute(new AddUserResourcePermission($id, $resourceId, ResourcePermissionType::View));
            }
        }

        $db->Execute(new AddUserToDefaultGroupsCommand($id));

        return $id;
    }

    /**
     * @param User $user
     * @return void
     */
    public function Update(User $user)
    {
        $userId = $user->Id();

        $db = ServiceLocator::GetDatabase();
        $updateUserCommand = new UpdateUserCommand(
            $user->Id(),
            $user->StatusId(),
            $user->encryptedPassword,
            $user->passwordSalt,
            $user->FirstName(),
            $user->LastName(),
            $user->EmailAddress(),
            $user->Username(),
            $user->Homepage(),
            $user->Timezone(),
            $user->LastLogin(),
            $user->GetIsCalendarSubscriptionAllowed(),
            $user->GetPublicId(),
            $user->Language(),
            $user->GetDefaultScheduleId(),
            $user->GetCurrentCredits()
        );
        $db->Execute($updateUserCommand);

        $removedPermissions = $user->GetRemovedPermissions();
        foreach ($removedPermissions as $resourceId) {
            $db->Execute(new DeleteUserResourcePermission($userId, $resourceId));
        }

        $addedPermissions = $user->GetAddedPermissions();
        foreach ($addedPermissions as $resourceId) {
            $db->Execute(new AddUserResourcePermission($userId, $resourceId, ResourcePermissionType::Full));
        }

        $addedPermissions = $user->GetAddedViewPermissions();
        foreach ($addedPermissions as $resourceId) {
            $db->Execute(new AddUserResourcePermission($userId, $resourceId, ResourcePermissionType::View));
        }

        if ($user->HaveAttributesChanged()) {
            $updateAttributesCommand = new UpdateUserAttributesCommand(
                $userId,
                $user->GetAttribute(UserAttribute::Phone),
                $user->GetAttribute(UserAttribute::Organization),
                $user->GetAttribute(UserAttribute::Position)
            );
            $db->Execute($updateAttributesCommand);
        }

        $removedPreferences = $user->GetRemovedEmailPreferences();
        foreach ($removedPreferences as $event) {
            $db->Execute(new DeleteEmailPreferenceCommand($userId, $event->EventCategory(), $event->EventType()));
        }

        $addedPreferences = $user->GetAddedEmailPreferences();
        foreach ($addedPreferences as $event) {
            $db->Execute(new AddEmailPreferenceCommand($userId, $event->EventCategory(), $event->EventType()));
        }

        foreach ($user->GetRemovedAttributes() as $removed) {
            $db->Execute(new RemoveAttributeValueCommand($removed->AttributeId, $user->Id()));
        }

        foreach ($user->GetAddedAttributes() as $added) {
            $db->Execute(new AddAttributeValueCommand($added->AttributeId, $added->Value, $user->Id(), CustomAttributeCategory::USER));
        }

        $db->Execute(new DeleteAllUserPreferences($user->Id()));
        foreach ($user->GetPreferences()->All() as $name => $value) {
            $db->Execute(new AddUserPreferenceCommand($user->Id(), $name, $value));
        }

        foreach ($user->GetRemovedGroups() as $removed) {
            $db->Execute(new DeleteUserGroupCommand($user->Id(), $removed->GroupId));
        }

        foreach ($user->GetAddedGroups() as $added) {
            $db->Execute(new AddUserGroupCommand($user->Id(), $added->GroupId));
        }

        if ($user->HaveCreditsChanged()) {
            $db->Execute(new LogCreditActivityCommand($user->Id(), $user->GetOriginalCredits(), $user->GetCurrentCredits(), $user->GetCreditsNote()));
        }

        $this->_cache->Remove($userId);
    }

    public function DeleteById($userId)
    {
        $deleteUserCommand = new DeleteUserCommand($userId);
        ServiceLocator::GetDatabase()->Execute($deleteUserCommand);
        $this->_cache->Remove($userId);
    }

    public function LoadEmailPreferences($userId)
    {
        $emailPreferences = new EmailPreferences();

        $command = new GetUserEmailPreferencesCommand($userId);
        $reader = ServiceLocator::GetDatabase()->Query($command);

        while ($row = $reader->GetRow()) {
            $emailPreferences->Add($row[ColumnNames::EVENT_CATEGORY], $row[ColumnNames::EVENT_TYPE]);
        }

        $reader->Free();

        return $emailPreferences;
    }

    /**
     * @param int $resourceId
     * @return array|UserDto[]
     */
    public function GetResourceAdmins($resourceId)
    {
        $command = new GetAllResourceAdminsCommand($resourceId);

        $reader = ServiceLocator::GetDatabase()->Query($command);
        $users = [];

        while ($row = $reader->GetRow()) {
            $users[] = new UserDto(
                $row[ColumnNames::USER_ID],
                $row[ColumnNames::FIRST_NAME],
                $row[ColumnNames::LAST_NAME],
                $row[ColumnNames::EMAIL],
                $row[ColumnNames::TIMEZONE_NAME],
                $row[ColumnNames::LANGUAGE_CODE]
            );
        }

        $reader->Free();

        return $users;
    }

    /**
     * @return array|UserDto[]
     */
    public function GetApplicationAdmins()
    {
        $adminEmails = Configuration::Instance()->GetAllAdminEmails();
        $command = new GetAllApplicationAdminsCommand($adminEmails);
        $reader = ServiceLocator::GetDatabase()->Query($command);
        $users = [];

        while ($row = $reader->GetRow()) {
            $users[] = new UserDto(
                $row[ColumnNames::USER_ID],
                $row[ColumnNames::FIRST_NAME],
                $row[ColumnNames::LAST_NAME],
                $row[ColumnNames::EMAIL],
                $row[ColumnNames::TIMEZONE_NAME],
                $row[ColumnNames::LANGUAGE_CODE]
            );
        }

        $reader->Free();

        return $users;
    }

    /**
     * @param int $userId
     * @return array|UserDto[]
     */
    public function GetGroupAdmins($userId)
    {
        $command = new GetAllGroupAdminsCommand($userId);
        $reader = ServiceLocator::GetDatabase()->Query($command);
        $users = [];

        while ($row = $reader->GetRow()) {
            $users[] = new UserDto(
                $row[ColumnNames::USER_ID],
                $row[ColumnNames::FIRST_NAME],
                $row[ColumnNames::LAST_NAME],
                $row[ColumnNames::EMAIL],
                $row[ColumnNames::TIMEZONE_NAME],
                $row[ColumnNames::LANGUAGE_CODE]
            );
        }

        $reader->Free();

        return $users;
    }

    private function LoadPermissions($userId)
    {
        $allowedResourceIds['full'] = [];
        $allowedResourceIds['view'] = [];

        $command = new GetUserPermissionsCommand($userId);
        $reader = ServiceLocator::GetDatabase()->Query($command);

        while ($row = $reader->GetRow()) {
            if ($row[ColumnNames::PERMISSION_TYPE] == ResourcePermissionType::Full) {
                $allowedResourceIds['full'][] = $row[ColumnNames::RESOURCE_ID];
            } else {
                $allowedResourceIds['view'][] = $row[ColumnNames::RESOURCE_ID];
            }
        }

        $reader->Free();
        return $allowedResourceIds;
    }

    public function LoadGroups($userId, $roleLevels = null)
    {
        /**
         * @var array|UserGroup[] $groups
         */
        $groups = [];

        if (!is_null($roleLevels) && !is_array($roleLevels)) {
            $roleLevels = [$roleLevels];
        }

        $command = new GetUserGroupsCommand($userId, $roleLevels);
        $reader = ServiceLocator::GetDatabase()->Query($command);

        while ($row = $reader->GetRow()) {
            $groupId = $row[ColumnNames::GROUP_ID];
            if (!array_key_exists($groupId, $groups)) {
                // a group can have many roles which are all returned at once
                $group = new UserGroup($groupId, $row[ColumnNames::GROUP_NAME], $row[ColumnNames::GROUP_ADMIN_GROUP_ID], $row[ColumnNames::ROLE_LEVEL]);
                $groups[$groupId] = $group;
            } else {
                $groups[$groupId]->AddRole($row[ColumnNames::ROLE_LEVEL]);
            }
        }

        $reader->Free();

        return array_values($groups);
    }

    public function LoadPreferences($userId)
    {
        $command = new GetUserPreferencesCommand($userId);
        $reader = ServiceLocator::GetDatabase()->Query($command);

        $preferences = new UserPreferences();
        while ($row = $reader->GetRow()) {
            $preferences->Add($row[ColumnNames::PREFERENCE_NAME], $row[ColumnNames::PREFERENCE_VALUE]);
        }

        $reader->Free();

        return $preferences;
    }

    /**
     * @param $emailAddress string
     * @return User
     */
    public function FindByEmail($emailAddress)
    {
        $command = new CheckEmailCommand($emailAddress);
        $reader = ServiceLocator::GetDatabase()->Query($command);

        if ($row = $reader->GetRow()) {
            $reader->Free();
            return $this->LoadById($row[ColumnNames::USER_ID]);
        }

        $reader->Free();
        return null;
    }

    /**
     * @param $userId int
     * @param $user User
     */
    private function LoadAttributes($userId, $user)
    {
        $getAttributes = new GetAttributeValuesCommand($userId, CustomAttributeCategory::USER);
        $attributeReader = ServiceLocator::GetDatabase()->Query($getAttributes);

        while ($attributeRow = $attributeReader->GetRow()) {
            $user->WithAttribute(
                new AttributeValue($attributeRow[ColumnNames::ATTRIBUTE_ID], $attributeRow[ColumnNames::ATTRIBUTE_VALUE]),
                $attributeRow[ColumnNames::ATTRIBUTE_ADMIN_ONLY]
            );
        }

        $attributeReader->Free();
    }

    public function AddActivation(User $user, $activationCode)
    {
        ServiceLocator::GetDatabase()->ExecuteInsert(new AddAccountActivationCommand($user->Id(), $activationCode));
    }

    /**
     * @param string $activationCode
     * @return int|null
     */
    public function FindUserIdByCode($activationCode)
    {
        $reader = ServiceLocator::GetDatabase()->Query(new GetUserIdByActivationCodeCommand($activationCode));
        if ($row = $reader->GetRow()) {
            $reader->Free();
            return $row[ColumnNames::USER_ID];
        }

        $reader->Free();

        return null;
    }

    /**
     * @param string $activationCode
     * @return void
     */
    public function DeleteActivation($activationCode)
    {
        ServiceLocator::GetDatabase()->Execute(new DeleteAccountActivationCommand($activationCode));
    }

    /**
     * @param int $userId
     * @return array|UserGroup[]
     */
    private function LoadOwnedGroups($userId)
    {
        $groups = [];
        $reader = ServiceLocator::GetDatabase()->Query(new GetGroupsIManageCommand($userId));
        while ($row = $reader->GetRow()) {
            $groups[] = new UserGroup($row[ColumnNames::GROUP_ID], $row[ColumnNames::GROUP_NAME]);
        }

        $reader->Free();
        return $groups;
    }

    public function UserExists($emailAddress, $userName)
    {
        $reader = ServiceLocator::GetDatabase()->Query(new CheckUserExistenceCommand($userName, $emailAddress));

        if ($row = $reader->GetRow()) {
            $reader->Free();
            return $row[ColumnNames::USER_ID];
        }

        $reader->Free();

        return null;
    }

    public function GetCount()
    {
        $reader = ServiceLocator::GetDatabase()->Query(new GetUserCountCommand());

        if ($row = $reader->GetRow()) {
            $reader->Free();
            return $row['count'];
        }

        $reader->Free();
        return 0;
    }
}

class UserDto
{
    public $UserId;
    public $FirstName;
    public $LastName;
    public $FullName;
    public $EmailAddress;
    public $Timezone;
    public $LanguageCode;
    public $Preferences;
    public $CurrentCreditCount;

    public function __construct(
        $userId,
        $firstName,
        $lastName,
        $emailAddress,
        $timezone = null,
        $languageCode = null,
        $preferences = null,
        $currentCreditCount = null
    ) {
        $this->UserId = $userId;
        $this->FirstName = $firstName;
        $this->LastName = $lastName;
        $this->EmailAddress = $emailAddress;
        $this->Timezone = $timezone;
        $this->LanguageCode = $languageCode;
        $name = new FullName($this->FirstName(), $this->LastName());
        $this->FullName = $name->__toString() . " ({$this->EmailAddress})";
        $this->Preferences = UserPreferences::Parse($preferences)->All();
        $this->CurrentCreditCount = $currentCreditCount;
    }

    public function Id()
    {
        return $this->UserId;
    }

    public function FirstName()
    {
        return $this->FirstName;
    }

    public function LastName()
    {
        return $this->LastName;
    }

    public function FullName()
    {
        return $this->FullName;
    }

    public function EmailAddress()
    {
        return $this->EmailAddress;
    }

    public function Timezone()
    {
        return $this->Timezone;
    }

    public function Language()
    {
        return $this->LanguageCode;
    }

    public function CurrentCreditCount()
    {
        return $this->CurrentCreditCount;
    }

    public function GetPreference($preferenceName)
    {
        return $this->Preferences[$preferenceName];
    }
}

class NullUserDto extends UserDto
{
    public function __construct()
    {
        parent::__construct(0, null, null, null, null, null, null, null);
    }

    public function FullName()
    {
        return null;
    }
}

class UserItemView
{
    public $Id;
    public $Username;
    public $First;
    public $Last;
    public $Email;
    public $Phone;
    /**
     * @var Date
     */
    public $DateCreated;
    /**
     * @var Date
     */
    public $LastLogin;
    public $StatusId;
    public $Timezone;
    public $Organization;
    public $Position;
    public $Language;
    public $ReservationColor;
    /**
     * @var CustomAttributes
     */
    public $Attributes;
    /**
     * @var UserPreferences
     */
    public $Preferences;

    /**
     * @var int
     */
    public $CurrentCreditCount;

    /**
     * @var int[]
     */
    public $GroupIds = [];

    public function __construct()
    {
        $this->Attributes = new CustomAttributes();
    }

    public function IsActive()
    {
        return $this->StatusId == AccountStatus::ACTIVE;
    }

    public static function Create($row)
    {
        $user = new UserItemView();

        $user->Id = $row[ColumnNames::USER_ID];
        $user->Username = $row[ColumnNames::USERNAME];
        $user->First = $row[ColumnNames::FIRST_NAME];
        $user->Last = $row[ColumnNames::LAST_NAME];
        $user->Email = $row[ColumnNames::EMAIL];
        $user->Phone = $row[ColumnNames::PHONE_NUMBER];
        $user->DateCreated = Date::FromDatabase($row[ColumnNames::USER_CREATED]);
        $user->LastLogin = Date::FromDatabase($row[ColumnNames::LAST_LOGIN]);
        $user->StatusId = $row[ColumnNames::USER_STATUS_ID];
        $user->Timezone = $row[ColumnNames::TIMEZONE_NAME];
        $user->Organization = $row[ColumnNames::ORGANIZATION];
        $user->Position = $row[ColumnNames::POSITION];
        $user->Language = $row[ColumnNames::LANGUAGE_CODE];

        if (isset($row[ColumnNames::ATTRIBUTE_LIST])) {
            $user->Attributes = CustomAttributes::Parse($row[ColumnNames::ATTRIBUTE_LIST]);
        } else {
            $user->Attributes = new CustomAttributes();
        }

        if (isset($row[ColumnNames::USER_PREFERENCES])) {
            $preferences = UserPreferences::Parse($row[ColumnNames::USER_PREFERENCES]);
            if (!empty($preferences)) {
                $user->ReservationColor = $preferences->Get(UserPreferences::RESERVATION_COLOR);
            }
            $user->Preferences = $preferences;
        } else {
            $user->Preferences = new UserPreferences();
        }

        $user->CurrentCreditCount = isset($row[ColumnNames::CREDIT_COUNT]) ? $row[ColumnNames::CREDIT_COUNT] : '';

        if (isset($row[ColumnNames::GROUP_IDS])) {
            $user->GroupIds = explode('!sep!', $row[ColumnNames::GROUP_IDS]);
        }

        return $user;
    }

    /**
     * @param $attributeId int
     * @return null|string
     */
    public function GetAttributeValue($attributeId)
    {
        return $this->Attributes->Get($attributeId);
    }
}

class UserPermissionItemView extends UserItemView
{
    public $PermissionType;

    public function __construct()
    {
        parent::__construct();
        $this->PermissionType = ResourcePermissionType::None;
    }

    public function PermissionType()
    {
        return $this->PermissionType;
    }

    public static function Create($row)
    {
        $item = UserItemView::Create($row);
        $me = new UserPermissionItemView();

        foreach (get_object_vars($item) as $key => $value) {
            $me->$key = $value;
        }

        $me->PermissionType = $row[ColumnNames::PERMISSION_TYPE];

        return $me;
    }
}
