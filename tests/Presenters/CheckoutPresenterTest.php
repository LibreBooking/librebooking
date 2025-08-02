<?php

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

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeCheckoutPage();
        $this->paymentRepository = new FakePaymentRepository();
        $this->userRepository = new FakeUserRepository();
        $this->paymentLogger = new FakePaymentTransactionLogger();
        $this->presenter = new CheckoutPresenter($this->page, $this->paymentRepository, $this->userRepository, $this->paymentLogger);
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

        $paymentId = "12323";
        $payerId = "23jksdlkf";

        $this->page->_PaymentId = $paymentId;
        $this->page->_PayerId = $payerId;

        $gateway = new FakePayPalGateway();
        $this->paymentRepository->_PayPal = $gateway;
        $gateway->_Payment->status = "COMPLETED";

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
}
