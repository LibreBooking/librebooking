<?php

declare(strict_types=1);

namespace LibreBooking\Application\Authentication;

use CSRFToken;
use IRoleService;
use User;
use UserSession;

/**
 * Builds a UserSession from a user's data.
 *
 * Depends on legacy global classes (User, UserSession, CSRFToken, IRoleService);
 * callers must have loaded them through the legacy include chain.
 */
class UserSessionBuilder
{
    public function __construct(private readonly IRoleService $roleService)
    {
    }

    /**
     * Builds a UserSession from the user's current data. Does not persist or change the user.
     */
    public function buildUserSession(User $user, string $loginTime): UserSession
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
