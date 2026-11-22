<?php

declare(strict_types=1);

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
        $creditCost = new CreditCost(count: 1, cost: 44.4, currency: 'USD');
        $this->paymentRepository->_CreditCost = [$creditCost];

        $this->presenter->PageLoad();

        $this->assertEquals($creditCost->Cost(), $this->page->_CreditCost[0]->Cost());
        $this->assertEquals($creditCost->Currency(), $this->page->_CreditCost[0]->Currency());
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
        $this->page->_CreditCost = '30.5';
        $this->page->_CreditCurrency = 'USD';

        $this->presenter->UpdateCreditCost();

        $this->assertEquals(
            new CreditCost(count: 1, cost: 30.5, currency: 'USD'),
            $this->paymentRepository->_LastCost,
        );
    }

    public function testSettingCreditCostToZero()
    {
        $this->page->_CreditCost = '0';
        $this->page->_CreditCurrency = 'USD';

        $this->presenter->UpdateCreditCost();

        $this->assertEquals(
            new CreditCost(count: 1, cost: 0.0, currency: 'USD'),
            $this->paymentRepository->_LastCost,
        );
        $this->assertFalse($this->page->_CreditCostRejected);
    }

    public function testSettingCreditCostToTheColumnMaximum()
    {
        $this->page->_CreditCost = '99999.99';
        $this->page->_CreditCurrency = 'USD';

        $this->presenter->UpdateCreditCost();

        $this->assertEquals(
            new CreditCost(count: 1, cost: 99999.99, currency: 'USD'),
            $this->paymentRepository->_LastCost,
        );
    }

    /**
     * @return array<string, array{mixed}>
     */
    public static function invalidCreditCostProvider(): array
    {
        return [
            'missing' => [null],
            'empty' => [''],
            'not a number' => ['abc'],
            // Numeric, but overflows a float to INF.
            'overflow' => ['1e400'],
            // Must not be clamped to a zero price.
            'negative' => ['-1'],
            // credit_cost is DECIMAL(7, 2), so the maximum is 99999.99.
            'above the column maximum' => ['100000'],
            'rounds above the column maximum' => ['99999.995'],
            // credit_cost[]=5 arrives as an array.
            'array' => [['5']],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('invalidCreditCostProvider')]
    public function testDoesNotUpdateCreditCostWithInvalidCost(mixed $cost)
    {
        $this->page->_CreditCost = $cost;
        $this->page->_CreditCurrency = 'USD';

        $this->presenter->UpdateCreditCost();

        $this->assertNull($this->paymentRepository->_LastCost);
        $this->assertNull($this->paymentRepository->_LastDeletedCreditCount);
        $this->assertTrue($this->page->_CreditCostRejected, 'the client must not report success');
    }

    /**
     * @return array<string, array{mixed}>
     */
    public static function invalidCreditCurrencyProvider(): array
    {
        return [
            'missing' => [null],
            'empty' => [''],
            'unknown' => ['XXX'],
            // The column holds ten characters.
            'too long for the column' => ['USDOLLARSXXX'],
            // credit_currency[]=USD arrives as an array.
            'array' => [['USD']],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('invalidCreditCurrencyProvider')]
    public function testDoesNotUpdateCreditCostWithInvalidCurrency(mixed $currency)
    {
        $this->page->_CreditCost = '30.5';
        $this->page->_CreditCurrency = $currency;

        $this->presenter->UpdateCreditCost();

        $this->assertNull($this->paymentRepository->_LastCost);
        $this->assertNull($this->paymentRepository->_LastDeletedCreditCount, 'the old price must be untouched');
        $this->assertTrue($this->page->_CreditCostRejected, 'the client must not report success');
    }

    public function testDeleteActionDeletesCreditCost()
    {
        // The delete form posts only the credit count.
        $this->fakeServer->SetQuerystring(QueryStringKeys::ACTION, ManagePaymentsActions::DELETE_CREDIT_COST);
        $this->fakeServer->SetForm(FormKeys::CREDIT_COUNT, '5');

        $this->presenter->ProcessAction();

        $this->assertSame(5, $this->paymentRepository->_LastDeletedCreditCount);
        $this->assertNull($this->paymentRepository->_LastCost, 'the cost must not be rewritten');
        $this->assertFalse($this->page->_CreditCostRejected);
    }

    /**
     * @return array<string, array{mixed}>
     */
    public static function invalidCreditCountProvider(): array
    {
        return [
            // intval() would make this the one-credit price.
            'missing' => [null],
            'empty' => [''],
            'not a number' => ['abc'],
            // intval() would make this the five-credit price.
            'trailing text' => ['5x'],
            'zero' => ['0'],
            // The base price, which the page offers no delete button for.
            'base price' => ['1'],
            'negative' => ['-5'],
            'array' => [['5']],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('invalidCreditCountProvider')]
    public function testDeleteActionDeletesNothingWithInvalidCount(mixed $count)
    {
        $this->fakeServer->SetQuerystring(QueryStringKeys::ACTION, ManagePaymentsActions::DELETE_CREDIT_COST);
        if ($count !== null) {
            $this->fakeServer->SetForm(FormKeys::CREDIT_COUNT, $count);
        }

        $this->presenter->ProcessAction();

        $this->assertNull($this->paymentRepository->_LastDeletedCreditCount);
        $this->assertNull($this->paymentRepository->_LastCost);
        $this->assertTrue($this->page->_CreditCostRejected, 'the client must not report success');
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
        $this->assertFalse($this->page->_PaymentGatewaysRejected);
    }

    public function testDisablesPaymentGatewaysWithoutCredentials()
    {
        // The form does not submit the fields of a disabled gateway.
        $this->page->_PayPalEnabled = false;
        $this->page->_StripeEnabled = false;

        $this->presenter->UpdatePaymentGateways();

        $this->assertEquals(
            new PayPalGateway(enabled: false, clientId: null, secret: null, environment: null),
            $this->paymentRepository->_LastPayPal,
        );
        $this->assertEquals(
            new StripeGateway(enabled: false, publishableKey: null, secretKey: null),
            $this->paymentRepository->_LastStripe,
        );
        $this->assertFalse($this->page->_PaymentGatewaysRejected);
    }

    /**
     * @return array<string, array{mixed}>
     */
    public static function invalidGatewaySettingProvider(): array
    {
        return [
            'missing' => [null],
            'empty' => [''],
            // setting_value is VARCHAR(1000).
            'too long for the column' => [str_repeat('a', 1001)],
            // A utf8 (three-byte) column rejects a four-byte character.
            'four-byte character' => ["secret\u{1F600}"],
            'invalid UTF-8' => ["secret\xFF"],
            // The field submitted as name[] arrives as an array.
            'array' => [['secret']],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('invalidGatewaySettingProvider')]
    public function testDoesNotUpdateEitherGatewayWithInvalidPayPalSetting(mixed $secret)
    {
        $this->page->_PayPalEnabled = true;
        $this->page->_PayPalClientId = 'clientid';
        $this->page->_PayPalSecret = $secret;
        $this->page->_PayPalEnvironment = 'sandbox';
        $this->page->_StripeEnabled = true;
        $this->page->_StripePublishableKey = 'publishable';
        $this->page->_StripeSecretKey = 'secret';

        $this->presenter->UpdatePaymentGateways();

        $this->assertNull($this->paymentRepository->_LastPayPal);
        $this->assertNull($this->paymentRepository->_LastStripe);
        $this->assertTrue($this->page->_PaymentGatewaysRejected, 'the client must not report success');
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('invalidGatewaySettingProvider')]
    public function testDoesNotUpdateEitherGatewayWithInvalidStripeSetting(mixed $secretKey)
    {
        $this->page->_PayPalEnabled = true;
        $this->page->_PayPalClientId = 'clientid';
        $this->page->_PayPalSecret = 'secret';
        $this->page->_PayPalEnvironment = 'sandbox';
        $this->page->_StripeEnabled = true;
        $this->page->_StripePublishableKey = 'publishable';
        $this->page->_StripeSecretKey = $secretKey;

        $this->presenter->UpdatePaymentGateways();

        $this->assertNull($this->paymentRepository->_LastPayPal);
        $this->assertNull($this->paymentRepository->_LastStripe);
        $this->assertTrue($this->page->_PaymentGatewaysRejected, 'the client must not report success');
    }

    public function testGatewaySettingLengthDoesNotDependOnTheInternalEncoding()
    {
        // Counted as UTF-16, 1002 ASCII bytes are 501 characters, which
        // would pass a limit of 1000.
        $tooLong = str_repeat('a', 1002);
        $this->page->_PayPalEnabled = true;
        $this->page->_PayPalClientId = 'clientid';
        $this->page->_PayPalSecret = $tooLong;
        $this->page->_PayPalEnvironment = 'sandbox';
        $this->page->_StripeEnabled = false;

        $internalEncoding = mb_internal_encoding();
        mb_internal_encoding('UTF-16LE');
        try {
            $this->presenter->UpdatePaymentGateways();
        } finally {
            mb_internal_encoding($internalEncoding);
        }

        $this->assertNull($this->paymentRepository->_LastPayPal);
        $this->assertNull($this->paymentRepository->_LastStripe);
        $this->assertTrue($this->page->_PaymentGatewaysRejected);
    }

    public function testStoresAGatewaySettingWithThreeByteCharacters()
    {
        // U+20AC takes three bytes in UTF-8, which a utf8 column can hold.
        $secret = "secret\u{20AC}";
        $this->page->_PayPalEnabled = true;
        $this->page->_PayPalClientId = 'clientid';
        $this->page->_PayPalSecret = $secret;
        $this->page->_PayPalEnvironment = 'sandbox';
        $this->page->_StripeEnabled = false;

        $this->presenter->UpdatePaymentGateways();

        $this->assertSame($secret, $this->paymentRepository->_LastPayPal->Secret());
        $this->assertFalse($this->page->_PaymentGatewaysRejected);
    }

    public function testStoresAGatewaySettingOfTheMaximumLength()
    {
        $secret = str_repeat('a', 1000);
        $this->page->_PayPalEnabled = true;
        $this->page->_PayPalClientId = 'clientid';
        $this->page->_PayPalSecret = $secret;
        $this->page->_PayPalEnvironment = 'sandbox';
        $this->page->_StripeEnabled = false;

        $this->presenter->UpdatePaymentGateways();

        $this->assertSame($secret, $this->paymentRepository->_LastPayPal->Secret());
        $this->assertFalse($this->page->_PaymentGatewaysRejected);
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

        /** @var FakePayPalGateway $gateway */
        $gateway = $this->paymentRepository->_PayPal;
        $gateway->_Refund->state = 'completed';

        $this->presenter->IssueRefund();

        $this->assertEquals($this->paymentRepository->_TransactionLogView, $gateway->_LastTransactionView);
        $this->assertEquals(100, $gateway->_LastRefundAmount);
        $this->assertEquals(true, $this->page->_RefundIssued);
    }

    public function testRefundIsNotIssuedWhenTransactionLogDoesNotExist(): void
    {
        $this->page->_RefundTransactionLogId = 999;
        $this->page->_RefundAmount = 100;
        $this->paymentRepository->_TransactionLogView = null;

        $this->presenter->IssueRefund();

        $gateway = $this->paymentRepository->_PayPal;
        $this->assertInstanceOf(FakePayPalGateway::class, $gateway);
        $this->assertFalse($this->page->_RefundIssued);
        $this->assertNull($gateway->_LastTransactionView);
    }

    public function testPayPalRefundIsNotIssuedWhenGatewayReturnsNoObject(): void
    {
        $this->page->_RefundTransactionLogId = 10;
        $this->page->_RefundAmount = 100;
        $this->paymentRepository->_TransactionLogView = $this->GetTransactionLogView();
        $gateway = $this->paymentRepository->_PayPal;
        $this->assertInstanceOf(FakePayPalGateway::class, $gateway);
        $gateway->_Refund = null;

        $this->presenter->IssueRefund();

        $this->assertFalse($this->page->_RefundIssued);
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
    public $_CreditCostRejected = false;
    public $_PaymentGatewaysRejected = false;
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

    public function EnforceCSRFCheck()
    {
        // The real check reads the request method, which tests do not set.
    }

    public function BindCreditCostRejected(): void
    {
        $this->_CreditCostRejected = true;
    }

    public function BindPaymentGatewaysRejected(): void
    {
        $this->_PaymentGatewaysRejected = true;
    }

    /**
     * Mirrors GetFormString(): a value that is not a string, such as a
     * field submitted as name[], reads as null.
     */
    private function AsFormString(mixed $value): ?string
    {
        return is_string($value) ? $value : null;
    }

    public function GetCreditCost(): ?string
    {
        return $this->AsFormString(value: $this->_CreditCost);
    }

    public function GetCreditCurrency(): ?string
    {
        return $this->AsFormString(value: $this->_CreditCurrency);
    }

    public function SetCreditCosts($creditCosts)
    {
        $this->_CreditCost = $creditCosts;
    }

    public function GetPayPalIsEnabled()
    {
        return $this->_PayPalEnabled;
    }

    public function GetPayPalClientId(): ?string
    {
        return $this->AsFormString(value: $this->_PayPalClientId);
    }

    public function GetPayPalSecret(): ?string
    {
        return $this->AsFormString(value: $this->_PayPalSecret);
    }

    public function GetPayPalEnvironment(): ?string
    {
        return $this->AsFormString(value: $this->_PayPalEnvironment);
    }

    public function GetStripeIsEnabled()
    {
        return $this->_StripeEnabled;
    }

    public function GetStripePublishableKey(): ?string
    {
        return $this->AsFormString(value: $this->_StripePublishableKey);
    }

    public function GetStripeSecretKey(): ?string
    {
        return $this->AsFormString(value: $this->_StripeSecretKey);
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

    public function GetPageNumber(): int
    {
        return $this->_CurrentPage;
    }

    public function GetPageSize(): int
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
