<?php

class FullName
{
    /**
     * @var string
     */
    private $fullName;

    public function __construct($firstName, $lastName)
    {
        $formatter = Configuration::Instance()->GetKey(ConfigKeys::NAME_FORMAT);
        if (empty($formatter)) {
            $this->fullName = "$firstName $lastName";
        } else {
            $this->fullName = str_replace('{first}', $firstName ?? '', $formatter);
            $this->fullName = str_replace('{last}', $lastName ?? '', $this->fullName);
        }
    }

    public function __toString()
    {
        return $this->fullName;
    }
}
