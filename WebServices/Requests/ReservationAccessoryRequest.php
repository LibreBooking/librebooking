<?php

class ReservationAccessoryRequest
{
    public function __construct(public $accessoryId, public $quantityRequested)
    {
    }
}
