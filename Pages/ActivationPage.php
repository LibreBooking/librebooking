<?php

require_once(ROOT_DIR . 'Pages/ActionPage.php');
require_once(ROOT_DIR . 'Presenters/ActivationPresenter.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

interface IActivationPage extends IPage
{
    public function ShowSent();
    public function ShowError();

    /**
     * @abstract
     * @return string
     */
    public function GetActivationCode();
}

class ActivationPage extends ActionPage implements IActivationPage
{
    /**
     * @var ActivationPresenter
     */
    private $_presenter;

    public function __construct()
    {
        parent::__construct('AccountActivation');

        $userRepo = new UserRepository();
        $this->_presenter = new ActivationPresenter($this, new AccountActivation($userRepo, $userRepo), new WebAuthentication(PluginManager::Instance()->LoadAuthentication()));
    }

    public function ProcessPageLoad()
    {
        $this->_presenter->PageLoad();
    }

    public function ProcessAction()
    {
        //$this->_presenter->Resend();
    }

    public function ProcessDataRequest($dataRequest)
    {
        // no-op
    }

    public function ShowSent()
    {
        $this->Display('Activation/activation-sent.tpl');
    }

    public function ShowError()
    {
        $this->Display('Activation/activation-error.tpl');
    }


    /**
     * @return string
     */
    public function GetActivationCode()
    {
        return $this->GetQuerystring(QueryStringKeys::ACCOUNT_ACTIVATION_CODE);
    }
}
