<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class ResourceCreatedResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $resourceId)
    {
        $this->AddService($server, WebServices::GetResource, [WebServiceParams::ResourceId => $this->resourceId]);
        $this->AddService($server, WebServices::UpdateResource, [WebServiceParams::ResourceId => $this->resourceId]);
    }

    public static function Example()
    {
        return new ExampleResourceCreatedResponse();
    }
}

class ExampleResourceCreatedResponse extends ResourceCreatedResponse
{
    public function __construct()
    {
        $this->resourceId = 1;
        $this->AddLink('http://url/to/resource', WebServices::GetResource);
        $this->AddLink('http://url/to/update/resource', WebServices::UpdateResource);
    }
}
