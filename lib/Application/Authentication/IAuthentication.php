<?php

interface IAuthenticationPage
{
    /**
     * @return string
     */
    public function GetEmailAddress();

    /**
     * @return string
     */
    public function GetPassword();

    /**
     * @return void
     */
    public function SetShowLoginError();
}

interface IAuthentication extends IAuthenticationPromptOptions, IAuthenticationActionOptions
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
     * @param ILoginContext $loginContext
     * @return UserSession
     */
    public function Login($username, $loginContext);

    /**
     * @param UserSession $user
     * @return void
     */
    public function Logout(UserSession $user);

    /**
     * @return bool
     */
    public function AreCredentialsKnown();

    /**
     * @param IAuthenticationPage $loginPage
     * @return void
     */
    public function HandleLoginFailure(IAuthenticationPage $loginPage);

    /**
     * Build a UserSession for the given username without recording the login event.
     * Unlike Login(), this does NOT update the user's last-login timestamp in the
     * database. Use this for machine-client endpoints (e.g. calendar feed pollers)
     * that authenticate on every request.
     *
     * @param string $username
     * @return UserSession
     */
    public function BuildSession(string $username): UserSession;
}

interface IAuthenticationPromptOptions
{
    /**
     * @abstract
     * @return bool
     */
    public function ShowUsernamePrompt();

    /**
     * @abstract
     * @return bool
     */
    public function ShowPasswordPrompt();

    /**
     * @abstract
     * @return bool
     */
    public function ShowPersistLoginPrompt();

    /**
     * @abstract
     * @return bool
     */
    public function ShowForgotPasswordPrompt();
}

interface IAuthenticationActionOptions
{
    /**
     * @return bool
     */
    public function AllowUsernameChange();

    /**
     * @return bool
     */
    public function AllowEmailAddressChange();

    /**
     * @return bool
     */
    public function AllowPasswordChange();

    /**
     * @return bool
     */
    public function AllowNameChange();

    /**
     * @return bool
     */
    public function AllowPhoneChange();

    /**
     * @return bool
     */
    public function AllowOrganizationChange();

    /**
     * @return bool
     */
    public function AllowPositionChange();
}
