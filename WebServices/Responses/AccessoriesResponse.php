<?php

class AccessoriesResponse extends RestResponse
{
    /**
     * @var AccessoryItemResponse
     */
    public $accessories;

    /**
     * @param IRestServer $server
     * @param AccessoryDto[] $accessories
     */
    public function __construct(IRestServer $server, $accessories)
    {
        /** @var AccessoryDto $accessory */
        foreach ($accessories as $accessory) {
            $this->accessories[] = new AccessoryItemResponse($server, $accessory);
        }
    }

    public static function Example()
    {
        return new ExampleAccessoriesResponse();
    }
}

class ExampleAccessoriesResponse extends AccessoriesResponse
{
    public function __construct()
    {
        $this->accessories = [AccessoryItemResponse::Example()];
    }
}

class AccessoryItemResponse extends RestResponse
{
    public $id;
    public $name;
    public $quantityAvailable;
    public $associatedResourceCount;

    public function __construct(IRestServer $server, AccessoryDto $accessory)
    {
        $this->id = $accessory->Id;
        $this->name = $accessory->Name;
        $this->quantityAvailable = $accessory->QuantityAvailable;
        $this->associatedResourceCount = $accessory->AssociatedResources;
        $this->AddService($server, WebServices::GetAccessory, [WebServiceParams::AccessoryId => $this->id]);
    }

    public static function Example()
    {
        return new ExampleAccessoryItemResponse();
    }
}

class ExampleAccessoryItemResponse extends AccessoryItemResponse
{
    public function __construct()
    {
        $this->id = 1;
        $this->name = 'accessoryName';
        $this->quantityAvailable = 3;
        $this->associatedResourceCount = 10;
    }
}
