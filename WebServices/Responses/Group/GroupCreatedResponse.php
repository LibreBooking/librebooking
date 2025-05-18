<?php

class GroupCreatedResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $groupId)
    {
        $this->message = 'The group was created';
        $this->AddService($server, WebServices::GetGroup, [WebServiceParams::GroupId => $this->groupId]);
        $this->AddService($server, WebServices::UpdateGroup, [WebServiceParams::GroupId => $this->groupId]);
        $this->AddService($server, WebServices::DeleteGroup, [WebServiceParams::GroupId => $this->groupId]);
    }

    public static function Example()
    {
        return new ExampleCustomAttributeCreatedResponse();
    }
}

class GroupUpdatedResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $groupId)
    {
        $this->message = 'The group was updated';
        $this->AddService($server, WebServices::GetGroup, [WebServiceParams::GroupId => $this->groupId]);
        $this->AddService($server, WebServices::UpdateGroup, [WebServiceParams::GroupId => $this->groupId]);
        $this->AddService($server, WebServices::DeleteGroup, [WebServiceParams::GroupId => $this->groupId]);
    }

    public static function Example()
    {
        return new ExampleGroupCreatedResponse();
    }
}

class ExampleGroupCreatedResponse extends GroupCreatedResponse
{
    public function __construct()
    {
        $this->groupId = 1;
        $this->AddLink('http://url/to/group', WebServices::GetGroup);
        $this->AddLink('http://url/to/update/group', WebServices::UpdateGroup);
        $this->AddLink('http://url/to/delete/group', WebServices::DeleteGroup);
    }
}
