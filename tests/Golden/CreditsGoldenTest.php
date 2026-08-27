<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../Domain/CreditCost.php');
require_once(__DIR__ . '/../../Domain/Values/CreditLogView.php');
require_once(__DIR__ . '/../../Domain/Values/TransactionLogView.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');

/**
 * Live Smarty-vs-Twig golden comparison for Credits templates:
 *   - tpl/Credits/credit_log.tpl        → tpl/Credits/credit_log.twig
 *   - tpl/Credits/transaction_log.tpl   → tpl/Credits/transaction_log.twig
 *   - tpl/Credits/user_credits.tpl      → tpl/Credits/user_credits.twig
 *   - tpl/Credits/checkout.tpl          → tpl/Credits/checkout.twig
 *
 * Both engines are rendered in the same process with identical template variables
 * and superglobal state; normalized outputs are asserted byte-identical.
 */
class CreditsGoldenTest extends GoldenTemplateTestCase
{
    private ?Resources $savedResources = null;

    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();

        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/index.php';

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

    private function makeCreditLogEntry(
        string $dateStr,
        string $note,
        int $originalCount,
        int $count
    ): CreditLogView {
        return new CreditLogView(
            Date::Parse($dateStr, 'UTC'),
            $note,
            $originalCount,
            $count
        );
    }

    private function makeTransactionLogEntry(
        string $dateStr,
        string $status,
        string $invoiceNumber,
        string $transactionId,
        float $total,
        float $fee,
        string $currency,
        string $gatewayName,
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
            1
        );
        $v->AmountRefunded = $amountRefunded;
        return $v;
    }

    // ── credit_log ────────────────────────────────────────────────────────────

    /**
     * Empty credit log: tbody has no rows.
     */
    public function testCreditLogEmptyMatchesSmarty(): void
    {
        $vars = [
            'CreditLog' => [],
            'Timezone' => 'UTC',
        ];
        $this->assertParity(
            'Credits/credit_log.tpl',
            'Credits/credit_log.twig',
            $vars
        );
    }

    /**
     * Two credit log entries: both rows appear.
     */
    public function testCreditLogWithEntriesMatchesSmarty(): void
    {
        $vars = [
            'CreditLog' => [
                $this->makeCreditLogEntry('2025-06-01 10:00:00', 'Initial grant', 0, 10),
                $this->makeCreditLogEntry('2025-06-15 14:30:00', 'Booking deduction', 10, 8),
            ],
            'Timezone' => 'UTC',
        ];
        $this->assertParity(
            'Credits/credit_log.tpl',
            'Credits/credit_log.twig',
            $vars
        );
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
            'Credits/transaction_log.tpl',
            'Credits/transaction_log.twig',
            $vars
        );
    }

    /**
     * Two transaction log entries: both rows appear with formatted currency.
     */
    public function testTransactionLogWithEntriesMatchesSmarty(): void
    {
        $vars = [
            'TransactionLog' => [
                $this->makeTransactionLogEntry('2025-06-01 10:00:00', 'COMPLETED', 'INV-001', 'TXN-ABC123', 9.99, 0.30, 'USD', 'PayPal'),
                $this->makeTransactionLogEntry('2025-06-10 09:00:00', 'REFUNDED', 'INV-002', 'TXN-DEF456', 19.99, 0.58, 'USD', 'Stripe', 19.99),
            ],
            'Timezone' => 'UTC',
        ];
        $this->assertParity(
            'Credits/transaction_log.tpl',
            'Credits/transaction_log.twig',
            $vars
        );
    }

    // ── user_credits ──────────────────────────────────────────────────────────

    /**
     * Purchase not allowed: only the credit-log tab is shown.
     */
    public function testUserCreditsNoPurchaseMatchesSmarty(): void
    {
        $vars = [
            'CurrentCredits' => 5.0,
            'AllowPurchasingCredits' => false,
            'IsCreditCostSet' => false,
            'CreditCosts' => [],
            'CreditCost' => '$1.00',
        ];
        $this->assertParity(
            'Credits/user_credits.tpl',
            'Credits/user_credits.twig',
            $vars
        );
    }

    /**
     * Purchase allowed with credit costs: all three tabs + purchase form are shown.
     */
    public function testUserCreditsPurchaseEnabledMatchesSmarty(): void
    {
        $vars = [
            'CurrentCredits' => 3.0,
            'AllowPurchasingCredits' => true,
            'IsCreditCostSet' => true,
            'CreditCosts' => [
                new CreditCost(1, 1.00, 'USD'),
                new CreditCost(5, 4.50, 'USD'),
            ],
            'CreditCost' => '$1.00',
        ];
        $this->assertParity(
            'Credits/user_credits.tpl',
            'Credits/user_credits.twig',
            $vars
        );
    }

    // ── checkout ──────────────────────────────────────────────────────────────

    /**
     * Empty cart: alert shown, no payment buttons.
     */
    public function testCheckoutEmptyCartMatchesSmarty(): void
    {
        $vars = [
            'IsCartEmpty' => true,
            'PayPalEnabled' => false,
            'PayPalClientId' => '',
            'StripeEnabled' => false,
            'StripePublishableKey' => '',
            'Currency' => 'USD',
            'Email' => 'user@example.com',
            'CreditCount' => 1,
            'CreditQuantity' => 1.0,
            'CreditCost' => '$1.00',
            'Total' => '$1.00',
            'TotalUnformatted' => 1.00,
            'ScriptUrl' => 'https://example.com',
        ];
        $this->assertParity(
            'Credits/checkout.tpl',
            'Credits/checkout.twig',
            $vars
        );
    }

    /**
     * Cart with PayPal only: PayPal button shown, no Stripe button.
     */
    public function testCheckoutPayPalOnlyMatchesSmarty(): void
    {
        $vars = [
            'IsCartEmpty' => false,
            'PayPalEnabled' => true,
            'PayPalClientId' => 'PAYPAL-CLIENT-ID',
            'StripeEnabled' => false,
            'StripePublishableKey' => '',
            'Currency' => 'USD',
            'Email' => 'buyer@example.com',
            'CreditCount' => 2,
            'CreditQuantity' => 3.0,
            'CreditCost' => '$1.50',
            'Total' => '$4.50',
            'TotalUnformatted' => 4.50,
            'ScriptUrl' => 'https://example.com',
        ];
        $this->assertParity(
            'Credits/checkout.tpl',
            'Credits/checkout.twig',
            $vars
        );
    }

    /**
     * Cart with Stripe only: Stripe button shown, no PayPal button.
     */
    public function testCheckoutStripeOnlyMatchesSmarty(): void
    {
        $vars = [
            'IsCartEmpty' => false,
            'PayPalEnabled' => false,
            'PayPalClientId' => '',
            'StripeEnabled' => true,
            'StripePublishableKey' => 'pk_test_ABCDEF',
            'Currency' => 'USD',
            'Email' => 'stripe-buyer@example.com',
            'CreditCount' => 1,
            'CreditQuantity' => 1.0,
            'CreditCost' => '$2.00',
            'Total' => '$2.00',
            'TotalUnformatted' => 2.00,
            'ScriptUrl' => 'https://example.com',
        ];
        $this->assertParity(
            'Credits/checkout.tpl',
            'Credits/checkout.twig',
            $vars
        );
    }

    /**
     * Cart with both PayPal and Stripe: both payment buttons shown.
     */
    public function testCheckoutBothPaymentMethodsMatchesSmarty(): void
    {
        $vars = [
            'IsCartEmpty' => false,
            'PayPalEnabled' => true,
            'PayPalClientId' => 'PAYPAL-CLIENT-ID',
            'StripeEnabled' => true,
            'StripePublishableKey' => 'pk_test_ABCDEF',
            'Currency' => 'EUR',
            'Email' => 'dual-buyer@example.com',
            'CreditCount' => 5,
            'CreditQuantity' => 2.0,
            'CreditCost' => '€3.00',
            'Total' => '€6.00',
            'TotalUnformatted' => 6.00,
            'ScriptUrl' => 'https://example.com',
        ];
        $this->assertParity(
            'Credits/checkout.tpl',
            'Credits/checkout.twig',
            $vars
        );
    }
}
