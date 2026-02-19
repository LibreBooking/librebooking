<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

class FakeActivation implements IAccountActivation
{
    /**
     * @var bool
     */
    public $_NotifyCalled = false;

    /**
     * @var User
     */
    public $_NotifiedUser;

    /**
     * @var string
     */
    public $_LastActivationCode;

    /**
     * @var ActivationResult
     */
    public $_ActivationResult;

    public function Notify(User $user)
    {
        $this->_NotifyCalled = true;
        $this->_NotifiedUser = $user;
    }

    /**
     * @param string $activationCode
     * @return ActivationResult
     */
    public function Activate($activationCode)
    {
        $this->_LastActivationCode = $activationCode;
        return $this->_ActivationResult;
    }
}
