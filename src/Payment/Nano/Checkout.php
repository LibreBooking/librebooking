<?php

declare(strict_types=1);

namespace LibreBooking\Payment\Nano;

use InvalidArgumentException;

/**
 * Quotes and settles one order paid in Nano (XNO), scheme "exact" on
 * nano:mainnet.
 *
 * The quote derives the raw amount owed from the order total and the
 * operator's price (XNO per one unit of the order currency) with integer
 * string math, and the representative account the payer sets on the send
 * block (binding option C of the xno-binding v1 standard), so a block is bound
 * to one order at one price. Settling reads the block the payer names, accepts
 * only a confirmed send of exactly that amount to the configured account
 * carrying that representative, and records it in a durable claim store so a
 * block credits one order once, also after a restart.
 *
 * The node read, the claim store and the clock are injected, so every decision
 * here is unit tested without a database or the network.
 */
final class Checkout
{
    public const NETWORK = 'nano:mainnet';
    public const SCHEME = 'exact';
    public const DEFAULT_REPLAY_WINDOW_SECONDS = 86400;

    /** The order total has at most a few decimals; this keeps the product within one raw. */
    private const MAX_PRICE_DECIMALS = 24;

    /** @var callable(string): array<string, mixed> */
    private $fetchBlock;

    /** @var callable(): int */
    private $clock;

    /**
     * @param string                                  $payTo      the operator's checksum-valid nano_ account
     * @param string                                  $xnoPrice   XNO per one unit of the order currency
     * @param callable(string): array<string, mixed>  $fetchBlock given a 64-hex block hash, the node's block_info reply
     * @param (callable(): int)|null                  $clock      current unix time (injected in tests)
     */
    public function __construct(
        private readonly string $payTo,
        private readonly string $xnoPrice,
        private readonly ClaimStore $claims,
        callable $fetchBlock,
        private readonly int $replayWindowSeconds = self::DEFAULT_REPLAY_WINDOW_SECONDS,
        ?callable $clock = null,
    ) {
        if (!Address::isValid($payTo)) {
            throw new InvalidArgumentException('The Nano account is not a valid nano_ address.');
        }
        if (!self::isValidPrice($xnoPrice)) {
            throw new InvalidArgumentException('The Nano price (XNO per unit of the order currency) must be a positive decimal.');
        }
        if ($replayWindowSeconds <= 0) {
            throw new InvalidArgumentException('The Nano replay window must be positive.');
        }

        $this->fetchBlock = $fetchBlock;
        $this->clock = $clock ?? static fn (): int => time();
    }

    public static function isValidPrice(string $xnoPrice): bool
    {
        $xnoPrice = trim($xnoPrice);
        if (preg_match('/^\d+(?:\.(\d+))?$/', $xnoPrice, $m) !== 1) {
            return false;
        }
        if (strlen($m[1] ?? '') > self::MAX_PRICE_DECIMALS) {
            return false;
        }

        return Amount::xnoToRaw($xnoPrice) !== '0';
    }

    /**
     * @param string $orderId    the application's id for this order (one quote per order)
     * @param string $orderTotal the order total in its currency, as a decimal string
     */
    public function quote(string $orderId, string $orderTotal): Quote
    {
        if ($orderId === '' || preg_match('/[\x00-\x1F\x7F|]/', $orderId) === 1) {
            throw new InvalidArgumentException('A Nano order id must be non-empty, without control characters or "|".');
        }

        $amountRaw = Amount::xnoToRaw(Amount::multiplyDecimals($orderTotal, trim($this->xnoPrice)));
        if ($amountRaw === '0') {
            throw new InvalidArgumentException('A Nano order must cost more than zero.');
        }

        return new Quote(
            orderId: $orderId,
            payTo: $this->payTo,
            amountRaw: $amountRaw,
            amountXno: Amount::rawToXno($amountRaw),
            representative: Binding::representativeAccount($orderId, $this->payTo, $amountRaw),
        );
    }

    /**
     * Settle $quote with the send block the payer names.
     *
     * @return bool true when the order is credited now; false when this same
     *              block already credited this same order (credit nothing again)
     *
     * @throws NanoException when the block does not pay this order
     */
    public function settle(Quote $quote, string $blockHash): bool
    {
        $hash = strtoupper(trim($blockHash));
        if (preg_match('/^[0-9A-F]{64}$/', $hash) !== 1) {
            throw new NanoException('A Nano block hash is 64 hex characters.');
        }

        $blockInfo = ($this->fetchBlock)($hash);

        $verdict = Verifier::verify(
            $blockInfo,
            $quote->payTo,
            $quote->amountRaw,
            $quote->representative,
            $this->replayWindowSeconds,
            ($this->clock)(),
        );

        if (!$verdict['ok']) {
            throw new NanoException($verdict['error']);
        }

        return $this->claims->claim($hash, $quote->orderId);
    }
}
