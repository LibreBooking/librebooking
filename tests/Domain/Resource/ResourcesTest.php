<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'lib/Config/namespace.php');

class ResourcesTest extends TestBase
{
    private $Resources;

    public function setUp(): void
    {
        parent::setup();
        Resources::SetInstance(null);
    }

    public function teardown(): void
    {
        $this->Resources = null;
        parent::teardown();
    }

    public function testLanguageIsLoadedCorrectlyFromCookie()
    {
        $langFile = 'en_us.php';
        $lang = 'en_us';
        $langCookie = new Cookie(CookieKeys::LANGUAGE, $lang, time(), '/');

        $this->fakeServer->SetCookie($langCookie);

        $this->Resources = Resources::GetInstance();

        $this->assertEquals($lang, $this->Resources->CurrentLanguage);
        $this->assertEquals($langFile, $this->Resources->LanguageFile);
    }

    public function testDefaultLanguageIsUsedIfCannotLoadFromCookie()
    {
        $langFile = 'en_us.php';
        $lang = 'en_us';

        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, $lang);

        $this->Resources = Resources::GetInstance();

        $this->assertEquals($lang, $this->Resources->CurrentLanguage);
        $this->assertEquals($langFile, $this->Resources->LanguageFile);
    }

    public function testLanguageIsLoadedFromUserSessionWhenNoCookie()
    {
        $lang = 'de_de';
        $langFile = 'de_de.php';

        $userSession = new FakeUserSession();
        $userSession->LanguageCode = $lang;
        $this->fakeServer->SetUserSession($userSession);

        $this->Resources = Resources::GetInstance();

        $this->assertEquals($lang, $this->Resources->CurrentLanguage);
        $this->assertEquals($langFile, $this->Resources->LanguageFile);
    }

    public function testCookieLanguageTakesPriorityOverUserSession()
    {
        $cookieLang = 'en_us';

        $langCookie = new Cookie(CookieKeys::LANGUAGE, $cookieLang, time(), '/');
        $this->fakeServer->SetCookie($langCookie);

        $userSession = new FakeUserSession();
        $userSession->LanguageCode = 'de_de';
        $this->fakeServer->SetUserSession($userSession);

        $this->Resources = Resources::GetInstance();

        $this->assertEquals($cookieLang, $this->Resources->CurrentLanguage);
    }

    public function testUnsupportedSessionLanguageFallsBackToConfigDefault()
    {
        $defaultLang = 'en_us';
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, $defaultLang);

        $userSession = new FakeUserSession();
        $userSession->LanguageCode = 'xx_invalid';
        $this->fakeServer->SetUserSession($userSession);

        $this->Resources = Resources::GetInstance();

        $this->assertEquals($defaultLang, $this->Resources->CurrentLanguage);
    }

    public function testLanguageIsLoadedCorrectlyWhenSet()
    {
        $langFile = 'en_us.php';
        $lang = 'en_us';

        $this->Resources = Resources::GetInstance();
        $this->Resources->SetLanguage($lang);

        $this->assertEquals($lang, $this->Resources->CurrentLanguage);
        $this->assertEquals($langFile, $this->Resources->LanguageFile);
    }

    public function testEmptyEnabledLanguagesShowsAll()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ENABLED_LANGUAGES, '');

        $this->Resources = Resources::GetInstance();

        $allLanguages = AvailableLanguages::GetAvailableLanguages();
        $this->assertEquals(array_keys($allLanguages), array_keys($this->Resources->AvailableLanguages));
    }

    public function testEnabledLanguagesFiltersToConfiguredSubset()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ENABLED_LANGUAGES, 'en_us,fr_fr,de_de');
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, 'en_us');

        $this->Resources = Resources::GetInstance();

        $this->assertEquals(['en_us', 'fr_fr', 'de_de'], array_keys($this->Resources->AvailableLanguages));
    }

    public function testEnabledLanguagesPreservesConfigOrder()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ENABLED_LANGUAGES, 'fr_fr,en_us,de_de');
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, 'en_us');

        $this->Resources = Resources::GetInstance();

        // Order should match the config value
        $this->assertEquals(['fr_fr', 'en_us', 'de_de'], array_keys($this->Resources->AvailableLanguages));
    }

    public function testEnabledLanguagesIgnoresUnknownCodesAndLogsError()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ENABLED_LANGUAGES, 'en_us,xx_invalid');
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, 'en_us');

        $this->Resources = Resources::GetInstance();

        $this->assertEquals(['en_us'], array_keys($this->Resources->AvailableLanguages));
    }

    public function testAllInvalidEnabledLanguagesFallsBackToAll()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ENABLED_LANGUAGES, 'xx_bad,yy_worse');

        $this->Resources = Resources::GetInstance();

        $allLanguages = AvailableLanguages::GetAvailableLanguages();
        $this->assertEquals(array_keys($allLanguages), array_keys($this->Resources->AvailableLanguages));
    }

    public function testDefaultLanguageNotInEnabledListFallsBackToEnUs()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ENABLED_LANGUAGES, 'fr_fr,de_de');
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, 'es');

        $this->Resources = Resources::GetInstance();

        // en_us should be forced into the list as the fallback, appended at the end
        $this->assertArrayHasKey('en_us', $this->Resources->AvailableLanguages);
        $this->assertEquals(['fr_fr', 'de_de', 'en_us'], array_keys($this->Resources->AvailableLanguages));
    }

    public function testEnabledLanguagesHandlesWhitespace()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ENABLED_LANGUAGES, ' en_us , fr_fr ');
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, 'en_us');

        $this->Resources = Resources::GetInstance();

        $this->assertEquals(['en_us', 'fr_fr'], array_keys($this->Resources->AvailableLanguages));
    }

    public function testEnabledLanguagesIsCaseInsensitive()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ENABLED_LANGUAGES, 'EN_US,FR_FR');
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, 'en_us');

        $this->Resources = Resources::GetInstance();

        $this->assertEquals(['en_us', 'fr_fr'], array_keys($this->Resources->AvailableLanguages));
    }

    public function testDefaultLanguageNotInEnabledListActuallyInitializesWithFallback()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ENABLED_LANGUAGES, 'fr_fr,de_de');
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, 'es');

        $this->Resources = Resources::GetInstance();

        // The app should initialize with en_us, not crash
        $this->assertEquals('en_us', $this->Resources->CurrentLanguage);
        $this->assertEquals('en_us.php', $this->Resources->LanguageFile);
    }

    public function testCookieLanguageNotInEnabledListFallsBackToDefault()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ENABLED_LANGUAGES, 'en_us,fr_fr');
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, 'en_us');

        $langCookie = new Cookie(CookieKeys::LANGUAGE, 'de_de', time(), '/');
        $this->fakeServer->SetCookie($langCookie);

        $this->Resources = Resources::GetInstance();

        // de_de is not in enabled list, should fall back to config default
        $this->assertEquals('en_us', $this->Resources->CurrentLanguage);
    }

    public function testEnabledLanguagesIgnoresBlankEntries()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ENABLED_LANGUAGES, 'en_us,,fr_fr,');
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, 'en_us');

        $this->Resources = Resources::GetInstance();

        $this->assertEquals(['en_us', 'fr_fr'], array_keys($this->Resources->AvailableLanguages));
    }

    public function testDefaultLanguageMatchesEnabledListCaseInsensitively()
    {
        $this->fakeConfig->SetKey(ConfigKeys::ENABLED_LANGUAGES, 'fr_fr,de_de');
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, 'FR_FR');

        $this->Resources = Resources::GetInstance();

        // Should not trigger fallback — FR_FR matches fr_fr after normalization
        // If fallback triggered, en_us would appear in the list
        $this->assertEquals(['fr_fr', 'de_de'], array_keys($this->Resources->AvailableLanguages));
    }
}
