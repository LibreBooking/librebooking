<?php

class AccessoryResponse extends RestResponse
{
    public $id;
    public $name;
    public $quantityAvailable;
    public $associatedResources = [];

    public function __construct(IRestServer $server, Accessory $accessory)
    {
        $this->id = $accessory->GetId();
        $this->name = $accessory->GetName();
        $this->quantityAvailable = $accessory->GetQuantityAvailable();
        $this->associatedResources = $this->GetResources($server, $accessory->Resources());
    }

    public static function Example()
    {
        return new ExampleAccessoryResponse();
    }

    /**
     * @param IRestServer $server
     * @param ResourceAccessory[] $resources
     * @return AssociatedResourceResponse[]
     */
    private function GetResources(IRestServer $server, $resources)
    {
        $items = [];
        foreach ($resources as $r) {
            $items[] = new AssociatedResourceResponse($server, $r);
        }

        return $items;
    }
}

class AssociatedResourceResponse extends RestResponse
{
    public $resourceId;
    public $minQuantity;
    public $maxQuantity;

    public function __construct(IRestServer $server, ResourceAccessory $resourceAccessory)
    {
        $this->resourceId = $resourceAccessory->ResourceId;
        $this->minQuantity = $resourceAccessory->MinQuantity;
        $this->maxQuantity = $resourceAccessory->MaxQuantity;
        $this->AddService($server, WebServices::GetResource, [WebServiceParams::ResourceId => $resourceAccessory->ResourceId]);
    }

    public static function Example()
    {
        return new ExampleAssociatedResourceResponse();
    }
}

class ExampleAccessoryResponse extends AccessoryResponse
{
    public function __construct()
    {
        $this->id = 1;
        $this->name = 'accessoryName';
        $this->quantityAvailable = 10;
        $this->associatedResources = [AssociatedResourceResponse::Example()];
    }
}

class ExampleAssociatedResourceResponse extends AssociatedResourceResponse
{
    public function __construct()
    {
        $this->resourceId = 1;
        $this->maxQuantity = 10;
        $this->minQuantity = 4;
    }
}
