<?php

use Symfony\Component\Ldap\Entry;

class LdapUser
{
    private string $fname;
    private string $lname;
    private string $mail;
    private string $phone;
    private string $institution;
    private string $title;
    private string $dn;
    /**
     * @var array<string, string>
     */
    private array $mapping;
    /**
     * @var string[]
     */
    private array $groups;

    /**
     * @param array<string, string> $mapping
     * @param string[] $userGroups
     */
    public function __construct(Entry $entry, array $mapping, array $userGroups = [])
    {
        $this->mapping = $mapping;
        $this->fname = $this->Get($entry, 'givenname');
        $this->lname = $this->Get($entry, 'sn');
        $this->mail = strtolower($this->Get($entry, 'mail'));
        $this->phone = $this->Get($entry, 'telephonenumber');
        $this->institution = $this->Get($entry, 'physicaldeliveryofficename');
        $this->title = $this->Get($entry, 'title');
        $this->dn = $this->ReadDn($entry);
        $this->groups = $userGroups;
    }

    public function GetFirstName(): string
    {
        return $this->fname;
    }

    public function GetLastName(): string
    {
        return $this->lname;
    }

    public function GetEmail(): string
    {
        return $this->mail;
    }

    public function GetPhone(): string
    {
        return $this->phone;
    }

    public function GetInstitution(): string
    {
        return $this->institution;
    }

    public function GetTitle(): string
    {
        return $this->title;
    }

    public function GetDn(): string
    {
        return $this->dn;
    }

    public function GetGroups(): array
    {
        return $this->groups;
    }

    private function Get(Entry $entry, string $field): string
    {
        $actualField = $field;
        if (array_key_exists($field, $this->mapping)) {
            $actualField = $this->mapping[$field];
        }
        // LDAP attribute names are case-insensitive; use case-insensitive lookup.
        $values = $entry->getAttribute(name: $actualField, caseSensitive: false);
        if (is_array($values) && !empty($values)) {
            return (string)$values[0];
        }

        return '';
    }

    private function ReadDn(Entry $entry): string
    {
        return $entry->getDn();
    }
}
