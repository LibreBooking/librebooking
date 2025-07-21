<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');
require_once(ROOT_DIR . 'WebServices/Controllers/AttributeSaveController.php');
require_once(ROOT_DIR . 'WebServices/Responses/CustomAttributes/CustomAttributeCreatedResponse.php');
require_once(ROOT_DIR . 'WebServices/Requests/CustomAttributes/CustomAttributeRequest.php');

class AttributesWriteWebService
{
    /**
     * @var IRestServer
     */
    private $server;

    public function __construct(IRestServer $server, private readonly IAttributeSaveController $attributeController)
    {
        $this->server = $server;
    }

    /**
     * @name CreateCustomAttribute
     * @description Creates a new custom attribute.
     * Allowed values for type: 1 (single line),  2 (multi line), 3 (select list), 4 (checkbox), 5 (datetime)
     * Allowed values for categoryId: 1 (reservation), 2 (user), 4 (resource), 5 (resource type)
     * appliesToIds only allowed for category 2, 4, 5 and must match the id of corresponding entities
     * secondaryCategoryId and secondaryEntityIds only applies to category 1 and must match the id of the corresponding entities
     * @request CustomAttributeRequest
     * @response CustomAttributeCreatedResponse
     * @return void
     */
    public function Create()
    {
        /** @var CustomAttributeRequest $request */
        $request = $this->server->GetRequest();

        Log::Debug('AttributesWriteWebService.Create() User=%s, Request=%s', $this->server->GetSession()->UserId, json_encode($request));

        $result = $this->attributeController->Create($request, $this->server->GetSession());

        if ($result->WasSuccessful()) {
            Log::Debug('AttributesWriteWebService.Create() - Attribute Created. AttributeId=%s', $result->AttributeId());

            $this->server->WriteResponse(new CustomAttributeCreatedResponse($this->server, $result->AttributeId()), RestResponse::CREATED_CODE);
        } else {
            Log::Debug('AttributesWriteWebService.Create() - Create Failed.');

            $this->server->WriteResponse(new FailedResponse($result->Errors()), RestResponse::BAD_REQUEST_CODE);
        }
    }

    /**
     * @name UpdateCustomAttribute
     * @description Updates and existing custom attribute
     * Allowed values for type: 1 (single line),  2 (multi line), 3 (select list), 4 (checkbox), 5 (datetime)
     * Allowed values for categoryId: 1 (reservation), 2 (user), 4 (resource), 5 (resource type)
     * appliesToIds only allowed for category 2, 4, 5 and must match the id of corresponding entities
     * secondaryCategoryId and secondaryEntityIds only applies to category 1 and must match the id of the corresponding entities
     * @request CustomAttributeRequest
     * @response CustomAttributeUpdatedResponse
     * @param $attributeId
     * @return void
     */
    public function Update($attributeId)
    {
        /** @var CustomAttributeRequest $request */
        $request = $this->server->GetRequest();

        Log::Debug('AttributesWriteWebService.Update() User=%s, AttributeId=%s, Request=%s', $this->server->GetSession()->UserId, $attributeId, json_encode($request));

        $result = $this->attributeController->Update($attributeId, $request, $this->server->GetSession());

        if ($result->WasSuccessful()) {
            Log::Debug('AttributesWriteWebService.Update() - Attribute Updated. AttributeId=%s', $result->AttributeId());

            $this->server->WriteResponse(new CustomAttributeUpdatedResponse($this->server, $result->AttributeId()), RestResponse::CREATED_CODE);
        } else {
            Log::Debug('AttributesWriteWebService.Update() - Update Failed.');

            $this->server->WriteResponse(new FailedResponse($result->Errors()), RestResponse::BAD_REQUEST_CODE);
        }
    }

    /**
     * @name DeleteCustomAttribute
     * @description Deletes an existing custom attribute
     * @response DeletedResponse
     * @param int $attributeId
     * @return void
     */
    public function Delete($attributeId)
    {
        Log::Debug('AttributesWriteWebService.Delete() AttributeId=%s, UserId=%s', $attributeId, $this->server->GetSession()->UserId);

        $result = $this->attributeController->Delete($attributeId, $this->server->GetSession());

        if ($result->WasSuccessful()) {
            Log::Debug('AttributesWriteWebService.Delete() - Attribute Deleted. AttributeId=%s', $result->AttributeId());

            $this->server->WriteResponse(new DeletedResponse(), RestResponse::OK_CODE);
        } else {
            Log::Debug('AttributesWriteWebService.Delete() - Attribute Delete Failed.');

            $this->server->WriteResponse(new FailedResponse($result->Errors()), RestResponse::BAD_REQUEST_CODE);
        }
    }
}
