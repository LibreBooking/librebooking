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
}
