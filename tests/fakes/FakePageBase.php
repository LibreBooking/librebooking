<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/IPage.php');
require_once(ROOT_DIR . 'Pages/ActionPage.php');

class FakePageBase implements IPage
{
    public $_RedirectCalled = false;
    public $_RedirectDestination = '';
    public $_IsPostBack = false;
    public $_IsValid = true;
    public $_Validators = [];
    public $_LastPage = '';
    public $_InlineEditValidators = [];

    public function Redirect($destination)
    {
        $this->_RedirectCalled = true;
        $this->_RedirectDestination = $destination;
    }

    public function RedirectToError($errorMessageId = ErrorMessages::UNKNOWN_ERROR, $lastPage = '')
    {
        // implement me?
    }

    public function IsPostBack()
    {
        return $this->_IsPostBack;
    }

    public function IsValid()
    {
        return $this->_IsValid;
    }

    public function RegisterValidator($validatorId, $validator)
    {
        $this->_Validators[$validatorId] = $validator;
    }

    public function RegisterInlineEditValidator($validatorId, $validator)
    {
        $this->_InlineEditValidators[$validatorId] = $validator;
    }

    public function GetLastPage($defaultPage = '')
    {
        return $this->_LastPage;
    }

    public function PageLoad()
    {
        // TODO: Implement PageLoad() method.
    }

    public function EnforceCSRFCheck()
    {
        // TODO: Implement EnforceCSRFCheck() method.
    }

    public function GetSortField()
    {
        // TODO: Implement GetSortField() method.
    }

    public function GetSortDirection()
    {
        // TODO: Implement GetSortDirection() method.
    }
}

class FakeActionPageBase extends FakePageBase implements IActionPage
{
    public function TakingAction()
    {
        // TODO: Implement TakingAction() method.
    }

    public function GetAction()
    {
        // TODO: Implement GetAction() method.
    }

    public function RequestingData()
    {
        // TODO: Implement RequestingData() method.
    }

    public function GetDataRequest()
    {
        // TODO: Implement GetDataRequest() method.
    }

    public function EnforceCSRFCheck()
    {
        // TODO: Implement EnforceCSRFCheck() method.
    }
}
