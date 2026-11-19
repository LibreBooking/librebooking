<?php

class AuthenticatedUser
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $fname;

    /**
     * @var string
     */
    private $lname;

    /**
     * @var string|null
     */
    private $password;

    /**
     * @var string
     */
    private $languageCode;

    /**
     * @var string
     */
    private $timezoneName;

    /**
     * @var string|null
     */
    private $phone;

    /**
     * @var string|null
     */
    private $organization;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string[]|null
     */
    private $groups = null;

    /**
     * @param string $username
     * @param string $email
     * @param string $fname
     * @param string $lname
     * @param string|null $password
     * @param string $languageCode
     * @param string $timezoneName
     * @param string|null $phone
     * @param string|null $organization
     * @param string|null $title
     * @param string[]|null $groups
     */
    public function __construct($username, $email, $fname, $lname, $password, $languageCode, $timezoneName, $phone, $organization, $title, $groups = null)
    {
        $this->username = $username;
        $this->email = $email;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->password = $password;
        $this->languageCode = empty($languageCode) ? Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE) : $languageCode;
        $this->timezoneName = $timezoneName;
        $this->phone = $phone;
        $this->organization = $organization;
        $this->title = $title;
        $this->groups = is_null($groups) ? [] : $groups;
        ;
    }

    public function Email(): ?string
    {
        return $this->EnsureNull($this->email);
    }

    public function FirstName(): ?string
    {
        return $this->EnsureNull($this->fname);
    }

    public function LanguageCode(): ?string
    {
        return $this->EnsureNull($this->languageCode);
    }

    public function LastName(): ?string
    {
        return $this->EnsureNull($this->lname);
    }

    public function Organization(): ?string
    {
        return $this->EnsureNull($this->organization);
    }

    /**
     * @return string|null
     */
    public function Password()
    {
        return $this->password;
    }

    public function Phone(): ?string
    {
        return $this->EnsureNull($this->phone);
    }

    /**
     * @return string
     */
    public function TimezoneName()
    {
        return $this->timezoneName;
    }

    public function Title(): ?string
    {
        return $this->EnsureNull($this->title);
    }

    /**
     * @return string
     */
    public function Username()
    {
        return $this->username;
    }

    private function EnsureNull(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $value = trim($value);
        if (empty($value)) {
            return null;
        }

        return trim($value);
    }

    /**
     * @return string[]|null
     */
    public function GetGroups()
    {
        return $this->groups;
    }
}
