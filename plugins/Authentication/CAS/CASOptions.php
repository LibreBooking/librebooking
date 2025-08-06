<?php

require_once(ROOT_DIR . '/lib/Config/namespace.php');

class CASOptions
{
    public function __construct()
    {
        Configuration::Instance()->Register(
            dirname(__FILE__) . '/CAS.config.php',
            dirname(__FILE__) . '/.env',
            CASConfigKeys::CONFIG_ID,
            false,
            CASConfigKeys::class
        );
    }

    private function GetConfig($configDef, $converter = null)
    {
        return Configuration::Instance()->File(CASConfigKeys::CONFIG_ID)->GetKey($configDef, $converter);
    }

    public function IsCasDebugOn()
    {
        return $this->GetConfig(CASConfigKeys::CAS_DEBUG_ENABLED, new BooleanConverter());
    }

    public function HasCertificate()
    {
        $cert = $this->Certificate();
        return !empty($cert);
    }

    public function Certificate()
    {
        return $this->GetConfig(CASConfigKeys::CAS_CERTIFICATES);
    }

    public function CasVersion()
    {
        return $this->GetConfig(CASConfigKeys::CAS_VERSION);
    }

    public function HostName()
    {
        return $this->GetConfig(CASConfigKeys::CAS_SERVER_HOSTNAME);
    }

    public function Port()
    {
        return $this->GetConfig(CASConfigKeys::CAS_PORT, new IntConverter());
    }

    public function ServerUri()
    {
        return $this->GetConfig(CASConfigKeys::CAS_SERVER_URI);
    }

    public function DebugFile()
    {
        return $this->GetConfig(CASConfigKeys::DEBUG_FILE);
    }

    public function ChangeSessionId()
    {
        return $this->GetConfig(CASConfigKeys::CAS_CHANGESESSIONID, new BooleanConverter());
    }

    public function CasHandlesLogouts()
    {
        $servers = $this->LogoutServers();
        return !empty($servers);
    }

    public function LogoutServers()
    {
        $servers = $this->GetConfig(CASConfigKeys::CAS_LOGOUT_SERVERS);

        if (empty($servers)) {
            return [];
        }

        $servers = explode(',', $servers);

        for ($i = 0; $i < count($servers); $i++) {
            $servers[$i] = trim($servers[$i]);
        }

        return $servers;
    }

    public function EmailSuffix()
    {
        return $this->GetConfig(CASConfigKeys::EMAIL_SUFFIX);
    }

    public function AttributeMapping()
    {
        $attributes = [
            'surName' => 'sn',
            'givenName' => 'givenname',
            'email' => 'mail',
            'groups' => 'Role'
        ];
        $configValue = $this->GetConfig(CASConfigKeys::ATTRIBUTE_MAPPING);

        if (!empty($configValue)) {
            $attributePairs = explode(',', $configValue);
            foreach ($attributePairs as $attributePair) {
                $pair = explode('=', trim($attributePair));
                if (count($pair) === 2) {
                    $attributes[trim($pair[0])] = trim($pair[1]);
                }else {
                    Log::Debug("Invalid attribute mapping pair: %s", $attributePair);
                }
            }
        }

        return $attributes;
    }
}
