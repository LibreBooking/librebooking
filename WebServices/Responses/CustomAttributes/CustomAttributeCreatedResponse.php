<?php

class CustomAttributeCreatedResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $attributeId)
    {
        $this->message = 'The attribute was created';
        $this->AddService($server, WebServices::GetCustomAttribute, [WebServiceParams::AttributeId => $this->attributeId]);
        $this->AddService($server, WebServices::UpdateCustomAttribute, [WebServiceParams::AttributeId => $this->attributeId]);
        $this->AddService($server, WebServices::DeleteCustomAttribute, [WebServiceParams::AttributeId => $this->attributeId]);
    }

    public static function Example()
    {
        return new ExampleCustomAttributeCreatedResponse();
    }
}

class CustomAttributeUpdatedResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $attributeId)
    {
        $this->message = 'The attribute was updated';
        $this->AddService($server, WebServices::GetCustomAttribute, [WebServiceParams::AttributeId => $this->attributeId]);
        $this->AddService($server, WebServices::UpdateCustomAttribute, [WebServiceParams::AttributeId => $this->attributeId]);
    }

    public static function Example()
    {
        return new ExampleCustomAttributeCreatedResponse();
    }
}

class ExampleCustomAttributeCreatedResponse extends CustomAttributeCreatedResponse
{
    public function __construct()
    {
        $this->attributeId = 1;
        $this->AddLink('http://url/to/attribute', WebServices::GetCustomAttribute);
        $this->AddLink('http://url/to/update/attribute', WebServices::UpdateCustomAttribute);
    }
}
