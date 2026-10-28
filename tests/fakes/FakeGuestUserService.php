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

    public $_CreateOrLoadCalled = false;
    public $_LastCreateOrLoadEmail = null;

    public function CreateOrLoad($email)
    {
        $this->_CreateOrLoadCalled = true;
        $this->_LastCreateOrLoadEmail = $email;

        return $this->_UserSession;
    }

    public function EmailExists($email)
    {
        return $this->_EmailExists;
    }
}
