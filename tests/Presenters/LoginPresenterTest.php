<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/LoginPresenter.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
require_once(ROOT_DIR . 'Pages/LoginPage.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');

class LoginPresenterTest extends TestBase
{
    /**
     * Resume URL with more than one query parameter. FakeLoginPage, like the
     * real LoginPage, returns it HTML-escaped (& becomes &amp;), so this
     * exercises the decoding along every redirect path.
     */
    private const MULTI_PARAM_RESUME_URL = '/Web/reservation.php?rn=123&sd=2026-01-01';

    /**
     * @var FakeWebAuthentication
     */
    private $auth;

    /**
     * @var FakeLoginPage
     */
    private $page;

    /**
     * @var LoginPresenter
     */
    private $presenter;

    /**
     * @var ICaptchaService
     */
    private $captchaService;

    /**
     * @var FakeAnnouncementRepository
     */
    private $announcementRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->auth = new FakeWebAuthentication();
        $this->page = new FakeLoginPage();
        $this->captchaService = $this->createMock('ICaptchaService');
        $this->announcementRepository = new FakeAnnouncementRepository();
        $this->fakeConfig->_ScriptUrl = 'https://booked.example/Web';

        $this->page->_EmailAddress = 'nobody@localhost';
        $this->page->_Password = 'somepassword';

        $this->fakeServer->SetSession(SessionKeys::USER_SESSION, new UserSession(1));

        $loginPage = $this->page;
        $this->presenter = new LoginPresenter($loginPage, $this->auth, $this->captchaService, $this->announcementRepository);
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    private function enableGoogleLogin(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_GOOGLE_LOGIN_ENABLED, 'true');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_GOOGLE_CLIENT_ID, 'client');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_GOOGLE_CLIENT_SECRET, 'secret');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_GOOGLE_REDIRECT_URI, 'https://booked.example/Web/google-auth.php');
    }

    private function enableKeycloakLogin(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_LOGIN_ENABLED, 'true');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_URL, 'https://keycloak.example');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_REALM, 'realm');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_CLIENT_ID, 'client');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_REDIRECT_URI, '/Web/keycloak-auth.php');
    }

    private function enableOauth2Login(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_OAUTH2_LOGIN_ENABLED, 'true');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_OAUTH2_URL_AUTHORIZE, 'https://oauth2.example/authorize');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_OAUTH2_CLIENT_ID, 'client');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_OAUTH2_REDIRECT_URI, '/Web/oauth2-auth.php');
    }

    /**
     * @return array<string, mixed>
     */
    private function getQueryParams(string $url): array
    {
        $query = parse_url(url: $url, component: PHP_URL_QUERY);
        $this->assertIsString($query);
        parse_str(string: $query, result: $params);

        return $params;
    }

    public function testLoginCallsAuthValidate()
    {
        $this->presenter->Login();

        $this->assertEquals($this->page->_EmailAddress, $this->auth->_LastLogin);
        $this->assertEquals($this->page->_Password, $this->auth->_LastPassword);
    }

    public function testSuccessfulValidateCallsLogin()
    {
        $this->auth->_ValidateResult = true;
        $this->page->_CurrentCode = 'en_gb';
        $this->presenter->Login();

        $this->assertEquals($this->page->_EmailAddress, $this->auth->_LastLogin);
        $data = $this->auth->_LastLoginContext->GetData();
        $this->assertFalse($data->Persist, 'persistent login is never requested');
        $this->assertSame('en_gb', $data->Language);
    }

    public function testSuccessfulValidateCallsRedirectToNormalPageWhenNoRequestedPage()
    {
        $userSession = new UserSession(1);
        $userSession->HomepageId = 2;

        $this->fakeServer->UserSession = $userSession;
        $this->auth->_ValidateResult = true;
        $this->presenter->Login();

        $this->assertEquals(Pages::UrlFromId(2), $this->page->_LastRedirect);
    }

    public function testRedirectsToRequestedPage()
    {
        $redirect = '/someurl/something.php';
        $this->page->_ResumeUrl = $redirect;

        $this->auth->_ValidateResult = true;
        $this->presenter->Login();

        $this->assertEquals($redirect, $this->page->_LastRedirect);
    }

    public function testRedirectsToRequestedPageWithMultipleQueryParams()
    {
        $this->page->_ResumeUrl = self::MULTI_PARAM_RESUME_URL;

        $this->auth->_ValidateResult = true;
        $this->presenter->Login();

        $this->assertEquals(self::MULTI_PARAM_RESUME_URL, $this->page->_LastRedirect);
    }

    public function testRedirectsToSameOriginAbsoluteRequestedPage()
    {
        $redirect = 'https://booked.example/Web/someurl/something.php';
        $this->page->_ResumeUrl = $redirect;

        $this->auth->_ValidateResult = true;
        $this->presenter->Login();

        $this->assertEquals($redirect, $this->page->_LastRedirect);
    }

    public function testBlocksAbsoluteExternalUrlRedirect()
    {
        $this->page->_ResumeUrl = 'https://evil.com/steal';

        $this->auth->_ValidateResult = true;
        $this->presenter->Login();

        $this->assertEquals(Pages::UrlFromId(Pages::DEFAULT_HOMEPAGE_ID), $this->page->_LastRedirect);
    }

    public function testBlocksProtocolRelativeExternalUrlRedirect()
    {
        $this->page->_ResumeUrl = '//evil.com/steal';

        $this->auth->_ValidateResult = true;
        $this->presenter->Login();

        $this->assertEquals(Pages::UrlFromId(Pages::DEFAULT_HOMEPAGE_ID), $this->page->_LastRedirect);
    }

    public function testBlocksJavascriptRedirect()
    {
        $this->page->_ResumeUrl = 'javascript:alert(1)';

        $this->auth->_ValidateResult = true;
        $this->presenter->Login();

        $this->assertEquals(Pages::UrlFromId(Pages::DEFAULT_HOMEPAGE_ID), $this->page->_LastRedirect);
    }

    public function testMicrosoftUrlIncludesResumeUrlAsState()
    {
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_LOGIN_ENABLED, 'true');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_TENANT_ID, 'tenant');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_CLIENT_ID, 'client');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_REDIRECT_URI, 'https://booked.example/Web/external-auth.php');

        $this->page->_ResumeUrl = '/Web/reservation.php?rn=123';

        $this->presenter->PageLoad();

        $this->assertStringContainsString(
            'state=' . rawurlencode('/Web/reservation.php?rn=123'),
            $this->page->_MicrosoftUrl
        );
    }

    public function testMicrosoftUrlStateIsHtmlDecodedResumeUrl()
    {
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_LOGIN_ENABLED, 'true');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_TENANT_ID, 'tenant');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_CLIENT_ID, 'client');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_REDIRECT_URI, 'https://booked.example/Web/external-auth.php');

        $this->page->_ResumeUrl = self::MULTI_PARAM_RESUME_URL;

        $this->presenter->PageLoad();

        $params = $this->getQueryParams(url: $this->page->_MicrosoftUrl);
        $this->assertSame(self::MULTI_PARAM_RESUME_URL, $params['state']);
    }

    public function testMicrosoftUrlOmitsStateWhenNoResumeUrl()
    {
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_LOGIN_ENABLED, 'true');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_TENANT_ID, 'tenant');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_CLIENT_ID, 'client');
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_REDIRECT_URI, 'https://booked.example/Web/external-auth.php');

        $this->page->_ResumeUrl = '';

        $this->presenter->PageLoad();

        $this->assertStringNotContainsString('state=', $this->page->_MicrosoftUrl);
    }

    public function testMicrosoftUrlNotSetWhenMicrosoftLoginDisabled()
    {
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_LOGIN_ENABLED, 'false');
        $this->page->_ResumeUrl = '/Web/reservation.php?rn=123';

        $this->presenter->PageLoad();

        $this->assertNull($this->page->_MicrosoftUrl);
    }

    public function testGoogleUrlIncludesResumeUrlAsState()
    {
        $this->enableGoogleLogin();
        $this->page->_ResumeUrl = self::MULTI_PARAM_RESUME_URL;

        $this->presenter->PageLoad();

        $params = $this->getQueryParams(url: $this->page->_GoogleUrl);
        $this->assertSame(self::MULTI_PARAM_RESUME_URL, $params['state']);
    }

    public function testGoogleUrlHasEmptyStateWhenNoResumeUrl()
    {
        $this->enableGoogleLogin();
        $this->page->_ResumeUrl = '';

        $this->presenter->PageLoad();

        // Google\Client always emits a bare "state" key; it must carry no value.
        $params = $this->getQueryParams(url: $this->page->_GoogleUrl);
        $this->assertEmpty($params['state'] ?? null);
    }

    public function testGoogleUrlNotSetWhenGoogleLoginDisabled()
    {
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_GOOGLE_LOGIN_ENABLED, 'false');
        $this->page->_ResumeUrl = '/Web/reservation.php?rn=123';

        $this->presenter->PageLoad();

        $this->assertNull($this->page->_GoogleUrl);
    }

    public function testKeycloakUrlIncludesResumeUrlAsState()
    {
        $this->enableKeycloakLogin();
        $this->page->_ResumeUrl = self::MULTI_PARAM_RESUME_URL;

        $this->presenter->PageLoad();

        $params = $this->getQueryParams(url: $this->page->_KeycloakUrl);
        $this->assertSame(self::MULTI_PARAM_RESUME_URL, $params['state']);
    }

    public function testKeycloakUrlOmitsStateWhenNoResumeUrl()
    {
        $this->enableKeycloakLogin();
        $this->page->_ResumeUrl = '';

        $this->presenter->PageLoad();

        $params = $this->getQueryParams(url: $this->page->_KeycloakUrl);
        $this->assertArrayNotHasKey('state', $params);
    }

    public function testKeycloakUrlNotSetWhenKeycloakLoginDisabled()
    {
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_LOGIN_ENABLED, 'false');
        $this->page->_ResumeUrl = '/Web/reservation.php?rn=123';

        $this->presenter->PageLoad();

        $this->assertNull($this->page->_KeycloakUrl);
    }

    public function testOauth2UrlIncludesResumeUrlAsState()
    {
        $this->enableOauth2Login();
        $this->page->_ResumeUrl = self::MULTI_PARAM_RESUME_URL;

        $this->presenter->PageLoad();

        $params = $this->getQueryParams(url: $this->page->_Oauth2Url);
        $this->assertSame(self::MULTI_PARAM_RESUME_URL, $params['state']);
    }

    public function testOauth2UrlOmitsStateWhenNoResumeUrl()
    {
        $this->enableOauth2Login();
        $this->page->_ResumeUrl = '';

        $this->presenter->PageLoad();

        $params = $this->getQueryParams(url: $this->page->_Oauth2Url);
        $this->assertArrayNotHasKey('state', $params);
    }

    public function testOauth2UrlNotSetWhenOauth2LoginDisabled()
    {
        $this->fakeConfig->SetKey(ConfigKeys::AUTHENTICATION_OAUTH2_LOGIN_ENABLED, 'false');
        $this->page->_ResumeUrl = '/Web/reservation.php?rn=123';

        $this->presenter->PageLoad();

        $this->assertNull($this->page->_Oauth2Url);
    }

    public function testPageLoadSetsVariablesCorrectly()
    {
        $this->fakeConfig->SetKey(ConfigKeys::REGISTRATION_ALLOW_SELF, 'true');

        $this->auth->_ShowUsernamePrompt = true;
        $this->auth->_ShowPasswordPrompt = true;
        $this->auth->_ShowForgotPasswordPrompt = true;

        $this->presenter->PageLoad();

        $this->assertEquals(true, $this->page->GetShowRegisterLink());

        $this->assertEquals(true, $this->page->_ShowUsernamePrompt);
        $this->assertEquals(true, $this->page->_ShowPasswordPrompt);
        $this->assertEquals(true, $this->page->_ShowForgotPasswordPrompt);
    }

    public function testPageLoadSetsLanguagesCorrect()
    {
        $this->presenter->PageLoad();

        $resources = Resources::GetInstance();
        $curLang = 'en_US';
        $resources->CurrentLanguage = $curLang;

        $langs = $this->page->_Languages;

        $this->assertEquals(count($langs), count($resources->AvailableLanguages));
        foreach ($resources->AvailableLanguages as $lang) {
            $this->assertEquals($langs[$lang->LanguageCode], $lang->DisplayName);
        }
    }

    public function testErrorIsDisplayedIfValidationFails()
    {
        $this->auth->_ValidateResult = false;
        $this->presenter->Login();

        $this->assertEquals('', $this->page->_LastRedirect, 'Does not redirect if auth fails');
        $this->assertTrue($this->page->_ShowLoginError, 'Should show login error if auth fails');
        $this->assertNull($this->page->_LoginErrorMessage);
    }

    public function testLdapDependencyErrorIsDisplayedIfAuthenticationThrowsRuntimeException()
    {
        $this->auth->_ValidateException = new RuntimeException('The LDAP plugin requires pear/net_ldap2. Install it with: composer require pear/net_ldap2');

        $this->presenter->Login();

        $this->assertTrue($this->page->_ShowLoginError, 'Should show login error when validation throws');
        $this->assertEquals(
            'LdapDependencyMissingMessage',
            $this->page->_LoginErrorMessage
        );
        $this->assertNull($this->auth->_LastLoginContext, 'Should not call login when validation throws');
    }

    public function testNetLdap2ClassErrorIsDisplayedAsMissingDependencyMessage()
    {
        $this->auth->_ValidateException = new Exception('Class "Net_LDAP2" not found');

        $this->presenter->Login();

        $this->assertTrue($this->page->_ShowLoginError);
        $this->assertEquals(
            'LdapDependencyMissingMessage',
            $this->page->_LoginErrorMessage
        );
    }

    public function testLdapConnectionErrorContainingNetLdap2DoesNotLookLikeMissingDependency()
    {
        $this->auth->_ValidateException = new Exception(
            "Could not connect to LDAP server. Check your settings in Ldap.config.php : Bind failed: Can't contact LDAP server: Unknown Net_LDAP2 Error (-1)"
        );

        $this->presenter->Login();

        $this->assertTrue($this->page->_ShowLoginError);
        $this->assertEquals(
            'LdapConnectionErrorMessage',
            $this->page->_LoginErrorMessage
        );
    }

    public function testNonLdapExceptionFallsBackToGenericLoginErrorPath()
    {
        $this->auth->_ValidateException = new Exception('Some unexpected auth error');

        $this->presenter->Login();

        $this->assertTrue($this->page->_ShowLoginError);
        $this->assertNull($this->page->_LoginErrorMessage);
    }

    public function testCanChangeToKnownLanguage()
    {
        $this->page->_requestedLanguage = 'en_gb';
        $this->fakeResources->_SetCurrentLanguageResult = true;

        $this->presenter->ChangeLanguage();

        $cookie = $this->fakeServer->GetCookie(CookieKeys::LANGUAGE);
        $this->assertEquals('en_gb', $cookie);
        $this->assertEquals('en_gb', $this->page->_selectedLanguage);
    }

    public function testLogsOut()
    {
        $this->page->_ResumeUrl = 'something';
        $logoutUrl = 'some url';
        $this->fakeConfig->SetKey(ConfigKeys::LOGOUT_URL, $logoutUrl);

        $this->presenter->Logout();

        $this->assertEquals($logoutUrl, $this->page->_LastRedirect);
        $this->assertTrue($this->auth->_LogoutCalled);
    }

    public function testLoadsAnnouncements()
    {
        $announcements = [new Announcement(1, 'text', new NullDate(), new NullDate(), 0, [], [], Pages::ID_LOGIN)];
        $this->announcementRepository->_ExpectedAnnouncements = $announcements;

        $this->presenter->PageLoad();

        $this->assertEquals($announcements, $this->page->_Announcements);
    }
}

class FakeLoginPage extends FakePageBase implements ILoginPage
{
    public $_EmailAddress;
    public $_Password;
    public $_LastRedirect;
    public $_ShowRegisterLink;
    public $_PageLoadWasCalled = false;
    public $_Languages = [];
    public $_UseLogonName = false;
    public $_ResumeUrl = '';
    public $_MicrosoftUrl;
    public $_GoogleUrl;
    public $_KeycloakUrl;
    public $_Oauth2Url;
    public $_ShowLoginError = false;
    public $_LoginErrorMessage = null;
    public $_requestedLanguage;
    public $_selectedLanguage;
    public $_CurrentCode = '';
    public $_ShowUsernamePrompt = false;
    public $_ShowPasswordPrompt = false;
    public $_ShowForgotPasswordPrompt = false;
    public $_ShowScheduleLink = false;
    public $_Announcements;

    public function SetGoogleUrl($URL)
    {
        $this->_GoogleUrl = $URL;
    }

    public function SetMicrosoftUrl($URL)
    {
        $this->_MicrosoftUrl = $URL;
    }

    public function SetFacebookUrl($URL)
    {
    }

    public function SetKeycloakUrl($URL)
    {
        $this->_KeycloakUrl = $URL;
    }

    public function SetOauth2Url($URL)
    {
        $this->_Oauth2Url = $URL;
    }

    public function SetOauth2Name($Name)
    {
    }

    public function PageLoad()
    {
        $this->_PageLoadWasCalled = true;
    }

    public function GetEmailAddress()
    {
        return $this->_EmailAddress;
    }

    public function GetPassword()
    {
        return $this->_Password;
    }

    public function GetShowRegisterLink()
    {
        return $this->_ShowRegisterLink;
    }

    public function SetShowRegisterLink($value)
    {
        $this->_ShowRegisterLink = $value;
    }

    public function Redirect($url)
    {
        $this->_LastRedirect = $url;
    }

    public function setAvailableLanguages($languages)
    {
        $this->_Languages = $languages;
    }

    public function GetSelectedLanguage()
    {
        return $this->_CurrentCode;
    }

    public function getUseLogonName()
    {
        return $this->_UseLogonName;
    }

    public function SetUseLogonName($value)
    {
        $this->_UseLogonName = $value;
    }

    public function SetResumeUrl($value)
    {
        $this->_ResumeUrl = $value;
    }

    /**
     * Mirrors LoginPage::GetResumeUrl(), which reads the value through
     * Server::GetForm()/GetQuerystring() and so returns it trimmed and
     * HTML-escaped. Tests set the raw URL in _ResumeUrl.
     */
    public function GetResumeUrl()
    {
        if ($this->_ResumeUrl === null || $this->_ResumeUrl === '') {
            return '';
        }

        return htmlspecialchars(trim($this->_ResumeUrl));
    }

    public function SetShowLoginError()
    {
        $this->_ShowLoginError = true;
    }

    public function SetLoginErrorMessage(?string $message): void
    {
        $this->_LoginErrorMessage = $message;
    }

    public function GetRequestedLanguage()
    {
        return $this->_requestedLanguage;
    }

    public function SetSelectedLanguage($languageCode)
    {
        $this->_selectedLanguage = $languageCode;
    }

    public function ShowUsernamePrompt($shouldShow)
    {
        $this->_ShowUsernamePrompt = $shouldShow;
    }

    public function ShowPasswordPrompt($shouldShow)
    {
        $this->_ShowPasswordPrompt = $shouldShow;
    }

    public function ShowForgotPasswordPrompt($shouldShow)
    {
        $this->_ShowForgotPasswordPrompt = $shouldShow;
    }

    public function SetShowScheduleLink($shouldShow)
    {
        $this->_ShowScheduleLink = $shouldShow;
    }

    public function SetRegistrationUrl($url)
    {
        // TODO: Implement SetRegistrationUrl() method.
    }

    public function SetPasswordResetUrl($url)
    {
        // TODO: Implement SetPasswordResetUrl() method.
    }

    public function GetCaptcha()
    {
        return '';
    }

    public function SetAnnouncements($announcements)
    {
        $this->_Announcements = $announcements;
    }
}
