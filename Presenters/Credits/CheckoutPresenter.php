<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Config/namespace.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'Pages/Credits/CheckoutPage.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'lib/Server/CreditCartSession.php');

use LibreBooking\Payment\Nano\ClaimStore;
use LibreBooking\Payment\Nano\NanoException;

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
    /**
     * @var ClaimStore
     */
    private $nanoClaims;

    public function __construct(
        ICheckoutPage $page,
        IPaymentRepository $paymentRepository,
        IUserRepository $userRepository,
        IPaymentTransactionLogger $paymentLogger,
        ?ClaimStore $nanoClaims = null
    ) {
        parent::__construct($page);

        $this->page = $page;
        $this->paymentRepository = $paymentRepository;
        $this->userRepository = $userRepository;
        $this->paymentLogger = $paymentLogger;
        $this->nanoClaims = $nanoClaims ?? new NanoPaymentClaimRepository();

        $this->AddAction('executePayPalPayment', 'ExecutePayPalPayment');
        $this->AddAction('createPayPalPayment', 'CreatePayPalPayment');
        $this->AddAction('executeStripePayment', 'ExecuteStripePayment');
        $this->AddAction('executeNanoPayment', 'ExecuteNanoPayment');
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
        $nano = $this->paymentRepository->GetNanoGateway();

        $total = $cost->GetTotal($creditQuantity);

        if ($creditQuantity == 0) {
            $this->page->SetEmptyCart(true);
        } else {
            $this->page->SetTotals($total, $cost, $creditQuantity);
            $this->page->SetPayPalSettings($paypal->IsEnabled(), $paypal->ClientId(), $paypal->Environment());
            $this->page->SetStripeSettings($stripe->IsEnabled(), $stripe->PublishableKey());

            $cart = new CreditCartSession($creditQuantity * $creditCount, $cost->Cost() / $creditCount, $cost->Currency(), $userSession->UserId);
            ServiceLocator::GetServer()->SetSession(SessionKeys::CREDIT_CART, $cart);

            $nanoQuote = null;
            if ($nano->IsEnabled()) {
                try {
                    $nanoQuote = $nano->Quote($cart);
                } catch (InvalidArgumentException $ex) {
                    Log::Error('Nano quote failed for cart %s: %s', $cart->Id(), $ex->getMessage());
                }
            }
            $this->page->SetNanoSettings($nanoQuote !== null, $nanoQuote);
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

        if (isset($payment->status) && $payment->status == 'COMPLETED') {
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

    public function ExecuteNanoPayment()
    {
        $userSession = ServiceLocator::GetServer()->GetUserSession();
        $gateway = $this->paymentRepository->GetNanoGateway();

        /** @var CreditCartSession|null $cart  */
        $cart = ServiceLocator::GetServer()->GetSession(SessionKeys::CREDIT_CART);

        if (!$gateway->IsEnabled() || $cart == null || $cart->UserId != $userSession->UserId) {
            $this->page->SetNanoResult(false, null);
            return;
        }

        try {
            $credited = $gateway->Settle($cart, (string) $this->page->GetNanoBlockHash(), $this->nanoClaims, $this->paymentLogger);
        } catch (NanoException | InvalidArgumentException $ex) {
            Log::Debug('Nano payment refused for cart %s: %s', $cart->Id(), $ex->getMessage());
            $this->page->SetNanoResult(false, $ex->getMessage());
            return;
        }

        if ($credited) {
            $user = $this->userRepository->LoadById($userSession->UserId);
            $user->AddCredits($cart->Quantity, Resources::GetInstance()->GetString('NoteCreditsPurchased'));
            $this->userRepository->Update($user);
            ServiceLocator::GetServer()->SetSession(SessionKeys::CREDIT_CART, null);
        }

        $this->page->SetNanoResult($credited, null);
    }
}
