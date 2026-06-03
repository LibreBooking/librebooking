<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

class FakeGuestUserService implements IGuestUserService
{
    /**
     * @var UserSession
     */
    public $_UserSession;
    /**
     * @var bool
     */
    private $_EmailExists = false;

    public function CreateOrLoad($email)
    {
        return $this->_UserSession;
    }

    public function EmailExists($email)
    {
        return $this->_EmailExists;
    }
}
