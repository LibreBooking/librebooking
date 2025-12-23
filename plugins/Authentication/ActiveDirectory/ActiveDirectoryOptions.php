<?php

require_once(ROOT_DIR . '/lib/Config/namespace.php');

class ActiveDirectoryOptions
{
    private $_options = [];

    public function __construct()
    {
        $configPath = dirname(__FILE__) . '/ActiveDirectory.config.php';
        if (!file_exists($configPath) && getenv('APP_ENV') === 'testing') {
            $configPath = dirname(__FILE__) . '/ActiveDirectory.config.dist.php';
        }

        require_once($configPath);

        Configuration::Instance()->Register(
            $configPath,
            '',
            ActiveDirectoryConfigKeys::CONFIG_ID,
            false,
            ActiveDirectoryConfigKeys::class
        );
    }

    public function AdLdapOptions()
    {
        $hosts = $this->GetHosts();
        $this->SetOption('domain_controllers', $hosts);
        $this->SetOption('ad_port', $this->GetConfig(ActiveDirectoryConfigKeys::PORT, new IntConverter()));
        $this->SetOption('admin_username', $this->GetConfig(ActiveDirectoryConfigKeys::USERNAME));
        $this->SetOption('admin_password', $this->GetConfig(ActiveDirectoryConfigKeys::PASSWORD));
        $baseDn = $this->GetConfig(ActiveDirectoryConfigKeys::BASEDN);
        $this->SetOption('base_dn', empty($baseDn) ? null : $baseDn);
        $this->SetOption('use_ssl', $this->GetConfig(ActiveDirectoryConfigKeys::USE_SSL, new BooleanConverter()));
        $this->SetOption('account_suffix', $this->GetConfig(ActiveDirectoryConfigKeys::ACCOUNT_SUFFIX));
        $this->SetOption('ldap_version', $this->GetConfig(ActiveDirectoryConfigKeys::VERSION, new IntConverter()));
        $this->SetOption('sso', $this->GetConfig(ActiveDirectoryConfigKeys::USE_SSO, new BooleanConverter()));

        return $this->_options;
    }

    public function RetryAgainstDatabase()
    {
        return $this->GetConfig(ActiveDirectoryConfigKeys::RETRY_AGAINST_DATABASE, new BooleanConverter());
    }

    public function Controllers()
    {
        return $this->GetHosts();
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
        return Configuration::Instance()->File(ActiveDirectoryConfigKeys::CONFIG_ID)->GetKey($configDef, $converter);
    }

    /**
     * @return bool
     */
    public function SyncGroups()
    {
        return $this->GetConfig(ActiveDirectoryConfigKeys::SYNC_GROUPS, new BooleanConverter());
    }

    private function GetHosts()
    {
        $hosts = explode(',', $this->GetConfig(ActiveDirectoryConfigKeys::DOMAIN_CONTROLLERS));

        for ($i = 0; $i < count($hosts); $i++) {
            $hosts[$i] = trim($hosts[$i]);
        }

        return $hosts;
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
        $configValue = $this->GetConfig(ActiveDirectoryConfigKeys::ATTRIBUTE_MAPPING);

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
     * @return bool
     */
    public function HasRequiredGroups()
    {
        $groupList = $this->GetConfig(ActiveDirectoryConfigKeys::REQUIRED_GROUPS);
        return !empty($groupList);
    }

    /**
     * @return string[]
     */
    public function RequiredGroups()
    {
        $groupList = $this->GetConfig(ActiveDirectoryConfigKeys::REQUIRED_GROUPS);
        return explode(',', strtolower($groupList));
    }

    /**
     * @return bool
     */
    public function CleanUsername()
    {
        return !$this->GetConfig(ActiveDirectoryConfigKeys::PREVENT_CLEAN_USERNAME, new BooleanConverter());
    }
}
