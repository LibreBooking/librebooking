<?php

class SignOutRequest
{
    /**
     * @param int|string|null $userId
     * @param string|null $sessionToken
     */
    public function __construct(public $userId = null, public $sessionToken = null)
    {
    }

    public static function Example()
    {
        return new self(
            userId: 123,
            sessionToken: 'your-session-token'
        );
    }
}
