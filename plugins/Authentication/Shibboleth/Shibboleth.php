<?php
/**
 * @file Shibboleth.php
 */

require_once ROOT_DIR . 'lib/Application/Authentication/namespace.php';
require_once ROOT_DIR . 'plugins/Authentication/Shibboleth/namespace.php';

/**
 * Shibboleth implementation of LibreBooking's authentication interface.
 * Supports auto-account provisioning on first-time login.
 *
 * @see IAuthorization
 * @see Authentication
 */
class Shibboleth extends Authentication
{
    /**
     * @var IAuthentication
     */

    protected $authToDecorate;
    /**
     * @var Registration
     */
    protected $_registration;

    /**
     * @var ShibbolethUser
     */
    protected $_user;

    /**
     * @var ShibbolethOptions
     */
    protected $_config;

    /**
     * Constructor.
     *
     * @param IAuthentication $authentication Authentication class to decorate.
     */
    public function __construct(IAuthentication $authentication)
    {
        $this->authToDecorate = $authentication;
    }

    /*
     * @overrides Authentication::Validate()
     */
    public function Validate($username, $password)
    {
        $user = $this->GetShibbolethUser();
        $uid = $user->GetUsername();
        if (! empty($uid)) {
            return true;
        }
        return false;
    }

    /*
     * @overrides Authentication::Login()
     */
    public function Login($username, $loginContext)
    {
        $user = $this->GetShibbolethUser();
        $registration = $this->GetRegistration();

        // auto-provision new user accounts
        // or update existing user accounts with the attributes passed in from shibboleth
        $registration->Synchronize(
            new AuthenticatedUser(
                $user->GetUsername(),
                $user->GetEmailAddress(),
                $user->GetFirstName(),
                $user->GetLastName(),
                null,
                Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE),
                Configuration::Instance()->GetDefaultTimezone(),
                $user->GetPhone(),
                $user->GetOrganization(),
                null
            )
        );

        return $this->authToDecorate->Login($user->GetUsername(), $loginContext);
    }

    /*
     * @overrides Authentication::Logout()
     */
    public function Logout(UserSession $user)
    {
        $this->authToDecorate->Logout($user);
    }

    /*
     * @override Authentication::AreCredentialsKnown()
    */
    public function AreCredentialsKnown()
    {
        return true;
    }

    /*
     * @overrides Authentication::ShowUsernamePrompt()
     */
    public function ShowUsernamePrompt()
    {
        return false;
    }

    /*
     * @overrides Authentication::ShowPasswordPrompt()
     */
    public function ShowPasswordPrompt()
    {
        return false;
    }

    /*
     * @overrides Authentication::ShowPersistLoginPrompt
     */
    public function ShowPersistLoginPrompt()
    {
        return false;
    }

    /*
     * @return Authentication::ShowForgotPasswordPrompt
     */
    public function ShowForgotPasswordPrompt()
    {
        return false;
    }

    /*
     * @overrides Authentication::HandleLoginFailure
     */
    public function HandleLoginFailure(IAuthenticationPage $loginPage)
    {
        // not implemented
    }

    /**
     * Retrieves the registration instance.
     *
     * @return Registration
     */
    protected function GetRegistration()
    {
        if (! isset($this->_registration)) {
            $this->_registration = new Registration();
        }
        return $this->_registration;
    }


    /**
     * Retrieves the plugin runtime configuration.
     *
     * @return ShibbolethOptions
     */
    protected function GetConfiguration()
    {
        if (! isset($this->_config)) {
            $this->_config = new ShibbolethOptions();
        }
        return $this->_config;
    }

    /**
     * Retrieves the external user representation.
     *
     * @return ShibbolethUser
     */
    protected function GetShibbolethUser()
    {
        if (! isset($this->_user)) {
            // user attributes are passed in the global $_SERVER array, get them from there.
            $config =  $this->GetConfiguration();
            $this->_user = new ShibbolethUser($_SERVER, $config);
        }
        return $this->_user;
    }
}
