<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/PaymentRepository.php');

class PaymentRepositoryTest extends TestBase
{
    /**
     * @var PaymentRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setup();
        $this->repository = new PaymentRepository();
    }

    public function testGetsCreditCost()
    {
        $expectedRows = [[ColumnNames::CREDIT_COUNT => 1, ColumnNames::CREDIT_COST => 32.99, ColumnNames::CREDIT_CURRENCY => 'USD']];
        $this->db->SetRows($expectedRows);

        $cost = $this->repository->GetCreditCosts();

        $this->assertEquals(new CreditCost(1, 32.99, 'USD'), $cost[0]);
        $this->assertEquals(new GetPaymentConfigurationCommand(), $this->db->_LastCommand);
    }

    public function testUpdatesCreditCost()
    {
        $this->repository->UpdateCreditCost(new CreditCost(1, 32.99, 'USD'));

        $this->assertEquals(new AddPaymentConfigurationCommand(1, 32.99, 'USD'), $this->db->_LastCommand);
    }

    public function testUpdatesPayPal()
    {
        $gateway = new PayPalGateway(true, 'client', 'secret', 'environment');
        $this->repository->UpdatePayPalGateway($gateway);

        $this->assertEquals(new DeletePaymentGatewaySettingsCommand(PaymentGateways::PAYPAL), $this->db->_Commands[0]);
        $this->assertTrue($this->db->ContainsCommand(new AddPaymentGatewaySettingCommand(PaymentGateways::PAYPAL, PayPalGateway::CLIENT_ID, 'client')));
        $this->assertTrue($this->db->ContainsCommand(new AddPaymentGatewaySettingCommand(PaymentGateways::PAYPAL, PayPalGateway::SECRET, 'secret')));
        $this->assertTrue($this->db->ContainsCommand(new AddPaymentGatewaySettingCommand(PaymentGateways::PAYPAL, PayPalGateway::ENVIRONMENT, 'environment')));
    }

    public function testUpdatesStripe()
    {
        $gateway = new StripeGateway(true, 'publishable', 'secret');
        $this->repository->UpdateStripeGateway($gateway);

        $this->assertEquals(new DeletePaymentGatewaySettingsCommand(PaymentGateways::STRIPE), $this->db->_Commands[0]);
        $this->assertTrue($this->db->ContainsCommand(new AddPaymentGatewaySettingCommand(PaymentGateways::STRIPE, StripeGateway::PUBLISHABLE_KEY, 'publishable')));
        $this->assertTrue($this->db->ContainsCommand(new AddPaymentGatewaySettingCommand(PaymentGateways::STRIPE, StripeGateway::SECRET_KEY, 'secret')));
    }

    public function testLoadsPayPal()
    {
        $this->db->SetRows([
            $this->GetRow(PaymentGateways::PAYPAL, PayPalGateway::CLIENT_ID, 'cid'),
            $this->GetRow(PaymentGateways::PAYPAL, PayPalGateway::SECRET, 'secret'),
            $this->GetRow(PaymentGateways::PAYPAL, PayPalGateway::ENVIRONMENT, 'env'),
        ]);

        $paypal = $this->repository->GetPayPalGateway();

        $this->assertEquals(new GetPaymentGatewaySettingsCommand(PaymentGateways::PAYPAL), $this->db->_LastCommand);
        $this->assertTrue($paypal->IsEnabled());
        $this->assertEquals('cid', $paypal->ClientId());
        $this->assertEquals('secret', $paypal->Secret());
        $this->assertEquals('env', $paypal->Environment());
    }

    public function testLoadsStripe()
    {
        $this->db->SetRows([
            $this->GetRow(PaymentGateways::STRIPE, StripeGateway::PUBLISHABLE_KEY, 'pk'),
            $this->GetRow(PaymentGateways::STRIPE, StripeGateway::SECRET_KEY, 'secret'),
        ]);

        $stripe = $this->repository->GetStripeGateway();

        $this->assertEquals(new GetPaymentGatewaySettingsCommand(PaymentGateways::STRIPE), $this->db->_LastCommand);
        $this->assertTrue($stripe->IsEnabled());
        $this->assertEquals('pk', $stripe->PublishableKey());
        $this->assertEquals('secret', $stripe->SecretKey());
    }

    private function GetRow($gatewayType, $name, $value)
    {
        return [ColumnNames::GATEWAY_TYPE => $gatewayType,
            ColumnNames::GATEWAY_SETTING_NAME => $name,
            ColumnNames::GATEWAY_SETTING_VALUE => $value];
    }
}
