<?php
/**
 * @file ShibbolethUser.php
 */

/**
 * Represents an external user and its attributes.
 *
 * @class ShibbolethUser
 */
class ShibbolethUser
{
    /**
     * The username.
     * @var string
     */
    private $_username;

    /**
     * The user's first name.
     * @var string
     */
    private $_firstname;

    /**
     * The user's last name.
     * @var string
     */
    private $_lastname;

    /**
     * The user's email address.
     * @var string
     */
    private $_email;

    /**
     * The user's phone number.
     * @var string
     */
    private $_phone;


    /**
     * The user's organization.
     * @var string
     */
    private $_organization;

    /**
     * Constructor.
     * Populates the object's members with given values.
     *
     * @param array $values A map of user attributes.
     * @param ShibbolethOptions $options The plugin configuration.
     */
    public function __construct(array $values, ShibbolethOptions $options)
    {
        $config = $options->GetShibbolethOptions();
        $this->_username = $this->GetValue($values, $config[ShibbolethConfigKeys::USERNAME['key']] ?? null);
        $this->_firstname = $this->GetValue($values, $config[ShibbolethConfigKeys::FIRSTNAME['key']] ?? null);
        $this->_lastname = $this->GetValue($values, $config[ShibbolethConfigKeys::LASTNAME['key']] ?? null);
        $this->_email = $this->GetValue($values, $config[ShibbolethConfigKeys::EMAIL['key']] ?? null);
        $this->_phone = $this->GetValue($values, $config[ShibbolethConfigKeys::PHONE['key']] ?? null);
        $this->_organization = $this->GetValue($values, $config[ShibbolethConfigKeys::ORGANIZATION['key']] ?? null);
    }

    /**
     * Retrieves the username.
     * @return string|null
     */
    public function GetUsername()
    {
        return $this->_username;
    }

    /**
     * Retrieve's the user's first name.
     * @return string|null
     */
    public function GetFirstName()
    {
        return $this->_firstname;
    }

    /**
     * Retrieves the user's last name.
     * @return string|null
     */
    public function GetLastName()
    {
        return $this->_lastname;
    }

    /**
     * Retrieves the user's email address.
     * @return string|null
     */
    public function GetEmailAddress()
    {
        return $this->_email;
    }

    /**
     * Retrieves the user's phone number.
     * @return string|null
     */
    public function GetPhone()
    {
        return $this->_phone;
    }

    /**
     * Retrieves the user's organization.
     * @return string|null
     */
    public function GetOrganization()
    {
        return $this->_organization;
    }

    /**
     * Retrieves a value from a given map by a given key.
     *
     * @param array $values A map of key/value pairs.
     * @param string $key The key.
     * @return string|null The value, or NULL if none was found.
     */
    protected function GetValue(array $values, $key)
    {
        $value = null;
        if (array_key_exists($key, $values)) {
            $value = $values[$key];
        }
        return $value;
    }
}
