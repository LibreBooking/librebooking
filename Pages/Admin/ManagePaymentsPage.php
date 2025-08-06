<?php

require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'Pages/IPageable.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManagePaymentsPresenter.php');
require_once(ROOT_DIR . 'Domain/Access/PaymentRepository.php');
require_once(ROOT_DIR . 'Domain/Values/Currency.php');

interface IManagePaymentsPage extends IActionPage
{
    /**
     * @return string
     */
    public function GetCreditCount();
    /**
     * @return string
     */
    public function GetCreditCost();

    /**
     * @return string
     */
    public function GetCreditCurrency();

    /**
     * @param array $creditCosts
     */
    public function SetCreditCosts($creditCosts);

    /**
     * @return bool
     */
    public function GetPayPalIsEnabled();

    /**
     * @return string
     */
    public function GetPayPalClientId();

    /**
     * @return string
     */
    public function GetPayPalSecret();

    /**
     * @return string
     */
    public function GetPayPalEnvironment();

    /**
     * @return bool
     */
    public function GetStripeIsEnabled();

    /**
     * @return string
     */
    public function GetStripePublishableKey();

    /**
     * @return string
     */
    public function GetStripeSecretKey();

    /**
     * @param bool $enabled
     * @param string $clientId
     * @param string $secret
     * @param string $environment
     */
    public function SetPayPalSettings($enabled, $clientId, $secret, $environment);

    /**
     * @param string $enabled
     * @param string $publishableKey
     * @param string $secretKey
     */
    public function SetStripeSettings($enabled, $publishableKey, $secretKey);

    /**
     * @return string
     */
    public function GetPageNumber();

    /**
     * @return string
     */
    public function GetPageSize();

    /**
     * @param PageableData|TransactionLogView[] $transactionLog
     */
    public function BindTransactionLog($transactionLog);

    /**
     * @return int
     */
    public function GetTransactionLogId();

    /**
     * @return int
     */
    public function GetRefundTransactionLogId();

    /**
     * @return float
     */
    public function GetRefundAmount();

    /**
     * @param TransactionLogView $transactionLogView
     */
    public function BindTransactionLogView(TransactionLogView $transactionLogView);

    /**
     * @param int $wasIssued
     */
    public function BindRefundIssued($wasIssued);
}

class ManagePaymentsPage extends ActionPage implements IManagePaymentsPage
{
    /**
     * @var ManagePaymentsPresenter
     */
    private $presenter;
    /**
     * @var PageablePage
     */
    private $pageable;

    public function __construct()
    {
        parent::__construct('ManagePayments', 1);
        $paymentRepository = new PaymentRepository();
        $this->presenter = new ManagePaymentsPresenter($this, $paymentRepository, new PaymentTransactionLogger());
        $this->pageable = new PageablePage($this);
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
        $paymentsEnabled = Configuration::Instance()->GetKey(ConfigKeys::CREDITS_ALLOW_PURCHASE, new BooleanConverter());

        $this->Set('Currencies', \Booked\Currency::Currencies());
        $this->Set('PaymentsEnabled', $paymentsEnabled);
        $this->presenter->PageLoad();
        $this->Display('Admin/Payments/manage_payments.tpl');
    }

    public function GetCreditCount()
    {
        return $this->GetForm(FormKeys::CREDIT_COUNT);
    }

    public function GetCreditCost()
    {
        return $this->GetForm(FormKeys::CREDIT_COST);
    }

    public function GetCreditCurrency()
    {
        return $this->GetForm(FormKeys::CREDIT_CURRENCY);
    }

    public function SetCreditCosts($creditCosts)
    {
        $this->Set('CreditCosts', $creditCosts);
    }

    public function GetPayPalIsEnabled()
    {
        return $this->GetCheckbox(FormKeys::PAYPAL_ENABLED);
    }

    public function GetPayPalClientId()
    {
        return $this->GetForm(FormKeys::PAYPAL_CLIENT_ID);
    }

    public function GetPayPalSecret()
    {
        return $this->GetForm(FormKeys::PAYPAL_SECRET);
    }

    public function GetPayPalEnvironment()
    {
        return $this->GetForm(FormKeys::PAYPAL_ENVIRONMENT);
    }

    public function GetStripeIsEnabled()
    {
        return $this->GetCheckbox(FormKeys::STRIPE_ENABLED);
    }

    public function GetStripePublishableKey()
    {
        return $this->GetForm(FormKeys::STRIPE_PUBLISHABLE_KEY);
    }

    public function GetStripeSecretKey()
    {
        return $this->GetForm(FormKeys::STRIPE_SECRET_KEY);
    }

    public function SetPayPalSettings($enabled, $clientId, $secret, $environment)
    {
        $this->Set('PayPalEnabled', (int)$enabled);
        $this->Set('PayPalClientId', $clientId);
        $this->Set('PayPalSecret', $secret);
        $this->Set('PayPalEnvironment', $environment);
    }

    public function SetStripeSettings($enabled, $publishableKey, $secretKey)
    {
        $this->Set('StripeEnabled', (int)$enabled);
        $this->Set('StripePublishableKey', $publishableKey);
        $this->Set('StripeSecretKey', $secretKey);
    }

    public function BindTransactionLog($transactionLog)
    {
        $this->Set('TransactionLog', $transactionLog->Results());
        $this->Set('PageInfo', $transactionLog->PageInfo());
        $this->Display('Admin/Payments/transaction_log.tpl');
    }

    public function GetPageNumber()
    {
        return $this->pageable->GetPageNumber();
    }

    public function GetPageSize()
    {
        return $this->pageable->GetPageSize();
    }

    public function GetTransactionLogId()
    {
        return intval($this->GetQuerystring(QueryStringKeys::TRANSACTION_LOG_ID));
    }

    public function GetRefundTransactionLogId()
    {
        return intval($this->GetForm(FormKeys::REFUND_TRANSACTION_ID));
    }

    public function GetRefundAmount()
    {
        return floatval($this->GetForm(FormKeys::REFUND_AMOUNT));
    }

    public function BindTransactionLogView(TransactionLogView $transactionLogView)
    {
        $this->SetJson($transactionLogView);
    }

    public function BindRefundIssued($wasIssued)
    {
        $this->SetJson($wasIssued);
    }
}
