<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Config/namespace.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'Pages/Credits/CheckoutPage.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'lib/Server/CreditCartSession.php');

class CheckoutPresenter extends ActionPresenter
{
    /**
     * @var ICheckoutPage
     */
    private $page;

    /**
     * @var IPaymentRepository
     */
    private $paymentRepository;

    /**
     * @var IUserRepository
     */
    private $userRepository;
    /**
     * @var IPaymentTransactionLogger
     */
    private $paymentLogger;

    public function __construct(ICheckoutPage $page, IPaymentRepository $paymentRepository, IUserRepository $userRepository, IPaymentTransactionLogger $paymentLogger)
    {
        parent::__construct($page);

        $this->page = $page;
        $this->paymentRepository = $paymentRepository;
        $this->userRepository = $userRepository;
        $this->paymentLogger = $paymentLogger;

        $this->AddAction('executePayPalPayment', 'ExecutePayPalPayment');
        $this->AddAction('createPayPalPayment', 'CreatePayPalPayment');
        $this->AddAction('executeStripePayment', 'ExecuteStripePayment');
    }

    public function PageLoad(UserSession $userSession)
    {
        $creditCount = floatval($this->page->GetCreditCount());
        $creditQuantity = floatval($this->page->GetCreditQuantity());

        $costs = $this->paymentRepository->GetCreditCosts();
        // Find the cost that matches the credit count
        foreach ($costs as $c) {
            if ($c->Count() == $creditCount) {
                $cost = $c;
                break;
            }
        }
        if (!isset($cost)) {
            Log::Error('Could not find cost for credit count %s', $creditCount);
            return;
        }

        $paypal = $this->paymentRepository->GetPayPalGateway();
        $stripe = $this->paymentRepository->GetStripeGateway();

        $total = $cost->GetTotal($creditQuantity);

        if ($creditQuantity == 0) {
            $this->page->SetEmptyCart(true);
        } else {
            $this->page->SetTotals($total, $cost, $creditQuantity);
            $this->page->SetPayPalSettings($paypal->IsEnabled(), $paypal->ClientId(), $paypal->Environment());
            $this->page->SetStripeSettings($stripe->IsEnabled(), $stripe->PublishableKey());

            ServiceLocator::GetServer()->SetSession(SessionKeys::CREDIT_CART, new CreditCartSession($creditQuantity * $creditCount, $cost->Cost() / $creditCount, $cost->Currency(), $userSession->UserId));
        }
    }

    public function CreatePayPalPayment()
    {
        $gateway = $this->paymentRepository->GetPayPalGateway();
        $baseUrl = new Url(Configuration::Instance()->GetScriptUrl());
        $returnUrl = $baseUrl->Copy()->Add(Pages::CHECKOUT)->AddQueryString(QueryStringKeys::PAYPAL_ACTION, PayPalGateway::ACTION_PAYMENT);
        $cancelUrl = $baseUrl->Copy()->Add(Pages::CHECKOUT)->AddQueryString(QueryStringKeys::PAYPAL_ACTION, PayPalGateway::ACTION_CANCEL);

        $cart = ServiceLocator::GetServer()->GetSession(SessionKeys::CREDIT_CART);
        $payment = $gateway->CreatePayment($cart, $returnUrl->ToString(), $cancelUrl->ToString());

        $this->page->SetPayPalPayment($payment);
    }

    public function ExecutePayPalPayment()
    {
        $gateway = $this->paymentRepository->GetPayPalGateway();

        /** @var CreditCartSession $cart  */
        $cart = ServiceLocator::GetServer()->GetSession(SessionKeys::CREDIT_CART);
        $payment = $gateway->ExecutePayment($cart, $this->page->GetPaymentId(), $this->page->GetPayerId(), $this->paymentLogger);

        if (isset($payment->status) && $payment->status == "COMPLETED") {
            $user = $this->userRepository->LoadById(ServiceLocator::GetServer()->GetUserSession()->UserId);
            $user->AddCredits($cart->Quantity, Resources::GetInstance()->GetString('NoteCreditsPurchased'));
            $this->userRepository->Update($user);
            ServiceLocator::GetServer()->SetSession(SessionKeys::CREDIT_CART, null);
        }

        $this->page->SetPayPalPayment($payment);
    }

    public function ExecuteStripePayment()
    {
        $token = $this->page->GetStripeToken();
        $userSession = ServiceLocator::GetServer()->GetUserSession();

        $gateway = $this->paymentRepository->GetStripeGateway();

        /** @var CreditCartSession $cart  */
        $cart = ServiceLocator::GetServer()->GetSession(SessionKeys::CREDIT_CART);
        $result = $gateway->Charge($cart, $userSession->Email, $token, $this->paymentLogger);

        if ($result == true) {
            $user = $this->userRepository->LoadById($userSession->UserId);
            $user->AddCredits($cart->Quantity, Resources::GetInstance()->GetString('NoteCreditsPurchased'));
            $this->userRepository->Update($user);
            ServiceLocator::GetServer()->SetSession(SessionKeys::CREDIT_CART, null);
        }

        $this->page->SetStripeResult($result);
    }
}
