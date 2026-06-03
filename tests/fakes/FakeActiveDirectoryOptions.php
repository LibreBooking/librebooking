<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'plugins/Authentication/ActiveDirectory/ActiveDirectoryOptions.php');

class FakeActiveDirectoryOptions extends ActiveDirectoryOptions
{
    public $_Options = [];
    public $_Hosts = [];
    public $_RetryAgainstDatabase = false;
    public $_SyncGroups = false;
    public $_CleanUsername = true;

    public function __construct()
    {
    }

    public function AdLdapOptions()
    {
        return $this->_Options;
    }

    public function Controllers()
    {
        return $this->_Hosts;
    }

    public function RetryAgainstDatabase()
    {
        return $this->_RetryAgainstDatabase;
    }

    public function SyncGroups()
    {
        return $this->_SyncGroups;
    }

    public function CleanUsername()
    {
        return $this->_CleanUsername;
    }
}
