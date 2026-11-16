<?php

declare(strict_types=1);

namespace LibreBooking\Payment\Nano;

/**
 * Where settled Nano payments are remembered, so one send block credits one
 * order once.
 *
 * A confirmed Nano send stays valid forever and its hash is public, so this
 * record must be durable: a store that forgot its claims on a restart would
 * let every old block be presented again. The application implements it on
 * its database with the block hash as the primary key and the order id unique,
 * so the check and the write are one atomic statement.
 */
interface ClaimStore
{
    /**
     * Record that $blockHash (64 upper-case hex characters) settles $orderId.
     *
     * @return bool true when this is the first time the block is credited;
     *              false when the same block was already recorded for the same
     *              order (idempotent: a retried request is not a new credit)
     *
     * @throws NanoException when the block was already used for another order,
     *                       or the order was already paid by another block
     */
    public function claim(string $blockHash, string $orderId): bool;
}
