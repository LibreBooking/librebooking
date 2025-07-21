<?php

require_once(ROOT_DIR . 'Pages/ActionPage.php');
require_once(ROOT_DIR . 'Pages/SecurePage.php');

class AdminPageDecorator extends ActionPage implements IActionPage
{
    /**
     * @var ActionPage
     */
    private $page;

    public function __construct(ActionPage $page)
    {
        $this->page = $page;
    }

    public function PageLoad()
    {
        $user = ServiceLocator::GetServer()->GetUserSession();

        if (!$this->page->IsAuthenticated() || !$user->IsAdmin) {
            $this->RedirectResume(sprintf("%s%s?%s=%s", $this->page->path, Pages::LOGIN, QueryStringKeys::REDIRECT, urlencode($this->page->server->GetUrl())));
            die();
        }

        $this->page->PageLoad();
    }

    public function IsValid()
    {
        return $this->page->IsValid();
    }

    public function ProcessAction()
    {
        $this->page->ProcessAction();
    }

    public function ProcessDataRequest($dataRequest)
    {
        $this->page->ProcessDataRequest($dataRequest);
    }

    public function ProcessPageLoad()
    {
        $this->page->ProcessPageLoad();
    }
}

abstract class AdminPage extends SecurePage implements IActionPage
{
    public function __construct($titleKey = '', $pageDepth = 1)
    {
        parent::__construct($titleKey, $pageDepth);

        $user = ServiceLocator::GetServer()->GetUserSession();

        if (!$user->IsAdmin) {
            $this->RedirectResume(sprintf("%s%s?%s=%s", $this->path, Pages::LOGIN, QueryStringKeys::REDIRECT, urlencode($this->server->GetUrl())));
            die();
        }
    }

    public function Display($adminTemplateName)
    {
        parent::Display('Admin/' . $adminTemplateName);
    }

    public function TakingAction()
    {
        $action = $this->GetAction();
        return !empty($action);
    }

    public function RequestingData()
    {
        $dataRequest = $this->GetDataRequest();
        return !empty($dataRequest);
    }

    public function GetAction()
    {
        return $this->GetQuerystring(QueryStringKeys::ACTION);
    }

    public function GetDataRequest()
    {
        return $this->GetQuerystring(QueryStringKeys::DATA_REQUEST);
    }

    public function IsValid()
    {
        if (parent::IsValid()) {
            Log::Debug('Action passed all validations');
            return true;
        }

        $errors = new ActionErrors();
        $inlineErrors = [];

        foreach ($this->smarty->failedValidators as $validator) {
            Log::Debug('Failed validator %s', $validator);
            $errors->Add($validator);

            if ($validator->ReturnsErrorResponse()) {
                http_response_code(400);
                $inlineErrors = array_merge($validator->Messages(), $inlineErrors);
            }
        }

        if (!empty($inlineErrors)) {
            $this->SetJson(implode(',', $inlineErrors));
        } else {
            $this->SetJson($errors);
        }
        return false;
    }
}
