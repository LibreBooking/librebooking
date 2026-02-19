<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

class FakeAuth implements IAuthentication
{
    /**
     * @var string
     */
    public $_LastLogin;
    public $_LastPassword;

    /**
     * @var ILoginContext
     */
    public $_LastLoginContext;
    public $_LastLoginId;

    public $_LoginCalled = false;
    public $_LogoutCalled = false;
    public $_HandleLoginFailureCalled = false;

    public $_ValidateResult = false;

    public $_ShowUsernamePrompt = false;
    public $_ShowPasswordPrompt = false;
    public $_ShowPersistLoginPrompt = false;
    public $_ShowForgotPasswordPrompt = false;

    /**
     * @var UserSession
     */
    public $_Session;

    public function Validate($username, $password)
    {
        $this->_LastLogin = $username;
        $this->_LastPassword = $password;

        return $this->_ValidateResult;
    }

    public function Login($username, $context)
    {
        $this->_LoginCalled = true;
        $this->_LastLogin = $username;
        $this->_LastLoginContext = $context;
        return $this->_Session;
    }

    public function Logout(UserSession $user)
    {
        $this->_LogoutCalled = true;
    }

    public function AreCredentialsKnown()
    {
        return true;
    }

    public function HandleLoginFailure(IAuthenticationPage $loginPage)
    {
        $this->_HandleLoginFailureCalled = true;
    }

    /**
     * @return bool
     */
    public function ShowUsernamePrompt()
    {
        return $this->_ShowUsernamePrompt;
    }

    /**
     * @return bool
     */
    public function ShowPasswordPrompt()
    {
        return $this->_ShowPasswordPrompt;
    }

    /**
     * @return bool
     */
    public function ShowPersistLoginPrompt()
    {
        return $this->_ShowPersistLoginPrompt;
    }

    /**
     * @return bool
     */
    public function ShowForgotPasswordPrompt()
    {
        return $this->_ShowForgotPasswordPrompt;
    }

    /**
     * @return bool
     */
    public function AllowUsernameChange()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function AllowEmailAddressChange()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function AllowPasswordChange()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function AllowNameChange()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function AllowPhoneChange()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function AllowOrganizationChange()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function AllowPositionChange()
    {
        return true;
    }
}
