<?php

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
