<?php

declare(strict_types=1);

namespace LibreBooking\Payment\Nano;

use RuntimeException;

/**
 * Raised when a Nano payment cannot be credited: a block already spent on
 * another order, an order already paid by another block, or a malformed claim.
 * A caller catches this to show the payer why the block was refused, while the
 * order stays unpaid.
 */
final class NanoException extends RuntimeException
{
}
