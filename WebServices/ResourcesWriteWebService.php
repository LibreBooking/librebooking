<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'WebServices/Controllers/ResourceSaveController.php');
require_once(ROOT_DIR . 'WebServices/Requests/Resource/ResourceRequest.php');
require_once(ROOT_DIR . 'WebServices/Responses/Resource/ResourceCreatedResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/Resource/ResourceUpdatedResponse.php');

class ResourcesWriteWebService
{
    /**
     * @var IRestServer
     */
    private $server;

    public function __construct(IRestServer $server, private readonly IResourceSaveController $controller)
    {
        $this->server = $server;
    }

    /**
     * @name CreateResource
     * @description Creates a new resource
     * @request ResourceRequest
     * @response ResourceCreatedResponse
     * @return void
     */
    public function Create()
    {
        /** @var ResourceRequest $request */
        $request = new ResourceRequest($this->server->GetRequest());

        Log::Debug(
            'ResourcesWriteWebService.Create() User=%s, Request=%s',
            $this->server->GetSession()->UserId,
            json_encode($request)
        );
        $result = $this->controller->Create($request, $this->server->GetSession());

        if ($result->WasSuccessful()) {
            Log::Debug('ResourcesWriteWebService.Create() - Resource created with id %s', $result->ResourceId());

            $this->server->WriteResponse(
                new ResourceCreatedResponse($this->server, $result->ResourceId()),
                RestResponse::CREATED_CODE
            );
        } else {
            Log::Debug('ResourcesWriteWebService.Create() - Resource create failed');

            $this->server->WriteResponse(
                new FailedResponse($result->Errors()),
                RestResponse::BAD_REQUEST_CODE
            );
        }
    }


    /**
     * @name UpdateResource
     * @description Updates an existing resource
     * @request ResourceRequest
     * @response ResourceUpdatedResponse
     * @param $resourceId
     * @return void
     */
    public function Update($resourceId)
    {
        /** @var ResourceRequest $request */
        $request = new ResourceRequest($this->server->GetRequest());

        Log::Debug(
            'ResourcesWriteWebService.Update() User=%s, Request=%s',
            $this->server->GetSession()->UserId,
            json_encode($request)
        );

        $result = $this->controller->Update($resourceId, $request, $this->server->GetSession());

        if ($result->WasSuccessful()) {
            Log::Debug(
                'ResourcesWriteWebService.Update() - Resource Updated. ResourceId=%s',
                $result->ResourceId()
            );

            $this->server->WriteResponse(
                new ResourceUpdatedResponse($this->server, $result->ResourceId()),
                RestResponse::OK_CODE
            );
        } else {
            Log::Debug('ResourcesWriteWebService.Update() - Resource Update Failed.');

            $this->server->WriteResponse(
                new FailedResponse($result->Errors()),
                RestResponse::BAD_REQUEST_CODE
            );
        }
    }

    /**
     * @name DeleteResource
     * @description Deletes an existing resource
     * @response DeletedResponse
     * @param int $resourceId
     * @return void
     */
    public function Delete($resourceId)
    {
        Log::Debug('ResourcesWriteWebService.Delete() Resource=%s', $this->server->GetSession()->UserId);

        $result = $this->controller->Delete($resourceId, $this->server->GetSession());

        if ($result->WasSuccessful()) {
            Log::Debug(
                'ResourcesWriteWebService.Delete() - Resource Deleted. ResourceId=%s',
                $result->ResourceId()
            );

            $this->server->WriteResponse(new DeletedResponse(), RestResponse::OK_CODE);
        } else {
            Log::Debug('ResourcesWriteWebService.Delete() - Resource Delete Failed.');

            $this->server->WriteResponse(
                new FailedResponse($result->Errors()),
                RestResponse::BAD_REQUEST_CODE
            );
        }
    }
}
