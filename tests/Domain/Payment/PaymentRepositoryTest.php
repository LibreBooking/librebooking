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

        // A single statement: deleting the row first would lose the price if the insert failed.
        $this->assertEquals([new SetPaymentConfigurationCommand(creditCount: 1, creditCost: 32.99, creditCurrency: 'USD')], $this->db->_Commands);
    }

    public function testUpdatesPayPal()
    {
        $gateway = new PayPalGateway(enabled: true, clientId: 'client', secret: 'secret', environment: 'environment');
        $this->repository->UpdatePayPalGateway($gateway);

        // The settings are written first, in one statement, and obsolete names
        // are removed after. Deleting first would lose them if the write failed.
        $write = new SetPaymentGatewaySettingsCommand(
            gatewayType: PaymentGateways::PAYPAL,
            settings: [
                new PaymentGatewaySetting(name: PayPalGateway::CLIENT_ID, value: 'client'),
                new PaymentGatewaySetting(name: PayPalGateway::SECRET, value: 'secret'),
                new PaymentGatewaySetting(name: PayPalGateway::ENVIRONMENT, value: 'environment'),
            ]
        );
        $cleanup = new DeleteObsoletePaymentGatewaySettingsCommand(
            gatewayType: PaymentGateways::PAYPAL,
            currentSettingNames: [PayPalGateway::CLIENT_ID, PayPalGateway::SECRET, PayPalGateway::ENVIRONMENT]
        );
        $this->assertEquals([$write, $cleanup], $this->db->_Commands);
    }

    public function testUpdatesStripe()
    {
        $gateway = new StripeGateway(enabled: true, publishableKey: 'publishable', secretKey: 'secret');
        $this->repository->UpdateStripeGateway($gateway);

        $write = new SetPaymentGatewaySettingsCommand(
            gatewayType: PaymentGateways::STRIPE,
            settings: [
                new PaymentGatewaySetting(name: StripeGateway::PUBLISHABLE_KEY, value: 'publishable'),
                new PaymentGatewaySetting(name: StripeGateway::SECRET_KEY, value: 'secret'),
            ]
        );
        $cleanup = new DeleteObsoletePaymentGatewaySettingsCommand(
            gatewayType: PaymentGateways::STRIPE,
            currentSettingNames: [StripeGateway::PUBLISHABLE_KEY, StripeGateway::SECRET_KEY]
        );
        $this->assertEquals([$write, $cleanup], $this->db->_Commands);
    }

    public function testDisablingPayPalDeletesItsSettings()
    {
        $gateway = new PayPalGateway(enabled: false, clientId: null, secret: null, environment: null);
        $this->repository->UpdatePayPalGateway($gateway);

        $this->assertEquals([new DeletePaymentGatewaySettingsCommand(PaymentGateways::PAYPAL)], $this->db->_Commands);
    }

    public function testDisablingStripeDeletesItsSettings()
    {
        $gateway = new StripeGateway(enabled: false, publishableKey: null, secretKey: null);
        $this->repository->UpdateStripeGateway($gateway);

        $this->assertEquals([new DeletePaymentGatewaySettingsCommand(PaymentGateways::STRIPE)], $this->db->_Commands);
    }

    public function testGatewaySettingsAreWrittenAsOneRowEach()
    {
        $command = new SetPaymentGatewaySettingsCommand(
            gatewayType: PaymentGateways::STRIPE,
            settings: [
                new PaymentGatewaySetting(name: StripeGateway::PUBLISHABLE_KEY, value: 'publishable'),
                new PaymentGatewaySetting(name: StripeGateway::SECRET_KEY, value: 'secret'),
            ]
        );

        $this->assertStringContainsString(
            'VALUES (@gateway_type, @setting_name0, @setting_value0), (@gateway_type, @setting_name1, @setting_value1)',
            $command->GetQuery()
        );
        $this->assertStringContainsString('ON DUPLICATE KEY UPDATE', $command->GetQuery());
    }

    public function testGatewaySettingsCommandRejectsNoSettings()
    {
        $this->expectException(InvalidArgumentException::class);

        new SetPaymentGatewaySettingsCommand(gatewayType: PaymentGateways::STRIPE, settings: []);
    }

    public function testObsoleteGatewaySettingsCommandRejectsNoNamesToKeep()
    {
        // With no names to keep it would delete every setting of the gateway.
        $this->expectException(InvalidArgumentException::class);

        new DeleteObsoletePaymentGatewaySettingsCommand(gatewayType: PaymentGateways::STRIPE, currentSettingNames: []);
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
