<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/Credits/CheckoutPresenter.php');

class CheckoutPresenterTest extends TestBase
{
    /**
     * @var FakeCheckoutPage
     */
    private $page;
    /**
     * @var FakePaymentRepository
     */
    private $paymentRepository;
    /**
     * @var FakeUserRepository
     */
    private $userRepository;
    /**
     * @var CheckoutPresenter
     */
    private $presenter;
    /**
     * @var FakePaymentTransactionLogger
     */
    private $paymentLogger;
    /**
     * @var FakeNanoClaimStore
     */
    private $nanoClaims;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeCheckoutPage();
        $this->paymentRepository = new FakePaymentRepository();
        $this->userRepository = new FakeUserRepository();
        $this->paymentLogger = new FakePaymentTransactionLogger();
        $this->nanoClaims = new FakeNanoClaimStore();
        $this->presenter = new CheckoutPresenter($this->page, $this->paymentRepository, $this->userRepository, $this->paymentLogger, $this->nanoClaims);
    }

    public function testPageLoadCreatesCartAndPresentsPaymentOptions()
    {
        $this->page->_CreditQuantity = 10;
        $this->page->_CreditCount = 1;
        $cost = new CreditCost(1, 5);
        $this->paymentRepository->_CreditCost = [$cost];
        $this->paymentRepository->_PayPal = new PayPalGateway(true, 'client', 'secret', 'live');
        $this->paymentRepository->_Stripe = new StripeGateway(true, 'publish', 'secret');

        $this->presenter->PageLoad($this->fakeUser);

        $this->assertEquals(50, $this->page->_Total);
        $this->assertEquals($cost, $this->page->_CreditCost);
        $this->assertEquals(10, $this->page->_NumberOfCreditsBeingPurchased);
        $expectedCart = new CreditCartSession(10, 5, 'USD', $this->fakeUser->UserId);
        $actualCart = $this->fakeServer->GetSession(SessionKeys::CREDIT_CART);
        $expectedCart->Id = $actualCart->Id = null;
        $this->assertEquals($expectedCart, $actualCart);
        $this->assertEquals(true, $this->page->_PayPalEnabled);
        $this->assertEquals('client', $this->page->_PayPalClientId);
        $this->assertEquals('live', $this->page->_PayPalEnvironment);
        $this->assertEquals(true, $this->page->_StripeEnabled);
        $this->assertEquals('publish', $this->page->_StripePublishableKey);
        $this->assertFalse($this->page->_NanoEnabled);
        $this->assertNull($this->page->_NanoQuote);
    }

    public function testPageLoadQuotesNanoForTheCartWhenEnabled()
    {
        $this->page->_CreditQuantity = 10;
        $this->page->_CreditCount = 1;
        $this->paymentRepository->_CreditCost = [new CreditCost(1, 5)];
        $this->paymentRepository->_Nano = new FakeNanoGateway();

        $this->presenter->PageLoad($this->fakeUser);

        $cart = $this->fakeServer->GetSession(SessionKeys::CREDIT_CART);
        $this->assertTrue($this->page->_NanoEnabled);
        // 50 USD at 0.05 XNO per USD is 2.5 XNO, derived in raw.
        $this->assertEquals('2.5', $this->page->_NanoQuote->amountXno);
        $this->assertEquals('25' . str_repeat('0', 29), $this->page->_NanoQuote->amountRaw);
        $this->assertEquals($cart->Id(), $this->page->_NanoQuote->orderId);
        $this->assertEquals(FakeNanoGateway::ACCOUNT_BURN, $this->page->_NanoQuote->payTo);
    }

    public function testCreatesPayPalPayment()
    {
        $creditCartSession = new CreditCartSession(5, 10, 'USD', $this->fakeUser->UserId);
        $this->fakeServer->SetSession(SessionKeys::CREDIT_CART, $creditCartSession);

        $gateway = new FakePayPalGateway();
        $this->paymentRepository->_PayPal = $gateway;

        $this->presenter->CreatePayPalPayment();

        $this->assertEquals($creditCartSession, $gateway->_PaymentCreatedCart);
        $this->assertEquals($gateway->_Payment, $this->page->_PayPalPayment);
        $this->assertEquals('/checkout.php?paypalaction=payment', $gateway->_ReturnUrl);
        $this->assertEquals('/checkout.php?paypalaction=cancel', $gateway->_CancelUrl);
    }

    public function testExecutesPayPalPayment()
    {
        $creditCartSession = new CreditCartSession(5, 10, 'USD', $this->fakeUser->UserId);
        $this->fakeServer->SetSession(SessionKeys::CREDIT_CART, $creditCartSession);

        $this->userRepository->_User->WithCredits(10);

        $paymentId = '12323';
        $payerId = '23jksdlkf';

        $this->page->_PaymentId = $paymentId;
        $this->page->_PayerId = $payerId;

        $gateway = new FakePayPalGateway();
        $this->paymentRepository->_PayPal = $gateway;
        $gateway->_Payment->status = 'COMPLETED';

        $this->presenter->ExecutePayPalPayment();

        $this->assertEquals($creditCartSession, $gateway->_PaymentCreatedCart);
        $this->assertEquals($gateway->_Payment, $this->page->_PayPalPayment);
        $this->assertEquals($paymentId, $gateway->_PaymentId);
        $this->assertEquals($payerId, $gateway->_PayerId);
        $this->assertNull($this->fakeServer->GetSession(SessionKeys::CREDIT_CART));
        $this->assertEquals(15, $this->userRepository->_UpdatedUser->GetCurrentCredits());
    }

    public function testExecutesStripePayment()
    {
        $creditCartSession = new CreditCartSession(5, 10, 'USD', $this->fakeUser->UserId);
        $this->fakeServer->SetSession(SessionKeys::CREDIT_CART, $creditCartSession);

        $this->userRepository->_User->WithCredits(10);

        $this->page->_StripeToken = '123';
        $gateway = new FakeStripeGateway();
        $this->paymentRepository->_Stripe = $gateway;
        $gateway->_ChargeResponse = true;

        $this->presenter->ExecuteStripePayment();

        $this->assertEquals(true, $this->page->_StripePaymentResult);
        $this->assertEquals('123', $gateway->_Token);
        $this->assertEquals($this->fakeUser->Email, $gateway->_Email);
        $this->assertNull($this->fakeServer->GetSession(SessionKeys::CREDIT_CART));
        $this->assertEquals(15, $this->userRepository->_UpdatedUser->GetCurrentCredits());
    }

    /**
     * @return array<string, mixed>
     */
    private function nanoSendFor(CreditCartSession $cart, FakeNanoGateway $gateway): array
    {
        $quote = $gateway->Quote($cart);
        return [
            'block_account' => 'nano_3t6k35gi95xu6tergt6p69ck76ogmitsa8mnijtpxm9fkcm736xtoncuohr3',
            'amount' => $quote->amountRaw,
            'confirmed' => 'true',
            'subtype' => 'send',
            'local_timestamp' => (string) time(),
            'contents' => ['link_as_account' => $quote->payTo, 'representative' => $quote->representative],
        ];
    }

    public function testExecutesNanoPaymentOnce()
    {
        $cart = new CreditCartSession(5, 10, 'USD', $this->fakeUser->UserId);
        $this->fakeServer->SetSession(SessionKeys::CREDIT_CART, $cart);
        $this->userRepository->_User->WithCredits(10);

        $hash = str_repeat('AB', 32);
        $gateway = new FakeNanoGateway();
        $gateway->_Blocks = [$hash => $this->nanoSendFor($cart, $gateway)];
        $this->paymentRepository->_Nano = $gateway;
        $this->page->_NanoBlockHash = strtolower($hash);

        $this->presenter->ExecuteNanoPayment();

        $this->assertTrue($this->page->_NanoResult);
        $this->assertNull($this->page->_NanoError);
        $this->assertNull($this->fakeServer->GetSession(SessionKeys::CREDIT_CART));
        $this->assertEquals(15, $this->userRepository->_UpdatedUser->GetCurrentCredits());
        $this->assertEquals([$hash => $cart->Id()], $this->nanoClaims->_Claims);
        $this->assertTrue($this->paymentLogger->_PaymentLogged);
    }

    public function testRefusesANanoBlockAlreadyUsedForAnotherCart()
    {
        $cart = new CreditCartSession(5, 10, 'USD', $this->fakeUser->UserId);
        $this->fakeServer->SetSession(SessionKeys::CREDIT_CART, $cart);

        $hash = str_repeat('AB', 32);
        $gateway = new FakeNanoGateway();
        $gateway->_Blocks = [$hash => $this->nanoSendFor($cart, $gateway)];
        $this->paymentRepository->_Nano = $gateway;
        $this->page->_NanoBlockHash = $hash;
        // The same block was credited to an earlier cart, before a restart.
        $this->nanoClaims->_Claims = [$hash => 'an-earlier-cart'];

        $this->presenter->ExecuteNanoPayment();

        $this->assertFalse($this->page->_NanoResult);
        $this->assertNotNull($this->page->_NanoError);
        $this->assertNull($this->userRepository->_UpdatedUser);
        $this->assertEquals($cart, $this->fakeServer->GetSession(SessionKeys::CREDIT_CART));
    }

    public function testRefusesANanoSendOfAnotherAmount()
    {
        $cart = new CreditCartSession(5, 10, 'USD', $this->fakeUser->UserId);
        $this->fakeServer->SetSession(SessionKeys::CREDIT_CART, $cart);

        $hash = str_repeat('CD', 32);
        $gateway = new FakeNanoGateway();
        $short = $this->nanoSendFor($cart, $gateway);
        $short['amount'] = '1';
        $gateway->_Blocks = [$hash => $short];
        $this->paymentRepository->_Nano = $gateway;
        $this->page->_NanoBlockHash = $hash;

        $this->presenter->ExecuteNanoPayment();

        $this->assertFalse($this->page->_NanoResult);
        $this->assertEquals([], $this->nanoClaims->_Claims);
        $this->assertNull($this->userRepository->_UpdatedUser);
    }

    public function testNanoPaymentIsRefusedWhenNotEnabled()
    {
        $cart = new CreditCartSession(5, 10, 'USD', $this->fakeUser->UserId);
        $this->fakeServer->SetSession(SessionKeys::CREDIT_CART, $cart);
        $this->page->_NanoBlockHash = str_repeat('AB', 32);

        $this->presenter->ExecuteNanoPayment();

        $this->assertFalse($this->page->_NanoResult);
        $this->assertEquals([], $this->nanoClaims->_Claims);
    }
}

class FakeCheckoutPage extends CheckoutPage
{
    public $_CreditCount;
    public $_CreditQuantity;
    public $_Total;
    public $_PayPalEnabled;
    public $_PayPalClientId;
    public $_PayPalEnvironment;
    public $_StripeEnabled;
    public $_StripePublishableKey;
    public $_CreditCost;
    public $_NumberOfCreditsBeingPurchased;
    public $_PayPalPayment;
    public $_PaymentId;
    public $_PayerId;
    public $_StripeToken;
    public $_StripePaymentResult;
    public $_NanoEnabled;
    public $_NanoQuote;
    public $_NanoBlockHash;
    public $_NanoResult;
    public $_NanoError;

    public function GetCreditCount()
    {
        return $this->_CreditCount;
    }

    public function GetCreditQuantity()
    {
        return $this->_CreditQuantity;
    }

    public function SetPayPalSettings($enabled, $clientId, $environment)
    {
        $this->_PayPalEnabled = $enabled;
        $this->_PayPalClientId = $clientId;
        $this->_PayPalEnvironment = $environment;
    }

    public function SetStripeSettings($enabled, $publishableKey)
    {
        $this->_StripeEnabled = $enabled;
        $this->_StripePublishableKey = $publishableKey;
    }

    public function SetTotals($total, $cost, $creditQuantity)
    {
        $this->_Total = $total;
        $this->_CreditCost = $cost;
        $this->_NumberOfCreditsBeingPurchased = $creditQuantity;
    }

    public function SetPayPalPayment($payment)
    {
        $this->_PayPalPayment = $payment;
    }

    public function GetPaymentId()
    {
        return $this->_PaymentId;
    }

    public function GetPayerId()
    {
        return $this->_PayerId;
    }

    public function GetStripeToken()
    {
        return $this->_StripeToken;
    }

    public function SetStripeResult($result)
    {
        $this->_StripePaymentResult = $result;
    }

    public function SetNanoSettings($enabled, $quote)
    {
        $this->_NanoEnabled = $enabled;
        $this->_NanoQuote = $quote;
    }

    public function GetNanoBlockHash()
    {
        return $this->_NanoBlockHash;
    }

    public function SetNanoResult($result, $error)
    {
        $this->_NanoResult = $result;
        $this->_NanoError = $error;
    }
}
