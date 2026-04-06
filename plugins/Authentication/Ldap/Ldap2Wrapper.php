<?php

class Ldap2Wrapper
{
    /**
     * @var LdapOptions
     */
    private $options;

    /**
     * @var Net_LDAP2|null
     */
    private $ldap;

    /**
     * @var LdapUser|null
     */
    private $user;

    /**
     * @param LdapOptions $ldapOptions
     */
    public function __construct($ldapOptions)
    {
        $this->options = $ldapOptions;
        $this->user = null;
    }

    public function Connect()
    {
        Log::Debug('Trying to connect to LDAP');

        // pear/net_ldap2 emits iterator return-type deprecations on newer PHP versions.
        // The API runs under Slim, which promotes reported deprecations into exceptions,
        // so suppress them only while loading/connecting this legacy dependency.
        $previousErrorReporting = error_reporting();
        error_reporting($previousErrorReporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);

        try {
            if (!class_exists('Net_LDAP2')) {
                throw new RuntimeException('The LDAP plugin requires pear/net_ldap2. Install it with: composer require pear/net_ldap2');
            }

            $this->ldap = Net_LDAP2::connect($this->options->Ldap2Config());
        } finally {
            error_reporting($previousErrorReporting);
        }
        if (PEAR::isError($this->ldap)) {
            $message = 'Could not connect to LDAP server. Check your settings in Ldap.config.php : ' . $this->ldap->getMessage();
            Log::Error($message);
            throw new Exception($message);
        }

        $this->ldap->setOption(LDAP_OPT_REFERRALS, 0);
        $this->ldap->setOption(LDAP_OPT_PROTOCOL_VERSION, 3);
        return true;
    }

    /**
     * @param $username string
     * @param $password string
     * @param $filter string
     * @return bool
     */
    public function Authenticate($username, $password, $filter)
    {
        $this->user = null;
        $populated = $this->PopulateUser($username, $filter, $password);

        if ($this->user == null) {
            return false;
        }

        Log::Debug('Trying to authenticate user %s against ldap with dn %s', $username, $this->user->GetDn());

        $result = $this->ldap->bind($this->user->GetDn(), $password);
        if ($result === true) {
            Log::Debug('Authentication was successful');

            if (!$populated) {
                // PopulateUser should be split into two functions: one for the anonymous bind that takes the pieces from the config
                // and another one that has to be run after that the user authenticated with his own dn
                return $this->PopulateUser($username, $filter, $password);
            }
            return $populated;
        }

        if (PEAR::isError($result)) {
            $message = 'Could not authenticate user against ldap %s: ' . $result->getMessage();
            Log::Error($message, $username);
        }
        return false;
    }

    /**
     * @param $username string
     * @param $configFilter string
     * @param $password string
     * @return bool
     */
    private function PopulateUser($username, $configFilter, $password)
    {
        $this->user = null;
        $uidAttribute = $this->options->GetUserIdAttribute();
        $requiredGroup = $this->options->GetRequiredGroup();
        Log::Debug('LDAP - uid attribute: %s', $uidAttribute);

        $filter = Net_LDAP2_Filter::create($uidAttribute, 'equals', $username);

        if ($configFilter) {
            $configFilter = Net_LDAP2_Filter::parse($configFilter);
            if (PEAR::isError($configFilter)) {
                $message = 'Could not parse search filter %s: ' . $configFilter->getMessage();
                Log::Error($message, $username);
                return false;
            }
            $filter = Net_LDAP2_Filter::combine('and', [$filter, $configFilter]);
            if (PEAR::isError($filter)) {
                $message = 'Could not combine search filters for user %s: ' . $filter->getMessage();
                Log::Error($message, $username);
                return false;
            }
        }

        $attributes = $this->options->Attributes();
        $loadGroups = !empty($requiredGroup) || $this->options->SyncGroups();
        if ($loadGroups) {
            $attributes[] = 'memberof';
        }

        Log::Debug('LDAP - Loading user attributes: %s', implode(', ', $attributes));

        $options = ['attributes' => $attributes];

        Log::Debug('Searching ldap for user %s', $username);
        $searchResult = $this->ldap->search(null, $filter, $options);

        if (PEAR::isError($searchResult)) {
            $message = 'Could not search ldap for user %s: ' . $searchResult->getMessage();
            Log::Error($message, $username);
            return false;
        }

        $currentResult = $searchResult->current();

        if ($searchResult->count() == 1 && $currentResult !== false) {
            $result = $this->ldap->bind($currentResult->dn(), $password);

            if ($result !== true) {
                if (PEAR::isError($result)) {
                    Log::Error('Could not load user %s: %s', $username, $result->getMessage());
                } else {
                    Log::Error('Could not load user %s', $username);
                }
                return false;
            }

            $userGroups = [];
            if ($loadGroups) {
                $userGroups = $currentResult->getValue('memberof', 'all');
                $userGroups = array_map('trim', $userGroups);
                $userGroups = array_map('strtolower', $userGroups);
            }

            Log::Debug('Found user %s', $username);

            if (!empty($requiredGroup)) {
                Log::Debug('LDAP - Required Group: %s', $requiredGroup);

                if (in_array(strtolower(trim($requiredGroup)), $userGroups)) {
                    Log::Debug('Matched Required Group %s', $requiredGroup);
                    $this->user = new LdapUser($currentResult, $this->options->AttributeMapping(), $userGroups);
                    return !empty($this->user->GetEmail());
                } else {
                    Log::Error('Not in required group %s', $requiredGroup);
                    return false;
                }
            } else {
                /** @var Net_LDAP2_Entry $entry */
                $this->user = new LdapUser($currentResult, $this->options->AttributeMapping(), $userGroups);
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
    public function GetLdapUser($username)
    {
        return $this->user;
    }
}
