<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/ReservationEvents.php');

class Registration implements IRegistration
{
    /**
     * @var PasswordEncryption
     */
    private $passwordEncryption;

    /**
     * @var IUserRepository
     */
    private $userRepository;

    /**
     * @var IRegistrationNotificationStrategy
     */
    private $notificationStrategy;

    /**
     * @var IRegistrationPermissionStrategy
     */
    private $permissionAssignmentStrategy;

    /**
     * @var IGroupViewRepository
     */
    private $groupRepository;

    public function __construct(
        $passwordEncryption = null,
        $userRepository = null,
        $notificationStrategy = null,
        $permissionAssignmentStrategy = null,
        $groupRepository = null
    ) {
        $this->passwordEncryption = $passwordEncryption;
        $this->userRepository = $userRepository;
        $this->notificationStrategy = $notificationStrategy;
        $this->permissionAssignmentStrategy = $permissionAssignmentStrategy;
        $this->groupRepository = $groupRepository;

        if ($passwordEncryption == null) {
            $this->passwordEncryption = new PasswordEncryption();
        }

        if ($userRepository == null) {
            $this->userRepository = new UserRepository();
        }

        if ($notificationStrategy == null) {
            $this->notificationStrategy = new RegistrationNotificationStrategy();
        }

        if ($permissionAssignmentStrategy == null) {
            $this->permissionAssignmentStrategy = new RegistrationPermissionStrategy();
        }

        if ($groupRepository == null) {
            $this->groupRepository = new GroupRepository();
        }
    }

    public function Register(
        $username,
        $email,
        $firstName,
        $lastName,
        $password,
        $timezone,
        $language,
        $homepageId,
        $additionalFields = [],
        $attributeValues = [],
        $groups = null,
        $acceptTerms = false
    ) {
        $homepageId = empty($homepageId) ? Pages::DEFAULT_HOMEPAGE_ID : $homepageId;
        $encryptedPassword = $this->passwordEncryption->EncryptPassword($password);
        $timezone = empty($timezone) ? Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_TIMEZONE) : $timezone;

        $attributes = new UserAttribute($additionalFields);

        if ($this->CreatePending()) {
            $user = User::CreatePending($firstName, $lastName, $email, $username, $language, $timezone, $encryptedPassword->EncryptedPassword(), $encryptedPassword->Salt(), $homepageId);
        } else {
            $user = User::Create($firstName, $lastName, $email, $username, $language, $timezone, $encryptedPassword->EncryptedPassword(), $encryptedPassword->Salt(), $homepageId);
        }

        $user->ChangeAttributes($attributes->Get(UserAttribute::Phone), $attributes->Get(UserAttribute::Organization), $attributes->Get(UserAttribute::Position));
        $user->ChangeCustomAttributes($attributeValues);
        $user->AcceptTerms($acceptTerms);

        if ($groups != null) {
            $user->WithGroups($groups);
        }

        if (Configuration::Instance()->GetKey(ConfigKeys::REGISTRATION_AUTO_SUBSCRIBE_EMAIL, new BooleanConverter())) {
            foreach (ReservationEvent::AllEvents() as $event) {
                $user->ChangeEmailPreference($event, true);
            }
        }

        $userId = $this->userRepository->Add($user);
        if ($user->Id() != $userId) {
            $user->WithId($userId);
        }
        $this->permissionAssignmentStrategy->AddAccount($user);
        $this->notificationStrategy->NotifyAccountCreated($user, $password);

        return $user;
    }

    /**
     * @return bool
     */
    protected function CreatePending()
    {
        return Configuration::Instance()->GetKey(ConfigKeys::REGISTRATION_REQUIRE_EMAIL_ACTIVATION, new BooleanConverter());
    }

    public function UserExists($loginName, $emailAddress)
    {
        $userId = $this->userRepository->UserExists($emailAddress, $loginName);

        return !empty($userId);
    }

    public function Synchronize(AuthenticatedUser $user, $insertOnly = false, $overwritePassword = true)
    {
        if ($this->UserExists($user->UserName(), $user->Email())) {
            if ($insertOnly) {
                return;
            }

            $password = null;
            $salt = null;

            if ($overwritePassword) {
                $encryptedPassword = $this->passwordEncryption->EncryptPassword($user->Password());
                $password = $encryptedPassword->EncryptedPassword();
                $salt = $encryptedPassword->Salt();
            }

            $command = new UpdateUserFromLdapCommand($user->UserName(), $user->Email(), $user->FirstName(), $user->LastName(), $password, $salt, $user->Phone(), $user->Organization(), $user->Title());
            ServiceLocator::GetDatabase()->Execute($command);

            if ($this->GetUserGroups($user) != null) {
                $updatedUser = $this->userRepository->LoadByUsername($user->Username());
                $updatedUser->ChangeGroups($this->GetUserGroups($user));
                $this->userRepository->Update($updatedUser);
            }
        } else {
            $defaultHomePageId = Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_HOMEPAGE, new IntConverter());
            $additionalFields = ['phone' => $user->Phone(), 'organization' => $user->Organization(), 'position' => $user->Title()];
            $this->Register(
                $user->UserName(),
                $user->Email(),
                $user->FirstName(),
                $user->LastName(),
                $user->Password(),
                $user->TimezoneName(),
                $user->LanguageCode(),
                empty($defaultHomePageId) ? Pages::DEFAULT_HOMEPAGE_ID : $defaultHomePageId,
                $additionalFields,
                [],
                $this->GetUserGroups($user)
            );
        }
    }

    /**
     * @param AuthenticatedUser $user
     * @return null|UserGroup[]
     */
    private function GetUserGroups(AuthenticatedUser $user)
    {
        $userGroups = $user->GetGroups();

        if (empty($userGroups)) {
            return null;
        }

        $groupsToSync = [];
        if ($userGroups != null) {
            $lowercaseGroups = array_map('strtolower', $userGroups);
            $altGroups = $userGroups;
            $altGroups= array_map(function($dn) {return sscanf(explode(",", $dn)[0], "cn=%s,")[0];}, $altGroups);

            $groupsToSync = [];
            $groups = $this->groupRepository->GetList()->Results();
            /** @var GroupItemView $group */
            foreach ($groups as $group) {
                if (in_array(strtolower($group->Name()), $lowercaseGroups)) {
                    Log::Debug('Syncing group %s for user %s', $group->Name(), $user->Username());
                    $groupsToSync[] = new UserGroup($group->Id(), $group->Name());
                } else {
                    if (in_array(strtolower($group->Name()), $altGroups)) {
                      Log::Debug('Syncing group %s for user %s', $group->Name(), $user->Username());
                      $groupsToSync[] = new UserGroup($group->Id(), $group->Name());
                    } else {
                      Log::Debug('User %s is not part of group %s, sync skipped', $user->Username(), $group->Name());
                    }
                }
            }
        }

        return $groupsToSync;
    }
}

class AdminRegistration extends Registration
{
    protected function CreatePending()
    {
        return false;
    }
}

class GuestRegistration extends Registration
{
    protected function CreatePending()
    {
        return false;
    }
}
