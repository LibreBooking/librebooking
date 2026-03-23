<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/ChangeLanguagePresenter.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class ChangeLanguagePresenterTest extends TestBase
{
    private FakeChangeLanguagePage $page;
    private FakeUserRepository $userRepository;
    private ChangeLanguagePresenter $presenter;

    public function setUp(): void
    {
        parent::setUp();

        $this->fakeResources->AvailableLanguages = [
            'en_us' => new AvailableLanguage('en_us', 'en_us.php', 'English US'),
            'de_de' => new AvailableLanguage('de_de', 'de_de.php', 'Deutsch'),
            'fr_fr' => new AvailableLanguage('fr_fr', 'fr_fr.php', 'Français'),
        ];

        $this->page = new FakeChangeLanguagePage();
        $this->userRepository = new FakeUserRepository();
        $this->presenter = new ChangeLanguagePresenter(
            page: $this->page,
            userRepository: $this->userRepository,
        );
    }

    public function testChangesLanguageCookieAndSessionAndPersistsToDatabase()
    {
        $userId = 42;
        $newLang = 'de_de';

        $userSession = new FakeUserSession();
        $userSession->UserId = $userId;
        $this->fakeServer->SetUserSession($userSession);

        $fakeUser = new FakeUser($userId);
        $this->userRepository->_User = $fakeUser;

        $this->page->_LanguageCode = $newLang;

        $this->presenter->PageLoad();

        $this->assertTrue($this->page->_SuccessCalled);
        $this->assertFalse($this->page->_ErrorCalled);

        $cookie = $this->fakeServer->GetCookie(CookieKeys::LANGUAGE);
        $this->assertEquals($newLang, $cookie);

        $updatedSession = $this->fakeServer->GetUserSession();
        $this->assertEquals($newLang, $updatedSession->LanguageCode);

        $this->assertNotNull($this->userRepository->_UpdatedUser);
        $this->assertEquals($newLang, $this->userRepository->_UpdatedUser->Language());
    }

    public function testReturnsErrorForUnsupportedLanguage()
    {
        $this->page->_LanguageCode = 'xx_xx';

        $this->presenter->PageLoad();

        $this->assertTrue($this->page->_ErrorCalled);
        $this->assertFalse($this->page->_SuccessCalled);
        $this->assertNull($this->userRepository->_UpdatedUser);
    }

    public function testReturnsErrorForEmptyLanguage()
    {
        $this->page->_LanguageCode = '';

        $this->presenter->PageLoad();

        $this->assertTrue($this->page->_ErrorCalled);
        $this->assertFalse($this->page->_SuccessCalled);
    }

    public function testReturnsErrorForNullLanguage()
    {
        $this->page->_LanguageCode = null;

        $this->presenter->PageLoad();

        $this->assertTrue($this->page->_ErrorCalled);
        $this->assertFalse($this->page->_SuccessCalled);
    }
}

class FakeChangeLanguagePage implements IChangeLanguagePage
{
    public ?string $_LanguageCode = null;
    public bool $_SuccessCalled = false;
    public bool $_ErrorCalled = false;
    public ?string $_ErrorMessage = null;

    public function GetLanguageCode(): ?string
    {
        return $this->_LanguageCode;
    }

    public function SetSuccessResponse(): void
    {
        $this->_SuccessCalled = true;
    }

    public function SetErrorResponse(string $message): void
    {
        $this->_ErrorCalled = true;
        $this->_ErrorMessage = $message;
    }
}
