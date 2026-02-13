<?php

require_once(ROOT_DIR . 'Presenters/Admin/ManagePaymentsPresenter.php');

class ManagePaymentsPresenterTest extends TestBase
{
    /**
     * @var FakeManagePaymentsPage
     */
    private $page;
    /**
     * @var ManagePaymentsPresenter
     */
    private $presenter;
    /**
     * @var FakePaymentRepository
     */
    private $paymentRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeManagePaymentsPage();
        $this->paymentRepository = new FakePaymentRepository();
        $this->presenter = new ManagePaymentsPresenter($this->page, $this->paymentRepository, new FakePaymentTransactionLogger());
    }

    public function testPageLoadSetsCurrentCreditValues()
    {
        $creditCost = new CreditCost(44.4, 'USD');
        $this->paymentRepository->_CreditCost = $creditCost;

        $this->presenter->PageLoad();

        $this->assertEquals($creditCost->Cost(), $this->page->_CreditCost->Cost());
        $this->assertEquals($creditCost->Currency(), $this->page->_CreditCost->Currency());
    }

    public function testPageLoadSetsGatewayValues()
    {
        $paypalGateway = new PayPalGateway(true, 'client', 'secret', 'environment');
        $stripeGateway = new StripeGateway(true, 'publishable', 'secret');

        $this->paymentRepository->_PayPal = $paypalGateway;
        $this->paymentRepository->_Stripe = $stripeGateway;

        $this->presenter->PageLoad();

        $this->assertEquals($paypalGateway->IsEnabled(), $this->page->_PayPalEnabled);
        $this->assertEquals($paypalGateway->ClientId(), $this->page->_PayPalClientId);
        $this->assertEquals($paypalGateway->Secret(), $this->page->_PayPalSecret);
        $this->assertEquals($paypalGateway->Environment(), $this->page->_PayPalEnvironment);

        $this->assertEquals($stripeGateway->IsEnabled(), $this->page->_StripeEnabled);
        $this->assertEquals($stripeGateway->PublishableKey(), $this->page->_StripePublishableKey);
        $this->assertEquals($stripeGateway->SecretKey(), $this->page->_StripeSecretKey);
    }

    public function testSettingCreditCost()
    {
        $this->page->_CreditCost = 30.5;
        $this->page->_CreditCurrency = 'USD';

        $this->presenter->UpdateCreditCost();

        $this->assertEquals(new CreditCost(1, $this->page->_CreditCost, $this->page->_CreditCurrency), $this->paymentRepository->_LastCost);
    }

    public function testUpdatesPaymentGateways()
    {
        $this->page->_PayPalEnabled = true;
        $this->page->_PayPalClientId = 'clientid';
        $this->page->_PayPalSecret = 'secret';
        $this->page->_PayPalEnvironment = 'sandbox';
        $this->page->_StripeEnabled = false;
        $this->page->_StripePublishableKey = 'something to erase';
        $this->page->_StripeSecretKey = 'something else to erase';

        $this->presenter->UpdatePaymentGateways();

        $paypalGateway = new PayPalGateway($this->page->_PayPalEnabled, $this->page->_PayPalClientId, $this->page->_PayPalSecret, $this->page->_PayPalEnvironment);
        $stripeGateway = new StripeGateway($this->page->_StripeEnabled, $this->page->_StripePublishableKey, $this->page->_StripeSecretKey);
        $this->assertEquals($paypalGateway, $this->paymentRepository->_LastPayPal);
        $this->assertEquals($stripeGateway, $this->paymentRepository->_LastStripe);
    }

    public function testGetTransactionLog()
    {
        $this->page->_CurrentPage = 10;
        $this->page->_PageSize = 50;

        $this->paymentRepository->_TransactionLogs = new PageableData([
            $this->GetTransactionLogView()
        ]);

        $this->presenter->GetTransactionLog();

        $this->assertEquals($this->paymentRepository->_TransactionLogs, $this->page->_TransactionLog);
        $this->assertEquals(10, $this->paymentRepository->_LastPage);
        $this->assertEquals(50, $this->paymentRepository->_LastPageSize);
        $this->assertEquals(-1, $this->paymentRepository->_LastUserId);
    }

    public function testGetsTransactionDetails()
    {
        $this->page->_TransactionLogId = 10;
        $this->paymentRepository->_TransactionLogView = $this->GetTransactionLogView();

        $this->presenter->GetTransactionDetails();

        $this->assertEquals($this->paymentRepository->_TransactionLogs, $this->page->_TransactionLog);
        $this->assertEquals(10, $this->paymentRepository->_LastTransactionLogId);
        $this->assertEquals($this->paymentRepository->_TransactionLogView, $this->page->_TransactionLogView);
    }

    public function testIssuesPayPalRefund()
    {
        $this->page->_RefundTransactionLogId = 10;
        $this->page->_RefundAmount = 100;
        $this->paymentRepository->_TransactionLogView = $this->GetTransactionLogView();

        $gateway = $this->paymentRepository->_PayPal;
        $gateway->_Refund->state = 'completed';

        $this->presenter->IssueRefund();

        $this->assertEquals($this->paymentRepository->_TransactionLogView, $gateway->_LastTransactionView);
        $this->assertEquals(100, $gateway->_LastRefundAmount);
        $this->assertEquals(true, $this->page->_RefundIssued);
    }

    public function testIssuesStripeRefund()
    {
        $this->page->_RefundTransactionLogId = 10;
        $this->page->_RefundAmount = 100;
        $this->paymentRepository->_TransactionLogView = $this->GetTransactionLogView(10.6, .33, PaymentGateways::STRIPE);

        $gateway = $this->paymentRepository->_Stripe;
        $gateway->_Refunded = true;

        $this->presenter->IssueRefund();

        $this->assertEquals($this->paymentRepository->_TransactionLogView, $gateway->_LastTransactionView);
        $this->assertEquals(100, $gateway->_LastRefundAmount);
        $this->assertEquals(true, $this->page->_RefundIssued);
    }

    /**
     * @return TransactionLogView
     */
    private function GetTransactionLogView($amount = 10.6, $fee = .33, $gateway = 'PayPal')
    {
        return new TransactionLogView(
            Date::Now(),
            'status',
            'invoice',
            'txid',
            $amount,
            $fee,
            'USD',
            'selfref',
            'refundref',
            'gatewaydate',
            $gateway,
            100
        );
    }
}

class FakeManagePaymentsPage extends ManagePaymentsPage
{
    public $_CreditCost;
    public $_CreditCurrency;
    public $_PayPalEnabled;
    public $_PayPalClientId;
    public $_PayPalSecret;
    public $_PayPalEnvironment;
    public $_StripeEnabled;
    public $_StripePublishableKey;
    public $_StripeSecretKey;
    public $_CurrentPage;
    public $_PageSize;
    public $_TransactionLog;
    public $_TransactionLogId;
    public $_TransactionLogView;
    public $_RefundAmount;
    public $_RefundIssued;
    public $_RefundTransactionLogId;

    public function __construct()
    {
        parent::__construct();
    }

    public function GetCreditCost()
    {
        return $this->_CreditCost;
    }

    public function GetCreditCurrency()
    {
        return $this->_CreditCurrency;
    }

    public function SetCreditCosts($cost)
    {
        $this->_CreditCost = $cost;
    }

    public function GetPayPalIsEnabled()
    {
        return $this->_PayPalEnabled;
    }

    public function GetPayPalClientId()
    {
        return $this->_PayPalClientId;
    }

    public function GetPayPalSecret()
    {
        return $this->_PayPalSecret;
    }

    public function GetPayPalEnvironment()
    {
        return $this->_PayPalEnvironment;
    }

    public function GetStripeIsEnabled()
    {
        return $this->_StripeEnabled;
    }

    public function GetStripePublishableKey()
    {
        return $this->_StripePublishableKey;
    }

    public function GetStripeSecretKey()
    {
        return $this->_StripeSecretKey;
    }

    public function SetPayPalSettings($enabled, $clientId, $secret, $environment)
    {
        $this->_PayPalEnabled = $enabled;
        $this->_PayPalClientId = $clientId;
        $this->_PayPalSecret = $secret;
        $this->_PayPalEnvironment = $environment;
    }

    public function SetStripeSettings($enabled, $publishableKey, $secretKey)
    {
        $this->_StripeEnabled = $enabled;
        $this->_StripePublishableKey = $publishableKey;
        $this->_StripeSecretKey = $secretKey;
    }

    public function GetPageNumber()
    {
        return $this->_CurrentPage;
    }

    public function GetPageSize()
    {
        return $this->_PageSize;
    }

    public function BindTransactionLog($transactionLog)
    {
        $this->_TransactionLog = $transactionLog;
    }

    public function GetTransactionLogId()
    {
        return $this->_TransactionLogId;
    }

    public function GetRefundTransactionLogId()
    {
        return $this->_RefundTransactionLogId;
    }

    public function GetRefundAmount()
    {
        return $this->_RefundAmount;
    }

    public function BindTransactionLogView(TransactionLogView $transactionLogView)
    {
        $this->_TransactionLogView = $transactionLogView;
    }

    public function BindRefundIssued($wasIssued)
    {
        $this->_RefundIssued = $wasIssued;
    }
}
