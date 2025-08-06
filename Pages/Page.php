<?php

// debugging tools / libs
if (file_exists(ROOT_DIR . 'vendor/autoload.php')) {
    require ROOT_DIR . 'vendor/autoload.php';
}

require_once(ROOT_DIR . 'Pages/IPage.php');
require_once(ROOT_DIR . 'Pages/Pages.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'lib/Server/namespace.php');
require_once(ROOT_DIR . 'lib/Config/namespace.php');

use Detection\MobileDetect;

abstract class Page implements IPage
{
    /**
     * @var SmartyPage
     */
    protected $smarty = null;

    /**
     * @var Server
     */
    protected $server = null;
    protected $path;

    protected $IsMobile = false;
    protected $IsTablet = false;
    protected $IsDesktop = true;

    protected function __construct($titleKey = '', $pageDepth = 0)
    {
        ExceptionHandler::SetExceptionHandler(new WebExceptionHandler([$this, 'RedirectToError']));

        $this->SetSecurityHeaders();

        $this->path = str_repeat('../', $pageDepth);
        $this->server = ServiceLocator::GetServer();
        $resources = Resources::GetInstance();

        $this->smarty = new SmartyPage($resources, $this->path);

        $userSession = ServiceLocator::GetServer()->GetUserSession();
        $this->smarty->assign('Timezone', $userSession->Timezone);
        $this->smarty->assign('Charset', $resources->Charset);
        $this->smarty->assign('CurrentLanguage', $resources->CurrentLanguage);
        $this->smarty->assign('HtmlLang', $resources->HtmlLang);
        $this->smarty->assign('HtmlTextDirection', $resources->TextDirection);
        $appTitle = Configuration::Instance()->GetKey(ConfigKeys::APP_TITLE);
        $pageTile = $resources->GetString($titleKey);
        $this->smarty->assign('Title', (empty($appTitle) ? 'LibreBooking' : $appTitle) . (empty($pageTile) ? '' : ' - ' . $pageTile));
        $this->smarty->assign('AppTitle', (empty($appTitle) ? 'LibreBooking' : $appTitle));
        $companyName = Configuration::Instance()->GetKey(ConfigKeys::COMPANY_NAME);
        $companyUrl = Configuration::Instance()->GetKey(ConfigKeys::COMPANY_URL);
        $this->smarty->assign('CompanyName', (empty($companyName) ? '' : $companyName));
        $this->smarty->assign('CompanyUrl', (empty($companyUrl) ? '' : $companyUrl));
        $this->smarty->assign('CalendarJSFile', $resources->CalendarLanguageFile);

        $this->smarty->assign('LoggedIn', $userSession->IsLoggedIn());
        $this->smarty->assign('Version', Configuration::VERSION);
        $this->smarty->assign('Path', $this->path);
        $this->smarty->assign('ScriptUrl', Configuration::Instance()->GetScriptUrl());
        $this->smarty->assign('UserName', !is_null($userSession) ? $userSession->FirstName : '');
        $this->smarty->assign('DisplayWelcome', $this->DisplayWelcome());
        $this->smarty->assign('UserId', $userSession->UserId);
        $this->smarty->assign('CanViewAdmin', $userSession->IsAdmin);
        $this->smarty->assign('CanViewGroupAdmin', $userSession->IsGroupAdmin);
        $this->smarty->assign('CanViewResourceAdmin', $userSession->IsResourceAdmin);
        $this->smarty->assign('CanViewScheduleAdmin', $userSession->IsScheduleAdmin);
        $this->smarty->assign('CanViewResponsibilities', !$userSession->IsAdmin && ($userSession->IsGroupAdmin || $userSession->IsResourceAdmin || $userSession->IsScheduleAdmin));
        $allowAllUsersToReports = Configuration::Instance()->GetKey(ConfigKeys::REPORTS_ALLOW_ALL_USERS, new BooleanConverter());
        $this->smarty->assign('CanViewReports', ($allowAllUsersToReports || $userSession->IsAdmin || $userSession->IsGroupAdmin || $userSession->IsResourceAdmin || $userSession->IsScheduleAdmin));

        $timeout = Configuration::Instance()->GetKey(ConfigKeys::INACTIVITY_TIMEOUT);
        if (!empty($timeout)) {
            $this->smarty->assign('SessionTimeoutSeconds', max($timeout, 1) * 60);
        }
        $this->smarty->assign('ShouldLogout', $this->GetShouldAutoLogout());
        $this->smarty->assign('CssExtensionFile', Configuration::Instance()->GetKey(ConfigKeys::CSS_EXTENSION_FILE));
        $this->smarty->assign('UseLocalJquery', Configuration::Instance()->GetKey(ConfigKeys::USE_LOCAL_JS_LIBS, new BooleanConverter()));
        $this->smarty->assign('EnableConfigurationPage', Configuration::Instance()->GetKey(ConfigKeys::PAGES_CONFIGURATION_ENABLED, new BooleanConverter()));
        $this->smarty->assign('ShowParticipation', !Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_PREVENT_PARTICIPATION, new BooleanConverter()));
        $this->smarty->assign('CreditsEnabled', Configuration::Instance()->GetKey(ConfigKeys::CREDITS_ENABLED, new BooleanConverter()));
        $this->smarty->assign('PaymentsEnabled', Configuration::Instance()->GetKey(ConfigKeys::CREDITS_ALLOW_PURCHASE, new BooleanConverter()));
        $this->smarty->assign('EmailEnabled', Configuration::Instance()->GetKey(ConfigKeys::EMAIL_ENABLED, new BooleanConverter()));

        $this->smarty->assign('checkinAdminOnly', Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_CHECKIN_ADMIN_ONLY, new BooleanConverter()));
        $this->smarty->assign('checkoutAdminOnly', Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_CHECKOUT_ADMIN_ONLY, new BooleanConverter()));

        $this->smarty->assign('cssTheme', (Configuration::Instance()->GetKey(ConfigKeys::CSS_THEME) ?? 'default'));

        $this->smarty->assign('LogoUrl', 'librebooking.png');
        if (file_exists($this->path . 'img/custom-logo.png')) {
            $this->smarty->assign('LogoUrl', 'custom-logo.png');
        }
        if (file_exists($this->path . 'img/custom-logo.gif')) {
            $this->smarty->assign('LogoUrl', 'custom-logo.gif');
        }
        if (file_exists($this->path . 'img/custom-logo.jpg')) {
            $this->smarty->assign('LogoUrl', 'custom-logo.jpg');
        }

        $this->smarty->assign('CssUrl', 'null-style.css');
        if (file_exists($this->path . 'css/custom-style.css')) {
            $this->smarty->assign('CssUrl', 'custom-style.css');
        }

        $stylingFactory = PluginManager::Instance()->LoadStyling();
        if (!empty($stylingFactory->AdditionalCSS($userSession))) {
            $this->smarty->assign('CssStylingFile', 'styling-plugin.php');
        }

        $this->smarty->assign('FaviconUrl', 'favicon.ico');
        if (file_exists($this->path . 'custom-favicon.png')) {
            $this->smarty->assign('FaviconUrl', 'custom-favicon.png');
        }
        if (file_exists($this->path . 'custom-favicon.gif')) {
            $this->smarty->assign('FaviconUrl', 'custom-favicon.gif');
        }
        if (file_exists($this->path . 'custom-favicon.jpg')) {
            $this->smarty->assign('FaviconUrl', 'custom-favicon.jpg');
        }
        if (file_exists($this->path . 'custom-favicon.ico')) {
            $this->smarty->assign('FaviconUrl', 'custom-favicon.ico');
        }

        $logoUrl = Configuration::Instance()->GetKey(ConfigKeys::HOME_URL);
        if (empty($logoUrl)) {
            $logoUrl = $this->path . Pages::UrlFromId($userSession->HomepageId);
        }
        $this->smarty->assign('HomeUrl', $logoUrl);

        $loadMobileViews = Configuration::Instance()->GetKey(ConfigKeys::SCHEDULE_LOAD_MOBILE_VIEWS, new BooleanConverter()) ?? true;

        if ($loadMobileViews) {
            $detect = new MobileDetect();
            $this->IsMobile = $detect->isMobile();
            $this->IsTablet = $detect->isTablet();
        }

        $this->IsDesktop = !$this->IsMobile && !$this->IsTablet;
        $this->Set('IsMobile', (bool) $this->IsMobile);
        $this->Set('IsTablet', (bool) $this->IsTablet);
        $this->Set('IsDesktop', (bool) $this->IsDesktop);
        $this->Set('GoogleAnalyticsTrackingId', Configuration::Instance()->GetKey(ConfigKeys::GOOGLE_ANALYTICS_TRACKING_ID));
        $this->Set('ShowNewVersion', $this->ShouldShowNewVersion());

        $this->Set('AutoScrollToday', Configuration::Instance()->GetKey(ConfigKeys::SCHEDULE_AUTO_SCROLL_TODAY, new BooleanConverter()) ?? true);
    }

    protected function SetTitle($title)
    {
        $this->smarty->assign('Title', $title);
    }

    public function Redirect($url)
    {
        if (!BookedStringHelper::StartsWith($url, $this->path)) {
            $url = $this->path . $url;
        }

        $url = str_replace('&amp;', '&', $url);
        header("Location: $url");
        die();
    }

    public function RedirectResume($url)
    {
        if (!BookedStringHelper::StartsWith($url, $this->path)) {
            $url = $this->path . $url;
        }

        header("Location: $url");
        die();
    }

    public function RedirectToError($errorMessageId = ErrorMessages::UNKNOWN_ERROR, $lastPage = '')
    {
        $errorMessageKey = ErrorMessages::Instance()->GetResourceKey($errorMessageId);
        $this->Set('ErrorMessage', $errorMessageKey);
        $this->Set('TitleKey', 'Error');
        $this->Display('error.tpl');
        die();
    }

    public function GetLastPage($defaultPage = '')
    {
        $referer = filter_var(getenv("HTTP_REFERER"), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($referer)) {
            return empty($defaultPage) ? Pages::LOGIN : $defaultPage;
        }

        $scriptUrl = Configuration::Instance()->GetScriptUrl();
        $page = str_ireplace($scriptUrl, '', $referer);
        return ltrim($page, '/');
    }

    public function DisplayWelcome()
    {
        return true;
    }

    /**
     * Returns whether or not the user has been authenticated
     *
     * @return bool
     */
    public function IsAuthenticated()
    {
        return !is_null($this->server->GetUserSession()) && $this->server->GetUserSession()->IsLoggedIn();
    }

    /**
     * Returns whether or not the page is currently posting back to itself
     *
     * @return bool
     */
    public function IsPostBack()
    {
        return !empty($_POST);
    }

    /**
     * Registers a Validator with the page
     *
     * @param int|string $validatorId
     * @param IValidator $validator
     */
    public function RegisterValidator($validatorId, $validator)
    {
        $this->smarty->Validators->Register($validatorId, $validator);
    }

    /**
     * Whether or not the current page is valid when checked against all registered validators
     *
     * @return bool
     */
    public function IsValid()
    {
        return $this->smarty->IsValid();
    }

    /**
     * @param string $var
     * @param string|object|array $value
     * @return void
     */
    public function Set($var, $value)
    {
        $this->smarty->assign($var, $value);
    }

    public function EnforceCSRFCheck()
    {
        $session = $this->server->GetUserSession();
        if (!$session->IsLoggedIn()) {
            return;
        }
        $token = $this->GetForm(FormKeys::CSRF_TOKEN);
        $session = $this->server->GetUserSession();
        if ($this->IsPost() && (empty($token) || $token != $session->CSRFToken)) {
            Log::Error('Possible CSRF attack. Submitted token=%s, Expected token=%s', $token, $session->CSRFToken);
            http_response_code(500);
            die('Insecure request');
        }
    }

    public function GetSortField()
    {
        return $this->GetQuerystring(QueryStringKeys::SORT_FIELD);
    }

    public function GetSortDirection()
    {
        return $this->GetQuerystring(QueryStringKeys::SORT_DIRECTION);
    }

    /**
     * @param string $var
     * @return string
     */
    protected function GetVar($var)
    {
        return $this->smarty->getTemplateVars($var);
    }

    /**
     * @param string $var
     * @return null|string
     */
    protected function GetForm($var)
    {
        return $this->server->GetForm($var);
    }

    /**
     * @param string $var
     * @return bool
     */
    protected function GetCheckbox($var)
    {
        $val = $this->server->GetForm($var);
        return !empty($val);
    }

    /**
     * @param string $var
     * @return null
     */
    protected function GetRawForm($var)
    {
        return $this->server->GetRawForm($var);
    }

    /**
     * @param string $key
     * @param bool $forceArray
     * @return null|string|array
     */
    protected function GetQuerystring($key, $forceArray = false)
    {
        $val = $this->server->GetQuerystring($key);
        if (!$forceArray) {
            return $val;
        }

        if (empty($val)) {
            return [];
        }
        if (!is_array($val)) {
            return [$val];
        }

        return $val;
    }

    /**
     * @param string $key
     * @return null|UploadedFile
     */
    protected function GetFile($key)
    {
        return $this->server->GetFile($key);
    }

    /**
     * @param mixed $objectToSerialize
     * @param string|null $error
     * @param int|null $httpResponseCode
     * @return void
     */
    protected function SetJson($objectToSerialize, $error = null, $httpResponseCode = 200)
    {
        header('Content-type: application/json');
        http_response_code(empty($httpResponseCode) ? 200 : $httpResponseCode);

        if (empty($error)) {
            $this->Set('data', json_encode($objectToSerialize));
        } else {
            $this->Set('error', json_encode(['response' => $objectToSerialize, 'errors' => $error]));
        }
        $this->smarty->display('json_data.tpl');
    }

    /**
     * @param string $objectToSerialize
     * @return void
     */
    protected function SetJsonError($objectToSerialize)
    {
        header('Content-type: application/json');
        header('HTTP/1.1 500 Internal Server Error');

        $this->Set('data', json_encode($objectToSerialize));

        $this->smarty->display('json_data.tpl');
    }

    /**
     * A template file to be displayed
     * @param string $templateName
     */
    protected function Display($templateName)
    {
        if (!$this->InMaintenanceMode()) {
            $this->smarty->display($templateName);
        } else {
            $this->smarty->display('maintenance.tpl');
        }
    }

    protected function DisplayCsv($templateName, $fileName)
    {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$fileName;");
        header("Content-Transfer-Encoding: binary");
        echo chr(239) . chr(187) . chr(191);

        $this->Display($templateName);
    }

    /**
     * @param string $templateName
     */
    protected function DisplayLocalized($templateName)
    {
        $languageCode = $this->GetVar('CurrentLanguage');
        $localizedPath = ROOT_DIR . 'lang/' . $languageCode;
        $defaultPath = ROOT_DIR . 'lang/en_us/';

        if (file_exists($localizedPath . '/' . $templateName)) {
            $this->smarty->AddTemplateDirectory($localizedPath);
        } else {
            $this->smarty->AddTemplateDirectory($defaultPath);
        }

        $this->Display($templateName);
    }

    protected function GetShouldAutoLogout()
    {
        $timeout = $this->GetVar('SessionTimeoutSeconds');

        return !empty($timeout);
    }

    private function InMaintenanceMode()
    {
        return is_file(ROOT_DIR . 'maint.txt');
    }

    private function SetSecurityHeaders()
    {
        $config = Configuration::Instance();
        if ($config->GetKey(ConfigKeys::SECURITY_HEADERS, new BooleanConverter())) {
            header('Strict-Transport-Security: ' . $config->GetKey(ConfigKeys::SECURITY_STRICT_TRANSPORT));
            header('X-Frame-Options: ' . $config->GetKey(ConfigKeys::SECURITY_X_FRAME));
            header('X-XSS-Protection: ' . $config->GetKey(ConfigKeys::SECURITY_X_XSS));
            header('X-Content-Type-Options: ' . $config->GetKey(ConfigKeys::SECURITY_X_CONTENT_TYPE));
            header('Content-Security-Policy: ' . $config->GetKey(ConfigKeys::SECURITY_CONTENT_SECURITY_POLICY));
        }
    }

    protected function IsPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    private function ShouldShowNewVersion()
    {
        if (!$this->server->GetUserSession()->IsAdmin) {
            return false;
        }

        // $newVersion = $this->server->GetCookie('new_version');
        // if (empty($newVersion)) {
        //     $cookie = sprintf('v=%s,fs=%s', Configuration::VERSION, Date::Now()->Timestamp());
        //     $this->server->SetCookie(new Cookie('new_version', $cookie));
        //     return true;
        // }

        // $parts = explode(',', $newVersion);
        // $versionParts = explode('=', $parts[0]);
        // $firstShownParts = explode('=', $parts[1]);

        // if ($versionParts[1] != Configuration::VERSION)
        // {
        //     $cookie = sprintf('v=%s,fs=%s', Configuration::VERSION, Date::Now()->Timestamp());
        //     $this->server->SetCookie(new Cookie('new_version', $cookie));
        //     return true;
        // }

        // if (Date::Now()->AddDays(-3)->Timestamp() > $firstShownParts[1])
        // {
        //     return false;
        // }

        // TODO Actually check for new versions on git / save install date to DB.
        // The old Method doesn't work when deleting cookies and confuses the users.
        return false;
    }
}
