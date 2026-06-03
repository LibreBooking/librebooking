<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');

interface IChangeLanguagePage
{
    public function GetLanguageCode(): ?string;

    public function SetSuccessResponse(): void;

    public function SetErrorResponse(string $message): void;
}

class ChangeLanguagePresenter
{
    public function __construct(
        private IChangeLanguagePage $page,
        private IUserRepository $userRepository,
    ) {
    }

    public function PageLoad(): void
    {
        $languageCode = $this->page->GetLanguageCode();
        $resources = Resources::GetInstance();

        if (empty($languageCode) || !$resources->IsLanguageSupported($languageCode)) {
            $this->page->SetErrorResponse('Invalid language');
            return;
        }

        $resources->SetLanguage($languageCode);

        ServiceLocator::GetServer()->SetCookie(
            new Cookie(CookieKeys::LANGUAGE, $languageCode, secure: false)
        );

        $server = ServiceLocator::GetServer();
        $userSession = $server->GetUserSession();

        if ($userSession->IsLoggedIn()) {
            $userSession->LanguageCode = $languageCode;
            $server->SetUserSession($userSession);

            $user = $this->userRepository->LoadById($userSession->UserId);
            $user->ChangeLanguage($languageCode);
            $this->userRepository->Update($user);
        }

        $this->page->SetSuccessResponse();
    }
}
