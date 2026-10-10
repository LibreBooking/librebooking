<?php

interface ILoginContext
{
    /**
     * @abstract
     * @return LoginData
     */
    public function GetData();
}

class LoginData
{
    /**
     * @deprecated Persistent login was removed. The value is no longer used.
     * @var bool
     */
    public $Persist;

    /**
     * @var string
     */
    public $Language;

    public function __construct($persist = false, $language = '')
    {
        $this->Persist = $persist;
        $this->Language = $language;
    }
}
