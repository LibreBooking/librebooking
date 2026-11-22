<?php

require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'Pages/IPageable.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManagePaymentsPresenter.php');
require_once(ROOT_DIR . 'Domain/Access/PaymentRepository.php');
require_once(ROOT_DIR . 'Domain/Values/Currency.php');

interface IManagePaymentsPage extends IActionPage
{
    /**
     * @return string|null
     */
    public function GetCreditCount();
    /**
     * @return string|null
     */
    public function GetCreditCost();

    /**
     * @return string|null
     */
    public function GetCreditCurrency();

    /**
     * @param array $creditCosts
     */
    public function SetCreditCosts($creditCosts);

    /**
     * Tells the client that a credit cost update or delete was rejected, so
     * it does not report success.
     */
    public function BindCreditCostRejected(): void;

    /**
     * Tells the client that a payment gateway update was rejected, so it does
     * not report success.
     */
    public function BindPaymentGatewaysRejected(): void;

    /**
     * @return bool
     */
    public function GetPayPalIsEnabled();

    /**
     * @return string|null
     */
    public function GetPayPalClientId();

    /**
     * @return string|null
     */
    public function GetPayPalSecret();

    /**
     * @return string|null
     */
    public function GetPayPalEnvironment();

    /**
     * @return bool
     */
    public function GetStripeIsEnabled();

    /**
     * @return string|null
     */
    public function GetStripePublishableKey();

    /**
     * @return string|null
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
     * @param bool $enabled
     * @param string $publishableKey
     * @param string $secretKey
     */
    public function SetStripeSettings($enabled, $publishableKey, $secretKey);

    public function GetPageNumber(): int;

    public function GetPageSize(): int;

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
     * @param bool $wasIssued
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

    public function GetCreditCount(): ?string
    {
        return $this->GetFormString(key: FormKeys::CREDIT_COUNT);
    }

    public function GetCreditCost(): ?string
    {
        return $this->GetFormString(key: FormKeys::CREDIT_COST);
    }

    public function GetCreditCurrency(): ?string
    {
        return $this->GetFormString(key: FormKeys::CREDIT_CURRENCY);
    }

    public function SetCreditCosts($creditCosts)
    {
        $this->Set('CreditCosts', $creditCosts);
    }

    public function GetPayPalIsEnabled()
    {
        return $this->GetCheckbox(FormKeys::PAYPAL_ENABLED);
    }

    public function GetPayPalClientId(): ?string
    {
        return $this->GetGatewaySetting(key: FormKeys::PAYPAL_CLIENT_ID);
    }

    public function GetPayPalSecret(): ?string
    {
        return $this->GetGatewaySetting(key: FormKeys::PAYPAL_SECRET);
    }

    public function GetPayPalEnvironment(): ?string
    {
        return $this->GetGatewaySetting(key: FormKeys::PAYPAL_ENVIRONMENT);
    }

    public function GetStripeIsEnabled()
    {
        return $this->GetCheckbox(FormKeys::STRIPE_ENABLED);
    }

    public function GetStripePublishableKey(): ?string
    {
        return $this->GetGatewaySetting(key: FormKeys::STRIPE_PUBLISHABLE_KEY);
    }

    public function GetStripeSecretKey(): ?string
    {
        return $this->GetGatewaySetting(key: FormKeys::STRIPE_SECRET_KEY);
    }

    /**
     * Reads a gateway setting, escaped as GetForm() escapes every form value.
     *
     * That escaping replaces a byte that is not valid UTF-8 with U+FFFD,
     * which would store a different credential from the one submitted. Check
     * the submitted bytes first and return null for such a value, so the
     * presenter rejects it like a missing one.
     */
    private function GetGatewaySetting(string $key): ?string
    {
        $submitted = $this->GetRawForm(var: $key);
        if (is_string($submitted) && !mb_check_encoding(value: $submitted, encoding: 'UTF-8')) {
            return null;
        }

        return $this->GetFormString(key: $key);
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

    public function GetPageNumber(): int
    {
        return $this->pageable->GetPageNumber();
    }

    public function GetPageSize(): int
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

    public function BindCreditCostRejected(): void
    {
        $this->SetRejectedResponse();
    }

    public function BindPaymentGatewaysRejected(): void
    {
        $this->SetRejectedResponse();
    }

    private function SetRejectedResponse(): void
    {
        // The payment forms treat a response as success unless it is non-empty
        // text or, as jQuery parses this JSON, an object with ErrorIds. Only
        // then do they call their error handler, which shows its own message.
        $this->SetJson(objectToSerialize: ['ErrorIds' => [], 'Messages' => []]);
    }

    public function BindRefundIssued($wasIssued)
    {
        $this->SetJson($wasIssued);
    }
}
