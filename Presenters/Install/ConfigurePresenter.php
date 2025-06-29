<?php

require_once(ROOT_DIR . 'Presenters/Install/InstallSecurityGuard.php');
require_once(ROOT_DIR . 'lib/Config/Configurator.php');

class ConfigurePresenter
{
    /**
     * @var IConfgurePage
     */
    private $page;

    /**
     * @var InstallSecurityGuard
     */
    private $securityGuard;

    public function __construct(IConfgurePage $page, InstallSecurityGuard $securityGuard)
    {
        $this->page = $page;
        $this->securityGuard = $securityGuard;
    }

    public function PageLoad()
    {
        $this->CheckForInstallPasswordInConfig();
        $this->CheckForInstallPasswordProvided();

        $this->Configure();
    }

    private function CheckForInstallPasswordInConfig()
    {
        $this->page->SetPasswordMissing(!$this->securityGuard->CheckForInstallPasswordInConfig());
    }

    private function CheckForInstallPasswordProvided()
    {
        if ($this->securityGuard->IsAuthenticated()) {
            return;
        }

        $installPassword = $this->page->GetInstallPassword();

        if (empty($installPassword)) {
            $this->page->SetShowPasswordPrompt(true);
            return;
        }

        $validated = $this->Validate($installPassword);
        if (!$validated) {
            $this->page->SetShowPasswordPrompt(true);
            $this->page->SetShowInvalidPassword(true);
            return;
        }

        $this->page->SetShowPasswordPrompt(false);
        $this->page->SetShowInvalidPassword(false);
    }

    private function Validate($installPassword)
    {
        return $this->securityGuard->ValidatePassword($installPassword);
    }

    private function Configure()
    {
        if (!$this->securityGuard->IsAuthenticated()) {
            return;
        }
        $user = ServiceLocator::GetServer()->GetUserSession();
        Log::Debug('Editing configuration file. Email=%s, UserId=%s', $user->Email, $user->UserId);

        $configFile = ROOT_DIR . 'config/config.php';
        $configDistFile = ROOT_DIR . 'config/config.example.php';

        $configurator = new Configurator();

        if ($configurator->CanOverwriteFile($configFile)) {
            $configurator->Merge($configFile, $configDistFile);
            $this->page->ShowConfigUpdateSuccess();
        } else {
            $manualConfig = $configurator->GetMergedString($configFile, $configDistFile);
            $this->page->ShowManualConfig($manualConfig);
        }
    }
}
