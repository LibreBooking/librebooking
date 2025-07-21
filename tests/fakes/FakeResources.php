<?php

class FakeResources extends Resources
{
    private $_dateFormats = [ResourceKeys::DATE_GENERAL => 'm/d/y',
            ResourceKeys::DATETIME_GENERAL => 'm/d/y h:i:s',
            ResourceKeys::DATETIME_SYSTEM => 'Y-m-d H:i:s',
            ResourceKeys::DATETIME_SHORT => 'Y-m-d'];

    public $_SetCurrentLanguageResult = true;

    public function __construct()
    {
    }

    public function GetString($key, $args = []): string
    {
        if (!is_array($args)) {
            $args = [$args];
        }

        $argstring = implode(',', $args);

        return $key . $argstring;
    }

    public function GetDateFormat($key)
    {
        if (array_key_exists($key, $this->_dateFormats)) {
            return $this->_dateFormats[$key];
        }
        return $key;
    }

    public function GetDays($key)
    {
        return $key;
    }

    public function GetMonths($key)
    {
        return $key;
    }

    public function SetDateFormat($key, $value)
    {
        $this->_dateFormats[$key] = $value;
    }

    public function SetLanguage($languageCode)
    {
        return $this->_SetCurrentLanguageResult;
    }
}
