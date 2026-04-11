<?php

use Symfony\Component\Ldap\Ldap;

class SymfonyLdapWrapper
{
    /**
     * @var array<string, mixed>
     */
    private array $config = [];

    private ?Ldap $ldap = null;
    private ?LdapUser $user = null;

    public function __construct(private LdapOptions $options)
    {
    }

    public function Connect(): bool
    {
        Log::Debug('Trying to connect to LDAP');

        if (!class_exists(Ldap::class)) {
            throw new RuntimeException('The LDAP plugin requires symfony/ldap. Install it with: composer require symfony/ldap');
        }

        $this->config = $this->options->GetConnectionConfig();
        $connectionString = (string)($this->config['connectionString'] ?? '');

        try {
            // Let ext-ldap/libldap handle multi-URI failover from a single connection string.
            $this->ldap = Ldap::create(
                adapter: 'ext_ldap', // symfony only supports 'ext_ldap'
                config: $this->BuildAdapterConfig(uri: $connectionString, config: $this->config)
            );
            $this->ldap->bind(
                dn: (string)($this->config['binddn'] ?? ''),
                password: (string)($this->config['bindpw'] ?? '')
            );
            return true;
        } catch (Throwable $e) {
            $message = 'Could not connect to LDAP server. Check your settings in Ldap.config.php: ' . $e->getMessage();
            Log::Error($message);
            throw new Exception($message);
        }
    }

    /**
     * @param $username string
     * @param $password string
     * @param $filter string
     * @return bool
     */
    public function Authenticate(string $username, string $password, string $filter): bool
    {
        $this->user = null;
        if (!$this->LoadUser(username: $username, configFilter: $filter, password: $password)) {
            return false;
        }

        Log::Debug('Authentication was successful');
        return true;
    }

    /**
     * @param $username string
     * @param $configFilter string
     * @param $password string
     * @return bool
     */
    private function LoadUser(string $username, string $configFilter, string $password): bool
    {
        $this->user = null;
        $uidAttribute = $this->options->GetUserIdAttribute();
        $requiredGroup = $this->options->GetRequiredGroup();
        Log::Debug('LDAP - uid attribute: %s', $uidAttribute);

        $usernameFilter = sprintf(
            '(%s=%s)',
            $uidAttribute,
            ldap_escape(value: $username, ignore: '', flags: LDAP_ESCAPE_FILTER)
        );
        $filter = $usernameFilter;

        if ($configFilter) {
            $filter = sprintf('(&%s%s)', $usernameFilter, $this->NormalizeFilterExpression(filter: $configFilter));
        }

        $attributes = $this->options->Attributes();
        $loadGroups = !empty($requiredGroup) || $this->options->SyncGroups();
        if ($loadGroups) {
            $attributes[] = 'memberof';
        }

        Log::Debug('LDAP - Loading user attributes: %s', implode(', ', $attributes));

        // Symfony LDAP expects requested attributes in "filter".
        $queryOptions = ['filter' => $attributes];
        $scope = $this->config['scope'] ?? null;
        if (!empty($scope)) {
            $queryOptions['scope'] = $scope;
        }

        Log::Debug('Searching ldap for user %s', $username);
        try {
            $query = $this->ldap->query(
                dn: $this->options->BaseDn(),
                query: $filter,
                options: $queryOptions
            );
            $entries = $query->execute()->toArray();
        } catch (Throwable $e) {
            Log::Error('Could not search ldap for user %s: %s', $username, $e->getMessage());
            return false;
        }

        $count = count($entries);
        $currentResult = $count === 1 ? $entries[0] : null;

        if ($count == 1 && $currentResult !== null) {
            $userDn = $currentResult->getDn();
            Log::Debug('Trying to authenticate user %s against ldap with dn %s', $username, $userDn);

            try {
                // Rebind as the user before loading attributes to respect directory ACLs.
                $this->ldap->bind(dn: $userDn, password: $password);
            } catch (Throwable $e) {
                Log::Error('Could not authenticate user against ldap %s: %s', $username, $e->getMessage());
                return false;
            }

            try {
                // Reload attributes while bound as the authenticated user.
                $userQuery = $this->ldap->query(
                    dn: $userDn,
                    query: '(objectClass=*)',
                    options: [
                        'filter' => $attributes,
                        'scope' => 'base',
                    ]
                );
                $userEntries = $userQuery->execute()->toArray();
                if (count($userEntries) !== 1) {
                    Log::Error('Could not load user attributes for %s after successful bind', $username);
                    return false;
                }
                $currentResult = $userEntries[0];
            } catch (Throwable $e) {
                Log::Error('Could not load user attributes for %s: %s', $username, $e->getMessage());
                return false;
            }

            $userGroups = [];
            if ($loadGroups) {
                // LDAP attribute names are case-insensitive; use case-insensitive lookup.
                $userGroups = $currentResult->getAttribute(name: 'memberof', caseSensitive: false) ?? [];
                $userGroups = array_map('trim', $userGroups);
                $userGroups = array_map('strtolower', $userGroups);
            }

            Log::Debug('Found user %s', $username);

            if (!empty($requiredGroup)) {
                Log::Debug('LDAP - Required Group: %s', $requiredGroup);

                if (in_array(strtolower(trim($requiredGroup)), $userGroups)) {
                    Log::Debug('Matched Required Group %s', $requiredGroup);
                    $this->user = new LdapUser(
                        entry: $currentResult,
                        mapping: $this->options->AttributeMapping(),
                        userGroups: $userGroups
                    );
                    return !empty($this->user->GetEmail());
                } else {
                    Log::Error('Not in required group %s', $requiredGroup);
                    return false;
                }
            } else {
                $this->user = new LdapUser(
                    entry: $currentResult,
                    mapping: $this->options->AttributeMapping(),
                    userGroups: $userGroups
                );
                return !empty($this->user->GetEmail());
            }
        } else {
            Log::Error('Could not find user %s', $username);
            return false;
        }
    }

    /**
     * @param $username string
     * @return LdapUser|null
     */
    public function GetLdapUser(string $username): ?LdapUser
    {
        return $this->user;
    }

    /**
     * @param string $uri
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    private function BuildAdapterConfig(string $uri, array $config): array
    {
        $adapterConfig = [
            'connection_string' => $uri,
            'options' => [
                'protocol_version' => (int)($config['version'] ?? 3),
                'referrals' => false,
            ],
        ];

        if (!empty($config['starttls']) && str_starts_with($uri, 'ldap://')) {
            $adapterConfig['encryption'] = 'tls';
        }

        return $adapterConfig;
    }

    /**
     * @param string $filter
     * @return string
     */
    private function NormalizeFilterExpression(string $filter): string
    {
        $trimmed = trim($filter);
        if ($trimmed === '') {
            return '(objectClass=*)';
        }

        return str_starts_with($trimmed, '(') ? $trimmed : sprintf('(%s)', $trimmed);
    }
}
