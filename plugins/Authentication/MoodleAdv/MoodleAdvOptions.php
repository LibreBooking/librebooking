<?php

require_once(ROOT_DIR . '/lib/Config/namespace.php');

class MoodleAdvOptions
{
    public function __construct()
    {
        Configuration::Instance()->Register(
            dirname(__FILE__) . '/MoodleAdv.config.php',
            dirname(__FILE__) . '/.env',
            MoodleAdvConfigKeys::CONFIG_ID,
            false,
            MoodleAdvConfigKeys::class
        );

        Log::Debug('MoodleAdv authentication plugin - options loaded');
    }

    public function GetDbHost()
    {
        return $this->GetConfig(MoodleAdvConfigKeys::DB_HOST);
    }

    public function GetDbName()
    {
        return $this->GetConfig(MoodleAdvConfigKeys::DB_NAME);
    }

    public function GetDbUser()
    {
        return $this->GetConfig(MoodleAdvConfigKeys::DB_USER);
    }

    public function GetDbPass()
    {
        return $this->GetConfig(MoodleAdvConfigKeys::DB_PASS);
    }

    public function GetTablePrefix()
    {
        return $this->GetConfig(MoodleAdvConfigKeys::DB_PREFIX);
    }

    public function GetAuthMethod()
    {
        return $this->GetConfig(MoodleAdvConfigKeys::AUTH_METHOD);
    }

    public function GetRoles()
    {
        $roles = $this->GetConfig(MoodleAdvConfigKeys::ROLES);
        return $roles ? explode(',', $roles) : [];
    }

    public function GetField()
    {
        return $this->GetConfig(MoodleAdvConfigKeys::FIELD);
    }

    private function GetConfig($configDef, $converter = null)
    {
        $keyName = is_array($configDef) ? $configDef['key'] : $configDef;
        return Configuration::Instance()->File(MoodleAdvConfigKeys::CONFIG_ID)->GetKey($keyName, $converter);
    }
}
