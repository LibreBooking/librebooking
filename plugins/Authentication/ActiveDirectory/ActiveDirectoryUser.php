<?php

class ActiveDirectoryUser
{
    private $fname;
    private $lname;
    private $mail;
    private $phone;
    private $institution;
    private $title;
    private $mapping;
    private $groups;

    /**
     * @param adLDAPUserCollection $entry
     * @param string[]|array $mapping
     * @param string[]|string|false|null $groups
     */
    public function __construct($entry, $mapping, $groups = null)
    {
        $this->mapping = $mapping;

        $this->fname = $this->Get($entry, 'givenname');
        $this->lname = $this->Get($entry, 'sn');
        $this->mail = strtolower($this->Get($entry, 'mail'));
        $this->phone = $this->Get($entry, 'telephonenumber');
        $this->institution = $this->Get($entry, 'physicaldeliveryofficename');
        $this->title = $this->Get($entry, 'title');

        $this->groups = null;
        if ($groups != null) {
            if (is_array($groups)) {
                $this->groups = $groups;
            } else {
                $this->groups = explode(',', $groups);
            }
        }
    }

    public function GetFirstName()
    {
        return $this->fname;
    }

    public function GetLastName()
    {
        return $this->lname;
    }

    public function GetEmail()
    {
        return $this->mail;
    }

    public function GetPhone()
    {
        return $this->phone;
    }

    public function GetInstitution()
    {
        return $this->institution;
    }

    public function GetTitle()
    {
        return $this->title;
    }

    /**
     * @return string[]|null
     */
    public function GetGroups()
    {
        return $this->groups;
    }

    public function __toString()
    {
        return sprintf(
            'ActiveDirectoryUser - First Name: %s, Last Name: %s, Email: %s, Phone: %s, Institution: %s, Title: %s',
            $this->fname,
            $this->lname,
            $this->mail,
            $this->phone,
            $this->institution,
            $this->title
        );
    }

    private function Get($entry, $field)
    {
        $actualField = $field;
        if (array_key_exists($field, $this->mapping)) {
            $actualField = $this->mapping[$field];
        }
        $value = $entry->$actualField;

        if (is_array($value)) {
            return $value[0];
        }

        return $value;
    }
}
