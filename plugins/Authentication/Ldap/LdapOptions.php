<?php

require_once(ROOT_DIR . '/lib/Config/namespace.php');

class LdapOptions
{
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

    }

    /**
     * @return array<string, mixed>
     */
    public function GetConnectionConfig(): array
    {
        $connectionString = $this->GetValidatedConnectionString();

        return [
            'connectionString' => $connectionString,
            'starttls' => $this->GetConfig(LdapConfigKeys::STARTTLS, new BooleanConverter()),
            'version' => $this->GetConfig(LdapConfigKeys::VERSION, new IntConverter()),
            'binddn' => $this->GetConfig(LdapConfigKeys::BINDDN),
            'bindpw' => $this->GetConfig(LdapConfigKeys::BINDPW),
            'basedn' => $this->BaseDn(),
            'filter' => $this->GetConfig(LdapConfigKeys::FILTER),
            'scope' => $this->GetScope(),
        ];
    }

    public function RetryAgainstDatabase(): bool
    {
        return $this->GetConfig(LdapConfigKeys::RETRY_AGAINST_DATABASE, new BooleanConverter());
    }

    /**
     * @return string[]
     */
    public function Controllers(): array
    {
        return $this->SplitAndValidateUris($this->GetValidatedConnectionString());
    }

    /**
     * @param array<string, mixed> $configDef
     * @return mixed
     */
    private function GetConfig(array $configDef, $converter = null)
    {
        return Configuration::Instance()->File(LdapConfigKeys::CONFIG_ID)->GetKey($configDef, $converter);
    }

    /**
     * @return string
     */
    private function GetValidatedConnectionString(): string
    {
        $this->AssertLegacyHostPortNotConfigured();

        $uriConfig = trim($this->GetConfig(LdapConfigKeys::URI));
        if (empty($uriConfig)) {
            throw new RuntimeException("LDAP setting 'uri' is required and must contain at least one ldap:// or ldaps:// URI.");
        }

        $this->SplitAndValidateUris($uriConfig);

        return $uriConfig;
    }

    /**
     * @param string $connectionString
     * @return string[]
     */
    private function SplitAndValidateUris(string $connectionString): array
    {
        $uris = preg_split('/\s+/', trim($connectionString)) ?: [];
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

    private function AssertLegacyHostPortNotConfigured(): void
    {
        $legacyHost = trim($this->GetConfig([
            'key' => 'host',
            'section' => 'ldap',
            'type' => 'string'
        ]));
        $legacyPort = trim($this->GetConfig([
            'key' => 'port',
            'section' => 'ldap',
            'type' => 'string'
        ]));

        if (!empty($legacyHost) || !empty($legacyPort)) {
            throw new RuntimeException("LDAP settings 'host' and 'port' have been removed. Use only the 'uri' setting.");
        }
    }

    public function BaseDn(): string
    {
        return trim($this->GetConfig(LdapConfigKeys::BASEDN));
    }

    /**
     * @return string|null
     */
    public function GetScope(): ?string
    {
        $scope = trim($this->GetConfig(LdapConfigKeys::SCOPE));
        if ($scope === '') {
            return null;
        }

        return $scope;
    }

    public function IsLdapDebugOn(): bool
    {
        return $this->GetConfig(LdapConfigKeys::DEBUG_ENABLED, new BooleanConverter());
    }

    /**
     * @return string[]
     */
    public function Attributes(): array
    {
        $attributes = $this->AttributeMapping();
        return array_values($attributes);
    }

    /**
     * @return array<string, string>
     */
    public function AttributeMapping(): array
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
    public function GetUserIdAttribute(): string
    {
        $attribute = trim((string)$this->GetConfig(LdapConfigKeys::USER_ID_ATTRIBUTE));

        if (empty($attribute)) {
            return 'uid';
        }

        if (!$this->IsValidLdapAttributeName($attribute)) {
            throw new RuntimeException(sprintf("Invalid LDAP attribute name for user.id.attribute: '%s'", $attribute));
        }

        return $attribute;
    }

    /**
     * @return string
     */
    public function GetRequiredGroup(): string
    {
        return (string)$this->GetConfig(LdapConfigKeys::REQUIRED_GROUP);
    }

    /**
     * @return string
     */
    public function Filter(): string
    {
        return (string)$this->GetConfig(LdapConfigKeys::FILTER);
    }

    /**
     * @return bool
     */
    public function SyncGroups(): bool
    {
        return $this->GetConfig(LdapConfigKeys::SYNC_GROUPS, new BooleanConverter());
    }

    /**
     * @return bool
     */
    public function CleanUsername(): bool
    {
        return !$this->GetConfig(LdapConfigKeys::PREVENT_CLEAN_USERNAME, new BooleanConverter());
    }

    private function IsValidLdapAttributeName(string $attribute): bool
    {
        // Allow common attribute names (including underscore/dot variants) and OID notation.
        return preg_match('/^([A-Za-z][A-Za-z0-9_.;-]*|[0-9]+(?:\.[0-9]+)+)$/', $attribute) === 1;
    }
}
