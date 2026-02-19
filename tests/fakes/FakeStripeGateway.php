<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/PaymentGateway.php');


class FakeStripeGateway extends StripeGateway
{
    public $_Token;
    public $_Cart;
    public $_Email;
    public $_ChargeResponse = false;

    public function __construct()
    {
        parent::__construct(true, '', '');
    }

    public function Charge(CreditCartSession $cart, $email, $token, IPaymentTransactionLogger $logger)
    {
        $this->_Cart = $cart;
        $this->_Email = $email;
        $this->_Token = $token;

        return $this->_ChargeResponse;
    }

    public function Refund(TransactionLogView $log, $amount, IPaymentTransactionLogger $logger)
    {
        $this->_LastTransactionView = $log;
        $this->_LastRefundAmount = $amount;
        return $this->_Refunded;
    }
}
