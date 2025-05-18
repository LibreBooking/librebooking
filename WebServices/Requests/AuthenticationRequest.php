<?php

class AuthenticationRequest
{
    /**
     * @param string $username
     * @param string $password
     */
    public function __construct(public $username = null, public $password = null)
    {
    }
}
