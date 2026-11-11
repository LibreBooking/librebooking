<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class ResourceUpdatedResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $resourceId)
    {
        $this->AddService(
            server: $server,
            serviceName: WebServices::GetResource,
            params: [WebServiceParams::ResourceId => $this->resourceId]
        );
        $this->AddService(
            server: $server,
            serviceName: WebServices::UpdateResource,
            params: [WebServiceParams::ResourceId => $this->resourceId]
        );
    }

    public static function Example()
    {
        return new ExampleResourceCreatedResponse();
    }
}

class ExampleResourceUpdatedResponse extends ResourceUpdatedResponse
{
    public function __construct()
    {
        $this->resourceId = 1;
        $this->AddLink('http://url/to/resource', WebServices::GetResource);
        $this->AddLink('http://url/to/update/resource', WebServices::UpdateResource);
    }
}
