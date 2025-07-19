<?php

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
        // TODO: Implement LoadById() method.
        return null;
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
        // TODO: Implement Add() method.
        return null;
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
