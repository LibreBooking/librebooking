<?php

class UserSession
{
    public $UserId = '';
    public $FirstName = '';
    public $LastName = '';
    public $Email = '';
    public $Timezone = '';
    public $HomepageId = 1;
    public $IsAdmin = false;
    public $IsGroupAdmin = false;
    public $IsResourceAdmin = false;
    public $IsScheduleAdmin = false;
    public $LanguageCode = '';
    public $PublicId = '';
    public $LoginTime = '';
    public $ScheduleId = '';
    public $Groups = [];
    public $AdminGroups = [];
    public $CSRFToken = '';

    public function __construct($id)
    {
        $this->UserId = $id;
    }

    public function IsLoggedIn()
    {
        return true;
    }

    public function IsGuest()
    {
        return false;
    }

    public function IsAdminForGroup($groupIds = [])
    {
        if (!is_array($groupIds)) {
            $groupIds = [$groupIds];
        }

        if ($this->IsAdmin) {
            return true;
        }

        if (!$this->IsGroupAdmin) {
            return false;
        }

        foreach ($groupIds as $groupId) {
            if (in_array($groupId, $this->AdminGroups)) {
                return true;
            }
        }

        return false;
    }

    public function __toString()
    {
        return "{$this->FirstName} {$this->LastName} ({$this->Email})";
    }

    public function FullName()
    {
        return new FullName($this->FirstName, $this->LastName);
    }
}

class NullUserSession extends UserSession
{
    public function __construct()
    {
        parent::__construct(0);
        $this->Timezone = Configuration::Instance()->GetDefaultTimezone();
    }

    public function IsLoggedIn()
    {
        return false;
    }

    public function IsGuest()
    {
        return true;
    }
}
