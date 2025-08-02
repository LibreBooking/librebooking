<?php

require_once(ROOT_DIR . 'Presenters/Install/Installer.php');
require_once(ROOT_DIR . 'Presenters/Install/MySqlScript.php');
require_once(ROOT_DIR . 'Presenters/Install/InstallationResult.php');
require_once(ROOT_DIR . 'Presenters/Install/InstallSecurityGuard.php');

class InstallPresenter
{
    /**
     * @var IInstallPage
     */
    private $page;

    /**
     * @var InstallSecurityGuard
     */
    private $securityGuard;

    public function __construct(IInstallPage $page, InstallSecurityGuard $securityGuard)
    {
        $this->page = $page;
        $this->securityGuard = $securityGuard;
    }

    public function PageLoad()
    {
        $this->CheckIfScriptUrlMayBeWrong();

        if ($this->page->RunningInstall()) {
            $this->RunInstall();
            return;
        }

        if ($this->page->RunningUpgrade()) {
            $this->RunUpgrade();
            return;
        }

        $dbname = Configuration::Instance()->GetKey(ConfigKeys::DATABASE_NAME);
        $dbuser = Configuration::Instance()->GetKey(ConfigKeys::DATABASE_USER);
        $dbhost = Configuration::Instance()->GetKey(ConfigKeys::DATABASE_HOSTSPEC);

        $this->page->SetDatabaseConfig($dbname, $dbuser, $dbhost);

        $this->CheckForInstallPasswordInConfig();
        $this->CheckForInstallPasswordProvided();
        $this->CheckForAuthentication();
        $this->CheckForUpgrade();
    }

    public function CheckForInstallPasswordInConfig()
    {
        $this->page->SetInstallPasswordMissing(!$this->securityGuard->CheckForInstallPasswordInConfig());
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

    private function CheckForAuthentication()
    {
        if ($this->securityGuard->IsAuthenticated()) {
            $this->page->SetShowDatabasePrompt(true);
            return;
        }

        $this->page->SetShowDatabasePrompt(false);
    }


    private function Validate($installPassword)
    {
        return $this->securityGuard->ValidatePassword($installPassword);
    }

    private function RunInstall()
    {
        $install = new Installer($this->page->GetInstallUser(), $this->page->GetInstallUserPassword());

        $results = $install->InstallFresh($this->page->GetShouldCreateDatabase(), $this->page->GetShouldCreateUser(), $this->page->GetShouldCreateSampleData());
        $install->ClearCachedTemplates();

        $this->page->SetInstallResults($results);
    }

    private function RunUpgrade()
    {
        $install = new Installer($this->page->GetInstallUser(), $this->page->GetInstallUserPassword());
        $results = $install->Upgrade();
        $install->ClearCachedTemplates();

        $this->page->SetUpgradeResults($results, Configuration::VERSION);
    }

    private function CheckForUpgrade()
    {
        $install = new Installer($this->page->GetInstallUser(), $this->page->GetInstallUserPassword());
        $currentVersion = $install->GetVersion();

        if (!$currentVersion) {
            $this->page->ShowInstallOptions(true);
            return;
        }

        if (floatval($currentVersion) < floatval(Configuration::VERSION)) {
            $this->page->SetCurrentVersion($currentVersion);
            $this->page->SetTargetVersion(Configuration::VERSION);
            $this->page->ShowUpgradeOptions(true);
        } else {
            $this->page->ShowUpToDate(true);
            $this->page->ShowInstallOptions(false);
            $this->page->ShowUpgradeOptions(false);
            $this->page->SetShowDatabasePrompt(false);
            $this->page->SetShowPasswordPrompt(false);
            $this->page->SetInstallPasswordMissing(false);
        }
    }

    private function CheckIfScriptUrlMayBeWrong()
    {
        $scriptUrl = Configuration::Instance()->GetScriptUrl();
        $server = ServiceLocator::GetServer();
        $currentUrl = $server->GetUrl();

        $maybeWrong = !BookedStringHelper::Contains($scriptUrl, '/Web') && BookedStringHelper::Contains($currentUrl, '/Web') ;
        if ($maybeWrong) {
            $parts = explode('/Web', $currentUrl);
            $port = $server->GetHeader('SERVER_PORT');
            $suggestedUrl = ($server->GetIsHttps() ? 'https://' : 'http://')
                . $server->GetHeader('SERVER_NAME')
                . ($port == '80' ? '' : $port)
                . $parts[0]
                . '/Web';
            $this->page->ShowScriptUrlWarning($scriptUrl, $suggestedUrl);
        }
    }
}
