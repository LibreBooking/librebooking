<?php

// debugging tools / libs
if (file_exists(ROOT_DIR . 'vendor/autoload.php')) {
    require ROOT_DIR . 'vendor/autoload.php';
}

require_once(ROOT_DIR . 'Pages/Page.php');
require_once(ROOT_DIR . 'Pages/Authentication/ILoginBasePage.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

interface ILoginPage extends IPage, ILoginBasePage
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
     * @return bool
     */
    public function GetPersistLogin();

    public function GetShowRegisterLink();

    public function SetShowRegisterLink($value);

    public function SetShowScheduleLink($value);

    /**
     * @return string
     */
    public function GetSelectedLanguage();

    /**
     * @return string
     */
    public function GetRequestedLanguage();

    public function SetUseLogonName($value);

    public function SetResumeUrl($value);

    public function SetShowLoginError();

    /**
     * @param $languageCode string
     */
    public function SetSelectedLanguage($languageCode);

    /**
     * @param $shouldShow bool
     */
    public function ShowUsernamePrompt($shouldShow);

    /**
     * @param $shouldShow bool
     */
    public function ShowPasswordPrompt($shouldShow);

    /**
     * @param $shouldShow bool
     */
    public function ShowPersistLoginPrompt($shouldShow);

    /**
     * @param $shouldShow bool
     */
    public function ShowForgotPasswordPrompt($shouldShow);

    /**
     * @param $url string
     */
    public function SetRegistrationUrl($url);

    /**
     * @param $url string
     */
    public function SetPasswordResetUrl($url);

    /**
     * @return string
     */
    public function GetCaptcha();

    /**
     * @param Announcement[] $announcements
     */
    public function SetAnnouncements($announcements);

    /**
     *
     */
    public function SetGoogleUrl($URL);

    /**
     *
     */
    public function SetMicrosoftUrl($URL);

    /**
     *
     */
    public function SetFacebookUrl($URL);

    /**
     *
     */
    public function SetKeycloakUrl($URL);

    /**
     *
     */
    public function SetOauth2Url($URL);
    public function SetOauth2Name($Name);
}

class LoginPage extends Page implements ILoginPage
{
    protected $presenter = null;

    public function __construct()
    {
        parent::__construct('LogIn'); // parent Page class

        $this->presenter = new LoginPresenter($this); // $this pseudo variable of class object is Page object
        $resumeUrl = $this->server->GetQuerystring(QueryStringKeys::REDIRECT);
        if ($resumeUrl !== NULL) $resumeUrl = str_replace('&amp;&amp;', '&amp;', $resumeUrl);
        $this->Set('ResumeUrl', $resumeUrl);
        $this->Set('ShowLoginError', false);
        $this->Set('Languages', Resources::GetInstance()->AvailableLanguages);

        $this->SetFacebookErrorMessage();
        $this->Set('AllowFacebookLogin', Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_FACEBOOK_LOGIN_ENABLED, new BooleanConverter()));
        $this->Set('AllowGoogleLogin', Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_GOOGLE_LOGIN_ENABLED, new BooleanConverter()));
        $this->Set('AllowMicrosoftLogin', Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_LOGIN_ENABLED, new BooleanConverter()));
        $this->Set('AllowKeycloakLogin', Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_LOGIN_ENABLED, new BooleanConverter()));
        $this->Set('AllowOauth2Login', Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_OAUTH2_LOGIN_ENABLED, new BooleanConverter()));
        $scriptUrl = Configuration::Instance()->GetScriptUrl();
        $parts = explode('://', $scriptUrl);
        $this->Set('Protocol', $parts[0]);
        if (isset($parts[1])) {
            $this->Set('ScriptUrlNoProtocol', $parts[1]);
        }
        $this->Set('GoogleState', strtr(base64_encode("resume=$scriptUrl/external-auth.php%3Ftype%3Dgoogle%26redirect%3D$resumeUrl"), '+/=', '-_,'));
        $this->Set('EnableCaptcha', Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_CAPTCHA_ON_LOGIN, new BooleanConverter()));
    }

    public function PageLoad()
    {
        $this->presenter->PageLoad();
        $this->Display('login.tpl');
    }

    public function GetEmailAddress()
    {
        return $this->GetForm(FormKeys::EMAIL);
    }

    public function GetPassword()
    {
        return $this->GetRawForm(FormKeys::PASSWORD);
    }

    public function GetPersistLogin()
    {
        return $this->GetCheckbox(FormKeys::PERSIST_LOGIN);
    }

    public function GetShowRegisterLink()
    {
        return $this->GetVar('ShowRegisterLink');
    }

    public function SetShowRegisterLink($value)
    {
        $this->Set('ShowRegisterLink', $value);
    }

    public function GetSelectedLanguage()
    {
        return $this->GetForm(FormKeys::LANGUAGE);
    }

    public function SetUseLogonName($value)
    {
        $this->Set('UseLogonName', $value);
    }

    public function GetCaptcha()
    {
        return $this->GetForm(FormKeys::CAPTCHA);
    }

    public function SetResumeUrl($value)
    {
        $this->Set('ResumeUrl', $value);
    }

    public function GetResumeUrl()
    {
        $resumeUrl = $this->GetForm(FormKeys::RESUME);
        if (empty($resumeUrl)) {
            return $this->GetQuerystring(QueryStringKeys::REDIRECT);
        } else {
            return $this->GetForm(FormKeys::RESUME);
        }
    }

    public function DisplayWelcome()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function LoggingIn()
    {
        $loggingIn = $this->GetForm(Actions::LOGIN);
        return !empty($loggingIn);
    }

    /**
     * @return bool
     */
    public function ChangingLanguage()
    {
        $lang = $this->GetRequestedLanguage();
        return !empty($lang);
    }

    public function Login()
    {
        $this->presenter->Login();
    }

    public function ChangeLanguage()
    {
        $this->presenter->ChangeLanguage();
    }

    public function SetShowLoginError()
    {
        $this->Set('ShowLoginError', true);
    }

    public function GetRequestedLanguage()
    {
        return $this->GetQuerystring(QueryStringKeys::LANGUAGE);
    }

    public function SetSelectedLanguage($languageCode)
    {
        $this->Set('SelectedLanguage', $languageCode);
    }

    protected function GetShouldAutoLogout()
    {
        return false;
    }

    public function ShowUsernamePrompt($shouldShow)
    {
        $this->Set('ShowUsernamePrompt', $shouldShow);
    }

    public function ShowPasswordPrompt($shouldShow)
    {
        $this->Set('ShowPasswordPrompt', $shouldShow);
    }

    public function ShowPersistLoginPrompt($shouldShow)
    {
        $this->Set('ShowPersistLoginPrompt', $shouldShow);
    }

    public function ShowForgotPasswordPrompt($shouldShow)
    {
        $this->Set('ShowForgotPasswordPrompt', $shouldShow);
    }

    public function SetShowScheduleLink($shouldShow)
    {
        $this->Set('ShowScheduleLink', $shouldShow);
    }

    public function SetPasswordResetUrl($url)
    {
        $this->Set('ForgotPasswordUrl', empty($url) ? Pages::FORGOT_PASSWORD : $url);
        if (BookedStringHelper::StartsWith($url, 'http')) {
            $this->Set('ForgotPasswordUrlNew', "target='_new'");
        }
    }

    public function SetRegistrationUrl($url)
    {
        $this->Set('RegisterUrl', empty($url) ? Pages::REGISTRATION : $url);
        if (BookedStringHelper::StartsWith($url, 'http')) {
            $this->Set('RegisterUrlNew', "target='_new'");
        }
    }

    public function SetAnnouncements($announcements)
    {
        $this->Set('Announcements', $announcements);
    }

    /**
     * Sends the created google url in the presenter to the smarty page
     */
    public function SetGoogleUrl($googleUrl)
    {
        if (Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_GOOGLE_LOGIN_ENABLED, new BooleanConverter())) {
            $this->Set('GoogleUrl', $googleUrl);
        }
    }

    /**
     * Sends the created microsoft url in the presenter to the smarty page
     */
    public function SetMicrosoftUrl($microsoftUrl)
    {
        if (Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_LOGIN_ENABLED, new BooleanConverter())) {
            $this->Set('MicrosoftUrl', $microsoftUrl);
        }
    }

    /**
     * Sends the created facebook url in the presenter to the smarty page
     */
    public function SetFacebookUrl($FacebookUrl)
    {
        if (Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_FACEBOOK_LOGIN_ENABLED, new BooleanConverter())) {
            $this->Set('FacebookUrl', $FacebookUrl);
        }
    }

    /**
     * Temporary solution for facebook auth SDK error
     * After facebook failed authentication user is redirected to login page (this one) and is shown a message to try again
     * Error occurs rarely (FacebookSDKException)
     */
    private function SetFacebookErrorMessage()
    {
        if (isset($_SESSION['facebook_error']) && $_SESSION['facebook_error'] == true) {
            $this->Set('facebookError', $_SESSION['facebook_error']);
            unset($_SESSION['facebook_error']);
        }
    }

    public function SetKeycloakUrl($KeycloakUrl)
    {
        if (Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_LOGIN_ENABLED, new BooleanConverter())) {
            $this->Set('KeycloakUrl', $KeycloakUrl);
        }
    }

    public function SetOauth2Url($Oauth2Url)
    {
        if (Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_OAUTH2_LOGIN_ENABLED, new BooleanConverter())) {
            $this->Set('Oauth2Url', $Oauth2Url);
        }
    }

    public function SetOauth2Name($Oauth2Name)
    {
        if (Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_OAUTH2_LOGIN_ENABLED, new BooleanConverter())) {
            $this->Set('Oauth2Name', $Oauth2Name);
        }
    }
}
