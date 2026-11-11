<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'WebServices/Responses/Group/GroupResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/Group/GroupsResponse.php');

class GroupsWebService
{
    /**
     * @var IRestServer
     */
    private $server;

    /**
     * @var IGroupRepository
     */
    private $groupRepository;

    /**
     * @var IGroupViewRepository
     */
    private $groupViewRepository;

    public function __construct(
        IRestServer $server,
        IGroupRepository $groupRepository,
        IGroupViewRepository $groupViewRepository
    ) {
        $this->server = $server;
        $this->groupRepository = $groupRepository;
        $this->groupViewRepository = $groupViewRepository;
    }

    /**
     * @name GetAllGroups
     * @description Loads all groups
     * @response GroupsResponse
     * @return void
     */
    public function GetGroups()
    {
        $pageable = $this->groupViewRepository->GetList(null, null);
        $groups = $pageable->Results();

        $this->server->WriteResponse(new GroupsResponse($this->server, $groups));
    }

    /**
     * @name GetGroup
     * @description Loads a specific group by id
     * @response GroupResponse
     * @param int $groupId
     * @return void
     */
    public function GetGroup(int $groupId)
    {
        $group = $this->groupRepository->LoadById($groupId);

        if ($group != null) {
            $this->server->WriteResponse(new GroupResponse($this->server, $group));
        } else {
            $this->server->WriteResponse(RestResponse::NotFound(), RestResponse::NOT_FOUND_CODE);
        }
    }
}
