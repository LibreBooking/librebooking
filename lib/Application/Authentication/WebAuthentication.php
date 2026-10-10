<?php

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

interface IWebAuthentication extends IAuthenticationPromptOptions
{
    /**
     * @param string $username
     * @param string $password
     * @return bool If user is valid
     */
    public function Validate($username, $password);

    /**
     * @param string $username
     * @param ILoginContext $loginContext
     * @return void
     */
    public function Login($username, $loginContext);

    /**
     * @param UserSession $user
     * @return void
     */
    public function Logout(UserSession $user);

    /**
     * @param UserSession $user
     * @return void
     */
    public function postLogout(UserSession $user);

    /**
     * @param ILoginPage $loginPage
     * @return void
     */
    public function HandleLoginFailure(ILoginPage $loginPage);

    /**
     * @return bool
     */
    public function AreCredentialsKnown();

    /**
     * @return mixed
     */
    public function IsLoggedIn();

    /**
     * @return string
     */
    public function GetRegistrationUrl();

    /**
     * @return string
     */
    public function GetPasswordResetUrl();
}

class WebAuthentication implements IWebAuthentication
{
    private $authentication;
    private $server;

    /**
     * @param IAuthentication $authentication
     * @param Server $server
     */
    public function __construct(IAuthentication $authentication, $server = null)
    {
        $this->authentication = $authentication;
        $this->server = $server;
        if ($this->server == null) {
            $this->server = ServiceLocator::GetServer();
        }
    }

    /**
     * @param string $username
     * @param string $password
     * @return bool If user is valid
     */
    public function Validate($username, $password)
    {
        if (empty($password) && !$this->authentication->AreCredentialsKnown()) {
            return false;
        }

        return $this->authentication->Validate($username, $password);
    }

    /**
     * @param string $username
     * @param ILoginContext $loginContext
     * @return void
     */
    public function Login($username, $loginContext)
    {
        $userSession = $this->authentication->Login($username, $loginContext);
        $this->server->SetUserSession($userSession);
    }

    /**
     * @param UserSession $userSession
     * @return void
     */
    public function Logout(UserSession $userSession)
    {
        $this->authentication->Logout($userSession);
        Log::Debug('Logout userId: %s', $userSession->UserId);

        $this->DeletePersistLoginCookie();
        ServiceLocator::GetServer()->EndSession(SessionKeys::USER_SESSION);
    }

    /**
     * @param UserSession $userSession
     * @return void
     */
    public function postLogout(UserSession $userSession)
    {
        // postLogout() is not part of IAuthentication; only some plugins provide it.
        if (method_exists($this->authentication, 'postLogout')) {
            $this->authentication->postLogout($userSession);
        }
        Log::Debug('Logout userId: %s', $userSession->UserId);

        $this->DeletePersistLoginCookie();
        ServiceLocator::GetServer()->EndSession(SessionKeys::USER_SESSION);
    }

    /**
     * Expires a persistent login cookie left over from an earlier release. Its value is never read.
     */
    private function DeletePersistLoginCookie(): void
    {
        $this->server->DeleteCookie(new Cookie(CookieKeys::PERSIST_LOGIN, ''));
    }

    public function HandleLoginFailure(ILoginPage $loginPage)
    {
        $this->authentication->HandleLoginFailure(new WebAuthenticationPage($loginPage));
    }

    public function AreCredentialsKnown()
    {
        return $this->authentication->AreCredentialsKnown();
    }

    public function ShowUsernamePrompt()
    {
        return $this->authentication->ShowUsernamePrompt();
    }

    public function ShowPasswordPrompt()
    {
        return $this->authentication->ShowPasswordPrompt();
    }

    /**
     * @deprecated Persistent login was removed. The result is no longer used.
     */
    public function ShowPersistLoginPrompt()
    {
        return $this->authentication->ShowPersistLoginPrompt();
    }

    public function ShowForgotPasswordPrompt()
    {
        return $this->authentication->ShowForgotPasswordPrompt();
    }

    public function IsLoggedIn()
    {
        return $this->server->GetUserSession()->IsLoggedIn();
    }

    public function GetRegistrationUrl()
    {
        $url = '';
        if (method_exists($this->authentication, 'GetRegistrationUrl')) {
            $url = $this->authentication->GetRegistrationUrl();
        }

        return $url;
    }

    public function GetPasswordResetUrl()
    {
        $url = '';
        if (method_exists($this->authentication, 'GetPasswordResetUrl')) {
            $url = $this->authentication->GetPasswordResetUrl();
        }

        return $url;
    }
}

class WebAuthenticationPage implements IAuthenticationPage
{
    /**
     * @var ILoginPage
     */
    private $page;

    public function __construct(ILoginPage $page)
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function GetEmailAddress()
    {
        return $this->page->GetEmailAddress();
    }

    /**
     * @return string
     */
    public function GetPassword()
    {
        return $this->page->GetPassword();
    }

    /**
     * @return void
     */
    public function SetShowLoginError()
    {
        $this->page->SetShowLoginError();
    }
}
