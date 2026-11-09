<?php

require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'Pages/IPageable.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Presenters/Admin/CreditLogPresenter.php');

interface ICreditLogPage extends IPageable
{
    public function GetUserId(): ?int;

    /**
     * @param CreditLogView[] $credits
     */
    public function BindCredits($credits);

    /**
     * @param string $fullName
     */
    public function BindUserName($fullName);

    /**
     * @return void
     */
    public function ShowError();
}

class CreditLogPage extends ActionPage implements ICreditLogPage
{
    /**
     * @var CreditLogPresenter
     */
    private $presenter;
    private $pageable;

    public function __construct()
    {
        $this->pageable = new PageablePage($this);

        $this->presenter = new CreditLogPresenter($this, new CreditRepository(), new UserRepository());
        parent::__construct('CreditHistory', 1);
    }

    public function ProcessAction()
    {
    }

    public function ProcessDataRequest($dataRequest)
    {
    }

    public function ProcessPageLoad()
    {
        $this->presenter->PageLoad(ServiceLocator::GetServer()->GetUserSession());

        $this->Display('Admin/Users/credit_log.tpl');
    }

    public function GetPageNumber()
    {
        return $this->pageable->GetPageNumber();
    }

    public function GetPageSize()
    {
        return $this->pageable->GetPageSize();
    }

    public function BindPageInfo(PageInfo $pageInfo)
    {
        $this->Set('PageInfo', $pageInfo);
    }

    public function GetUserId(): ?int
    {
        return $this->GetQuerystringInt(key: QueryStringKeys::USER_ID);
    }

    public function BindCredits($credits)
    {
        $this->Set('CreditLog', $credits);
    }

    public function BindUserName($fullName)
    {
        $this->Set('UserName', $fullName);
    }

    public function ShowError()
    {
        $this->Set('ShowError', true);
    }
}
