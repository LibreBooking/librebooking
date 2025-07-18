<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class UserUpdatedResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $userId)
    {
        $this->AddService($server, WebServices::GetUser, [WebServiceParams::UserId => $this->userId]);
        $this->AddService($server, WebServices::UpdateUser, [WebServiceParams::UserId => $this->userId]);
    }

    public static function Example()
    {
        return new ExampleUserUpdatedResponse();
    }
}

class ExampleUserUpdatedResponse extends UserCreatedResponse
{
    public function __construct()
    {
        $this->AddLink('http://url/to/user', WebServices::GetUser);
        $this->AddLink('http://url/to/update/user', WebServices::UpdateUser);
    }
}
