<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'WebServices/Controllers/GroupSaveController.php');
require_once(ROOT_DIR . 'WebServices/Responses/Group/GroupCreatedResponse.php');
require_once(ROOT_DIR . 'WebServices/Requests/Group/GroupRequest.php');

class GroupsWriteWebService
{
    /**
     * @var IRestServer
     */
    private $server;

    public function __construct(IRestServer $server, private readonly IGroupSaveController $controller)
    {
        $this->server = $server;
    }

    /**
     * @name CreateGroup
     * @description Creates a new group
     * @request GroupRequest
     * @response GroupCreatedResponse
     * @return void
     */
    public function Create()
    {
        /** @var GroupRequest $request */
        $request = $this->server->GetRequest();

        Log::Debug('GroupsWriteWebService.Create() User=%s, Request=%s', $this->server->GetSession()->UserId, json_encode($request));

        $result = $this->controller->Create($request, $this->server->GetSession());

        if ($result->WasSuccessful()) {
            Log::Debug('GroupsWriteWebService.Create() - Group Created. GroupId=%s', $result->GroupId());

            $this->server->WriteResponse(new GroupCreatedResponse($this->server, $result->GroupId()), RestResponse::CREATED_CODE);
        } else {
            Log::Debug('GroupsWriteWebService.Create() - Create Failed.');

            $this->server->WriteResponse(new FailedResponse($result->Errors()), RestResponse::BAD_REQUEST_CODE);
        }
    }

    /**
     * @name UpdateGroup
     * @description Updates and existing group
     * @request GroupRequest
     * @response GroupUpdatedResponse
     * @param $groupId
     * @return void
     */
    public function Update($groupId)
    {
        /** @var GroupRequest $request */
        $request = $this->server->GetRequest();

        Log::Debug('GroupsWriteWebService.Update() User=%s, GroupId=%s, Request=%s', $this->server->GetSession()->UserId, $groupId, json_encode($request));

        $result = $this->controller->Update($groupId, $request, $this->server->GetSession());

        if ($result->WasSuccessful()) {
            Log::Debug('GroupsWriteWebService.Update() - Group Updated. GroupId=%s', $result->GroupId());

            $this->server->WriteResponse(new GroupUpdatedResponse($this->server, $result->GroupId()), RestResponse::CREATED_CODE);
        } else {
            Log::Debug('GroupsWriteWebService.Update() - Update Failed.');

            $this->server->WriteResponse(new FailedResponse($result->Errors()), RestResponse::BAD_REQUEST_CODE);
        }
    }

    /**
     * @name DeleteGroup
     * @description Deletes an existing group
     * @response DeletedResponse
     * @param int $groupId
     * @return void
     */
    public function Delete($groupId)
    {
        Log::Debug('GroupsWriteWebService.Delete() GroupId=%s, UserId=%s', $groupId, $this->server->GetSession()->UserId);

        $result = $this->controller->Delete($groupId, $this->server->GetSession());

        if ($result->WasSuccessful()) {
            Log::Debug('GroupsWriteWebService.Delete() - Group Deleted. GroupId=%s', $result->GroupId());

            $this->server->WriteResponse(new DeletedResponse(), RestResponse::OK_CODE);
        } else {
            Log::Debug('GroupsWriteWebService.Delete() - Group Delete Failed.');

            $this->server->WriteResponse(new FailedResponse($result->Errors()), RestResponse::BAD_REQUEST_CODE);
        }
    }

    /**
     * @name ChangeGroupRoles
     * @description Updates the roles for an existing group
     * roleIds : 1 (Group Administrator), 2 (Application Administrator), 3 (Resource Administrator), 4 (Schedule Administrator)
     * @response GroupUpdatedResponse
     * @param int $groupId
     * @return void
     */
    public function Roles($groupId)
    {
        /** @var GroupRolesRequest $request */
        $request = $this->server->GetRequest();

        Log::Debug('GroupsWriteWebService.Roles() User=%s, GroupId=%s, Request=%s', $this->server->GetSession()->UserId, $groupId, json_encode($request));

        $result = $this->controller->ChangeRoles($groupId, $request, $this->server->GetSession());

        if ($result->WasSuccessful()) {
            Log::Debug('GroupsWriteWebService.Roles() - Group Updated. GroupId=%s', $result->GroupId());

            $this->server->WriteResponse(new GroupUpdatedResponse($this->server, $result->GroupId()), RestResponse::CREATED_CODE);
        } else {
            Log::Debug('GroupsWriteWebService.Roles() - Update Failed.');

            $this->server->WriteResponse(new FailedResponse($result->Errors()), RestResponse::BAD_REQUEST_CODE);
        }
    }

    /**
     * @name ChangeGroupPermissions
     * @description Updates the permissions for an existing group
     * @response GroupUpdatedResponse
     * @param int $groupId
     * @return void
     */
    public function Permissions($groupId)
    {
        /** @var GroupPermissionsRequest $request */
        $request = $this->server->GetRequest();

        Log::Debug('GroupsWriteWebService.Permissions() User=%s, GroupId=%s, Request=%s', $this->server->GetSession()->UserId, $groupId, json_encode($request));

        $result = $this->controller->ChangePermissions($groupId, $request, $this->server->GetSession());

        if ($result->WasSuccessful()) {
            Log::Debug('GroupsWriteWebService.Permissions() - Group Updated. GroupId=%s', $result->GroupId());

            $this->server->WriteResponse(new GroupUpdatedResponse($this->server, $result->GroupId()), RestResponse::CREATED_CODE);
        } else {
            Log::Debug('GroupsWriteWebService.Permissions() - Update Failed.');

            $this->server->WriteResponse(new FailedResponse($result->Errors()), RestResponse::BAD_REQUEST_CODE);
        }
    }

    /**
     * @name ChangeGroupUsers
     * @description Updates the permissions for an existing group
     * @response GroupUpdatedResponse
     * @param int $groupId
     * @return void
     */
    public function Users($groupId)
    {
        /** @var GroupUsersRequest $request */
        $request = $this->server->GetRequest();

        Log::Debug('GroupsWriteWebService.Users() User=%s, GroupId=%s, Request=%s', $this->server->GetSession()->UserId, $groupId, json_encode($request));

        $result = $this->controller->ChangeUsers($groupId, $request, $this->server->GetSession());

        if ($result->WasSuccessful()) {
            Log::Debug('GroupsWriteWebService.Users() - Group Updated. GroupId=%s', $result->GroupId());

            $this->server->WriteResponse(new GroupUpdatedResponse($this->server, $result->GroupId()), RestResponse::CREATED_CODE);
        } else {
            Log::Debug('GroupsWriteWebService.Users() - Update Failed.');

            $this->server->WriteResponse(new FailedResponse($result->Errors()), RestResponse::BAD_REQUEST_CODE);
        }
    }
}
