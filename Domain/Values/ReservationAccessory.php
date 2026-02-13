<?php

class ReservationAccessory
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
     * @var null|string
     */
    public $Name;

    /**
     * @param int $accessoryId
     * @param int $quantityReserved
     * @param string $accessoryName
     */
    public function __construct($accessoryId, $quantityReserved, $accessoryName = null)
    {
        $this->AccessoryId = $accessoryId;
        $this->QuantityReserved = $quantityReserved;
        $this->Name = $accessoryName;
    }

    public function __toString()
    {
        return sprintf('ReservationAccessory id:%d quantity reserved:%d', $this->AccessoryId, $this->QuantityReserved);
    }
}
