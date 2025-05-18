<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class ReservationRetryParameterRequestResponse
{
    public function __construct(public $name, public $value)
    {
    }

    public static function Example()
    {
        return new ReservationRetryParameterRequestResponse('name', 'value');
    }
}
