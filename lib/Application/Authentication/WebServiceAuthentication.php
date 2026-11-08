<?php

require_once(ROOT_DIR . 'Domain/Access/UserSessionRepository.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

interface IWebServiceAuthentication
{
    /**
     * @abstract
     * @param string $username
     * @param string $password
     * @return bool If user is valid
     */
    public function Validate($username, $password);

    /**
     * @abstract
     * @param string $username
     * @return WebServiceUserSession|NullUserSession NullUserSession when login fails
     */
    public function Login($username): UserSession;

    /**
     * @param int|string $userId internal user ID returned at sign-in
     * @param string $sessionToken
     * @return void
     */
    public function Logout($userId, $sessionToken);
}

class WebServiceAuthentication implements IWebServiceAuthentication
{
    private $authentication;
    private $userSessionRepository;

    /**
     * @param IAuthentication $authentication
     * @param IUserSessionRepository $userSessionRepository
     */
    public function __construct(IAuthentication $authentication, IUserSessionRepository $userSessionRepository)
    {
        $this->authentication = $authentication;
        $this->userSessionRepository = $userSessionRepository;
    }

    /**
     * @param string $username
     * @param string $password
     * @return bool If user is valid
     */
    public function Validate($username, $password)
    {
        return $this->authentication->Validate($username, $password);
    }

    /**
     * @param string $username
     * @return WebServiceUserSession|NullUserSession
     */
    public function Login($username): UserSession
    {
        Log::Debug('Web Service Login with username: %s', $username);
        $userSession = $this->authentication->Login($username, new WebServiceLoginContext());
        if ($userSession->IsLoggedIn()) {
            $webSession = WebServiceUserSession::FromSession($userSession);
            $existingSession = $this->userSessionRepository->LoadBySessionToken($webSession->SessionToken);

            if ($existingSession == null) {
                $this->userSessionRepository->Add($webSession);
            } else {
                $this->userSessionRepository->Update($webSession);
            }

            return $webSession;
        }

        return new NullUserSession();
    }

    /**
     * @param int|string $userId
     * @param string $sessionToken
     * @return void
     */
    public function Logout($userId, $sessionToken)
    {
        Log::Debug('Logout sessionToken: %s', $sessionToken);

        $webSession = $this->userSessionRepository->LoadBySessionToken($sessionToken);
        if ($webSession != null && $webSession->UserId == $userId) {
            $this->userSessionRepository->Delete($webSession);
            $this->authentication->Logout($webSession);
        }
    }
}

class WebServiceLoginContext implements ILoginContext
{
    /**
     * @return LoginData
     */
    public function GetData()
    {
        return new LoginData(false, null);
    }
}
