<?php

class FakePaymentRepository implements IPaymentRepository
{
    /**
     * @var CreditCost
     */
    public $_LastCost;
    /**
     * @var CreditCost
     */
    public $_CreditCost;
    /**
     * @var PayPalGateway
     */
    public $_LastPayPal;
    /**
     * @var StripeGateway
     */
    public $_LastStripe;
    /**
     * @var FakePayPalGateway
     */
    public $_PayPal;
    /**
     * @var StripeGateway
     */
    public $_Stripe;
    /**
     * @var PayPalPaymentResult
     */
    public $_LastSavedPayPalResult;

    /**
     * @var PageablePage
     */
    public $_TransactionLogs;
    public $_LastPage;
    public $_LastPageSize;
    public $_LastUserId;
    public $_TransactionLogView;
    public $_LastTransactionLogId;

    public function __construct()
    {
        $this->_PayPal = new FakePayPalGateway();
        $this->_Stripe = new FakeStripeGateway();
        $this->_CreditCost = [new CreditCost()];
    }

    public function DeleteCreditCost($creditCount)
    {
    }

    public function GetCreditCosts()
    {
        return $this->_CreditCost;
    }

    public function UpdateCreditCost($cost)
    {
        $this->_LastCost = $cost;
    }

    public function GetCreditCost()
    {
        return $this->_CreditCost;
    }

    public function UpdatePayPalGateway(PayPalGateway $gateway)
    {
        $this->_LastPayPal = $gateway;
    }

    public function UpdateStripeGateway(StripeGateway $gateway)
    {
        $this->_LastStripe = $gateway;
    }

    public function GetPayPalGateway()
    {
        return $this->_PayPal;
    }

    public function GetStripeGateway()
    {
        return $this->_Stripe;
    }

    public function SavePayPalPaymentResult(PayPalPaymentResult $result)
    {
        $this->_LastSavedPayPalResult;
    }

    public function GetList($pageNumber, $pageSize, $userId = -1, $sortField = null, $sortDirection = null, $filter = null)
    {
        $this->_LastPage = $pageNumber;
        $this->_LastPageSize = $pageSize;
        $this->_LastUserId = $userId;

        return $this->_TransactionLogs;
    }

    public function GetTransactionLogView($transactionLogId)
    {
        $this->_LastTransactionLogId = $transactionLogId;
        return $this->_TransactionLogView;
    }
}
