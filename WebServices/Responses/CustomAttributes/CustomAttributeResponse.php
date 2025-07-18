<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class CustomAttributeResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $id, public $label, public $value)
    {
        $this->AddService($server, WebServices::GetCustomAttribute, [WebServiceParams::AttributeId => $this->id]);
    }

    public static function Example()
    {
        return new ExampleCustomAttributeResponse();
    }
}

class ExampleCustomAttributeResponse extends CustomAttributeResponse
{
    public function __construct()
    {
        $this->id = 123;
        $this->label = 'label';
        $this->value = 'value';
    }
}
