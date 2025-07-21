<?php

if (file_exists(ROOT_DIR . 'vendor/autoload.php')) {
    require_once ROOT_DIR . 'vendor/autoload.php';
}


class PaymentGateways
{
    public const PAYPAL = 'PayPal';
    public const STRIPE = 'Stripe';
}

class PaymentGatewaySetting
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function Name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function Value()
    {
        return $this->value;
    }
}

interface IPaymentGateway
{
    /**
     * @return string
     */
    public function GetGatewayType();

    /**
     * @return bool
     */
    public function IsEnabled();

    /**
     * @return PaymentGatewaySetting[]
     */
    public function Settings();
}

interface IPaymentTransactionLogger
{
    /**
     * @param string $userId
     * @param string $status
     * @param string $invoiceNumber
     * @param int $transactionId
     * @param float $totalAmount
     * @param float $transactionFee
     * @param string $currency
     * @param string $transactionHref
     * @param string $refundHref
     * @param Date $dateCreated
     * @param string $gatewayDateCreated
     * @param string $gatewayName
     * @param string $gatewayResponse
     */
    public function LogPayment(
        $userId,
        $status,
        $invoiceNumber,
        $transactionId,
        $totalAmount,
        $transactionFee,
        $currency,
        $transactionHref,
        $refundHref,
        $dateCreated,
        $gatewayDateCreated,
        $gatewayName,
        $gatewayResponse
    );

    /**
     * @param string $paymentTransactionLogId
     * @param string $status
     * @param int $transactionId
     * @param float $totalRefundAmount
     * @param float $paymentRefundAmount
     * @param float $feeRefundAmount
     * @param string $transactionHref
     * @param Date $dateCreated
     * @param string $gatewayDateCreated
     * @param string $refundResponse
     */
    public function LogRefund(
        $paymentTransactionLogId,
        $status,
        $transactionId,
        $totalRefundAmount,
        $paymentRefundAmount,
        $feeRefundAmount,
        $transactionHref,
        $dateCreated,
        $gatewayDateCreated,
        $refundResponse
    );
}

class PaymentTransactionLogger implements IPaymentTransactionLogger
{
    public function LogPayment(
        $userId,
        $status,
        $invoiceNumber,
        $transactionId,
        $totalAmount,
        $transactionFee,
        $currency,
        $transactionHref,
        $refundHref,
        $dateCreated,
        $gatewayDateCreated,
        $gatewayName,
        $gatewayResponse
    ) {
        ServiceLocator::GetDatabase()->Execute(new AddPaymentTransactionLogCommand(
            $userId,
            $status,
            $invoiceNumber,
            $transactionId,
            $totalAmount,
            $transactionFee,
            $currency,
            $transactionHref,
            $refundHref,
            $dateCreated,
            $gatewayDateCreated,
            $gatewayName,
            $gatewayResponse
        ));
    }

    public function LogRefund(
        $paymentTransactionLogId,
        $status,
        $transactionId,
        $totalRefundAmount,
        $paymentRefundAmount,
        $feeRefundAmount,
        $transactionHref,
        $dateCreated,
        $gatewayDateCreated,
        $refundResponse
    ) {
        ServiceLocator::GetDatabase()->Execute(new AddRefundTransactionLogCommand(
            $paymentTransactionLogId,
            $status,
            $transactionId,
            $totalRefundAmount,
            $paymentRefundAmount,
            $feeRefundAmount,
            $transactionHref,
            $dateCreated,
            $gatewayDateCreated,
            $refundResponse
        ));
    }
}

class PayPalGateway implements IPaymentGateway
{
    public const CLIENT_ID = 'client_id';
    public const SECRET = 'secret';
    public const ENVIRONMENT = 'environment';
    public const ACTION_PAYMENT = 'payment';
    public const ACTION_CANCEL = 'cancel';
    public const ACTION_EXECUTE = 'execute';

    /**
     * @var bool
     */
    private $enabled;
    /**
     * @var string
     */
    private $clientId;
    /**
     * @var string
     */
    private $secret;
    /**
     * @var string
     */
    private $environment;

    /**
     * @param bool $enabled
     * @param string $clientId
     * @param string $secret
     * @param string $environment
     */
    public function __construct($enabled, $clientId, $secret, $environment)
    {
        $this->enabled = $enabled;
        if ($enabled) {
            $this->clientId = $clientId;
            $this->secret = $secret;
            $this->environment = $environment;
        }
    }

    /**
     * @param string $clientId
     * @param string $secret
     * @param string $environment
     * @return PayPalGateway
     */
    public static function Create($clientId, $secret, $environment)
    {
        $enabled = (!empty($clientId) && !empty($secret) && !empty($environment));
        return new PayPalGateway($enabled, $clientId, $secret, $environment);
    }

    public function GetGatewayType()
    {
        return PaymentGateways::PAYPAL;
    }

    public function IsEnabled()
    {
        return $this->enabled;
    }

    public function Settings()
    {
        return [
            new PaymentGatewaySetting(self::CLIENT_ID, $this->ClientId()),
            new PaymentGatewaySetting(self::SECRET, $this->Secret()),
            new PaymentGatewaySetting(self::ENVIRONMENT, $this->Environment()),
        ];
    }

    /**
     * @return string
     */
    public function ClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function Secret()
    {
        return $this->secret;
    }

    /**
     * @return string
     */
    public function Environment()
    {
        return $this->environment;
    }

    private function GetAuthToken($baseUrl)
    {
        $authUrl = "$baseUrl/v1/oauth2/token";
        $headers = ['Accept' => 'application/json', 'Accept-Language' => 'en_US', 'Content-Type' => 'application/x-www-form-urlencoded'];
        $data = ['grant_type' => 'client_credentials'];
        $body = Unirest\Request\Body::form($data);
        Unirest\Request::auth($this->ClientId(), $this->Secret());
        Unirest\Request::verifyPeer(false);
        $response = Unirest\Request::post($authUrl, $headers, $body);

        return $response->body->access_token;
    }

    private function GetBaseUrl()
    {
        $baseUrl = "https://api.paypal.com";
        $paypalEnvironment = $this->Environment();
        if (strtolower($paypalEnvironment) == "sandbox") {
            $baseUrl = "https://api-m.sandbox.paypal.com";
        }
        return $baseUrl;
    }

    /**
     * @param CreditCartSession $cart
     * @param string $returnUrl
     * @param string $cancelUrl
     * @return object
     */
    public function CreatePayment(CreditCartSession $cart, $returnUrl, $cancelUrl)
    {
        $resources = Resources::GetInstance();
        $baseUrl = $this->GetBaseUrl();
        $token = $this->GetAuthToken($baseUrl);
        $body = "";

        try {
            Log::Debug('PayPal Checkout/Orders CartId/invoice number: %s, Total: %s', $cart->Id(), $cart->Total());
            $checkoutUrl = "$baseUrl/v2/checkout/orders";
            $headers = ['Accept' => 'application/json', 'Accept-Language' => 'en_US', 'Content-Type' => 'application/json', "Authorization" => "Bearer $token"];
            $purchaseRequest = ['description' => $resources->GetString('CreditPurchase'), 'amount' => ['value' => "{$cart->Total()}", 'currency_code' => $cart->Currency]];
            $data = [
                'intent' => 'CAPTURE',
                'application_context' => ['return_url' => $returnUrl, 'cancel_url' => $cancelUrl],
                'purchase_units' => [$purchaseRequest]
            ];
            $body = Unirest\Request\Body::json($data);
            Unirest\Request::verifyPeer(false);
            $response = Unirest\Request::post($checkoutUrl, $headers, $body);

            if (Log::DebugEnabled()) {
                Log::Debug("PayPal Checkout/Orders Url: %s, Request: %s, Response: %s", $checkoutUrl, $body, json_encode($response->body));
            }

            return $response->body;
        } catch (Exception $exception) {
            Log::Error(
                'PayPal Checkout/Orders error details. Data: %s, CartId/invoice number: %s, Total: %s, Error: %s',
                $body,
                $cart->Id(),
                $cart->Total(),
                $exception
            );
        }

        return null;
    }

    /**
     * @param CreditCartSession $cart
     * @param string $paymentId
     * @param string $payerId
     * @param IPaymentTransactionLogger $logger
     * @return object
     */
    public function ExecutePayment(CreditCartSession $cart, $paymentId, $payerId, IPaymentTransactionLogger $logger)
    {
        $baseUrl = $this->GetBaseUrl();
        $token = $this->GetAuthToken($baseUrl);
        try {
            Log::Debug('PayPal Capture CartId/invoice number: %s, Total: %s', $cart->Id(), $cart->Total());
            $checkoutUrl = "$baseUrl/v2/checkout/orders/$paymentId/capture";
            $headers = ['Accept' => 'application/json', 'Accept-Language' => 'en_US', 'Content-Type' => 'application/json', "Authorization" => "Bearer $token"];
            Unirest\Request::verifyPeer(false);
            $response = Unirest\Request::post($checkoutUrl, $headers);

            $sale = $response->body->purchase_units[0]->payments->captures[0];
            $self = "";
            $refund = "";
            foreach ($sale->links as $link) {
                if ($link->rel == "self") {
                    $self = $link->href;
                }
                if ($link->rel == "refund") {
                    $refund = $link->href;
                }
            }

            $paypalFee = isset($sale->seller_receivable_breakdown->paypal_fee->value)
                ? $sale->seller_receivable_breakdown->paypal_fee->value
                : 0;

            $logger->LogPayment(
                $cart->UserId,
                $response->body->status,
                $sale->id,
                $response->body->id,
                $sale->amount->value,
                $paypalFee,
                $sale->amount->currency_code,
                $self,
                $refund,
                Date::Now(),
                $sale->create_time,
                $this->GetGatewayType(),
                json_encode($response->body)
            );
            if (Log::DebugEnabled()) {
                Log::Debug("PayPal Capture Url: %s, Response: %s", $checkoutUrl, json_encode($response->body));
            }

            return $response->body;
        } catch (Exception $exception) {
            Log::Error('PayPal Capture error details. CartId/invoice number: %s, Total: %s, Error: %s', $cart->Id(), $cart->Total(), $exception);
        }
        return null;
    }

    /**
     * @param TransactionLogView $log
     * @param float $amount
     * @param IPaymentTransactionLogger $logger
     * @return object
     */
    public function Refund(TransactionLogView $log, $amount, IPaymentTransactionLogger $logger)
    {
        $baseUrl = $this->GetBaseUrl();
        $token = $this->GetAuthToken($baseUrl);

        try {
            Log::Debug('PayPal Refund. TransactionId: %s, InvoiceNumber: %s, Total: %s', $log->TransactionId, $log->InvoiceNumber, $amount);
            $refundUrl = "$baseUrl/v2/payments/captures/{$log->InvoiceNumber}/refund";
            //			$refundUrl = "$baseUrl/v2/payments/captures/{$log->TransactionId}/refund";
            $headers = ['Accept' => 'application/json', 'Accept-Language' => 'en_US', 'Content-Type' => 'application/json', "Authorization" => "Bearer $token"];
            $data = ['amount' => ['value' => "{$amount}", 'currency_code' => $log->Currency]];
            $body = Unirest\Request\Body::json($data);
            Unirest\Request::verifyPeer(false);
            $response = Unirest\Request::post($refundUrl, $headers, $body);

            if (Log::DebugEnabled()) {
                Log::Debug("PayPal Refund Url: %s, Request: %s, Response: %s", $refundUrl, $body, json_encode($response->body));
            }

            $self = "";
            foreach ($response->body->links as $link) {
                if ($link->rel == "self") {
                    $self = $link->href;
                }
            }

            $breakdown = $response->body->seller_payable_breakdown;

            $logger->LogRefund(
                $log->Id,
                $response->body->status,
                $response->body->id,
                $breakdown ? $breakdown->total_refunded_amount->value : $amount,
                $breakdown ? $breakdown->gross_amount->value : 0,
                $breakdown ? $breakdown->paypal_fee->value : 0,
                $self,
                Date::Now(),
                $response->body->create_time ? $response->body->create_time : "",
                json_encode($response->body)
            );

            return $response->body;
        } catch (Exception $exception) {
            Log::Error('Error refunding PayPal payment. TransactionId %s, Total %s, Error %s', $log->TransactionId, $amount, $exception);
        }

        return null;
    }
}

class StripeGateway implements IPaymentGateway
{
    public const PUBLISHABLE_KEY = 'publishable_key';
    public const SECRET_KEY = 'secret_key';

    /**
     * @var bool
     */
    private $enabled;
    /**
     * @var string
     */
    private $publishableKey;
    /**
     * @var string
     */
    private $secretKey;
    /**
     * @var TransactionLogView
     */
    public $_LastTransactionView;
    /**
     * @var bool
     */
    public $_Refunded;
    /**
     * @var float
     */
    public $_LastRefundAmount;

    /**
     * @param bool $enabled
     * @param string $publishableKey
     * @param string $secretKey
     */
    public function __construct($enabled, $publishableKey, $secretKey)
    {
        $this->enabled = $enabled;
        if ($enabled) {
            $this->publishableKey = $publishableKey;
            $this->secretKey = $secretKey;
        }
    }

    /**
     * @param string $publishableKey
     * @param string $secretKey
     * @return StripeGateway
     */
    public static function Create($publishableKey, $secretKey)
    {
        $enabled = (!empty($publishableKey) && !empty($secretKey));
        return new StripeGateway($enabled, $publishableKey, $secretKey);
    }

    public function GetGatewayType()
    {
        return PaymentGateways::STRIPE;
    }

    public function IsEnabled()
    {
        return $this->enabled;
    }

    public function Settings()
    {
        return [
            new PaymentGatewaySetting(self::PUBLISHABLE_KEY, $this->PublishableKey()),
            new PaymentGatewaySetting(self::SECRET_KEY, $this->SecretKey()),
        ];
    }

    /**
     * @return string
     */
    public function PublishableKey()
    {
        return $this->publishableKey;
    }

    /**
     * @return string
     */
    public function SecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @param CreditCartSession $cart
     * @param string $email
     * @param string $token
     * @param IPaymentTransactionLogger $logger
     * @return bool
     */
    public function Charge(CreditCartSession $cart, $email, $token, IPaymentTransactionLogger $logger)
    {
        try {
            \Stripe\Stripe::setApiKey($this->SecretKey());

            $customer = \Stripe\Customer::create([
                'email' => $email,
                'source' => $token
            ]);

            $currency = new \Booked\Currency($cart->Currency);

            $charge = \Stripe\Charge::create([
                'customer' => $customer->id,
                'amount' => $currency->ToStripe($cart->Total()),
                'currency' => strtolower($cart->Currency),
                'description' => Resources::GetInstance()->GetString('Credits'),
                'expand' => ['balance_transaction']
            ]);

            if (Log::DebugEnabled()) {
                Log::Debug('Stripe charge response %s', json_encode($charge));
            }

            $logger->LogPayment(
                $cart->UserId,
                $charge->status,
                $charge->invoice,
                $charge->id,
                $currency->FromStripe($charge->amount),
                $currency->FromStripe($charge->balance_transaction->fee),
                $cart->Currency,
                null,
                null,
                Date::Now(),
                $charge->created,
                $this->GetGatewayType(),
                json_encode($charge)
            );
            return $charge->status == 'succeeded';
        } catch (\Stripe\Exception\CardException $ex) {
            // Declined
            $body = $ex->getJsonBody();
            $err = $body['error'];
            Log::Debug(
                'Stripe charge failed. http status %s, type %s, code %s, param %s, message %s',
                $ex->getHttpStatus(),
                $err['type'],
                $err['code'],
                $err['param'],
                $err['message']
            );
        } catch (\Stripe\Exception\RateLimitException $ex) {
            Log::Error('Stripe - too many requests. %s', $ex);
        } catch (\Stripe\Exception\InvalidRequestException $ex) {
            Log::Error('Stripe - invalid request. %s', $ex);
        } catch (\Stripe\Exception\AuthenticationException $ex) {
            Log::Error('Stripe - authentication error. %s', $ex);
        } catch (\Stripe\Exception\ApiConnectionException $ex) {
            Log::Error('Stripe - connection failure. %s', $ex);
        } catch (\Stripe\Exception\ApiErrorException $ex) {
            Log::Error('Stripe - error. %s', $ex);
        } catch (Exception $ex) {
            Log::Error('Stripe - internal error. %s', $ex);
        }

        return false;
    }

    /**
     * @param TransactionLogView $log
     * @param float $amount
     * @param IPaymentTransactionLogger $logger
     * @return bool
     */
    public function Refund(TransactionLogView $log, $amount, IPaymentTransactionLogger $logger)
    {
        try {
            $currency = new \Booked\Currency($log->Currency);

            \Stripe\Stripe::setApiKey($this->SecretKey());
            $refund = \Stripe\Refund::create([
                'charge' => $log->TransactionId,
                'amount' => $currency->ToStripe($amount),
                'expand' => ['balance_transaction']
            ]);

            if (Log::DebugEnabled()) {
                Log::Debug('Stripe refund response %s', json_encode($refund));
            }

            $logger->LogRefund(
                $log->Id,
                $refund->status,
                $refund->id,
                $currency->FromStripe($refund->amount),
                $currency->FromStripe($refund->amount),
                $currency->FromStripe($refund->balance_transaction->fee),
                null,
                Date::Now(),
                $refund->created,
                json_encode($refund)
            );

            return $refund->status == 'succeeded';
        } catch (\Stripe\Exception\CardException $ex) {
            // Declined
            $body = $ex->getJsonBody();
            $err = $body['error'];
            Log::Debug(
                'Stripe refund  failed. http status %s, type %s, code %s, param %s, message %s',
                $ex->getHttpStatus(),
                $err['type'],
                $err['code'],
                $err['param'],
                $err['message']
            );
        } catch (\Stripe\Exception\RateLimitException $ex) {
            Log::Error('Stripe refund - too many requests. %s', $ex);
        } catch (\Stripe\Exception\InvalidRequestException $ex) {
            Log::Error('Stripe refund - invalid request. %s', $ex);
        } catch (\Stripe\Exception\AuthenticationException $ex) {
            Log::Error('Stripe refund - authentication error. %s', $ex);
        } catch (\Stripe\Exception\ApiConnectionException $ex) {
            Log::Error('Stripe refund - connection failure. %s', $ex);
        } catch (\Stripe\Exception\ApiErrorException $ex) {
            Log::Error('Stripe - error. %s', $ex);
        } catch (Exception $ex) {
            Log::Error('Stripe refund - internal error. %s', $ex);
        }

        return false;
    }
}
