<?php

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Config/namespace.php');

interface IImpersonationService
{
    /**
     * Switch the current admin session to the target user, stashing the admin's session.
     * @return bool false if denied
     */
    public function StartImpersonation(User $targetUser): bool;

    /** Restore the stashed admin session; no-op if not impersonating. */
    public function StopImpersonation(): void;

    public function IsImpersonating(): bool;

    public function GetImpersonator(): ?UserSession;
}

class ImpersonationService implements IImpersonationService
{
    public function __construct(
        private IRoleService $roleService,
        private IUserRepository $userRepository,
    ) {
    }

    public function StartImpersonation(User $targetUser): bool
    {
        $adminSession = ServiceLocator::GetServer()->GetUserSession();

        if (!$adminSession->IsAdmin) {
            Log::Error('StartImpersonation denied. UserId=%s is not an application administrator.', $adminSession->UserId);
            return false;
        }

        if (!Configuration::Instance()->GetKey(ConfigKeys::ADMIN_IMPERSONATION_ENABLED, new BooleanConverter())) {
            Log::Error('StartImpersonation denied. Feature disabled. UserId=%s', $adminSession->UserId);
            return false;
        }

        if ($targetUser->Id() == $adminSession->UserId) {
            return false;
        }

        if (
            $targetUser->Id() === null ||
            $targetUser->StatusId() != AccountStatus::ACTIVE ||
            $this->roleService->IsApplicationAdministrator($targetUser)
        ) {
            Log::Error(
                'StartImpersonation denied. Target invalid, inactive, or an administrator. UserId=%s, Target UserId=%s',
                $adminSession->UserId,
                $targetUser->Id()
            );
            return false;
        }

        $targetSession = (new UserSessionFactory($this->roleService))->Create($targetUser, LoginTime::Now());

        $server = ServiceLocator::GetServer();
        $server->SetSession(SessionKeys::IMPERSONATOR_SESSION, $adminSession);
        $server->SetUserSession($targetSession);
        Log::Debug('UserId=%s started impersonating UserId=%s', $adminSession->UserId, $targetUser->Id());

        return true;
    }

    public function StopImpersonation(): void
    {
        $adminSession = $this->GetImpersonator();
        if ($adminSession === null) {
            return;
        }

        $server = ServiceLocator::GetServer();

        // Re-derive rather than restore: the admin's roles may have changed. Also new CSRF token.
        $admin = $this->userRepository->LoadById($adminSession->UserId);
        if ($admin->Id() === null || $admin->StatusId() != AccountStatus::ACTIVE) {
            $server->EndSession(SessionKeys::USER_SESSION);
            return;
        }

        $userSessionFactory = new UserSessionFactory($this->roleService);
        $restoredSession = $userSessionFactory->Create($admin, $adminSession->LoginTime);

        $server->SetUserSession($restoredSession);
        // Not EndSession(): that destroys the whole PHP session.
        $server->SetSession(SessionKeys::IMPERSONATOR_SESSION, null);
    }

    public function IsImpersonating(): bool
    {
        return $this->GetImpersonator() !== null;
    }

    public function GetImpersonator(): ?UserSession
    {
        $session = ServiceLocator::GetServer()->GetSession(SessionKeys::IMPERSONATOR_SESSION);
        return $session instanceof UserSession ? $session : null;
    }
}
