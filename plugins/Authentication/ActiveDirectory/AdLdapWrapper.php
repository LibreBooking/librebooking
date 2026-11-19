<?php

require_once(ROOT_DIR . 'plugins/Authentication/ActiveDirectory/adLDAP.php');

class AdLdapWrapper implements IActiveDirectory
{
    /**
     * @var ActiveDirectoryOptions
     */
    private $options;

    /**
     * @var adLDAP|null
     */
    private $ldap;

    /**
     * @param ActiveDirectoryOptions $ldapOptions
     */
    public function __construct($ldapOptions)
    {
        $this->options = $ldapOptions;
    }

    public function Connect()
    {
        $connected = false;
        $attempts = 0;
        $hosts = $this->options->Controllers();
        $options = $this->options->AdLdapOptions();

        while (!$connected && $attempts < count($hosts)) {
            try {
                $host = $hosts[$attempts];
                Log::Debug('ActiveDirectory - Trying to connect to host %s', $host);
                $options['domain_controllers'] = [$host];
                $attempts++;
                $this->ldap = new adLDAP($options);
                $connected = true;

                Log::Debug('ActiveDirectory - Connection succeeded to host %s', $host);
            } catch (Exception $ex) {
                Log::Error($ex);
                throw($ex);
            }
        }

        return $connected;
    }

    public function Authenticate($username, $password)
    {
        $authenticated = $this->ldap->user()->authenticate($username, $password);
        if (!$authenticated) {
            Log::Debug(
                'ActiveDirectory - Authenticate for user %s failed with reason %s',
                $username,
                $this->ldap->getLastError()
            );
        }

        if ($authenticated) {
            Log::Debug('ActiveDirectory - Authenticate for user %s was successful', $username);

            if ($this->options->HasRequiredGroups()) {
                $groups = $this->ldap->user()->groups($username);
                $groupList = [];
                if (!empty($groups) && !is_array($groups)) {
                    $groupList = explode(',', strtolower($groups));
                }
                if (is_array($groups)) {
                    $groupList = $groups;
                }

                $requiredGroups = $this->options->RequiredGroups();
                $authenticated = false;

                foreach ($groupList as $groupName) {
                    if (in_array(strtolower($groupName), $requiredGroups)) {
                        return true;
                    }
                }

                Log::Debug('ActiveDirectory - Authenticate for user %s failed because user was not in the required groups', $username);
            }
        }

        return $authenticated;
    }

    public function GetLdapUser($username)
    {
        $attributes = $this->options->Attributes();
        Log::Debug('ActiveDirectory - Loading user attributes: %s', implode(', ', $attributes));
        $entries = $this->ldap->user()->infoCollection($username, $attributes);

        /** @var adLDAPUserCollection $entries */
        if ($entries && is_a($entries, 'adLDAPUserCollection')) {
            $groups = null;
            if ($this->options->SyncGroups()) {
                $groups = $this->ldap->user()->groups($username);
            }
            return new ActiveDirectoryUser($entries, $this->options->AttributeMapping(), $groups);
        } else {
            Log::Debug(
                'ActiveDirectory - Could not load user details for user %s. Reason %s',
                $username,
                $this->ldap->getLastError()
            );
        }

        return null;
    }
}
