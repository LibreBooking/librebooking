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

        $allowRegistration = Configuration::Instance()->GetKey(ConfigKeys::REGISTRATION_ALLOW_SELF, new BooleanConverter());
        $allowAnonymousSchedule = Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_VIEW_SCHEDULES, new BooleanConverter());
        $allowGuestBookings = Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_ALLOW_GUEST_RESERVATIONS, new BooleanConverter());
        $hideLogin = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_HIDE_LOGIN_PROMPT, new BooleanConverter());
        $resetDisabled = Configuration::Instance()->GetKey(ConfigKeys::PASSWORD_DISABLE_RESET, new BooleanConverter());

        $showRegisterLink = ($allowRegistration && !$hideLogin);
        $showScheduleLink = ($allowAnonymousSchedule || $allowGuestBookings);

        $showForgot = (!$resetDisabled && $this->authentication->ShowForgotPasswordPrompt() && !$hideLogin);

        $showPasswordPrompt   = ($this->authentication->ShowPasswordPrompt() && !$hideLogin);
        $showPersistLogin     = ($this->authentication->ShowPersistLoginPrompt() && !$hideLogin);
        $showUsernamePrompt   = ($this->authentication->ShowUsernamePrompt() && !$hideLogin);

        $this->_page->SetShowRegisterLink($showRegisterLink);
        $this->_page->SetShowScheduleLink($showScheduleLink);

        $this->_page->ShowForgotPasswordPrompt($showForgot);
        $this->_page->ShowPasswordPrompt($showPasswordPrompt);
        $this->_page->ShowPersistLoginPrompt($showPersistLogin);
        $this->_page->ShowUsernamePrompt($showUsernamePrompt);
        $registrationUrl = $this->authentication->GetRegistrationUrl();
        $this->_page->SetRegistrationUrl($showRegisterLink ? $registrationUrl : null);
        $this->_page->SetPasswordResetUrl($showForgot ? $this->authentication->GetPasswordResetUrl() : null);

        $this->_page->SetAnnouncements($this->announcementRepository->GetFuture(Pages::ID_LOGIN));
        $this->_page->SetSelectedLanguage(Resources::GetInstance()->CurrentLanguage);

        $googleEnabled    = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_GOOGLE_LOGIN_ENABLED, new BooleanConverter());
        $microsoftEnabled = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_LOGIN_ENABLED, new BooleanConverter());
        $facebookEnabled  = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_FACEBOOK_LOGIN_ENABLED, new BooleanConverter());
        $keycloakEnabled  = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_LOGIN_ENABLED, new BooleanConverter());
        $oauth2Enabled    = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_OAUTH2_LOGIN_ENABLED, new BooleanConverter());

        $this->_page->SetGoogleUrl($googleEnabled ? $this->GetGoogleUrl() : null);
        $this->_page->SetMicrosoftUrl($microsoftEnabled ? $this->GetMicrosoftUrl() : null);
        $this->_page->SetFacebookUrl($facebookEnabled ? $this->GetFacebookUrl() : null);
        $this->_page->SetKeycloakUrl($keycloakEnabled ? $this->GetKeycloakUrl() : null);
        $this->_page->SetOauth2Url($oauth2Enabled ? $this->GetOauth2Url() : null);
        $this->_page->SetOauth2Name($oauth2Enabled ? Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_OAUTH2_NAME) : null);
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
        $languageCode = Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE);

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
        if (Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_CAPTCHA_ON_LOGIN, new BooleanConverter())) {
            $this->_page->RegisterValidator('captcha', new CaptchaValidator($this->_page->GetCaptcha(), $this->captchaService));
        }
    }

    private function buildRedirectUri(string $configuredPath): string
    {
        $scriptUrl = rtrim(Configuration::Instance()->GetScriptUrl(), '/');
        $path = '/' . ltrim($configuredPath, '/');
        if (str_ends_with($scriptUrl, '/Web') && str_starts_with($path, '/Web/')) {
            $path = substr($path, 4); // remove the first "/Web"
        }

        return $scriptUrl . $path;
    }

    /**
     * Checks in the config files if google authentication is active creating a new client if true and setting it's config keys.
     * Returns the created google url for the authentication
     */
    public function GetGoogleUrl()
    {
        $client = new Google\Client();
        $client->setClientId(Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_GOOGLE_CLIENT_ID));
        $client->setClientSecret(Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_GOOGLE_CLIENT_SECRET));
        $client->setRedirectUri(Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_GOOGLE_REDIRECT_URI));
        $client->addScope("email");
        $client->addScope("profile");
        $client->setPrompt("select_account");
        $GoogleUrl = $client->createAuthUrl();

        return $GoogleUrl;
    }

    /**
     * Checks in the config files if microsoft authentication is active creating the url if true with the respective keys
     * Returns the created microsoft url for the authentication
     */
    public function GetMicrosoftUrl()
    {

        $tenantId = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_TENANT_ID);
        $clientId = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_CLIENT_ID);
        $redirectUri = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_REDIRECT_URI);

        $baseUrl = 'https://login.microsoftonline.com/' . rawurlencode($tenantId) . '/oauth2/v2.0/authorize';
        $params = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => 'user.read',
            'response_type' => 'code',
            'prompt' => 'select_account'
        ];

        $MicrosoftUrl = $baseUrl . '?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        return $MicrosoftUrl;
    }

    /**
     * Checks in the config files if facebook authentication is active creating the url if true with the respective keys
     * Returns the created facebook url for the authentication
     */
    public function GetFacebookUrl()
    {
        $facebook_Client = new Facebook\Facebook([
            'app_id' => Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_FACEBOOK_CLIENT_ID),
            'app_secret' => Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_FACEBOOK_CLIENT_SECRET),
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
        $redirectUri = $this->buildRedirectUri(Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_FACEBOOK_REDIRECT_URI));

        $FacebookUrl = $helper->getLoginUrl(
            $redirectUri,
            $permissions
        );

        return $FacebookUrl;
    }

    public function GetKeycloakUrl()
    {
        // Retrieve Keycloak configuration values
        $baseUrl    = rtrim(Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_URL), '/');
        $realm = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_REALM);
        $clientId = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_CLIENT_ID);
        $redirectUri = $this->buildRedirectUri(Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_REDIRECT_URI));

        $authorizeEndpoint = $baseUrl . '/realms/' . rawurlencode($realm) . '/protocol/openid-connect/auth';

        $params = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => 'openid email profile',
            'response_type' => 'code'
        ];

        return $authorizeEndpoint . '?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986);
    }

    public function GetOauth2Url()
    {
        // Retrieve Oauth2 configuration values
        $baseUrl = rtrim(Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_OAUTH2_URL_AUTHORIZE), '/');
        $clientId = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_OAUTH2_CLIENT_ID);
        $redirectUri = $this->buildRedirectUri(Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_OAUTH2_REDIRECT_URI));

        $params = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => 'openid email profile',
            'response_type' => 'code'
        ];

        $Oauth2Url = $baseUrl . '?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        return $Oauth2Url;
    }

}
