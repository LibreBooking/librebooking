<?php

declare(strict_types=1);

require_once(__DIR__ . '/DatabaseTestCase.php');
require_once(ROOT_DIR . 'Domain/Access/PaymentRepository.php');

/**
 * Runs the PaymentRepository writes against a real database, to check that a
 * write the database rejects leaves the stored payment settings as they were.
 *
 * Each test starts with empty payment tables. The rows that were there before
 * are put back afterwards.
 */
class PaymentRepositoryDatabaseTest extends DatabaseTestCase
{
    private const CREDIT_COUNT = 5;
    private const ORIGINAL_COST = '12.50';
    private const ORIGINAL_CURRENCY = 'USD';
    private const NEW_COST = 20.25;
    private const NEW_CURRENCY = 'EUR';
    /**
     * A setting name that neither gateway writes.
     */
    private const OBSOLETE_SETTING_NAME = 'obsolete_setting';
    private const OBSOLETE_SETTING_VALUE = 'obsolete value';
    /**
     * The smallest value above what the DECIMAL(7, 2) credit_cost column can hold.
     */
    private const COST_ABOVE_COLUMN_MAXIMUM = 100000.00;
    /**
     * One character more than the VARCHAR(10) credit_currency column can hold.
     */
    private const CURRENCY_LONGER_THAN_COLUMN = 'ABCDEFGHIJK';

    private Database $database;
    private PaymentRepository $repository;
    /**
     * @var array<int, array<string, mixed>>
     */
    private array $savedCreditCosts = [];
    /**
     * @var array<int, array<string, mixed>>
     */
    private array $savedGatewaySettings = [];
    private bool $tablesSaved = false;

    public function setUp(): void
    {
        parent::setUp();
        $this->requireLoadedSchema();

        $this->database = $this->realDatabase();
        ServiceLocator::SetDatabase($this->database);
        $this->repository = new PaymentRepository();

        $this->savedCreditCosts = $this->rows('SELECT * FROM `payment_configuration`');
        $this->savedGatewaySettings = $this->rows('SELECT * FROM `payment_gateway_settings`');
        $this->tablesSaved = true;
        $this->emptyPaymentTables();
    }

    public function tearDown(): void
    {
        if ($this->tablesSaved) {
            $this->emptyPaymentTables();
            foreach ($this->savedCreditCosts as $row) {
                $this->database->Execute($this->command(
                    'INSERT INTO `payment_configuration` (`credit_count`, `credit_cost`, `credit_currency`) VALUES (@count, @cost, @currency)',
                    ['@count' => $row['credit_count'], '@cost' => $row['credit_cost'], '@currency' => $row['credit_currency']]
                ));
            }
            foreach ($this->savedGatewaySettings as $row) {
                $this->database->Execute($this->command(
                    'INSERT INTO `payment_gateway_settings` (`gateway_type`, `setting_name`, `setting_value`) VALUES (@type, @name, @value)',
                    ['@type' => $row['gateway_type'], '@name' => $row['setting_name'], '@value' => $row['setting_value']]
                ));
            }
        }
        ServiceLocator::SetDatabase(new FakeDatabase());

        parent::tearDown();
    }

    public function testUpdateCreditCostAddsANewPrice(): void
    {
        $this->repository->UpdateCreditCost(new CreditCost(count: self::CREDIT_COUNT, cost: self::NEW_COST, currency: self::NEW_CURRENCY));

        $this->assertSame(
            [['credit_count' => '5', 'credit_cost' => '20.25', 'credit_currency' => 'EUR']],
            $this->creditCostRows()
        );
    }

    public function testUpdateCreditCostReplacesAnExistingPrice(): void
    {
        $this->storeOriginalCreditCost();

        $this->repository->UpdateCreditCost(new CreditCost(count: self::CREDIT_COUNT, cost: self::NEW_COST, currency: self::NEW_CURRENCY));

        $this->assertSame(
            [['credit_count' => '5', 'credit_cost' => '20.25', 'credit_currency' => 'EUR']],
            $this->creditCostRows()
        );
    }

    public function testUpdateCreditCostLeavesOtherPricesAlone(): void
    {
        $this->storeOriginalCreditCost();

        $this->repository->UpdateCreditCost(new CreditCost(count: self::CREDIT_COUNT + 1, cost: self::NEW_COST, currency: self::NEW_CURRENCY));

        $this->assertSame(
            [
                ['credit_count' => '5', 'credit_cost' => '12.50', 'credit_currency' => 'USD'],
                ['credit_count' => '6', 'credit_cost' => '20.25', 'credit_currency' => 'EUR'],
            ],
            $this->creditCostRows()
        );
    }

    public function testRejectedCreditCurrencyKeepsTheExistingPrice(): void
    {
        $this->storeOriginalCreditCost();

        $this->assertWriteFails(function (): void {
            $this->repository->UpdateCreditCost(new CreditCost(
                count: self::CREDIT_COUNT,
                cost: self::NEW_COST,
                currency: self::CURRENCY_LONGER_THAN_COLUMN
            ));
        });

        $this->assertSame(
            [['credit_count' => '5', 'credit_cost' => '12.50', 'credit_currency' => 'USD']],
            $this->creditCostRows()
        );
    }

    public function testRejectedCreditCostKeepsTheExistingPrice(): void
    {
        $this->storeOriginalCreditCost();

        $this->assertWriteFails(function (): void {
            $this->repository->UpdateCreditCost(new CreditCost(
                count: self::CREDIT_COUNT,
                cost: self::COST_ABOVE_COLUMN_MAXIMUM,
                currency: self::NEW_CURRENCY
            ));
        });

        $this->assertSame(
            [['credit_count' => '5', 'credit_cost' => '12.50', 'credit_currency' => 'USD']],
            $this->creditCostRows()
        );
    }

    public function testUpdateGatewayAddsItsSettings(): void
    {
        $this->repository->UpdatePayPalGateway(new PayPalGateway(enabled: true, clientId: 'client', secret: 'secret', environment: 'live'));

        $this->assertSame(
            [PayPalGateway::CLIENT_ID => 'client', PayPalGateway::ENVIRONMENT => 'live', PayPalGateway::SECRET => 'secret'],
            $this->gatewaySettings(PaymentGateways::PAYPAL)
        );
    }

    public function testUpdateGatewayReplacesItsSettings(): void
    {
        $this->repository->UpdatePayPalGateway(new PayPalGateway(enabled: true, clientId: 'client', secret: 'secret', environment: 'live'));

        $this->repository->UpdatePayPalGateway(new PayPalGateway(enabled: true, clientId: 'client2', secret: 'secret2', environment: 'sandbox'));

        $this->assertSame(
            [PayPalGateway::CLIENT_ID => 'client2', PayPalGateway::ENVIRONMENT => 'sandbox', PayPalGateway::SECRET => 'secret2'],
            $this->gatewaySettings(PaymentGateways::PAYPAL)
        );
    }

    public function testUpdateGatewayLeavesTheOtherGatewayAlone(): void
    {
        $this->repository->UpdateStripeGateway(new StripeGateway(enabled: true, publishableKey: 'publishable', secretKey: 'secret'));

        $this->repository->UpdatePayPalGateway(new PayPalGateway(enabled: true, clientId: 'client', secret: 'secret', environment: 'live'));
        $this->repository->UpdatePayPalGateway(new PayPalGateway(enabled: false, clientId: null, secret: null, environment: null));

        $this->assertSame(
            [StripeGateway::PUBLISHABLE_KEY => 'publishable', StripeGateway::SECRET_KEY => 'secret'],
            $this->gatewaySettings(PaymentGateways::STRIPE)
        );
    }

    public function testDisablingAGatewayRemovesItsSettings(): void
    {
        $this->repository->UpdateStripeGateway(new StripeGateway(enabled: true, publishableKey: 'publishable', secretKey: 'secret'));

        $this->repository->UpdateStripeGateway(new StripeGateway(enabled: false, publishableKey: null, secretKey: null));

        $this->assertSame([], $this->gatewaySettings(PaymentGateways::STRIPE));
    }

    public function testRejectedGatewaySettingKeepsEveryExistingSetting(): void
    {
        $this->repository->UpdatePayPalGateway(new PayPalGateway(enabled: true, clientId: 'client', secret: 'secret', environment: 'live'));

        // The client id comes first and is storable; only the secret is rejected.
        // Neither may be written.
        $this->assertWriteFails(function (): void {
            $this->repository->UpdatePayPalGateway(new PayPalGateway(enabled: true, clientId: 'client2', secret: null, environment: 'sandbox'));
        });

        $this->assertSame(
            [PayPalGateway::CLIENT_ID => 'client', PayPalGateway::ENVIRONMENT => 'live', PayPalGateway::SECRET => 'secret'],
            $this->gatewaySettings(PaymentGateways::PAYPAL)
        );
    }

    public function testUpdateGatewayRemovesASettingItNoLongerUses(): void
    {
        $this->storeGatewaySetting(gatewayType: PaymentGateways::PAYPAL, name: self::OBSOLETE_SETTING_NAME);

        $this->repository->UpdatePayPalGateway(new PayPalGateway(enabled: true, clientId: 'client', secret: 'secret', environment: 'live'));

        $this->assertSame(
            [PayPalGateway::CLIENT_ID => 'client', PayPalGateway::ENVIRONMENT => 'live', PayPalGateway::SECRET => 'secret'],
            $this->gatewaySettings(PaymentGateways::PAYPAL)
        );
    }

    public function testUpdateGatewayKeepsAnotherGatewaysUnknownSetting(): void
    {
        $this->storeGatewaySetting(gatewayType: PaymentGateways::STRIPE, name: self::OBSOLETE_SETTING_NAME);

        $this->repository->UpdatePayPalGateway(new PayPalGateway(enabled: true, clientId: 'client', secret: 'secret', environment: 'live'));

        $this->assertSame(
            [self::OBSOLETE_SETTING_NAME => self::OBSOLETE_SETTING_VALUE],
            $this->gatewaySettings(PaymentGateways::STRIPE)
        );
    }

    public function testRejectedGatewaySettingRemovesNothing(): void
    {
        $this->storeGatewaySetting(gatewayType: PaymentGateways::STRIPE, name: self::OBSOLETE_SETTING_NAME);

        // The cleanup must not run when the write before it fails.
        $this->assertWriteFails(function (): void {
            $this->repository->UpdateStripeGateway(new StripeGateway(enabled: true, publishableKey: 'publishable', secretKey: null));
        });

        $this->assertSame(
            [self::OBSOLETE_SETTING_NAME => self::OBSOLETE_SETTING_VALUE],
            $this->gatewaySettings(PaymentGateways::STRIPE)
        );
    }

    public function testRejectedGatewaySettingStoresNothingForANewGateway(): void
    {
        $this->assertWriteFails(function (): void {
            $this->repository->UpdateStripeGateway(new StripeGateway(enabled: true, publishableKey: 'publishable', secretKey: null));
        });

        $this->assertSame([], $this->gatewaySettings(PaymentGateways::STRIPE));
    }

    public function testStoredGatewayIsLoadedBack(): void
    {
        $this->repository->UpdateStripeGateway(new StripeGateway(enabled: true, publishableKey: 'publishable', secretKey: "it's a secret"));

        $stripe = $this->repository->GetStripeGateway();

        $this->assertTrue($stripe->IsEnabled());
        $this->assertSame('publishable', $stripe->PublishableKey());
        $this->assertSame("it's a secret", $stripe->SecretKey());
    }

    private function assertWriteFails(Closure $write): void
    {
        try {
            $write();
        } catch (Exception) {
            return;
        }

        $this->fail('The database should have rejected the write');
    }

    private function storeOriginalCreditCost(): void
    {
        $this->database->Execute($this->command(
            'INSERT INTO `payment_configuration` (`credit_count`, `credit_cost`, `credit_currency`) VALUES (@count, @cost, @currency)',
            ['@count' => self::CREDIT_COUNT, '@cost' => self::ORIGINAL_COST, '@currency' => self::ORIGINAL_CURRENCY]
        ));
    }

    private function storeGatewaySetting(string $gatewayType, string $name): void
    {
        $this->database->Execute($this->command(
            'INSERT INTO `payment_gateway_settings` (`gateway_type`, `setting_name`, `setting_value`) VALUES (@type, @name, @value)',
            ['@type' => $gatewayType, '@name' => $name, '@value' => self::OBSOLETE_SETTING_VALUE]
        ));
    }

    private function emptyPaymentTables(): void
    {
        $this->database->Execute(new AdHocCommand('DELETE FROM `payment_configuration`'));
        $this->database->Execute(new AdHocCommand('DELETE FROM `payment_gateway_settings`'));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function creditCostRows(): array
    {
        return $this->rows('SELECT `credit_count`, `credit_cost`, `credit_currency` FROM `payment_configuration` ORDER BY `credit_count`');
    }

    /**
     * @return array<string, string> setting value by setting name, ordered by name
     */
    private function gatewaySettings(string $gatewayType): array
    {
        $reader = $this->database->Query($this->command(
            'SELECT `setting_name`, `setting_value` FROM `payment_gateway_settings` WHERE `gateway_type` = @type ORDER BY `setting_name`',
            ['@type' => $gatewayType]
        ));

        $settings = [];
        while ($row = $reader->GetRow()) {
            $settings[$row['setting_name']] = $row['setting_value'];
        }
        $reader->Free();

        return $settings;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function rows(string $sql): array
    {
        $reader = $this->database->Query(new AdHocCommand($sql));

        $rows = [];
        while ($row = $reader->GetRow()) {
            $rows[] = $row;
        }
        $reader->Free();

        return $rows;
    }

    /**
     * @param array<string, int|string|null> $parameters
     */
    private function command(string $sql, array $parameters): SqlCommand
    {
        $command = new SqlCommand($sql);
        foreach ($parameters as $name => $value) {
            $command->AddParameter(new Parameter($name, $value));
        }

        return $command;
    }
}
