<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class AccountActionResponse extends RestResponse
{
    public function __construct(IRestServer $server, public $userId)
    {
        $this->AddService($server, WebServices::GetAccount, [WebServiceParams::UserId => $this->userId]);
        $this->AddService($server, WebServices::UpdateAccount, [WebServiceParams::UserId => $this->userId]);
    }

    public static function Example()
    {
        return new ExampleAccountActionResponse();
    }
}

class ExampleAccountActionResponse extends AccountActionResponse
{
    public function __construct()
    {
        $this->AddLink('http://url/to/account', WebServices::GetAccount);
        $this->AddLink('http://url/to/update/account', WebServices::UpdateAccount);
    }
}
