<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'Presenters/ChangeLanguagePresenter.php');

class ChangeLanguagePage extends SecurePage implements IChangeLanguagePage
{
    private ChangeLanguagePresenter $presenter;

    public function __construct()
    {
        parent::__construct('', 1);

        $this->presenter = new ChangeLanguagePresenter(
            page: $this,
            userRepository: new UserRepository(),
        );
    }

    public function PageLoad(): void
    {
        $this->EnforceCSRFCheck();
        $this->presenter->PageLoad();
    }

    public function GetLanguageCode(): ?string
    {
        return $this->GetForm(FormKeys::LANGUAGE);
    }

    public function SetSuccessResponse(): void
    {
        $this->SetJson(['success' => true]);
    }

    public function SetErrorResponse(string $message): void
    {
        $this->SetJson(['success' => false], [$message]);
    }
}
