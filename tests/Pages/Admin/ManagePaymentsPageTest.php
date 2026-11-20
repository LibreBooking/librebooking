<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/ManagePaymentsPage.php');

/**
 * These tests read through the real Server, not FakeServer, because the
 * behaviour under test is how Server::GetForm() escapes the submitted bytes.
 */
class ManagePaymentsPageTest extends TestBase
{
    private const GATEWAY_SETTING_KEYS = [
        FormKeys::PAYPAL_CLIENT_ID,
        FormKeys::PAYPAL_SECRET,
        FormKeys::PAYPAL_ENVIRONMENT,
        FormKeys::STRIPE_PUBLISHABLE_KEY,
        FormKeys::STRIPE_SECRET_KEY,
    ];

    public function tearDown(): void
    {
        foreach (self::GATEWAY_SETTING_KEYS as $key) {
            unset($_POST[$key]);
        }

        parent::tearDown();
    }

    public function testGatewaySettingIsReturned(): void
    {
        $_POST[FormKeys::PAYPAL_SECRET] = 'secret';

        $this->assertSame('secret', $this->CreatePage()->GetPayPalSecret());
    }

    public function testGatewaySettingKeepsTheFormEscaping(): void
    {
        $_POST[FormKeys::PAYPAL_SECRET] = 'a&b';

        $this->assertSame('a&amp;b', $this->CreatePage()->GetPayPalSecret());
    }

    public function testMissingGatewaySettingIsNull(): void
    {
        $this->assertNull($this->CreatePage()->GetPayPalSecret());
    }

    public function testGatewaySettingWithMultiByteCharactersIsReturned(): void
    {
        $_POST[FormKeys::PAYPAL_SECRET] = "secret\u{20AC}";

        $this->assertSame("secret\u{20AC}", $this->CreatePage()->GetPayPalSecret());
    }

    public function testEveryGatewaySettingWithInvalidUtf8IsNull(): void
    {
        // Without the check, the escaping turns the invalid byte into U+FFFD
        // and the presenter sees a valid, different string.
        foreach (self::GATEWAY_SETTING_KEYS as $key) {
            $_POST[$key] = "secret\xFF";
        }
        $page = $this->CreatePage();

        $this->assertNull($page->GetPayPalClientId());
        $this->assertNull($page->GetPayPalSecret());
        $this->assertNull($page->GetPayPalEnvironment());
        $this->assertNull($page->GetStripePublishableKey());
        $this->assertNull($page->GetStripeSecretKey());
    }

    private function CreatePage(): ManagePaymentsPage
    {
        return new class () extends ManagePaymentsPage {
            public function __construct()
            {
                $this->server = new Server();
            }
        };
    }
}
