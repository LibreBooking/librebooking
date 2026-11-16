<?php

require_once(ROOT_DIR . 'lib/Database/namespace.php');
require_once(ROOT_DIR . 'lib/Database/Commands/namespace.php');

use LibreBooking\Payment\Nano\ClaimStore;
use LibreBooking\Payment\Nano\NanoException;

/**
 * The durable record of Nano blocks already credited, in `nano_payment_claims`.
 * The block hash is the primary key and the order id is unique, so the claim
 * is one atomic INSERT IGNORE: two requests presenting the same block at the
 * same moment cannot both be credited, and a restart forgets nothing.
 */
class NanoPaymentClaimRepository implements ClaimStore
{
    public function claim(string $blockHash, string $orderId): bool
    {
        $inserted = ServiceLocator::GetDatabase()->ExecuteAffectedRows(
            new AddNanoPaymentClaimCommand($blockHash, $orderId, Date::Now())
        );

        if ($inserted === 1) {
            return true;
        }

        $claimedFor = null;
        $reader = ServiceLocator::GetDatabase()->Query(new GetNanoPaymentClaimCommand($blockHash));
        if ($row = $reader->GetRow()) {
            $claimedFor = $row[ColumnNames::NANO_ORDER_ID];
        }
        $reader->Free();

        if ($claimedFor === $orderId) {
            return false;
        }

        if ($claimedFor !== null) {
            throw new NanoException('This Nano block was already used to pay another order.');
        }

        throw new NanoException('This order was already paid by another Nano block.');
    }
}
