<?php

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'lib/Database/namespace.php');
require_once(ROOT_DIR . 'lib/Database/Commands/namespace.php');
require_once(ROOT_DIR . 'Domain/Values/RoleLevel.php');

class Authentication implements IAuthentication
{
    /**
     * @var PasswordMigration
     */
    private $passwordMigration = null;

    /**
     * @var IRoleService
     */
    private $roleService;

    /**
     * @var IUserRepository
     */
    private $userRepository;

    /**
     * @var IFirstRegistrationStrategy
     */
    private $firstRegistration;
    /**
     * @var IGroupRepository
     */
    private $groupRepository;

    public function __construct(IRoleService $roleService, IUserRepository $userRepository, IGroupRepository $groupRepository)
    {
        $this->roleService = $roleService;
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
    }

    public function SetMigration(PasswordMigration $migration)
    {
        $this->passwordMigration = $migration;
    }

    /**
     * @return PasswordMigration
     */
    private function GetMigration()
    {
        if (is_null($this->passwordMigration)) {
            $this->passwordMigration = new PasswordMigration();
        }

        return $this->passwordMigration;
    }

    public function SetFirstRegistrationStrategy(IFirstRegistrationStrategy $migration)
    {
        $this->firstRegistration = $migration;
    }

    /**
     * @return IFirstRegistrationStrategy
     */
    private function GetFirstRegistrationStrategy()
    {
        if (is_null($this->firstRegistration)) {
            $this->firstRegistration = new SetAdminFirstRegistrationStrategy();
        }

        return $this->firstRegistration;
    }

    public function Validate($username, $passwordPlainText)
    {
        if (($this->ShowUsernamePrompt() && empty($username)) || ($this->ShowPasswordPrompt() && empty($passwordPlainText))) {
            return false;
        }

        Log::Debug('Trying to log in as: %s', $username);

        $command = new AuthorizationCommand($username);
        $reader = ServiceLocator::GetDatabase()->Query($command);
        $valid = false;

        if ($row = $reader->GetRow()) {
            Log::Debug('User was found: %s', $username);
            $migration = $this->GetMigration();
            $password = $migration->Create($passwordPlainText, $row[ColumnNames::OLD_PASSWORD], $row[ColumnNames::PASSWORD]);
            $salt = $row[ColumnNames::SALT];

            if ($password->Validate($salt)) {
                $password->Migrate($row[ColumnNames::USER_ID]);
                $valid = true;
            }
        }

        if ($valid) {
            Log::Debug('Successful user authentication for %s', $username);
        } else {
            Log::Error('Failed user authentication for %s', $username);
        }

        return $valid;
    }

    public function Login($username, $loginContext)
    {
        Log::Debug('Logging in with user: %s', $username);

        $user = $this->userRepository->LoadByUsername($username);
        if ($user->StatusId() == AccountStatus::ACTIVE) {
            $loginData = $loginContext->GetData();
            $loginTime = LoginTime::Now();
            $language = $user->Language();

            if (!empty($loginData->Language)) {
                $language = $loginData->Language;
            }

            $user->Login($loginTime, $language);
            $this->userRepository->Update($user);

            $user = $this->GetFirstRegistrationStrategy()->HandleLogin($user, $this->userRepository, $this->groupRepository);

            return $this->GetUserSession($user, $loginTime);
        }

        return new NullUserSession();
    }

    public function Logout(UserSession $userSession)
    {
        // hook for implementing Logout logic
    }

    public function AreCredentialsKnown()
    {
        return false;
    }

    public function HandleLoginFailure(IAuthenticationPage $loginPage)
    {
        $loginPage->SetShowLoginError();
    }

    public function BuildSession(string $username): UserSession
    {
        $user = $this->userRepository->LoadByUsername($username);
        if ($user->StatusId() == AccountStatus::ACTIVE) {
            return $this->GetUserSession($user, LoginTime::Now());
        }
        return new NullUserSession();
    }

    /**
     * @param User $user
     * @param string $loginTime
     * @return UserSession
     */
    protected function GetUserSession(User $user, $loginTime)
    {
        $userSession = new UserSession($user->Id());
        $userSession->Email = $user->EmailAddress();
        $userSession->FirstName = $user->FirstName();
        $userSession->LastName = $user->LastName();
        $userSession->Timezone = $user->Timezone();
        $userSession->HomepageId = $user->Homepage();
        $userSession->LanguageCode = $user->Language();
        $userSession->LoginTime = $loginTime;
        $userSession->PublicId = $user->GetPublicId();
        $userSession->ScheduleId = $user->GetDefaultScheduleId();

        $userSession->IsAdmin = $this->roleService->IsApplicationAdministrator($user);
        $userSession->IsGroupAdmin = $this->roleService->IsGroupAdministrator($user);
        $userSession->IsResourceAdmin = $this->roleService->IsResourceAdministrator($user);
        $userSession->IsScheduleAdmin = $this->roleService->IsScheduleAdministrator($user);
        $userSession->CSRFToken = CSRFToken::Create();

        foreach ($user->Groups() as $group) {
            $userSession->Groups[] = $group->GroupId;
        }

        foreach ($user->GetAdminGroups() as $group) {
            $userSession->AdminGroups[] = $group->GroupId;
        }

        return $userSession;
    }

    public function ShowUsernamePrompt()
    {
        return true;
    }

    public function ShowPasswordPrompt()
    {
        return true;
    }

    public function ShowPersistLoginPrompt()
    {
        return true;
    }

    public function ShowForgotPasswordPrompt()
    {
        return true;
    }

    public function AllowUsernameChange()
    {
        return true;
    }

    public function AllowEmailAddressChange()
    {
        return true;
    }

    public function AllowPasswordChange()
    {
        return true;
    }

    public function AllowNameChange()
    {
        return true;
    }

    public function AllowPhoneChange()
    {
        return true;
    }

    public function AllowOrganizationChange()
    {
        return true;
    }

    public function AllowPositionChange()
    {
        return true;
    }

    public function GetRegistrationUrl()
    {
        return '';
    }

    public function GetPasswordResetUrl()
    {
        return '';
    }
}
