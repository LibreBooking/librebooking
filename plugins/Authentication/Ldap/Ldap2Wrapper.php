<?php

require_once(ROOT_DIR . 'plugins/Authentication/Ldap/LDAP2.php');

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

        $this->ldap = Net_LDAP2::connect($this->options->Ldap2Config());
        $p = new Pear();
        if ($p->isError($this->ldap)) {
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

        $l = new Net_LDAP2();
        if ($l->isError($result)) {
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
        $uidAttribute = $this->options->GetUserIdAttribute();
        $requiredGroup = $this->options->GetRequiredGroup();
        Log::Debug('LDAP - uid attribute: %s', $uidAttribute);

        $filter = Net_LDAP2_Filter::create($uidAttribute, 'equals', $username);

        $l = new Net_LDAP2();
        if ($configFilter) {
            $configFilter = Net_LDAP2_Filter::parse($configFilter);
            if ($l->isError($configFilter)) {
                $message = 'Could not parse search filter %s: ' . $configFilter->getMessage();
                Log::Error($message, $username);
            }
            $filter = Net_LDAP2_Filter::combine('and', [$filter, $configFilter]);
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

        if ($l->isError($searchResult)) {
            $message = 'Could not search ldap for user %s: ' . $searchResult->getMessage();
            Log::Error($message, $username);
        }

        $currentResult = $searchResult->current();

        if ($searchResult->count() == 1 && $currentResult !== false) {
            $result = $this->ldap->bind($currentResult->dn(), $password);

            if (!$result) {
                Log::Error('Could not load user %s', $username);
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
