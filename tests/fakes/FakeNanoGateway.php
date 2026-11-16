<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/PaymentGateway.php');

use LibreBooking\Payment\Nano\ClaimStore;
use LibreBooking\Payment\Nano\NanoException;

/**
 * A Nano gateway whose node read is a fixed map of block hash => block_info
 * reply, so the checkout is tested without the network.
 */
class FakeNanoGateway extends NanoGateway
{
    // The all-zero-key account: valid checksum, nobody holds its key.
    public const ACCOUNT_BURN = 'nano_1111111111111111111111111111111111111111111111111111hifc8npp';

    /**
     * @var array<string, array<string, mixed>>
     */
    public $_Blocks = [];

    public function __construct()
    {
        parent::__construct(true, self::ACCOUNT_BURN, 'https://node.invalid', '0.05');
    }

    public function Settle(CreditCartSession $cart, $blockHash, ClaimStore $claims, IPaymentTransactionLogger $logger, ?callable $fetchBlock = null)
    {
        $blocks = $this->_Blocks;
        return parent::Settle($cart, $blockHash, $claims, $logger, static function (string $hash) use ($blocks): array {
            return $blocks[$hash] ?? ['error' => 'Block not found'];
        });
    }
}

/**
 * The claim store contract (block hash is the key, an order is paid once)
 * held in memory.
 */
class FakeNanoClaimStore implements ClaimStore
{
    /**
     * @var array<string, string>
     */
    public $_Claims = [];

    public function claim(string $blockHash, string $orderId): bool
    {
        if (isset($this->_Claims[$blockHash])) {
            if ($this->_Claims[$blockHash] === $orderId) {
                return false;
            }
            throw new NanoException('This Nano block was already used to pay another order.');
        }
        if (in_array($orderId, $this->_Claims, true)) {
            throw new NanoException('This order was already paid by another Nano block.');
        }
        $this->_Claims[$blockHash] = $orderId;

        return true;
    }
}
