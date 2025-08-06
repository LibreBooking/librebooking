<?php

require_once(ROOT_DIR . 'Presenters/Credits/CheckoutPresenter.php');
require_once(ROOT_DIR . 'Pages/SecurePage.php');

interface ICheckoutPage extends IActionPage
{
    public function GetCreditCount();

    public function GetCreditQuantity();

    /**
     * @param float $total
     * @param CreditCost $cost
     * @param float $creditQuantity
     */
    public function SetTotals($total, $cost, $creditQuantity);

    /**
     * @param bool $enabled
     * @param string $clientId
     * @param string $environment
     */
    public function SetPayPalSettings($enabled, $clientId, $environment);

    /**
     * @param string $enabled
     * @param string $publishableKey
     */
    public function SetStripeSettings($enabled, $publishableKey);

    /**
     * @param object $payment
     */
    public function SetPayPalPayment($payment);

    /**
     * @return string
     */
    public function GetPaymentId();

    /**
     * @return string
     */
    public function GetPayerId();

    /**
     * @param bool $isEmpty
     */
    public function SetEmptyCart($isEmpty);

    /**
     * @return string
     */
    public function GetStripeToken();

    /**
     * @param bool $result
     */
    public function SetStripeResult($result);
}

class CheckoutPage extends ActionPage implements ICheckoutPage
{
    /**
     * @var CheckoutPresenter
     */
    private $presenter;

    public function __construct()
    {
        parent::__construct('Checkout');
        $this->Set('AllowPurchasingCredits', Configuration::Instance()->GetKey(ConfigKeys::CREDITS_ALLOW_PURCHASE, new BooleanConverter()));

        $this->presenter = new CheckoutPresenter($this, new PaymentRepository(), new UserRepository(), new PaymentTransactionLogger());
    }

    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    public function ProcessDataRequest($dataRequest)
    {
        // no-op
    }

    public function ProcessPageLoad()
    {
        $this->EnforceCSRFCheck();

        $this->Set('Email', ServiceLocator::GetServer()->GetUserSession()->Email);
        $this->presenter->PageLoad(ServiceLocator::GetServer()->GetUserSession());
        $this->Display('Credits/checkout.tpl');
    }

    public function GetCreditCount()
    {
        return $this->GetForm(FormKeys::CREDIT_COUNT);
    }

    public function GetCreditQuantity()
    {
        return $this->GetForm(FormKeys::CREDIT_QUANTITY);
    }

    public function SetPayPalSettings($enabled, $clientId, $environment)
    {
        $this->Set('PayPalEnabled', $enabled);
        $this->Set('PayPalClientId', $clientId);
        $this->Set('PayPalEnvironment', $environment);
    }

    public function SetStripeSettings($enabled, $publishableKey)
    {
        $this->Set('StripeEnabled', $enabled);
        $this->Set('StripePublishableKey', $publishableKey);
    }

    public function SetTotals($total, $cost, $creditQuantity)
    {
        $this->Set('Total', $cost->FormatCurrency($total));
        $this->Set('CreditCost', $cost->FormatCurrency());
        $this->Set('CreditCount', $cost->Count());
        $this->Set('CreditQuantity', $creditQuantity);
        $this->Set('Currency', $cost->Currency());
        $this->Set('TotalUnformatted', $cost->GetTotal($creditQuantity));
    }

    public function SetPayPalPayment($payment)
    {
        $this->SetJson($payment);
    }

    public function GetPaymentId()
    {
        return $this->GetForm('paymentID');
    }

    public function GetPayerId()
    {
        return $this->GetForm('payerID');
    }

    public function SetEmptyCart($isEmpty)
    {
        $this->Set('IsCartEmpty', $isEmpty);
    }

    public function GetStripeToken()
    {
        return $this->GetForm(FormKeys::STRIPE_TOKEN);
    }

    public function SetStripeResult($result)
    {
        $this->SetJson(['result'=>$result]);
    }
}
