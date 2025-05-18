<?php

class SignOutRequest
{
    /**
     * @param string $userId
     * @param string $sessionToken
     */
    public function __construct(public $userId = null, public $sessionToken = null)
    {
    }
}
