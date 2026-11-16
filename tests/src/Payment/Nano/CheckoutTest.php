<?php

declare(strict_types=1);

namespace LibreBooking\Tests\Payment\Nano;

use InvalidArgumentException;
use LibreBooking\Payment\Nano\Address;
use LibreBooking\Payment\Nano\Amount;
use LibreBooking\Payment\Nano\Binding;
use LibreBooking\Payment\Nano\Blake2b;
use LibreBooking\Payment\Nano\Checkout;
use LibreBooking\Payment\Nano\ClaimStore;
use LibreBooking\Payment\Nano\NanoException;
use LibreBooking\Payment\Nano\NodeClient;
use LibreBooking\Payment\Nano\Verifier;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the dependency-free Nano (XNO) pieces under src/Payment/Nano:
 * the address codec (BLAKE2b checksum), raw-amount derivation, the xno-binding
 * v1 option C commitment, the block verifier and the checkout that ties them
 * to a claim store, with the node read and the clock injected.
 */
final class CheckoutTest extends TestCase
{
    // The published xno-binding v1 option C vector (docs/BINDING-STANDARD.md).
    private const BURN = 'nano_1111111111111111111111111111111111111111111111111111hifc8npp';
    private const PRICE_RAW = '50000000000000000000000000000'; // 0.05 XNO
    private const ORDER = 'order-0001';
    private const REP = 'nano_33q9dassq66es6mcay83n5kypqgpzjikr9bkc9ope9xch1borfphgehj4ozj';
    private const PAYER = 'nano_3t6k35gi95xu6tergt6p69ck76ogmitsa8mnijtpxm9fkcm736xtoncuohr3';
    private const NOW = 1763200000;
    private const HASH = 'A1B2C3D4E5F60718293A4B5C6D7E8F90A1B2C3D4E5F60718293A4B5C6D7E8F90';
    private const OTHER_HASH = 'B1B2C3D4E5F60718293A4B5C6D7E8F90A1B2C3D4E5F60718293A4B5C6D7E8F90';

    /**
     * @param array<string, mixed> $overrides
     *
     * @return array<string, mixed>
     */
    private function confirmedSend(array $overrides = []): array
    {
        $base = [
            'block_account' => self::PAYER,
            'amount' => self::PRICE_RAW,
            'confirmed' => 'true',
            'subtype' => 'send',
            'local_timestamp' => (string) self::NOW,
            'contents' => [
                'link_as_account' => self::BURN,
                'representative' => self::REP,
            ],
        ];
        $contents = array_merge($base['contents'], $overrides['contents'] ?? []);
        unset($overrides['contents']);

        return array_merge($base, $overrides, ['contents' => $contents]);
    }

    /**
     * A checkout that charges 0.05 XNO per unit, so an order total of "1" is
     * exactly the published vector's price.
     *
     * @param array<string, array<string, mixed>> $blocks block hash => block_info reply
     */
    private function checkout(array $blocks, ClaimStore $claims, int $now = self::NOW): Checkout
    {
        return new Checkout(
            payTo: self::BURN,
            xnoPrice: '0.05',
            claims: $claims,
            fetchBlock: static function (string $hash) use ($blocks): array {
                return $blocks[$hash] ?? ['error' => 'Block not found'];
            },
            clock: static fn (): int => $now,
        );
    }

    public function testBlake2bMatchesTheRfc7693Vector(): void
    {
        $this->assertSame(
            'ba80a53f981c4d0d6a2797b69f12f6e94c212f14685ac4b74b12bb6fdbffa2d1'
            . '7d87c5392aab792dc252d5de4533cc9518d38aa8dbf1925ab92386edd4009923',
            bin2hex(Blake2b::hash('abc', 64))
        );
    }

    public function testValidAddressRoundTripsThroughItsPublicKey(): void
    {
        $this->assertTrue(Address::isValid(self::BURN));
        $this->assertSame(str_repeat('0', 64), Address::publicKey(self::BURN));
        $this->assertSame(self::BURN, Address::fromPublicKey(str_repeat('0', 64)));
    }

    public function testOneCharacterChecksumErrorIsRejected(): void
    {
        $this->assertFalse(Address::isValid('nano_1111111111111111111111111111111111111111111111111111hifc8npq'));
    }

    public function testRawIsDerivedFromXnoNeverFinerThanOneRaw(): void
    {
        $this->assertSame(self::PRICE_RAW, Amount::xnoToRaw('0.05'));
        $this->assertSame('1' . str_repeat('0', Amount::RAW_DECIMALS), Amount::xnoToRaw('1'));
        $this->assertSame('0.05', Amount::rawToXno(self::PRICE_RAW));

        $this->expectException(InvalidArgumentException::class);
        Amount::xnoToRaw('0.' . str_repeat('0', Amount::RAW_DECIMALS) . '1');
    }

    public function testOrderTotalTimesPriceHasNoFloatRounding(): void
    {
        // 12.50 at 3.2 XNO per unit = 40 XNO; 0.1 + 0.2 style float error cannot appear.
        $this->assertSame('40', Amount::multiplyDecimals('12.50', '3.2'));
        $this->assertSame('0.03', Amount::multiplyDecimals('0.1', '0.3'));
    }

    public function testOptionCCommitmentReproducesThePublishedVector(): void
    {
        $this->assertSame(
            '86e75a339b908cc926a478c1a0e5eb5dd6fc612c1d3251eb661faa78135c36cf',
            Binding::commitmentHex(self::ORDER, self::BURN, self::PRICE_RAW)
        );
        $this->assertSame(self::REP, Binding::representativeAccount(self::ORDER, self::BURN, self::PRICE_RAW));
    }

    public function testQuoteIsDerivedFromTheOrderTotalAndBoundToTheOrder(): void
    {
        $quote = $this->checkout([], new InMemoryClaimStore())->quote(self::ORDER, '1');

        $this->assertSame(self::PRICE_RAW, $quote->amountRaw);
        $this->assertSame('0.05', $quote->amountXno);
        $this->assertSame(self::BURN, $quote->payTo);
        $this->assertSame(self::REP, $quote->representative);
        $this->assertSame('nano:mainnet', Checkout::NETWORK);
        $this->assertSame('exact', Checkout::SCHEME);
    }

    public function testSettleCreditsAConfirmedBoundExactSendOnce(): void
    {
        $claims = new InMemoryClaimStore();
        $checkout = $this->checkout([self::HASH => $this->confirmedSend()], $claims);
        $quote = $checkout->quote(self::ORDER, '1');

        $this->assertTrue($checkout->settle($quote, strtolower(self::HASH)));
        // The same block for the same order again: no second credit.
        $this->assertFalse($checkout->settle($quote, self::HASH));
        $this->assertSame([self::HASH => self::ORDER], $claims->byHash);
    }

    public function testReplayIsRefusedAfterARestart(): void
    {
        // The claim store is the durable table; the checkout is rebuilt as after a restart.
        $claims = new InMemoryClaimStore();
        $blocks = [self::HASH => $this->confirmedSend()];
        $before = $this->checkout($blocks, $claims);
        $this->assertTrue($before->settle($before->quote(self::ORDER, '1'), self::HASH));

        $after = $this->checkout($blocks, $claims);
        $otherOrder = $after->quote('order-0002', '1');

        $this->expectException(NanoException::class);
        // Also refused by the binding (another order has another representative),
        // so present the block as if it carried the second order's commitment.
        $replayed = $this->checkout(
            [self::HASH => $this->confirmedSend(['contents' => ['representative' => $otherOrder->representative]])],
            $claims
        );
        $replayed->settle($otherOrder, self::HASH);
    }

    public function testAnOrderPaidOnceCannotBePaidByASecondBlock(): void
    {
        $claims = new InMemoryClaimStore();
        $checkout = $this->checkout(
            [self::HASH => $this->confirmedSend(), self::OTHER_HASH => $this->confirmedSend()],
            $claims
        );
        $quote = $checkout->quote(self::ORDER, '1');
        $this->assertTrue($checkout->settle($quote, self::HASH));

        $this->expectException(NanoException::class);
        $checkout->settle($quote, self::OTHER_HASH);
    }

    public function testASendBoundToAnotherOrderIsRefused(): void
    {
        $checkout = $this->checkout([self::HASH => $this->confirmedSend()], new InMemoryClaimStore());

        $this->expectException(NanoException::class);
        $checkout->settle($checkout->quote('order-0002', '1'), self::HASH);
    }

    public function testAShortSendIsRefused(): void
    {
        $short = $this->confirmedSend(['amount' => '49999999999999999999999999999']);
        $checkout = $this->checkout([self::HASH => $short], new InMemoryClaimStore());

        $this->expectException(NanoException::class);
        $checkout->settle($checkout->quote(self::ORDER, '1'), self::HASH);
    }

    public function testASendToAnotherAccountIsRefused(): void
    {
        $elsewhere = $this->confirmedSend(['contents' => ['link_as_account' => self::PAYER]]);
        $checkout = $this->checkout([self::HASH => $elsewhere], new InMemoryClaimStore());

        $this->expectException(NanoException::class);
        $checkout->settle($checkout->quote(self::ORDER, '1'), self::HASH);
    }

    public function testAnUnconfirmedSendIsRefused(): void
    {
        $checkout = $this->checkout([self::HASH => $this->confirmedSend(['confirmed' => 'false'])], new InMemoryClaimStore());

        $this->expectException(NanoException::class);
        $checkout->settle($checkout->quote(self::ORDER, '1'), self::HASH);
    }

    public function testAReceiveBlockIsRefused(): void
    {
        $checkout = $this->checkout([self::HASH => $this->confirmedSend(['subtype' => 'receive'])], new InMemoryClaimStore());

        $this->expectException(NanoException::class);
        $checkout->settle($checkout->quote(self::ORDER, '1'), self::HASH);
    }

    public function testASendOlderThanTheReplayWindowIsRefused(): void
    {
        $late = self::NOW + Checkout::DEFAULT_REPLAY_WINDOW_SECONDS + 1;
        $checkout = $this->checkout([self::HASH => $this->confirmedSend()], new InMemoryClaimStore(), $late);

        $this->expectException(NanoException::class);
        $checkout->settle($checkout->quote(self::ORDER, '1'), self::HASH);
    }

    public function testAMalformedBlockHashIsRefusedBeforeAnyNodeRead(): void
    {
        $read = false;
        $checkout = new Checkout(
            payTo: self::BURN,
            xnoPrice: '0.05',
            claims: new InMemoryClaimStore(),
            fetchBlock: static function (string $hash) use (&$read): array {
                $read = true;

                return [];
            },
        );

        try {
            $checkout->settle($checkout->quote(self::ORDER, '1'), 'not-a-hash');
            $this->assertTrue(false, 'a malformed hash was accepted');
        } catch (NanoException $e) {
            $this->assertStringContainsString('64 hex', $e->getMessage());
        }
        $this->assertFalse($read);
    }

    public function testVerifierReportsWhyItRefuses(): void
    {
        $verdict = Verifier::verify($this->confirmedSend(['amount' => '1.5']), self::BURN, self::PRICE_RAW, self::REP, 60, self::NOW);

        $this->assertFalse($verdict['ok']);
        $this->assertStringContainsString('raw integer', $verdict['error']);
    }

    public function testAnInvalidAccountOrPriceIsRefusedAtConfiguration(): void
    {
        $this->assertFalse(Checkout::isValidPrice('0'));
        $this->assertFalse(Checkout::isValidPrice('-1'));
        $this->assertFalse(Checkout::isValidPrice('1e3'));
        $this->assertTrue(Checkout::isValidPrice('0.05'));

        $this->expectException(InvalidArgumentException::class);
        new Checkout(
            payTo: 'nano_1111111111111111111111111111111111111111111111111111hifc8npq',
            xnoPrice: '0.05',
            claims: new InMemoryClaimStore(),
            fetchBlock: static fn (string $hash): array => [],
        );
    }

    public function testANodeUrlMustBeHttpsOrLocal(): void
    {
        $this->assertTrue(NodeClient::isUrlAllowed('https://node.example/proxy'));
        $this->assertTrue(NodeClient::isUrlAllowed('http://127.0.0.1:7076'));
        $this->assertTrue(NodeClient::isUrlAllowed('http://localhost:7076'));
        $this->assertFalse(NodeClient::isUrlAllowed('http://node.example:7076'));
        $this->assertFalse(NodeClient::isUrlAllowed('file:///etc/passwd'));
        $this->assertFalse(NodeClient::isUrlAllowed(''));
    }
}
