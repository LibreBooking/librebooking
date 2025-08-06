<?php

require_once(ROOT_DIR . 'Presenters/Credits/UserCreditsPresenter.php');
require_once(ROOT_DIR . 'Pages/IPageable.php');
require_once(ROOT_DIR . 'Pages/SecurePage.php');

interface IUserCreditsPage extends IPage, IActionPage
{
    /**
     * @param float $credits
     */
    public function SetCurrentCredits($credits);

    /**
     * @param CreditCost[] $costs
     */
    public function SetCreditCosts($costs);

    /**
     * @param CreditCost $cost
     */
    public function SetCreditCost(CreditCost $cost);

    /**
     * @return float
     */
    public function GetQuantity();

    /**
     * @return int
     */
    public function GetCount();

    /**
     * @param string $formattedTotal
     */
    public function SetTotalCost($formattedTotal);

    /**
     * @return string
     */
    public function GetPageNumber();

    /**
     * @return string
     */
    public function GetPageSize();

    /**
     * @param PageableData|CreditLogView[] $creditLog
     */
    public function BindCreditLog($creditLog);

    /**
     * @param PageableData|TransactionLogView[] $transactionLog
     */
    public function BindTransactionLog($transactionLog);
}

class UserCreditsPage extends ActionPage implements IUserCreditsPage
{
    /**
     * @var UserCreditsPresenter
     */
    private $presenter;
    /**
     * @var PageablePage
     */
    private $pageable;

    public function __construct()
    {
        parent::__construct('Credits');
        $this->Set('AllowPurchasingCredits', Configuration::Instance()->GetKey(ConfigKeys::CREDITS_ALLOW_PURCHASE, new BooleanConverter()));

        $this->pageable = new PageablePage($this);

        $this->presenter = new UserCreditsPresenter($this, new UserRepository(), new PaymentRepository(), new CreditRepository());
    }

    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    public function ProcessDataRequest($dataRequest)
    {
        $this->presenter->ProcessDataRequest($dataRequest, ServiceLocator::GetServer()->GetUserSession());
    }

    public function ProcessPageLoad()
    {
        $this->presenter->PageLoad(ServiceLocator::GetServer()->GetUserSession());
        $this->Display('Credits/user_credits.tpl');
    }

    public function SetCurrentCredits($credits)
    {
        $this->Set('CurrentCredits', $credits);
    }

    public function SetCreditCosts($costs)
    {
        $this->Set('CreditCosts', $costs);
    }

    public function SetCreditCost(CreditCost $cost)
    {
        $this->Set('CreditCost', $cost->FormatCurrency());
        $this->Set('IsCreditCostSet', $cost->Cost() > 0);
    }

    public function GetQuantity()
    {
        return $this->GetQuerystring(QueryStringKeys::QUANTITY);
    }

    public function GetCount()
    {
        return $this->GetQuerystring(QueryStringKeys::COUNT);
    }

    public function SetTotalCost($formattedTotal)
    {
        $this->SetJson($formattedTotal);
    }

    public function GetPageNumber()
    {
        return $this->pageable->GetPageNumber();
    }

    public function GetPageSize()
    {
        return $this->pageable->GetPageSize();
    }

    public function BindCreditLog($creditLog)
    {
        $this->Set('CreditLog', $creditLog->Results());
        $this->Set('PageInfo', $creditLog->PageInfo());
        $this->Display('Credits/credit_log.tpl');
    }

    public function BindTransactionLog($transactionLog)
    {
        $this->Set('TransactionLog', $transactionLog->Results());
        $this->Set('PageInfo', $transactionLog->PageInfo());
        $this->Display('Credits/transaction_log.tpl');
    }
}
