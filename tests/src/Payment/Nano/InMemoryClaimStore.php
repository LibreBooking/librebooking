<?php

declare(strict_types=1);

namespace LibreBooking\Tests\Payment\Nano;

use LibreBooking\Payment\Nano\ClaimStore;
use LibreBooking\Payment\Nano\NanoException;

/**
 * A claim store with the same contract as the database one: the block hash is
 * the key and an order is paid by one block only. Shared between two Checkout
 * instances it stands for the table that outlives a restart.
 */
final class InMemoryClaimStore implements ClaimStore
{
    /** @var array<string, string> block hash => order id */
    public array $byHash = [];

    public function claim(string $blockHash, string $orderId): bool
    {
        if (isset($this->byHash[$blockHash])) {
            if ($this->byHash[$blockHash] === $orderId) {
                return false;
            }
            throw new NanoException('This Nano block was already used to pay another order.');
        }
        if (in_array($orderId, $this->byHash, true)) {
            throw new NanoException('This order was already paid by another Nano block.');
        }
        $this->byHash[$blockHash] = $orderId;

        return true;
    }
}
