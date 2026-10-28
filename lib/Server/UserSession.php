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
    // Also prevents services from deriving administrative permissions from stored roles.
    public bool $AllowAdminPrivileges = true;

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

    /**
     * Keeps booking ownership and ordinary permissions without granting admin exemptions.
     * Returns a copy so a kiosk cannot change an authenticated browser session.
     */
    public function WithoutAdminPrivileges(): UserSession
    {
        $session = clone $this;
        $session->IsAdmin = false;
        $session->IsGroupAdmin = false;
        $session->IsResourceAdmin = false;
        $session->IsScheduleAdmin = false;
        $session->AdminGroups = [];
        $session->AllowAdminPrivileges = false;

        return $session;
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
