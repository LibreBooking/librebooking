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
     * The one-credit price is the base price. The page offers no delete
     * button for it, and without it the repository falls back to a zero price.
     */
    private const BASE_CREDIT_COUNT = 1;

    /**
     * The largest cost the payment_configuration.credit_cost column can hold.
     * The column is DECIMAL(7, 2) UNSIGNED: seven digits in total, two of
     * them after the decimal point, which leaves five before it.
     */
    private const MAX_CREDIT_COST = 99999.99;
    private const MIN_CREDIT_COST = 0;
    private const CREDIT_COST_DECIMAL_PLACES = 2;

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
        $this->AddAction(
            actionName: ManagePaymentsActions::DELETE_CREDIT_COST,
            actionMethod: 'DeleteCreditCost',
        );
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

        // floatval() turns a missing or malformed cost into 0, which would
        // overwrite the configured price. '0' is numeric, so a zero price can
        // still be set on purpose. A numeric string such as '1e400' overflows
        // to INF, which cannot be stored.
        $submittedCost = $this->page->GetCreditCost();
        if (!is_numeric($submittedCost) || !is_finite(floatval($submittedCost))) {
            Log::Error('Rejected credit cost update with missing or malformed cost. Count=%s', $count);

            return;
        }

        $cost = floatval($submittedCost);

        // Clamping a negative cost to 0 would overwrite the price, as a
        // malformed cost used to. The repository deletes the existing price
        // before inserting the new one, so a cost the column cannot hold would
        // lose the price. Compare the rounded value, as the column rounds
        // 99999.995 up out of range.
        $isAboveMaximum = round(num: $cost, precision: self::CREDIT_COST_DECIMAL_PLACES) > self::MAX_CREDIT_COST;
        if ($cost < self::MIN_CREDIT_COST || $isAboveMaximum) {
            Log::Error('Rejected credit cost update with cost outside the allowed range. Count=%s, Cost=%s', $count, $cost);

            return;
        }

        $currency = $this->page->GetCreditCurrency();

        Log::Debug('Updating credit cost. %s, %s, %s', $count, $cost, $currency);
        $this->paymentRepository->UpdateCreditCost(new CreditCost($count, $cost, $currency));
    }

    public function DeleteCreditCost()
    {
        // intval() would turn a missing count into the one-credit price and
        // '5x' into the five-credit price, so only accept an exact count
        // above the base price, which cannot be deleted.
        $count = filter_var(
            $this->page->GetCreditCount(),
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => self::BASE_CREDIT_COUNT + 1]],
        );
        if ($count === false) {
            Log::Error('Rejected credit cost delete with missing, malformed or base count');

            return;
        }

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
