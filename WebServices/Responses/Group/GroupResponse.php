<?php

class GroupResponse extends RestResponse
{
    public $id;
    public $name;
    public $adminGroup;
    public $permissions = [];
    public $viewPermissions = [];
    public $users = [];
    public $roles = [];
    public $isDefault = false;

    public function __construct(IRestServer $server, Group $group)
    {
        $this->id = $group->Id();
        $this->name = $group->Name();
        $adminId =  $group->AdminGroupId();
        if (!empty($adminId)) {
            $this->adminGroup = $server->GetServiceUrl(WebServices::GetGroup, [WebServiceParams::GroupId => $group->AdminGroupId()]);
        }

        foreach ($group->AllowedResourceIds() as $resourceId) {
            $this->permissions[] = $server->GetServiceUrl(WebServices::GetResource, [WebServiceParams::ResourceId => $resourceId]);
        }

        foreach ($group->AllowedViewResourceIds() as $resourceId) {
            $this->viewPermissions[] = $server->GetServiceUrl(WebServices::GetResource, [WebServiceParams::ResourceId => $resourceId]);
        }

        foreach ($group->UserIds() as $userId) {
            $this->users[] = $server->GetServiceUrl(WebServices::GetUser, [WebServiceParams::UserId => $userId]);
        }

        foreach ($group->RoleIds() as $roleId) {
            $this->roles[] = $roleId;
        }

        $this->isDefault = $group->IsDefault();
    }

    public static function Example()
    {
        return new ExampleGroupResponse();
    }
}

class ExampleGroupResponse extends GroupResponse
{
    public function __construct()
    {
        $this->id = 123;
        $this->name = 'group name';
        $this->adminGroup = 'http://url/to/group';
        $this->permissions = ['http://url/to/resource'];
        $this->viewPermissions = ['http://url/to/resource'];
        $this->users = ['http://url/to/user'];
        $this->roles = [1,2];
        $this->isDefault = true;
    }
}
