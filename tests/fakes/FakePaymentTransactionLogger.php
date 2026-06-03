<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/PaymentGateway.php');

class FakePaymentTransactionLogger implements IPaymentTransactionLogger
{
    public $_PaymentLogged = false;

    public function LogPayment($userId, $status, $invoiceNumber, $transactionId, $totalAmount, $transactionFee, $currency, $transactionHref, $refundHref, $dateCreated, $gatewayDateCreated, $gatewayName, $gatewayResponse)
    {
        $this->_PaymentLogged = true;
    }

    public function LogRefund($paymentTransactionLogId, $status, $transactionId, $totalRefundAmount, $paymentRefundAmount, $feeRefundAmount, $transactionHref, $dateCreated, $gatewayDateCreated, $refundResponse)
    {
        // TODO: Implement LogRefund() method.
    }
}
