<?php

require_once(ROOT_DIR . 'WebServices/Requests/Account/AccountRequestBase.php');
require_once(ROOT_DIR . 'WebServices/Requests/CustomAttributes/AttributeValueRequest.php');

class CreateAccountRequest extends AccountRequestBase
{
    public $password;
    public $acceptTermsOfService;

    public static function Example()
    {
        $request = new CreateAccountRequest();
        $request->firstName = 'FirstName';
        $request->lastName = 'LastName';
        $request->emailAddress = 'email@address.com';
        $request->userName = 'username';
        $request->timezone = Configuration::Instance()->GetDefaultTimezone();
        $request->language = Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE);
        $request->organization = 'organization';
        $request->phone = 'phone';
        $request->position = 'position';
        $request->customAttributes = [AttributeValueRequest::Example()];

        $request->password = 'plaintextpassword';
        $request->acceptTermsOfService = true;

        return $request;
    }
}
