<?php

require_once(ROOT_DIR . 'lib/Config/namespace.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'Presenters/Authentication/LoginRedirector.php');

class LoginPresenter
{
    /**
     * @var ILoginPage
     */
    private $_page = null;

    /**
     * @var IWebAuthentication
     */
    private $authentication = null;

    /**
     * @var ICaptchaService
     */
    private $captchaService;

    /**
     * @var IAnnouncementRepository
     */
    private $announcementRepository;

    /**
     * @param ILoginPage $page
     * @param IWebAuthentication $authentication
     * @param ICaptchaService $captchaService
     * @param IAnnouncementRepository $announcementRepository
     */
    public function __construct(ILoginPage &$page, $authentication = null, $captchaService = null, $announcementRepository = null)
    {
        $this->_page = &$page;
        $this->SetAuthentication($authentication);
        $this->SetCaptchaService($captchaService);
        $this->SetAnnouncementRepository($announcementRepository);

        $this->LoadValidators();
    }

    /**
     * @param IWebAuthentication $authentication
     */
    private function SetAuthentication($authentication)
    {
        if (is_null($authentication)) {
            $this->authentication = new WebAuthentication(PluginManager::Instance()->LoadAuthentication(), ServiceLocator::GetServer());
        } else {
            $this->authentication = $authentication;
        }
    }

    /**
     * @param ICaptchaService $captchaService
     */
    private function SetCaptchaService($captchaService)
    {
        if (is_null($captchaService)) {
            $this->captchaService = CaptchaService::Create();
        } else {
            $this->captchaService = $captchaService;
        }
    }

    /**
     * @param IAnnouncementRepository $announcementRepository
     */
    private function SetAnnouncementRepository($announcementRepository)
    {
        if (is_null($announcementRepository)) {
            $this->announcementRepository = new AnnouncementRepository();
        } else {
            $this->announcementRepository = $announcementRepository;
        }
    }

    public function PageLoad()
    {
        if ($this->authentication->IsLoggedIn()) {
            $this->_Redirect();
            return;
        }

        $this->SetSelectedLanguage();

        if ($this->authentication->AreCredentialsKnown()) {
            $this->Login();
            return;
        }

        $server = ServiceLocator::GetServer();
        $loginCookie = $server->GetCookie(CookieKeys::PERSIST_LOGIN);

        if ($this->IsCookieLogin($loginCookie)) {
            if ($this->authentication->CookieLogin($loginCookie, new WebLoginContext(new LoginData(true)))) {
                $this->_Redirect();
                return;
            }
        }

        $allowRegistration = Configuration::Instance()->GetKey(ConfigKeys::ALLOW_REGISTRATION, new BooleanConverter());
        $allowAnonymousSchedule = Configuration::Instance()->GetSectionKey(ConfigSection::PRIVACY, ConfigKeys::PRIVACY_VIEW_SCHEDULES, new BooleanConverter());
        $allowGuestBookings = Configuration::Instance()->GetSectionKey(ConfigSection::PRIVACY, ConfigKeys::PRIVACY_ALLOW_GUEST_BOOKING, new BooleanConverter());
        $this->_page->SetShowRegisterLink($allowRegistration);
        $this->_page->SetShowScheduleLink($allowAnonymousSchedule || $allowGuestBookings);

        $hideLogin = Configuration::Instance()
            ->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::AUTHENTICATION_HIDE_BOOKED_LOGIN_PROMPT, new BooleanConverter());

        $this->_page->ShowForgotPasswordPrompt(!Configuration::Instance()->GetKey(ConfigKeys::DISABLE_PASSWORD_RESET, new BooleanConverter()) &&
            $this->authentication->ShowForgotPasswordPrompt() &&
            !$hideLogin);
        $this->_page->ShowPasswordPrompt($this->authentication->ShowPasswordPrompt() && !$hideLogin);
        $this->_page->ShowPersistLoginPrompt($this->authentication->ShowPersistLoginPrompt());

        $this->_page->ShowUsernamePrompt($this->authentication->ShowUsernamePrompt() && !$hideLogin);
        $this->_page->SetRegistrationUrl($this->authentication->GetRegistrationUrl() && !$hideLogin);
        $this->_page->SetPasswordResetUrl($this->authentication->GetPasswordResetUrl());
        $this->_page->SetAnnouncements($this->announcementRepository->GetFuture(Pages::ID_LOGIN));
        $this->_page->SetSelectedLanguage(Resources::GetInstance()->CurrentLanguage);

        $this->_page->SetGoogleUrl($this->GetGoogleUrl());
        $this->_page->SetMicrosoftUrl($this->GetMicrosoftUrl());
        $this->_page->SetFacebookUrl($this->GetFacebookUrl());
        $this->_page->SetKeycloakUrl($this->GetKeycloakUrl());
        $this->_page->SetOauth2Url($this->GetOauth2Url());
        $this->_page->SetOauth2Name($this->GetOauth2Name());
    }

    public function Login()
    {
        if (!$this->_page->IsValid()) {
            return;
        }

        $id = $this->_page->GetEmailAddress();

        if ($this->authentication->Validate($id, $this->_page->GetPassword())) {
            $context = new WebLoginContext(new LoginData($this->_page->GetPersistLogin(), $this->_page->GetSelectedLanguage()));
            $this->authentication->Login($id, $context);
            $this->_Redirect();
        } else {
            sleep(2);
            $this->authentication->HandleLoginFailure($this->_page);
            $this->_page->SetShowLoginError();
        }
    }

    public function postLogout()
    {
        $url = Configuration::Instance()->GetKey(ConfigKeys::LOGOUT_URL);
        if (empty($url)) {
            $url = htmlspecialchars_decode($this->_page->GetResumeUrl());
            $url = sprintf('%s?%s=%s', Pages::LOGIN, QueryStringKeys::REDIRECT, urlencode($url));
        }
        $this->authentication->postLogout(ServiceLocator::GetServer()->GetUserSession());
        $this->_page->Redirect($url);
    }

    public function ChangeLanguage()
    {
        $resources = Resources::GetInstance();

        $languageCode = $this->_page->GetRequestedLanguage();

        if ($resources->SetLanguage($languageCode)) {
            ServiceLocator::GetServer()->SetCookie(new Cookie(CookieKeys::LANGUAGE, $languageCode, secure: false));
            $this->_page->SetSelectedLanguage($languageCode);
            $this->_page->Redirect(Pages::LOGIN);
        }
    }

    public function Logout()
    {
        $url = Configuration::Instance()->GetKey(ConfigKeys::LOGOUT_URL);
        if (empty($url)) {
            $url = htmlspecialchars_decode($this->_page->GetResumeUrl() ?? '');
            $url = sprintf('%s?%s=%s', Pages::LOGIN, QueryStringKeys::REDIRECT, urlencode($url));
        }
        $this->authentication->Logout(ServiceLocator::GetServer()->GetUserSession());
        $this->_page->Redirect($url);
    }

    private function _Redirect()
    {
        LoginRedirector::Redirect($this->_page);
    }

    private function IsCookieLogin($loginCookie)
    {
        return !empty($loginCookie);
    }

    private function SetSelectedLanguage()
    {
        $requestedLanguage = $this->_page->GetRequestedLanguage();
        if (!empty($requestedLanguage)) {
            // this is handled by ChangeLanguage()
            return;
        }

        $languageCookie = ServiceLocator::GetServer()->GetCookie(CookieKeys::LANGUAGE);
        $languageHeader = ServiceLocator::GetServer()->GetLanguage();
        $languageCode = Configuration::Instance()->GetKey(ConfigKeys::LANGUAGE);

        $resources = Resources::GetInstance();

        if ($resources->IsLanguageSupported($languageCookie)) {
            $languageCode = $languageCookie;
        } else {
            if ($resources->IsLanguageSupported($languageHeader)) {
                $languageCode = $languageHeader;
            }
        }

        $this->_page->SetSelectedLanguage(strtolower($languageCode));
        $resources->SetLanguage($languageCode);
    }

    protected function LoadValidators()
    {
        if (Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::AUTHENTICATION_CAPTCHA_ON_LOGIN, new BooleanConverter())) {
            $this->_page->RegisterValidator('captcha', new CaptchaValidator($this->_page->GetCaptcha(), $this->captchaService));
        }
    }

    /**
     * Checks in the config files if google authentication is active creating a new client if true and setting it's config keys.
     * Returns the created google url for the authentication
     */
    public function GetGoogleUrl()
    {
        if (Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::AUTHENTICATION_ALLOW_GOOGLE, new BooleanConverter())) {
            $client = new Google\Client();
            $client->setClientId(Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::GOOGLE_CLIENT_ID));
            $client->setClientSecret(Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::GOOGLE_CLIENT_SECRET));
            $client->setRedirectUri(Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::GOOGLE_REDIRECT_URI));
            $client->addScope("email");
            $client->addScope("profile");
            $client->setPrompt("select_account");
            $GoogleUrl = $client->createAuthUrl();

            return $GoogleUrl;
        }
    }

    /**
     * Checks in the config files if microsoft authentication is active creating the url if true with the respective keys
     * Returns the created microsoft url for the authentication
     */
    public function GetMicrosoftUrl()
    {
        if (Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::AUTHENTICATION_ALLOW_MICROSOFT, new BooleanConverter())) {
            $MicrosoftUrl = 'https://login.microsoftonline.com/'
                . urlencode(Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::MICROSOFT_TENANT_ID))
                . '/oauth2/v2.0/authorize?'
                . 'client_id=' . urlencode(Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::MICROSOFT_CLIENT_ID))
                . '&redirect_uri=' . urlencode(Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::MICROSOFT_REDIRECT_URI))
                . '&scope=user.read'
                . '&response_type=code'
                . '&prompt=select_account';

            return $MicrosoftUrl;
        }
    }

    /**
     * Checks in the config files if facebook authentication is active creating the url if true with the respective keys
     * Returns the created facebook url for the authentication
     */
    public function GetFacebookUrl()
    {
        if (Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::AUTHENTICATION_ALLOW_FACEBOOK, new BooleanConverter())) {
            $facebook_Client = new Facebook\Facebook([
                'app_id'                => Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::FACEBOOK_CLIENT_ID),
                'app_secret'            => Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::FACEBOOK_CLIENT_SECRET),
                'default_graph_version' => 'v2.5'
            ]);

            $helper = $facebook_Client->getRedirectLoginHelper();

            $permissions = ['email', 'public_profile']; // Add other permissions as needed

            //The FacebookRedirectLoginHelper makes use of sessions to store a CSRF value.
            //You need to make sure you have sessions enabled before invoking the getLoginUrl() method.
            if (!session_id()) {
                session_start();
            }

            // Build the full redirect URL
            $redirectUri = Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::FACEBOOK_REDIRECT_URI);
            $scriptUrl = Configuration::Instance()->GetScriptUrl();

            $scriptUrl = rtrim($scriptUrl, '/');
            if (!str_starts_with($redirectUri, '/')) {
                $redirectUri = '/' . $redirectUri;
            }

            if (str_ends_with($scriptUrl, '/Web') && str_starts_with($redirectUri, '/Web/')) {
                $redirectUri = substr($redirectUri, 4); // Remove the first "/Web"
            }

            $fullRedirectUrl = $scriptUrl . $redirectUri;

            $FacebookUrl = $helper->getLoginUrl(
                $fullRedirectUrl,
                $permissions
            );

            return $FacebookUrl;
        }
    }

    public function GetKeycloakUrl()
    {
        if (Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::AUTHENTICATION_ALLOW_KEYCLOAK, new BooleanConverter())) {
            // Retrieve Keycloak configuration values
            $baseUrl     = Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::KEYCLOAK_URL);
            $realm       = Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::KEYCLOAK_REALM);
            $clientId    = Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::KEYCLOAK_CLIENT_ID);
            $redirectUri = rtrim(Configuration::Instance()->GetScriptUrl(), 'Web/') . Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::KEYCLOAK_REDIRECT_URI);

            // Construct the Keycloak authentication URL
            $keycloakUrl = rtrim($baseUrl, '/')
                . '/realms/' . urlencode($realm)
                . '/protocol/openid-connect/auth?'
                . 'client_id=' . urlencode($clientId)
                . '&redirect_uri=' . urlencode($redirectUri)
                . '&response_type=code'
                . '&scope=' . urlencode('openid email profile');

            return $keycloakUrl;
        }
    }

    public function GetOauth2Url()
    {
        if (Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::AUTHENTICATION_ALLOW_OAUTH2, new BooleanConverter())) {
            // Retrieve Oauth2 configuration values
            $baseUrl     = Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::OAUTH2_URL_AUTHORIZE);
            $clientId    = Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::OAUTH2_CLIENT_ID);
            $redirectUri = rtrim(Configuration::Instance()->GetScriptUrl(), 'Web/') . Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::OAUTH2_REDIRECT_URI);

            // Construct the Oauth2 authentication URL
            $Oauth2Url = $baseUrl
                . '?client_id=' . urlencode($clientId)
                . '&redirect_uri=' . urlencode($redirectUri)
                . '&response_type=code'
                . '&scope=' . urlencode('openid email profile');

            return $Oauth2Url;
        }
    }

    public function GetOauth2Name()
    {
        if (Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::AUTHENTICATION_ALLOW_OAUTH2, new BooleanConverter())) {
            // Retrieve Oauth2 configuration values
            $Oauth2Name = Configuration::Instance()->GetSectionKey(ConfigSection::AUTHENTICATION, ConfigKeys::OAUTH2_NAME);

            return $Oauth2Name;
        }
    }
}
