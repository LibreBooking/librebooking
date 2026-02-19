<?php

declare(strict_types=1);

class FakeUserSession extends UserSession
{
    public function __construct($isAdmin = false, $timezone = 'America/New_York', $userId = 1)
    {
        parent::__construct($userId);

        $this->FirstName = 'first';
        $this->LastName = 'last';
        $this->Email = 'first.last@email.com';
        $this->IsAdmin = $isAdmin;
        $this->Timezone = $timezone;
        $this->HomepageId = 1;
        $this->IsGroupAdmin = false;
        $this->IsResourceAdmin = true;
        $this->IsScheduleAdmin = false;
        $this->LanguageCode = 'en_US';
        $this->PublicId = 'public id';
        $this->ScheduleId = 19;
    }
}

class FakeWebServiceUserSession extends WebServiceUserSession
{
    public $_SessionExtended = false;
    public $_IsExpired = false;

    public function __construct($id)
    {
        parent::__construct($id);
        $this->Timezone = 'America/Chicago';
    }

    public function ExtendSession()
    {
        $this->_SessionExtended = true;
    }

    public function IsExpired()
    {
        return $this->_IsExpired;
    }
}
