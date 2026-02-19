<?php

declare(strict_types=1);

class FakeAccessoryRepository implements IAccessoryRepository
{
    public $_AllAccessories = [];

    public function AddAccessory(Accessory $accessory)
    {
        $this->_AllAccessories[] = $accessory;
        return $this;
    }

    /**
     * @param int $accessoryId
     * @return Accessory
     */
    public function LoadById($accessoryId)
    {
        throw new LogicException('LoadById() not implemented in FakeAccessoryRepository');
    }

    /**
     * @return Accessory[]
     */
    public function LoadAll()
    {
        return $this->_AllAccessories;
    }

    /**
     * @param Accessory $accessory
     * @return int
     */
    public function Add(Accessory $accessory)
    {
        $this->_AllAccessories[] = $accessory;
        return count($this->_AllAccessories);
    }

    /**
     * @param Accessory $accessory
     * @return void
     */
    public function Update(Accessory $accessory)
    {
        // TODO: Implement Update() method.
    }

    /**
     * @param int $accessoryId
     * @return void
     */
    public function Delete($accessoryId)
    {
        // TODO: Implement Delete() method.
    }
}
