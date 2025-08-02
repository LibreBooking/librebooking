<?php

require_once(ROOT_DIR . 'lib/WebService/JsonRequest.php');

abstract class AccountRequestBase extends JsonRequest
{
    public $firstName;
    public $lastName;
    public $emailAddress;
    public $userName;
    public $language;
    public $timezone;
    public $phone;
    public $organization;
    public $position;
    /** @var array|AttributeValueRequest[] */
    public $customAttributes = [];

    /**
     * @return string
     */
    public function GetTimezone()
    {
        if (empty($this->timezone)) {
            return Configuration::Instance()->GetDefaultTimezone();
        }

        return $this->timezone;
    }

    /**
     * @return string
     */
    public function GetLanguage()
    {
        if (empty($this->language)) {
            return Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE);
        }

        return $this->language;
    }

    /**
     * @return array|AttributeValueRequest[]
     */
    public function GetCustomAttributes()
    {
        if (!empty($this->customAttributes)) {
            return $this->customAttributes;
        }
        return [];
    }

    /**
     * @return array
     */
    public function GetAdditionalFields()
    {
        return ['phone' => $this->phone,
            'organization' => $this->organization,
            'position' => $this->position];
    }
}
