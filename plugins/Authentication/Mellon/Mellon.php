<?php

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
require_once(ROOT_DIR . 'plugins/Authentication/Mellon/namespace.php');

/**
 * Provides Apache mod_auth_mellon authentication/synchronization for Booked.
 * @see IAuthorization
 */
class Mellon extends Authentication implements IAuthentication
{
    private $options;

    private $authToDecorate;
    private $_registration;

    private function GetRegistration()
    {
        if ($this->_registration == null) {
            $this->_registration = new Registration();
        }

        return $this->_registration;
    }

    public function __construct(Authentication $authentication)
    {
        $this->options = new MellonOptions();

        $this->authToDecorate = $authentication;
    }

    public function Validate($username, $password)
    {
        $username = $_SERVER['REMOTE_USER'];
        return true;
    }

    public function Login($username, $loginContext)
    {
        $username = $_SERVER['REMOTE_USER'];

        Log::Debug('Attempting Mellon login for username: %s', $username);

        $this->Synchronize($username, $loginContext);

        return $this->authToDecorate->Login($username, $loginContext);
    }

    public function Logout(UserSession $user)
    {
        $this->authToDecorate->Logout($user);
    }

    public function Synchronize($username, $loginContext)
    {
        $registration = $this->GetRegistration();
        $registration->Synchronize(
            new AuthenticatedUser(
                            $username,
                            $username . '@' . $this->options->EmailDomain(),
                            $_SERVER[$this->options->KeyGivenName()],
                            $_SERVER[$this->options->KeySurname()],
                            BookedStringHelper::Random(12),
                            Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE),
                            Configuration::Instance()->GetDefaultTimezone(),
                            null,
                            null,
                            null,
                            $this->GetGroups($loginContext)
                        )
        );
    }

    public function GetGroups($attributes)
    {
        $groups = [];

        $groupMappings = $this->options->GroupMappings();
        $mellonGroups = explode(';', $_SERVER[$this->options->KeyGroups()]);
        foreach ($mellonGroups as $mellonGroup) {
            if (!array_key_exists($mellonGroup, $groupMappings)) {
                continue;
            }

            array_push($groups, $groupMappings[$mellonGroup]);
        }

        return $groups;
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
