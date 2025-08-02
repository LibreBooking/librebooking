<?php

/**
File in Authentication plugin package for ver 2.1.4 LibreBooking
to implement Single Sign On Capability.  Based on code from the
LibreBooking Authentication Ldap plugin as well as a SAML
Authentication plugin for Moodle 1.9+.
See http://moodle.org/mod/data/view.php?d=13&rid=2574
This plugin uses the SimpleSAMLPHP version 1.8.2 libraries.
http://simplesamlphp.org/
 */

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
require_once(ROOT_DIR . 'plugins/Authentication/Saml/namespace.php');

/**
 * Provides simpleSAMLphp authentication/synchronization for LibreBooking
 * @see IAuthorization
 */
class Saml extends Authentication implements IAuthentication
{
    /**
     * @var IAuthentication
     */
    private $authToDecorate;

    /**
     * @var AdSamlWrapper
     */
    private $saml;

    /**
     * @var SamlOptions
     */
    private $options;

    /**
     * @var IRegistration
     */
    private $_registration;

    /**
     * @var PasswordEncryption
     */
    private $_encryption;

    /**
     * @var SamlUser
     */
    private $user;

    /**
     * @var string
     *
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    public function SetRegistration($registration)
    {
        $this->_registration = $registration;
    }

    private function GetRegistration()
    {
        if ($this->_registration == null) {
            $this->_registration = new Registration();
        }

        return $this->_registration;
    }

    public function SetEncryption($passwordEncryption)
    {
        $this->_encryption = $passwordEncryption;
    }

    private function GetEncryption()
    {
        if ($this->_encryption == null) {
            $this->_encryption = new PasswordEncryption();
        }

        return $this->_encryption;
    }


    /**
     * @param IAuthentication $authentication Authentication class to decorate
     * @param ISaml $samlImplementation The actual SAML implementation to work against
     * @param SamlOptions $samlOptions Options to use for SAML configuration
     */
    public function __construct(IAuthentication $authentication, $samlImplementation = null, $samlOptions = null)
    {
        $this->authToDecorate = $authentication;

        $this->options = $samlOptions;
        if ($samlOptions == null) {
            $this->options = new SamlOptions();
        }

        $this->saml = $samlImplementation;
        if ($samlImplementation == null) {
            $this->saml = new AdSamlWrapper($this->options);
        }
    }

    public function Validate($username, $password)
    {
        $this->saml->Connect();
        $isValid = $this->saml->Authenticate();

        if ($isValid) {
            $this->user = $this->saml->GetSamlUser();
            $userLoaded = $this->SamlUserExists();

            if (!$userLoaded) {
                Log::Error(
                    'Could not load user details from SinmpleSamlPhpSSO. Check your SSO settings. User: %s',
                    $username
                );
            }
            return $userLoaded;
        }

        return false;
    }

    public function Login($username, $loginContext)
    {
        $this->username = $username;
        if (empty($this->username)) {
            $this->username = $this->user->GetUserName();
        }
        if ($this->SamlUserExists()) {
            $this->Synchronize($this->username);
        }

        return $this->authToDecorate->Login($this->username, $loginContext);
    }

    public function Logout(UserSession $user)
    {
        $this->saml->Logout($user);
    }

    public function postLogout(UserSession $user)
    {
        $this->saml->Cleanup();
        // Continue logout process
        $this->authToDecorate->Logout($user);
    }

    public function AreCredentialsKnown()
    {
        return true;
    }

    private function SamlUserExists()
    {
        return $this->user != null;
    }

    private function Synchronize($username)
    {
        $registration = $this->GetRegistration();

        $registration->Synchronize(
            new AuthenticatedUser(
                $username,
                $this->user->GetEmail(),
                $this->user->GetFirstName(),
                $this->user->GetLastName(),
                $this->password,
                Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE),
                Configuration::Instance()->GetDefaultTimezone(),
                $this->user->GetPhone(),
                $this->user->GetInstitution(),
                $this->user->GetTitle(),
                $this->user->GetGroups()
            )
        );
    }

    public function ShowForgotPasswordPrompt()
    {
        return false;
    }

    public function ShowPasswordPrompt()
    {
        return true;
    }

    public function ShowPersistLoginPrompt()
    {
        return false;
    }

    public function ShowUsernamePrompt()
    {
        return true;
    }


    public function AllowUsernameChange()
    {
        return false;
    }

    public function AllowEmailAddressChange()
    {
        return false;
    }

    public function AllowPasswordChange()
    {
        return false;
    }

    public function AllowNameChange()
    {
        return false;
    }

    public function AllowPhoneChange()
    {
        return false;
    }

    public function AllowOrganizationChange()
    {
        return false;
    }

    public function AllowPositionChange()
    {
        return false;
    }
}
