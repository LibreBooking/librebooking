<?php

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

class Apache extends Authentication implements IAuthentication
{
    private $authToDecorate;

    public function __construct(Authentication $authentication)
    {
        $this->authToDecorate = $authentication;
    }

    public function Validate($username, $password)
    {
        ## Get username for apache configuration and if Kerberus is used, the REALM is removed
        $ru = explode('@', $_SERVER['REMOTE_USER']);
        $user = $ru[0];
        return true;
    }

    public function Login($username, $loginContext)
    {
        ## Get username for apache configuration and if Kerberus is used, the REALM is removed
        $ru = explode('@', $_SERVER['REMOTE_USER']);
        $username = $ru[0];
        return $this->authToDecorate->Login($username, $loginContext);
    }

    public function Logout(UserSession $user)
    {
        $this->authToDecorate->Logout($user);
    }

    public function AreCredentialsKnown()
    {
        return (bool)$_SERVER['REMOTE_USER'];
    }

    public function ShowUsernamePrompt()
    {
        return false;
    }

    public function ShowPasswordPrompt()
    {
        return false;
    }

    public function ShowPersistLoginPrompt()
    {
        return false;
    }

    public function ShowForgotPasswordPrompt()
    {
        return false;
    }
}
