<?php

require_once(ROOT_DIR . 'lang/AvailableLanguages.php');

interface IResourceLocalization
{
    /**
     * @abstract
     * @param $key
     * @param array|string $args
     * @return string
     */
    public function GetString($key, $args = []): string;

    public function GetDateFormat($key);

    public function HasDateFormat($key): bool;

    public function GetDays($key);

    public function GetMonths($key);

    public function GeneralDateFormat();

    public function GeneralDateTimeFormat();
}

class ResourceKeys
{
    public const DATE_GENERAL = 'general_date';
    public const DATETIME_GENERAL = 'general_datetime';
    public const DATETIME_SHORT = 'short_datetime';
    public const DATETIME_SYSTEM = 'system_datetime';
}

class Resources implements IResourceLocalization
{
    /**
     * @var string
     */
    public $CurrentLanguage;
    public $LanguageFile;
    public $CalendarLanguageFile;

    /**
     * @var array|AvailableLanguage[]
     */
    public $AvailableLanguages = [];

    /**
     * @var string
     */
    public $Charset;

    /**
     * @var string
     */
    public $HtmlLang;

    /**
     * @var string
     */
    public $TextDirection = 'ltr';

    protected $LanguageDirectory;

    private static $_instance;

    private $systemDateKeys = [];

    /**
     * @var Language
     */
    private $_lang;

    protected function __construct()
    {
        $this->LanguageDirectory = dirname(__FILE__) . '/../../lang/';

        $this->systemDateKeys['js_general_date'] = 'yy-mm-dd';
        $this->systemDateKeys['js_general_datetime'] = 'yy-mm-dd HH:mm';
        $this->systemDateKeys['js_general_time'] = 'HH:mm';
        $this->systemDateKeys['system_datetime'] = 'Y-m-d H:i:s';
        $this->systemDateKeys['url'] = 'Y-m-d';
        $this->systemDateKeys['url_full'] = 'Y-m-d H:i:s';
        $this->systemDateKeys['ical'] = 'Ymd\THis\Z';
        $this->systemDateKeys['system'] = 'Y-m-d';
        $this->systemDateKeys['fullcalendar'] = 'Y-m-d H:i';
        $this->systemDateKeys['google'] = 'Ymd\\THi00\\Z';

        $this->LoadAvailableLanguages();
    }

    private static function Create()
    {
        $resources = new Resources();
        $resources->SetCurrentLanguage($resources->GetLanguageCode());
        $resources->LoadOverrides();
        return $resources;
    }

    /**
     * @return Resources
     */
    public static function &GetInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = Resources::Create();
        }

        setlocale(LC_ALL, self::$_instance->CurrentLanguage);
        return self::$_instance;
    }

    public static function SetInstance($instance)
    {
        self::$_instance = $instance;
    }

    /**
     * @param string $languageCode
     * @return bool
     */
    public function SetLanguage($languageCode)
    {
        return $this->SetCurrentLanguage($languageCode);
    }

    /**
     * @param string $languageCode
     * @return bool
     */
    public function IsLanguageSupported($languageCode)
    {
        $languageCode = strtolower(trim($languageCode ?? ''));

        return !empty($languageCode) &&
            (array_key_exists($languageCode, $this->AvailableLanguages) &&
                file_exists($this->LanguageDirectory . $this->AvailableLanguages[$languageCode]->LanguageFile));
    }

    public function GetString($key, $args = []): string
    {
        if (!is_array($args)) {
            $args = [$args];
        }

        $strings = $this->_lang->Strings;

        if ($key === null || $key === '' || !isset($strings[$key]) || empty($strings[$key])) {
            return '?';
        }

        if (empty($args)) {
            return $strings[$key];
        }
        $sprintf_args = '';

        for ($i = 0; $i < count($args); $i++) {
            $sprintf_args .= "'" . addslashes($args[$i]) . "',";
        }

        $sprintf_args = substr($sprintf_args, 0, strlen($sprintf_args) - 1);
        $string = addslashes($strings[$key]);
        $return = eval("return sprintf('$string', $sprintf_args);");
        return $return;
    }

    public function GetDateFormat($key)
    {
        if (array_key_exists($key, $this->systemDateKeys)) {
            return $this->systemDateKeys[$key];
        }

        $dates = $this->_lang->Dates;

        if (!isset($dates[$key]) || empty($dates[$key])) {
            return '?';
        }

        return $dates[$key];
    }

    public function HasDateFormat($key): bool
    {
        if (array_key_exists($key, $this->systemDateKeys)) {
            return true;
        }

        $dates = $this->_lang->Dates;

        return isset($dates[$key]) && !empty($dates[$key]);
    }

    public function GeneralDateFormat()
    {
        return $this->GetDateFormat(ResourceKeys::DATE_GENERAL);
    }

    public function GeneralDateTimeFormat()
    {
        return $this->GetDateFormat(ResourceKeys::DATETIME_GENERAL);
    }

    public function ShortDateTimeFormat()
    {
        return $this->GetDateFormat(ResourceKeys::DATETIME_SHORT);
    }

    public function SystemDateTimeFormat()
    {
        return $this->GetDateFormat(ResourceKeys::DATETIME_SYSTEM);
    }

    public function GetDays($key)
    {
        $days = $this->_lang->Days;

        if (!isset($days[$key]) || empty($days[$key])) {
            return '?';
        }

        return $days[$key];
    }

    public function GetMonths($key)
    {
        $months = $this->_lang->Months;

        if (!isset($months[$key]) || empty($months[$key])) {
            return '?';
        }

        return $months[$key];
    }

    /**
     * @param $languageCode
     * @return bool
     */
    private function SetCurrentLanguage($languageCode)
    {
        $languageCode = strtolower($languageCode);

        if ($languageCode == $this->CurrentLanguage) {
            return true;
        }

        if ($this->IsLanguageSupported($languageCode)) {
            $languageSettings = $this->AvailableLanguages[$languageCode];
            $this->LanguageFile = $languageSettings->LanguageFile;

            require_once($this->LanguageDirectory . $this->LanguageFile);

            $class = $languageSettings->LanguageClass;
            $this->_lang = new $class();
            $this->CurrentLanguage = $languageCode;
            $this->Charset = $this->_lang->Charset;
            $this->HtmlLang = $this->_lang->HtmlLang;
            $this->TextDirection = $this->_lang->TextDirection;

            setlocale(LC_ALL, $this->CurrentLanguage);

            return true;
        }

        return false;
    }

    private function GetLanguageCode()
    {
        $cookie = ServiceLocator::GetServer()->GetCookie(CookieKeys::LANGUAGE);
        if ($cookie != null && $this->IsLanguageSupported($cookie)) {
            return $cookie;
        }

        $userSession = ServiceLocator::GetServer()->GetUserSession();
        if ($this->IsLanguageSupported($userSession->LanguageCode)) {
            return $userSession->LanguageCode;
        }

        $configDefault = Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE);
        if ($this->IsLanguageSupported($configDefault)) {
            return $configDefault;
        }

        // Fall back to hardcoded default (currently 'en_us')
        return ConfigKeys::DEFAULT_LANGUAGE['default'];
    }

    private function LoadAvailableLanguages()
    {
        $allLanguages = AvailableLanguages::GetAvailableLanguages();
        $enabledConfig = Configuration::Instance()->GetKey(ConfigKeys::ENABLED_LANGUAGES);

        // If no enabled.languages configured, all supported languages are available
        if (empty($enabledConfig)) {
            $this->AvailableLanguages = $allLanguages;
            return;
        }

        // Filter to only the languages specified in the config
        $enabledCodes = array_map('trim', explode(',', $enabledConfig));
        $filtered = [];

        foreach ($enabledCodes as $code) {
            $code = strtolower($code);
            if ($code === '') {
                continue;
            }
            if (array_key_exists($code, $allLanguages)) {
                $filtered[$code] = $allLanguages[$code];
            } else {
                Log::Error('Unknown language code in enabled.languages config: %s', $code);
            }
        }

        // If every code was invalid, fall back to all languages to avoid breaking the app
        if (empty($filtered)) {
            Log::Error('All language codes in enabled.languages are invalid, falling back to all languages');
            $this->AvailableLanguages = $allLanguages;
            return;
        }

        // Ensure default.language is in the enabled set. If not, force the
        // hardcoded default (currently 'en_us') so the misconfiguration is
        // visible to the admin — the app appearing in English is an obvious
        // signal that something is wrong.
        $defaultLanguage = strtolower(trim(Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE)));
        if (!array_key_exists($defaultLanguage, $filtered)) {
            $defaultFallback = ConfigKeys::DEFAULT_LANGUAGE['default'];
            Log::Error(
                'default.language "%s" is not in enabled.languages list, falling back to "%s"',
                $defaultLanguage,
                $defaultFallback
            );
            $filtered[$defaultFallback] = $allLanguages[$defaultFallback];
        }

        // Use the order specified in the config so admins control display order
        $this->AvailableLanguages = $filtered;
    }

    private function LoadOverrides()
    {
        $overrideFile = ROOT_DIR . 'config/lang-overrides.php';
        if (file_exists($overrideFile)) {
            global $langOverrides;
            global $langOverridesByLanguage;
            include_once($overrideFile);

            if (!empty($langOverrides)) {
                $this->_lang->Strings = array_merge($this->_lang->Strings, $langOverrides);
            }

            if (!empty($langOverridesByLanguage[$this->CurrentLanguage])) {
                $this->_lang->Strings = array_merge(
                    $this->_lang->Strings,
                    $langOverridesByLanguage[$this->CurrentLanguage]
                );
            }
        }
    }
}
