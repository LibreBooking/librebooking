<?php

class ReservationCreatedResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $referenceNumber, public $isPendingApproval)
    {
        $this->message = 'The reservation was created';
        $this->AddService($server, WebServices::GetReservation, [WebServiceParams::ReferenceNumber => $this->referenceNumber]);
        $this->AddService($server, WebServices::UpdateReservation, [WebServiceParams::ReferenceNumber => $this->referenceNumber]);
    }

    public static function Example()
    {
        return new ExampleReservationCreatedResponse();
    }
}

class ReservationUpdatedResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $referenceNumber, public $isPendingApproval)
    {
        $this->message = 'The reservation was updated';
        $this->AddService($server, WebServices::GetReservation, [WebServiceParams::ReferenceNumber => $this->referenceNumber]);
    }

    public static function Example()
    {
        return new ExampleReservationCreatedResponse();
    }
}

class ReservationApprovedResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $referenceNumber)
    {
        $this->message = 'The reservation was approved';
        $this->AddService($server, WebServices::GetReservation, [WebServiceParams::ReferenceNumber => $this->referenceNumber]);
    }

    public static function Example()
    {
        return new ExampleReservationCreatedResponse();
    }
}

class ReservationCheckedInResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $referenceNumber)
    {
        $this->message = 'The reservation was checked in';
        $this->AddService($server, WebServices::GetReservation, [WebServiceParams::ReferenceNumber => $this->referenceNumber]);
    }

    public static function Example()
    {
        return new ExampleReservationCreatedResponse();
    }
}

class ReservationCheckedOutResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $referenceNumber)
    {
        $this->message = 'The reservation was checked out';
        $this->AddService($server, WebServices::GetReservation, [WebServiceParams::ReferenceNumber => $this->referenceNumber]);
    }

    public static function Example()
    {
        return new ExampleReservationCreatedResponse();
    }
}

class ExampleReservationCreatedResponse extends ReservationCreatedResponse
{
    public function __construct()
    {
        $this->referenceNumber = 'referenceNumber';
        $this->isPendingApproval = true;
        $this->AddLink('http://url/to/reservation', WebServices::GetReservation);
        $this->AddLink('http://url/to/update/reservation', WebServices::UpdateReservation);
    }
}
