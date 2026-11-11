<?php

class Accessory
{
    /**
     * @var int|null null until the accessory is saved
     */
    private $id;


    /**
     * @var string
     */
    private $name;

    /**
     * @var int|null null means unlimited
     */
    private $quantityAvailable;

    /**
     * @var ResourceAccessory[]
     */
    private $resources = [];

    /**
     * @param int|null $id null until the accessory is saved
     * @param string $name
     * @param int|string|null $quantityAvailable empty means unlimited
     */
    public function __construct($id, $name, $quantityAvailable)
    {
        $this->id = $id;
        $this->SetName($name);
        $this->SetQuantityAvailable($quantityAvailable);
    }

    /**
     * @return int|null
     */
    public function GetId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function GetName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function SetName($name)
    {
        $this->name = $name;
    }

    /**
     * @param int|string|null $quantity empty means unlimited
     */
    public function SetQuantityAvailable($quantity)
    {
        $q = intval($quantity);
        $this->quantityAvailable = empty($q) ? null : $q;
    }

    /**
     * @return int|null null means unlimited
     */
    public function GetQuantityAvailable()
    {
        return $this->quantityAvailable;
    }

    /**
     * @return ResourceAccessory[]
     */
    public function Resources()
    {
        return $this->resources;
    }

    /**
     * @return int[]
     */
    public function ResourceIds()
    {
        $ids = [];
        foreach ($this->resources as $resource) {
            $ids[] = $resource->ResourceId;
        }

        return $ids;
    }

    /**
     * @static
     * @param string $name
     * @param int|string|null $quantity empty means unlimited
     * @return Accessory
     */
    public static function Create($name, $quantity)
    {
        return new Accessory(null, $name, $quantity);
    }

    /**
     * @return bool
     */
    public function HasUnlimitedQuantity()
    {
        return empty($this->quantityAvailable);
    }

    public function AddResource($resourceId, $minQuantity, $maxQuantity)
    {
        $this->resources[] = new ResourceAccessory($resourceId, $minQuantity, $maxQuantity);
    }

    /**
     * @param ResourceAccessory[] $resources
     */
    public function ChangeResources($resources)
    {
        $this->resources = $resources;
    }

    /**
     * @return bool
     */
    public function IsTiedToResource()
    {
        return count($this->resources) > 0;
    }

    /**
     * @param int $resourceId
     */
    public function GetResource($resourceId): ?ResourceAccessory
    {
        foreach ($this->resources as $resource) {
            if ($resource->ResourceId == $resourceId) {
                return $resource;
            }
        }

        return null;
    }
}

class ResourceAccessory
{
    public $ResourceId;
    public $MinQuantity;
    public $MaxQuantity;

    public function __construct($resourceId, $minQuantity, $maxQuantity)
    {
        $this->ResourceId = $resourceId;
        $this->MinQuantity = empty($minQuantity) ? null : (int)$minQuantity;
        $this->MaxQuantity = empty($maxQuantity) ? null : (int)$maxQuantity;
        ;
    }
}
