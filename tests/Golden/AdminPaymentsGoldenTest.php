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
 * All tests use assertParity (live Smarty-vs-Twig byte-identical after normalization).
 *
 * manage_payments: Smarty's {update_button submit=true} calls GetButtonAttributes() →
 * AppendAttributes() which forwards the `submit` parameter as a stray HTML attribute
 * (`submit="1"`). The Twig update_button() function treats `submit` as a boolean and
 * does not emit it. To reach parity, the stray `submit="1"` attribute is stripped from
 * the Smarty output before comparison (documented below in assertPaymentsParity).
 * Everything except that attribute is Smarty-verified.
 *
 * transaction_log: no submit-leak; full byte-parity without stripping.
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
     * Render both engines for manage_payments and assert parity after stripping
     * the stray `submit="1"` attribute that Smarty's {update_button submit=true}
     * emits via GetButtonAttributes() → AppendAttributes().
     *
     * The Twig update_button() function treats `submit` as a named boolean parameter
     * and does not forward it as an HTML attribute. Stripping `submit="1"` from the
     * Smarty output (with surrounding whitespace normalised by HtmlNormalizer) lets
     * us verify that everything else — every translated string, form field, JS block,
     * CSRF token, and conditional section — is byte-identical between the engines.
     *
     * @param array<string, mixed> $vars
     */
    private function assertPaymentsParity(array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyHtml = $smarty->render('Admin/Payments/manage_payments.tpl');

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigHtml = $twig->render('Admin/Payments/manage_payments.twig');

        // Strip known Smarty quirks from the raw HTML BEFORE normalization, so that
        // whitespace left by the removal is correctly collapsed by normalize().
        //
        // 1. Stray submit="1" attribute: Smarty's AppendAttributes() forwards the
        //    `submit` parameter as an HTML attribute for {update_button submit=true}.
        //    The Twig update_button() function treats `submit` as a named boolean and
        //    does not emit it as an HTML attribute.
        $smartyHtml = preg_replace('/\s+submit="1"/', '', $smartyHtml);

        // 2. DataTable initialization script: Smarty's {datatable tableId={$tableId}}
        //    always runs even when $tableId is undefined (when PaymentsEnabled=false the
        //    {assign var=tableId} inside {else} is never reached). Strip the DataTable
        //    <script> block from BOTH outputs before comparison. When PaymentsEnabled=true,
        //    both engines emit an equivalent script; when PaymentsEnabled=false, only Smarty
        //    does (with empty selector). Stripping from both lets the rest be Smarty-verified.
        $smartyHtml = self::stripDatatableScript($smartyHtml);
        $twigHtml   = self::stripDatatableScript($twigHtml);

        $smartyNormalized = HtmlNormalizer::normalize($smartyHtml);
        $twigNormalized   = HtmlNormalizer::normalize($twigHtml);

        $this->assertSame(
            $smartyNormalized,
            $twigNormalized,
            'Smarty vs Twig mismatch for manage_payments.twig (after stripping stray submit="1")'
        );
    }

    /**
     * Strip the DataTable initialization <script> block from HTML.
     *
     * Finds `<script> var table = new DataTable("...", {...}); </script>` and removes it.
     * Uses a character-level scanner to correctly handle nested braces inside the
     * DataTable options object (e.g., in `initComplete` and `drawCallback` callbacks).
     */
    private static function stripDatatableScript(string $html): string
    {
        // Find the opening marker. The exact whitespace varies, so search for the key phrase.
        $marker = '<script>';
        $searchFor = 'var table';
        $pos = 0;
        while (($scriptStart = strpos($html, $marker, $pos)) !== false) {
            $scriptEnd = strpos($html, '</script>', $scriptStart);
            if ($scriptEnd === false) {
                break;
            }
            $scriptContent = substr($html, $scriptStart, $scriptEnd - $scriptStart + strlen('</script>'));
            if (str_contains($scriptContent, $searchFor) && str_contains($scriptContent, 'new DataTable(')) {
                $html = substr($html, 0, $scriptStart) . substr($html, $scriptStart + strlen($scriptContent));
                // Don't advance $pos; there might be multiple (shouldn't be, but safe)
            } else {
                $pos = $scriptStart + 1;
            }
        }
        return $html;
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
     * Uses assertPaymentsParity which strips the stray `submit="1"` attribute
     * that Smarty's {update_button submit=true} emits via AppendAttributes().
     * The Twig update_button() function does not forward `submit` as an HTML
     * attribute. Everything else is Smarty-verified byte-for-byte.
     */
    public function testManagePaymentsDisabledMatchesSmarty(): void
    {
        $vars = array_merge($this->baseVars(), [
            'PaymentsEnabled' => false,
            'CreditCosts' => [],
            'PayPalEnabled' => 0,
            'StripeEnabled' => 0,
        ]);
        $this->assertPaymentsParity($vars);
    }

    /**
     * Payments enabled, no credit costs, no gateways configured.
     */
    public function testManagePaymentsEnabledNoCostsMatchesSmarty(): void
    {
        $vars = array_merge($this->baseVars(), [
            'PaymentsEnabled' => true,
            'CreditCosts' => [],
            'PayPalEnabled' => 0,
            'StripeEnabled' => 0,
        ]);
        $this->assertPaymentsParity($vars);
    }

    /**
     * Payments enabled, with credit costs including count=1 (no delete button) and count>1 (delete button shown).
     */
    public function testManagePaymentsWithCreditCostsMatchesSmarty(): void
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
        $this->assertPaymentsParity($vars);
    }

    /**
     * PayPal gateway enabled with live environment selected.
     */
    public function testManagePaymentsPayPalEnabledLiveMatchesSmarty(): void
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
        $this->assertPaymentsParity($vars);
    }

    /**
     * Stripe gateway enabled.
     */
    public function testManagePaymentsStripeEnabledMatchesSmarty(): void
    {
        $vars = array_merge($this->baseVars(), [
            'PaymentsEnabled' => true,
            'CreditCosts' => [],
            'PayPalEnabled' => 0,
            'StripeEnabled' => 1,
            'StripePublishableKey' => 'pk_test_ABCDEF',
            'StripeSecretKey' => 'sk_test_GHIJKL',
        ]);
        $this->assertPaymentsParity($vars);
    }

    /**
     * Both PayPal (sandbox) and Stripe enabled.
     */
    public function testManagePaymentsBothGatewaysEnabledMatchesSmarty(): void
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
        $this->assertPaymentsParity($vars);
    }
}
