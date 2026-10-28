<?php

class CookieKeys
{
    private function __construct()
    {
    }

    public const LANGUAGE = 'language';
    /**
     * No longer issued or read. Kept only so that logout can expire cookies set by earlier releases.
     */
    public const PERSIST_LOGIN = 'persist_login';
}
