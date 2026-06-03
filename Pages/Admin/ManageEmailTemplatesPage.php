<?php

require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManageEmailTemplatesPresenter.php');

interface IManageEmailTemplatesPage extends IActionPage
{
    /**
     * @return string
     */
    public function GetLanguage();

    /**
     * @param EmailTemplateFile[] $templates
     */
    public function BindTemplateNames($templates);

    /**
     * @param string $languageCode
     */
    public function SetSelectedLanguage($languageCode);

    /**
     * @return string
     */
    public function GetTemplateName();

    /**
     * @param string $templateContents
     */
    public function BindTemplate($templateContents);

    /**
     * @return string
     */
    public function GetTemplateContents();

    /**
     * @param bool $saveResult
     */
    public function SetSaveResult($saveResult);

    /**
     * @return string
     */
    public function GetUpdatedTemplateName();

    public function GetUpdatedLanguage(): string;
}

class ManageEmailTemplatesPage extends ActionPage implements IManageEmailTemplatesPage
{
    private $presenter;

    public function __construct()
    {
        parent::__construct('ManageEmailTemplates', 1);
        $this->presenter = new ManageEmailTemplatesPresenter($this);
    }

    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    public function ProcessDataRequest($dataRequest)
    {
        $this->presenter->ProcessDataRequest($dataRequest);
    }

    public function ProcessPageLoad()
    {
        $this->Set('Languages', Resources::GetInstance()->AvailableLanguages);

        $this->presenter->PageLoad();

        $this->Display('Admin/Configuration/manage_email_templates.tpl');
    }

    public function GetUpdatedLanguage(): string
    {
        return $this->GetForm(FormKeys::EMAIL_TEMPLATE_LANGUAGE);
    }

    public function GetLanguage()
    {
        return $this->GetQuerystring(QueryStringKeys::LANGUAGE);
    }

    public function SetSelectedLanguage($languageCode)
    {
        $this->Set('Language', $languageCode);
    }

    public function BindTemplateNames($templates)
    {
        $this->Set('Templates', $templates);
    }

    public function GetTemplateName()
    {
        return $this->GetQuerystring(QueryStringKeys::EMAIL_TEMPLATE_NAME);
    }

    public function GetUpdatedTemplateName()
    {
        return $this->GetForm(FormKeys::EMAIL_TEMPLATE_NAME);
    }

    public function BindTemplate($templateContents)
    {
        $this->SetJson($templateContents);
    }

    public function GetTemplateContents()
    {
        return $this->server->GetRawForm(FormKeys::EMAIL_CONTENTS);
    }

    public function SetSaveResult($saveResult)
    {
        $this->SetJson(['saveResult' => $saveResult]);
    }
}
