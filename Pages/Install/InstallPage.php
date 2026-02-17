<?php

require_once(ROOT_DIR . 'Pages/Page.php');
require_once(ROOT_DIR . 'Presenters/Install/InstallPresenter.php');
require_once(ROOT_DIR . 'lib/Application/Admin/namespace.php');

interface IInstallPage
{
    /**
     * @param bool $isMissing
     */
    public function SetInstallPasswordMissing($isMissing);

    /**
     * @return string
     */
    public function GetInstallPassword();

    /**
     * @param bool $showPasswordPrompt
     */
    public function SetShowPasswordPrompt($showPasswordPrompt);

    /**
     * @param bool $showInvalidPassword
     */
    public function SetShowInvalidPassword($showInvalidPassword);

    /**
     * @param bool $showDatabasePrompt
     */
    public function SetShowDatabasePrompt($showDatabasePrompt);

    /**
     * @param string $dbname
     * @param string $dbuser
     * @param string $dbhost
     */
    public function SetDatabaseConfig($dbname, $dbuser, $dbhost);

    /**
     * @return bool
     */
    public function RunningInstall();

    /**
     * @return bool
     */
    public function RunningUpgrade();

    /**
     * @return string
     */
    public function GetInstallUser();

    /**
     * @return string
     */
    public function GetInstallUserPassword();

    /**
     * @return bool
     */
    public function GetShouldCreateDatabase();

    /**
     * @return bool
     */
    public function GetShouldCreateUser();

    /**
     * @return bool
     */
    public function GetShouldCreateSampleData();

    /**
     * @param $results array|InstallationResult[]
     * @return void
     */
    public function SetInstallResults($results);

    /**
     * @param $results array|InstallationResult[]
     * @param $currentVersion string
     * @return void
     */
    public function SetUpgradeResults($results, $currentVersion);

    /**
     * @param string $currentVersion
     */
    public function SetCurrentVersion($currentVersion);

    /**
     * @param string $targetVersion
     */
    public function SetTargetVersion($targetVersion);

    /**
     * @param bool $showUpgradeOptions
     */
    public function ShowUpgradeOptions($showUpgradeOptions);

    /**
     * @param bool $showInstallOptions
     */
    public function ShowInstallOptions($showInstallOptions);

    /**
     * @param $showUpToDateMessage
     */
    public function ShowUpToDate($showUpToDateMessage);

    /**
     * @param string $currentScriptUrl
     * @param string $suggestedScriptUrl
     */
    public function ShowScriptUrlWarning($currentScriptUrl, $suggestedScriptUrl);
}

class InstallPage extends Page implements IInstallPage
{
    /**
     * @var \InstallPresenter
     */
    private $presenter;

    public function __construct()
    {
        parent::__construct('Install', 1);

        $this->presenter = new InstallPresenter($this, new InstallSecurityGuard());
    }

    public function PageLoad()
    {
        $cacheDirectory = new TemplateCacheDirectory();
        $cacheDirectory->Flush();

        $this->Set('SuggestedInstallPassword', BookedStringHelper::Random());
        $this->Set('ConfigSetting', '$conf[\'settings\'][\'install.password\']');
        $this->Set('ConfigPath', '/config/config.php');
        $this->presenter->PageLoad();
        $this->Display('Install/install.tpl');
    }

    public function SetInstallPasswordMissing($isMissing)
    {
        $this->Set('InstallPasswordMissing', $isMissing);
    }

    public function ShowScriptUrlWarning($currentScriptUrl, $suggestedScriptUrl)
    {
        $this->Set('CurrentScriptUrl', $currentScriptUrl);
        $this->Set('SuggestedScriptUrl', $suggestedScriptUrl);
        $this->Set('ShowScriptUrlWarning', true);
    }

    public function GetInstallPassword()
    {
        return $this->GetForm(FormKeys::INSTALL_PASSWORD);
    }

    public function SetShowPasswordPrompt($showPrompt)
    {
        $this->Set('ShowPasswordPrompt', $showPrompt);
    }

    public function SetShowInvalidPassword($showInvalidPassword)
    {
        $this->Set('ShowInvalidPassword', $showInvalidPassword);
    }

    public function SetShowDatabasePrompt($showDatabasePrompt)
    {
        $this->Set('ShowDatabasePrompt', $showDatabasePrompt);
    }

    /**
     * Set values for displayed template - install.tpl
     * @param string $dbname database name
     * @param string $dbuser mysql user for your database e.g LibreBooking
     * @param string $dbhost server address/name where mySql lives
     */
    public function SetDatabaseConfig($dbname, $dbuser, $dbhost)
    {
        $this->Set('dbname', $dbname);
        $this->Set('dbuser', $dbuser);
        $this->Set('dbhost', $dbhost);
    }

    public function RunningInstall()
    {
        $run_install = $this->GetForm('run_install');
        return !empty($run_install);
    }

    /**
     * @return bool
     */
    public function RunningUpgrade()
    {
        $run_upgrade = $this->GetForm('run_upgrade');
        return !empty($run_upgrade);
    }

    public function GetInstallUser()
    {
        return $this->GetForm(FormKeys::INSTALL_DB_USER);
    }

    public function GetInstallUserPassword()
    {
        return strval($this->GetRawForm(FormKeys::INSTALL_DB_PASSWORD));
    }

    public function GetShouldCreateDatabase()
    {
        $x = $this->GetForm('create_database');
        return isset($x) && $x == true;
    }

    public function GetShouldCreateUser()
    {
        $x = $this->GetForm('create_user');
        return isset($x) && $x == true;
    }

    public function GetShouldCreateSampleData()
    {
        $x = $this->GetForm('create_sample_data');
        return isset($x) && $x == true;
    }

    /**
     * @param $results array|InstallationResult[]
     * @return void
     */
    public function SetInstallResults($results)
    {
        $failure = false;
        foreach ($results as $result) {
            if (!$result->WasSuccessful()) {
                $failure = true;
            }
        }
        // Set installation status
        $this->Set('InstallCompletedSuccessfully', !$failure);
        $this->Set('InstallFailed', $failure);
        $this->Set('installresults', $results);
    }

    /**
     * @param $results array|InstallationResult[]
     * @param $currentVersion string
     * @return void
     */
    public function SetUpgradeResults($results, $currentVersion)
    {
        $failure = false;
        foreach ($results as $result) {
            if (!$result->WasSuccessful()) {
                $failure = true;
            }
        }
        // Set installation status
        $this->Set('UpgradeCompletedSuccessfully', !$failure);
        $this->Set('TargetVersion', $currentVersion);
        $this->Set('InstallFailed', $failure);
        $this->Set('installresults', $results);
    }

    /**
     * @param string $currentVersion
     */
    public function SetCurrentVersion($currentVersion)
    {
        $this->Set('CurrentVersion', $currentVersion);
    }

    /**
     * @param string $targetVersion
     */
    public function SetTargetVersion($targetVersion)
    {
        $this->Set('TargetVersion', $targetVersion);
    }

    /**
     * @param bool $showUpgradeOptions
     */
    public function ShowUpgradeOptions($showUpgradeOptions)
    {
        $this->Set('ShowUpgradeOptions', $showUpgradeOptions);
    }

    /**
     * @param bool $showInstallOptions
     */
    public function ShowInstallOptions($showInstallOptions)
    {
        $this->Set('ShowInstallOptions', $showInstallOptions);
    }

    /**
     * @param $showUpToDateMessage
     */
    public function ShowUpToDate($showUpToDateMessage)
    {
        $this->Set('ShowUpToDateMessage', $showUpToDateMessage);
    }
}
