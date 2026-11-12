<?php

class ReservationAccessoryView
{
    /**
     * @var int
     */
    public $AccessoryId;

    /**
     * @var int
     */
    public $QuantityReserved;

    /**
     * @var int|null null when the quantity is unlimited or not known
     */
    public $QuantityAvailable;

    /**
     * @var null|string
     */
    public $Name;

    /**
     * @param int $accessoryId
     * @param int $quantityReserved
     * @param string $accessoryName
     * @param int|null $quantityAvailable null when the quantity is unlimited or not known
     */
    public function __construct($accessoryId, $quantityReserved, $accessoryName, $quantityAvailable)
    {
        $this->AccessoryId = $accessoryId;
        $this->QuantityReserved = $quantityReserved;
        $this->Name = $accessoryName;
        $this->QuantityAvailable = $quantityAvailable;
    }
}
