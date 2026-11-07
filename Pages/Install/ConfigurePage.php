<?php

require_once(ROOT_DIR . 'Pages/Page.php');
require_once(ROOT_DIR . 'Presenters/Install/ConfigurePresenter.php');

interface IConfgurePage
{
    /**
     * @abstract
     * @param bool $isPasswordMissing
     */
    public function SetPasswordMissing($isPasswordMissing);

    /**
     * @abstract
     * @return string
     */
    public function GetInstallPassword();

    /**
     * @abstract
     * @param bool $showPasswordPrompt
     */
    public function SetShowPasswordPrompt($showPasswordPrompt);

    /**
     * @abstract
     * @param bool $showInvalidPassword
     */
    public function SetShowInvalidPassword($showInvalidPassword);

    /**
     * @abstract
     */
    public function ShowConfigUpdateSuccess();

    /**
     * @abstract
     * @param string $manualConfig
     */
    public function ShowManualConfig($manualConfig);
}

class ConfigurePage extends Page implements IConfgurePage
{
    /**
     * @var ConfigurePresenter
     */
    private $presenter;

    public function __construct()
    {
        parent::__construct('Install', 1);

        $this->presenter = new ConfigurePresenter($this, new InstallSecurityGuard());
    }

    public function PageLoad()
    {
        $this->Set('SuggestedInstallPassword', BookedStringHelper::Random());
        $this->Set('ConfigSetting', '$conf[\'settings\'][\'install.password\']');
        $this->Set('ConfigPath', '/config/config.php');
        $this->presenter->PageLoad();
        $this->Display('Install/configure.tpl');
    }

    public function SetPasswordMissing($isPasswordMissing)
    {
        $this->Set('InstallPasswordMissing', $isPasswordMissing);
    }

    public function GetInstallPassword()
    {
        $password = $this->GetRawForm(FormKeys::INSTALL_PASSWORD);

        return is_string($password) ? $password : '';
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

    public function ShowConfigUpdateSuccess()
    {
        $this->Set('ShowConfigSuccess', true);
    }

    /**
     * @param string $manualConfig
     */
    public function ShowManualConfig($manualConfig)
    {
        $this->Set('ShowManualConfig', true);
        $this->Set('ManualConfig', $manualConfig);
    }
}
