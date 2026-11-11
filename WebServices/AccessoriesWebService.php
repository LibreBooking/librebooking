<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'WebServices/Responses/AccessoriesResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/AccessoryResponse.php');

class AccessoriesWebService
{
    /**
     * @var IRestServer
     */
    private $server;

    /**
     * @var IResourceRepository
     */
    private $resourceRepository;

    /**
     * @var IAccessoryRepository
     */
    private $accessoryRepository;

    public function __construct(
        IRestServer $server,
        IResourceRepository $resourceRepository,
        IAccessoryRepository $accessoryRepository
    ) {
        $this->server = $server;
        $this->resourceRepository = $resourceRepository;
        $this->accessoryRepository = $accessoryRepository;
    }

    /**
     * @name GetAllAccessories
     * @description Loads all accessories
     * @response AccessoriesResponse
     * @return void
     */
    public function GetAll()
    {
        $accessories = $this->resourceRepository->GetAccessoryList();
        $this->server->WriteResponse(new AccessoriesResponse($this->server, $accessories));
    }

    /**
     * @name GetAccessory
     * @description Loads a specific accessory by id
     * @param int $accessoryId
     * @response AccessoryResponse
     * @return void
     */
    public function GetAccessory(int $accessoryId)
    {
        $accessory = $this->accessoryRepository->LoadById($accessoryId);

        if (empty($accessory)) {
            $this->server->WriteResponse(RestResponse::NotFound(), RestResponse::NOT_FOUND_CODE);
        } else {
            $this->server->WriteResponse(new AccessoryResponse($this->server, $accessory));
        }
    }
}
