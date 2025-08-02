<?php

require_once(ROOT_DIR . 'WebServices/Requests/Account/AccountRequestBase.php');

class UpdateAccountRequest extends AccountRequestBase
{
    public static function Example()
    {
        $request = new UpdateAccountRequest();
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

        return $request;
    }
}
