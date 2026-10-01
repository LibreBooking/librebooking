<?php

require_once(ROOT_DIR . 'lib/Application/Authentication/CSRFToken.php');

/**
 * Builds a UserSession for a known User. Does no credential check: only call
 * after a successful login or an authorized impersonation.
 */
class UserSessionFactory
{
    public function __construct(
        private IRoleService $roleService,
    ) {
    }

    /**
     * @param User $user
     * @param string $loginTime
     * @return UserSession
     */
    public function Create(User $user, $loginTime)
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
}
