<?php

require_once(ROOT_DIR . '/lib/Config/namespace.php');

class MellonOptions
{
    public function __construct()
    {
        Configuration::Instance()->Register(dirname(__FILE__) . '/Mellon.config.php', '', MellonConfigKeys::CONFIG_ID, false, MellonConfigKeys::class);
    }

    private function GetConfig($configDef, $converter = null)
    {
        return Configuration::Instance()->File(MellonConfigKeys::CONFIG_ID)->GetKey($configDef, $converter);
    }

    public function EmailDomain()
    {
        return $this->GetConfig(MellonConfigKeys::EMAIL_DOMAIN);
    }

    public function KeyGivenName()
    {
        return $this->GetConfig(MellonConfigKeys::KEY_GIVEN_NAME);
    }

    public function KeySurname()
    {
        return $this->GetConfig(MellonConfigKeys::KEY_SURNAME);
    }

    public function KeyGroups()
    {
        return $this->GetConfig(MellonConfigKeys::KEY_GROUPS);
    }

    public function GroupMappings()
    {
        $configValue = $this->GetConfig(MellonConfigKeys::GROUP_MAPPINGS);
        $mappings = [];
        if (!empty($configValue)) {
            $mappingPairs = explode(',', $configValue);
            foreach ($mappingPairs as $map) {
                $pair = explode('=', trim($map));
                $mappings[trim($pair[1])] = trim($pair[0]);
            }
        }

        return $mappings;
    }
}
