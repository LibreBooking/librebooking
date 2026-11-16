<?php

declare(strict_types=1);

namespace LibreBooking\Payment\Nano;

/**
 * What one order asks the payer to send: the exact amount in raw (and in XNO
 * for display), the account to pay and the representative the send block must
 * carry so that the block belongs to this order and no other.
 */
final class Quote
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $payTo,
        public readonly string $amountRaw,
        public readonly string $amountXno,
        public readonly string $representative,
    ) {
    }
}
