<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;

require_once(ROOT_DIR . 'Domain/Values/Currency.php');

class CurrencyTest extends TestBase
{
    public static function toStripeProvider(): array
    {
        return [
            'whole amount' => ['USD', 10, 1000],
            // 19.99 * 100 is 1998.9999999999998 in floating point.
            'two decimal places' => ['USD', 19.99, 1999],
            // CreditCartSession::Total() multiplies cost by quantity.
            'product of floats' => ['USD', 1.15 * 3, 345],
            'numeric string' => ['USD', '19.99', 1999],
            'fraction of a cent rounds to nearest' => ['USD', 0.125, 13],
            // 1.005 * 100 is 100.49999999999999; PHP 8.4+ rounds that to 100.
            'half cent below its binary value' => ['USD', '1.005', 101],
            'another half cent below its binary value' => ['USD', 1.015, 102],
            'zero decimal currency' => ['JPY', 1500, 1500],
            'zero decimal currency with fraction' => ['JPY', 1500.4, 1500],
        ];
    }

    #[DataProvider('toStripeProvider')]
    public function testToStripeReturnsWholeMinorUnits(string $currencyCode, float|int|string $amount, int $expected): void
    {
        $currency = new \Booked\Currency($currencyCode);

        $this->assertSame($expected, $currency->ToStripe($amount));
    }

    public function testFromStripeConvertsMinorUnitsBack(): void
    {
        $this->assertEquals(19.99, (new \Booked\Currency('USD'))->FromStripe(1999));
        $this->assertEquals(1500, (new \Booked\Currency('JPY'))->FromStripe(1500));
    }
}
