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

    public static function Example()
    {
        return new self(
            username: 'your-username',
            password: 'your-password'
        );
    }
}
