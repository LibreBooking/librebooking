<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class AccountResponse extends RestResponse
{
    public $userId;
    public $firstName;
    public $lastName;
    public $emailAddress;
    public $userName;
    public $language;
    public $timezone;
    public $phone;
    public $organization;
    public $position;
    /** @var array|CustomAttributeResponse[] */
    public $customAttributes = [];
    public $icsUrl;

    public function __construct(IRestServer $server, User $user, IEntityAttributeList $attributeList)
    {
        $this->userId = $user->Id();
        $this->firstName = $user->FirstName();
        $this->lastName = $user->LastName();
        $this->userName = $user->Username();
        $this->phone = $user->GetAttribute(UserAttribute::Phone);
        $this->organization = $user->GetAttribute(UserAttribute::Organization);
        $this->position = $user->GetAttribute(UserAttribute::Position);
        $this->timezone = $user->Timezone();
        $this->language = $user->Language();
        $this->emailAddress = $user->EmailAddress();
        $attributeValues = $attributeList->GetAttributes($this->userId);

        $i = 0;
        foreach ($attributeValues as $av) {
            $this->customAttributes[] = new CustomAttributeResponse($server, $av->Id(), $av->Label(), $av->Value());
            $i++;
        }

        if ($user->GetIsCalendarSubscriptionAllowed()) {
            $url = new CalendarSubscriptionUrl($user->GetPublicId(), null, null);
            $this->icsUrl = $url->__toString();
        }

        $this->AddService($server, WebServices::GetAccount, [WebServiceParams::UserId, $this->userId]);
        $this->AddService($server, WebServices::UpdateAccount, [WebServiceParams::UserId, $this->userId]);
        $this->AddService($server, WebServices::UpdatePassword, [WebServiceParams::UserId, $this->userId]);
    }

    public static function Example()
    {
        return new ExampleAccountResponse();
    }
}

class ExampleAccountResponse extends AccountResponse
{
    public function __construct()
    {
        $this->emailAddress = 'email@address.com';
        $this->timezone = Configuration::Instance()->GetDefaultTimezone();
        $this->language = Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE);
        $this->userId = 1;
        $this->position = 'position';
        $this->organization = 'organization';
        $this->phone = 'phone';
        $this->lastName = 'last';
        $this->firstName = 'first';
        $this->userName = 'username';
        $this->customAttributes = [CustomAttributeResponse::Example()];
        $this->icsUrl = 'webcal://path-to-calendar';
    }
}
