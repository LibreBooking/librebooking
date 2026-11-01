<?php

use LibreBooking\Application\Authentication\UserSessionBuilder;

interface IGuestUserService
{
    /**
     * Returns a session for the active account with this email, creating a guest account
     * when no account exists. Returns a NullUserSession when an account exists but is not
     * active.
     *
     * @param string $email
     * @return UserSession
     */
    public function CreateOrLoad($email);

    /**
     * @param $email
     * @return bool whether an active account with this email exists
     */
    public function EmailExists($email);
}

class GuestUserService implements IGuestUserService
{
    /**
     * @var IAuthentication
     */
    private $authentication;

    /**
     * @var IRegistration
     */
    private $registration;

    public function __construct(
        IAuthentication $authentication,
        IRegistration $registration,
        private readonly IUserRepository $userRepository,
        private readonly UserSessionBuilder $userSessionBuilder
    ) {
        $this->authentication = $authentication;
        $this->registration = $registration;
    }

    public function CreateOrLoad($email)
    {
        // An existing account is only read here. The caller has not authenticated as that
        // user, so this must not log them in or change their account in any way.
        $user = $this->userRepository->LoadByUsername($email);

        if ($this->Exists($user)) {
            if (!$this->IsActive($user)) {
                Log::Debug('Account is not active, not creating a guest account %s', $email);

                return new NullUserSession();
            }

            Log::Debug('User already has account, skipping guest creation %s', $email);

            return $this->userSessionBuilder->buildUserSession(user: $user, loginTime: LoginTime::Now());
        }

        Log::Debug('Email address was not found, creating guest account %s', $email);

        $currentLanguage = Resources::GetInstance()->CurrentLanguage;
        $this->registration->Register($email, $email, 'Guest', 'Guest', Password::GenerateRandom(), null, $currentLanguage, null);
        return $this->authentication->Login($email, new WebLoginContext(new LoginData(false, $currentLanguage)));
    }

    public function EmailExists($email)
    {
        $user = $this->userRepository->LoadByUsername($email);

        return $this->Exists($user) && $this->IsActive($user);
    }

    private function Exists(User $user): bool
    {
        return !empty($user->Id());
    }

    private function IsActive(User $user): bool
    {
        return $user->StatusId() == AccountStatus::ACTIVE;
    }
}
