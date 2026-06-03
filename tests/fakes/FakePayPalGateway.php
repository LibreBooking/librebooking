<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/PaymentGateway.php');

class FakePayPalGateway extends PayPalGateway
{
    public $_Payment;
    public $_PaymentCreatedCart;
    public $_ReturnUrl;
    public $_CancelUrl;
    public $_PaymentId;
    public $_PayerId;
    public $_Refund;
    public $_LastTransactionView;
    public $_LastRefundAmount;

    public function __construct()
    {
        $this->_Payment = new stdClass();
        $this->_Refund = new stdClass();
        parent::__construct(true, null, null, null);
    }

    public function CreatePayment(CreditCartSession $cart, $returnUrl, $cancelUrl)
    {
        $this->_PaymentCreatedCart = $cart;
        $this->_ReturnUrl = $returnUrl;
        $this->_CancelUrl = $cancelUrl;
        return $this->_Payment;
    }

    public function ExecutePayment(CreditCartSession $cart, $paymentId, $payerId, IPaymentTransactionLogger $logger)
    {
        $this->_PaymentCreatedCart = $cart;
        $this->_PaymentId = $paymentId;
        $this->_PayerId = $payerId;
        return $this->_Payment;
    }

    public function Refund(TransactionLogView $log, $amount, IPaymentTransactionLogger $logger)
    {
        $this->_LastTransactionView = $log;
        $this->_LastRefundAmount = $amount;
        return $this->_Refund;
    }
}
