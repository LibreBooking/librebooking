<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class ReservationAccessoryResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $id, public $name, public $quantityReserved, public $quantityAvailable)
    {
        $this->AddService($server, WebServices::GetAccessory, [WebServiceParams::AccessoryId => $this->id]);
    }

    public static function Example()
    {
        return new ExampleReservationAccessoryResponse();
    }
}

class ExampleReservationAccessoryResponse extends ReservationAccessoryResponse
{
    public function __construct()
    {
        $this->id = 1;
        $this->name = 'Example';
        $this->quantityAvailable = 12;
        $this->quantityReserved = 3;
    }
}
