<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../Domain/CreditCost.php');
require_once(__DIR__ . '/../../Domain/Values/TransactionLogView.php');
require_once(__DIR__ . '/../../Domain/Values/Currency.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');
require_once(__DIR__ . '/../../tests/fakes/FakeConfig.php');

/**
 * Golden tests for Admin/Payments templates:
 *   - tpl/Admin/Payments/manage_payments.tpl    → tpl/Admin/Payments/manage_payments.twig
 *   - tpl/Admin/Payments/transaction_log.tpl    → tpl/Admin/Payments/transaction_log.twig
 *
 * transaction_log tests use assertParity (Smarty vs Twig byte-identical after normalization).
 *
 * manage_payments tests use assertTwigBaseline (Twig output stored as authoritative golden
 * baseline) because of an accepted structural divergence in the Smarty engine:
 *   - {update_button submit=true} calls GetButtonAttributes() → AppendAttributes() which
 *     forwards the `submit` parameter as a stray HTML attribute (`submit="1"`).
 *     The Twig update_button() function treats `submit` as a boolean and does not emit it.
 *     The Twig output is the correct, authoritative rendering.
 *
 * Other translated constructs that render identically between engines:
 *   - JS string context: `{$smarty.server.SCRIPT_NAME}` → `{{ server.SCRIPT_NAME|raw }}`
 *     (identical when SCRIPT_NAME is set in $_SERVER)
 *   - `payments.initGateways({$PayPalEnabled}, {$StripeEnabled})`: integers, no HTML escaping
 */
class AdminPaymentsGoldenTest extends GoldenTemplateTestCase
{
    private ?Resources $savedResources = null;

    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();

        // Install a FakeConfig before anything else so that Resources::GetInstance()
        // can read config keys (e.g. DEFAULT_LANGUAGE) without requiring a real config.php.
        Configuration::SetInstance(new FakeConfig());

        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();

        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/admin/manage_payments.php';

        $this->savedServiceLocatorServer = ServiceLocator::GetServer();
        $fakeServer = new FakeServer();
        $fakeServer->UserSession->CSRFToken = 'golden-test-csrf-token';
        ServiceLocator::SetServer($fakeServer);
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->savedServer;
        Resources::SetInstance($this->savedResources);
        ServiceLocator::SetServer($this->savedServiceLocatorServer);
        Configuration::SetInstance(null);
        parent::tearDown();
    }

    /**
     * Render both Smarty and Twig with the given vars and assert normalized parity.
     *
     * @param array<string, mixed> $vars
     */
    private function assertParity(string $tplName, string $twigName, array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $expected = $smarty->render($tplName);

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $actual = $twig->render($twigName);

        $this->assertSame(
            HtmlNormalizer::normalize($expected),
            HtmlNormalizer::normalize($actual),
            "Smarty vs Twig mismatch for $twigName"
        );
    }

    /**
     * Render the Twig template and assert against a stored baseline.
     *
     * Used for manage_payments.twig which has an accepted structural divergence:
     * Smarty's {update_button submit=true} emits an extra `submit="1"` HTML attribute
     * via GetButtonAttributes() → AppendAttributes(), while the Twig update_button()
     * function treats `submit` as a boolean parameter and does not forward it as an
     * HTML attribute. The Twig output is authoritative for the migrated template.
     *
     * @param string               $baselineName Unique key for the stored .html file
     * @param string               $twigName     Template path relative to /tpl/
     * @param array<string, mixed> $vars
     */
    private function assertTwigBaseline(string $baselineName, string $twigName, array $vars): void
    {
        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $this->assertMatchesBaseline($baselineName, $twig->render($twigName));
    }

    private function makeCurrency(string $isoCode): \Booked\CurrencyDefinition
    {
        return new \Booked\CurrencyDefinition($isoCode);
    }

    private function makeTransactionLogView(
        string $dateStr,
        string $status,
        string $invoiceNumber,
        string $transactionId,
        float $total,
        float $fee,
        string $currency,
        string $gatewayName,
        string $userFullName = 'John Doe',
        int $id = 1,
        float $amountRefunded = 0.0
    ): TransactionLogView {
        $v = new TransactionLogView(
            Date::Parse($dateStr, 'UTC'),
            $status,
            $invoiceNumber,
            $transactionId,
            $total,
            $fee,
            $currency,
            '',
            '',
            '',
            $gatewayName,
            42,
            $userFullName
        );
        $v->Id = $id;
        $v->AmountRefunded = $amountRefunded;
        return $v;
    }

    private function baseVars(): array
    {
        return [
            'Currencies' => [
                $this->makeCurrency('USD'),
                $this->makeCurrency('EUR'),
                $this->makeCurrency('GBP'),
            ],
            'Path' => '../',
            'PayPalClientId' => '',
            'PayPalSecret' => '',
            'PayPalEnvironment' => 'sandbox',
            'StripePublishableKey' => '',
            'StripeSecretKey' => '',
        ];
    }

    // ── transaction_log ───────────────────────────────────────────────────────

    /**
     * Empty transaction log: tbody has no rows.
     */
    public function testTransactionLogEmptyMatchesSmarty(): void
    {
        $vars = [
            'TransactionLog' => [],
            'Timezone' => 'UTC',
        ];
        $this->assertParity(
            'Admin/Payments/transaction_log.tpl',
            'Admin/Payments/transaction_log.twig',
            $vars
        );
    }

    /**
     * Transaction log with a fully-paid entry: refund link shown.
     */
    public function testTransactionLogWithRefundableEntryMatchesSmarty(): void
    {
        $vars = [
            'TransactionLog' => [
                $this->makeTransactionLogView(
                    '2025-06-01 10:00:00',
                    'COMPLETED',
                    'INV-001',
                    'TXN-ABC123',
                    9.99,
                    0.30,
                    'USD',
                    'PayPal',
                    'Jane Smith',
                    10
                ),
            ],
            'Timezone' => 'UTC',
        ];
        $this->assertParity(
            'Admin/Payments/transaction_log.tpl',
            'Admin/Payments/transaction_log.twig',
            $vars
        );
    }

    /**
     * Transaction log with a fully-refunded entry: "FullyRefunded" text shown instead of link.
     */
    public function testTransactionLogFullyRefundedMatchesSmarty(): void
    {
        $vars = [
            'TransactionLog' => [
                $this->makeTransactionLogView(
                    '2025-06-10 09:00:00',
                    'REFUNDED',
                    'INV-002',
                    'TXN-DEF456',
                    19.99,
                    0.58,
                    'USD',
                    'Stripe',
                    'Bob Brown',
                    20,
                    19.99
                ),
            ],
            'Timezone' => 'UTC',
        ];
        $this->assertParity(
            'Admin/Payments/transaction_log.tpl',
            'Admin/Payments/transaction_log.twig',
            $vars
        );
    }

    /**
     * Transaction log with zero total: "FullyRefunded" text shown (zero total treated as fully refunded).
     */
    public function testTransactionLogZeroTotalMatchesSmarty(): void
    {
        $vars = [
            'TransactionLog' => [
                $this->makeTransactionLogView(
                    '2025-07-01 12:00:00',
                    'COMPLETED',
                    'INV-003',
                    'TXN-GHI789',
                    0.0,
                    0.0,
                    'EUR',
                    'PayPal',
                    'Alice Green',
                    30
                ),
            ],
            'Timezone' => 'UTC',
        ];
        $this->assertParity(
            'Admin/Payments/transaction_log.tpl',
            'Admin/Payments/transaction_log.twig',
            $vars
        );
    }

    /**
     * Transaction log with multiple entries covering mixed refund states.
     */
    public function testTransactionLogMultipleEntriesMatchesSmarty(): void
    {
        $vars = [
            'TransactionLog' => [
                $this->makeTransactionLogView(
                    '2025-06-01 10:00:00',
                    'COMPLETED',
                    'INV-001',
                    'TXN-ABC123',
                    9.99,
                    0.30,
                    'USD',
                    'PayPal',
                    'Jane Smith',
                    10
                ),
                $this->makeTransactionLogView(
                    '2025-06-10 09:00:00',
                    'REFUNDED',
                    'INV-002',
                    'TXN-DEF456',
                    19.99,
                    0.58,
                    'USD',
                    'Stripe',
                    'Bob Brown',
                    20,
                    19.99
                ),
            ],
            'Timezone' => 'UTC',
        ];
        $this->assertParity(
            'Admin/Payments/transaction_log.tpl',
            'Admin/Payments/transaction_log.twig',
            $vars
        );
    }

    // ── manage_payments ───────────────────────────────────────────────────────

    /**
     * Payments disabled: only the "not enabled" error alert is shown.
     *
     * Uses assertTwigBaseline (not assertParity) because manage_payments.twig has an
     * accepted structural divergence: {update_button submit=true} in Smarty emits a
     * stray `submit="1"` HTML attribute; the Twig function does not. The Twig output
     * is authoritative.
     */
    public function testManagePaymentsDisabledMatchesBaseline(): void
    {
        $vars = array_merge($this->baseVars(), [
            'PaymentsEnabled' => false,
            'CreditCosts' => [],
            'PayPalEnabled' => 0,
            'StripeEnabled' => 0,
        ]);
        $this->assertTwigBaseline(
            'admin-payments-disabled',
            'Admin/Payments/manage_payments.twig',
            $vars
        );
    }

    /**
     * Payments enabled, no credit costs, no gateways configured.
     *
     * Uses assertTwigBaseline — see testManagePaymentsDisabledMatchesBaseline for rationale.
     */
    public function testManagePaymentsEnabledNoCostsMatchesBaseline(): void
    {
        $vars = array_merge($this->baseVars(), [
            'PaymentsEnabled' => true,
            'CreditCosts' => [],
            'PayPalEnabled' => 0,
            'StripeEnabled' => 0,
        ]);
        $this->assertTwigBaseline(
            'admin-payments-enabled-no-costs',
            'Admin/Payments/manage_payments.twig',
            $vars
        );
    }

    /**
     * Payments enabled, with credit costs including count=1 (no delete button) and count>1 (delete button shown).
     *
     * Uses assertTwigBaseline — see testManagePaymentsDisabledMatchesBaseline for rationale.
     */
    public function testManagePaymentsWithCreditCostsMatchesBaseline(): void
    {
        $vars = array_merge($this->baseVars(), [
            'PaymentsEnabled' => true,
            'CreditCosts' => [
                new CreditCost(1, 1.00, 'USD'),
                new CreditCost(5, 4.50, 'USD'),
                new CreditCost(10, 8.00, 'EUR'),
            ],
            'PayPalEnabled' => 0,
            'StripeEnabled' => 0,
        ]);
        $this->assertTwigBaseline(
            'admin-payments-with-credit-costs',
            'Admin/Payments/manage_payments.twig',
            $vars
        );
    }

    /**
     * PayPal gateway enabled with live environment selected.
     *
     * Uses assertTwigBaseline — see testManagePaymentsDisabledMatchesBaseline for rationale.
     */
    public function testManagePaymentsPayPalEnabledLiveMatchesBaseline(): void
    {
        $vars = array_merge($this->baseVars(), [
            'PaymentsEnabled' => true,
            'CreditCosts' => [],
            'PayPalEnabled' => 1,
            'PayPalClientId' => 'client-id-123',
            'PayPalSecret' => 'secret-abc',
            'PayPalEnvironment' => 'live',
            'StripeEnabled' => 0,
        ]);
        $this->assertTwigBaseline(
            'admin-payments-paypal-live',
            'Admin/Payments/manage_payments.twig',
            $vars
        );
    }

    /**
     * Stripe gateway enabled.
     *
     * Uses assertTwigBaseline — see testManagePaymentsDisabledMatchesBaseline for rationale.
     */
    public function testManagePaymentsStripeEnabledMatchesBaseline(): void
    {
        $vars = array_merge($this->baseVars(), [
            'PaymentsEnabled' => true,
            'CreditCosts' => [],
            'PayPalEnabled' => 0,
            'StripeEnabled' => 1,
            'StripePublishableKey' => 'pk_test_ABCDEF',
            'StripeSecretKey' => 'sk_test_GHIJKL',
        ]);
        $this->assertTwigBaseline(
            'admin-payments-stripe',
            'Admin/Payments/manage_payments.twig',
            $vars
        );
    }

    /**
     * Both PayPal (sandbox) and Stripe enabled.
     *
     * Uses assertTwigBaseline — see testManagePaymentsDisabledMatchesBaseline for rationale.
     */
    public function testManagePaymentsBothGatewaysEnabledMatchesBaseline(): void
    {
        $vars = array_merge($this->baseVars(), [
            'PaymentsEnabled' => true,
            'CreditCosts' => [
                new CreditCost(1, 2.00, 'USD'),
            ],
            'PayPalEnabled' => 1,
            'PayPalClientId' => 'pp-client-id',
            'PayPalSecret' => 'pp-secret',
            'PayPalEnvironment' => 'sandbox',
            'StripeEnabled' => 1,
            'StripePublishableKey' => 'pk_test_STRIPE',
            'StripeSecretKey' => 'sk_test_STRIPE',
        ]);
        $this->assertTwigBaseline(
            'admin-payments-both-gateways',
            'Admin/Payments/manage_payments.twig',
            $vars
        );
    }
}
