<?php

declare(strict_types=1);

class FakeUser extends User
{
    public $_IsAdminForUser = false;
    public $_IsResourceAdmin = true;
    public $_ResourceAdminResourceIds = [];
    public $_WantsEmail;
    public $_Password;
    public $_Salt;
    public $_CurrentCredits;

    public function __construct($userId = null, $email = 'test@test.com')
    {
        $this->timezone = 'America/Chicago';
        $this->language = 'en_us';
        $this->emailAddress = $email;
        $this->id = $userId;
        $this->statusId = AccountStatus::ACTIVE;
        $this->homepageId = Pages::CALENDAR;
        $this->preferences = new UserPreferences();
    }

    public function SetStatus($statusId)
    {
        $this->statusId = $statusId;
    }

    public function SetLanguage($language)
    {
        $this->language = $language;
    }

    public function SetTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * @param $groups array|UserGroup[]
     * @return void
     */
    public function SetGroups($groups)
    {
        $this->groups = $groups;
    }

    public function WithPublicId($publicId)
    {
        $this->SetPublicId($publicId);
    }

    public function _SetIsAdminForUser($isAdminForUser)
    {
        $this->_IsAdminForUser = $isAdminForUser;
    }

    public function IsAdminFor(User $user)
    {
        return $this->_IsAdminForUser;
    }

    public function IsResourceAdminFor(IResource $resource)
    {
        return $this->_IsResourceAdmin || in_array($resource->GetId(), $this->_ResourceAdminResourceIds);
    }

    public function WantsEventEmail(IDomainEvent $event)
    {
        return $this->_WantsEmail;
    }

    public function ChangePassword($encryptedPassword, $salt)
    {
        $this->_Password = $encryptedPassword;
        $this->_Salt = $salt;
    }
}
