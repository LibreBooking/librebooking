<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');
require_once(ROOT_DIR . 'WebServices/Responses/CustomAttributes/CustomAttributesResponse.php');

class AttributesWebService
{
    /**
     * @var IRestServer
     */
    private $server;

    /**
     * @var IAttributeService
     */
    private $attributeService;

    public function __construct(IRestServer $server, IAttributeService $attributeService)
    {
        $this->server = $server;
        $this->attributeService = $attributeService;
    }

    /**
     * @name GetCategoryAttributes
     * @description Gets all custom attribute definitions for the requested category
     * Categories are RESERVATION = 1, USER = 2, RESOURCE = 4
     * @response CustomAttributesResponse
     * @return void
     * @param int $categoryId
     */
    public function GetAttributes(int $categoryId)
    {
        $attributes = $this->attributeService->GetByCategory($categoryId);

        $this->server->WriteResponse(new CustomAttributesResponse($this->server, $attributes));
    }

    /**
     * @name GetAttribute
     * @description Gets all custom attribute definitions for the requested attribute
     * @response CustomAttributeDefinitionResponse
     * @return void
     * @param int $attributeId
     */
    public function GetAttribute(int $attributeId)
    {
        $attribute = $this->attributeService->GetById($attributeId);

        if ($attribute != null) {
            $this->server->WriteResponse(new CustomAttributeDefinitionResponse($this->server, $attribute));
        } else {
            $this->server->WriteResponse(RestResponse::NotFound(), RestResponse::NOT_FOUND_CODE);
        }
    }
}
