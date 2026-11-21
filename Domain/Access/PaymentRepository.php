<?php

require_once(ROOT_DIR . 'Domain/CreditCost.php');
require_once(ROOT_DIR . 'Domain/PaymentGateway.php');
require_once(ROOT_DIR . 'Domain/Values/PayPalPaymentResult.php');
require_once(ROOT_DIR . 'Domain/Values/TransactionLogView.php');
require_once(ROOT_DIR . 'Domain/Access/PageableDataStore.php');
require_once(ROOT_DIR . 'lib/Database/namespace.php');
require_once(ROOT_DIR . 'lib/Database/Commands/namespace.php');

interface IPaymentRepository
{
    /**
     * @param CreditCost $credit
     */
    public function UpdateCreditCost($credit);

    /**
     * @param int $creditCount
     */
    public function DeleteCreditCost($creditCount);

    /**
     * @return CreditCost[]
     */
    public function GetCreditCosts();

    /**
     * @param PayPalGateway $gateway
     */
    public function UpdatePayPalGateway(PayPalGateway $gateway);

    /**
     * @param StripeGateway $gateway
     */
    public function UpdateStripeGateway(StripeGateway $gateway);

    /**
     * @return PayPalGateway
     */
    public function GetPayPalGateway();

    /**
     * @return StripeGateway
     */
    public function GetStripeGateway();

    /**
     * @param int|null $pageNumber null for no paging
     * @param int|null $pageSize null for no paging
     * @param int $userId
     * @param string $sortField
     * @param string $sortDirection
     * @param ISqlFilter $filter
     * @return PageableData|TransactionLogView[]
     */
    public function GetList($pageNumber, $pageSize, $userId = -1, $sortField = null, $sortDirection = null, $filter = null);

    /**
     * @param int $transactionLogId
     */
    public function GetTransactionLogView($transactionLogId): ?TransactionLogView;
}

class PaymentRepository implements IPaymentRepository
{
    public function UpdateCreditCost($credit)
    {
        // One statement that adds or overwrites, so a price the database
        // rejects leaves the existing price in place.
        ServiceLocator::GetDatabase()->Execute(new SetPaymentConfigurationCommand(
            creditCount: $credit->Count(),
            creditCost: $credit->Cost(),
            creditCurrency: $credit->Currency()
        ));
    }

    public function DeleteCreditCost($creditCount)
    {
        ServiceLocator::GetDatabase()->Execute(new DeletePaymentConfigurationCommand($creditCount));
    }

    public function GetCreditCosts()
    {
        $reader = ServiceLocator::GetDatabase()->Query(new GetPaymentConfigurationCommand());
        $res = [];
        for ($i = 0;$i < $reader->NumRows();$i++) {
            $row = $reader->GetRow();
            $res[] = new CreditCost($row[ColumnNames::CREDIT_COUNT], $row[ColumnNames::CREDIT_COST], $row[ColumnNames::CREDIT_CURRENCY]);
        }
        $reader->Free();

        if (empty($res)) {
            $res[] = new CreditCost();
        }
        return $res;
    }

    public function UpdatePayPalGateway(PayPalGateway $gateway)
    {
        $this->UpdateGateway($gateway);
    }

    public function UpdateStripeGateway(StripeGateway $gateway)
    {
        $this->UpdateGateway($gateway);
    }

    private function UpdateGateway(IPaymentGateway $gateway)
    {
        if (!$gateway->IsEnabled()) {
            ServiceLocator::GetDatabase()->Execute(new DeletePaymentGatewaySettingsCommand($gateway->GetGatewayType()));
            return;
        }

        // One statement for all the settings, so a setting the database
        // rejects leaves every existing setting in place. Deleting first and
        // then inserting would lose the stored settings when an insert fails.
        $settings = $gateway->Settings();
        ServiceLocator::GetDatabase()->Execute(new SetPaymentGatewaySettingsCommand(
            gatewayType: $gateway->GetGatewayType(),
            settings: $settings
        ));

        // Then remove any setting stored under a name the gateway no longer
        // uses, so the stored settings are exactly the ones just written.
        // This runs second on purpose: if it fails, the only result is a
        // leftover row that nothing reads.
        ServiceLocator::GetDatabase()->Execute(new DeleteObsoletePaymentGatewaySettingsCommand(
            gatewayType: $gateway->GetGatewayType(),
            currentSettingNames: array_map(
                callback: static fn (PaymentGatewaySetting $setting): string => $setting->Name(),
                array: $settings
            )
        ));
    }

    public function GetPayPalGateway()
    {
        $clientId = null;
        $secret = null;
        $environment = null;

        $reader = ServiceLocator::GetDatabase()->Query(new GetPaymentGatewaySettingsCommand(PaymentGateways::PAYPAL));
        while ($row = $reader->GetRow()) {
            if ($row[ColumnNames::GATEWAY_SETTING_NAME] == PayPalGateway::CLIENT_ID) {
                $clientId = $row[ColumnNames::GATEWAY_SETTING_VALUE];
            } elseif ($row[ColumnNames::GATEWAY_SETTING_NAME] == PayPalGateway::SECRET) {
                $secret = $row[ColumnNames::GATEWAY_SETTING_VALUE];
            } elseif ($row[ColumnNames::GATEWAY_SETTING_NAME] == PayPalGateway::ENVIRONMENT) {
                $environment = $row[ColumnNames::GATEWAY_SETTING_VALUE];
            }
        }

        $reader->Free();
        return PayPalGateway::Create($clientId, $secret, $environment);
    }

    public function GetStripeGateway()
    {
        $publishableKey = null;
        $secretKey = null;

        $reader = ServiceLocator::GetDatabase()->Query(new GetPaymentGatewaySettingsCommand(PaymentGateways::STRIPE));
        while ($row = $reader->GetRow()) {
            if ($row[ColumnNames::GATEWAY_SETTING_NAME] == StripeGateway::PUBLISHABLE_KEY) {
                $publishableKey = $row[ColumnNames::GATEWAY_SETTING_VALUE];
            } elseif ($row[ColumnNames::GATEWAY_SETTING_NAME] == StripeGateway::SECRET_KEY) {
                $secretKey = $row[ColumnNames::GATEWAY_SETTING_VALUE];
            }
        }

        $reader->Free();
        return StripeGateway::Create($publishableKey, $secretKey);
    }

    /**
     * @param int|null $pageNumber null for no paging
     * @param int|null $pageSize null for no paging
     * @param int $userId
     * @param string $sortField
     * @param string $sortDirection
     * @param ISqlFilter $filter
     * @return PageableData|TransactionLogView[]
     */
    public function GetList($pageNumber, $pageSize, $userId = -1, $sortField = null, $sortDirection = null, $filter = null)
    {
        $command = new GetAllTransactionLogsCommand($userId);

        if ($filter != null) {
            $command = new FilterCommand($command, $filter);
        }

        $builder = ['TransactionLogView', 'Populate'];
        return PageableDataStore::GetList($command, $builder, $pageNumber, $pageSize, $sortField, $sortDirection);
    }

    /**
     * @param int $transactionLogId
     */
    public function GetTransactionLogView($transactionLogId): ?TransactionLogView
    {
        $command = new GetTransactionLogCommand($transactionLogId);
        $reader = ServiceLocator::GetDatabase()->Query($command);
        if ($row = $reader->GetRow()) {
            $reader->Free();
            return TransactionLogView::Populate($row);
        }

        $reader->Free();
        return null;
    }
}
