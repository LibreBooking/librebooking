<?php

require_once(ROOT_DIR . '/lib/Config/namespace.php');

class LdapOptions
{
    private $_options = [];
    /**
     * @var array<string, mixed>
     */
    private $rawLdapSettings = [];

    public function __construct()
    {
        $configPath = dirname(__FILE__) . '/Ldap.config.php';
        if (!file_exists($configPath) && getenv('APP_ENV') === 'testing') {
            $configPath = dirname(__FILE__) . '/Ldap.config.dist.php';
        }

        Configuration::Instance()->Register(
            $configPath,
            '',
            LdapConfigKeys::CONFIG_ID,
            false,
            LdapConfigKeys::class
        );

        $allValues = Configuration::Instance()->File(LdapConfigKeys::CONFIG_ID)->GetValues();
        $this->rawLdapSettings = $allValues['ldap'] ?? [];
    }

    public function Ldap2Config()
    {
        $hosts = $this->GetUris();
        $this->SetOption('host', $hosts);
        $this->SetOption('starttls', $this->GetConfig(LdapConfigKeys::STARTTLS, new BooleanConverter()));
        $this->SetOption('version', $this->GetConfig(LdapConfigKeys::VERSION, new IntConverter()));
        $this->SetOption('binddn', $this->GetConfig(LdapConfigKeys::BINDDN));
        $this->SetOption('bindpw', $this->GetConfig(LdapConfigKeys::BINDPW));
        $this->SetOption('basedn', $this->GetConfig(LdapConfigKeys::BASEDN));
        $this->SetOption('filter', $this->GetConfig(LdapConfigKeys::FILTER));
        $this->SetOption('scope', $this->GetConfig(LdapConfigKeys::SCOPE));

        return $this->_options;
    }

    public function RetryAgainstDatabase()
    {
        return $this->GetConfig(LdapConfigKeys::RETRY_AGAINST_DATABASE, new BooleanConverter());
    }

    public function Controllers()
    {
        return $this->GetUris();
    }

    private function SetOption($key, $value)
    {
        if (empty($value)) {
            $value = null;
        }

        $this->_options[$key] = $value;
    }

    private function GetConfig($configDef, $converter = null)
    {
        return Configuration::Instance()->File(LdapConfigKeys::CONFIG_ID)->GetKey($configDef, $converter);
    }

    private function GetUris()
    {
        $this->AssertLegacyHostPortNotConfigured();

        $uriConfig = trim($this->GetConfig(LdapConfigKeys::URI));
        if (empty($uriConfig)) {
            throw new RuntimeException("LDAP setting 'uri' is required and must contain at least one ldap:// or ldaps:// URI.");
        }

        $uris = preg_split('/\s+/', $uriConfig) ?: [];
        foreach ($uris as $uri) {
            $scheme = parse_url($uri, PHP_URL_SCHEME);
            $host = parse_url($uri, PHP_URL_HOST);

            if (!in_array($scheme, ['ldap', 'ldaps'], true) || empty($host)) {
                throw new RuntimeException(
                    sprintf("Invalid LDAP URI '%s'. Use ldap:// or ldaps:// with a hostname. For multiple servers, separate URIs with spaces.", $uri)
                );
            }
        }

        return $uris;
    }

    private function AssertLegacyHostPortNotConfigured()
    {
        if (array_key_exists('host', $this->rawLdapSettings) || array_key_exists('port', $this->rawLdapSettings)) {
            throw new RuntimeException("LDAP settings 'host' and 'port' have been removed. Use only the 'uri' setting.");
        }
    }

    public function BaseDn()
    {
        $baseDnKey = LdapConfigKeys::BASEDN['key'];
        return $this->_options[$baseDnKey];
    }

    public function IsLdapDebugOn()
    {
        return $this->GetConfig(LdapConfigKeys::DEBUG_ENABLED, new BooleanConverter());
    }

    public function Attributes()
    {
        $attributes = $this->AttributeMapping();
        return array_values($attributes);
    }

    public function AttributeMapping()
    {
        $attributes = [
            'sn' => 'sn',
            'givenname' => 'givenname',
            'mail' => 'mail',
            'telephonenumber' => 'telephonenumber',
            'physicaldeliveryofficename' => 'physicaldeliveryofficename',
            'title' => 'title'
        ];
        $configValue = $this->GetConfig(LdapConfigKeys::ATTRIBUTE_MAPPING);

        if (!empty($configValue)) {
            $attributePairs = explode(',', $configValue);
            foreach ($attributePairs as $attributePair) {
                $pair = explode('=', trim($attributePair));
                $attributes[trim($pair[0])] = trim($pair[1]);
            }
        }

        return $attributes;
    }

    /**
     * @return string
     */
    public function GetUserIdAttribute()
    {
        $attribute = $this->GetConfig(LdapConfigKeys::USER_ID_ATTRIBUTE);

        if (empty($attribute)) {
            return 'uid';
        }

        return $attribute;
    }

    /**
     * @return string
     */
    public function GetRequiredGroup()
    {
        return $this->GetConfig(LdapConfigKeys::REQUIRED_GROUP);
    }

    /**
     * @return string
     */
    public function Filter()
    {
        return $this->GetConfig(LdapConfigKeys::FILTER);
    }

    /**
     * @return bool
     */
    public function SyncGroups()
    {
        return $this->GetConfig(LdapConfigKeys::SYNC_GROUPS, new BooleanConverter());
    }

    /**
     * @return bool
     */
    public function CleanUsername()
    {
        return !$this->GetConfig(LdapConfigKeys::PREVENT_CLEAN_USERNAME, new BooleanConverter());
    }
}
