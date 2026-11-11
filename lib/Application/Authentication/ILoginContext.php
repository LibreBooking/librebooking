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
