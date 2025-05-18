<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class ReservationUserResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $userId, public $firstName, public $lastName, public $emailAddress)
    {
        $this->AddService($server, WebServices::GetUser, [WebServiceParams::UserId => $this->userId]);
    }

    public static function Masked()
    {
        return new MaskedReservationUserResponse();
    }

    public static function Example()
    {
        return new ExampleReservationUserResponse();
    }
}

class MaskedReservationUserResponse extends ReservationUserResponse
{
    public function __construct()
    {
        $this->userId = null;
        $this->firstName = 'Private';
        $this->lastName = 'Private';
        $this->emailAddress = 'Private';
    }
}

class ExampleReservationUserResponse extends ReservationUserResponse
{
    public function __construct()
    {
        $this->userId = 123;
        $this->firstName = 'first';
        $this->lastName = 'last';
        $this->emailAddress = 'email@address.com';
    }
}
