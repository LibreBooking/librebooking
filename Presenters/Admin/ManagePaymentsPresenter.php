<?php

require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'Pages/Admin/ManagePaymentsPage.php');
require_once(ROOT_DIR . 'Domain/Access/PaymentRepository.php');

class ManagePaymentsActions
{
    public const UPDATE_CREDIT_COST = 'updateCreditCost';
    public const DELETE_CREDIT_COST = 'deleteCreditCost';
    public const UPDATE_PAYMENT_GATEWAYS = 'updatePaymentGateways';
    public const ISSUE_REFUND = 'issueRefund';
}

class ManagePaymentsPresenter extends ActionPresenter
{
    /**
     * @var IManagePaymentsPage
     */
    private $page;
    /**
     * @var IPaymentRepository
     */
    private $paymentRepository;
    /**
     * @var IPaymentTransactionLogger
     */
    private $paymentLogger;

    public function __construct(IManagePaymentsPage $page, IPaymentRepository $paymentRepository, IPaymentTransactionLogger $logger)
    {
        parent::__construct($page);
        $this->page = $page;
        $this->paymentRepository = $paymentRepository;
        $this->paymentLogger = $logger;

        $this->AddAction(ManagePaymentsActions::UPDATE_CREDIT_COST, 'UpdateCreditCost');
        $this->AddAction(ManagePaymentsActions::DELETE_CREDIT_COST, 'UpdateCreditCost');
        $this->AddAction(ManagePaymentsActions::UPDATE_PAYMENT_GATEWAYS, 'UpdatePaymentGateways');
        $this->AddAction(ManagePaymentsActions::ISSUE_REFUND, 'IssueRefund');
    }

    public function PageLoad()
    {
        $costs = $this->paymentRepository->GetCreditCosts();
        $this->page->SetCreditCosts($costs);

        $paypal = $this->paymentRepository->GetPayPalGateway();
        $stripe = $this->paymentRepository->GetStripeGateway();

        $this->page->SetPayPalSettings($paypal->IsEnabled(), $paypal->ClientId(), $paypal->Secret(), $paypal->Environment());
        $this->page->SetStripeSettings($stripe->IsEnabled(), $stripe->PublishableKey(), $stripe->SecretKey());
    }

    public function UpdateCreditCost()
    {
        $count = max(1, intval($this->page->GetCreditCount()));
        $cost = max(0, floatval($this->page->GetCreditCost()));
        $currency = $this->page->GetCreditCurrency();

        Log::Debug('Updating credit cost. %s, %s, %s', $count, $cost, $currency);
        $this->paymentRepository->UpdateCreditCost(new CreditCost($count, $cost, $currency));
    }

    public function DeleteCreditCost()
    {
        $count = max(1, intval($this->page->GetCreditCount()));

        Log::Debug('Deleting credit cost. %s', $count);
        $this->paymentRepository->DeleteCreditCost($count);
    }

    public function UpdatePaymentGateways()
    {
        $paypalGateway = new PayPalGateway($this->page->GetPayPalIsEnabled(), $this->page->GetPayPalClientId(), $this->page->GetPayPalSecret(), $this->page->GetPayPalEnvironment());
        $stripeGateway = new StripeGateway($this->page->GetStripeIsEnabled(), $this->page->GetStripePublishableKey(), $this->page->GetStripeSecretKey());

        Log::Debug('Updating payment gateways');

        $this->paymentRepository->UpdatePayPalGateway($paypalGateway);
        $this->paymentRepository->UpdateStripeGateway($stripeGateway);
    }

    public function ProcessDataRequest($dataRequest, $userSession)
    {
        if ($dataRequest == 'transactionLog') {
            $this->GetTransactionLog();
        } else {
            $this->GetTransactionDetails();
        }
    }

    public function GetTransactionLog()
    {
        $page = $this->page->GetPageNumber();
        $size = $this->page->GetPageSize();
        $log = $this->paymentRepository->GetList($page, $size);

        $this->page->BindTransactionLog($log);
    }

    public function GetTransactionDetails()
    {
        $id = $this->page->GetTransactionLogId();
        $transactionLogView = $this->paymentRepository->GetTransactionLogView($id);
        if ($transactionLogView != null) {
            $this->page->BindTransactionLogView($transactionLogView);
        }
    }

    public function IssueRefund()
    {
        $id = $this->page->GetRefundTransactionLogId();
        $amount = $this->page->GetRefundAmount();
        $transactionLogView = $this->paymentRepository->GetTransactionLogView($id);

        if ($transactionLogView === null) {
            Log::Error('Cannot issue refund, transaction log %s not found', $id);
            $this->page->BindRefundIssued(false);
            return;
        }

        if ($transactionLogView->GatewayName == PaymentGateways::PAYPAL) {
            $gateway = $this->paymentRepository->GetPayPalGateway();
            $refund = $gateway->Refund($transactionLogView, $amount, $this->paymentLogger);

            // Refund() returns null on failure, or the raw body when PayPal does not return JSON.
            // Checking the v1 "state" field on a v2 response is a known bug, see:
            // https://github.com/LibreBooking/librebooking/issues/1873
            $this->page->BindRefundIssued(is_object($refund) && ($refund->state ?? null) == 'completed');
        } else {
            $gateway = $this->paymentRepository->GetStripeGateway();

            $refunded = $gateway->Refund($transactionLogView, $amount, $this->paymentLogger);

            $this->page->BindRefundIssued($refunded);
        }
    }
}
